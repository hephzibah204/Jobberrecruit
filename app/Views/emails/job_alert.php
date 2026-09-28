<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Match - JobberRecruit</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #1e293b;
            line-height: 1.6;
        }

        .container {
            max-width: 620px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(10, 47, 87, 0.08);
            border: 1px solid #e2e8f0;
        }

        .header {
            background: linear-gradient(145deg, #0A2F57 0%, #064A85 60%, #0861A9 100%);
            padding: 32px 28px;
            text-align: center;
            color: #ffffff;
        }

        .header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 12px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .badge-alert {
            display: inline-block;
            background: rgba(237, 144, 32, 0.2);
            border: 1px solid #ED9020;
            color: #FDF1E0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .content {
            padding: 30px 24px;
        }

        .greeting {
            font-size: 15px;
            color: #334155;
            margin-bottom: 20px;
        }

        .job-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0861A9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .job-card.is-featured {
            border-left-color: #ED9020;
            background: #fffcf8;
        }

        .job-title {
            margin: 0 0 6px 0;
            font-size: 17px;
            font-weight: 700;
            color: #0A2F57;
        }

        .job-company {
            font-size: 14px;
            font-weight: 600;
            color: #0861A9;
            margin-bottom: 10px;
        }

        .job-meta {
            display: flex;
            gap: 8px;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .job-meta span {
            background: #f1f5f9;
            padding: 4px 9px;
            border-radius: 5px;
            display: inline-block;
        }

        .pill-badge {
            float: right;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 12px;
        }

        .pill-urgent {
            background: #fee2e2;
            color: #dc2626;
        }

        .pill-featured {
            background: #fdf1e0;
            color: #c8770e;
        }

        .btn-apply {
            display: inline-block;
            background: #0861A9;
            color: #ffffff !important;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn-apply:hover {
            background: #064A85;
        }

        .cta-footer-box {
            text-align: center;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 20px;
            margin-top: 26px;
        }

        .cta-footer-box p {
            margin: 0 0 12px 0;
            font-size: 13px;
            color: #64748b;
        }

        .btn-secondary {
            display: inline-block;
            background: #ED9020;
            color: #0A2F57 !important;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .footer {
            background: #f8fafc;
            padding: 24px 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        .footer a {
            color: #0861A9;
            text-decoration: none;
        }

        .social-icons {
            margin: 12px 0;
        }

        .social-icons a {
            margin: 0 6px;
            color: #64748b;
            text-decoration: none;
        }

        .tracking-pixel {
            display: none;
            width: 1px;
            height: 1px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= esc(base_url('assets/imgs/template/logo-white.png')) ?>" alt="JobberRecruit Logo">
            <div><span class="badge-alert">🔔 Instant Job Alert</span></div>
            <h1>New Opportunity Matching Your Criteria</h1>
        </div>

        <div class="content">
            <p class="greeting">
                Hello <strong><?= esc($candidate->full_name ?? 'Candidate') ?></strong>,<br>
                A new vacancy matching your saved alert 
                <?php if (!empty($alert->keyword)): ?>
                    criteria (<strong><?= esc($alert->keyword) ?></strong>)
                <?php endif; ?>
                was just posted on JobberRecruit.
            </p>

            <?php foreach ($jobs as $job): ?>
                <?php
                    $isFeatured = !empty($job->is_featured);
                    $isUrgent   = !empty($job->is_urgent);
                    $jobUrl     = !empty($alert->id)
                        ? site_url('track/click/' . $alert->id . '/' . $job->id)
                        : base_url('jobs/' . ($job->slug ?? $job->id));
                ?>
                <div class="job-card <?= $isFeatured ? 'is-featured' : '' ?>">
                    <?php if ($isUrgent): ?>
                        <span class="pill-badge pill-urgent">⚡ Urgent</span>
                    <?php elseif ($isFeatured): ?>
                        <span class="pill-badge pill-featured">★ Featured</span>
                    <?php endif; ?>

                    <h3 class="job-title"><?= esc($job->title) ?></h3>
                    <div class="job-company">
                        <?= !empty($job->is_anonymous) ? 'Confidential Employer' : esc($job->company_name ?? 'Verified Employer') ?>
                    </div>

                    <div class="job-meta">
                        <?php if (!empty($job->state_name) || !empty($job->location)): ?>
                            <span>📍 <?= esc($job->state_name ?? $job->location) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($job->job_type)): ?>
                            <span>💼 <?= esc(ucwords(str_replace('_', ' ', $job->job_type))) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($job->salary_details) && $job->salary_details !== 'Negotiable'): ?>
                            <span>₦ <?= esc($job->salary_details) ?></span>
                        <?php elseif (!empty($job->salary_min) || !empty($job->salary_max)): ?>
                            <span>₦ <?= number_format($job->salary_min ?? 0) ?> - <?= number_format($job->salary_max ?? 0) ?></span>
                        <?php elseif (!empty($job->salary)): ?>
                            <span>₦ <?= esc($job->salary) ?></span>
                        <?php endif; ?>
                    </div>

                    <a href="<?= esc($jobUrl) ?>" class="btn-apply">
                        View Vacancy &amp; Apply →
                    </a>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($alert->id)): ?>
                <!-- Open tracking pixel -->
                <img src="<?= site_url('track/open/' . $alert->id) ?>" width="1" height="1" class="tracking-pixel" alt="">
            <?php endif; ?>

            <div class="cta-footer-box">
                <p>Looking for more career matches? Browse hundreds of active listings across Nigeria.</p>
                <a href="<?= base_url('jobs') ?>" class="btn-secondary">Explore All Active Jobs →</a>
            </div>
        </div>

        <div class="footer">
            <p>
                You are receiving this email because you created an active job alert on JobberRecruit.<br>
                You can pause, snooze, or customize your alerts anytime from your
                <a href="<?= base_url('candidate/notifications') ?>">Job Alerts</a> or
                <a href="<?= base_url('candidate/settings') ?>">Notification Preferences</a>.
            </p>
            <div class="social-icons">
                <a href="https://www.linkedin.com/company/jobber-recruit/">LinkedIn</a> ·
                <a href="https://www.instagram.com/jobberrecruit_ltd">Instagram</a> ·
                <a href="https://x.com/jobberrecruit">Twitter / X</a> ·
                <a href="https://wa.me/message/GZ266BV42CQUK1">WhatsApp</a>
            </div>
            <p>6 Ojulari Rd, Lekki Peninsula II, 106104, Lagos, Nigeria<br>&copy; <?= date('Y') ?> JobberRecruit Limited. All rights reserved.</p>
        </div>
    </div>
</body>

</html>