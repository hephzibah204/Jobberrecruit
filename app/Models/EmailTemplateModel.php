<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailTemplateModel extends Model
{
    protected $table            = 'email_templates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'template_key',
        'name',
        'category',
        'subject',
        'greeting_type',
        'body_html',
        'body_text',
        'available_variables',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get a template by key (cached or active)
     */
    public function getByKey(string $key): ?object
    {
        try {
            return $this->where('template_key', $key)
                        ->where('is_active', 1)
                        ->first();
        } catch (\Throwable $e) {
            log_message('notice', 'EmailTemplateModel getByKey query notice: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse full name into first and last name components
     */
    public static function parseNameParts($fullName): array
    {
        $fullName = trim((string)$fullName);
        if (empty($fullName)) {
            return [
                'first_name' => 'Member',
                'last_name'  => '',
                'full_name'  => 'Member',
            ];
        }

        // Clean out extra spaces
        $parts = preg_split('/\s+/', $fullName);
        $firstName = $parts[0] ?? $fullName;
        $lastName  = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        return [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'full_name'  => $fullName,
        ];
    }

    /**
     * Generate greeting string based on preference
     */
    public static function renderGreeting(string $greetingType, array $names): string
    {
        $firstName = !empty($names['first_name']) ? $names['first_name'] : 'Member';
        $fullName  = !empty($names['full_name']) ? $names['full_name'] : $firstName;

        switch ($greetingType) {
            case 'full_name':
                return "Hello {$fullName},";
            case 'custom':
                return "Hello,";
            case 'first_name':
            default:
                return "Hello {$firstName},";
        }
    }

    /**
     * Replace template placeholders with real dynamic data
     */
    public static function parseVariables(string $content, array $data, string $greetingType = 'first_name'): string
    {
        if (empty($content)) {
            return '';
        }

        // Determine recipient name
        $rawName = '';
        if (!empty($data['first_name']) && !empty($data['last_name'])) {
            $rawName = trim($data['first_name'] . ' ' . $data['last_name']);
        } elseif (!empty($data['fullname'])) {
            $rawName = $data['fullname'];
        } elseif (!empty($data['name'])) {
            $rawName = $data['name'];
        } elseif (!empty($data['userName'])) {
            $rawName = $data['userName'];
        } elseif (!empty($data['user_name'])) {
            $rawName = $data['user_name'];
        } elseif (!empty($data['candidate_name'])) {
            $rawName = $data['candidate_name'];
        } elseif (!empty($data['contact_name'])) {
            $rawName = $data['contact_name'];
        } elseif (!empty($data['first_name'])) {
            $rawName = $data['first_name'];
        } elseif (isset($data['user']) && is_object($data['user'])) {
            $rawName = $data['user']->username ?? ($data['user']->first_name ?? '');
        }

        $names = self::parseNameParts($rawName);
        $greeting = self::renderGreeting($greetingType, $names);

        // Core system placeholders
        $replacements = [
            '{first_name}'   => esc($names['first_name']),
            '{firstName}'    => esc($names['first_name']),
            '{last_name}'    => esc($names['last_name']),
            '{lastName}'     => esc($names['last_name']),
            '{full_name}'    => esc($names['full_name']),
            '{fullName}'     => esc($names['full_name']),
            '{name}'         => esc($names['full_name']),
            '{userName}'     => esc($names['full_name']),
            '{user_name}'    => esc($names['full_name']),
            '{candidate_name}'=> esc($names['full_name']),
            '{candidateName}' => esc($names['full_name']),
            '{greeting}'     => $greeting,
            '{site_name}'    => 'JobberRecruit',
            '{siteName}'     => 'JobberRecruit',
            '{site_url}'     => base_url(),
            '{siteUrl}'      => base_url(),
            '{login_url}'    => base_url('login'),
            '{loginUrl}'     => base_url('login'),
            '{current_year}' => date('Y'),
            '{currentYear}'  => date('Y'),
            '{logo_url}'     => base_url('images/logo-white.png'),
            '{logoUrl}'      => base_url('images/logo-white.png'),
            '{support_email}'=> env('email.supportEmail', 'support@jobberrecruit.com'),
            '{supportEmail}' => env('email.supportEmail', 'support@jobberrecruit.com'),
        ];

        // Merge custom data keys with automatic casing normalization (camelCase <-> snake_case <-> PascalCase)
        foreach ($data as $key => $val) {
            if (is_scalar($val) || is_null($val)) {
                $strVal = (string)$val;
                $replacements['{' . $key . '}'] = $strVal;

                // snake_case variant (e.g. jobTitle -> job_title)
                $snake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
                $replacements['{' . $snake . '}'] = $strVal;

                // camelCase variant (e.g. job_title -> jobTitle)
                $camel = lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $key))));
                $replacements['{' . $camel . '}'] = $strVal;

                // PascalCase variant (e.g. job_title -> JobTitle)
                $pascal = ucfirst($camel);
                $replacements['{' . $pascal . '}'] = $strVal;
            }
        }

        // Semantic cross-mapping for common job & application entity attributes
        $jobTitle = $data['job_title'] ?? $data['jobTitle'] ?? $data['job'] ?? $data['position'] ?? $data['role'] ?? $data['title'] ?? null;
        if ($jobTitle !== null && $jobTitle !== '') {
            $strJob = (string)$jobTitle;
            $replacements['{job_title}'] = $strJob;
            $replacements['{jobTitle}']  = $strJob;
            $replacements['{position}']  = $strJob;
            $replacements['{role}']      = $strJob;
            $replacements['{job}']       = $strJob;
            $replacements['{job_name}']  = $strJob;
            $replacements['{jobName}']   = $strJob;
        }

        $companyName = $data['company_name'] ?? $data['companyName'] ?? $data['company'] ?? $data['employer_name'] ?? $data['employerName'] ?? $data['employer'] ?? null;
        if ($companyName !== null && $companyName !== '') {
            $strComp = (string)$companyName;
            $replacements['{company_name}']  = $strComp;
            $replacements['{companyName}']   = $strComp;
            $replacements['{company}']       = $strComp;
            $replacements['{employer_name}'] = $strComp;
            $replacements['{employerName}']  = $strComp;
        }

        $statusLabel = $data['status_label'] ?? $data['statusLabel'] ?? $data['status'] ?? $data['stage'] ?? null;
        if ($statusLabel !== null && $statusLabel !== '') {
            $strStatus = (string)$statusLabel;
            $replacements['{status_label}'] = $strStatus;
            $replacements['{statusLabel}']  = $strStatus;
            $replacements['{status}']       = $strStatus;
        }

        $message = $data['message'] ?? $data['messageToCandidate'] ?? $data['custom_message'] ?? $data['customMessage'] ?? null;
        if ($message !== null && $message !== '') {
            $strMsg = (string)$message;
            $replacements['{message}']            = $strMsg;
            $replacements['{messageToCandidate}'] = $strMsg;
            $replacements['{custom_message}']     = $strMsg;
            $replacements['{customMessage}']      = $strMsg;
        }

        $planName = $data['plan_name'] ?? $data['planName'] ?? $data['plan'] ?? null;
        if ($planName !== null && $planName !== '') {
            $strPlan = (string)$planName;
            $replacements['{plan_name}'] = $strPlan;
            $replacements['{planName}']  = $strPlan;
        }

        $expirationDate = $data['expiration_date'] ?? $data['expirationDate'] ?? $data['expiry_date'] ?? null;
        if ($expirationDate !== null && $expirationDate !== '') {
            $strExp = (string)$expirationDate;
            $replacements['{expiration_date}'] = $strExp;
            $replacements['{expirationDate}']  = $strExp;
        }

        $daysRemaining = $data['days_remaining'] ?? $data['daysRemaining'] ?? null;
        if ($daysRemaining !== null && $daysRemaining !== '') {
            $strDays = (string)$daysRemaining;
            $replacements['{days_remaining}'] = $strDays;
            $replacements['{daysRemaining}']  = $strDays;
        }

        $renewUrl = $data['renew_url'] ?? $data['renewUrl'] ?? null;
        if ($renewUrl !== null && $renewUrl !== '') {
            $strRenew = (string)$renewUrl;
            $replacements['{renew_url}'] = $strRenew;
            $replacements['{renewUrl}']  = $strRenew;
        }

        // Safety fallback replacements for template tags so raw curly brackets never leak to recipients
        if (!isset($replacements['{job_title}']) || $replacements['{job_title}'] === '') {
            $replacements['{job_title}'] = 'Position';
            $replacements['{jobTitle}']  = 'Position';
        }
        if (!isset($replacements['{company_name}']) || $replacements['{company_name}'] === '') {
            $replacements['{company_name}'] = 'JobberRecruit Hiring Team';
            $replacements['{companyName}']  = 'JobberRecruit Hiring Team';
        }
        if (!isset($replacements['{statusLabel}']) || $replacements['{statusLabel}'] === '') {
            $replacements['{statusLabel}']  = 'Application Update';
            $replacements['{status_label}'] = 'Application Update';
        }
        if (!isset($replacements['{message}'])) {
            $replacements['{message}'] = '';
        }

        return strtr($content, $replacements);
    }
}