<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletTransactionModel extends Model
{
    protected $table = 'wallet_transactions';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\WalletTransaction::class;

    protected $allowedFields = [
        'wallet_id',
        'type',
        'amount',
        'reference',
        'description'
    ];

    protected $useTimestamps = false;
}
