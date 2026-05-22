<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verification Approved - JobberRecruit</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .success-badge {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .success-badge h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .details td:first-child {
            font-weight: 600;
            color: #495057;
            width: 40%;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            border-top: 1px solid #dee2e6;
        }

        .footer a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✓ Verification Approved</h1>
            <p>Your employer account has been successfully verified</p>
        </div>

        <div class="content">
            <div class="success-badge">
                <h3>Congratulations, <?= esc($company_name ?? 'Employer') ?>!</h3>
                <p style="margin: 0;">Your company verification has been approved. You now have a verified badge on your profile, which increases trust with job seekers.</p>
            </div>

            <p>Dear <?= esc($contact_name ?? 'Employer') ?>,</p>

            <p>We're pleased to inform you that your employer account verification has been <strong>successfully approved</strong> by our team.</p>

            <div class="details">
                <table>
                    <tr>
                        <td>Company Name:</td>
                        <td><?= esc($company_name ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Verification Date:</td>
                        <td><?= date('F d, Y', strtotime($verification_date ?? 'now')) ?></td>
                    </tr>
                    <tr>
                        <td>Verification Status:</td>
                        <td><strong style="color: #28a745;">✓ Verified</strong></td>
                    </tr>
                </table>
            </div>

            <h3>What This Means:</h3>
            <ul>
                <li>✓ Your company profile now displays a <strong>verified badge</strong></li>
                <li>✓ Job seekers can trust your company is legitimate</li>
                <li>✓ Your job postings receive higher visibility</li>
                <li>✓ Access to premium employer features</li>
            </ul>

            <div style="text-align: center;">
                <a href="<?= base_url('employer/profile') ?>" class="cta-button">View Your Verified Profile</a>
            </div>

            <p style="margin-top: 30px;">If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

            <p>Best regards,<br>
            <strong>The JobberRecruit Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
            <p>
                <a href="<?= base_url('contact-us') ?>">Contact Support</a> |
                <a href="<?= base_url('faq') ?>">FAQ</a> |
                <a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>

</html>
