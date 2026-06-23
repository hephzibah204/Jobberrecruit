<?php

namespace App\Services;

use App\Models\BundlePackageModel;
use App\Models\JobCreditWalletModel;


class BundleRecommendationService
{
    public function recommend(int $userId)
    {
        $creditBalance = model(JobCreditWalletModel::class)
            ->where('user_id', $userId)
            ->selectSum('credits')
            ->get()
            ->getRow()->credits ?? 0;

        $bundles = model(BundlePackageModel::class)
            ->where('is_active', 1)
            ->orderBy('credits', 'ASC')
            ->findAll();

        // Low credits → recommend best value
        return model(BundlePackageModel::class)
            ->where('is_active', 1)
            ->orderBy('cost_per_credit', 'ASC')
            ->first();
    }
}
