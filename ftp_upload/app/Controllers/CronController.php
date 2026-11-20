<?php

namespace App\Controllers;

use App\Models\JobQueueModel;

class CronController extends BaseController
{
    protected function validateCronToken(): bool
    {
        $token = $this->request->getGet('token') ?? ($_GET['token'] ?? null);
        $expectedToken = env('cron_token') ?: env('CRON_TOKEN', 'jobber_cron_secret_123');

        return !empty($token) && hash_equals(trim((string)$expectedToken), trim((string)$token));
    }

    public function processQueue()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        return $this->response->setJSON(array_merge(['status' => 'success'],
            (new \App\Services\EmailQueueWorker())->process(50)));
    }

    public function processEmailQueue()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }
        return $this->response->setJSON(array_merge(['status' => 'success'],
            (new \App\Services\EmailQueueWorker())->process(100, ['campaign_email', 'newsletter_email'])));
    }

    /**
     * Archive accounts that haven't updated their profile in 3 months
     * Triggered via cron: /cron/archive-inactive-accounts?token=...
     */
    public function archiveInactiveAccounts()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        $db = \Config\Database::connect();
        $threeMonthsAgo = date('Y-m-d H:i:s', strtotime('-3 months'));

        // We "archive" by setting status to 'inactive' or 'archived'
        // and logging the action. Safest method is to NOT delete, but flag.
        
        // 1. Update Employers
        $employerCount = $db->table('users')
            ->where('user_type', 'employer')
            ->where('status', 'active')
            ->where('updated_at <', $threeMonthsAgo)
            ->update(['status' => 'inactive', 'status_message' => 'Account archived due to 3 months of inactivity.']);

        $employerCount = $db->affectedRows();

        // 2. Update Candidates
        $candidateCount = $db->table('users')
            ->whereIn('user_type', ['job_seeker', 'candidate'])
            ->where('status', 'active')
            ->where('updated_at <', $threeMonthsAgo)
            ->update(['status' => 'inactive', 'status_message' => 'Account archived due to 3 months of inactivity.']);

        $candidateCount = $db->affectedRows();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Inactivity archiving complete.',
            'employers_archived' => $employerCount,
            'candidates_archived' => $candidateCount
        ]);
    }

    /**
     * Unified Cron Endpoint to run all automated email reminders and queue jobs in one call.
     * Trigger via URL e.g.: /cron/run-all-automations?token=jobber_cron_secret_123
     */
    public function runAllAutomations()
    {
        @set_time_limit(180);
        @ignore_user_abort(true);

        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        $results = [];

        // 1. Process Pending Queue Items (Newsletters, Transactional, etc. - 15 items per batch)
        try {
            $queueResult = (new \App\Services\EmailQueueWorker())->process(15);
            $results['queue_processed'] = $queueResult['jobs_processed'];
            $results['queue_failed'] = $queueResult['jobs_failed'];
        } catch (\Throwable $e) {
            $results['queue_error'] = $e->getMessage();
        }

        // 2. Run Webinar Reminders (24h & 1h)
        try {
            $webinarModel = new \App\Models\WebinarModel();
            $regModel     = new \App\Models\WebinarRegistrationModel();
            $userModel    = new \App\Models\UserModel();
            $emailNotif   = new \App\Services\EmailNotificationService();

            $now = time();
            $webinars = $webinarModel->where('status !=', 'cancelled')
                ->where('scheduled_at >=', date('Y-m-d H:i:s', $now))
                ->findAll();

            $wSent = 0;
            foreach ($webinars as $w) {
                $wObj = (object) $w;
                $diffHours = (strtotime($wObj->scheduled_at) - $now) / 3600;
                if ($diffHours <= 25 && $diffHours >= 23) {
                    $regs = $regModel->where('webinar_id', $wObj->id)->where('reminder_sent_24h', 0)->findAll();
                    foreach ($regs as $r) {
                        $user = $userModel->find($r->user_id);
                        if ($user) {
                            $emailNotif->sendWebinarReminderNotification($user, $wObj, '24h');
                            $regModel->update($r->id, ['reminder_sent_24h' => 1]);
                            $wSent++;
                        }
                    }
                } elseif ($diffHours <= 1.5 && $diffHours >= 0.1) {
                    $regs = $regModel->where('webinar_id', $wObj->id)->where('reminder_sent_1h', 0)->findAll();
                    foreach ($regs as $r) {
                        $user = $userModel->find($r->user_id);
                        if ($user) {
                            $emailNotif->sendWebinarReminderNotification($user, $wObj, '1h');
                            $regModel->update($r->id, ['reminder_sent_1h' => 1]);
                            $wSent++;
                        }
                    }
                }
            }
            $results['webinar_reminders_sent'] = $wSent;
        } catch (\Throwable $e) {
            $results['webinar_reminders_error'] = $e->getMessage();
        }

        // 3. Run Subscription Expiry Reminders
        try {
            $subModel   = new \App\Models\UserSubscriptionModel();
            $planModel  = new \App\Models\PlanModel();
            $userModel  = new \App\Models\UserModel();
            $emailNotif = new \App\Services\EmailNotificationService();

            $now = time();
            $activeSubs = $subModel->where('is_active', 1)->where('ends_at IS NOT NULL', null, false)->findAll();
            $sSent = 0;

            foreach ($activeSubs as $s) {
                $sObj = (object) $s;
                $diffDays = (strtotime($sObj->ends_at) - $now) / 86400;
                $user = $userModel->find($sObj->user_id);
                $plan = $planModel->find($sObj->plan_id);

                if ($user && $plan) {
                    $subDetails = [
                        'plan_name' => $plan->name ?? 'Subscription Plan',
                        'ends_at'   => $sObj->ends_at,
                    ];

                    if ($diffDays <= 3.5 && $diffDays >= 2.5 && empty($sObj->expiry_reminder_3d_sent)) {
                        $emailNotif->sendSubscriptionExpiringNotification($user, $subDetails, 3);
                        $subModel->update($sObj->id, ['expiry_reminder_3d_sent' => 1]);
                        $sSent++;
                    } elseif ($diffDays <= 1.5 && $diffDays >= 0.5 && empty($sObj->expiry_reminder_1d_sent)) {
                        $emailNotif->sendSubscriptionExpiringNotification($user, $subDetails, 1);
                        $subModel->update($sObj->id, ['expiry_reminder_1d_sent' => 1]);
                        $sSent++;
                    } elseif ($diffDays < 0 && empty($sObj->expired_notice_sent)) {
                        $emailNotif->sendSubscriptionExpiredNotification($user, $subDetails);
                        $subModel->update($sObj->id, ['is_active' => 0, 'expired_notice_sent' => 1]);
                        $sSent++;
                    }
                }
            }
            $results['subscription_reminders_sent'] = $sSent;
        } catch (\Throwable $e) {
            $results['subscription_reminders_error'] = $e->getMessage();
        }

        // 4. Run Scheduled Job Alert Matches
        try {
            $jobAlertService = new \App\Services\JobAlertService();
            $alertsDailySent = $jobAlertService->processAlerts('daily');
            $alertsWeeklySent = 0;
            if (date('N') == 1) { // Mondays
                $alertsWeeklySent = $jobAlertService->processAlerts('weekly');
            }
            $results['job_alerts_sent'] = $alertsDailySent + $alertsWeeklySent;
        } catch (\Throwable $e) {
            $results['job_alerts_error'] = $e->getMessage();
        }

        // 5. Run Weekly Premium Job Digest (Runs on Saturdays or Mondays)
        try {
            $dayOfWeek = (int) date('N'); // 1 = Mon, 6 = Sat
            if ($dayOfWeek === 6 || $dayOfWeek === 1 || $this->request->getGet('force_digest')) {
                $jobAlertService = new \App\Services\JobAlertService();
                $digestResult = $jobAlertService->sendWeeklyPremiumDigest();
                $results['weekly_premium_digest'] = $digestResult;
            }
        } catch (\Throwable $e) {
            $results['weekly_digest_error'] = $e->getMessage();
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'timestamp' => date('Y-m-d H:i:s'),
            'summary'   => $results
        ]);
    }

    /**
     * Standalone Job Alert Matching endpoint: /cron/send-job-alerts?token=...
     */
    public function sendJobAlerts()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        $frequency = $this->request->getGet('frequency') ?: 'daily';
        $jobAlertService = new \App\Services\JobAlertService();
        $sentCount = $jobAlertService->processAlerts($frequency);

        return $this->response->setJSON([
            'status'     => 'success',
            'frequency'  => $frequency,
            'sent_count' => $sentCount,
            'timestamp'  => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Standalone Weekly Premium Job Digest endpoint: /cron/send-weekly-digest?token=...
     */
    public function sendWeeklyDigest()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        $jobAlertService = new \App\Services\JobAlertService();
        $result = $jobAlertService->sendWeeklyPremiumDigest();

        return $this->response->setJSON([
            'status'    => 'success',
            'result'    => $result,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    protected function sendEmail($to, $subject, $content)
    {
        $config = config('Email');
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $email->setFrom($config->fromEmail, $config->fromName);
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($content);
        $email->setMailType('html');
        $email->SMTPTimeout = 5;

        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Queue Email Error: ' . $email->printDebugger(['headers']));
            return false;
        }
    }

    protected function sendTransactionalEmail($data)
    {
        $config = config('Email');
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $fromEmail = !empty($data['from_email']) ? $data['from_email'] : $config->fromEmail;
        $fromName  = !empty($data['from_name']) ? $data['from_name'] : $config->fromName;
        $email->setFrom($fromEmail, $fromName);
        $email->setTo($data['to']);
        $email->setSubject($data['subject']);
        $email->setMessage($data['message']);
        $email->SMTPTimeout = 5;

        if (!empty($data['alt_message'])) {
            $email->setAltMessage($data['alt_message']);
        }
        if (!empty($data['reply_to'])) {
            $email->setReplyTo($data['reply_to']);
        }
        if (!empty($data['mail_type'])) {
            $email->setMailType($data['mail_type']);
        }
        if (!empty($data['headers']) && is_array($data['headers'])) {
            foreach ($data['headers'] as $key => $val) {
                $email->setHeader($key, $val);
            }
        }

        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Queue Transactional Email Error: ' . $email->printDebugger(['headers']));
            return false;
        }
    }

    /**
     * Populate slugs for jobs, categories, industries, and locations via browser/web request
     * /cron/populate-slugs?token=jobber_cron_secret_123
     * Optional &force=1 to re-generate even if already populated
     */
    public function populateSlugs()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized request. Pass valid ?token=']);
        }

        @set_time_limit(300);
        $force = (bool)$this->request->getGet('force');

        $results = \App\Services\SeoSlugGenerator::populateAll($force);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Populated slugs for jobs, categories, industries, and locations.',
            'mode' => $force ? 'force_regeneration' : 'populate_missing',
            'results' => $results,
        ]);
    }

    /**
     * Verify SEO implementation via browser/web request
     * /cron/verify-seo?token=jobber_cron_secret_123
     */
    public function verifySeo()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized request. Pass valid ?token=']);
        }

        $jobModel = new \App\Models\JobModel();
        $totalJobs = $jobModel->countAllResults();
        $slugsCount = $jobModel->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();

        $sampleJobs = $jobModel->where('admin_status', 'approved')->findAll(5);
        $samples = [];
        foreach ($sampleJobs as $sample) {
            $samples[] = [
                'id' => $sample->id,
                'title' => $sample->title,
                'slug' => $sample->slug,
                'url' => base_url('jobs/' . $sample->slug),
            ];
        }

        $db = \Config\Database::connect();
        $totalCats  = $db->table('job_categories')->countAllResults();
        $catSlugs   = $db->table('job_categories')->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $totalInds  = $db->table('industries')->countAllResults();
        $indSlugs   = $db->table('industries')->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $totalStates = $db->table('states')->countAllResults();
        $stateSlugs = $db->table('states')->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $seededH1    = $db->table('states')->where('seo_h1 IS NOT NULL')->where('seo_h1 !=', '')->countAllResults();
        $nationwide  = $db->table('states')->where('slug', 'nigeria-nationwide')->get()->getRow();
        $remote      = $db->table('states')->where('slug', 'remote')->get()->getRow();

        return $this->response->setJSON([
            'status' => 'success',
            'slugs_audit' => [
                'jobs' => [
                    'total' => $totalJobs,
                    'populated' => $slugsCount,
                    'complete' => ($totalJobs > 0 && $totalJobs === $slugsCount),
                ],
                'categories' => [
                    'total' => $totalCats,
                    'populated' => $catSlugs,
                    'complete' => ($totalCats > 0 && $totalCats === $catSlugs),
                ],
                'industries' => [
                    'total' => $totalInds,
                    'populated' => $indSlugs,
                    'complete' => ($totalInds > 0 && $totalInds === $indSlugs),
                ],
                'locations' => [
                    'total' => $totalStates,
                    'populated' => $stateSlugs,
                    'complete' => ($totalStates > 0 && $totalStates === $stateSlugs),
                ],
            ],
            'sample_jobs' => $samples,
            'location_hubs' => [
                'total_hubs' => $totalStates,
                'hubs_with_seo' => $seededH1,
                'all_seeded' => ($totalStates === 39 && $seededH1 === 39),
                'nationwide_hub' => $nationwide ? [
                    'id' => $nationwide->id,
                    'slug' => $nationwide->slug,
                    'url' => base_url('jobs/' . $nationwide->slug),
                    'h1' => $nationwide->seo_h1,
                ] : null,
                'remote_hub' => $remote ? [
                    'id' => $remote->id,
                    'slug' => $remote->slug,
                    'url' => base_url('jobs/' . $remote->slug),
                    'h1' => $remote->seo_h1,
                ] : null,
            ],
        ]);
    }
}

