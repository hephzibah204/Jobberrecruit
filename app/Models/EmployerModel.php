<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployerModel extends Model
{
    protected $table      = 'employers';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Employer::class;
    protected $allowedFields = [
        'user_id',
        'company_name',
        'logo',
        'industry_id',
        'company_size',
        'website',
        'state_id',
        'description',
        'contact_name',
        'contact_email',
        'contact_phone',
        'company_address',
        'verification_doc',
        'unlimited_access',
        'unlimited_until',
        'tin_number',
        'is_verified',
        'verification_status',
        'verification_documents',
        'verification_notes',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $useTimestamps = true;

    protected $cast = [
        'verification_documents' => 'json'
    ];


    public function getTopEmployers(int $limit = 5)
    {
        return $this->select([
            'employers.id',
            'employers.company_name',
            'employers.logo',
            'employers.is_verified',
            'COUNT(jobs.id) AS total_jobs'
        ])
            ->join('jobs', 'jobs.employer_id = employers.id', 'left')
            ->groupBy('employers.id')
            ->orderBy('total_jobs', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function filterByIndustries(array $industryIds)
    {
        if (empty($industryIds)) {
            return $this;
        }

        return $this->whereIn(
            'employers.id',
            function ($builder) use ($industryIds) {
                return $builder
                    ->select('employer_id')
                    ->from('employer_industries')
                    ->whereIn('industry_id', $industryIds);
            }
        );
    }

    public function hasTrustBadge(): bool
    {
        $sub = model(UserSubscriptionModel::class)
            ->where('user_id', $this->user_id)
            ->where('is_active', 1)
            ->first();

        return $sub && $sub->getPlan()->allows('trust_badge');
    }

    // Network blast queue
    // if ($job->network_blast) {
    //     model(JobBroadcastQueue::class)->insert([
    //         'job_id' => $job->id,
    //         'status' => 'pending'
    //     ]);
    // }

    // Validation rule (Controller): ... No plan → no redirect. Period.
    // if (
    //     !service(PlanFeatureService::class)->userAllows($userId, 'url_redirect')
    // ) {
    //     $postData['external_url'] = null;
    // }

    /**
     * Check if employer has unlimited access
     */
    public function hasUnlimitedAccess(int $employerId): bool
    {
        $employer = $this->find($employerId);

        if (!$employer) {
            return false;
        }

        // Check if this employer has unlimited access
        if (
            $employer->unlimited_access &&
            ($employer->unlimited_until === null ||
                $employer->unlimited_until > date('Y-m-d H:i:s'))
        ) {
            return true;
        }

        // // Check if parent employer has unlimited access
        // if ($employer->parent_employer_id) {
        //     return $this->hasUnlimitedAccess($employer->parent_employer_id);
        // }

        return false;
    }

    /**
     * Get pending employers for verification
     */
    public function getPendingEmployers(int $limit = 20)
    {
        return $this->select('employers.*, users.email, users.username')
            ->join('users', 'users.id = employers.user_id')
            ->where('verification_status', 'pending')
            ->orderBy('employers.created_at', 'ASC')
            ->paginate($limit);
    }

    /**
     * Get verification statistics
     */
    public function getVerificationStats(): array
    {
        $db = db_connect();

        $stats = $db->table('employers')
            ->select('verification_status, COUNT(*) as total')
            ->groupBy('verification_status')
            ->get()
            ->getResultArray();

        $result = [
            'pending' => 0,
            'verified' => 0,
            'rejected' => 0,
            'document_required' => 0,
            'total' => 0
        ];

        foreach ($stats as $stat) {
            $result[$stat['verification_status']] = (int)$stat['total'];
            $result['total'] += (int)$stat['total'];
        }

        return $result;
    }

    /**
     * Verify an employer
     */
    public function verifyEmployer(int $employerId, int $adminId, string $notes = null): bool
    {
        $data = [
            'verification_status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $adminId,
            'verification_notes' => $notes,
            'rejection_reason' => null
        ];

        $this->update($employerId, $data);

        // Log the verification
        $this->logVerification($employerId, $adminId, 'verified', $notes);

        return true;
    }

    /**
     * Reject an employer
     */
    public function rejectEmployer(int $employerId, int $adminId, string $reason): bool
    {
        $data = [
            'verification_status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => $adminId,
            'verified_at' => date('Y-m-d H:i:s')
        ];

        $this->update($employerId, $data);

        // Log the rejection
        $this->logVerification($employerId, $adminId, 'rejected', $reason);

        return true;
    }

    /**
     * Request additional documents
     */
    public function requestDocuments(int $employerId, int $adminId, string $notes): bool
    {
        $data = [
            'verification_status' => 'document_required',
            'verification_notes' => $notes
        ];

        $this->update($employerId, $data);

        // Log the request
        $this->logVerification($employerId, $adminId, 'document_requested', $notes);

        return true;
    }

    /**
     * Log verification actions
     */
    private function logVerification(int $employerId, int $adminId, string $action, string $notes = null)
    {
        $logModel = model(EmployerVerificationLogModel::class);
        $logModel->insert([
            'employer_id' => $employerId,
            'admin_id' => $adminId,
            'action' => $action,
            'notes' => $notes
        ]);
    }
}
