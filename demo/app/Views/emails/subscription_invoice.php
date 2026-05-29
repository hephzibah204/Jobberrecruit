<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Invoice - JobberRecruit</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #005DA8 0%, #0a4a7a 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 10px 0 0 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 40px 30px;
        }

        .content h2 {
            color: #005DA8;
            margin-top: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .invoice-badge {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }

        .invoice-badge h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #005DA8;
            color: white;
            font-weight: 600;
            width: 40%;
        }

        td {
            background-color: white;
        }

        .total {
            font-size: 20px;
            font-weight: 700;
            color: #005DA8;
            background: #e7f3ff !important;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #005DA8, #0a4a7a);
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 25px 0;
            text-align: center;
        }

        .footer {
            background: #f8f9fa;
            color: #6c757d;
            padding: 30px;
            text-align: center;
            font-size: 13px;
            border-top: 1px solid #dee2e6;
        }

        .footer a {
            color: #005DA8;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }

        .footer p {
            margin: 8px 0;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="header">
                <img src="<?= esc($logoUrl) ?>" alt="JobberRecruit Logo">
                <h1>Payment Invoice</h1>
            </td>
        </tr>
        <tr>
            <td class="content">
                <h2>Thank You for Your Subscription!</h2>

                <p>Dear <?= esc($fullname) ?>,</p>

                <div class="invoice-badge">
                    <h3>✓ Payment Successful</h3>
                    <p style="margin: 0;">Your payment has been successfully processed and your subscription is now active.</p>
                </div>

                <p>Your <strong><?= esc($planName) ?></strong> subscription details are below:</p>

                <p>Below are the details of your transaction:</p>

                <table>
                    <tr>
                        <th>Invoice Date</th>
                        <td><?= esc($paidAt) ?></td>
                    </tr>
                    <tr>
                        <th>Transaction Reference</th>
                        <td><?= esc($reference) ?></td>
                    </tr>
                    <tr>
                        <th>Plan</th>
                        <td><?= esc($planName) ?> (Monthly)</td>
                    </tr>
                    <!-- Plan Details -->
                    <tr>
                        <th>Payment Method</th>
                        <td><?= esc($channel) ?></td>
                    </tr>
                    <tr>
                        <th>Amount Paid</th>
                        <td>₦<?= esc($amount) ?> NGN</td>
                    </tr>
                    <tr class="total">
                        <th>Total</th>
                        <td>₦<?= esc($amount) ?></td>
                    </tr>
                </table>

                <p>Your subscription will automatically renew monthly. You can manage or cancel it anytime from your employer dashboard.</p>

                <p>If you have any questions, feel free to contact us.</p>

                <p>Best regards,<br><strong>JobberRecruit Team</strong></p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p><?= esc($companyAddress) ?><br>
                    Phone: +234 901 480 8902 | Email: <a href="mailto:<?= esc($supportEmail) ?>"><?= esc($supportEmail) ?></a></p>
                <div>
                    <a href="https://www.instagram.com/jobberrecruit_ltd">Instagram</a> |
                    <a href="https://www.linkedin.com/company/jobber-recruit/">LinkedIn</a> |
                    <a href="https://www.tiktok.com/@jobberecruit">TikTok</a> |
                    <a href="https://x.com/jobberrecruit">X (Twitter)</a> |
                    <a href="https://t.me/jobberecruit">Telegram</a> |
                    <a href="https://wa.me/message/GZ266BV42CQUK1">WhatsApp</a>
                </div>
                <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>