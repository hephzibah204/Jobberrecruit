<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailVerificationModel extends Model
{
    protected $table            = 'email_verifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'user_id',
        'token',
        'expires_at'
    ];

    protected $returnType = 'object';

    protected $useTimestamps = true;  // created_at, updated_at
}
