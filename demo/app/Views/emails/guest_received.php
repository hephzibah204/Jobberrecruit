<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Application Received</title>
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
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .button {
            display: inline-block;
            background: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }

        .button:hover {
            background: #218838;
        }

        .alert-info {
            background: #e7f3ff;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #17a2b8;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Application Received!</h2>
        </div>

        <p>Dear <strong><?= $name ?></strong>,</p>

        <p>Thank you for applying for the position of <strong><?= $jobTitle ?></strong> at <strong><?= $companyName ?></strong>.</p>

        <p>Your application has been received and is currently under review. You will receive updates about your application status via email.</p>

        <div class="alert-info">
            <strong>📝 Note:</strong> You applied as a guest. To track all your applications, get faster updates, and manage your job search,
            <a href="<?= site_url('register') ?>" style="color: #0d6efd;">create a free account</a> with the same email address.
        </div>

        <p>
            <a href="<?= site_url('register') ?>" class="button">Create Free Account</a>
        </p>

        <p>Best of luck with your application!</p>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= $companyName ?>. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>