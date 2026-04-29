<?php

namespace App\Services;

use CodeIgniter\Config\Services;

class EmailNotificationService
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    protected function sendEmail(string $to, string $subject, string $view, array $data): bool
    {
        $this->email->clear();

        $this->email->setTo($to);
        $this->email->setFrom(env('email.from_email', 'no-reply@jobberrecruit.com'), $data['companyName'] ?? 'Jobber Recruit');
        $this->email->setSubject($subject);

        // Render view template
        $html = view($view, $data);

        $this->email->setMessage($html);
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
     * Send application status update email to job seeker
     */
    public function sendApplicationStatusEmail($application, string $status, string $jobTitle, string $companyName, ?string $messageToCandidate = null): bool
    {
        $statusMap = [
            'pending' => ['label' => 'Pending Review', 'color' => '#ffc107'],
            'reviewed' => ['label' => 'Application Reviewed', 'color' => '#17a2b8'],
            'shortlisted' => ['label' => 'Shortlisted!', 'color' => '#28a745'],
            'rejected' => ['label' => 'Application Update', 'color' => '#dc3545'],
            'hired' => ['label' => "Congratulations! You're Hired!", 'color' => '#28a745'],
        ];

        if (!isset($statusMap[$status])) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $defaultMessages = [
            'pending' => "Your application for <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong> has been received and is currently under review.",
            'reviewed' => "Your application for <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong> has been reviewed by our team.",
            'shortlisted' => "Congratulations! You have been shortlisted for the position of <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong>. Our recruitment team will contact you shortly to schedule an interview.",
            'rejected' => "Thank you for your interest in the position of <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong>. After careful review of all applications, we have decided to move forward with other candidates.",
            'hired' => "Congratulations! We are pleased to inform you that you have been selected for the position of <strong>{$jobTitle}</strong> at <strong>{$companyName}</strong>. Our HR team will contact you shortly with the offer letter and onboarding details.",
        ];

        // Use custom message if provided, otherwise use default
        $finalMessage = $messageToCandidate
            ? nl2br(htmlspecialchars($messageToCandidate))
            : $defaultMessages[$status];

        $data = [
            'name' => htmlspecialchars($application->first_name . ' ' . $application->last_name),
            'statusLabel' => $statusMap[$status]['label'],
            'statusColor' => $statusMap[$status]['color'],
            'message' => $finalMessage,
            'isGuest' => (bool)($application->is_guest ?? false),
            'companyName' => htmlspecialchars($companyName),
            'jobTitle' => htmlspecialchars($jobTitle),
        ];

        $subject = "Application Status Update: " . $statusMap[$status]['label'];

        $result = $this->sendEmail(
            $application->email,
            $subject,
            'emails/application_status',
            $data
        );

        // Update guest email sent flag
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
            'name' => htmlspecialchars($application->first_name . ' ' . $application->last_name),
            'jobTitle' => htmlspecialchars($jobTitle),
            'companyName' => htmlspecialchars($companyName),
        ];

        return $this->sendEmail(
            $application->email,
            "Application Received - {$jobTitle}",
            'emails/guest_received',
            $data
        );
    }
}
