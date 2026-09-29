<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationNoteModel extends Model
{
    protected $table = 'application_notes';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'application_id',
        'employer_id',
        'note',
        'type',  // 'internal', 'feedback', 'reminder'
        'created_by'
    ];

    protected $useAutoIncrement = true;

    protected $cast = [
        'id' => 'int',
        'application_id' => 'int',
        'employer_id' => 'int',
        'created_by' => 'int'
    ];

    /**
     * Get notes for a specific application
     */
    public function getApplicationNotes(int $applicationId): array
    {
        return $this->select('application_notes.*, users.fullname as created_by_name')
            ->join('users', 'users.id = application_notes.created_by', 'left')
            ->where('application_notes.application_id', $applicationId)
            ->orderBy('application_notes.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get recent notes for an employer
     */
    public function getRecentNotes(int $employerId, int $limit = 10): array
    {
        return $this->select('application_notes.*, jobs.title as job_title, job_applications.first_name, job_applications.last_name')
            ->join('job_applications', 'job_applications.id = application_notes.application_id')
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->where('application_notes.employer_id', $employerId)
            ->orderBy('application_notes.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Add a note to an application
     */
    public function addNote(int $applicationId, int $employerId, string $note, int $createdBy, string $type = 'internal'): bool|int
    {
        return $this->insert([
            'application_id' => $applicationId,
            'employer_id' => $employerId,
            'note' => $note,
            'type' => $type,
            'created_by' => $createdBy
        ]);
    }

    /**
     * Delete all notes for an application (when application is deleted)
     */
    public function deleteApplicationNotes(int $applicationId): bool
    {
        return $this->where('application_id', $applicationId)->delete();
    }

    /**
     * Get notes by type
     */
    public function getNotesByType(int $applicationId, string $type): array
    {
        return $this->where('application_id', $applicationId)
            ->where('type', $type)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Count notes for an application
     */
    public function countNotes(int $applicationId): int
    {
        return $this->where('application_id', $applicationId)->countAllResults();
    }
}
