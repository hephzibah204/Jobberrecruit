<?php

namespace App\Services;

use CodeIgniter\Config\Services;
use App\Models\CandidateNotificationModel;
use App\Models\JobNotificationModel;
use App\Models\JobSeekerModel;
use App\Models\EmployerModel;

class EmailNotificationService
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    protected function sendEmail(string $to, string $subject, string $view, array $data): bool
    {
        // Extract template key from view path (e.g., 'emails/application_status' => 'application_status')
        $templateKey = str_replace(['emails/', '.php'], '', $view);
        if ($view === 'emails/application_status' && isset($data['statusLabel']) && $data['statusLabel'] === 'Aptitude Test Invitation') {
            $templateKey = 'aptitude_test_invitation';
        }

        $templateService = new EmailTemplateService();
        $rendered = $templateService->render($templateKey, $data);

        $this->email->clear();
        $this->email->setTo($to);

        $fromEmail = env('email.fromEmail') ?: (env('email.from_email') ?: (config('Email')->fromEmail ?? 'no-reply@jobberrecruit.com'));
        $fromName  = $data['from_name'] ?? ($data['companyName'] ?? (env('email.fromName') ?: config('Email')->fromName ?? 'JobberRecruit'));

        $this->email->setFrom($fromEmail, $fromName);
        $finalSubject = !empty($rendered['subject']) ? $rendered['subject'] : $subject;
        $this->email->setSubject($finalSubject);

        $html = !empty($rendered['html']) ? $rendered['html'] : (is_file(APPPATH . "Views/{$view}.php") ? view($view, $data) : '');

        $this->email->setMessage($html);
        if (!empty($rendered['text'])) {
            $this->email->setAltMessage($rendered['text']);
        }
        $this->email->setMailType('html');

        try {
            $result = $this->email->send();
            if (!$result) {
                log_message('error', 'Email send failed: ' . $this->email->printDebugger(['headers']));
            }
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Email send exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create in-app notification for candidate or employer user
     */
    public function createInAppNotification(int $userId, string $type, string $title, string $message, ?int $metaId = null): bool
    {
        try {
            // Check if user is a Candidate (JobSeeker)
            $candidateModel = model(JobSeekerModel::class);
            $candidate = $candidateModel->where('user_id', $userId)->first();

            if ($candidate) {
                $candidateNotifModel = model(CandidateNotificationModel::class);
                return $candidateNotifModel->createNotification(
                    (int) $candidate->id,
                    $type,
                    $title,
                    $message,
                    $metaId
                );
            }

            // Check if user is an Employer
            $employerModel = model(EmployerModel::class);
            $employer = $employerModel->where('user_id', $userId)->first();

            if ($employer) {
                $jobNotifModel = model(JobNotificationModel::class);
                return $jobNotifModel->createNotification(
                    (int) $employer->id,
                    $type,
                    $title,
                    $message
                );
            }
        } catch (\Throwable $e) {
            log_message('error', 'In-app notification creation error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * 1. Webinar Registration Notification (Email + In-App)
     */
    public function sendWebinarRegistrationNotification($user, $webinar): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $userId = is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0);
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Member') : ($user['username'] ?? $user['first_name'] ?? 'Member');

        $webinarTitle = is_object($webinar) ? $webinar->title : ($webinar['title'] ?? 'Upcoming Webinar');
        $webinarId = is_object($webinar) ? $webinar->id : ($webinar['id'] ?? 0);
        $presenter = is_object($webinar) ? ($webinar->presenter ?? '') : ($webinar['presenter'] ?? '');
        $accessType = is_object($webinar) ? ($webinar->access_type ?? 'free') : ($webinar['access_type'] ?? 'free');
        
        $scheduledAtRaw = is_object($webinar) ? ($webinar->scheduled_at ?? '') : ($webinar['scheduled_at'] ?? '');
        $scheduledAt = !empty($scheduledAtRaw) ? date('F j, Y \a\t g:i A', strtotime($scheduledAtRaw)) : 'Scheduled Soon';

        $joinUrl = base_url('webinars');

        $data = [
            'userName'     => htmlspecialchars($userName),
            'webinarTitle' => htmlspecialchars($webinarTitle),
            'presenter'    => htmlspecialchars($presenter),
            'scheduledAt'  => $scheduledAt,
            'accessType'   => $accessType,
            'joinUrl'      => $joinUrl,
            'subject'      => "Registration Confirmed: {$webinarTitle}",
            'companyName'  => 'Jobber Recruit'
        ];

        // Send Email
        $emailResult = $this->sendEmail(
            $email,
            "Registration Confirmed: {$webinarTitle}",
            'emails/webinar_registration',
            $data
        );

        // Send In-App Notification
        if ($userId > 0) {
            $this->createInAppNotification(
                $userId,
                'webinar_registered',
                "Webinar Registered: {$webinarTitle}",
                "You are confirmed for '{$webinarTitle}' scheduled on {$scheduledAt}. Click to view details.",
                (int)$webinarId
            );
        }

        return $emailResult;
    }

    /**
     * 2. Webinar Reminder Notification (Email + In-App)
     */
    public function sendWebinarReminderNotification($user, $webinar, string $timeFrame = '24h'): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $userId = is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0);
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Member') : ($user['username'] ?? $user['first_name'] ?? 'Member');

        $webinarTitle = is_object($webinar) ? $webinar->title : ($webinar['title'] ?? 'Upcoming Webinar');
        $presenter = is_object($webinar) ? ($webinar->presenter ?? '') : ($webinar['presenter'] ?? '');
        
        $scheduledAtRaw = is_object($webinar) ? ($webinar->scheduled_at ?? '') : ($webinar['scheduled_at'] ?? '');
        $scheduledAt = !empty($scheduledAtRaw) ? date('F j, Y \a\t g:i A', strtotime($scheduledAtRaw)) : 'Starting Soon';

        $timeFrameLabel = $timeFrame === '1h' ? 'in 1 Hour!' : 'in 24 Hours';

        $data = [
            'userName'       => htmlspecialchars($userName),
            'webinarTitle'   => htmlspecialchars($webinarTitle),
            'presenter'      => htmlspecialchars($presenter),
            'scheduledAt'    => $scheduledAt,
            'timeFrameLabel' => $timeFrameLabel,
            'joinUrl'        => base_url('webinars'),
            'subject'        => "Reminder: Webinar '{$webinarTitle}' starts {$timeFrameLabel}",
            'companyName'    => 'Jobber Recruit'
        ];

        // Send Email
        $emailResult = $this->sendEmail(
            $email,
            "Reminder: Webinar '{$webinarTitle}' starts {$timeFrameLabel}",
            'emails/webinar_reminder',
            $data
        );

        // Send In-App Notification
        if ($userId > 0) {
            $this->createInAppNotification(
                $userId,
                'webinar_reminder',
                "Reminder: Webinar Starts {$timeFrameLabel}",
                "Your registered webinar '{$webinarTitle}' is starting {$timeFrameLabel} ({$scheduledAt})."
            );
        }

        return $emailResult;
    }

    /**
     * 3. Course Enrollment Notification (Email + In-App)
     */
    public function sendCourseEnrollmentNotification($user, $course): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $userId = is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0);
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Student') : ($user['username'] ?? $user['first_name'] ?? 'Student');

        $courseTitle = is_object($course) ? $course->title : ($course['title'] ?? 'Course');
        $courseId = is_object($course) ? $course->id : ($course['id'] ?? 0);

        $data = [
            'user_name'    => htmlspecialchars($userName),
            'course_title' => htmlspecialchars($courseTitle),
            'course_id'    => $courseId,
            'companyName'  => 'Jobber Recruit'
        ];

        // Send Email
        $emailResult = $this->sendEmail(
            $email,
            "Welcome to " . $courseTitle,
            'emails/course_enrollment',
            $data
        );

        // Send In-App Notification
        if ($userId > 0) {
            $this->createInAppNotification(
                $userId,
                'course_enrolled',
                "Enrolled in Course: {$courseTitle}",
                "Welcome to '{$courseTitle}'! You can start learning anytime in your classroom dashboard."
            );
        }

        return $emailResult;
    }

    /**
     * 4. Course Completed Notification (Email + In-App)
     */
    public function sendCourseCompletedNotification($user, $course, ?string $certificateUrl = null): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $userId = is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0);
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Student') : ($user['username'] ?? $user['first_name'] ?? 'Student');

        $courseTitle = is_object($course) ? $course->title : ($course['title'] ?? 'Course');
        $courseId = is_object($course) ? $course->id : ($course['id'] ?? 0);

        $data = [
            'user_name'      => htmlspecialchars($userName),
            'course_title'   => htmlspecialchars($courseTitle),
            'course_id'      => $courseId,
            'certificateUrl' => $certificateUrl ?? base_url("candidate/my-courses/{$courseId}"),
            'companyName'    => 'Jobber Recruit'
        ];

        // Send Email
        $emailResult = $this->sendEmail(
            $email,
            "Congratulations! You completed " . $courseTitle,
            'emails/course_completed',
            $data
        );

        // Send In-App Notification
        if ($userId > 0) {
            $this->createInAppNotification(
                $userId,
                'course_completed',
                "Course Completed: {$courseTitle}",
                "Congratulations! You completed '{$courseTitle}'. Your certificate of completion is now available."
            );
        }

        return $emailResult;
    }

    /**
     * 5. Subscription Expiring Warning Notification (Email + In-App)
     */
    public function sendSubscriptionExpiringNotification($user, $subDetails, int $daysRemaining): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $userId = is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0);
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Member') : ($user['username'] ?? $user['first_name'] ?? 'Member');

        if (is_object($subDetails)) {
            $planName = $subDetails->name ?? ($subDetails->plan_name ?? 'Subscription Plan');
            $endsAtRaw = $subDetails->ends_at ?? date('Y-m-d H:i:s');
        } else {
            $planName = $subDetails['plan_name'] ?? ($subDetails['name'] ?? 'Subscription Plan');
            $endsAtRaw = $subDetails['ends_at'] ?? date('Y-m-d H:i:s');
        }
        $expirationDate = date('F j, Y', strtotime($endsAtRaw));

        $data = [
            'userName'       => htmlspecialchars($userName),
            'planName'       => htmlspecialchars($planName),
            'expirationDate' => $expirationDate,
            'daysRemaining'  => $daysRemaining,
            'renewUrl'       => 'employer/pricing',
            'companyName'    => 'Jobber Recruit'
        ];

        $subject = "Subscription Warning: Your {$planName} plan expires in {$daysRemaining} day" . ($daysRemaining > 1 ? 's' : '');

        // Send Email
        $emailResult = $this->sendEmail(
            $email,
            $subject,
            'emails/subscription_expiring',
            $data
        );

        // Send In-App Notification
        if ($userId > 0) {
            $this->createInAppNotification(
                $userId,
                'subscription_expiring',
                "Subscription Expiring in {$daysRemaining} Days",
                "Your '{$planName}' plan is scheduled to expire on {$expirationDate}. Renew now to maintain uninterrupted access."
            );
        }

        return $emailResult;
    }

    /**
     * 6. Subscription Expired Notice Notification (Email + In-App)
     */
    public function sendSubscriptionExpiredNotification($user, $subDetails): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $userId = is_object($user) ? ($user->id ?? 0) : ($user['id'] ?? 0);
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Member') : ($user['username'] ?? $user['first_name'] ?? 'Member');

        if (is_object($subDetails)) {
            $planName = $subDetails->name ?? ($subDetails->plan_name ?? 'Subscription Plan');
            $endsAtRaw = $subDetails->ends_at ?? date('Y-m-d H:i:s');
        } else {
            $planName = $subDetails['plan_name'] ?? ($subDetails['name'] ?? 'Subscription Plan');
            $endsAtRaw = $subDetails['ends_at'] ?? date('Y-m-d H:i:s');
        }
        $expirationDate = date('F j, Y', strtotime($endsAtRaw));

        $data = [
            'userName'       => htmlspecialchars($userName),
            'planName'       => htmlspecialchars($planName),
            'expirationDate' => $expirationDate,
            'renewUrl'       => 'employer/pricing',
            'companyName'    => 'Jobber Recruit'
        ];

        // Send Email
        $emailResult = $this->sendEmail(
            $email,
            "Your JobberRecruit {$planName} Plan Has Expired",
            'emails/subscription_expired',
            $data
        );

        // Send In-App Notification
        if ($userId > 0) {
            $this->createInAppNotification(
                $userId,
                'subscription_expired',
                "Subscription Expired",
                "Your '{$planName}' plan expired on {$expirationDate}. Click to view available plans and reactivate."
            );
        }

        return $emailResult;
    }

    /**
     * Send application status update email to job seeker
     */
    public function sendApplicationStatusEmail($application, string $status, string $jobTitle, string $companyName, ?string $messageToCandidate = null): bool
    {
        $statusMap = [
            'pending'     => ['label' => 'Pending Review', 'color' => '#ffc107'],
            'reviewed'    => ['label' => 'Application Reviewed', 'color' => '#17a2b8'],
            'shortlisted' => ['label' => 'Shortlisted!', 'color' => '#28a745'],
            'rejected'    => ['label' => 'Application Update', 'color' => '#dc3545'],
            'hired'       => ['label' => "Congratulations! You're Hired!", 'color' => '#28a745'],
        ];

        if (!isset($statusMap[$status])) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $defaultMessages = [
            'pending'     => "Your application for <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong> has been received and is currently under review.",
            'reviewed'    => "Your application for <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong> has been reviewed by our team.",
            'shortlisted' => "Congratulations! You have been shortlisted for the position of <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong>. Our recruitment team will contact you shortly to schedule an interview.",
            'rejected'    => "Thank you for your interest in the position of <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong>. After careful review of all applications, we have decided to move forward with other candidates.",
            'hired'       => "Congratulations! We are pleased to inform you that you have been selected for the position of <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong>. Our HR team will contact you shortly with the offer letter and onboarding details.",
        ];

        $finalMessage = $messageToCandidate
            ? nl2br(htmlspecialchars($messageToCandidate))
            : $defaultMessages[$status];

        $data = [
            'name'        => htmlspecialchars(($application->first_name ?? '') . ' ' . ($application->last_name ?? '')),
            'statusLabel' => $statusMap[$status]['label'],
            'statusColor' => $statusMap[$status]['color'],
            'message'     => $finalMessage,
            'isGuest'     => (bool)($application->is_guest ?? false),
            'companyName' => htmlspecialchars($companyName),
            'jobTitle'    => htmlspecialchars($jobTitle),
        ];

        $subject = "Application Status Update: " . $statusMap[$status]['label'];

        $result = false;
        if (!empty($application->email)) {
            $result = $this->sendEmail(
                $application->email,
                $subject,
                'emails/application_status',
                $data
            );
        }

        // Send in-app notification if job seeker candidate profile or user exists
        $targetUserId = null;
        if (!empty($application->job_seeker_id)) {
            $candidateModel = model(JobSeekerModel::class);
            $candidate = $candidateModel->find($application->job_seeker_id);
            if ($candidate && !empty($candidate->user_id)) {
                $targetUserId = (int) $candidate->user_id;
            }
        } elseif (!empty($application->user_id)) {
            $targetUserId = (int) $application->user_id;
        }

        if ($targetUserId) {
            $this->createInAppNotification(
                $targetUserId,
                CandidateNotificationModel::TYPE_APPLICATION_STATUS_CHANGED,
                "Application Update: {$jobTitle}",
                "Your application status for {$jobTitle} at {$companyName} was updated to: {$statusMap[$status]['label']}.",
                (int) $application->id
            );
        }

        if ($result && ($application->is_guest ?? false) && !($application->guest_email_sent ?? false)) {
            model(\App\Models\JobApplicationModel::class)
                ->update($application->id, ['guest_email_sent' => 1]);
        }

        return $result;
    }

    /**
     * Send thank you email to guest applicant after application submission
     */
    public function sendGuestApplicationReceivedEmail($application, string $jobTitle, string $companyName): bool
    {
        $data = [
            'name'        => htmlspecialchars(($application->first_name ?? '') . ' ' . ($application->last_name ?? '')),
            'jobTitle'    => htmlspecialchars($jobTitle),
            'companyName' => htmlspecialchars($companyName),
        ];

        return $this->sendEmail(
            $application->email,
            "Application Received - {$jobTitle}",
            'emails/guest_received',
            $data
        );
    }

    /**
     * Send aptitude test invitation email to candidate
     */
    public function sendAptitudeTestInvitationEmail(
        string $candidateEmail,
        string $candidateName,
        string $testTitle,
        string $companyName,
        string $jobTitle,
        string $invitationUrl,
        ?string $dueDate = null,
        ?string $customMessage = null
    ): bool {
        $formattedDueDate = $dueDate ? date('F j, Y', strtotime($dueDate)) : 'No strict deadline';

        $htmlMessage = "
        <p>Dear " . htmlspecialchars($candidateName) . ",</p>
        <p><strong>" . htmlspecialchars($companyName) . "</strong> has invited you to complete an official Aptitude Assessment for your application for <strong>" . htmlspecialchars($jobTitle) . "</strong>.</p>
        <div style='background-color:#f8f9fa; border-left:4px solid #0d6efd; padding:15px; margin:15px 0;'>
            <p style='margin:0 0 5px 0;'><strong>Assessment Title:</strong> " . htmlspecialchars($testTitle) . "</p>
            <p style='margin:0 0 5px 0;'><strong>Completion Deadline:</strong> {$formattedDueDate}</p>
            " . ($customMessage ? "<p style='margin:10px 0 0 0;'><strong>Instructions:</strong> " . nl2br(htmlspecialchars($customMessage)) . "</p>" : "") . "
        </div>
        <p style='text-align:center; margin:25px 0;'>
            <a href='{$invitationUrl}' style='background-color:#0d6efd; color:#ffffff; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold; display:inline-block;'>Start Aptitude Assessment</a>
        </p>
        <p>If the button above does not work, copy and paste this link into your browser:<br><a href='{$invitationUrl}'>{$invitationUrl}</a></p>
        <p>Best regards,<br>The Recruitment Team at " . htmlspecialchars($companyName) . "</p>";

        $data = [
            'name'        => htmlspecialchars($candidateName),
            'statusLabel' => 'Aptitude Test Invitation',
            'statusColor' => '#0d6efd',
            'message'     => $htmlMessage,
            'companyName' => htmlspecialchars($companyName),
            'jobTitle'    => htmlspecialchars($jobTitle),
        ];

        return $this->sendEmail(
            $candidateEmail,
            "Aptitude Test Invitation: {$testTitle} - {$companyName}",
            'emails/application_status',
            $data
        );
    }

    /**
     * Send Employer Verification Submitted Confirmation
     */
    public function sendEmployerVerificationSubmittedEmail($employer, $user): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $companyName = is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer');

        $data = [
            'company_name' => $companyName,
            'submitted_at' => date('F j, Y, g:i A'),
            'logoUrl'      => base_url('images/logo.png')
        ];

        return $this->sendEmail(
            $email,
            "Verification Documents Received - {$companyName}",
            'emails/employer_verification_submitted',
            $data
        );
    /**
     * Send email to employer when a candidate completes a tracked assessment link.
     */
    public function sendAssessmentLinkCompletedEmail(string $employerEmail, string $employerName, string $candidateName, string $testTitle, float $scorePct, string $candidateProfileUrl): bool
    {
        $data = [
            'employer_name'         => $employerName,
            'candidate_name'        => $candidateName,
            'test_title'            => $testTitle,
            'score_percentage'      => $scorePct,
            'passed'                => ($scorePct >= 50), // Simplified display
            'completed_at'          => date('F j, Y, g:i A'),
            'candidate_profile_url' => $candidateProfileUrl,
        ];

        return $this->sendEmail(
            $employerEmail,
            "Assessment Completed: {$candidateName} scored {$scorePct}%",
            'emails/assessment_link_completed',
            $data
        );
    }
     * Resolve Admin Email address dynamically
     */
    public function getAdminEmail(): string
    {
        $adminEmail = env('email.admin_email') ?: env('ADMIN_EMAIL');

        if (empty($adminEmail) && function_exists('get_site_setting')) {
            $adminEmail = get_site_setting('admin_email');
        }

        if (empty($adminEmail)) {
            try {
                $adminUser = model(\App\Models\UserModel::class)
                    ->where('user_type', 'admin')
                    ->first();
                if ($adminUser && !empty($adminUser->email)) {
                    $adminEmail = $adminUser->email;
                }
            } catch (\Throwable $e) {
                // Ignore DB lookup error
            }
        }

        if (empty($adminEmail)) {
            $adminEmail = config('Email')->fromEmail ?? 'admin@jobberrecruit.com';
        }

        return $adminEmail;
    }

    /**
     * Send Admin Alert for Pending Employer Verification
     */
    public function sendEmployerVerificationAdminAlertEmail($employer, $user): bool
    {
        $adminEmail = $this->getAdminEmail();
        $companyName = is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer');
        $contactEmail = is_object($user) ? $user->email : ($user['email'] ?? 'N/A');

        $data = [
            'company_name'  => $companyName,
            'contact_email' => $contactEmail,
            'submitted_at'  => date('F j, Y, g:i A')
        ];

        return $this->sendEmail(
            $adminEmail,
            "🛡️ Admin Alert: Verification Pending for {$companyName}",
            'emails/employer_verification_admin_alert',
            $data
        );
    }

    /**
     * Send Employer Verification Approved Email
     */
    public function sendEmployerVerificationApprovedEmail($employer, $user, string $notes = ''): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $companyName = is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer');
        $contactName = is_object($employer) ? ($employer->contact_person ?? $user->username ?? 'Team') : ($employer['contact_person'] ?? 'Team');

        $data = [
            'company_name'      => $companyName,
            'contact_name'      => $contactName,
            'verification_date' => date('F j, Y'),
            'notes'             => $notes
        ];

        return $this->sendEmail(
            $email,
            "Congratulations! Your Employer Account Has Been Verified - {$companyName}",
            'emails/verification_approved',
            $data
        );
    }

    /**
     * Send Employer Verification Rejected Email
     */
    public function sendEmployerVerificationRejectedEmail($employer, $user, string $reason = ''): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $companyName = is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer');
        $contactName = is_object($employer) ? ($employer->contact_person ?? $user->username ?? 'Team') : ($employer['contact_person'] ?? 'Team');

        $data = [
            'company_name'     => $companyName,
            'contact_name'     => $contactName,
            'review_date'      => date('F j, Y'),
            'rejection_reason' => !empty($reason) ? $reason : 'The uploaded documentation did not meet our verification guidelines.'
        ];

        return $this->sendEmail(
            $email,
            "Verification Update Required - {$companyName}",
            'emails/verification_rejected',
            $data
        );
    }

    /**
     * Send Job Posting Submitted Notification to Employer
     */
    public function sendJobPostingSubmittedEmail($job, $employer, $user): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? '');
        $jobTitle = is_object($job) ? $job->title : ($job['title'] ?? 'Job Posting');
        $companyName = is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer');

        $data = [
            'job_title'     => $jobTitle,
            'employer_name' => $companyName,
            'created_at'    => date('F j, Y')
        ];

        return $this->sendEmail(
            $email,
            "Job Submitted for Review: {$jobTitle}",
            'emails/job_submitted',
            $data
        );
    }

    /**
     * Send Admin Alert for Pending Job Posting
     */
    public function sendJobPostingAdminAlertEmail($job, $employer): bool
    {
        $adminEmail = $this->getAdminEmail();
        $jobTitle = is_object($job) ? $job->title : ($job['title'] ?? 'Job Posting');
        $companyName = is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer');

        $data = [
            'job_title'    => $jobTitle,
            'company_name' => $companyName,
            'location'     => is_object($job) ? ($job->location ?? 'Nigeria') : ($job['location'] ?? 'Nigeria'),
            'submitted_at' => date('F j, Y, g:i A')
        ];

        return $this->sendEmail(
            $adminEmail,
            "📋 Admin Alert: New Job Awaiting Approval - {$jobTitle}",
            'emails/job_posting_admin_alert',
            $data
        );
    }

    /**
     * Send Aptitude Test Completed Notification to Employer
     */
    public function sendAptitudeTestCompletedEmployerAlert(
        string $employerEmail,
        string $companyName,
        string $candidateName,
        string $testTitle,
        string $jobTitle,
        float $scorePercentage,
        bool $passed,
        string $resultUrl
    ): bool {
        $data = [
            'company_name'     => $companyName,
            'candidate_name'   => $candidateName,
            'test_title'       => $testTitle,
            'job_title'        => $jobTitle,
            'score_percentage' => round($scorePercentage, 1),
            'passed'           => $passed,
            'result_url'       => $resultUrl,
            'completed_at'     => date('F j, Y, g:i A'),
            'logoUrl'          => base_url('images/logo.png')
        ];

        return $this->sendEmail(
            $employerEmail,
            "Assessment Completed: {$candidateName} scored {$data['score_percentage']}% - {$jobTitle}",
            'emails/aptitude_test_completed_employer',
            $data
        );
    }

    /**
     * Send Purchase Invoice Receipt Email
     */
    public function sendPurchaseInvoiceEmail($user, array $invoiceData): bool
    {
        $email = is_object($user) ? $user->email : ($user['email'] ?? ($invoiceData['email'] ?? ''));
        $userName = is_object($user) ? ($user->username ?? $user->first_name ?? 'Customer') : ($user['username'] ?? 'Customer');

        $data = [
            'userName'       => $userName,
            'reference'      => $invoiceData['reference'] ?? ('INV-' . strtoupper(uniqid())),
            'itemName'       => $invoiceData['item_name'] ?? 'JobberRecruit Service',
            'itemCategory'   => $invoiceData['category'] ?? 'Digital Subscription / Training',
            'amount'         => (float) ($invoiceData['amount'] ?? 0),
            'paidAt'         => $invoiceData['paid_at'] ?? date('F j, Y, g:i A'),
            'paymentChannel' => $invoiceData['channel'] ?? 'Paystack',
            'accessUrl'      => $invoiceData['access_url'] ?? base_url('login'),
            'buttonText'     => $invoiceData['button_text'] ?? 'Go to Dashboard',
            'supportEmail'   => 'support@jobberrecruit.com',
            'logoUrl'        => base_url('images/logo.png')
        ];

        $subject = "Official Invoice & Receipt: {$data['itemName']} (#{$data['reference']})";

        return $this->sendEmail(
            $email,
            $subject,
            'emails/purchase_invoice',
            $data
        );
    }

    /**
     * Send Job Approved Notification to Employer (Email + In-App)
     */
    public function sendJobApprovedNotification($job, $employer = null): bool
    {
        try {
            if (is_numeric($job)) {
                $job = model(\App\Models\JobModel::class)->find($job);
            }
            if (!$job) return false;

            $employerModel = model(EmployerModel::class);
            if (!$employer) {
                $employer = $employerModel->find($job->employer_id);
            }
            if (!$employer) return false;

            $userModel = model(\App\Models\UserModel::class);
            $user = $userModel->find($employer->user_id);
            $recipientEmail = $employer->contact_email ?? ($user->email ?? null);
            if (!$recipientEmail) return false;

            $creditService = new \App\Services\CreditService();
            $creditBalance = $creditService->getAvailableCredits($employer->user_id);
            $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($employer->user_id);
            $currentPlan = $creditService->getCurrentPlan($employer->user_id);

            $jobModel = model(\App\Models\JobModel::class);
            $totalJobsPosted = $jobModel->where('employer_id', $employer->id)->countAllResults();
            $pendingJobs = $jobModel->where('employer_id', $employer->id)->where('admin_status', 'pending')->countAllResults();
            $approvedJobs = $jobModel->where('employer_id', $employer->id)->where('admin_status', 'approved')->countAllResults();

            $subModel = model(\App\Models\UserSubscriptionModel::class);
            $activeSub = $subModel->where('user_id', $employer->user_id)->where('is_active', 1)->where('ends_at >', date('Y-m-d H:i:s'))->first();

            $jobTitle = is_object($job) ? $job->title : ($job['title'] ?? 'Job Opportunity');
            $jobId = is_object($job) ? $job->id : ($job['id'] ?? 0);
            $jobSlug = is_object($job) ? ($job->slug ?? $jobId) : ($job['slug'] ?? $jobId);
            $jobCreatedAt = is_object($job) ? ($job->created_at ?? date('Y-m-d')) : ($job['created_at'] ?? date('Y-m-d'));

            $data = [
                'employer_name'        => is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer'),
                'job_title'            => $jobTitle,
                'job_url'              => base_url('jobs/' . $jobSlug),
                'action_url'           => base_url('jobs/' . $jobSlug),
                'dashboard_url'        => base_url('employer/dashboard'),
                'jobs_url'             => base_url('employer/jobs'),
                'pricing_url'          => base_url('employer/pricing'),
                'job_created_at'       => date('F j, Y', strtotime($jobCreatedAt)),
                'total_jobs_posted'    => $totalJobsPosted,
                'approved_jobs'        => $approvedJobs,
                'pending_jobs'         => $pendingJobs,
                'has_unlimited_access' => $hasUnlimitedAccess,
                'credit_balance'       => $creditBalance,
                'current_plan'         => $currentPlan,
                'subscription_ends_at' => $activeSub ? $activeSub->ends_at : null,
                'platform_name'        => config('App')->appName ?? 'JobberRecruit',
                'companyName'          => is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'JobberRecruit'),
            ];

            // 1. Send In-App Notification to Employer
            $notifModel = model(JobNotificationModel::class);
            $notifModel->createNotification(
                (int) $employer->id,
                'job_approved',
                'Job Approved - ' . $jobTitle,
                "Your job '{$jobTitle}' has been approved and is now live on our platform.",
                (int) $jobId
            );

            // 2. Send Email
            return $this->sendEmail(
                $recipientEmail,
                "Job Approved: {$jobTitle} is now live!",
                'emails/job_approved',
                $data
            );
        } catch (\Throwable $e) {
            log_message('error', 'sendJobApprovedNotification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Job Rejected Notification to Employer (Email + In-App)
     */
    public function sendJobRejectedNotification($job, $employer = null, string $reason = ''): bool
    {
        try {
            if (is_numeric($job)) {
                $job = model(\App\Models\JobModel::class)->find($job);
            }
            if (!$job) return false;

            $employerModel = model(EmployerModel::class);
            if (!$employer) {
                $employer = $employerModel->find($job->employer_id);
            }
            if (!$employer) return false;

            $userModel = model(\App\Models\UserModel::class);
            $user = $userModel->find($employer->user_id);
            $recipientEmail = $employer->contact_email ?? ($user->email ?? null);
            if (!$recipientEmail) return false;

            $jobTitle = is_object($job) ? $job->title : ($job['title'] ?? 'Job Opportunity');
            $jobId = is_object($job) ? $job->id : ($job['id'] ?? 0);
            $rejectionReason = !empty($reason) ? $reason : 'Your job posting did not meet our quality guidelines. Please review and update.';

            $data = [
                'employer_name' => is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'Employer'),
                'job_title'     => $jobTitle,
                'reason'        => $rejectionReason,
                'platform_name' => config('App')->appName ?? 'JobberRecruit',
                'dashboard_url' => base_url('employer/jobs'),
                'companyName'   => is_object($employer) ? $employer->company_name : ($employer['company_name'] ?? 'JobberRecruit'),
            ];

            // 1. Send In-App Notification
            $notifModel = model(JobNotificationModel::class);
            $notifModel->createNotification(
                (int) $employer->id,
                'job_rejected',
                'Job Not Approved - ' . $jobTitle,
                "Your job '{$jobTitle}' was not approved: " . substr($rejectionReason, 0, 120),
                (int) $jobId
            );

            // 2. Send Email
            return $this->sendEmail(
                $recipientEmail,
                "Job Update: {$jobTitle}",
                'emails/job_rejected',
                $data
            );
        } catch (\Throwable $e) {
            log_message('error', 'sendJobRejectedNotification error: ' . $e->getMessage());
            return false;
        }
    }
}
