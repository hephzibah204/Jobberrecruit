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
                $nationwideId = $this->nationwideStateId();
                $jobIsNationwide   = $nationwideId !== null && (int) ($job->state_id ?? 0) === $nationwideId;
                $alertIsNationwide = $nationwideId !== null && (int) $alert->location_id === $nationwideId;
                if (!$jobIsNationwide && !$alertIsNationwide && ($job->state_id ?? null) != $alert->location_id) {
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

            // Rendered from the admin email template system (template key:
            // weekly_premium_digest); the job cards are passed in as a
            // pre-built {jobs_html} fragment (the template parser has no loops).
            $rendered = (new EmailTemplateService())->render('weekly_premium_digest', [
                'candidate_name' => $candidate->full_name ?? 'Candidate',
                'week_label'     => $weekLabel,
                'job_count'      => count($jobs),
                'jobs_html'      => $this->buildDigestJobsHtml($jobs),
            ]);

            try {
                $email = \Config\Services::email();
                $email->clear();
                $email->setTo($candidate->email);
                $email->setSubject($rendered['subject']);
                $email->setMessage($rendered['html']);
                if (!empty($rendered['text'])) {
                    $email->setAltMessage($rendered['text']);
                }
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

    protected function nationwideStateId(): ?int
    {
        static $nationwideId = false;
        if ($nationwideId === false) {
            $row = model(\App\Models\StateModel::class)->where('slug', 'nigeria-nationwide')->first()
                ?? model(\App\Models\StateModel::class)->like('name', 'nationwide')->first();
            $nationwideId = $row ? (int) $row->id : null;
        }
        return $nationwideId;
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
            $nationwideId = $this->nationwideStateId();
            if ($nationwideId !== null && (int) $alert->location_id === $nationwideId) {
                // Alert set to Nationwide: jobs from every state match, so no location constraint.
            } elseif ($nationwideId !== null) {
                // Nationwide jobs are open to candidates in every state: include
                // them alongside the alert state's own jobs.
                $builder->groupStart()
                    ->where('j.state_id', $alert->location_id)
                    ->orWhere('j.state_id', $nationwideId)
                    ->groupEnd();
            } else {
                $builder->where('j.state_id', $alert->location_id);
            }
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

        $rendered = $this->renderJobAlertEmail($candidate, $alert, $jobs);

        $email = \Config\Services::email();
        $email->clear();
        $email->setTo($candidate->email);
        $email->setSubject($rendered['subject']);
        $email->setMessage($rendered['html']);
        if (!empty($rendered['text'])) {
            $email->setAltMessage($rendered['text']);
        }
        $email->setMailType('html');
        $email->send();
    }

    /**
     * Render the job-alert email from the admin email template system
     * (template key: job_alert). Public so the legacy alerts:send console
     * command renders through exactly the same path.
     *
     * @return array{subject: string, html: string, text: string}
     */
    public function renderJobAlertEmail($candidate, $alert, array $jobs): array
    {
        return (new EmailTemplateService())->render('job_alert', [
            'candidate_name'      => $candidate->full_name ?? 'Candidate',
            'alert_term'          => !empty($alert->keyword) ? '"' . $alert->keyword . '"' : 'Your Saved Criteria',
            'alert_criteria_html' => !empty($alert->keyword) ? ' criteria (<strong>' . esc($alert->keyword) . '</strong>)' : '',
            'jobs_html'           => $this->buildJobAlertJobsHtml($jobs, $alert),
            'tracking_pixel_html' => !empty($alert->id)
                ? '<img src="' . base_url('track/open/' . $alert->id) . '" width="1" height="1" style="display: none;" alt="">'
                : '',
        ]);
    }

    /**
     * Build the per-job card list for the job_alert template ({jobs_html}).
     * Mirrors the markup the retired emails/job_alert view produced,
     * including the tracked click URLs and the urgent/featured pills.
     */
    private function buildJobAlertJobsHtml(array $jobs, $alert): string
    {
        $html = '';

        foreach ($jobs as $job) {
            $isFeatured = !empty($job->is_featured);
            $isUrgent   = !empty($job->is_urgent);
            $jobUrl     = !empty($alert->id)
                ? base_url('track/click/' . $alert->id . '/' . $job->id)
                : base_url('jobs/' . ($job->slug ?? $job->id));

            $pill = '';
            if ($isUrgent) {
                $pill = '<span style="float: right; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px; background: #FEE2E2; color: #DC2626;">⚡ Urgent</span>';
            } elseif ($isFeatured) {
                $pill = '<span style="float: right; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px; background: #FDF1E0; color: #C8770E;">★ Featured</span>';
            }

            $company = !empty($job->is_anonymous)
                ? 'Confidential Employer'
                : esc($job->company_name ?? 'Verified Employer');

            $meta = '';
            if (!empty($job->state_name) || !empty($job->location)) {
                $meta .= '<span style="background: #F1F5F9; padding: 4px 9px; border-radius: 5px; display: inline-block; margin: 0 8px 8px 0;">📍 ' . esc($job->state_name ?? $job->location) . '</span>';
            }
            if (!empty($job->job_type)) {
                $meta .= '<span style="background: #F1F5F9; padding: 4px 9px; border-radius: 5px; display: inline-block; margin: 0 8px 8px 0;">💼 ' . esc(ucwords(str_replace('_', ' ', $job->job_type))) . '</span>';
            }
            if (!empty($job->salary_details) && $job->salary_details !== 'Negotiable') {
                $meta .= '<span style="background: #F1F5F9; padding: 4px 9px; border-radius: 5px; display: inline-block; margin: 0 8px 8px 0;">₦ ' . esc($job->salary_details) . '</span>';
            } elseif (!empty($job->salary_min) || !empty($job->salary_max)) {
                $meta .= '<span style="background: #F1F5F9; padding: 4px 9px; border-radius: 5px; display: inline-block; margin: 0 8px 8px 0;">₦ ' . number_format($job->salary_min ?? 0) . ' - ' . number_format($job->salary_max ?? 0) . '</span>';
            } elseif (!empty($job->salary)) {
                $meta .= '<span style="background: #F1F5F9; padding: 4px 9px; border-radius: 5px; display: inline-block; margin: 0 8px 8px 0;">₦ ' . esc($job->salary) . '</span>';
            }

            $cardStyle = 'background: ' . ($isFeatured ? '#FFFCF8' : '#FFFFFF')
                . '; border: 1px solid #E2E8F0; border-left: 4px solid ' . ($isFeatured ? '#ED9020' : '#0861A9')
                . '; border-radius: 10px; padding: 20px; margin-bottom: 18px;';

            $html .= '
                <div style="' . $cardStyle . '">
                    ' . $pill . '
                    <h3 style="margin: 0 0 6px 0; font-size: 17px; font-weight: 700; color: #0A2F57;">' . esc($job->title) . '</h3>
                    <div style="font-size: 14px; font-weight: 600; color: #0861A9; margin-bottom: 10px;">' . $company . '</div>
                    <div style="font-size: 12px; color: #64748B; margin-bottom: 16px;">' . $meta . '</div>
                    <a href="' . esc($jobUrl) . '" style="display: inline-block; background: #0861A9; color: #FFFFFF; padding: 10px 20px; font-size: 13px; font-weight: 600; text-decoration: none; border-radius: 6px;">View Vacancy &amp; Apply →</a>
                </div>';
        }

        return $html;
    }

    /**
     * Build the per-job card list for the weekly_premium_digest template
     * ({jobs_html}). Mirrors the markup the retired
     * emails/weekly_premium_digest view produced (direct job links, no
     * click tracking; gold left border on every card).
     */
    private function buildDigestJobsHtml(array $jobs): string
    {
        $html = '';

        foreach ($jobs as $job) {
            $pill = '';
            if (!empty($job->is_featured) || !empty($job->is_urgent)) {
                $pill = '<span style="float: right; background: #FDF1E0; color: #C8770E; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">'
                    . (!empty($job->is_urgent) ? '⚡ Urgent' : '★ Featured') . '</span>';
            }

            $company = !empty($job->is_anonymous)
                ? 'Confidential Employer'
                : esc($job->company_name ?? ($job->employer_name ?? 'Verified Employer'));

            $meta = '';
            if (!empty($job->location) || !empty($job->state_name)) {
                $meta .= '<span style="background: #F1F5F9; padding: 3px 8px; border-radius: 4px; display: inline-block; margin: 0 12px 8px 0;">📍 ' . esc($job->state_name ?? $job->location) . '</span>';
            }
            if (!empty($job->job_type) || !empty($job->employment_type)) {
                $meta .= '<span style="background: #F1F5F9; padding: 3px 8px; border-radius: 4px; display: inline-block; margin: 0 12px 8px 0;">💼 ' . esc(ucwords(str_replace('_', ' ', $job->job_type ?? $job->employment_type))) . '</span>';
            }
            if (!empty($job->salary_details) && $job->salary_details !== 'Negotiable') {
                $meta .= '<span style="background: #F1F5F9; padding: 3px 8px; border-radius: 4px; display: inline-block; margin: 0 12px 8px 0;">₦ ' . esc($job->salary_details) . '</span>';
            } elseif (!empty($job->salary_min) || !empty($job->salary_max)) {
                $meta .= '<span style="background: #F1F5F9; padding: 3px 8px; border-radius: 4px; display: inline-block; margin: 0 12px 8px 0;">₦ ' . number_format($job->salary_min ?? 0) . ' - ' . number_format($job->salary_max ?? 0) . '</span>';
            } elseif (!empty($job->salary)) {
                $meta .= '<span style="background: #F1F5F9; padding: 3px 8px; border-radius: 4px; display: inline-block; margin: 0 12px 8px 0;">₦ ' . esc($job->salary) . '</span>';
            }

            $html .= '
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-left: 4px solid #ED9020; border-radius: 10px; padding: 20px; margin-bottom: 18px;">
                    ' . $pill . '
                    <h3 style="margin: 0 0 6px 0; font-size: 17px; font-weight: 700; color: #0A2F57;">' . esc($job->title) . '</h3>
                    <div style="font-size: 14px; font-weight: 600; color: #0861A9; margin-bottom: 8px;">' . $company . '</div>
                    <div style="font-size: 12px; color: #64748B; margin-bottom: 14px;">' . $meta . '</div>
                    <a href="' . esc(base_url('jobs/' . ($job->slug ?? $job->id))) . '" style="display: inline-block; background: #0861A9; color: #FFFFFF; padding: 9px 18px; font-size: 13px; font-weight: 600; text-decoration: none; border-radius: 6px;">View Vacancy &amp; Apply →</a>
                </div>';
        }

        return $html;
    }
}
