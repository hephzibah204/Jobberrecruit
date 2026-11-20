<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #0D609E, #0b5ed7); color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .button { display: inline-block; background: #0D609E; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Job Submission Received</h2>
        </div>
        <div class='content'>
            <p>Hello <strong><?= htmlspecialchars($employer->company_name) ?></strong>,</p>
            <p>This email is to confirm that we have successfully received your job posting for "<strong><?= htmlspecialchars($jobTitle) ?></strong>".</p>
            <p>Your job is currently under review/approval by our moderation team. We request your patience while the moderation process is completed.</p>
            <p>You will be notified once it's approved and published on our platform.</p>
            <p>
                <a href="<?= base_url("employer/jobs/view/{$jobId}") ?>" class='button'>View Job Details</a>
            </p>
            <p>Thank you for using Jobber Recruit!</p>
        </div>
        <div class='footer'>
            <p>&copy; <?= date('Y') ?> Jobber Recruit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
