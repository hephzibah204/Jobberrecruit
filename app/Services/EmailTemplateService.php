<?php

namespace App\Services;

use App\Models\EmailTemplateModel;
use Config\Services;

class EmailTemplateService
{
    protected $email;
    protected $templateModel;

    public function __construct()
    {
        $this->email = Services::email();
        $this->templateModel = model(EmailTemplateModel::class);
    }

    /**
     * Render subject, HTML body, and text body for a template
     */
    public function render(string $templateKey, array $data = []): array
    {
        $template = null;
        try {
            $template = $this->templateModel->where('template_key', $templateKey)->first();
        } catch (\Throwable $e) {
            log_message('notice', "EmailTemplate DB lookup note: " . $e->getMessage());
        }

        // If not in database or inactive, fallback to system default definition
        if (!$template) {
            $default = $this->getDefaultTemplate($templateKey);
            if ($default) {
                $greetingType = $default['greeting_type'] ?? 'first_name';
                $subject = EmailTemplateModel::parseVariables($default['subject'], $data, $greetingType);
                $html    = EmailTemplateModel::parseVariables($default['body_html'], $data, $greetingType);
                $text    = EmailTemplateModel::parseVariables($default['body_text'] ?? strip_tags($default['body_html']), $data, $greetingType);
                return [
                    'subject' => $subject,
                    'html'    => $html,
                    'text'    => $text,
                    'template'=> (object)$default,
                ];
            }

            // Ultimate fallback to view file if it exists
            $viewPath = "emails/{$templateKey}";
            $subject = $data['subject'] ?? 'Notification from JobberRecruit';
            $html = is_file(APPPATH . "Views/{$viewPath}.php") ? view($viewPath, $data) : '';
            return [
                'subject' => $subject,
                'html'    => $html,
                'text'    => strip_tags($html),
                'template'=> null,
            ];
        }

        $greetingType = $template->greeting_type ?? 'first_name';
        $subject = EmailTemplateModel::parseVariables($template->subject, $data, $greetingType);
        $html    = EmailTemplateModel::parseVariables($template->body_html, $data, $greetingType);
        $text    = !empty($template->body_text)
            ? EmailTemplateModel::parseVariables($template->body_text, $data, $greetingType)
            : strip_tags($html);

        return [
            'subject' => $subject,
            'html'    => $html,
            'text'    => $text,
            'template'=> $template,
        ];
    }

