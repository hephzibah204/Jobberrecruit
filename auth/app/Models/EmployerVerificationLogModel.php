<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployerVerificationLogModel extends Model
{
    protected $table = 'employer_verification_logs';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'employer_id',
        'admin_id',
        'action',
        'notes',
        'created_at'
    ];

    protected $useAutoIncrement = true;

    protected $beforeInsert = ['setTimestamps'];

    protected function setTimestamps(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}
