<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobModel;
use App\Models\EmployerModel;
use App\Models\UserModel;
use App\Models\JobNotificationModel;
use App\Services\Mailer;

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
        $mailer        = new Mailer();

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
            try {
                $mailer->sendTemplate(
                    $user->email,
                    $title,
                    'emails/job_alert',
                    [
                        'userName'    => $employer->company_name ?? $user->username,
                        'jobTitle'    => $jobObj->title,
                        'message'     => $message,
                        'companyName' => 'Jobber Recruit'
                    ]
                );
            } catch (\Throwable $e) {
                log_message('error', 'Job expiring email error: ' . $e->getMessage());
            }

            $notifiedCount++;
        }

        CLI::write("Job posting expiration notifications completed. Notified: {$notifiedCount}", "green");
    }
}
