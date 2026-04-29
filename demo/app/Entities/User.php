<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends ShieldUser
{
    protected $attributes = [
        'user_type' => 'job_seeker',
        'status' => 'active',
        'status_message' => null,
        'email_verified_at' => null,
        'paystack_customer_code' => null,
        'email' => null,
    ];

    protected $datamap = [
        'user_type' => 'user_type',
        'status' => 'status',
        'status_message' => 'status_message',
        'email_verified_at' => 'email_verified_at',
        'paystack_customer_code' => 'paystack_customer_code',
        'email' => 'email',
    ];

    protected $casts = [
        'id'        => 'integer',
        'active'    => 'boolean',
        'user_type' => 'string',
        'status'    => 'string',
        'status_message' => 'string',
        'email_verified_at' => 'datetime',
        'email'     => 'string',
    ];

    public function isEmployer(): bool
    {
        return $this->user_type === 'employer';
    }

    public function isJobSeeker(): bool
    {
        return $this->user_type === 'job_seeker';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    // Since email is not in users or job_seekers... Let's fetch from Shield auth_identities
    public function getEmail(): ?string
    {
        $db = db_connect();

        $row = $db->table('auth_identities')
            ->select('secret')
            ->where('user_id', $this->id)
            ->where('type', 'email_password')
            ->get()
            ->getRow();

        return $row ? $row->secret : null;
    }
}
