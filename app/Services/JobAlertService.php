<?php

namespace App\Services;

use App\Models\JobAlertModel;
use App\Models\JobModel;
use App\Models\JobSeekerModel;
use App\Models\UserModel;

class JobAlertService
{
    protected $alertModel;
    protected $jobModel;

    public function __construct()
    {
        $this->alertModel = model(JobAlertModel::class);
        $this->jobModel   = model(JobModel::class);
    }

    /**
     * Process scheduled alerts for a given frequency (daily, weekly, monthly)
     */
    public function processAlerts(string $frequency = 'daily'): int
    {
        $nowDateTime = date('Y-m-d H:i:s');
        $nowDate     = date('Y-m-d');

        $alerts = $this->alertModel
            ->where('frequency', $frequency)
            ->where('is_active', 1)
            ->groupStart()
                ->where('is_paused', 0)
                ->orWhere('is_paused IS NULL')
            ->groupEnd()
            ->groupStart()
                ->where('snooze_until IS NULL')
                ->orWhere('snooze_until <=', $nowDateTime)
            ->groupEnd()
            ->groupStart()
                ->where('last_sent_at IS NULL')
                ->orWhere('DATE(last_sent_at) <', $nowDate)
            ->groupEnd()
            ->findAll();

        $sentCount = 0;

        foreach ($alerts as $alert) {
            $jobs = $this->findMatchingJobs($alert);

            if (empty($jobs)) {
                continue;
            }

            $this->deliverAlert($alert, $jobs);

            $this->alertModel->update($alert->id, [
                'last_sent_at' => $nowDateTime
            ]);

            $sentCount++;
        }

        return $sentCount;
    }

    /**
     * Instantly match and notify candidates when a new job is posted / published / approved
     */
    public function sendImmediateMatchAlerts(int $jobId): int
    {
        $db = db_connect();
        $job = $db->table('jobs j')
            ->select('j.*, e.company_name, e.logo as company_logo, s.name as state_name, c.name as category_name')
            ->join('employers e', 'e.id = j.employer_id', 'left')
            ->join('states s', 's.id = j.state_id', 'left')
            ->join('job_categories c', 'c.id = j.category_id', 'left')
            ->where('j.id', $jobId)
            ->get()->getFirstRow();

        if (!$job || $job->status !== 'open') {
            return 0;
        }

        $nowDateTime = date('Y-m-d H:i:s');
        $isPremium = (!empty($job->is_featured) || !empty($job->is_urgent));

        // Find active alerts
        $alerts = $this->alertModel
            ->where('is_active', 1)
            ->groupStart()
                ->where('is_paused', 0)
                ->orWhere('is_paused IS NULL')
            ->groupEnd()
            ->groupStart()
                ->where('snooze_until IS NULL')
                ->orWhere('snooze_until <=', $nowDateTime)
            ->groupEnd()
            ->findAll();

        $sent = 0;
        $candidateModel = model(JobSeekerModel::class);

        foreach ($alerts as $alert) {
            // Anti-spam rule for weekly digest option:
            // If the job is a premium job, candidates who have selected weekly digest
            // (frequency == 'weekly') should not receive redundant per-job individual emails
            // for every premium job; they receive them collected in the weekly digest.
            if ($isPremium && ($alert->frequency === 'weekly')) {
                continue;
            }

            $match = true;

            if (!empty($alert->keyword)) {
                $kw = strtolower(trim($alert->keyword));
                $titleMatch = (stripos($job->title ?? '', $kw) !== false);
                $descMatch  = (stripos($job->description ?? '', $kw) !== false);
                $catMatch   = (stripos($job->category_name ?? '', $kw) !== false);
                $skillMatch = (stripos($job->skills ?? '', $kw) !== false);
                if (!$titleMatch && !$descMatch && !$catMatch && !$skillMatch) {
                    $match = false;
                }
            }

            if ($match && !empty($alert->location_id)) {
                if (($job->state_id ?? null) != $alert->location_id) {
                    $match = false;
                }
            }

            if ($match) {
                $candidate = $candidateModel->find($alert->job_seeker_id);
                if ($candidate && !empty($candidate->email) && ($candidate->notify_job_alerts ?? 1)) {
                    $this->deliverAlert($alert, [$job]);
                    $this->alertModel->update($alert->id, [
                        'last_sent_at' => $nowDateTime
                    ]);
                    $sent++;
                }
            }
        }

        return $sent;
    }

