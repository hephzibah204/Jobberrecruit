<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Job Update - <?= $job_title ?></title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 30px;
        }

        .rejection-card {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            color: #721c24;
        }

        .button {
            display: inline-block;
            background: #0d6efd;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Job Update</h1>
            <p>Important information about your job posting</p>
        </div>

        <div class="content">
            <p>Dear <strong><?= htmlspecialchars($employer_name) ?></strong>,</p>

            <p>We have reviewed your job posting for <strong><?= htmlspecialchars($job_title) ?></strong>.</p>

            <div class="rejection-card">
                <strong>📝 Reason for not being approved:</strong><br>
                <?= nl2br(htmlspecialchars($reason)) ?>
            </div>

            <p>Please review the feedback above and make the necessary changes to your job posting. You can edit and resubmit your job for approval.</p>

            <center>
                <a href="<?= base_url('employer/jobs') ?>" class="button">✏️ Edit Your Job</a>
            </center>

            <p style="margin-top: 20px;">If you need assistance or believe this was a mistake, please contact our support team.</p>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($platform_name) ?>. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>