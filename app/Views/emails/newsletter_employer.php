<?php
// Variables expected: $title, $content, $email
$siteName = 'JobberRecruit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - JobberRecruit Employers</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0f172a; /* Sophisticated deep slate for premium business background */
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0D609E 0%, #002340 100%); /* Premium B2B gradient */
            padding: 40px 20px;
            text-align: center;
            border-bottom: 5px solid #F3921D; /* Brand Primary */
        }
        .header img {
            max-width: 190px;
            height: auto;
        }
        .employer-tag {
            display: inline-block;
            background-color: #F3921D;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 12px;
            margin-top: 15px;
            letter-spacing: 1.5px;
        }
        .content {
            padding: 45px 35px;
            line-height: 1.7;
        }
        .content h1 {
            color: #0D609E;
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        .body-text {
            font-size: 15px;
            color: #334155;
            margin-bottom: 25px;
        }
        .highlight-box {
            background-color: #f0f7ff;
            border-left: 4px solid #0D609E;
            padding: 20px;
            border-radius: 0 12px 12px 0;
            margin: 30px 0;
        }
        .highlight-title {
            font-weight: bold;
            color: #0D609E;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .highlight-text {
            font-size: 13.5px;
            color: #1e293b;
            margin: 0;
        }
        .pricing-callout {
            background: linear-gradient(135deg, #fef8f0 0%, #fffcf8 100%);
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        .pricing-tag {
            color: #ea580c;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .pricing-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0 15px 0;
        }
        .button-wrapper {
            text-align: center;
            margin: 35px 0 15px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background-color: #F3921D; /* Brand Primary */
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            box-shadow: 0 5px 15px rgba(245, 166, 35, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 35px 0;
        }
        .footer {
            background-color: #0D609E;
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
            font-size: 12px;
            line-height: 1.6;
        }
        .footer p {
            margin: 6px 0;
        }
        .footer a {
            color: #ffffff;
            text-decoration: underline;
        }
        .social-icons {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .social-icons a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
            font-size: 13px;
        }
        .unsubscribe-text {
            margin-top: 25px;
            font-size: 11px;
            opacity: 0.9;
        }
        .unsubscribe-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 8px 22px;
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
                <br>
                <span class="employer-tag">Employer & Hiring Partner Desk</span>
            </td>
        </tr>
        <tr>
            <td class="content">
                <h1><?= esc($title) ?></h1>
                
                <div class="body-text">
                    <?= $content ?>
                </div>

                <?= view('emails/signature') ?>

                <div class="highlight-box">
                    <p class="highlight-title">🎯 Premium Talent Acquisition</p>
                    <p class="highlight-text">Unlock access to our pool of verified candidates with verified ATS-standard CVs. Reduce your time-to-hire by over 50% by leveraging JobberRecruit's smart matching and candidate shortlisting engine!</p>
                </div>

                <div class="pricing-callout">
                    <span class="pricing-tag">Featured Offer</span>
                    <p class="pricing-title">Scale Your Hiring Team Effortlessly</p>
                    <p style="font-size: 14px; color: #475569; margin: 0 0 20px 0; line-height: 1.5;">Post unlimited job openings, unlock advanced candidate CV database search, and access pre-screened applications tailored for your open positions.</p>
                    <a href="<?= base_url('employers/bundles') ?>" style="display: inline-block; padding: 10px 25px; background-color: #0D609E; color: #ffffff !important; font-weight: bold; font-size: 13px; text-decoration: none; border-radius: 6px;">View Bundles & Pricing</a>
                </div>

                <div class="button-wrapper">
                    <a href="<?= base_url('employers/dashboard') ?>" class="button">Access Recruitment Dashboard</a>
                </div>
                
                <hr>

                <p class="body-text" style="font-size: 13px; color: #64748b; line-height: 1.5;">
                    This is an exclusive publication sent to registered employer partners at JobberRecruit. For corporate support or customized enterprise hiring packages, contact our partner success team at <a href="mailto:support@jobberrecruit.com" class="fw-bold" style="color: #0D609E; text-decoration: none">support@jobberrecruit.com</a>.
                </p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p class="fw-bold" style="font-size: 14px; margin-bottom: 8px">JobberRecruit Ltd</p>
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
                    Want to stop receiving employer newsletters? <br>
                    <a href="<?= base_url('newsletter/unsubscribe?email=' . urlencode($email ?? '')) ?>" class="unsubscribe-btn">Unsubscribe From List</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
