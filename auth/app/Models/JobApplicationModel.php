<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationModel extends Model
{
    protected $table      = 'job_applications';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\JobApplication::class;

    protected $allowedFields = [
        'job_id',
        'job_seeker_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cv_path',
        'cover_letter',
        'availability',
        'salary_expectation',
        'work_eligibility',
        'consent',
        'status',
        'status_message',
        'reviewed_at',
        'is_guest',
        'guest_email_sent',
        'created_at'
    ];

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;

    public function countByStatus(): array
    {
        return $this->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->findAll();
    }

    public function performanceStats(int $year)
    {
        return [
            'daily' => $this->select('MONTH(created_at) m, COUNT(*) total')
                ->where('YEAR(created_at)', $year)
                ->groupBy('MONTH(created_at)')
                ->findAll(),

            'weekly' => $this->select('MONTH(created_at) m, COUNT(*) total')
                ->where('YEAR(created_at)', $year)
                ->whereIn('status', ['reviewed', 'shortlisted'])
                ->groupBy('MONTH(created_at)')
                ->findAll(),

            'monthly' => $this->select('MONTH(created_at) m, COUNT(*) total')
                ->where('YEAR(created_at)', $year)
                ->where('status', 'hired')
                ->groupBy('MONTH(created_at)')
                ->findAll(),
        ];
    }

    /**
     * Record a status change for an application atomically.
     *
     * Updates `job_applications.status` (and `status_message`) AND inserts a
     * row into `job_application_status_history` inside a single transaction.
     * Returns false on transaction failure or when the application does not exist.
     *
     * @param int         $applicationId
     * @param string      $newStatus
     * @param int|null    $changedByUserId
     * @param string|null $message
     *
     * @return bool
     */
    public function recordStatusChange(int $applicationId, string $newStatus, ?int $changedByUserId, ?string $message): bool
    {
        $this->db->transStart();

        // Read current status inside the transaction for a consistent snapshot.
        $current = $this->db->table('job_applications')
            ->select('status')
            ->where('id', $applicationId)
            ->get()
            ->getRowArray();

        if ($current === null) {
            $this->db->transRollback();
            return false;
        }

        $oldStatus = $current['status'];

        // No-op when status is already the target value.
        if ($oldStatus === $newStatus) {
            $this->db->transComplete();
            return $this->db->transStatus() !== false;
        }

        // Update the application row.
        $updated = $this->update($applicationId, [
            'status'         => $newStatus,
            'status_message' => $message,
        ]);

        if ($updated === false) {
            $this->db->transRollback();
            return false;
        }

        // Insert the history row. If this fails, the whole transaction rolls back.
        $historyInserted = $this->db->table('job_application_status_history')->insert([
            'application_id'     => $applicationId,
            'old_status'         => $oldStatus,
            'new_status'         => $newStatus,
            'changed_by_user_id' => $changedByUserId,
            'message'            => $message,
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        if ($historyInserted === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return true;
    }

    /**
     * Get the full status-change history for an application, most recent first.
     *
     * Joins to `users` (and `employers`) to expose the changer as a display name.
     *
     * @param int $applicationId
     *
     * @return array<int, array<string, mixed>>
     */
    public function getStatusHistory(int $applicationId): array
    {
        return $this->db->table('job_application_status_history')
            ->select('job_application_status_history.*, users.username, employers.contact_name AS changed_by_name')
            ->join('users', 'users.id = job_application_status_history.changed_by_user_id', 'left')
            ->join('employers', 'employers.user_id = users.id', 'left')
            ->where('job_application_status_history.application_id', $applicationId)
            ->orderBy('job_application_status_history.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Mark an application as `viewed` if (and only if) it is currently `pending`.
     *
     * No-op when the application does not exist or its status is anything other
     * than `pending` — this keeps the `viewed` history row idempotent on
     * subsequent employer opens.
     *
     * @param int $applicationId
     * @param int $employerUserId
     *
     * @return void
     */
    public function markViewedOnFirstOpen(int $applicationId, int $employerUserId): void
    {
        $current = $this->db->table('job_applications')
            ->select('status')
            ->where('id', $applicationId)
            ->get()
            ->getRowArray();

        if ($current === null || $current['status'] !== 'pending') {
            return;
        }

        $this->recordStatusChange($applicationId, 'viewed', $employerUserId, null);
    }
}
