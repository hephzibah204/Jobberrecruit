<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Invoice - JobberRecruit</title>
    <style>
        /* Same styles as your previous emails */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #0D609E;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        .content {
            padding: 30px 20px;
        }

        .content h2 {
            color: #F0890E;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eeeeee;
        }

        th {
            background-color: #f9f9f9;
            width: 30%;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            color: #F0890E;
        }

        .footer {
            background-color: #0D609E;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 8px;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="header">
                <img src="<?= esc($logoUrl) ?>" alt="JobberRecruit Logo">
            </td>
        </tr>
        <tr>
            <td class="content">
                <h2>Thank You for Your Subscription! 🎉</h2>

                <p>Dear <?= esc($fullname) ?>,</p>

                <p>Your payment has been successfully processed and your <strong><?= esc($planName) ?></strong> subscription is now active.</p>

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