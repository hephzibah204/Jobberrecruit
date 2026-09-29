<?php
// Variables expected: $title, $content, $email
$siteName = 'JobberRecruit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - JobberRecruit Candidates</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0D609E 0%, #003a6a 100%); /* Brand Secondary gradient */
            padding: 40px 20px;
            text-align: center;
            border-bottom: 5px solid #F3921D; /* Brand Primary */
        }
        .header img {
            max-width: 190px;
            height: auto;
        }
        .candidate-tag {
            display: inline-block;
            background-color: #F3921D;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 12px;
            margin-top: 15px;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 35px;
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
        .tip-box {
            background-color: #fdf8f2;
            border-left: 4px solid #F3921D;
            padding: 20px;
            border-radius: 0 12px 12px 0;
            margin: 30px 0;
        }
        .tip-title {
            font-weight: bold;
            color: #b26a00;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .tip-text {
            font-size: 13.5px;
            color: #5c442c;
            margin: 0;
        }
        .features-grid {
            margin: 30px 0;
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px dashed #cbd5e1;
        }
        .feature-item {
            font-size: 14px;
            color: #475569;
            margin-bottom: 10px;
        }
        .feature-item strong {
            color: #0D609E;
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
                <span class="candidate-tag">Candidate Career Insights</span>
            </td>
        </tr>
        <tr>
            <td class="content">
                <h1><?= esc($title) ?></h1>
                
                <div class="body-text">
                    <?= $content ?>
                </div>

                <?= view('emails/signature') ?>

                <div class="tip-box">
                    <p class="tip-title">💡 Pro Career Tip</p>
                    <p class="tip-text">Keep your professional profile and resume fully updated. JobberRecruit recruiters search our database daily using advanced AI tools to match candidate profiles with top tier employers!</p>
                </div>

                <div class="features-grid">
                    <p class="mt-0 fw-bold" style="color: #0D609E; font-size: 15px">Explore JobberRecruit Tools:</p>
                    <div class="feature-item">🚀 <strong>AI Resume Builder:</strong> Craft a professional, ATS-optimized CV in minutes.</div>
                    <div class="feature-item">📅 <strong>Exclusive Webinars:</strong> Learn interview techniques directly from global HR experts.</div>
                    <div class="feature-item">⚡ <strong>Smart Job Alerts:</strong> Get the latest high-paying job matches right to your inbox.</div>
                </div>

                <div class="button-wrapper">
                    <a href="<?= base_url('candidate/resume/builder') ?>" class="button">Build Your ATS Resume Now</a>
                </div>
                
                <hr>

                <p class="body-text" style="font-size: 13px; color: #64748b; line-height: 1.5;">
                    This is an exclusive career growth publication sent to registered candidates at JobberRecruit. Need guidance or support? Reach our candidate success desk at <a href="mailto:support@jobberrecruit.com" class="fw-bold" style="color: #0D609E; text-decoration: none">support@jobberrecruit.com</a>.
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
                    Want to stop receiving candidate updates? <br>
                    <a href="<?= base_url('newsletter/unsubscribe?email=' . urlencode($email ?? '')) ?>" class="unsubscribe-btn">Unsubscribe From List</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
