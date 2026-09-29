<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateNotificationModel extends Model
{
    protected $table         = 'candidate_notifications';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    protected $allowedFields = [
        'candidate_id',
        'application_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $useAutoIncrement = true;

    protected $cast = [
        'is_read'        => 'boolean',
        'id'             => 'int',
        'candidate_id'   => 'int',
        'application_id' => '?int',
    ];

    // Define notification types for candidates
    const TYPE_APPLICATION_VIEWED         = 'application_viewed';
    const TYPE_APPLICATION_STATUS_CHANGED = 'application_status_changed';

    /**
     * Get unread count for candidate
     */
    public function getUnreadCount(int $candidateId): int
    {
        return $this->where('candidate_id', $candidateId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /**
     * Get all notifications for candidate with pagination, joined to job + application for display.
     */
    public function getNotifications(int $candidateId, int $limit = 20, int $offset = 0): array
    {
        return $this->select('candidate_notifications.*, jobs.title as job_title, job_applications.status as application_status, job_applications.id as application_pk')
            ->join('job_applications', 'job_applications.id = candidate_notifications.application_id', 'left')
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->where('candidate_notifications.candidate_id', $candidateId)
            ->orderBy('candidate_notifications.created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(int $notificationId, int $candidateId): bool
    {
        return $this->where('id', $notificationId)
            ->where('candidate_id', $candidateId)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    /**
     * Mark all notifications as read for candidate
     */
    public function markAllAsRead(int $candidateId): bool
    {
        return $this->where('candidate_id', $candidateId)
            ->where('is_read', 0)
            ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    /**
     * Create notification helper
     */
    public function createNotification(
        int $candidateId,
        string $type,
        string $title,
        string $message,
        ?int $applicationId = null
    ): bool {
        $data = [
            'candidate_id'   => $candidateId,
            'application_id' => $applicationId,
            'type'           => $type,
            'title'          => $title,
            'message'        => $message,
            'is_read'        => 0,
            'read_at'        => null,
        ];

        return (bool) $this->insert($data);
    }

    /**
     * Get notification type label, icon, and color
     */
    public static function getTypeInfo(string $type): array
    {
        $types = [
            self::TYPE_APPLICATION_VIEWED         => ['label' => 'Application Viewed',        'icon' => 'ti-eye',     'color' => 'info'],
            self::TYPE_APPLICATION_STATUS_CHANGED => ['label' => 'Application Status Updated', 'icon' => 'ti-refresh', 'color' => 'primary'],
        ];

        return $types[$type] ?? ['label' => 'System', 'icon' => 'ti-bell', 'color' => 'secondary'];
    }
}