    /**
     * Send Weekly Premium Job Digest to candidates
     * Collects premium/featured jobs posted Monday–Friday
     */
    public function sendWeeklyPremiumDigest(): array
    {
        $db = db_connect();

        // Calculate Monday 00:00:00 to Friday 23:59:59 of this/recent week
        $monday = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $friday = date('Y-m-d 23:59:59', strtotime('friday this week'));

        // If running on a Monday morning, grab previous week's Mon-Fri
        if (date('N') == 1) {
            $monday = date('Y-m-d 00:00:00', strtotime('monday last week'));
            $friday = date('Y-m-d 23:59:59', strtotime('friday last week'));
        }

        // Query premium & featured open jobs
        $jobs = $db->table('jobs j')
            ->select('j.id, j.title, j.slug, j.salary, j.salary_details, j.job_type, j.is_featured, j.is_urgent, j.is_anonymous, j.created_at, e.company_name, e.logo as company_logo, s.name as state_name')
            ->join('employers e', 'e.id = j.employer_id', 'left')
            ->join('states s', 's.id = j.state_id', 'left')
            ->where('j.status', 'open')
            ->groupStart()
                ->where('j.is_featured', 1)
                ->orWhere('j.is_urgent', 1)
                ->orWhere('j.created_at >=', $monday)
            ->groupEnd()
            ->orderBy('j.is_featured', 'DESC')
            ->orderBy('j.is_urgent', 'DESC')
            ->orderBy('j.created_at', 'DESC')
            ->limit(10)
            ->get()->getResult();

        if (empty($jobs)) {
            // Fallback: grab latest 6 open jobs so candidates always receive high value
            $jobs = $db->table('jobs j')
                ->select('j.id, j.title, j.slug, j.salary, j.salary_details, j.job_type, j.is_featured, j.is_urgent, j.is_anonymous, j.created_at, e.company_name, e.logo as company_logo, s.name as state_name')
                ->join('employers e', 'e.id = j.employer_id', 'left')
                ->join('states s', 's.id = j.state_id', 'left')
                ->where('j.status', 'open')
                ->orderBy('j.created_at', 'DESC')
                ->limit(6)
                ->get()->getResult();
        }

        if (empty($jobs)) {
            return ['status' => 'skipped', 'message' => 'No active jobs available for digest.'];
        }

        // Get candidates who have not opted out of job alerts or weekly digest
        $candidateModel = model(JobSeekerModel::class);
        $builder = $candidateModel->where('notify_job_alerts', 1);
        if ($candidateModel->db->fieldExists('notify_weekly_digest', 'job_seekers')) {
            $builder->where('notify_weekly_digest', 1);
        }
        $candidates = $builder->findAll(250);

        $sent = 0;
        $failed = 0;
        $weekLabel = date('M j', strtotime($monday)) . ' – ' . date('M j, Y', strtotime($friday));

        foreach ($candidates as $candidate) {
            if (empty($candidate->email)) continue;

            $mailData = [
                'candidate_name' => $candidate->full_name ?? 'Candidate',
                'jobs'           => $jobs,
                'week_label'     => $weekLabel,
                'logoUrl'        => base_url('assets/imgs/template/logo-white.png'),
            ];

            try {
                $email = \Config\Services::email();
                $email->clear();
                $email->setTo($candidate->email);
                $email->setSubject("⭐ Weekly Premium Job Digest — " . count($jobs) . " New Handpicked Opportunities ({$weekLabel})");
                $email->setMessage(view('emails/weekly_premium_digest', $mailData));
                $email->setMailType('html');
                
                if ($email->send()) {
                    $sent++;
                } else {
                    $failed++;
                }
            } catch (\Throwable $e) {
                log_message('error', 'Weekly digest email failure: ' . $e->getMessage());
                $failed++;
            }
        }

        return [
            'status'         => 'success',
            'jobs_included'  => count($jobs),
            'emails_sent'    => $sent,
            'emails_failed'  => $failed,
            'week_period'    => $weekLabel,
        ];
    }

    protected function findMatchingJobs($alert): array
    {
        $db = db_connect();
        $builder = $db->table('jobs j')
            ->select('j.*, e.company_name, e.logo as company_logo, s.name as state_name, c.name as category_name')
            ->join('employers e', 'e.id = j.employer_id', 'left')
            ->join('states s', 's.id = j.state_id', 'left')
            ->join('job_categories c', 'c.id = j.category_id', 'left')
            ->where('j.status', 'open');

        if (!empty($alert->keyword)) {
            $builder->groupStart()
                ->like('j.title', $alert->keyword)
                ->orLike('j.description', $alert->keyword)
                ->orLike('c.name', $alert->keyword)
                ->orLike('j.skills', $alert->keyword)
                ->groupEnd();
        }

        if (!empty($alert->location_id)) {
            $builder->where('j.state_id', $alert->location_id);
        }

        if (!empty($alert->last_sent_at)) {
            $builder->where('j.created_at >', $alert->last_sent_at);
        }

        return $builder->orderBy('j.created_at', 'DESC')->limit(8)->get()->getResult();
    }

    protected function deliverAlert($alert, array $jobs)
    {
        $channel = $alert->channel ?? 'email';
        if ($channel === 'email' || $channel === 'both') {
            $this->sendEmailAlert($alert, $jobs);
        }

        if ($channel === 'inapp' || $channel === 'both') {
            $this->sendInAppAlert($alert, $jobs);
        }
    }

    protected function sendInAppAlert($alert, array $jobs)
    {
        try {
            $notifModel = model(\App\Models\CandidateNotificationModel::class);
            $jobCount = count($jobs);
            $firstJob = $jobs[0] ?? null;
            $title = $jobCount === 1
                ? "New Job Match: " . ($firstJob->title ?? 'New Opportunity')
                : "{$jobCount} New Jobs Matching Your Alert";
            $message = $jobCount === 1
                ? "A new job '{$firstJob->title}' matching your alert criteria has been posted."
                : "{$jobCount} new jobs have been posted matching your alert criteria.";

            $notifModel->insert([
                'candidate_id'   => $alert->job_seeker_id,
                'application_id' => null,
                'type'           => 'job_alert',
                'title'          => $title,
                'message'        => $message,
                'is_read'        => 0,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'In-app alert notification failed: ' . $e->getMessage());
        }
    }

    protected function sendEmailAlert($alert, array $jobs)
    {
        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->find($alert->job_seeker_id);

        if (!$candidate || empty($candidate->email)) {
            return;
        }

        $email = \Config\Services::email();
        $email->clear();
        $email->setTo($candidate->email);
        $alertTerm = !empty($alert->keyword) ? "\"{$alert->keyword}\"" : 'Your Saved Criteria';
        $email->setSubject("🔔 New Job Match: {$alertTerm} — JobberRecruit");
        $email->setMessage(view('emails/job_alert', [
            'candidate' => $candidate,
            'jobs'      => $jobs,
            'alert'     => $alert,
        ]));
        $email->setMailType('html');
        $email->send();
    }
}
