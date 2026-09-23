<?php

namespace App\Services;

use App\Models\PlanBundleModel;
use App\Models\JobCreditWalletModel;
use App\Models\JobCreditTransactionModel;


class BundleService
{
    public function apply(int $userId, string $bundleCode)
    {
        $bundle = (new PlanBundleModel())
            ->where('slug', $bundleCode)
            ->first();

        (new JobCreditWalletModel())->insert([
            'user_id' => $userId,
            'credits' => $bundle->job_credits,
            'source' => 'bundle'
        ]);

        (new JobCreditTransactionModel())->insert([
            'user_id' => $userId,
            'type' => 'credit',
            'credits' => $bundle->job_credits,
            'description' => 'Job bundle purchase',
            'reference' => $bundleCode
        ]);
    }

    // public function creditFromWebhook(array $data)
    // {
    //     $meta = $data['metadata'];

    //     $bundle = model(PlanBundleModel::class)->find($meta['bundle_id']);
    //     if (!$bundle) return;

    //     model(JobCreditWalletModel::class)->insert([
    //         'user_id' => $meta['user_id'],
    //         'credits' => $bundle->job_credits,
    //         'source'  => 'bundle'
    //     ]);

    //     model(JobCreditTransactionModel::class)->insert([
    //         'user_id'    => $meta['user_id'],
    //         'type'       => 'credit',
    //         'credits'    => $bundle->job_credits,
    //         'reference'  => $data['reference'],
    //         'description' => 'Bundle purchase'
    //     ]);
    // }

    /**
     * Credit job credits for a bundle purchase
     * $reference MUST be unique (Paystack ref or wallet tx ref)
     */
    public function credit(
        int $userId,
        int $bundleId,
        string $reference,
        string $source = 'paystack'
    ): void {
        $db = db_connect();
        $db->transBegin();

        // Idempotency check
        $exists = (new JobCreditTransactionModel())
            ->where('reference', $reference)
            ->countAllResults();

        if ($exists > 0) {
            $db->transRollback();
            return;
        }

        $bundle = (new PlanBundleModel())->find($bundleId);
        if (!$bundle) {
            $db->transRollback();
            throw new \RuntimeException('Invalid bundle');
        }

        // Credit wallet
        (new JobCreditWalletModel())->insert([
            'user_id' => $userId,
            'credits' => $bundle['job_credits'],
            'source'  => 'bundle'
        ]);

        // Log transaction
        (new JobCreditTransactionModel())->insert([
            'user_id'     => $userId,
            'type'        => 'credit',
            'credits'     => $bundle['job_credits'],
            'reference'   => $reference,
            'description' => 'Bundle purchase via ' . $source,
            'meta'        => json_encode([
                'bundle_id' => $bundleId,
                'source'    => $source
            ])
        ]);

        $db->transCommit();
    }

    /**
     * Feature eligibility granted by bundles
     */
    public function allows(string $feature): bool
    {
        return in_array($feature, [
            'anonymous_job',
            'network_blast',
            'featured_job',
        ], true);
    }
}
