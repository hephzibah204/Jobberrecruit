<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table      = 'password_resets';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\PasswordResetToken::class;

    protected $allowedFields = ['user_id', 'token', 'expires_at'];
    protected $useTimestamps = true;
}
