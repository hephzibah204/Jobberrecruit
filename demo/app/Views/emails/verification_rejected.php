<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verification Update - JobberRecruit</title>
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
            background: linear-gradient(135deg, #ffc107, #ff9800);
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

        .info-badge {
            background: #fff3cd;
            color: #856404;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }

        .info-badge h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .rejection-reason {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }

        .rejection-reason h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
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

        .help-box {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0d6efd;
        }

        .help-box h4 {
            margin: 0 0 10px 0;
            color: #0d6efd;
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
            <h1>⚠ Verification Update</h1>
            <p>Action required on your employer verification</p>
        </div>

        <div class="content">
            <div class="info-badge">
                <h3>Verification Requires Attention</h3>
                <p style="margin: 0;">We've reviewed your verification submission and some issues need to be addressed.</p>
            </div>

            <p>Dear <?= esc($contact_name ?? 'Employer') ?>,</p>

            <p>Thank you for submitting your company verification documents. After careful review, we regret to inform you that your verification could not be approved at this time.</p>

            <div class="details">
                <table>
                    <tr>
                        <td>Company Name:</td>
                        <td><?= esc($company_name ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Review Date:</td>
                        <td><?= date('F d, Y', strtotime($review_date ?? 'now')) ?></td>
                    </tr>
                    <tr>
                        <td>Verification Status:</td>
                        <td><strong style="color: #dc3545;">✗ Not Approved</strong></td>
                    </tr>
                </table>
            </div>

            <?php if (!empty($rejection_reason)): ?>
            <div class="rejection-reason">
                <h4>Reason for Rejection:</h4>
                <p style="margin: 0;"><?= nl2br(esc($rejection_reason)) ?></p>
            </div>
            <?php endif; ?>

            <h3>What You Can Do:</h3>
            <ul>
                <li>Review the rejection reason provided above</li>
                <li>Upload corrected or additional documents</li>
                <li>Ensure all documents are clear and legible</li>
                <li>Resubmit your verification request</li>
            </ul>

            <div class="help-box">
                <h4>Need Help?</h4>
                <p style="margin: 0;">Our support team is here to assist you. Please contact us if you have questions about the verification requirements or need guidance on uploading the correct documents.</p>
            </div>

            <div style="text-align: center;">
                <a href="<?= base_url('employer/profile/upload-document') ?>" class="cta-button">Upload New Documents</a>
            </div>

            <p style="margin-top: 30px;">We're here to help you complete the verification process successfully. Don't hesitate to reach out if you need assistance.</p>

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
