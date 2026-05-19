<?php

namespace App\Controllers;

use App\Models\PlanModel;
use App\Models\UserSubscriptionModel;
use App\Models\PaymentModel;
use App\Services\PaystackService;

class CandidateSubscriptionController extends BaseController
{
    protected $planModel;
    protected $subModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->planModel = model(PlanModel::class);
        $this->subModel = model(UserSubscriptionModel::class);
        $this->paymentModel = model(PaymentModel::class);
    }

    /**
     * Candidate pricing page
     */
    public function pricing()
    {
        $plans = $this->planModel
            ->where('plan_type', 'candidate')
            ->where('is_active', 1)
            ->orderBy('base_price', 'ASC')
            ->findAll();

        $currentPlan = null;
        if (auth()->loggedIn()) {
            $currentPlan = $this->subModel
                ->select('user_subscriptions.*, plans.name as plan_name')
                ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
                ->where('user_subscriptions.user_id', auth()->id())
                ->where('user_subscriptions.is_active', 1)
                ->first();
        }

        $isFreeMode = env('site_free_mode') === 'true';

        return view('candidate/pricing', [
            'title' => 'Candidate Premium Plans',
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'isFreeMode' => $isFreeMode,
        ]);
    }

    /**
     * Initiate payment for candidate plan
     */
    public function checkout()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login to subscribe');
        }

        $planId = (int) $this->request->getPost('plan_id');
        $plan = $this->planModel->find($planId);

        if (!$plan || $plan->plan_type !== 'candidate') {
            return redirect()->back()->with('error', 'Invalid plan');
        }

        if ((float) $plan->base_price <= 0) {
            return $this->activateFreePlan($plan);
        }

        $paystack = new PaystackService();
        $email = auth()->user()->email;
        $callbackUrl = base_url('candidate/subscription/verify');
        $reference = 'cand_sub_' . uniqid();

        $response = $paystack->initialize($email, $plan->base_price, $callbackUrl, [
            'plan_id' => $planId,
            'user_id' => auth()->id(),
            'reference' => $reference,
        ]);

        if ($response['status']) {
            $this->session->set('pending_subscription', [
                'plan_id' => $planId,
                'reference' => $reference,
            ]);
            return redirect()->to($response['data']['authorization_url']);
        }

        return redirect()->back()->with('error', 'Payment initialization failed');
    }

    /**
     * Verify Paystack payment for candidate subscription
     */
    public function verify()
    {
        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('candidate/subscription/pricing')->with('error', 'Invalid payment reference');
        }

        $paystack = new PaystackService();
        $response = $paystack->verify($reference);

        if ($response['status'] && $response['data']['status'] === 'success') {
            $pending = $this->session->get('pending_subscription');
            $planId = $pending['plan_id'] ?? $this->request->getGet('plan_id');

            if ($planId) {
                $this->activatePlan($planId, $reference, $response['data']['amount'] / 100);
                $this->session->remove('pending_subscription');
                return redirect()->to('candidate/dashboard')->with('success', 'Subscription activated successfully!');
            }
        }

        return redirect()->to('candidate/subscription/pricing')->with('error', 'Payment verification failed');
    }

    /**
     * Activate a plan for the current user
     */
    protected function activatePlan(int $planId, string $reference, float $amount)
    {
        $plan = $this->planModel->find($planId);
        if (!$plan) return;

        $userId = auth()->id();
        $now = date('Y-m-d H:i:s');
        $duration = (int) ($plan->pricing_tiers ? json_decode($plan->pricing_tiers, true)[1]['duration'] ?? 30 : 30);
        $endDate = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        // Deactivate existing subscription
        $this->subModel->where('user_id', $userId)->where('is_active', 1)->set(['is_active' => 0, 'updated_at' => $now])->update();

        // Record payment
        $this->paymentModel->insert([
            'user_id' => $userId,
            'plan_id' => $planId,
            'reference' => $reference,
            'amount' => $amount,
            'amount_paid' => $amount,
            'currency' => 'NGN',
            'status' => 'paid',
            'channel' => 'card',
            'paid_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create subscription
        $this->subModel->insert([
            'user_id' => $userId,
            'plan_id' => $planId,
            'start_date' => $now,
            'end_date' => $endDate,
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Activate free plan
     */
    protected function activateFreePlan($plan)
    {
        $userId = auth()->id();
        $now = date('Y-m-d H:i:s');
        $duration = 30;
        $endDate = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        $this->subModel->where('user_id', $userId)->where('is_active', 1)->set(['is_active' => 0, 'updated_at' => $now])->update();

        $this->subModel->insert([
            'user_id' => $userId,
            'plan_id' => $plan->id,
            'start_date' => $now,
            'end_date' => $endDate,
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()->to('candidate/dashboard')->with('success', 'Free plan activated!');
    }
}
