<?php

// app/Models/UserModel.php
namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use App\Entities\User;

class UserModel extends ShieldUserModel
{
    protected $returnType = User::class;
    protected $allowedFields = [
        'username',
        'email',
        'password_hash',
        'active',
        'status',
        'status_message',
        'last_active',
        'user_type',
        'paystack_customer_code',
        'email_verified_at',
    ];
}
