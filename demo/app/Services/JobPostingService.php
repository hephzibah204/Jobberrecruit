<?php

namespace App\Services;

use App\Models\JobModel;

class JobPostingService
{
    // public function post(array $data, int $userId)
    // {
    //     $creditService = new JobCreditService();

    //     if ($creditService->getCredits($userId) < 1) {
    //         throw new \Exception('Please purchase job credits');
    //     }

    //     $jobId = (new JobModel())->insert($data);

    //     $creditService->deduct($userId, $jobId);

    //     return $jobId;
    // }

    public function post(array $data, int $userId)
    {
        // 1️⃣ Enforce features
        service(FeatureGateService::class)->enforce($data, $userId);

        // 2️⃣ Check credits
        service(JobCreditService::class)->deduct($userId, null);

        // 3️⃣ Save job
        return model(JobModel::class)->insert($data);
    }
}
