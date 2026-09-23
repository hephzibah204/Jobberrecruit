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
            '{last_name}'    => esc($names['last_name']),
            '{full_name}'    => esc($names['full_name']),
            '{name}'         => esc($names['full_name']),
            '{userName}'     => esc($names['full_name']),
            '{greeting}'     => $greeting,
            '{site_name}'    => 'JobberRecruit',
            '{site_url}'     => base_url(),
            '{login_url}'    => base_url('login'),
            '{current_year}' => date('Y'),
            '{logo_url}'     => base_url('images/logo-white.png'),
            '{support_email}'=> env('email.supportEmail', 'support@jobberrecruit.com'),
        ];

        // Merge custom data keys
        foreach ($data as $key => $val) {
            if (is_scalar($val) || is_null($val)) {
                $tagKey = '{' . $key . '}';
                $replacements[$tagKey] = (string)$val;
            }
        }

        return strtr($content, $replacements);
    }
}