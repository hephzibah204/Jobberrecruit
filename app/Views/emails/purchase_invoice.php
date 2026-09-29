<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Receipt & Invoice - JobberRecruit</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f9;
            color: #333333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #005DA8 0%, #003a6b 100%);
            padding: 35px 25px;
            text-align: center;
            color: #ffffff;
        }
        .header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 35px 30px;
        }
        .badge-success {
            background: #e6f7ec;
            color: #0e8345;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #10b981;
        }
        .badge-success h3 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
        }
        .badge-success p {
            margin: 0;
            font-size: 13px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .invoice-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            width: 40%;
        }
        .invoice-table td {
            padding: 12px 16px;
            font-size: 14px;
            color: #1e293b;
            font-weight: 500;
            border-bottom: 1px solid #e2e8f0;
        }
        .invoice-table tr.total th,
        .invoice-table tr.total td {
            background: #f1f5f9;
            font-size: 16px;
            font-weight: 700;
            color: #005DA8;
            border-top: 2px solid #cbd5e1;
            border-bottom: none;
        }
        .btn-action {
            display: inline-block;
            background: #005DA8;
            color: #ffffff !important;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f8fafc;
            padding: 25px 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #005DA8;
            text-decoration: none;
            margin: 0 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?= esc($logoUrl ?? base_url('images/logo.png')) ?>" alt="JobberRecruit Logo">
            <h1>Official Payment Receipt & Invoice</h1>
        </div>
        <div class="content">
            <p>Dear <strong><?= esc($userName ?? 'Valued Customer') ?></strong>,</p>

            <div class="badge-success">
                <h3>✓ Payment Confirmed</h3>
                <p>Your transaction has been processed successfully. Your itemized receipt details are below.</p>
            </div>

            <table class="invoice-table">
                <tr>
                    <th>Invoice Reference</th>
                    <td><code><?= esc($reference ?? ('INV-' . strtoupper(uniqid()))) ?></code></td>
                </tr>
                <tr>
                    <th>Item / Description</th>
                    <td><strong><?= esc($itemName ?? 'JobberRecruit Service') ?></strong></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><?= esc($itemCategory ?? 'Digital Service / Subscription') ?></td>
                </tr>
                <tr>
                    <th>Payment Date</th>
                    <td><?= esc($paidAt ?? date('F j, Y, g:i A')) ?></td>
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td><?= esc($paymentChannel ?? 'Paystack / Online Payment') ?></td>
                </tr>
                <tr class="total">
                    <th>Amount Paid</th>
                    <td>₦<?= esc(number_format((float)($amount ?? 0), 2)) ?> NGN</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="<?= esc($accessUrl ?? base_url('login')) ?>" class="btn-action">
                    <?= esc($buttonText ?? 'Access Your Dashboard') ?>
                </a>
            </div>

            <p style="font-size: 13px; color: #64748b;">If you have any questions or require assistance regarding this invoice, please reach out to our billing support at <a href="mailto:<?= esc($supportEmail ?? 'support@jobberrecruit.com') ?>"><?= esc($supportEmail ?? 'support@jobberrecruit.com') ?></a>.</p>

            <p style="margin-top: 25px;">Warm regards,<br><strong>The JobberRecruit Billing Team</strong></p>
        </div>
        <div class="footer">
            <p>JobberRecruit Nigeria Ltd &bull; Victoria Island, Lagos, Nigeria</p>
            <p>
                <a href="https://jobberrecruit.com">Website</a> |
                <a href="https://jobberrecruit.com/terms-and-conditions">Terms of Service</a> |
                <a href="https://jobberrecruit.com/privacy-policy">Privacy Policy</a>
            </p>
            <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
