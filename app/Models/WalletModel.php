<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Wallet::class;

    protected $allowedFields = [
        'user_id','balance','currency','is_locked'
    ];

    protected $useTimestamps = true;
}