    /**
     * Send email using a templated key
     */
    public function send(string $templateKey, string $to, array $data = [], ?string $replyTo = null): bool
    {
        $rendered = $this->render($templateKey, $data);

        $this->email->clear();
        $this->email->setTo($to);

        $fromEmail = env('email.fromEmail') ?: config('Email')->fromEmail ?? 'no-reply@jobberrecruit.com';
        $fromName  = $data['from_name'] ?? ($data['companyName'] ?? (env('email.fromName') ?: config('Email')->fromName ?? 'JobberRecruit'));

        $this->email->setFrom($fromEmail, $fromName);
        $this->email->setSubject($rendered['subject']);
        $this->email->setMessage($rendered['html']);
        if (!empty($rendered['text'])) {
            $this->email->setAltMessage($rendered['text']);
        }
        $this->email->setMailType('html');

        if ($replyTo) {
            $this->email->setReplyTo($replyTo);
        }

        try {
            $result = $this->email->send();
            if (!$result) {
                log_message('error', "EmailTemplateService send failed for [{$templateKey}] to [{$to}]: " . $this->email->printDebugger(['headers']));
            }
            return $result;
        } catch (\Throwable $e) {
            log_message('error', "EmailTemplateService exception for [{$templateKey}] to [{$to}]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reset a template to its factory default content
     */
    public function resetToDefault(string $templateKey): bool
    {
        $default = $this->getDefaultTemplate($templateKey);
        if (!$default) {
            return false;
        }

        $existing = $this->templateModel->where('template_key', $templateKey)->first();
        if ($existing) {
            return $this->templateModel->update($existing->id, [
                'name'                => $default['name'],
                'category'            => $default['category'],
                'subject'             => $default['subject'],
                'greeting_type'       => $default['greeting_type'],
                'body_html'           => $default['body_html'],
                'body_text'           => $default['body_text'],
                'available_variables' => json_encode($default['available_variables']),
                'is_active'           => 1,
            ]);
        }

        return (bool) $this->templateModel->insert([
            'template_key'        => $templateKey,
            'name'                => $default['name'],
            'category'            => $default['category'],
            'subject'             => $default['subject'],
            'greeting_type'       => $default['greeting_type'],
            'body_html'           => $default['body_html'],
            'body_text'           => $default['body_text'],
            'available_variables' => json_encode($default['available_variables']),
            'is_active'           => 1,
        ]);
    }

    /**
     * Automatically ensure email_templates table exists in database
     */
    public function ensureTableExists(): void
    {
        try {
            $db = \Config\Database::connect();
            if (!$db->tableExists('email_templates')) {
                if ($db->DBDriver === 'SQLite3') {
                    $db->query("CREATE TABLE IF NOT EXISTS email_templates (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        template_key VARCHAR(100) UNIQUE NOT NULL,
                        name VARCHAR(255) NOT NULL,
                        category VARCHAR(50) NOT NULL,
                        subject VARCHAR(255) NOT NULL,
                        greeting_type VARCHAR(20) DEFAULT 'first_name',
                        body_html TEXT NOT NULL,
                        body_text TEXT NULL,
                        available_variables TEXT NULL,
                        is_active INTEGER DEFAULT 1,
                        created_at DATETIME NULL,
                        updated_at DATETIME NULL
                    )");
                } else {
                    $db->query("CREATE TABLE IF NOT EXISTS `email_templates` (
                        `id`                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `template_key`        VARCHAR(100) NOT NULL UNIQUE,
                        `name`                VARCHAR(255) NOT NULL,
                        `category`            VARCHAR(50) NOT NULL,
                        `subject`             VARCHAR(255) NOT NULL,
                        `greeting_type`       ENUM('first_name', 'full_name', 'custom') DEFAULT 'first_name',
                        `body_html`           MEDIUMTEXT NOT NULL,
                        `body_text`           TEXT NULL,
                        `available_variables` TEXT NULL,
                        `is_active`           TINYINT(1) DEFAULT 1,
                        `created_at`          DATETIME NULL,
                        `updated_at`          DATETIME NULL,
                        KEY `idx_template_key` (`template_key`),
                        KEY `idx_category` (`category`),
                        KEY `idx_is_active` (`is_active`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'ensureTableExists error: ' . $e->getMessage());
        }
    }

    /**
     * Seed all default system email templates into DB if missing
     */
    public function seedDefaults(): int
    {
        $this->ensureTableExists();
        $defaults = $this->getAllDefaultTemplates();
        $count = 0;

        try {
            foreach ($defaults as $key => $tpl) {
                $existing = $this->templateModel->where('template_key', $key)->first();
                if (!$existing) {
                    $this->templateModel->insert([
                        'template_key'        => $key,
                        'name'                => $tpl['name'],
                        'category'            => $tpl['category'],
                        'subject'             => $tpl['subject'],
                        'greeting_type'       => $tpl['greeting_type'] ?? 'first_name',
                        'body_html'           => $tpl['body_html'],
                        'body_text'           => $tpl['body_text'] ?? strip_tags($tpl['body_html']),
                        'available_variables' => json_encode($tpl['available_variables'] ?? []),
                        'is_active'           => 1,
                    ]);
                    $count++;
                }
            }
        } catch (\Throwable $e) {
            log_message('notice', 'EmailTemplateService seed skipped (table might be initializing): ' . $e->getMessage());
        }

        return $count;
    }

    /**
     * Get realistic sample data for preview and test email
     */
    public function getSampleData(string $templateKey): array
    {
        return [
            'first_name'        => 'Alexander',
            'last_name'         => 'Johnson',
            'full_name'         => 'Alexander Johnson',
            'name'              => 'Alexander Johnson',
            'userName'          => 'Alexander Johnson',
            'email'             => 'alexander.johnson@example.com',
            'job_title'         => 'Senior Full-Stack Software Engineer',
            'company_name'      => 'Apex Global Technologies Ltd',
            'companyName'       => 'Apex Global Technologies Ltd',
            'location'          => 'Lagos, Nigeria',
            'statusLabel'       => 'Shortlisted for Interview',
            'statusColor'       => '#28a745',
            'message'           => 'Congratulations! You have been shortlisted for this position. Our recruitment team will reach out soon.',
            'verifyUrl'         => base_url('auth/verify?token=sample_verification_token_12345'),
            'resetLink'         => base_url('auth/reset-password?token=sample_reset_token_12345'),
            'action_url'        => base_url('candidate/dashboard'),
            'webinarTitle'      => 'Mastering High-Impact Tech Interviews & Career Growth',
            'presenter'         => 'Dr. Samuel Okafor (Principal Talent Lead)',
            'scheduledAt'       => date('F j, Y \a\t 4:00 PM', strtotime('+2 days')),
            'timeFrameLabel'    => 'in 24 Hours',
            'joinUrl'           => base_url('webinars'),
            'course_title'      => 'Executive Leadership & Project Mastery Certification',
            'course_id'         => 1,
            'certificateUrl'    => base_url('candidate/my-courses/1'),
            'planName'          => 'Employer Professional Plus',
            'expirationDate'    => date('F j, Y', strtotime('+30 days')),
            'daysRemaining'     => 5,
            'renewUrl'          => base_url('employer/pricing'),
            'reference'         => 'INV-2026-' . strtoupper(substr(md5(time()), 0, 6)),
            'itemName'          => 'Employer Candidate Search & Job Bundle',
            'itemCategory'      => 'Employer Recruitment Subscription',
            'amount'            => 45000,
            'paidAt'            => date('F j, Y, g:i A'),
            'paymentChannel'    => 'Paystack Card / Bank Transfer',
            'accessUrl'         => base_url('employer/dashboard'),
            'buttonText'        => 'Access Employer Dashboard',
            'notes'             => 'All documents verified and compliant with JobberRecruit verification policy.',
            'rejection_reason'  => 'Business registration document was blurry. Please re-upload a clear CAC certificate.',
            'test_title'        => 'Quantitative & Technical Aptitude Assessment',
            'invitationUrl'     => base_url('aptitude/invite/sample_invitation_code_123'),
            'score_percentage'  => 88.5,
            'passed'            => true,
            'resultUrl'         => base_url('employer/aptitude/results/1'),
        ];
    }

    /**
     * Get single default template
     */
    public function getDefaultTemplate(string $key): ?array
    {
        $all = $this->getAllDefaultTemplates();
        return $all[$key] ?? null;
    }

    /**
     * Base HTML Wrapper for consistent, high-conversion responsive email design
     */
    public static function wrapHtml(string $title, string $headerBadge, string $contentBody, ?string $buttonText = null, ?string $buttonUrl = null): string
    {
        $buttonHtml = '';
        if (!empty($buttonText) && !empty($buttonUrl)) {
            $buttonHtml = '
            <div style="text-align: center; margin: 32px 0 20px 0;">
                <a href="' . $buttonUrl . '" style="background: linear-gradient(135deg, #0D609E 0%, #00457A 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 4px 12px rgba(13, 96, 158, 0.25);">' . $buttonText . '</a>
            </div>';
        }

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
    <style>
        body { margin: 0; padding: 0; background-color: #F4F7FB; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; color: #1E293B; }
        .wrapper { width: 100%; background-color: #F4F7FB; padding: 30px 10px; }
        .main-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08); border: 1px solid #E2E8F0; }
        .header { background: #0D609E; padding: 26px 30px; text-align: center; }
        .header img { max-width: 190px; width: 100%; height: auto; display: block; margin: 0 auto; }
        .badge { display: inline-block; background: rgba(255, 255, 255, 0.18); color: #ffffff; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 10px; }
        .body-content { padding: 32px 30px; line-height: 1.65; font-size: 15px; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 22px 30px; text-align: center; font-size: 12px; color: #64748B; line-height: 1.5; }
        .footer a { color: #0D609E; text-decoration: none; }
        .info-box { background: #F8FAFC; border-left: 4px solid #0D609E; padding: 16px 20px; border-radius: 6px; margin: 20px 0; }
        @media only screen and (max-width: 600px) {
            .body-content { padding: 24px 18px !important; }
            .header { padding: 20px 18px !important; }
            .header img { max-width: 160px !important; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="main-card">
        <div class="header">
            <a href="{site_url}"><img src="{logo_url}" alt="{site_name}" width="190" style="max-width: 190px; width: 100%; height: auto; display: block; margin: 0 auto; border: 0;"></a>
            ' . (!empty($headerBadge) ? '<div style="margin-top: 10px;"><span class="badge">' . $headerBadge . '</span></div>' : '') . '
        </div>
        <div class="body-content">
            ' . $contentBody . '
            ' . $buttonHtml . '
        </div>
        <div class="footer">
            <p style="margin: 0 0 6px 0;"><strong>{site_name}</strong> — Nigeria\'s Leading Job & Recruitment Platform</p>
            <p style="margin: 0 0 6px 0;">Need assistance? Contact us at <a href="mailto:{support_email}">{support_email}</a></p>
            <p style="margin: 0; color: #94A3B8;">&copy; {current_year} {site_name}. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>';
    }

    /**
     * Complete library of all system default email templates
     */
    public function getAllDefaultTemplates(): array
    {
        return [
            // ==========================================
            // 1. AUTHENTICATION & SECURITY
            // ==========================================
            'verify_email' => [
                'name'     => 'Email Address Verification',
                'category' => 'Authentication',
                'subject'  => 'Verify Your Email Address — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Verify Your Email',
                    'Account Activation',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Thank you for signing up on <strong>{site_name}</strong>. We are thrilled to welcome you aboard!</p>
                    <p>Please confirm your email address by clicking the activation button below to unlock full access to job opportunities, employer matching, and learning resources.</p>
                    <p style="color: #64748B; font-size: 13px; margin-top: 25px;">If the button does not work, copy and paste this link into your browser:<br><a href="{verifyUrl}" style="color: #0D609E; word-break: break-all;">{verifyUrl}</a></p>',
                    'Verify My Email Address',
                    '{verifyUrl}'
                ),
                'body_text' => "{greeting}\n\nThank you for signing up on {site_name}!\n\nPlease verify your email address by visiting this link:\n{verifyUrl}\n\nBest regards,\nThe {site_name} Team",
                'available_variables' => [
                    '{first_name}' => 'Recipient\'s first name',
                    '{last_name}'  => 'Recipient\'s last name',
                    '{full_name}'  => 'Recipient\'s full name',
                    '{greeting}'   => 'Dynamic greeting (e.g. Hello John,)',
                    '{verifyUrl}'  => 'Secure account activation URL',
                    '{site_name}'  => 'Website name (JobberRecruit)',
                ],
            ],

            'password_reset' => [
                'name'     => 'Password Reset Link',
                'category' => 'Authentication',
                'subject'  => 'Reset Your Password — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Password Reset Request',
                    'Security & Access',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>We received a request to reset the password for your <strong>{site_name}</strong> account.</p>
                    <p>Click the button below to choose a new password. For security purposes, this link will expire in 1 hour.</p>
                    <div class="info-box">
                        <strong>Security Note:</strong> If you did not request this password reset, please ignore this email or contact support immediately. Your account remains completely secure.
                    </div>
                    <p style="color: #64748B; font-size: 13px; margin-top: 25px;">If the button does not work, copy and paste this link into your browser:<br><a href="{resetLink}" style="color: #0D609E; word-break: break-all;">{resetLink}</a></p>',
                    'Reset My Password',
                    '{resetLink}'
                ),
                'body_text' => "{greeting}\n\nWe received a request to reset your password on {site_name}.\n\nReset your password here:\n{resetLink}\n\nIf you did not request this, please ignore this email.",
                'available_variables' => [
                    '{first_name}' => 'Recipient\'s first name',
                    '{full_name}'  => 'Recipient\'s full name',
                    '{greeting}'   => 'Dynamic greeting',
                    '{resetLink}'  => 'Password reset URL',
                    '{site_name}'  => 'JobberRecruit',
                ],
            ],

            // ==========================================
            // 2. CANDIDATE & APPLICATIONS
            // ==========================================
            'application_status' => [
                'name'     => 'Application Status Update',
                'category' => 'Applications',
                'subject'  => 'Application Update: {job_title} at {company_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Application Status Update',
                    'Application Notice',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>You have an update regarding your application for the role of <strong>{job_title}</strong> at <strong>{company_name}</strong>.</p>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px 20px; margin: 20px 0;">
                        <div style="font-size: 13px; color: #64748B; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Current Status</div>
                        <div style="font-size: 18px; font-weight: 700; color: #0D609E; margin-top: 4px;">{statusLabel}</div>
                        <div style="margin-top: 12px; font-size: 14.5px; color: #334155; line-height: 1.6;">{message}</div>
                    </div>
                    <p>You can track all your active applications and interview invitations anytime on your candidate dashboard.</p>',
                    'View My Application',
                    '{site_url}/candidate/applications'
                ),
                'body_text' => "{greeting}\n\nYour application status for {job_title} at {company_name} was updated to: {statusLabel}.\n\nMessage: {message}\n\nTrack your applications: {site_url}/candidate/applications",
                'available_variables' => [
                    '{first_name}'   => 'Candidate\'s first name',
                    '{full_name}'    => 'Candidate\'s full name',
                    '{job_title}'    => 'Job title',
                    '{company_name}' => 'Hiring company name',
                    '{statusLabel}'  => 'New status (e.g. Shortlisted, Hired, Reviewed)',
                    '{message}'      => 'Detailed message to candidate',
                ],
            ],

            'guest_received' => [
                'name'     => 'Guest Application Received',
                'category' => 'Applications',
                'subject'  => 'Application Received: {job_title} — {company_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Application Received',
                    'Application Confirmation',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>We are pleased to confirm that your job application for <strong>{job_title}</strong> at <strong>{company_name}</strong> has been successfully received.</p>
                    <div class="info-box">
                        <strong>Position:</strong> {job_title}<br>
                        <strong>Company:</strong> {company_name}<br>
                        <strong>Date Submitted:</strong> ' . date('F j, Y') . '
                    </div>
                    <p>The recruitment team at {company_name} will review your credentials. You will receive updates directly at this email address.</p>
                    <p>To unlock automated job match alerts, build professional resumes, and take skill assessments, create a free candidate account.</p>',
                    'Create Candidate Profile',
                    '{site_url}/register'
                ),
                'body_text' => "{greeting}\n\nYour application for {job_title} at {company_name} has been received.\n\nThe hiring team will review your application.\n\nCreate a free account to track status: {site_url}/register",
                'available_variables' => [
                    '{first_name}'   => 'Candidate\'s first name',
                    '{full_name}'    => 'Candidate\'s full name',
                    '{job_title}'    => 'Job title',
                    '{company_name}' => 'Hiring company name',
                ],
            ],

            'aptitude_test_invitation' => [
                'name'     => 'Aptitude Assessment Invitation',
                'category' => 'Applications',
                'subject'  => 'Assessment Invitation: {test_title} — {company_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Aptitude Assessment Invitation',
                    'Official Assessment',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p><strong>{company_name}</strong> has invited you to complete an online Aptitude Assessment for your application for <strong>{job_title}</strong>.</p>
                    <div class="info-box">
                        <strong>Assessment:</strong> {test_title}<br>
                        <strong>Position:</strong> {job_title}<br>
                        <strong>Company:</strong> {company_name}
                    </div>
                    <p>Please ensure you are in a quiet environment with a stable internet connection before launching the assessment.</p>',
                    'Start Aptitude Assessment',
                    '{invitationUrl}'
                ),
                'body_text' => "{greeting}\n\n{company_name} has invited you to take an assessment for {job_title}.\n\nAssessment: {test_title}\n\nStart now: {invitationUrl}",
                'available_variables' => [
                    '{first_name}'    => 'Candidate\'s first name',
                    '{full_name}'     => 'Candidate\'s full name',
                    '{company_name}'  => 'Employer company name',
                    '{job_title}'     => 'Job title',
                    '{test_title}'    => 'Assessment title',
                    '{invitationUrl}' => 'Direct assessment link',
                ],
            ],

            'cv_review_received' => [
                'name'     => 'CV Review Request Received',
                'category' => 'Applications',
                'subject'  => 'CV Review Order Received — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'CV Review Received',
                    'Career Services',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Thank you for submitting your CV for professional review on <strong>{site_name}</strong>.</p>
                    <p>Our certified career coaches and AI review engine are analyzing your resume structure, keyword optimization, ATS compatibility, and phrasing.</p>
                    <p>You will receive your full review score, section-by-section breakdown, and actionable rewrite recommendations shortly.</p>',
                    'View CV Review Status',
                    '{site_url}/candidate/cv-reviews'
                ),
                'body_text' => "{greeting}\n\nYour CV has been received for professional review.\n\nYou will receive your full report shortly.\n\nView status: {site_url}/candidate/cv-reviews",
                'available_variables' => [
                    '{first_name}' => 'Candidate\'s first name',
                    '{full_name}'  => 'Candidate\'s full name',
                    '{site_name}'  => 'JobberRecruit',
                ],
            ],

            'cv_review_completed' => [
                'name'     => 'CV Review Completed',
                'category' => 'Applications',
                'subject'  => 'Your Professional CV Review is Ready! — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'CV Review Completed',
                    'Review Ready',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Great news! The comprehensive review of your CV is now complete and ready for your review.</p>
                    <p>Log in to your candidate dashboard to view your ATS score, personalized strengths, formatting improvements, and expert career recommendations.</p>',
                    'Download My CV Review Report',
                    '{site_url}/candidate/cv-reviews'
                ),
                'body_text' => "{greeting}\n\nYour CV Review report is ready.\n\nView your score and report: {site_url}/candidate/cv-reviews",
                'available_variables' => [
                    '{first_name}' => 'Candidate\'s first name',
                    '{full_name}'  => 'Candidate\'s full name',
                    '{site_name}'  => 'JobberRecruit',
                ],
            ],

            // ==========================================
            // 3. EMPLOYER & RECRUITMENT
            // ==========================================
            'employer_new_application' => [
                'name'     => 'New Candidate Application Alert',
                'category' => 'Employer Verification',
                'subject'  => 'New Application Received for {job_title} — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'New Candidate Application',
                    'Applicant Alert',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>A new candidate has just submitted an application for your active listing: <strong>{job_title}</strong>.</p>
                    <div class="info-box">
                        <strong>Applicant Name:</strong> {candidate_name}<br>
                        <strong>Position:</strong> {job_title}<br>
                        <strong>Submitted:</strong> ' . date('F j, Y, g:i A') . '
                    </div>
                    <p>Log in to your employer dashboard to view their profile, download their CV, and manage their recruitment stage.</p>',
                    'Review Candidate Application',
                    '{site_url}/employer/applications'
                ),
                'body_text' => "{greeting}\n\nA new candidate ({candidate_name}) applied for {job_title}.\n\nReview application: {site_url}/employer/applications",
                'available_variables' => [
                    '{first_name}'     => 'Employer contact first name',
                    '{full_name}'      => 'Employer contact full name',
                    '{candidate_name}' => 'Applicant name',
                    '{job_title}'      => 'Job title',
                    '{company_name}'   => 'Company name',
                ],
            ],

            'employer_verification_submitted' => [
                'name'     => 'Employer Verification Submitted',
                'category' => 'Employer Verification',
                'subject'  => 'Verification Documents Received — {company_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Verification Under Review',
                    'Trust & Verification',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Thank you for submitting your corporate verification documents for <strong>{company_name}</strong>.</p>
                    <p>Our compliance team is currently reviewing your documentation. The verification process typically takes 12 to 24 business hours.</p>
                    <p>Once verified, your company profile will receive the official <strong>Verified Employer Trust Badge</strong>, increasing applicant trust and listing prominence.</p>',
                    'View Company Profile',
                    '{site_url}/employer/profile'
                ),
                'body_text' => "{greeting}\n\nYour company verification documents for {company_name} have been received.\n\nOur team will review them within 24 hours.",
                'available_variables' => [
                    '{first_name}'   => 'Contact person first name',
                    '{company_name}' => 'Company name',
                ],
            ],

            'verification_approved' => [
                'name'     => 'Employer Verification Approved',
                'category' => 'Employer Verification',
                'subject'  => 'Congratulations! Your Employer Account Has Been Verified — {company_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Company Verified Successfully',
                    'Verified Employer',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>We are delighted to inform you that the corporate verification for <strong>{company_name}</strong> has been officially approved!</p>
                    <div class="info-box">
                        <strong>Status:</strong> Verified Employer<br>
                        <strong>Company:</strong> {company_name}<br>
                        <strong>Verified On:</strong> ' . date('F j, Y') . '
                    </div>
                    <p>Your company profile and all posted jobs now proudly display the official <strong>Verified Employer Badge</strong>.</p>',
                    'Go to Employer Dashboard',
                    '{site_url}/employer/dashboard'
                ),
                'body_text' => "{greeting}\n\nCongratulations! Your employer account for {company_name} has been verified.\n\nAccess dashboard: {site_url}/employer/dashboard",
                'available_variables' => [
                    '{first_name}'   => 'Contact person first name',
                    '{company_name}' => 'Company name',
                    '{notes}'        => 'Admin verification notes',
                ],
            ],

            'verification_rejected' => [
                'name'     => 'Employer Verification Action Required',
                'category' => 'Employer Verification',
                'subject'  => 'Verification Update Required — {company_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Verification Update Required',
                    'Action Required',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Thank you for submitting your verification details for <strong>{company_name}</strong>. After review, our compliance team requires updated or clearer documentation.</p>
                    <div style="background: #FFF1F2; border-left: 4px solid #E11D48; padding: 16px 20px; border-radius: 6px; margin: 20px 0; color: #881337;">
                        <strong>Reason / Instructions:</strong><br>
                        {rejection_reason}
                    </div>
                    <p>Please log in and re-upload the requested documentation to complete your company verification.</p>',
                    'Re-Upload Verification Documents',
                    '{site_url}/employer/profile'
                ),
                'body_text' => "{greeting}\n\nYour verification for {company_name} requires updated documents.\n\nReason: {rejection_reason}\n\nRe-upload here: {site_url}/employer/profile",
                'available_variables' => [
                    '{first_name}'       => 'Contact person first name',
                    '{company_name}'     => 'Company name',
                    '{rejection_reason}' => 'Explanation of required documents',
                ],
            ],

            'job_submitted' => [
                'name'     => 'Job Posting Submitted for Review',
                'category' => 'Employer Verification',
                'subject'  => 'Job Submitted for Review: {job_title} — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Job Submitted for Review',
                    'Listing Status',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Your job posting for <strong>{job_title}</strong> at <strong>{company_name}</strong> has been submitted successfully.</p>
                    <p>Our quality assurance team reviews all new listings to ensure maximum reach and candidate engagement. You will be notified as soon as it goes live.</p>',
                    'Manage My Job Postings',
                    '{site_url}/employer/jobs'
                ),
                'body_text' => "{greeting}\n\nYour job {job_title} at {company_name} was submitted for review.\n\nManage listings: {site_url}/employer/jobs",
                'available_variables' => [
                    '{first_name}'   => 'Contact person first name',
                    '{company_name}' => 'Company name',
                    '{job_title}'    => 'Job title',
                ],
            ],

            'job_approved' => [
                'name'     => 'Job Posting Approved & Published',
                'category' => 'Employer Verification',
                'subject'  => 'Your Job is Live! {job_title} on {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Job is Live & Published',
                    'Listing Approved',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Great news! Your job posting for <strong>{job_title}</strong> at <strong>{company_name}</strong> has been approved and is now live on <strong>{site_name}</strong>.</p>
                    <p>Candidates matching your job requirements are now receiving instant notifications and submitting applications.</p>',
                    'View Live Job Post',
                    '{action_url}'
                ),
                'body_text' => "{greeting}\n\nYour job {job_title} at {company_name} is now approved and live!\n\nView post: {action_url}",
                'available_variables' => [
                    '{first_name}'   => 'Contact person first name',
                    '{company_name}' => 'Company name',
                    '{job_title}'    => 'Job title',
                    '{action_url}'   => 'Direct public job URL',
                ],
            ],

            'aptitude_test_completed_employer' => [
                'name'     => 'Candidate Assessment Results Alert',
                'category' => 'Employer Verification',
                'subject'  => 'Assessment Completed: {candidate_name} scored {score_percentage}% — {job_title}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Assessment Completed',
                    'Candidate Test Result',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Candidate <strong>{candidate_name}</strong> has completed the aptitude assessment for <strong>{job_title}</strong>.</p>
                    <div class="info-box">
                        <strong>Candidate:</strong> {candidate_name}<br>
                        <strong>Assessment:</strong> {test_title}<br>
                        <strong>Score:</strong> <span style="font-size: 18px; font-weight: 700; color: #0D609E;">{score_percentage}%</span><br>
                        <strong>Completed On:</strong> ' . date('F j, Y, g:i A') . '
                    </div>
                    <p>Log in to view their full answer sheet, time spent, and comparative ranking.</p>',
                    'View Assessment Full Report',
                    '{resultUrl}'
                ),
                'body_text' => "{greeting}\n\nCandidate {candidate_name} completed {test_title} with score {score_percentage}% for {job_title}.\n\nView report: {resultUrl}",
                'available_variables' => [
                    '{first_name}'       => 'Employer contact first name',
                    '{company_name}'     => 'Company name',
                    '{candidate_name}'   => 'Candidate name',
                    '{job_title}'        => 'Job title',
                    '{test_title}'       => 'Assessment title',
                    '{score_percentage}' => 'Score percentage',
                    '{resultUrl}'        => 'Results URL',
                ],
            ],

            // ==========================================
            // 4. LEARNING & WEBINARS
            // ==========================================
            'webinar_registration' => [
                'name'     => 'Webinar Registration Confirmed',
                'category' => 'Webinars & Courses',
                'subject'  => 'Registration Confirmed: {webinarTitle} — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Webinar Registration Confirmed',
                    'Live Event',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>You have successfully registered for the live webinar: <strong>{webinarTitle}</strong>.</p>
                    <div class="info-box">
                        <strong>Webinar:</strong> {webinarTitle}<br>
                        <strong>Presenter:</strong> {presenter}<br>
                        <strong>Date & Time:</strong> {scheduledAt}
                    </div>
                    <p>We recommend adding this event to your calendar. You will receive a reminder link prior to the session.</p>',
                    'Access Webinar Room',
                    '{joinUrl}'
                ),
                'body_text' => "{greeting}\n\nYou are confirmed for webinar: {webinarTitle}\n\nScheduled for: {scheduledAt}\n\nJoin here: {joinUrl}",
                'available_variables' => [
                    '{first_name}'   => 'Member\'s first name',
                    '{full_name}'    => 'Member\'s full name',
                    '{webinarTitle}' => 'Webinar title',
                    '{presenter}'    => 'Presenter name',
                    '{scheduledAt}'  => 'Event scheduled date and time',
                    '{joinUrl}'      => 'Webinar room link',
                ],
            ],

            'webinar_reminder' => [
                'name'     => 'Webinar Starting Soon Reminder',
                'category' => 'Webinars & Courses',
                'subject'  => 'Reminder: {webinarTitle} starts {timeFrameLabel}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Webinar Starting Soon',
                    'Event Reminder',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>This is a quick reminder that your registered webinar <strong>{webinarTitle}</strong> is starting <strong>{timeFrameLabel}</strong>!</p>
                    <div class="info-box">
                        <strong>Topic:</strong> {webinarTitle}<br>
                        <strong>Presenter:</strong> {presenter}<br>
                        <strong>Time:</strong> {scheduledAt}
                    </div>
                    <p>Click below to join the live classroom early and secure your spot.</p>',
                    'Join Live Webinar Now',
                    '{joinUrl}'
                ),
                'body_text' => "{greeting}\n\nReminder: Webinar {webinarTitle} starts {timeFrameLabel} ({scheduledAt}).\n\nJoin: {joinUrl}",
                'available_variables' => [
                    '{first_name}'     => 'Member\'s first name',
                    '{webinarTitle}'   => 'Webinar title',
                    '{presenter}'      => 'Presenter name',
                    '{scheduledAt}'    => 'Scheduled date & time',
                    '{timeFrameLabel}' => 'Timeframe (e.g. in 24 Hours, in 1 Hour!)',
                    '{joinUrl}'        => 'Join link',
                ],
            ],

