<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PasswordResetToken extends Entity
{
    protected $dates = ['expires_at', 'created_at'];
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
    ];
}
