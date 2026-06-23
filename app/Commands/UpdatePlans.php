<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PlanModel;

class UpdatePlans extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'plans:update';
    protected $description = 'Update plan credits.';

    public function run(array $params)
    {
        $planModel = new PlanModel();
        $plans = $planModel->findAll();

        foreach ($plans as $plan) {
            $credits = 0;
            // Basic logic:
            if (stripos($plan->name, 'starter') !== false || stripos($plan->name, 'basic') !== false) {
                $credits = 10;
            } elseif (stripos($plan->name, 'pro') !== false || stripos($plan->name, 'standard') !== false) {
                $credits = 25;
            } elseif (stripos($plan->name, 'premium') !== false || stripos($plan->name, 'advanced') !== false || stripos($plan->name, 'enterprise') !== false) {
                $credits = 100;
            } else {
                // If it's a bundle, or something else
                if ($plan->plan_type === 'bundle') {
                    $credits = 5; // Default for bundle if not set
                } else {
                    $credits = 5; // Default free/starter
                }
            }

            CLI::write("Updating plan {$plan->name} with {$credits} credits.", 'yellow');
            $planModel->update($plan->id, ['monthly_job_credits' => $credits]);
        }
        
        CLI::write('Plans updated successfully!', 'green');
    }
}
