<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobModel;
use App\Models\EmployerModel;
use App\Models\UserModel;
use App\Models\JobNotificationModel;
use App\Services\EmailTemplateService;

class JobPostingReminders extends BaseCommand
{
    protected $group       = 'Automations';
    protected $name        = 'jobs:send-expiring-reminders';
    protected $description = 'Send email and in-app notifications for job postings expiring in 3 days';

    public function run(array $params)
    {
        CLI::write("Starting job posting expiration check...", "yellow");

        $jobModel      = new JobModel();
        $employerModel = new EmployerModel();
        $userModel     = new UserModel();
        $jobNotifModel = new JobNotificationModel();

        $now           = time();
        $threeDaysTime = date('Y-m-d H:i:s', $now + (3 * 86400));

        // Find active jobs expiring in the next 3 days that haven't been notified yet
        $expiringJobs = $jobModel
            ->where('status', 'active')
            ->where('deadline >=', date('Y-m-d H:i:s', $now))
            ->where('deadline <=', $threeDaysTime)
            ->findAll();

        $notifiedCount = 0;

        foreach ($expiringJobs as $job) {
            $jobObj     = (object) $job;
            $employer   = $employerModel->find($jobObj->employer_id);
            if (!$employer) continue;

            $user       = $userModel->find($employer->user_id);
            if (!$user) continue;

            // Check if notification already created for this job expiry
            $existing = $jobNotifModel
                ->where('employer_id', $employer->id)
                ->where('job_id', $jobObj->id)
                ->where('type', JobNotificationModel::TYPE_JOB_EXPIRING)
                ->first();

            if ($existing) continue;

            $daysLeft = max(1, (int) ceil((strtotime($jobObj->deadline) - $now) / 86400));
            $title    = "Job Expiring Soon: {$jobObj->title}";
            $message  = "Your job listing '{$jobObj->title}' will expire in {$daysLeft} day(s) on " . date('M j, Y', strtotime($jobObj->deadline)) . ". Renew or edit your listing to keep receiving applications.";

            // 1. In-App Notification
            $jobNotifModel->createNotification(
                (int) $employer->id,
                JobNotificationModel::TYPE_JOB_EXPIRING,
                $title,
                $message,
                (int) $jobObj->id
            );

            // 2. Email Notification
            // Rendered from the admin email template system (template key:
            // job_expiring_soon) — this previously mis-reused the candidate
            // job_alert view, which ignored the employer data entirely.
            try {
                (new EmailTemplateService())->send('job_expiring_soon', $user->email, [
                    'name'            => $employer->company_name ?? $user->username,
                    'company_name'    => $employer->company_name ?? $user->username,
                    'job_title'       => $jobObj->title,
                    'days_remaining'  => $daysLeft,
                    'expiration_date' => date('M j, Y', strtotime($jobObj->deadline)),
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Job expiring email error: ' . $e->getMessage());
            }

            $notifiedCount++;
        }

        CLI::write("Job posting expiration notifications completed. Notified: {$notifiedCount}", "green");
    }
}
