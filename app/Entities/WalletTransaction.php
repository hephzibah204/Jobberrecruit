<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class WalletTransaction extends Entity
{
    protected $casts = [
        'amount' => 'float',
        'balance_before' => 'float',
        'balance_after' => 'float'
    ];
}
