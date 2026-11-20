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
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
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
        $walletBalance = 0.0;
        if (auth()->loggedIn()) {
            $currentPlan = $this->subModel
                ->select('user_subscriptions.*, plans.name as plan_name')
                ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
                ->where('user_subscriptions.user_id', auth()->id())
                ->where('user_subscriptions.is_active', 1)
                ->where('user_subscriptions.starts_at <=', date('Y-m-d H:i:s'))
                ->where('user_subscriptions.ends_at >', date('Y-m-d H:i:s'))
                ->where('plans.plan_type', 'candidate')
                ->orderBy('user_subscriptions.ends_at', 'DESC')
                ->first();

            $wallet = (new \App\Services\WalletService())->getOrCreateWallet(auth()->id());
            $walletBalance = (float) ($wallet->balance ?? 0.0);
        }

        $currentPlan = $currentPlan ? (object) $currentPlan : null;
        $isFreeMode = is_site_free_mode();

        return view('candidate/pricing', [
            'title' => 'Candidate Premium Plans',
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'isFreeMode' => $isFreeMode,
            'walletBalance' => $walletBalance,
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
        $paymentMethod = $this->request->getPost('payment_method');
        $plan = $this->planModel->find($planId);

        if (!$plan || $plan->plan_type !== 'candidate') {
            return redirect()->back()->with('error', 'Invalid plan');
        }

        if (!$plan->is_active) return redirect()->back()->with('error', 'This plan is unavailable.');
        try {
            $fulfillment = new \App\Services\PaymentFulfillmentService();
            if ($paymentMethod === 'wallet' || (float) $plan->base_price == 0) {
                $fulfillment->buyWithWallet((int) auth()->id(), 'candidate_plan', $planId, $this->request->getPost('checkout_key') ?: null);
                return redirect()->to('candidate/dashboard')->with('success', 'Subscription activated successfully.');
            }
            $reference = $fulfillment->createPending((int) auth()->id(), 'candidate_plan', (float) $plan->base_price, ['plan_id' => $planId]);
            $response = (new PaystackService())->initialize(auth()->user()->email, $plan->base_price,
                base_url('candidate/subscription/verify'), ['type' => 'candidate_plan', 'plan_id' => $planId,
                    'user_id' => auth()->id(), 'reference' => $reference]);
            if ($response['status'] ?? false) return redirect()->to($response['data']['authorization_url']);
        } catch (\Throwable $e) {
            log_message('error', 'Candidate checkout: ' . $e->getMessage());
        }
        return redirect()->back()->with('error', 'Payment could not be completed. Check your balance or try again.');
    }

    public function verify()
    {
        $reference = (string) $this->request->getGet('reference');
        try {
            $response = (new PaystackService())->verify($reference);
            if (!($response['status'] ?? false)) throw new \InvalidArgumentException('Payment verification failed.');
            // Order-anchored fulfillment (see ElearningController::verify):
            // the session may not survive the Paystack round-trip.
            (new \App\Services\PaymentFulfillmentService())->fulfill($response['data'], null, 'candidate_plan');
            if (!auth()->loggedIn()) {
                return redirect()->to('login')->with('success', 'Payment verified and subscription activated. Sign in to continue.');
            }
            return redirect()->to('candidate/dashboard')->with('success', 'Subscription activated successfully.');
        } catch (\Throwable $e) {
            log_message('error', 'Candidate verification: ' . $e->getMessage());
            return redirect()->to('candidate/subscription/pricing')->with('error', 'Payment could not be matched to your subscription order.');
        }
    }
}
