<?php

namespace App\Controllers;

use App\Models\PlanModel;

class TestPlansController extends BaseController
{
    public function index()
    {
        $planModel = new PlanModel();
        
        echo "=== ALL PLANS IN DATABASE ===\n";
        $all = $planModel->findAll();
        foreach ($all as $p) {
            echo "ID: {$p->id} | Code: {$p->code} | Type: {$p->plan_type} | Active: {$p->is_active}\n";
        }
        
        echo "\n=== CANDIDATE PLANS QUERY ===\n";
        $candidates = $planModel->where('plan_type', 'candidate')->where('is_active', 1)->findAll();
        foreach ($candidates as $p) {
            echo "ID: {$p->id} | Name: {$p->name}\n";
        }
        
        echo "\n=== FIND BY ID 1 ===\n";
        $plan = $planModel->find(1);
        if ($plan) {
            echo "ID: {$plan->id} | Type: {$plan->plan_type} | Is Candidate? " . ($plan->plan_type === 'candidate' ? 'YES' : 'NO') . "\n";
        } else {
            echo "Plan ID 1 not found.\n";
        }
    }
}
