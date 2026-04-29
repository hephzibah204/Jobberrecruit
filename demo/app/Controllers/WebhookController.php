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
        // --------------------------------------------------
        // 1. PAYSTACK IP WHITELIST
        // --------------------------------------------------
        $allowedIps = [
            '52.31.139.75',
            '52.49.173.169',
            '52.214.14.220',
            // '34.243.147.89',
            // '54.76.141.43',
            // '54.171.129.173',
        ];

        $requestIp = $this->request->getIPAddress();

        if (! in_array($requestIp, $allowedIps, true)) {
            log_message('error', 'Unauthorized Paystack webhook attempt from IP: ' . $requestIp);
            return $this->response->setStatusCode(401)->setBody('Unauthorized');
        }

        // --------------------------------------------------
        // 2. READ & PARSE PAYLOAD
        // --------------------------------------------------
        $payload = file_get_contents('php://input');
        $event   = json_decode($payload, true);

        if (! is_array($event) || empty($event['event'])) {
            return $this->response->setStatusCode(200);
        }

        $eventType = $event['event'];
        $data      = $event['data'] ?? [];

        // --------------------------------------------------
        // MODELS
        // --------------------------------------------------
        $paymentModel = model(PaymentModel::class);
        $subModel     = model(UserSubscriptionModel::class);
        $planModel    = model(SubscriptionPlanModel::class);
        $userModel    = model(UserModel::class);
        $employerModel = model(EmployerModel::class); // For company_name

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
                $user = $userModel->where('email', $customerEmail)->first();

                if ($user) {
                    $subModel
                        ->where('user_id', $user->id)
                        ->where('is_active', 1)
                        ->set(['subscription_code' => $subscriptionCode])
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
            $user = $customerEmail ? $userModel->where('email', $customerEmail)->first() : null;
            if (! $user) {
                log_message('warning', "Webhook charge.success: User not found for email {$customerEmail}");
                return $this->response->setStatusCode(200);
            }

            // Find employer for company name (optional fallback)
            $employer = $employerModel->where('user_id', $user->id)->first();

            // Find plan
            $plan = null;
            if ($planCode) {
                $plan = $planModel->where('paystack_plan_code', $planCode)->first();
            }
            if (! $plan && $amount > 0) {
                $plan = $planModel->where('price', $amount)->first();
            }

            if (! $plan) {
                log_message('error', "Webhook charge.success: Plan not found for amount {$amount} / code {$planCode}");
                return $this->response->setStatusCode(200);
            }

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
                'start_date'              => $start,
                'end_date'                => $end,
                'is_active'               => 1,
                'subscription_code' => $data['subscription']['subscription_code'] ?? null,
                'authorization'           => isset($data['authorization']) ? json_encode($data['authorization']) : null,
                'created_at'              => $start,
                'updated_at'              => $start,
            ]);

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

            log_message('info', "Subscription activated/renewed and invoice emailed for user {$user->id}");

            return $this->response->setStatusCode(200);
        }

        // webhook
        if ($eventType === 'charge.success') {

            $subCode = $event['data']['subscription']['subscription_code'];

            $subscription = model(UserSubscriptionModel::class)
                ->where('paystack_subscription_code', $subCode)
                ->first();

            if ($subscription) {

                // Extend subscription
                model(UserSubscriptionModel::class)
                    ->update($subscription['id'], [
                        'ends_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
                        'is_active' => 1
                    ]);

                // Monthly credit refill
                (new \App\Services\SubscriptionService())->creditMonthly(
                    userId: (int) $subscription['user_id'],
                    planId: (int) $subscription['plan_id'],
                    reference: 'sub_renew_' . $event['data']['reference'],
                    source: 'subscription_renewal'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3) INVOICE.PAYMENT_FAILED → SUSPEND + OPTIONAL EMAIL
        |--------------------------------------------------------------------------
        */
        if ($eventType === 'invoice.payment_failed') {
            $customerEmail = $data['customer']['email'] ?? null;

            if ($customerEmail) {
                $user = $userModel->where('email', $customerEmail)->first();
                if ($user) {
                    $subModel
                        ->where('user_id', $user->id)
                        ->where('is_active', 1)
                        ->set(['is_active' => 0])
                        ->update();

                    // Optional: Send payment failed notification
                    // $this->sendPaymentFailedEmail($user);

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
                        ->where('user_id', $subscription->user_id)
                        ->where('is_active', 1)
                        ->set(['is_active' => 0])
                        ->update();

                    // Optional: Send cancellation confirmation
                    // $this->sendSubscriptionCancelledEmail($subscription->user_id);

                    log_message('info', "Subscription cancelled on Paystack – deactivated locally for user {$subscription->user_id}");
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
                $user = $userModel->where('email', $customerEmail)->first();

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

                        // Send cancellation scheduled confirmation email
                        $this->sendSubscriptionCancellationScheduledEmail($user);
                    }
                }
            }

            return $this->response->setStatusCode(200);
        }

        return $this->response->setStatusCode(200);
    }

    /**
     * Send subscription invoice/receipt email after successful charge
     */
    private function sendSubscriptionInvoiceEmail(array $data)
    {
        $emailService = \Config\Services::email();

        $emailService->setTo($data['email']);
        $emailService->setFrom('billing@jobberrecruit.com', 'JobberRecruit');
        $emailService->setSubject('Your JobberRecruit Subscription Invoice - ' . $data['reference']);

        $viewData = [
            'fullname'       => $data['employer']->company_name ?? $data['user']->username ?? 'Employer',
            'planName'       => $data['plan']->name,
            'amount'         => number_format($data['amountPaid'], 2),
            'reference'      => $data['reference'],
            'paidAt'         => date('F j, Y \a\t g:i A', strtotime($data['paidAt'])),
            'channel'        => ucfirst($data['channel']),
            'companyAddress' => '6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria',
            'supportEmail'   => 'support@jobberrecruit.com',
            'logoUrl'        => base_url('images/logo-white.png'),
        ];

        $message = view('emails/subscription_invoice', $viewData);

        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            log_message('error', 'Failed to send invoice email to ' . $data['email'] . ': ' . print_r($emailService->printDebugger(['headers']), true));
        } else {
            log_message('info', 'Subscription invoice emailed to ' . $data['email'] . ' (Ref: ' . $data['reference'] . ')');
        }
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
        $emailService->setSubject('Subscription Cancellation Confirmed');

        // Fetch current active subscription to get end date
        $subModel = model(UserSubscriptionModel::class);
        $subscription = $subModel
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->first();

        $endDate = $subscription ? date('F j, Y', strtotime($subscription->end_date)) : 'your current billing period';

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $viewData = [
            'fullname'       => $employer->company_name ?? $user->username ?? 'Employer',
            'endDate'        => $endDate,
            'companyAddress' => '6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria',
            'supportEmail'   => 'support@jobberrecruit.com',
            'logoUrl'        => base_url('images/logo-white.png'),
        ];

        $message = view('emails/subscription_cancellation_scheduled', $viewData);

        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            log_message('error', 'Failed to send cancellation scheduled email to ' . $user->email . ': ' . print_r($emailService->printDebugger(['headers']), true));
        } else {
            log_message('info', 'Cancellation scheduled email sent to ' . $user->email);
        }
    }
}
