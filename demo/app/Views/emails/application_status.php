<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Application Status Update</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .badge {
            padding: 8px 15px;
            color: #ffffff;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            margin: 10px 0;
        }

        .message-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #0d6efd;
        }

        .button {
            display: inline-block;
            background: #0d6efd;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }

        .button:hover {
            background: #0b5ed7;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }

        .alert-info {
            background: #e7f3ff;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #17a2b8;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Application Status Update</h2>
        </div>

        <p>Dear <strong><?= $name ?></strong>,</p>

        <div class="badge" style="background: <?= $statusColor ?>">
            <?= $statusLabel ?>
        </div>

        <div class="message-box">
            <strong>Message from employer:</strong><br>
            <?= $message ?>
        </div>

        <?php if (!$isGuest): ?>
            <p style="margin-top: 20px;">
                <a href="<?= site_url('candidate/dashboard') ?>" class="button">View Dashboard</a>
            </p>
            <p>You can track all your applications and status updates from your dashboard.</p>
        <?php else: ?>
            <div class="alert-info">
                <strong>📝 Guest Applicant Note:</strong><br>
                You applied as a guest. To track all your applications, view history, and get faster updates,
                <a href="<?= site_url('register') ?>" style="color: #0d6efd;">create a free account</a> with the same email address.
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= $companyName ?>. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>