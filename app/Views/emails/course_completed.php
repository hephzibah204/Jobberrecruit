<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($subject ?? 'Course Completed') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        h1 { font-size: 24px; color: #1e293b; margin-top: 0; }
        p { font-size: 16px; line-height: 1.5; color: #475569; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #10b981; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #94a3b8; }
        .celebration { font-size: 40px; text-align: center; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?= esc(config('App')->appName ?? 'JobberRecruit') ?></h2>
        </div>
        <div class="celebration">🎓</div>
        <h1 style="text-align: center;">Congratulations, <?= esc($user_name) ?>!</h1>
        <p>You have successfully completed the course: <strong><?= esc($course_title) ?></strong>.</p>
        <p>Your dedication and hard work have paid off. Your official certificate of completion has been generated and is now available in your dashboard.</p>
        
        <div style="text-align: center;">
            <a href="<?= base_url('training/certificates') ?>" class="btn">View Your Certificate</a>
        </div>
        
        <p style="margin-top: 30px;">Keep up the great work and continue expanding your skills!</p>
        
        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= esc(config('App')->appName ?? 'JobberRecruit') ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
