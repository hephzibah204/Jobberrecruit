<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobberRecruit - Thank You for Contacting Us</title>
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
            /* Secondary Color */
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

        .footer {
            background-color: #F0890E;
            /* Primary Color */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 10px;
        }

        .social-icons {
            margin-top: 10px;
        }

        .social-icons a {
            margin: 0 5px;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td class="header">
                <img src="<?= base_url('images/logo-white.png') ?>" alt="JobberRecruit Logo">
            </td>
        </tr>
        <tr>
            <td class="content">
                <p>Hello <?= esc($name) ?>,</p>

                <p>
                    Thank you for contacting <strong>JobberRecruit</strong>.
                    We have received your message and our team will respond within 24 hours.
                </p>

                <p>
                    If your inquiry is urgent, please reply directly to this email.
                </p>

                <p>
                    Regards,<br>
                    JobberRecruit Support Team
                </p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p>
                    6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria<br>
                    Phone: +234 901 480 8902 | Email: <a href="mailto:support@jobberrecruit.com">support@jobberrecruit.com</a>
                </p>
                <div class="social-icons">
                    <a href="https://www.instagram.com/jobberrecruit_ltd?igsh=YWFheGE0eDJ6NXh2">Instagram</a> |
                    <a href="https://www.linkedin.com/company/jobber-recruit/">LinkedIn</a> |
                    <a href="https://www.tiktok.com/@jobberecruit">TikTok</a> |
                    <a href="https://x.com/jobberrecruit?s=21&t=-feIW_cwkJ1KudODM2mONQ">X (Twitter)</a> |
                    <a href="https://t.me/jobberecruit">Telegram</a> |
                    <a href="https://wa.me/message/GZ266BV42CQUK1">WhatsApp</a>
                </div>
                <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>