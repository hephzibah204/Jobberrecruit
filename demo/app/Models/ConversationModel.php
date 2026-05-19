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
        return $this->select('conversations.*, job_seekers.full_name as seeker_name, job_seekers.profile_photo, jobs.title as job_title')
            ->join('job_seekers', 'job_seekers.id = conversations.job_seeker_id', 'left')
            ->join('jobs', 'jobs.id = conversations.job_id', 'left')
            ->where('conversations.employer_id', $employerId)
            ->where('conversations.is_active', 1)
            ->orderBy('conversations.last_message_at', 'DESC')
            ->findAll();
    }

    public function getConversationsForSeeker(int $seekerId): array
    {
        return $this->select('conversations.*, employers.company_name, employers.logo as employer_logo, jobs.title as job_title')
            ->join('employers', 'employers.id = conversations.employer_id', 'left')
            ->join('jobs', 'jobs.id = conversations.job_id', 'left')
            ->where('conversations.job_seeker_id', $seekerId)
            ->where('conversations.is_active', 1)
            ->orderBy('conversations.last_message_at', 'DESC')
            ->findAll();
    }
}
