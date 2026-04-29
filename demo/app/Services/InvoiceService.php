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
    public function sendSubscriptionInvoice($userId, $subscriptionId, $paymentId, $amount, $months)
    {
        $subscriptionModel = model(UserSubscriptionModel::class);
        $planModel = model(PlanModel::class);

        $subscription = $subscriptionModel->find($subscriptionId);
        if (!$subscription) {
            throw new \Exception('Subscription not found');
        }

        $plan = $planModel->find($subscription->plan_id);
        $employer = $this->employerModel->where('user_id', $userId)->first();
        $payment = $this->paymentModel->find($paymentId);

        $invoiceData = [
            'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($paymentId, 5, '0', STR_PAD_LEFT),
            'date' => date('F d, Y'),
            'due_date' => date('F d, Y', strtotime('+7 days')),
            'company_name' => $employer->company_name,
            'company_email' => $employer->contact_email ?? $employer->company_email,
            'company_phone' => $employer->contact_phone,
            'company_address' => $employer->company_address,
            'item_name' => "{$plan->name} Subscription",
            'item_description' => "{$months} Month(s) - Unlimited job postings + Premium Features",
            'quantity' => $months,
            'unit_price' => number_format($amount / $months, 2),
            'subtotal' => number_format($amount, 2),
            'tax' => '0.00',
            'total' => number_format($amount, 2),
            'payment_method' => ucfirst($payment['payment_method']),
            'payment_reference' => $payment['reference'],
            'payment_date' => date('F d, Y H:i', strtotime($payment['paid_at'])),
            'plan_features' => $this->formatPlanFeatures($plan),
            'valid_until' => date('F d, Y', strtotime($subscription['ends_at']))
        ];

        $subject = "Invoice for {$plan->name} Subscription - " . $invoiceData['invoice_number'];
        $htmlContent = $this->generateInvoiceHTML($invoiceData, 'subscription');

        return $this->sendEmail($employer->contact_email ?? $employer->company_email, $subject, $htmlContent);
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

        $invoiceData = [
            'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($paymentId, 5, '0', STR_PAD_LEFT),
            'date' => date('F d, Y'),
            'due_date' => date('F d, Y', strtotime('+7 days')),
            'company_name' => $employer->company_name,
            'company_email' => $employer->contact_email ?? $employer->company_email,
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

        $subject = "Invoice for {$bundle->name} - " . $invoiceData['invoice_number'];
        $htmlContent = $this->generateInvoiceHTML($invoiceData, 'bundle');

        return $this->sendEmail($employer->contact_email ?? $employer->company_email, $subject, $htmlContent);
    }

    /**
     * Generate beautiful invoice HTML
     */
    private function generateInvoiceHTML($data, $type)
    {
        $logoPath = base_url('images/logo.png');

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Invoice ' . $data['invoice_number'] . '</title>
            <style>
                body {
                    font-family: "Segoe UI", Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background: #f5f5f5;
                    margin: 0;
                    padding: 20px;
                }
                .invoice-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                }
                .invoice-header {
                    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
                    color: white;
                    padding: 30px;
                    text-align: center;
                }
                .invoice-header h1 {
                    margin: 0;
                    font-size: 32px;
                }
                .invoice-header p {
                    margin: 5px 0 0;
                    opacity: 0.9;
                }
                .invoice-body {
                    padding: 30px;
                }
                .company-details {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                    padding-bottom: 20px;
                    border-bottom: 2px solid #e9ecef;
                }
                .bill-to {
                    margin-bottom: 30px;
                }
                .bill-to strong {
                    color: #555;
                    font-size: 13px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                .bill-to p {
                    margin: 5px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th {
                    background: #f8f9fa;
                    padding: 12px;
                    text-align: left;
                    border-bottom: 2px solid #dee2e6;
                }
                td {
                    padding: 12px;
                    border-bottom: 1px solid #eee;
                }
                .total-section {
                    text-align: right;
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 8px;
                    margin-top: 20px;
                }
                .total-amount {
                    font-size: 28px;
                    font-weight: bold;
                    color: #0d6efd;
                }
                .features-list {
                    margin: 20px 0;
                    padding: 0;
                    list-style: none;
                }
                .features-list li {
                    padding: 5px 0;
                }
                .features-list li:before {
                    content: "✓";
                    color: #28a745;
                    font-weight: bold;
                    margin-right: 10px;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #e9ecef;
                }
                .badge {
                    display: inline-block;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: 600;
                }
                .badge-success {
                    background: #d4edda;
                    color: #155724;
                }
            </style>
        </head>
        <body>
            <div class="invoice-container">
                <div class="invoice-header">
                    <img src="' . $logoPath . '" alt="Logo" style="height: 50px; margin-bottom: 10px;">
                    <h1>INVOICE</h1>
                    <p>' . $data['invoice_number'] . '</p>
                </div>
                
                <div class="invoice-body">
                    <div class="company-details">
                        <div>
                            <strong style="color: #555;">FROM</strong>
                            <h3 style="margin: 5px 0;">Jobber Recruit</h3>
                            <p>Lagos, Nigeria</p>
                            <p>support@jobberrecruit.com</p>
                        </div>
                        <div style="text-align: right;">
                            <strong style="color: #555;">INVOICE DATE</strong>
                            <p>' . $data['date'] . '</p>
                            <strong style="color: #555;">DUE DATE</strong>
                            <p>' . $data['due_date'] . '</p>
                        </div>
                    </div>
                    
                    <div class="bill-to">
                        <strong>BILL TO</strong>
                        <p><strong>' . htmlspecialchars($data['company_name']) . '</strong></p>
                        <p>' . htmlspecialchars($data['company_email']) . '</p>
                        <p>' . htmlspecialchars($data['company_phone']) . '</p>
                        <p>' . htmlspecialchars($data['company_address']) . '</p>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>' . htmlspecialchars($data['item_name']) . '</strong></td>
                                <td>' . htmlspecialchars($data['item_description']) . '</td>
                                <td>' . $data['quantity'] . '</td>
                                <td>₦' . $data['unit_price'] . '</td>
                                <td>₦' . $data['subtotal'] . '</td>
                            </tr>
                        </tbody>
                    </table>';

        if ($type === 'subscription' && isset($data['plan_features'])) {
            $html .= '
                    <div style="margin-top: 20px;">
                        <strong>Plan Features Included:</strong>
                        <ul class="features-list">
                            ' . $data['plan_features'] . '
                        </ul>
                        <p><strong>Valid Until:</strong> ' . $data['valid_until'] . '</p>
                    </div>';
        } elseif ($type === 'bundle') {
            $html .= '
                    <div style="margin-top: 20px; background: #e7f3ff; padding: 15px; border-radius: 8px;">
                        <strong>🎉 ' . number_format($data['credits']) . ' Job Credits Added!</strong>
                        <p style="margin: 5px 0 0;">Use these credits to post jobs. 1 credit = 1 job posting.</p>
                    </div>';
        }

        $html .= '
                    <div class="total-section">
                        <div>Subtotal: ₦' . $data['subtotal'] . '</div>
                        <div>Tax (0%): ₦' . $data['tax'] . '</div>
                        <div class="total-amount">Total: ₦' . $data['total'] . '</div>
                    </div>
                    
                    <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 8px;">
                        <strong>Payment Details</strong><br>
                        Method: ' . $data['payment_method'] . '<br>
                        Reference: ' . $data['payment_reference'] . '<br>
                        Date: ' . $data['payment_date'] . '
                    </div>
                </div>
                
                <div class="footer">
                    <p>Thank you for choosing Jobber Recruit!</p>
                    <p>For support, contact us at support@jobberrecruit.com</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
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
    private function sendEmail($to, $subject, $htmlContent)
    {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($htmlContent);
        $this->email->setMailType('html');

        // Set from address
        $this->email->setFrom(env('email.from_email') ?? 'noreply@jobberrecruit.com', env('email.from_name') ?? 'Jobber Recruit');

        // Attach invoice as PDF (optional - you can use a PDF library like Dompdf)
        // $pdfContent = $this->generatePDF($htmlContent);
        // $this->email->attach($pdfContent, 'attachment', $data['invoice_number'] . '.pdf');

        if (!$this->email->send()) {
            log_message('error', 'Failed to send invoice email to ' . $to . ': ' . $this->email->printDebugger());
            return false;
        }

        return true;
    }
}
