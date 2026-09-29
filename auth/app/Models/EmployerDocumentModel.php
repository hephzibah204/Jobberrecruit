<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployerDocumentModel extends Model
{
    protected $table = 'employer_documents';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'employer_id',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'status',
        'uploaded_at',
        'verified_at',
        'verified_by'
    ];

    protected $beforeInsert = ['setTimestamps'];

    protected function setTimestamps(array $data)
    {
        $data['data']['uploaded_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Get documents by employer with status
     */
    public function getEmployerDocuments(int $employerId): array
    {
        return $this->where('employer_id', $employerId)
            ->orderBy('uploaded_at', 'DESC')
            ->findAll();
    }

    /**
     * Get pending documents for verification
     */
    public function getPendingDocuments(int $limit = 50): array
    {
        return $this->select('employer_documents.*, employers.company_name, users.email')
            ->join('employers', 'employers.id = employer_documents.employer_id')
            ->join('users', 'users.id = employers.user_id')
            ->where('employer_documents.status', 'pending')
            ->orderBy('employer_documents.uploaded_at', 'ASC')
            ->findAll($limit);
    }

    /**
     * Verify a document
     */
    public function verifyDocument(int $documentId, int $adminId): bool
    {
        return $this->update($documentId, [
            'status' => 'approved',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $adminId
        ]);
    }

    /**
     * Reject a document
     */
    public function rejectDocument(int $documentId, int $adminId): bool
    {
        return $this->update($documentId, [
            'status' => 'rejected',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $adminId
        ]);
    }
}
