<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Wallet extends Entity
{
    protected $casts = [
        'balance' => 'float',
        'is_locked' => 'boolean'
    ];

    public function canDebit(float $amount): bool
    {
        return ! $this->is_locked && $this->balance >= $amount;
    }
}
