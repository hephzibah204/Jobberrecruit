<?php

namespace App\Controllers;

use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\PaymentModel;
use App\Models\PlanModel;
use App\Models\UserSubscriptionModel;
use App\Models\PlanBundleModel;
use App\Models\JobCreditWalletModel;
use App\Services\PaystackService;
use App\Services\WalletService;
use CodeIgniter\API\ResponseTrait;

class WalletController extends BaseController
{
    use ResponseTrait;

    protected $walletService;
    protected $paystackService;
    protected $walletModel;
    protected $txModel;
    protected $paymentModel;
    protected $planModel;
    protected $subModel;

    public function __construct()
    {
        $this->walletService   = new WalletService();
        $this->paystackService = new PaystackService();
        $this->walletModel     = model(WalletModel::class);
        $this->txModel         = model(WalletTransactionModel::class);
        $this->paymentModel    = model(PaymentModel::class);
        $this->planModel       = model(PlanModel::class);
        $this->subModel        = model(UserSubscriptionModel::class);
        helper(['auth', 'form', 'url']);
    }

    /**
     * Candidate Wallet Dashboard
     */
    public function candidateWallet()
    {
        $userId = auth()->id();
        $wallet = $this->walletService->getOrCreateWallet($userId);
        
        $transactions = $this->txModel
            ->where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('candidate/wallet', [
            'title'        => 'My Wallet',
            'wallet'       => $wallet,
            'transactions' => $transactions,
            'user'         => auth()->user(),
        ]);
    }

    /**
     * Employer Wallet Dashboard
     */
    public function employerWallet()
    {
        $userId = auth()->id();
        $wallet = $this->walletService->getOrCreateWallet($userId);
        
        $transactions = $this->txModel
            ->where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Employer dashboard requires employer profile details
        $employer = model(\App\Models\EmployerModel::class)->where('user_id', $userId)->first();

        return view('employers/wallet', [
            'title'        => 'My Wallet',
            'wallet'       => $wallet,
            'transactions' => $transactions,
            'employer'     => $employer,
            'user'         => auth()->user(),
        ]);
    }

    /**
     * Initialize Funding via Paystack
     */
    public function initializeFunding()
    {
        $amount = (float) $this->request->getPost('amount');
        if ($amount < 100) {
            return redirect()->back()->with('error', 'Minimum deposit amount is ₦100.00.');
        }

        $user = auth()->user();
        $callbackUrl = base_url('wallet/callback');
        $reference = 'wallet_fund_' . uniqid();

        $metadata = [
            'type'      => 'wallet_funding',
            'user_id'   => $user->id,
            'reference' => $reference,
        ];

        $response = $this->paystackService->initialize($user->email, $amount, $callbackUrl, $metadata);

        if ($response['status'] && !empty($response['data']['authorization_url'])) {
            // Track pending reference in session
            session()->set('pending_wallet_funding', [
                'reference' => $reference,
                'amount'    => $amount,
            ]);

            return redirect()->to($response['data']['authorization_url']);
        }

        return redirect()->back()->with('error', 'Failed to initialize Paystack funding transaction. Please try again.');
    }

    /**
     * Payment callback verification
     */
    public function paymentCallback()
    {
        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('dashboard')->with('error', 'Invalid payment callback reference.');
        }

        $response = $this->paystackService->verify($reference);

