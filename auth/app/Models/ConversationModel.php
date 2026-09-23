<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
    protected $table      = 'conversations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employer_id',
        'job_seeker_id',
        'job_id',
        'last_message',
        'last_message_at',
        'employer_last_read',
        'seeker_last_read',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getConversationsForEmployer(int $employerId): array
    {
        $conversations = $this->select('conversations.*, job_seekers.full_name as seeker_name, job_seekers.profile_picture as profile_photo, jobs.title as job_title')
            ->join('job_seekers', 'job_seekers.id = conversations.job_seeker_id', 'left')
            ->join('jobs', 'jobs.id = conversations.job_id', 'left')
            ->where('conversations.employer_id', $employerId)
            ->where('conversations.is_active', 1)
            ->orderBy('conversations.last_message_at', 'DESC')
            ->findAll();

        return $this->attachUnreadCounts($conversations, 'job_seeker');
    }

    public function getConversationsForSeeker(int $seekerId): array
    {
        $conversations = $this->select('conversations.*, employers.company_name, employers.logo as employer_logo, jobs.title as job_title')
            ->join('employers', 'employers.id = conversations.employer_id', 'left')
            ->join('jobs', 'jobs.id = conversations.job_id', 'left')
            ->where('conversations.job_seeker_id', $seekerId)
            ->where('conversations.is_active', 1)
            ->orderBy('conversations.last_message_at', 'DESC')
            ->findAll();

        return $this->attachUnreadCounts($conversations, 'employer');
    }

    /**
     * Adds unread_count = number of unread messages sent by $counterpartSenderType in each conversation.
     */
    protected function attachUnreadCounts(array $conversations, string $counterpartSenderType): array
    {
        if (empty($conversations)) {
            return $conversations;
        }

        $ids = array_column($conversations, 'id');
        $counts = $this->db->table('messages')
            ->select('conversation_id, COUNT(*) as unread_count')
            ->whereIn('conversation_id', $ids)
            ->where('sender_type', $counterpartSenderType)
            ->where('is_read', 0)
            ->groupBy('conversation_id')
            ->get()
            ->getResultArray();

        $countsByConversation = array_column($counts, 'unread_count', 'conversation_id');

        foreach ($conversations as &$conversation) {
            $conversation['unread_count'] = (int) ($countsByConversation[$conversation['id']] ?? 0);
        }

        return $conversations;
    }
}
