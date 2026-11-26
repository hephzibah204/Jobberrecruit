<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PaymentModel;
use App\Models\UserSubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\JobCreditWalletModel;
use App\Models\UserModel;
use App\Models\EmployerModel; // Assuming you have this for company_name

class WebhookController extends Controller
{
    public function paystack()
    {
        $payload = file_get_contents('php://input');

        // --------------------------------------------------
        // 1. HMAC-SHA512 SIGNATURE VERIFICATION
        // --------------------------------------------------
        $signature = $this->request->getHeaderLine('x-paystack-signature') ?: $this->request->getServer('HTTP_X_PAYSTACK_SIGNATURE');
        $secretKey = env('PAYSTACK_SECRET_KEY') ?: (env('paystack_secret_key') ?: env('paystack.secret_key'));
        
        if (empty($secretKey)) {
            log_message('critical', 'Paystack webhook: PAYSTACK_SECRET_KEY is not configured in .env.');
            return $this->response->setStatusCode(401)->setBody('Unauthorized');
        }
        
        $computed = hash_hmac('sha512', $payload, $secretKey);
        if (empty($signature) || !hash_equals($computed, (string)$signature)) {
            log_message('error', 'Paystack webhook: Invalid HMAC signature. Ensure live secret key matches webhook environment.');
            return $this->response->setStatusCode(401)->setBody('Unauthorized');
        }

        // --------------------------------------------------
        // 2. READ & PARSE PAYLOAD
        // --------------------------------------------------
        $event   = json_decode($payload, true);

        if (! is_array($event) || empty($event['event'])) {
            return $this->response->setStatusCode(200);
        }

        $eventType = $event['event'];
        $data      = $event['data'] ?? [];

        $meta = \App\Services\PaymentFulfillmentService::metadata($data);
        if (in_array($eventType, ['charge.success', 'transaction.success'], true) && ($meta['type'] ?? '') === 'bundle') {
            try {
                (new \App\Services\BundleService())->fulfill($data);
                return $this->response->setStatusCode(200)->setBody('OK');
            } catch (\InvalidArgumentException $e) {
                return $this->response->setStatusCode(400)->setBody('Bundle payment mismatch');
            } catch (\Throwable $e) {
                log_message('error', 'Bundle webhook fulfillment failed: ' . $e->getMessage());
                return $this->response->setStatusCode(500)->setBody('Bundle activation failed');
            }
        }
        if (in_array($eventType, ['charge.success', 'transaction.success'], true) && ($meta['type'] ?? '') === 'webinar') {
            try {
                (new \App\Services\WebinarPurchaseService())->fulfill($data);
                return $this->response->setStatusCode(200)->setBody('OK');
            } catch (\InvalidArgumentException $e) {
                return $this->response->setStatusCode(400)->setBody('Webinar payment mismatch');
            } catch (\Throwable $e) {
                log_message('error', 'Webinar fulfillment failed: ' . $e->getMessage());
                return $this->response->setStatusCode(500)->setBody('Webinar registration failed');
            }
        }
        // Recurring Paystack charges can omit the original metadata. Resolve
        // their exact gateway plan code against the same catalog as checkout.
        if (in_array($eventType, ['charge.success', 'transaction.success'], true)
            && empty($meta['type']) && !empty($data['plan']['plan_code'])) {
            $catalogPlan = model(\App\Models\PlanModel::class)
                ->where('paystack_plan_code', $data['plan']['plan_code'])->first();
            $payer = !empty($data['customer']['email']) ? $this->findPayingUser($data['customer']['email']) : null;
            if ($catalogPlan && $catalogPlan->plan_type !== 'candidate' && $payer) {
                $meta = ['type' => 'subscription', 'user_id' => (int) $payer->id,
                    'plan_id' => (int) $catalogPlan->id, 'months' => 1];
                $data['metadata'] = $meta;
            }
        }
        if (in_array($eventType, ['charge.success', 'transaction.success'], true)
            && ($meta['type'] ?? '') === 'subscription') {
            try {
                (new \App\Services\EmployerSubscriptionPaymentService())->fulfill($data);
                return $this->response->setStatusCode(200)->setBody('OK');
            } catch (\InvalidArgumentException $e) {
                log_message('error', 'Subscription webhook mismatch: ' . $e->getMessage());
                return $this->response->setStatusCode(400)->setBody('Subscription payment mismatch');
            } catch (\Throwable $e) {
                log_message('error', 'Subscription webhook activation: ' . $e->getMessage());
                return $this->response->setStatusCode(500)->setBody('Subscription activation failed');
            }
        }
        if (in_array($eventType, ['charge.success', 'transaction.success'], true)
            && in_array($meta['type'] ?? '', ['wallet_funding', 'cv_review', 'candidate_plan', 'course'], true)) {
            try {
                (new \App\Services\PaymentFulfillmentService())->fulfill($data);
                return $this->response->setStatusCode(200)->setBody('OK');
            } catch (\InvalidArgumentException $e) {
                log_message('error', 'Webhook payment mismatch: ' . $e->getMessage());
                return $this->response->setStatusCode(400)->setBody('Payment order mismatch');
            } catch (\Throwable $e) {
                log_message('error', 'Webhook fulfillment: ' . $e->getMessage());
                return $this->response->setStatusCode(500)->setBody('Payment fulfillment failed');
            }
        }

        // --------------------------------------------------
        // MODELS
        // --------------------------------------------------
        $paymentModel = model(PaymentModel::class);
        $subModel     = model(UserSubscriptionModel::class);
        $planModel    = model(SubscriptionPlanModel::class);
        $userModel    = model(UserModel::class);
        $employerModel = model(EmployerModel::class); // For company_name

        $requestIp = $this->request->getIPAddress();
        log_message('info', "Paystack webhook received from {$requestIp}: {$eventType}");

        /*
        |--------------------------------------------------------------------------
        | 1) SUBSCRIPTION CREATED → STORE SUBSCRIPTION CODE
        |--------------------------------------------------------------------------
        */
        if ($eventType === 'subscription.create') {
            $subscriptionCode = $data['subscription_code'] ?? null;
            $customerEmail    = $data['customer']['email'] ?? null;

            if ($subscriptionCode && $customerEmail) {
                $user = $this->findPayingUser($customerEmail);

                if ($user) {
                    $subModel
                        ->where('user_id', $user->id)
                        ->where('is_active', 1)
                        ->set(['subscription_code' => $subscriptionCode, 'email_token' => $data['email_token'] ?? null])
                        ->update();

                    log_message('info', "Stored Paystack subscription code {$subscriptionCode} for user {$user->id}");
                }
            }

            return $this->response->setStatusCode(200);
        }

        /*
        |--------------------------------------------------------------------------
        | 2) CHARGE.SUCCESS → INITIAL PAYMENT OR RENEWAL → SEND INVOICE
        |--------------------------------------------------------------------------
        */
        if ($eventType === 'charge.success') {

            $reference     = $data['reference'] ?? null;
            $amount        = ($data['amount'] ?? 0) / 100;
            $paidAt        = $data['paid_at'] ?? date('Y-m-d H:i:s');
            $customerEmail = $data['customer']['email'] ?? null;
            $channel       = $data['channel'] ?? 'card';
            $planData      = $data['plan'] ?? [];
            $planCode      = $planData['plan_code'] ?? null;

            // Prevent duplicate processing
            if ($reference && $paymentModel->where('reference', $reference)->countAllResults() > 0) {
                return $this->response->setStatusCode(200);
            }

            // Find user
            $user = $customerEmail ? $this->findPayingUser($customerEmail) : null;
            if (! $user) {
                log_message('warning', "Webhook charge.success: User not found for email {$customerEmail}");
                return $this->response->setStatusCode(200);
            }

            // Find employer for company name (optional fallback)
            $employer = $employerModel->where('user_id', $user->id)->first();

            // 1. Check for Job Credit Bundle purchases
            $meta = $data['metadata'] ?? [];
            if (isset($meta['type']) && $meta['type'] === 'bundle' && !empty($meta['bundle_id'])) {
                $bundleAlreadyCredited = model(\App\Models\JobCreditTransactionModel::class)->where('reference', $reference)->countAllResults() > 0;
                $bundleService = new \App\Services\BundleService();
                $bundleService->credit(
                    userId: (int) ($meta['user_id'] ?? $user->id),
                    bundleId: (int) $meta['bundle_id'],
                    reference: $reference,
                    source: 'paystack'
                );

                if (!empty($meta['wallet_used']) && $meta['wallet_used'] > 0) {
                    (new \App\Services\WalletService())->debit(
                        userId: (int) ($meta['user_id'] ?? $user->id),
                        amount: (float) $meta['wallet_used'],
                        source: 'bundle_purchase_hybrid',
                        reference: $reference . '_wallet',
                        sourceId: (int) $meta['bundle_id'],
                        description: 'Partial wallet payment for bundle'
                    );
                }

                log_message('info', "Webhook: bundle {$meta['bundle_id']} credited for user {$user->id}");
                if (!$bundleAlreadyCredited) {
                    (new \App\Services\EmailNotificationService())->notifyEmployerAccount((int) $user->id,
                        'Job credit bundle purchased', 'Your payment was successful and your bundle credits have been added.',
                        ['Amount (NGN)' => number_format($amount, 2), 'Reference' => $reference]);
                }
                return $this->response->setStatusCode(200);
            }

            // 2. Check for E-learning Course enrollment purchases
            if (!empty($meta['course_id'])) {
                $courseId = (int) $meta['course_id'];
                $courseUserId = (int) ($meta['user_id'] ?? $user->id);
                $enrollmentModel = model(\App\Models\CourseEnrollmentModel::class);
                $existing = $enrollmentModel->where('course_id', $courseId)->where('user_id', $courseUserId)->first();
                if (!$existing) {
                    $enrollmentModel->insert([
                        'course_id' => $courseId,
                        'user_id' => $courseUserId,
                        'status' => 'enrolled',
                        'payment_reference' => $reference,
                        'amount' => $amount
                    ]);
                }
                log_message('info', "Webhook: enrolled user {$courseUserId} in course {$courseId}");
                return $this->response->setStatusCode(200);
            }

            // Find plan for Subscriptions
            $plan = null;
            if ($planCode) {
                $plan = $planModel->where('paystack_plan_code', $planCode)->first();
            }
            if (! $plan && $amount > 0 && ($meta['type'] ?? '') === 'subscription') {
                $plan = $planModel->where('price', $amount)->first();
            }

            if (! $plan) {
                log_message('info', "Webhook charge.success: Handled non-subscription charge for amount {$amount} / reference {$reference}");
                return $this->response->setStatusCode(200);
            }

            $db = \Config\Database::connect();
            $db->transStart();

            // Record payment
            $paymentModel->insert([
                'user_id'          => $user->id,
                'plan_id'          => $plan->id,
                'reference'        => $reference,
                'amount'           => $amount,
                'amount_paid'      => $amount,
                'currency'         => $data['currency'] ?? 'NGN',
                'status'           => 'paid',
                'channel'          => $channel,
                'ip_address'       => $data['ip_address'] ?? null,
                'gateway_response' => json_encode($data),
                'paid_at'          => $paidAt,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);

            // Extend/activate subscription
            $now   = new \DateTime();
            $start = $now->format('Y-m-d H:i:s');
            $end   = (clone $now)->modify("+{$plan->duration} days")->format('Y-m-d H:i:s');

            $subModel
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->set(['is_active' => 0, 'updated_at' => $now->format('Y-m-d H:i:s')])
                ->update();

            $subModel->insert([
                'user_id'                 => $user->id,
                'plan_id'                 => $plan->id,
                'starts_at'               => $start,
                'ends_at'                 => $end,
                'is_active'               => 1,
                'subscription_code' => $data['subscription']['subscription_code'] ?? null,
                'email_token' => $data['subscription']['email_token'] ?? null,
                'authorization'           => isset($data['authorization']) ? json_encode($data['authorization']) : null,
                'created_at'              => $start,
                'updated_at'              => $start,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', "Webhook charge.success transaction failed for user {$user->id}");
                return $this->response->setStatusCode(500)->setBody('Internal Server Error');
            }

            // --------------------------------------------------
            // SEND INVOICE EMAIL TO CUSTOMER
            // --------------------------------------------------
            $this->sendSubscriptionInvoiceEmail([
                'email'      => $user->email,
                'employer'   => $employer,                    // May be null
                'user'       => $user,
                'plan'       => $plan,
                'amountPaid' => $amount,
                'reference'  => $reference,
                'paidAt'     => $paidAt,
                'channel'    => $channel,
            ]);

            // Monthly credit refill for subscription renewals
            if (!empty($data['subscription']['subscription_code'])) {
                try {
                    (new \App\Services\SubscriptionService())->creditMonthly(
                        userId: (int) $user->id,
                        planId: (int) $plan->id,
                        reference: 'sub_renew_' . $reference,
                        source: 'subscription_renewal'
                    );
                    log_message('info', "Monthly credits refilled for user {$user->id}");
                } catch (\Throwable $e) {
                    log_message('error', "Failed to credit monthly for user {$user->id}: " . $e->getMessage());
                }
            }

            // Create in-app payment_confirmed notification for employer
            if ($employer) {
                try {
                    $planName  = $plan->name ?? 'Subscription';
                    $amountFmt = '₦' . number_format($amount, 0);
                    model(\App\Models\JobNotificationModel::class)->createNotification(
                        (int) $employer->id,
                        'payment_confirmed',
                        'Payment Confirmed',
                        "Your payment of {$amountFmt} for \"{$planName}\" was successful. Your subscription is now active until " . date('d M Y', strtotime($end)) . '.',
                        null,
                        null,
                        base_url('employer/subscription')
                    );
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to create payment_confirmed notification: ' . $e->getMessage());
                }
            }

            log_message('info', "Subscription activated/renewed and invoice emailed for user {$user->id}");

            return $this->response->setStatusCode(200);
        }

        /*
        |--------------------------------------------------------------------------
        | 3) INVOICE.PAYMENT_FAILED → SUSPEND + OPTIONAL EMAIL
        |--------------------------------------------------------------------------
        */
        if ($eventType === 'invoice.payment_failed') {
            $customerEmail = $data['customer']['email'] ?? null;

            if ($customerEmail) {
                $user = $this->findPayingUser($customerEmail);
                if ($user) {
                    $subModel
                        ->where('user_id', $user->id)
                        ->where('is_active', 1)
                        ->set(['is_active' => 0])
                        ->update();

                    (new \App\Services\EmailNotificationService())->notifyEmployerAccount((int) $user->id,
                        'Subscription payment failed', 'Your subscription renewal payment failed. Please review your payment method.',
                        ['Reference' => $data['reference'] ?? $data['invoice_code'] ?? 'Renewal']);

                    log_message('warning', "Subscription suspended for user {$user->id} – renewal failed");
                }
            }

            return $this->response->setStatusCode(200);
        }

        /*
        |--------------------------------------------------------------------------
        | 4) SUBSCRIPTION DISABLED / CANCELLED
        |--------------------------------------------------------------------------
        */
        if (in_array($eventType, ['subscription.disable', 'subscription.cancellation'])) {
            $subscriptionCode = $data['subscription_code'] ?? null;

            if ($subscriptionCode) {
                $subscription = $subModel
                    ->where('subscription_code', $subscriptionCode)
                    ->first();

                if ($subscription) {
                    $subModel
                        ->where('id', $subscription['id'])
                        ->where('is_active', 1)
                        ->set(['is_active' => 0])
                        ->update();

                    if (!empty($subscription['is_active'])) {
                        (new \App\Services\EmailNotificationService())->notifyEmployerAccount((int) $subscription['user_id'],
                            'Subscription ended', 'Your subscription has been cancelled and is no longer active.');
                    }

                    log_message('info', "Subscription cancelled on Paystack – deactivated locally for user {$subscription['user_id']}");
                }
            }

            return $this->response->setStatusCode(200);
        }

        /*
        |--------------------------------------------------------------------------
        | 5) SUBSCRIPTION.NOT_RENEW → MARK AS NON-RENEWING (UPCOMING CANCELLATION)
        |--------------------------------------------------------------------------
        | This event is sent when a subscription is cancelled/disabled but still active
        | until the current billing period ends (next_payment_date becomes null).
        | We keep the subscription active locally until the actual end.
        | Optional: Send a confirmation email that cancellation is scheduled.
        */
        if ($eventType === 'subscription.not_renew') {

            $subscriptionCode = $data['subscription_code'] ?? null;
            $customerEmail    = $data['customer']['email'] ?? null;

            if ($subscriptionCode && $customerEmail) {
                $user = $this->findPayingUser($customerEmail);

                if ($user) {
                    // Update the active subscription record
                    $updated = $subModel
                        ->where('user_id', $user->id)
                        ->where('subscription_code', $subscriptionCode)
                        ->set([
                            'will_not_renew' => 1,
                            'updated_at'     => date('Y-m-d H:i:s')
                        ])
                        ->update();

                    if ($updated) {
                        log_message('info', "Subscription marked as will_not_renew=1 for user {$user->id} (code: {$subscriptionCode})");

                        (new \App\Services\EmailNotificationService())->notifyEmployerAccount((int) $user->id,
                            'Subscription cancellation scheduled', 'Automatic renewal has been disabled. Your current access continues until the expiry date.');
                    }
                }
            }

            return $this->response->setStatusCode(200);
        }

        return $this->response->setStatusCode(200);
    }

    private function findPayingUser(string $email)
    {
        $identity = db_connect()->table('auth_identities')->where('type', 'email_password')
            ->where('LOWER(secret)', strtolower(trim($email)))->get()->getRow();
        return $identity ? model(UserModel::class)->find($identity->user_id) : null;
    }

    /**
     * Send subscription invoice/receipt email after successful charge
     */
    private function sendSubscriptionInvoiceEmail(array $data)
    {
        $emailService = \Config\Services::email();
        $emailService->clear();

        $emailService->setTo($data['email']);
        $emailService->setFrom(config('Email')->fromEmail, config('Email')->fromName);

        // Rendered from the admin email template system (template key:
        // subscription_invoice) — the same template InvoiceService uses, so
        // this email no longer has a separate hardcoded view body.
        $employer = $data['employer'] ?? null;
        $amount   = number_format($data['amountPaid'], 2);

        $escape = static fn ($value) => htmlspecialchars((string) $value);

        $rendered = (new \App\Services\EmailTemplateService())->render('subscription_invoice', [
            'invoice_number'    => $data['reference'],
            'date'              => date('F d, Y', strtotime($data['paidAt'])),
            'due_date'          => date('F d, Y', strtotime($data['paidAt'] . ' +7 days')),
            'company_name'      => $escape($employer->company_name ?? $data['user']->username ?? 'Employer'),
            'company_email'     => $escape($data['email']),
            'company_phone'     => $escape($employer->contact_phone ?? ''),
            'company_address'   => $escape($employer->company_address ?? ''),
            'item_name'         => $escape($data['plan']->name . ' Subscription'),
            'item_description'  => 'Monthly subscription',
            'quantity'          => 1,
            'unit_price'        => $amount,
            'subtotal'          => $amount,
            'tax'               => '0.00',
            'total'             => $amount,
            'payment_method'    => ucfirst($data['channel']),
            'payment_reference' => $data['reference'],
            'payment_date'      => date('F d, Y H:i', strtotime($data['paidAt'])),
            'proration_row_html'   => '',
            'proration_total_html' => '',
            // Preserve the two content blocks this email has always carried:
            // the payment-success badge and the auto-renewal note.
            'extra_section_html'   => '
                    <div style="background: #D4EDDA; color: #155724; padding: 12px 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28A745;">
                        <strong>✓ Payment Successful</strong><br>
                        Your payment has been successfully processed and your subscription is now active.
                    </div>
                    <p>Your subscription will automatically renew monthly. You can manage or cancel it anytime from your employer dashboard.</p>',
        ]);

        $emailService->setSubject($rendered['subject']);
        $emailService->setMessage($rendered['html']);
        if (!empty($rendered['text'])) {
            $emailService->setAltMessage($rendered['text']);
        }
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            log_message('error', 'Failed to send invoice email to ' . $data['email'] . ': ' . print_r($emailService->printDebugger(['headers']), true));
        } else {
            log_message('info', 'Subscription invoice emailed to ' . $data['email'] . ' (Ref: ' . $data['reference'] . ')');
        }
        (new \App\Services\EmailNotificationService())->sendAdministrativeEvent(
            'Employer subscription payment received',
            ($data['employer']->company_name ?? 'Employer') . ' paid for ' . $data['plan']->name . '.',
            ['Amount (NGN)' => number_format($data['amountPaid'], 2), 'Reference' => $data['reference']]
        );
    }

    /**
     * Send email confirming that subscription cancellation has been scheduled
     * (access continues until end of current period)
     */
    private function sendSubscriptionCancellationScheduledEmail($user)
    {
        $emailService = \Config\Services::email();

        $emailService->setTo($user->email);
        $emailService->setFrom('support@jobberrecruit.com', 'JobberRecruit');

        // Fetch current active subscription to get end date
        $subModel = model(UserSubscriptionModel::class);
        $subscription = $subModel
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->first();

        $endDate = $subscription ? date('F j, Y', strtotime($subscription->ends_at)) : 'your current billing period';

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        // Rendered from the admin email template system (template key:
        // subscription_cancellation_scheduled) instead of the hardcoded view.
        $rendered = (new \App\Services\EmailTemplateService())->render('subscription_cancellation_scheduled', [
            'fullname' => $employer->company_name ?? $user->username ?? 'Employer',
            'endDate'  => $endDate,
        ]);

        $emailService->setSubject($rendered['subject']);
        $emailService->setMessage($rendered['html']);
        if (!empty($rendered['text'])) {
            $emailService->setAltMessage($rendered['text']);
        }
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            log_message('error', 'Failed to send cancellation scheduled email to ' . $user->email . ': ' . print_r($emailService->printDebugger(['headers']), true));
        } else {
            log_message('info', 'Cancellation scheduled email sent to ' . $user->email);
        }
    }
}
