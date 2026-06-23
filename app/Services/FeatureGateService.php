<?php

namespace App\Services;

use App\Services\BundleService;
use App\Services\PlanFeatureService;

class FeatureGateService
{
    public function enforce(array &$data, int $userId)
    {
        $planService   = new PlanFeatureService();
        $bundleService = new BundleService();

        $features = [
            'is_anonymous'  => 'anonymous_job',
            'network_blast' => 'network_blast',
            'is_featured'   => 'featured_job',
            'external_url'  => 'url_redirect',
        ];

        foreach ($features as $field => $feature) {

            if (empty($data[$field])) {
                $data[$field] = 0;
                continue;
            }

            if (
                $planService->userAllows($userId, $feature)
                || $bundleService->allows($feature)
            ) {
                continue;
            }

            // ❌ Force disable
            $data[$field] = 0;
        }

        return $data;
    }
}
