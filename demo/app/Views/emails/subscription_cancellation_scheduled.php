<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Cancellation Confirmed</title>
    <style>
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
                <h2>Subscription Cancellation Confirmed</h2>

                <p>Dear <?= esc($fullname) ?>,</p>

                <p>We have received your request to cancel your JobberRecruit subscription.</p>

                <p>
                    <strong>Your access will remain active until the end of your current billing period on <?= esc($endDate) ?>.</strong><br>
                    After this date, your subscription will not renew, and access to premium features will end.
                </p>

                <p>
                    You can reactivate or change your plan anytime before <?= esc($endDate) ?> by visiting your
                    <a href="<?= base_url('employer/pricing') ?>">billing dashboard</a>.
                </p>

                <p>If this cancellation was made in error or you'd like to discuss other options, please reply to this email or contact support.</p>

                <p>Thank you for using JobberRecruit.</p>

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