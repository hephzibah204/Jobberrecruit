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
        'read_at'
    ];

    protected $useAutoIncrement = true;

    protected $cast = [
        'is_read' => 'boolean',
        'id' => 'int',
        'employer_id' => 'int',
        'job_id' => 'int',
        'application_id' => 'int'
    ];

    // Define notification types
    const TYPE_NEW_APPLICATION = 'new_application';
    const TYPE_JOB_APPROVED = 'job_approved';
    const TYPE_JOB_REJECTED = 'job_rejected';
    const TYPE_JOB_PENDING = 'job_pending';
    const TYPE_JOB_EXPIRING = 'job_expiring';

    /**
     * Get unread count for employer
     */
    public function getUnreadCount(int $employerId): int
    {
        return $this->where('employer_id', $employerId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /**
     * Get all notifications for employer with pagination
     */
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

    /**
     * Get recent notifications (last 5)
     */
    public function getRecentNotifications(int $employerId, int $limit = 5): array
    {
        return $this->select('job_notifications.*, jobs.title as job_title')
            ->join('jobs', 'jobs.id = job_notifications.job_id', 'left')
            ->where('job_notifications.employer_id', $employerId)
            ->orderBy('job_notifications.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(int $notificationId, int $employerId): bool
    {
        return $this->where('id', $notificationId)
            ->where('employer_id', $employerId)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    /**
     * Mark all notifications as read for employer
     */
    public function markAllAsRead(int $employerId): bool
    {
        return $this->where('employer_id', $employerId)
            ->where('is_read', 0)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public function deleteOldNotifications(): bool
    {
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        return $this->where('created_at <', $thirtyDaysAgo)->delete();
    }

    /**
     * Delete single notification
     */
    public function deleteNotification(int $notificationId, int $employerId): bool
    {
        return $this->where('id', $notificationId)
            ->where('employer_id', $employerId)
            ->delete();
    }

    /**
     * Create notification helper
     */
    public function createNotification(
        int $employerId,
        string $type,
        string $title,
        string $message,
        ?int $jobId = null,
        ?int $applicationId = null
    ): bool {

        log_message('debug', '===== CREATE NOTIFICATION START =====');

        log_message('debug', json_encode([
            'employerId'     => $employerId,
            'type'           => $type,
            'title'          => $title,
            'message'        => $message,
            'jobId'          => $jobId,
            'applicationId'  => $applicationId,
        ], JSON_PRETTY_PRINT));

        $data = [
            'employer_id'    => $employerId,
            'job_id'         => $jobId,
            'application_id' => $applicationId,
            'type'           => $type,
            'title'          => $title,
            'message'        => $message,
            'is_read'        => 0,
            'read_at'       => null,
        ];

        log_message('debug', 'DB INSERT PAYLOAD: ' . json_encode($data, JSON_PRETTY_PRINT));

        $result = $this->insert($data);

        log_message('debug', 'INSERT RESULT: ' . json_encode($result));

        log_message('debug', '===== CREATE NOTIFICATION END =====');

        return (bool) $result;
    }

    /**
     * Get notification type label and icon
     */
    public static function getTypeInfo(string $type): array
    {
        $types = [
            self::TYPE_NEW_APPLICATION => ['label' => 'New Application', 'icon' => 'ti-user-check', 'color' => 'success'],
            self::TYPE_JOB_APPROVED => ['label' => 'Job Approved', 'icon' => 'ti-check', 'color' => 'success'],
            self::TYPE_JOB_REJECTED => ['label' => 'Job Rejected', 'icon' => 'ti-x', 'color' => 'danger'],
            self::TYPE_JOB_PENDING => ['label' => 'Pending Review', 'icon' => 'ti-hourglass', 'color' => 'warning'],
            self::TYPE_JOB_EXPIRING => ['label' => 'Job Expiring', 'icon' => 'ti-alert', 'color' => 'warning'],
        ];

        return $types[$type] ?? ['label' => 'System', 'icon' => 'ti-bell', 'color' => 'secondary'];
    }
}
