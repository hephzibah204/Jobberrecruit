<?php
// Variables expected: $title, $content, $email
$siteName = 'JobberRecruit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - JobberRecruit</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0D609E; /* Brand Secondary */
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-width: 180px;
            height: auto;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content h1 {
            color: #0D609E;
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
            border-left: 4px solid #F3921D;
            padding-left: 10px;
        }
        .body-text {
            font-size: 15px;
            color: #334155;
            margin-bottom: 25px;
        }
        .button-wrapper {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #F3921D; /* Brand Primary */
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            box-shadow: 0 4px 6px rgba(245, 166, 35, 0.2);
            transition: all 0.2s ease-in-out;
        }
        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 35px 0;
        }
        .footer {
            background-color: #0D609E;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #ffffff;
            text-decoration: underline;
            margin: 0 4px;
        }
        .social-icons {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .social-icons a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 8px;
            font-weight: bold;
        }
        .unsubscribe-text {
            margin-top: 20px;
            font-size: 11px;
            opacity: 0.9;
        }
        .unsubscribe-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 20px;
            background-color: rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
        }
        .unsubscribe-btn:hover {
            background-color: rgba(255, 255, 255, 0.25);
            border-color: #ffffff;
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
                <h1><?= esc($title) ?></h1>
                
                <div class="body-text">
                    <?= $content ?>
                </div>
                
                <?= view('emails/signature') ?>

                <hr>

                <p class="body-text" style="font-size: 13px; color: #64748b;">
                    You received this email because you opted into our newsletter announcements. If you have any inquiries, feel free to drop us a line at <a href="mailto:support@jobberrecruit.com" style="color: #0D609E; text-decoration: none; font-weight: 500;">support@jobberrecruit.com</a>.
                </p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p class="fw-bold" style="font-size: 13px; margin-bottom: 8px">JobberRecruit Ltd</p>
                <p>6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria</p>
                <p>Phone: +234 901 480 8902 | Support: support@jobberrecruit.com</p>
                
                <div class="social-icons">
                    <a href="https://www.linkedin.com/company/jobber-recruit/">LinkedIn</a> |
                    <a href="https://www.instagram.com/jobberrecruit_ltd?igsh=YWFheGE0eDJ6NXh2">Instagram</a> |
                    <a href="https://x.com/jobberrecruit?s=21&t=-feIW_cwkJ1KudODM2mONQ">X (Twitter)</a> |
                    <a href="https://wa.me/message/GZ266BV42CQUK1">WhatsApp</a>
                </div>
                
                <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
                
                <div class="unsubscribe-text">
                    Want to change how you receive these emails? <br>
                    <a href="<?= base_url('newsletter/unsubscribe?email=' . urlencode($email ?? '')) ?>" class="unsubscribe-btn">Unsubscribe From List</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
