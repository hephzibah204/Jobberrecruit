<?php

namespace App\Services;

use App\Models\PlanBundleModel;
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

        $bundles = model(PlanBundleModel::class)
            ->where('is_active', 1)
            ->orderBy('job_credits', 'ASC')
            ->findAll();

        // Low credits → recommend best value
        return model(PlanBundleModel::class)
            ->where('is_active', 1)
            ->orderBy('price_per_credit', 'ASC')
            ->first();
    }
}

