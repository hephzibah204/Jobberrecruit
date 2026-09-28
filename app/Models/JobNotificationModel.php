<?php

namespace App\Models;

use CodeIgniter\Model;

class JobNotificationModel extends Model
{
    protected $table = 'job_notifications';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    protected $allowedFields = [
        'employer_id',
        'job_id',
        'application_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
        'action_url',
    ];

    protected $useAutoIncrement = true;

    protected $cast = [
        'is_read'        => 'boolean',
        'id'             => 'int',
        'employer_id'    => 'int',
        'job_id'         => 'int',
        'application_id' => 'int',
    ];

    // Job lifecycle
    const TYPE_JOB_POSTED   = 'job_posted';
    const TYPE_JOB_PENDING  = 'job_pending';
    const TYPE_JOB_APPROVED = 'job_approved';
    const TYPE_JOB_REJECTED = 'job_rejected';
    const TYPE_JOB_EXPIRING = 'job_expiring';
    const TYPE_JOB_EXPIRED  = 'job_expired';
    const TYPE_JOB_REPOSTED = 'job_reposted';
    const TYPE_JOB_CLOSED   = 'job_closed';

    // Applications
    const TYPE_NEW_APPLICATION       = 'new_application';
    const TYPE_CANDIDATE_STATUS      = 'candidate_status_update';
    const TYPE_APPLICATION_WITHDRAWN = 'application_withdrawn';

    // Subscription & billing
    const TYPE_SUBSCRIPTION_EXPIRING = 'subscription_expiring';
    const TYPE_SUBSCRIPTION_EXPIRED  = 'subscription_expired';
    const TYPE_SUBSCRIPTION_RENEWED  = 'subscription_renewed';
    const TYPE_PAYMENT_CONFIRMED     = 'payment_confirmed';
    const TYPE_PAYMENT_FAILED        = 'payment_failed';

    // Messaging
    const TYPE_NEW_MESSAGE = 'new_message';

    // Aptitude / assessments
    const TYPE_APTITUDE_SUBMITTED = 'aptitude_test_submitted';
    const TYPE_APTITUDE_RESULT    = 'aptitude_test_result';
    const TYPE_ASSESSMENT_RESULT  = 'candidate_assessment_result';

    // AI / matching
    const TYPE_AI_MATCH           = 'ai_match_recommendation';
    const TYPE_MATCHING_CANDIDATE = 'matching_candidate';

    // Account / system
    const TYPE_ACCOUNT_UPDATED = 'account_updated';
    const TYPE_ACCOUNT_WARNING  = 'account_warning';
    const TYPE_SYSTEM           = 'system';

    // Category groups
    const CATEGORY_JOBS         = ['job_posted','job_pending','job_approved','job_rejected','job_expiring','job_expired','job_reposted','job_closed'];
    const CATEGORY_APPLICATIONS = ['new_application','candidate_status_update','application_withdrawn'];
    const CATEGORY_CANDIDATES   = ['matching_candidate','ai_match_recommendation','aptitude_test_submitted','aptitude_test_result','candidate_assessment_result'];
    const CATEGORY_PAYMENTS     = ['subscription_expiring','subscription_expired','subscription_renewed','payment_confirmed','payment_failed'];
    const CATEGORY_MESSAGES     = ['new_message'];
    const CATEGORY_SYSTEM       = ['account_updated','account_warning','system'];

    public function getUnreadCount(int $employerId): int
    {
        return $this->where('employer_id', $employerId)->where('is_read', 0)->countAllResults();
    }

