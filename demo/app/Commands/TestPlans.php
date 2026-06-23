<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PlanModel;

class TestPlans extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:plans';
    protected $description = 'Test fetching candidate plans from DB.';

    public function run(array $params)
    {
        $planModel = new PlanModel();
        $plans = $planModel
            ->where('plan_type', 'candidate')
            ->where('is_active', 1)
            ->orderBy('base_price', 'ASC')
            ->findAll();

        if (empty($plans)) {
            CLI::error('No candidate plans found.');
        } else {
            CLI::write('Found ' . count($plans) . ' candidate plans:', 'green');
            foreach ($plans as $plan) {
                CLI::write("- {$plan->name} ({$plan->plan_type}) - {$plan->base_price}");
            }
        }
    }
}
