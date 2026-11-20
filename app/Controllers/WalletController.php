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
        if (!is_finite($amount) || $amount < 100) {
            return redirect()->back()->with('error', 'Minimum deposit amount is ₦100.00.');
        }

        $user = auth()->user();
        $callbackUrl = base_url('wallet/callback');
        $reference = (new \App\Services\PaymentFulfillmentService())->createPending((int) $user->id, 'wallet_funding', $amount);

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

            $userId = isset($meta['user_id']) ? (int) $meta['user_id'] : (int) auth()->id();

            $type = $meta['type'] ?? null;

            $sessionUser = auth()->user();
            $user = $sessionUser ?: ($userId ? model(\App\Models\UserModel::class)->find($userId) : null);
            $userType = $user->user_type ?? 'job_seeker';
            // The session may not survive the Paystack round-trip; fulfillment
            // is anchored to the verified order, so only reject when a
            // DIFFERENT signed-in account returns the reference.
            if ($type !== 'wallet_funding' || !$user || ($sessionUser && $userId !== (int) $sessionUser->id)) {
                return redirect()->to('dashboard')->with('error', 'Payment does not match your wallet order.');
            }
            try {
                (new \App\Services\PaymentFulfillmentService())->fulfill($data, $userId);
            } catch (\Throwable $e) {
                log_message('error', 'Wallet funding: ' . $e->getMessage());
                return redirect()->to($userType === 'employer' ? 'employer/wallet' : 'candidate/wallet')
                    ->with('error', 'Payment could not be recorded. Please contact support with your reference.');
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
        $payload = str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')
            ? $this->request->getJSON(true) : $this->request->getPost();
        
        $type = $payload['type'] ?? null; // candidate_plan, employer_plan, subscription, bundle
        
        if ($type === 'bundle') {
            $itemId = (int) ($payload['bundle_id'] ?? $payload['item_id'] ?? 0);
        } else {
            $itemId = (int) ($payload['plan_id'] ?? $payload['item_id'] ?? 0);
        }
        $rawDuration = $payload['duration_months'] ?? 1;
        if (filter_var($rawDuration, FILTER_VALIDATE_INT) === false) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Invalid subscription duration.']);
        }
        $durationMonths = (int) $rawDuration;

        if (!$type || ($type !== 'cv_review' && $itemId <= 0)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid parameters specified.'
            ]);
        }

        if ($type === 'webinar') {
            try {
                (new \App\Services\WebinarPurchaseService())->register((int) $user->id, $itemId, true);
                return $this->response->setJSON(['success'=>true,'message'=>'Registration confirmed.',
                    'redirect_url'=>base_url('training/webinars/registered?id=' . $itemId)]);
            } catch (\Throwable $e) {
                return $this->response->setStatusCode(409)->setJSON(['success'=>false,'message'=>$e->getMessage()]);
            }
        }
        if (in_array($type, ['candidate_plan', 'course'], true)) {
            try {
                $purchase = (new \App\Services\PaymentFulfillmentService())->buyWithWallet((int) $user->id, $type, $itemId, $payload['idempotency_key'] ?? null);
                return $this->response->setJSON(['success' => true, 'message' => 'Purchase completed.', 'redirect_url' => $type === 'candidate_plan'
                    ? base_url('candidate/dashboard') : base_url('training/payment-acknowledgement/' . $itemId . '?reference=' . urlencode($purchase['reference']) . '&method=wallet')]);
            } catch (\Throwable $e) {
                return $this->response->setStatusCode(409)->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        try {
            $accountEvent = null;
            $response = \App\Services\PurchaseTransaction::run((int) $user->id, function () use ($payload, $type, $itemId, $durationMonths, $user, &$accountEvent) {
                if (in_array($type, ['employer_plan', 'subscription', 'bundle'], true) && $user->user_type !== 'employer') {
                    return $this->failForbidden('An employer account is required.');
                }
                $requestKey = $payload['idempotency_key'] ?? null;
                $fingerprint = hash('sha256', json_encode([$type, $itemId, $durationMonths, $payload['plan'] ?? null]));
                $reference = $requestKey ? 'wallet_pay_' . hash('sha256', $user->id . ':' . (string) $requestKey) : 'wallet_pay_' . bin2hex(random_bytes(16));
                if ($requestKey && ($prior = $this->paymentModel->where('reference', $reference)->first())) {
                    $priorMeta = \App\Services\PaymentFulfillmentService::metadata($prior);
                    if (($priorMeta['fingerprint'] ?? '') !== $fingerprint) {
                        throw new \InvalidArgumentException('Checkout key was already used for a different purchase.');
                    }
                    return $this->response->setJSON($priorMeta['response']);
                }
                if ($type === 'course' && model(\App\Models\CourseEnrollmentModel::class)->where('course_id', $itemId)->where('user_id', $user->id)->first()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You are already enrolled in this course.']);
                }
                if ($type === 'webinar' && model(\App\Models\WebinarRegistrationModel::class)->where('webinar_id', $itemId)->where('user_id', $user->id)->first()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You are already registered for this webinar.']);
                }
                $price = 0.0;
                $description = '';

                // 1. Calculate price and description depending on type
                if ($type === 'candidate_plan') {
                    $plan = $this->planModel->find($itemId);
                    if (!$plan || !$plan->is_active || $plan->plan_type !== 'candidate') {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Invalid candidate plan specified.'
                        ]);
                    }
                    $price = (float) $plan->base_price;
                    $description = 'Paid with wallet for plan: ' . $plan->name;
                } elseif ($type === 'course') {
                    $courseModel = model(\App\Models\CourseModel::class);
                    $course = $courseModel->find($itemId);
                    if (!$course) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Invalid course specified.'
                        ]);
                    }
                    $price = (float) $course->price;
                    $description = 'Paid with wallet for course: ' . $course->title;
                } elseif ($type === 'cv_review') {
                    $cvPlan = $payload['plan'] ?? 'professional';
                    $amounts = [
                        'professional' => (int) env('cv_review_pro_price', 15000),
                        'premium'      => (int) env('cv_review_prem_price', 30000),
                    ];
                    if (!isset($amounts[$cvPlan])) {
                        throw new \InvalidArgumentException('Invalid CV review plan.');
                    }
                    $price = (float) $amounts[$cvPlan];
                    $description = 'Paid with wallet for CV Review plan: ' . ucfirst($cvPlan);
                } elseif ($type === 'employer_plan' || $type === 'subscription') {
                    $plan = $this->planModel->find($itemId);
                    if (!$plan || !$plan->is_active || $plan->plan_type === 'candidate') {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Invalid subscription plan specified.'
                        ]);
                    }
            
                    $subService = new \App\Services\SubscriptionService();
                    $proration  = $subService->calculateUpgradeProration($user->id, (int)$plan->id, $durationMonths);
                    $price      = (float) $proration['net_amount_due'];

                    $description = 'Paid with wallet for subscription: ' . $plan->name . ' (' . $durationMonths . ' Month' . ($durationMonths > 1 ? 's' : '') . ')';
                    if ($proration['proration_discount'] > 0) {
                        $description .= ' (Prorated credit: ₦' . number_format($proration['proration_discount'], 2) . ')';
                    }
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
                } elseif ($type === 'webinar') {
                    $webinarModel = model(\App\Models\WebinarModel::class);
                    $webinar = $webinarModel->find($itemId);
                    if (!$webinar) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Invalid webinar specified.'
                        ]);
                    }
                    if (!\App\Models\WebinarModel::registrationOpen($webinar)) {
                        return $this->response->setStatusCode(409)->setJSON([
                            'success' => false,
                            'message' => 'Registration for this webinar is closed. Please choose an upcoming session.'
                        ]);
                    }
                    $price = (float) $webinar->price;
                    $description = 'Paid with wallet for webinar: ' . $webinar->title;
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid checkout type specified.'
                    ]);
                }

                if (!is_finite($price) || $price < 0) {
                    throw new \InvalidArgumentException('Invalid purchase price.');
                }

                // 2. Check current wallet balance
                $wallet = $this->walletService->getOrCreateWallet($user->id);
                if ((float) $wallet->balance < $price) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Insufficient wallet balance. (Balance: ₦' . number_format($wallet->balance, 2) . '). Please top up your wallet first.',
                        'insufficient' => true
                    ]);
                }

                $db = \Config\Database::connect();

                // 3. Debit Wallet (if price > 0)
                if ($price > 0) {
                    $this->walletService->debit(
                        userId: $user->id,
                        amount: $price,
                        source: 'wallet_checkout',
                        reference: $reference,
                        sourceId: $itemId ?: null,
                        description: $description
                    );
                }

                // 4. Insert Payment Ledger record
                $paymentId = $this->paymentModel->insert([
                    'user_id'          => $user->id,
                    'plan_id'          => in_array($type, ['candidate_plan', 'employer_plan', 'subscription'], true) ? $itemId : null,
                    'reference'        => $reference,
                    'amount'           => $price,
                    'status'           => 'paid',
                    'payment_method'   => 'wallet',
                    'paid_at'          => date('Y-m-d H:i:s'),
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ]);

                if (!$paymentId) {
                    throw new \RuntimeException('Payment could not be recorded.');
                }
                $now = date('Y-m-d H:i:s');

                // 5. Activate Purchased Platform Service
                if ($type === 'candidate_plan') {
                    $duration = 30;
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
                } elseif ($type === 'course') {
                    $enrollmentModel = model(\App\Models\CourseEnrollmentModel::class);
                    $existing = $enrollmentModel->where(['course_id' => $itemId, 'user_id' => $user->id])->first();
                    if (!$existing) {
                        $enrollmentModel->insert([
                            'course_id'         => $itemId,
                            'user_id'           => $user->id,
                            'status'            => 'enrolled',
                            'payment_reference' => $reference,
                            'amount'            => $price,
                        ]);

                        try {
                            $emailNotifService = new \App\Services\EmailNotificationService();
                            $courseObj = model(\App\Models\CourseModel::class)->find($itemId);
                            if ($courseObj) {
                                $emailNotifService->sendCourseEnrollmentNotification($user, $courseObj);
                            }
                        } catch (\Throwable $e) {
                            log_message('error', 'Wallet course enrollment notification error: ' . $e->getMessage());
                        }
                    }
                } elseif ($type === 'cv_review') {
                    $reviewId = (new \App\Services\PaymentFulfillmentService())->createCvReview((int) $user->id, $cvPlan, $price, $reference);
                } elseif ($type === 'employer_plan' || $type === 'subscription') {
                    // Deactivate employer old active subscriptions
                    $this->subModel->where('user_id', $user->id)->where('is_active', 1)->set(['is_active' => 0])->update();

                    $startsAt = date('Y-m-d H:i:s');
                    $endsAt = date('Y-m-d H:i:s', strtotime("+{$durationMonths} months"));

                    // Create new active subscription
                    $subscriptionId = $this->subModel->insert([
                        'user_id'    => $user->id,
                        'plan_id'    => $itemId,
                        'starts_at'  => $startsAt,
                        'ends_at'    => $endsAt,
                        'is_active'  => 1,
                        'auto_renew' => 0
                    ]);

                    $creditService = new \App\Services\CreditService();
                    $employerModel = model(\App\Models\EmployerModel::class);
                    $employer = $employerModel->where('user_id', $user->id)->first();
                    if ($employer && $creditService->planProvidesUnlimitedPosting($plan)) {
                        $employerModel->update($employer->id, [
                            'unlimited_access' => 1,
                            'unlimited_until'  => $endsAt
                        ]);
                    }

                    // Add monthly job credits to credit wallet
                    if ($plan->monthly_job_credits > 0) {
                        $creditService->addCredits(
                            $user->id,
                            $plan->monthly_job_credits * $durationMonths,
                            'subscription',
                            $subscriptionId,
                            $endsAt,
                            $reference
                        );
                    }

                    try {
                        (new \App\Services\InvoiceService())->sendSubscriptionInvoice(
                            $user->id,
                            $subscriptionId,
                            $paymentId,
                            $price,
                            $durationMonths,
                            $proration ?? null
                        );
                    } catch (\Exception $e) {
                        log_message('error', 'Failed to send subscription invoice via wallet checkout: ' . $e->getMessage());
                    }
                } elseif ($type === 'bundle') {
                    // Credit Bundle Credits
                    (new \App\Services\BundleService())->credit(
                        userId: $user->id,
                        bundleId: $itemId,
                        reference: $reference,
                        source: 'wallet'
                    );
                } elseif ($type === 'webinar') {
                    $registrationModel = model(\App\Models\WebinarRegistrationModel::class);
                    $existing = $registrationModel->where(['webinar_id' => $itemId, 'user_id' => $user->id])->first();
                    if (!$existing) {
                        $registrationModel->insert([
                            'webinar_id' => $itemId,
                            'user_id'    => $user->id
                        ]);
                        model(\App\Models\WebinarModel::class)->where('id', $itemId)->increment('registrants_count', 1);
                    }
                    try {
                        $webinarObj = model(\App\Models\WebinarModel::class)->find($itemId);
                        if ($webinarObj) {
                            (new \App\Services\EmailNotificationService())->sendWebinarRegistrationNotification($user, $webinarObj);
                        }
                    } catch (\Throwable $e) {
                        log_message('error', 'Wallet webinar registration notification error: ' . $e->getMessage());
                    }
                }

                $redirectUrl = null;
                if ($type === 'course') {
                    $redirectUrl = base_url('training/payment-acknowledgement/' . $itemId . '?reference=' . urlencode($reference) . '&method=wallet');
                } elseif ($type === 'candidate_plan') {
                    $redirectUrl = base_url('candidate/dashboard');
                } elseif ($type === 'cv_review') {
                    $redirectUrl = base_url('cv-review/submit?review_id=' . $reviewId);
                } elseif ($type === 'webinar') {
                    $redirectUrl = base_url('training/webinars/registered?id=' . $itemId);
                }

                $result = [
                    'success' => true,
                    'message' => 'Payment completed successfully using your wallet balance!',
                    'redirect_url' => $redirectUrl,
                ];
                $this->paymentModel->update($paymentId, ['metadata' => json_encode([
                    'type' => $type, 'fingerprint' => $fingerprint, 'response' => $result,
                    'subscription_id' => $subscriptionId ?? null,
                    'subscription_value' => $proration['new_plan_price'] ?? $price,
                ])]);
                if ($type === 'bundle') {
                    $accountEvent = ['Amount (NGN)' => number_format($price, 2), 'Reference' => $reference, 'Bundle ID' => $itemId];
                }
                return $this->response->setJSON($result);
            });
            if ($accountEvent) {
                (new \App\Services\EmailNotificationService())->notifyEmployerAccount((int) $user->id,
                    'Job credit bundle purchased', 'Your wallet payment was successful and the bundle credits have been added.', $accountEvent);
            }
            return $response;
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        } catch (\Throwable $e) {
            log_message('error', 'Wallet checkout: ' . $e->getMessage());
            return $this->response->setStatusCode(409)->setJSON(['success' => false, 'message' => 'Wallet payment could not be completed. Check your balance and try again.']);
        }
    }

}
