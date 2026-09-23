<?php

namespace App\Services;

use App\Models\JobCreditWalletModel;
use App\Models\JobCreditTransactionModel;

class JobCreditService
{
    public function getCredits(int $userId): int
    {
        return (new JobCreditWalletModel())
            ->where('user_id', $userId)
            ->selectSum('credits')
            ->get()
            ->getRow()->credits ?? 0;
    }

    public function deduct(int $userId, int $jobId, float $amount, string $usage)
    {
        $wallet = (new JobCreditWalletModel())
            ->where('user_id', $userId)
            ->orderBy('expires_at', 'ASC')
            ->first();

        if (!$wallet || $wallet['credits'] < $amount) {
            throw new \Exception('No job credits available');
        }

        $wallet['credits'] -= $amount;
        (new JobCreditWalletModel())->save($wallet);

        (new JobCreditTransactionModel())->insert([
            'user_id' => $userId,
            'type' => 'debit',
            'credits' => $amount,
            'description' => 'Job posting',
            'usage' => $usage ?? null,
            'meta' => json_encode(['job_id' => $jobId])
        ]);
    }
}
