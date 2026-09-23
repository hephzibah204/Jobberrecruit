<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($subject ?? 'Subscription Expiring Soon') ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 24px; }
        .brand { font-size: 24px; font-weight: 700; color: #0f172a; text-decoration: none; }
        .brand span { color: #2563eb; }
        .badge { display: inline-block; background-color: #fee2e2; color: #dc2626; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 13px; margin-bottom: 12px; }
        h1 { font-size: 22px; color: #1e293b; margin-top: 0; }
        p { font-size: 15px; line-height: 1.6; color: #475569; }
        .details-box { background-color: #f8fafc; border-left: 4px solid #ef4444; padding: 18px 20px; border-radius: 6px; margin: 20px 0; }
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
        
        <div class="badge">⚠️ SUBSCRIPTION WARNING</div>
        
        <h1>Your Subscription Expires in <?= esc($daysRemaining) ?> Day<?= $daysRemaining > 1 ? 's' : '' ?></h1>
        <p>Hi <strong><?= esc($userName) ?></strong>,</p>
        <p>This is a notification that your <strong><?= esc($planName) ?></strong> subscription on JobberRecruit is set to expire on <strong><?= esc($expirationDate) ?></strong>.</p>
        
        <div class="details-box">
            <p><strong>Active Plan:</strong> <?= esc($planName) ?></p>
            <p><strong>Expiration Date:</strong> <?= esc($expirationDate) ?></p>
            <p><strong>Days Remaining:</strong> <?= esc($daysRemaining) ?> Day<?= $daysRemaining > 1 ? 's' : '' ?></p>
        </div>

        <p>To avoid any disruption to your recruitment posting, candidate search, or premium features, please renew or upgrade your subscription plan today.</p>

        <div style="text-align: center; margin: 25px 0;">
            <a href="<?= base_url($renewUrl ?? 'employer/pricing') ?>" class="btn">Renew / Upgrade Plan Now</a>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> JobberRecruit Inc. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
