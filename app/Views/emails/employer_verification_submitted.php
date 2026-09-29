<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Documents Received - JobberRecruit</title>
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
        }
        .content {
            padding: 35px 30px;
        }
        .info-box {
            background: #eff6ff;
            color: #1e40af;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
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
        }
        .footer {
            background: #f8fafc;
            padding: 25px 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?= esc($logoUrl ?? base_url('images/logo.png')) ?>" alt="JobberRecruit Logo">
            <h1>Verification Documents Received</h1>
        </div>
        <div class="content">
            <p>Dear <strong><?= esc($company_name ?? 'Employer') ?></strong> Team,</p>
            <p>Thank you for submitting your official business verification documents on JobberRecruit.</p>

            <div class="info-box">
                <h4 style="margin:0 0 6px 0;">⏳ Verification Under Review</h4>
                <p style="margin:0; font-size:14px;">Our trust & safety compliance team is currently reviewing your documentation. The verification process typically takes between <strong>24 to 48 business hours</strong>.</p>
            </div>

            <p>Once approved, your company will receive:</p>
            <ul>
                <li><strong>Verified Employer Trust Badge</strong> on all your job postings and company profile</li>
                <li><strong>Priority candidate matching</strong> and increased applicant trust</li>
                <li><strong>Access to advanced recruitment features</strong></li>
            </ul>

            <div style="text-align: center;">
                <a href="<?= base_url('employer/dashboard') ?>" class="btn-action">View Employer Dashboard</a>
            </div>

            <p style="margin-top: 25px;">Best regards,<br><strong>The JobberRecruit Compliance Team</strong></p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