            'course_enrollment' => [
                'name'     => 'Course Enrollment Welcome',
                'category' => 'Webinars & Courses',
                'subject'  => 'Welcome to {course_title} — Start Learning Now!',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Course Enrollment Confirmed',
                    'E-Learning Hub',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Welcome to <strong>{course_title}</strong>! Your course enrollment is confirmed and your modules are now accessible.</p>
                    <p>You can learn at your own pace, complete interactive quizzes, and earn an official verifiable Certificate of Completion upon finishing all modules.</p>',
                    'Launch Course Classroom',
                    '{site_url}/candidate/my-courses/{course_id}'
                ),
                'body_text' => "{greeting}\n\nWelcome to {course_title}! Start learning now: {site_url}/candidate/my-courses/{course_id}",
                'available_variables' => [
                    '{first_name}'   => 'Student\'s first name',
                    '{full_name}'    => 'Student\'s full name',
                    '{course_title}' => 'Course title',
                    '{course_id}'    => 'Course ID',
                ],
            ],

            'course_completed' => [
                'name'     => 'Course Completed & Certificate Ready',
                'category' => 'Webinars & Courses',
                'subject'  => 'Congratulations! You Completed {course_title}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Course Completed!',
                    'Certificate of Achievement',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Congratulations on successfully completing <strong>{course_title}</strong>!</p>
                    <p>Your official verifiable Certificate of Completion has been generated and added to your candidate profile. You can download your PDF certificate and add it directly to your LinkedIn and CV.</p>',
                    'View & Download Certificate',
                    '{certificateUrl}'
                ),
                'body_text' => "{greeting}\n\nCongratulations on completing {course_title}!\n\nDownload your certificate: {certificateUrl}",
                'available_variables' => [
                    '{first_name}'     => 'Student\'s first name',
                    '{course_title}'   => 'Course title',
                    '{certificateUrl}' => 'Certificate download link',
                ],
            ],

            // ==========================================
            // 5. BILLING, SUBSCRIPTIONS & INVOICES
            // ==========================================
            'purchase_invoice' => [
                'name'     => 'Official Payment Receipt & Invoice',
                'category' => 'Subscriptions & Invoices',
                'subject'  => 'Payment Receipt: {itemName} (#{reference}) — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Official Invoice & Receipt',
                    'Payment Confirmed',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Thank you for your payment. This email serves as your official payment receipt and tax invoice for <strong>{itemName}</strong>.</p>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 20px; margin: 20px 0;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                            <tr><td style="padding: 6px 0; color: #64748B;">Invoice Number:</td><td style="padding: 6px 0; font-weight: 700; text-align: right;">#{reference}</td></tr>
                            <tr><td style="padding: 6px 0; color: #64748B;">Item / Plan:</td><td style="padding: 6px 0; font-weight: 600; text-align: right;">{itemName}</td></tr>
                            <tr><td style="padding: 6px 0; color: #64748B;">Payment Date:</td><td style="padding: 6px 0; text-align: right;">{paidAt}</td></tr>
                            <tr><td style="padding: 6px 0; color: #64748B;">Payment Method:</td><td style="padding: 6px 0; text-align: right;">{paymentChannel}</td></tr>
                            <tr style="border-top: 1px solid #CBD5E1;"><td style="padding: 10px 0 0 0; font-size: 16px; font-weight: 700;">Total Paid:</td><td style="padding: 10px 0 0 0; font-size: 18px; font-weight: 700; color: #0D609E; text-align: right;">₦' . '{amount}</td></tr>
                        </table>
                    </div>
                    <p>Your subscription and service benefits have been credited immediately.</p>',
                    '{buttonText}',
                    '{accessUrl}'
                ),
                'body_text' => "{greeting}\n\nThank you for your payment.\n\nItem: {itemName}\nReference: #{reference}\nTotal: ₦{amount}\nDate: {paidAt}\n\nAccess service: {accessUrl}",
                'available_variables' => [
                    '{first_name}'     => 'Customer\'s first name',
                    '{full_name}'      => 'Customer\'s full name',
                    '{itemName}'       => 'Service or plan purchased',
                    '{reference}'      => 'Invoice reference ID',
                    '{amount}'         => 'Payment amount',
                    '{paidAt}'         => 'Date & time of payment',
                    '{paymentChannel}' => 'Payment gateway / channel',
                    '{accessUrl}'      => 'Dashboard link',
                    '{buttonText}'     => 'Action button label',
                ],
            ],

            'subscription_expiring' => [
                'name'     => 'Subscription Expiring Soon Warning',
                'category' => 'Subscriptions & Invoices',
                'subject'  => 'Warning: Your {planName} Plan Expires in {daysRemaining} Days',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Subscription Expiring Soon',
                    'Renewal Notice',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Your current <strong>{planName}</strong> plan on <strong>{site_name}</strong> is scheduled to expire in <strong>{daysRemaining} days</strong> on <strong>{expirationDate}</strong>.</p>
                    <div class="info-box">
                        <strong>Plan:</strong> {planName}<br>
                        <strong>Expiration Date:</strong> {expirationDate}<br>
                        <strong>Days Remaining:</strong> {daysRemaining} days
                    </div>
                    <p>Renew or upgrade your plan now to maintain uninterrupted candidate access, candidate unlocking, and live job listing visibility.</p>',
                    'Renew / Upgrade Subscription',
                    '{renewUrl}'
                ),
                'body_text' => "{greeting}\n\nYour {planName} plan expires in {daysRemaining} days ({expirationDate}).\n\nRenew now: {renewUrl}",
                'available_variables' => [
                    '{first_name}'     => 'Customer\'s first name',
                    '{planName}'       => 'Plan name',
                    '{expirationDate}' => 'Expiration date',
                    '{daysRemaining}'  => 'Days left',
                    '{renewUrl}'       => 'Renewal URL',
                ],
            ],

            'subscription_expired' => [
                'name'     => 'Subscription Expired Notice',
                'category' => 'Subscriptions & Invoices',
                'subject'  => 'Your {planName} Plan Has Expired — {site_name}',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Subscription Expired',
                    'Account Notice',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Your <strong>{planName}</strong> plan on <strong>{site_name}</strong> expired on <strong>{expirationDate}</strong>.</p>
                    <p>To reactivate your premium features, view full candidate contact details, and publish active job postings, please select an active plan below.</p>',
                    'Reactivate My Plan',
                    '{renewUrl}'
                ),
                'body_text' => "{greeting}\n\nYour {planName} plan expired on {expirationDate}.\n\nReactivate here: {renewUrl}",
                'available_variables' => [
                    '{first_name}'     => 'Customer\'s first name',
                    '{planName}'       => 'Plan name',
                    '{expirationDate}' => 'Expiration date',
                    '{renewUrl}'       => 'Renewal URL',
                ],
            ],

            // ==========================================
            // 6. SUPPORT & CONTACT
            // ==========================================
            'contact_autoreply' => [
                'name'     => 'Contact Us Auto-Reply',
                'category' => 'Support & Inquiries',
                'subject'  => 'We Received Your Message — {site_name} Support',
                'greeting_type' => 'first_name',
                'body_html' => self::wrapHtml(
                    'Message Received',
                    'Support Team',
                    '<p style="font-size: 16px; margin-top: 0;">{greeting}</p>
                    <p>Thank you for getting in touch with <strong>{site_name}</strong>!</p>
                    <p>Our support team has received your message and is reviewing your inquiry. We typically respond within 2 to 6 business hours.</p>
                    <div class="info-box">
                        <strong>Subject:</strong> {message_subject}<br>
                        <strong>Reference ID:</strong> #{reference}
                    </div>',
                    'Visit Help Center',
                    '{site_url}'
                ),
                'body_text' => "{greeting}\n\nThank you for contacting {site_name}. We received your inquiry and will respond shortly.",
                'available_variables' => [
                    '{first_name}'      => 'Inquirer first name',
                    '{full_name}'       => 'Inquirer full name',
                    '{message_subject}' => 'Subject of inquiry',
                    '{reference}'       => 'Reference tracking ID',
                ],
            ],

            // ==========================================
            // 7. ADMIN ALERTS
            // ==========================================
            'employer_verification_admin_alert' => [
                'name'     => 'Admin Alert: Employer Verification Pending',
                'category' => 'Admin Alerts',
                'subject'  => '🛡️ Admin Alert: Verification Documents Submitted by {company_name}',
                'greeting_type' => 'custom',
                'body_html' => self::wrapHtml(
                    'Admin Verification Alert',
                    'Compliance Queue',
                    '<p style="font-size: 16px; margin-top: 0;">Hello Admin,</p>
                    <p>A new company verification submission has been received and is waiting in the review queue.</p>
                    <div class="info-box">
                        <strong>Company:</strong> {company_name}<br>
                        <strong>Contact Email:</strong> {contact_email}<br>
                        <strong>Submitted At:</strong> {submitted_at}
                    </div>',
                    'Review in Admin Panel',
                    '{site_url}/admin/employers?status=pending'
                ),
                'body_text' => "Hello Admin,\n\n{company_name} submitted verification documents.\n\nReview: {site_url}/admin/employers?status=pending",
                'available_variables' => [
                    '{company_name}'  => 'Company name',
                    '{contact_email}' => 'Contact email',
                    '{submitted_at}'  => 'Submission timestamp',
                ],
            ],

            'job_posting_admin_alert' => [
                'name'     => 'Admin Alert: Job Awaiting Approval',
                'category' => 'Admin Alerts',
                'subject'  => '📋 Admin Alert: New Job Post Awaiting Approval — {job_title}',
                'greeting_type' => 'custom',
                'body_html' => self::wrapHtml(
                    'Admin Job Approval Alert',
                    'Jobs Moderation',
                    '<p style="font-size: 16px; margin-top: 0;">Hello Admin,</p>
                    <p>A new job posting has been submitted by <strong>{company_name}</strong> and is awaiting your review.</p>
                    <div class="info-box">
                        <strong>Job Title:</strong> {job_title}<br>
                        <strong>Company:</strong> {company_name}<br>
                        <strong>Location:</strong> {location}<br>
                        <strong>Submitted At:</strong> {submitted_at}
                    </div>',
                    'Review Job in Admin Panel',
                    '{site_url}/admin/jobs?status=pending_approval'
                ),
                'body_text' => "Hello Admin,\n\nNew job posted: {job_title} by {company_name}.\n\nReview: {site_url}/admin/jobs?status=pending_approval",
                'available_variables' => [
                    '{job_title}'     => 'Job title',
                    '{company_name}'  => 'Company name',
                    '{location}'      => 'Location',
                    '{submitted_at}'  => 'Submission timestamp',
                ],
            ],
        ];
    }
}