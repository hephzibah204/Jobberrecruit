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
        // ReferralService writes these; omitting them makes update() throw
        // "There is no data to update" for any user without a referral code
        'referral_code',
        'referred_by',
    ];
}
