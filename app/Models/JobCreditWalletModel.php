<?php

namespace App\Models;

use CodeIgniter\Model;

class JobCreditWalletModel extends Model
{
    protected $table = 'job_credit_wallets';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id',
        'credits',          // remaining balance for this wallet entry
        'source',           // starter | subscription | bundle
        'reference_id',     // plan_id or bundle_id
        'expires_at'        // NULL = never expires (for starter/subscription monthly reset handled differently)
    ];

    /**
     * Get total available credits for a user (active only)
     */
    public function getAvailableCredits(int $userId): int
    {
        return (int) $this->where('user_id', $userId)
            ->where('credits >', 0)
            ->groupStart()
            ->where('expires_at', null)
            ->orWhere('expires_at >', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->selectSum('credits')
            ->get()
            ->getRow()
            ->credits ?? 0;
    }
}
