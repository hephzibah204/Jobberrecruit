<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($subject ?? 'CV Review Request Received') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        h1 { font-size: 24px; color: #1e293b; margin-top: 0; }
        p { font-size: 16px; line-height: 1.5; color: #475569; }
        .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?= esc(config('App')->appName ?? 'JobberRecruit') ?></h2>
        </div>
        <h1>We've Received Your CV!</h1>
        <p>Hi <?= esc($user_name) ?>,</p>
        <p>This email is to confirm that we have successfully received your CV for review.</p>
        <p>Our expert team (along with our AI analysis tools) will thoroughly review your document to provide actionable feedback on how to improve your chances of landing your dream job.</p>
        <p>You will receive another email as soon as your comprehensive CV review is complete and ready to be downloaded.</p>
        
        <p style="margin-top: 30px;">Thank you for trusting us with your career development.</p>
        
        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= esc(config('App')->appName ?? 'JobberRecruit') ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
