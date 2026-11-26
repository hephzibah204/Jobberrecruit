<?php

namespace App\Services;

use App\Models\EmployerModel;
use App\Models\PaymentModel;
use App\Models\UserSubscriptionModel;
use App\Models\PlanBundleModel;
use App\Models\PlanModel;
use CodeIgniter\Email\Email;

class InvoiceService
{
    protected $email;
    protected $employerModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->employerModel = model(EmployerModel::class);
        $this->paymentModel = model(PaymentModel::class);
    }

    /**
     * Send invoice for subscription purchase
     */
    public function sendSubscriptionInvoice($userId, $subscriptionId, $paymentId, $amount, $months, $prorationData = null)
    {
        $subscriptionModel = model(UserSubscriptionModel::class);
        $planModel = model(PlanModel::class);

        $subscription = $subscriptionModel->find($subscriptionId);
        if (!$subscription) {
            throw new \Exception('Subscription not found');
        }
        $subscription = (object) $subscription;

        $plan = $planModel->find($subscription->plan_id);
        $employer = $this->employerModel->where('user_id', $userId)->first();
        $payment = $this->paymentModel->find($paymentId);
        $recipient = $this->recipient((int) $userId, $employer);

        $newPlanPrice = !empty($prorationData['new_plan_price']) ? (float)$prorationData['new_plan_price'] : $amount;
        $prorationDiscount = !empty($prorationData['proration_discount']) ? (float)$prorationData['proration_discount'] : 0.0;

        $invoiceData = [
            'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($paymentId, 5, '0', STR_PAD_LEFT),
            'date' => date('F d, Y'),
            'due_date' => date('F d, Y', strtotime('+7 days')),
            'company_name' => $employer->company_name,
            'company_email' => $recipient,
            'company_phone' => $employer->contact_phone,
            'company_address' => $employer->company_address,
            'item_name' => "{$plan->name} Subscription",
            'item_description' => "{$months} Month(s) - " . ((new CreditService())->planProvidesUnlimitedPosting($plan)
                ? 'Unlimited job postings + Premium Features'
                : ((int) $plan->monthly_job_credits * $months) . ' credits + Plan Features'),
            'quantity' => $months,
            'unit_price' => number_format($newPlanPrice / $months, 2),
            'subtotal' => number_format($newPlanPrice, 2),
            'proration_discount' => number_format($prorationDiscount, 2),
            'has_proration' => $prorationDiscount > 0,
            'days_remaining_credited' => $prorationData['days_remaining'] ?? 0,
            'tax' => '0.00',
            'total' => number_format($amount, 2),
            'payment_method' => ucfirst($payment['payment_method']),
            'payment_reference' => $payment['reference'],
            'payment_date' => date('F d, Y H:i', strtotime($payment['paid_at'])),
            'plan_features' => $this->formatPlanFeatures($plan),
            'valid_until' => date('F d, Y', strtotime($subscription->ends_at))
        ];

        $rendered = $this->renderInvoice($invoiceData, 'subscription');

        $sent = $this->sendEmail($recipient, $rendered['subject'], $rendered['html'], $rendered['text']);
        (new EmailNotificationService())->sendAdministrativeEvent(
            'Employer subscription payment received',
            $employer->company_name . ' paid for ' . $plan->name . '.',
            ['Amount (NGN)' => number_format($amount, 2), 'Reference' => $payment['reference'], 'Expires' => $subscription->ends_at]
        );
        return $sent;
    }

    /**
     * Send invoice for bundle purchase
     */
    public function sendBundleInvoice($userId, $bundleId, $paymentId, $amount, $credits)
    {
        $bundleModel = model(PlanBundleModel::class);

        $bundle = $bundleModel->find($bundleId);
        $employer = $this->employerModel->where('user_id', $userId)->first();
        $payment = $this->paymentModel->find($paymentId);
        $recipient = $this->recipient((int) $userId, $employer);

        $invoiceData = [
            'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($paymentId, 5, '0', STR_PAD_LEFT),
            'date' => date('F d, Y'),
            'due_date' => date('F d, Y', strtotime('+7 days')),
            'company_name' => $employer->company_name,
            'company_email' => $recipient,
            'company_phone' => $employer->contact_phone,
            'company_address' => $employer->company_address,
            'item_name' => $bundle->name,
            'item_description' => "{$credits} Job Posting Credits - Pay As You Go",
            'quantity' => 1,
            'unit_price' => number_format($amount, 2),
            'subtotal' => number_format($amount, 2),
            'tax' => '0.00',
            'total' => number_format($amount, 2),
            'payment_method' => ucfirst($payment['payment_method']),
            'payment_reference' => $payment['reference'],
            'payment_date' => date('F d, Y H:i', strtotime($payment['paid_at'])),
            'credits' => $credits
        ];

        $rendered = $this->renderInvoice($invoiceData, 'bundle');

        $sent = $this->sendEmail($recipient, $rendered['subject'], $rendered['html'], $rendered['text']);
        (new EmailNotificationService())->sendAdministrativeEvent(
            'Employer bundle payment received',
            $employer->company_name . ' purchased ' . $bundle->name . '.',
            ['Amount (NGN)' => number_format($amount, 2), 'Reference' => $payment['reference'], 'Job credits' => $credits]
        );
        return $sent;
    }

    /**
     * Render the invoice email from the admin email template system
     * (template key: subscription_invoice).
     *
     * The flat {variable} template parser has no conditionals or loops, so the
     * few branching fragments of the old inline builder (proration row, totals
     * discount line, plan-features block / bundle credits banner) are built
     * here and passed in as fragment variables. The senders always receive the
     * rendered subject, HTML and text bodies from the template.
     */
    private function renderInvoice($data, $type): array
    {
        $data['proration_row_html']   = '';
        $data['proration_total_html'] = '';
        if (!empty($data['has_proration'])) {
            $data['proration_row_html'] = '
                    <tr style="background: #E8F4FD;">
                        <td style="padding: 12px; border-bottom: 1px solid #EEEEEE;"><strong style="color: #0861A9;">Upgrade Proration Credit</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #EEEEEE; color: #0861A9;">Credit from remaining ' . ($data['days_remaining_credited'] ?? 0) . ' days on active subscription</td>
                        <td style="padding: 12px; border-bottom: 1px solid #EEEEEE;">1</td>
                        <td style="padding: 12px; border-bottom: 1px solid #EEEEEE; color: #0861A9;">-₦' . $data['proration_discount'] . '</td>
                        <td style="padding: 12px; border-bottom: 1px solid #EEEEEE; color: #0861A9; font-weight: bold;">-₦' . $data['proration_discount'] . '</td>
                    </tr>';
            $data['proration_total_html'] = '<div style="color: #0861A9;">Proration Discount: -₦' . $data['proration_discount'] . '</div>';
        }

        $data['extra_section_html'] = '';
        if ($type === 'subscription' && isset($data['plan_features'])) {
            $featuresList = str_replace(
                '<li>',
                '<li style="padding: 5px 0;"><span style="color: #28A745; font-weight: bold; margin-right: 10px;">✓</span>',
                $data['plan_features']
            );
            $data['extra_section_html'] = '
                    <div style="margin-top: 20px;">
                        <strong>Plan Features Included:</strong>
                        <ul style="margin: 20px 0; padding: 0; list-style: none;">
                            ' . $featuresList . '
                        </ul>
                        <p><strong>Valid Until:</strong> ' . $data['valid_until'] . '</p>
                    </div>';
        } elseif ($type === 'bundle') {
            $data['extra_section_html'] = '
                    <div style="margin-top: 20px; background: #E7F3FF; padding: 15px; border-radius: 8px;">
                        <strong>🎉 ' . number_format($data['credits']) . ' Job Credits Added!</strong>
                        <p style="margin: 5px 0 0;">Use these credits to post jobs. 1 credit = 1 job posting.</p>
                    </div>';
        }

        // Escape the same free-text fields the previous inline builder escaped
        // with htmlspecialchars(); EmailTemplateModel::parseVariables()
        // substitutes data values verbatim (the rendered subject is
        // html_entity_decode()d by EmailTemplateService::render(), so the
        // subject line is unaffected).
        foreach (['company_name', 'company_email', 'company_phone', 'company_address', 'item_name', 'item_description'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = htmlspecialchars((string) $data[$field]);
            }
        }

        return (new EmailTemplateService())->render('subscription_invoice', $data);
    }

    /**
     * Format plan features as HTML list items
     */
    private function formatPlanFeatures($plan)
    {
        $features = is_string($plan->features) ? json_decode($plan->features, true) : ($plan->features ?? []);

        $featureMap = [
            'featured' => 'Featured job listings (top position)',
            'network_blast' => 'Network Blast (115k+ reach)',
            'anonymous' => 'Anonymous job posting',
            'url_redirect' => 'Applicant URL redirection',
            'trust_badge' => 'Verified Hirer Badge',
            'priority_support' => 'Priority WhatsApp support'
        ];

        $html = '';
        foreach ($features as $key => $enabled) {
            if ($enabled && isset($featureMap[$key])) {
                $html .= '<li>' . htmlspecialchars($featureMap[$key]) . '</li>';
            }
        }

        return $html;
    }

    /**
     * Send email
     */
    private function sendEmail($to, $subject, $htmlContent, $textContent = null)
    {
        $this->email->clear();
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($htmlContent);
        if (!empty($textContent)) {
            $this->email->setAltMessage($textContent);
        }
        $this->email->setMailType('html');

        // Set from address
        $this->email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

        // Attach invoice as PDF (optional - you can use a PDF library like Dompdf)
        // $pdfContent = $this->generatePDF($htmlContent);
        // $this->email->attach($pdfContent, 'attachment', $data['invoice_number'] . '.pdf');

        if (!$this->email->send()) {
            log_message('error', 'Failed to send invoice email to ' . $to . ': ' . $this->email->printDebugger());
            return false;
        }

        return true;
    }

    private function recipient(int $userId, $employer): string
    {
        $user = model(\CodeIgniter\Shield\Models\UserModel::class)->find($userId);
        foreach ([$user->email ?? '', $employer->contact_email ?? '', $employer->company_email ?? ''] as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) return $email;
        }
        throw new \RuntimeException('No valid email address is available for the employer receipt.');
    }
}