    public function getNotifications(int $employerId, int $limit = 20, int $offset = 0): array
    {
        return $this->select('job_notifications.*, jobs.title as job_title, job_applications.first_name, job_applications.last_name')
            ->join('jobs', 'jobs.id = job_notifications.job_id', 'left')
            ->join('job_applications', 'job_applications.id = job_notifications.application_id', 'left')
            ->where('job_notifications.employer_id', $employerId)
            ->orderBy('job_notifications.created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    public function getRecentNotifications(int $employerId, int $limit = 5): array
    {
        return $this->select('job_notifications.*, jobs.title as job_title')
            ->join('jobs', 'jobs.id = job_notifications.job_id', 'left')
            ->where('job_notifications.employer_id', $employerId)
            ->orderBy('job_notifications.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function markAsRead(int $notificationId, int $employerId): bool
    {
        return $this->where('id', $notificationId)
            ->where('employer_id', $employerId)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    public function markAllAsRead(int $employerId): bool
    {
        return $this->where('employer_id', $employerId)
            ->where('is_read', 0)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    public function deleteOldNotifications(): bool
    {
        return $this->where('created_at <', date('Y-m-d H:i:s', strtotime('-90 days')))->delete();
    }

    public function deleteNotification(int $notificationId, int $employerId): bool
    {
        return $this->where('id', $notificationId)->where('employer_id', $employerId)->delete();
    }

    public function createNotification(
        int $employerId,
        string $type,
        string $title,
        string $message,
        ?int $jobId = null,
        ?int $applicationId = null,
        ?string $actionUrl = null
    ): bool {
        $data = [
            'employer_id'    => $employerId,
            'job_id'         => $jobId,
            'application_id' => $applicationId,
            'type'           => $type,
            'title'          => $title,
            'message'        => $message,
            'is_read'        => 0,
            'read_at'        => null,
            'action_url'     => $actionUrl,
        ];
        $result = $this->insert($data);
        if (!$result) {
            log_message('error', 'JobNotificationModel::createNotification failed — type=' . $type . ' employer=' . $employerId);
        }
        return (bool) $result;
    }

    public static function getTypeInfo(string $type): array
    {
        $map = [
            'job_posted'   => ['label' => 'Job Posted',          'icon' => 'i-briefcase',  'color' => 'info',    'category' => 'jobs'],
            'job_pending'  => ['label' => 'Pending Review',      'icon' => 'i-clock',      'color' => 'warning', 'category' => 'jobs'],
            'job_approved' => ['label' => 'Job Approved',        'icon' => 'i-check-c',    'color' => 'success', 'category' => 'jobs'],
            'job_rejected' => ['label' => 'Job Rejected',        'icon' => 'i-x',          'color' => 'danger',  'category' => 'jobs'],
            'job_expiring' => ['label' => 'Job Expiring Soon',   'icon' => 'i-clock',      'color' => 'warning', 'category' => 'jobs'],
            'job_expired'  => ['label' => 'Job Expired',         'icon' => 'i-clock',      'color' => 'muted',   'category' => 'jobs'],
            'job_reposted' => ['label' => 'Job Reposted',        'icon' => 'i-briefcase',  'color' => 'info',    'category' => 'jobs'],
            'job_closed'   => ['label' => 'Job Closed',          'icon' => 'i-x',          'color' => 'muted',   'category' => 'jobs'],

            'new_application'         => ['label' => 'New Application',       'icon' => 'i-users',      'color' => 'success', 'category' => 'applications'],
            'candidate_status_update' => ['label' => 'Candidate Status',      'icon' => 'i-user-check', 'color' => 'info',    'category' => 'applications'],
            'application_withdrawn'   => ['label' => 'Application Withdrawn', 'icon' => 'i-users',      'color' => 'muted',   'category' => 'applications'],

            'matching_candidate'          => ['label' => 'Matching Candidate',      'icon' => 'i-star',      'color' => 'accent',  'category' => 'candidates'],
            'ai_match_recommendation'     => ['label' => 'AI Match',                'icon' => 'i-zap',       'color' => 'accent',  'category' => 'candidates'],
            'aptitude_test_submitted'     => ['label' => 'Aptitude Test Submitted', 'icon' => 'i-clipboard', 'color' => 'info',    'category' => 'candidates'],
            'aptitude_test_result'        => ['label' => 'Aptitude Test Result',    'icon' => 'i-award',     'color' => 'success', 'category' => 'candidates'],
            'candidate_assessment_result' => ['label' => 'Assessment Result',       'icon' => 'i-award',     'color' => 'success', 'category' => 'candidates'],

            'subscription_expiring' => ['label' => 'Subscription Expiring', 'icon' => 'i-bell',    'color' => 'warning', 'category' => 'payments'],
            'subscription_expired'  => ['label' => 'Subscription Expired',  'icon' => 'i-bell',    'color' => 'danger',  'category' => 'payments'],
            'subscription_renewed'  => ['label' => 'Subscription Renewed',  'icon' => 'i-check-c', 'color' => 'success', 'category' => 'payments'],
            'payment_confirmed'     => ['label' => 'Payment Confirmed',     'icon' => 'i-check-c', 'color' => 'success', 'category' => 'payments'],
            'payment_failed'        => ['label' => 'Payment Failed',        'icon' => 'i-bell',    'color' => 'danger',  'category' => 'payments'],

            'new_message' => ['label' => 'New Message', 'icon' => 'i-message', 'color' => 'info', 'category' => 'messages'],

            'account_updated' => ['label' => 'Account Updated', 'icon' => 'i-settings', 'color' => 'secondary', 'category' => 'system'],
            'account_warning' => ['label' => 'Account Warning', 'icon' => 'i-bell',     'color' => 'warning',   'category' => 'system'],
            'system'          => ['label' => 'System Notice',   'icon' => 'i-bell',     'color' => 'secondary', 'category' => 'system'],
        ];

        return $map[$type] ?? ['label' => 'Notification', 'icon' => 'i-bell', 'color' => 'secondary', 'category' => 'system'];
    }

    public static function getCategory(string $type): string
    {
        return self::getTypeInfo($type)['category'] ?? 'system';
    }
}