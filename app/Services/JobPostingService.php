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
        service(FeatureGateService::class)->enforce($data, $userId);

        $jobId = model(JobModel::class)->insert($data);

        if ($jobId) {
            try {
                $creditService = new JobCreditService();
                $creditService->deduct($userId, $jobId, 1, 'job_posting');
            } catch (\Throwable $e) {
                log_message('error', "Credit deduction failed for job {$jobId}: " . $e->getMessage());
            }
        }

        return $jobId;
    }
}
