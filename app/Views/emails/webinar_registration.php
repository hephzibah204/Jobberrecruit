<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($subject ?? 'Webinar Registration Confirmed') ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 24px; }
        .brand { font-size: 24px; font-weight: 700; color: #0f172a; text-decoration: none; }
        .brand span { color: #2563eb; }
        h1 { font-size: 22px; color: #1e293b; margin-top: 0; }
        p { font-size: 15px; line-height: 1.6; color: #475569; }
        .details-box { background-color: #f8fafc; border-left: 4px solid #2563eb; padding: 18px 20px; border-radius: 6px; margin: 20px 0; }
        .details-box p { margin: 6px 0; font-size: 14px; }
        .details-box strong { color: #0f172a; }
        .btn { display: inline-block; padding: 14px 28px; background-color: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; margin-top: 15px; text-align: center; }
        .btn:hover { background-color: #1d4ed8; }
        .footer { margin-top: 36px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="background-color: #0D609E; padding: 25px; border-radius: 8px 8px 0 0; text-align: center;">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url('assets/imgs/template/logo-white.png') ?>" alt="JobberRecruit Logo" width="180" style="max-width: 180px; height: auto; display: block; margin: 0 auto; border: 0;">
            </a>
        </div>
        
        <h1>🎉 You're Registered for the Webinar!</h1>
        <p>Hi <strong><?= esc($userName) ?></strong>,</p>
        <p>Your registration for our upcoming webinar has been successfully confirmed. Below are your event details:</p>
        
        <div class="details-box">
            <p><strong>Webinar Title:</strong> <?= esc($webinarTitle) ?></p>
            <?php if (!empty($presenter)): ?>
                <p><strong>Speaker / Host:</strong> <?= esc($presenter) ?></p>
            <?php endif; ?>
            <p><strong>Date & Time:</strong> <?= esc($scheduledAt) ?></p>
            <p><strong>Access Type:</strong> <?= esc(ucfirst($accessType ?? 'free')) ?></p>
        </div>

        <p>Make sure to mark your calendar so you don't miss out on important insights and live Q&A!</p>

        <div style="text-align: center; margin: 25px 0;">
            <a href="<?= esc($joinUrl) ?>" class="btn">View Webinar & Join Room</a>
        </div>

        <p>We look forward to seeing you live at the session!</p>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> JobberRecruit Inc. All rights reserved.</p>
            <p>If you didn't register for this event, please disregard this message.</p>
        </div>
    </div>
</body>
</html>
