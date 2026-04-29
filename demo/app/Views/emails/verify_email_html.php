<?php
// Variables expected: $fullname, $verifyUrl
$siteName = 'JobberRecruit';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - JobberRecruit</title>
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

        .content h2 {
            color: #F0890E;
            /* Primary Color */
            margin-top: 0;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #F0890E;
            /* Primary Color */
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }

        .muted {
            color: #666666;
            font-size: 14px;
            line-height: 1.5;
        }

        .muted a {
            color: #0D609E;
            text-decoration: underline;
        }

        hr {
            border: none;
            border-top: 1px solid #eeeeee;
            margin: 30px 0;
        }

        .footer {
            background-color: #0D609E;
            /* Secondary Color */
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

        .social-icons {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="header">
                <img src="<?= base_url('images/logo-white.png') ?>" alt="JobberRecruit Logo">
            </td>
        </tr>
        <tr>
            <td class="content">
                <h2>Verify Your Email</h2>

                <p>Hi <?= esc($fullname ?: 'there') ?>,</p>

                <p>
                    Thanks for creating an account with <strong>JobberRecruit</strong>.
                    To complete your registration, please confirm your email address by clicking the button below:
                </p>

                <div style="text-align: center;">
                    <a href="<?= esc($verifyUrl) ?>" class="button" target="_blank" rel="noopener">
                        Verify My Email
                    </a>
                </div>

                <p class="muted">
                    If the button doesn't work, you can copy and paste this link into your browser:<br>
                    <a href="<?= esc($verifyUrl) ?>"><?= esc($verifyUrl) ?></a>
                </p>

                <hr>

                <p class="muted">
                    If you didn't create an account with JobberRecruit, you can safely ignore this email.
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