        if ($response['status'] && $response['data']['status'] === 'success') {
            $data   = $response['data'];
            $amount = $data['amount'] / 100;
            $meta   = isset($data['metadata']) ? (is_array($data['metadata']) ? $data['metadata'] : json_decode($data['metadata'], true)) : [];
            
            // Normalize metadata
            if (isset($meta['app_data'])) {
                $meta = array_merge($meta, $meta['app_data']);
            }

            $userId = isset($meta['user_id']) ? (int) $meta['user_id'] : auth()->id();
            
            $type = $meta['type'] ?? 'wallet_funding';

            // Check if webhook already processed this completely
            $txExists = $this->paymentModel->where('reference', $reference)->where('status', 'paid')->first();
            
            if (!$txExists && $type === 'wallet_funding') {
                $db = \Config\Database::connect();
                $db->transStart();

                // 1. Record payment ledger
                $this->paymentModel->insert([
                    'user_id'          => $userId,
                    'reference'        => $reference,
                    'amount'           => $amount,
                    'status'           => 'paid',
                    'payment_method'   => $data['channel'] ?? 'card',
                    'metadata'         => json_encode($meta),
                    'paid_at'          => date('Y-m-d H:i:s'),
                ]);

                // 2. Credit Wallet
                $this->walletService->credit(
                    userId: $userId,
                    amount: $amount,
                    source: 'paystack_deposit',
                    reference: $reference,
                    description: 'Wallet funded successfully via Paystack.'
                );

                $db->transComplete();

                if ($db->transStatus() === false) {
                    log_message('error', 'Database transaction failed in paymentCallback for reference: ' . $reference);
                    return redirect()->to(($userType === 'employer') ? base_url('employer/wallet') : base_url('candidate/wallet'))
                        ->with('error', 'Payment processed but recording transaction failed. Please contact support.');
                }
            }

            session()->remove('pending_wallet_funding');
            
            // Re-fetch user to ensure we have the correct type for redirection
            $userModel = model(\App\Models\UserModel::class);
            $user = auth()->user() ?: $userModel->find($userId);
            
            $userType = $user->user_type ?? ($meta['user_type'] ?? 'candidate');

            // Redirect based on the purchase type
            if ($type === 'bundle') {
                $redirectTarget = base_url('employer/bundles');
                $message = 'Bundle purchased successfully!';
            } elseif ($type === 'employer_plan' || $type === 'subscription') {
                $redirectTarget = base_url('employer/pricing');
                $message = 'Subscription payment successful!';
            } elseif ($type === 'candidate_plan') {
                $redirectTarget = base_url('candidate/pricing');
                $message = 'Subscription payment successful!';
            } else {
                // Default to wallet
                $redirectTarget = ($userType === 'employer') ? base_url('employer/wallet') : base_url('candidate/wallet');
                $message = 'Wallet funded successfully with ₦' . number_format($amount, 2) . '!';
            }

            return redirect()->to($redirectTarget)->with('success', $message);
        }

        log_message('error', 'Paystack verification failed for reference: ' . $reference . ' Response: ' . json_encode($response));

