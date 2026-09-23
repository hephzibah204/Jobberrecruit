<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($subject ?? 'Welcome to your Course') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { max-width: 150px; }
        h1 { font-size: 24px; color: #1e293b; margin-top: 0; }
        p { font-size: 16px; line-height: 1.5; color: #475569; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #3b82f6; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?= esc(config('App')->appName ?? 'JobberRecruit') ?></h2>
        </div>
        <h1>Welcome to <?= esc($course_title) ?>!</h1>
        <p>Hi <?= esc($user_name) ?>,</p>
        <p>Congratulations on enrolling in <strong><?= esc($course_title) ?></strong>. We are thrilled to have you join this course.</p>
        <p>You can start learning immediately by logging into your dashboard and accessing your course materials.</p>
        
        <div style="text-align: center;">
            <a href="<?= base_url('training/classroom/' . $course_id) ?>" class="btn">Start Learning Now</a>
        </div>
        
        <p style="margin-top: 30px;">If you have any questions, feel free to reach out to our support team.</p>
        
        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= esc(config('App')->appName ?? 'JobberRecruit') ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