        $user = auth()->user();
        if ($user) {
            $redirectTarget = ($user && $user->user_type === 'employer') ? base_url('employer/wallet') : base_url('candidate/wallet');
        } else {
            $redirectTarget = base_url('login');
        }
        return redirect()->to($redirectTarget)->with('error', 'Verification failed or payment cancelled.');
    }

    /**
     * Checkout paying entirely with wallet balance
     */
    public function payWithWallet()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to checkout.'
            ]);
        }

        $user = auth()->user();
        
        // Read JSON payload or standard POST variables
        $payload = $this->request->getJSON(true) ?: $this->request->getPost();
        
        $type = $payload['type'] ?? null; // candidate_plan, employer_plan, subscription, bundle
        
        if ($type === 'bundle') {
            $itemId = (int) ($payload['bundle_id'] ?? $payload['item_id'] ?? 0);
        } else {
            $itemId = (int) ($payload['plan_id'] ?? $payload['item_id'] ?? 0);
        }
        $durationMonths = (int) ($payload['duration_months'] ?? 1);

        if (!$type || !$itemId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid parameters specified.'
            ]);
        }

        $price = 0.0;
        $description = '';

        // 1. Calculate price and description depending on type
        if ($type === 'candidate_plan') {
            $plan = $this->planModel->find($itemId);
            if (!$plan || $plan->plan_type !== 'candidate') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid candidate plan specified.'
                ]);
            }
            $price = (float) $plan->base_price;
            $description = 'Paid with wallet for plan: ' . $plan->name;
        } elseif ($type === 'employer_plan' || $type === 'subscription') {
            $plan = $this->planModel->find($itemId);
            if (!$plan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid subscription plan specified.'
                ]);
            }
            
            $tiers = is_string($plan->pricing_tiers) ? json_decode($plan->pricing_tiers, true) : ($plan->pricing_tiers ?? []);
            $price = (float) ($tiers[$durationMonths] ?? ($plan->base_price * $durationMonths));
            $description = 'Paid with wallet for subscription: ' . $plan->name . ' (' . $durationMonths . ' Month' . ($durationMonths > 1 ? 's' : '') . ')';
        } elseif ($type === 'bundle') {
            $bundleModel = model(PlanBundleModel::class);
            $bundle = $bundleModel->find($itemId);
            if (!$bundle) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid job bundle specified.'
                ]);
            }
            $price = (float) $bundle->price;
            $description = 'Paid with wallet for bundle: ' . $bundle->name . ' (' . $bundle->job_credits . ' Credits)';
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid checkout type specified.'
            ]);
        }

        // 2. Check current wallet balance
        $wallet = $this->walletService->getOrCreateWallet($user->id);
        if ((float) $wallet->balance < $price) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Insufficient wallet balance. Please fund your wallet first.',
                'insufficient' => true
            ]);
        }

        $reference = 'wallet_pay_' . uniqid();

        $db = \Config\Database::connect();
        $db->transStart();

        // 3. Debit Wallet
        $this->walletService->debit(
            userId: $user->id,
            amount: $price,
            source: 'wallet_checkout',
            reference: $reference,
            sourceId: $itemId,
            description: $description
        );

        // 4. Insert Payment Ledger record
        $this->paymentModel->insert([
            'user_id'          => $user->id,
            'plan_id'          => ($type === 'bundle') ? null : $itemId,
            'reference'        => $reference,
            'amount'           => $price,
            'status'           => 'paid',
            'payment_method'   => 'wallet',
            'paid_at'          => date('Y-m-d H:i:s'),
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        $now = date('Y-m-d H:i:s');

        // 5. Activate Purchased Platform Service
        if ($type === 'candidate_plan') {
            $duration = (int) ($plan->pricing_tiers ? json_decode($plan->pricing_tiers, true)[1]['duration'] ?? 30 : 30);
            $endDate = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

            // Deactivate candidate old active subscription
            $this->subModel->where('user_id', $user->id)->where('is_active', 1)->set(['is_active' => 0, 'updated_at' => $now])->update();

            // Create new active subscription
            $this->subModel->insert([
                'user_id'    => $user->id,
                'plan_id'    => $itemId,
                'starts_at'  => $now,
                'ends_at'    => $endDate,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } elseif ($type === 'employer_plan' || $type === 'subscription') {
            // Deactivate employer old active subscriptions
            $this->subModel->where('user_id', $user->id)->where('is_active', 1)->set(['is_active' => 0])->update();

            $startsAt = date('Y-m-d H:i:s');
            $endsAt = date('Y-m-d H:i:s', strtotime("+{$durationMonths} months"));

            // Create new active subscription
            $this->subModel->insert([
                'user_id'    => $user->id,
                'plan_id'    => $itemId,
                'starts_at'  => $startsAt,
                'ends_at'    => $endsAt,
                'is_active'  => 1,
                'auto_renew' => 0
            ]);

            // Add monthly job credits to credit wallet
            if ($plan->monthly_job_credits > 0) {
                (new \App\Services\CreditService())->addCredits(
                    $user->id,
                    $plan->monthly_job_credits * $durationMonths,
                    'subscription',
                    $reference,
                    $endsAt
                );
            }
        } elseif ($type === 'bundle') {
            // Credit Bundle Credits
            (new \App\Services\BundleService())->credit(
                userId: $user->id,
                bundleId: $itemId,
                reference: $reference,
                source: 'wallet'
            );
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transaction failed during wallet payment process.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Payment completed successfully using your escrow wallet balance!'
        ]);
    }
}
