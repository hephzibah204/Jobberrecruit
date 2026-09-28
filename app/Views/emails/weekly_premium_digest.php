<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Premium Job Digest - JobberRecruit</title>
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
            padding: 36px 30px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 14px;
        }
        .badge {
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
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
        }
        .content {
            padding: 32px 28px;
        }
        .intro-text {
            font-size: 14px;
            color: #475569;
            margin-bottom: 24px;
        }
        .job-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #ED9020;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
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
            margin-bottom: 8px;
        }
        .job-meta {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .job-meta span {
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .btn-view {
            display: inline-block;
            background: #0861A9;
            color: #ffffff !important;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn-view:hover {
            background: #064A85;
        }
        .featured-pill {
            float: right;
            background: #fdf1e0;
            color: #c8770e;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 12px;
        }
        .cta-box {
            text-align: center;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 24px 20px;
            margin-top: 28px;
        }
        .cta-box h3 {
            margin: 0 0 6px 0;
            font-size: 15px;
            color: #0A2F57;
        }
        .cta-box p {
            margin: 0 0 16px 0;
            font-size: 13px;
            color: #64748b;
        }
        .btn-all {
            display: inline-block;
            background: #ED9020;
            color: #0A2F57 !important;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
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
        .socials {
            margin: 12px 0;
        }
        .socials a {
            margin: 0 6px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?= esc($logoUrl ?? base_url('assets/imgs/template/logo-white.png')) ?>" alt="JobberRecruit Logo">
            <div><span class="badge">★ Curated Weekly Digest</span></div>
            <h1>Premium Jobs of the Week</h1>
            <p>Top handpicked and verified opportunities posted Monday–Friday</p>
        </div>

        <div class="content">
            <p class="intro-text">
                Hello <strong><?= esc($candidate_name ?? 'Candidate') ?></strong>,<br>
                Here is your curated weekly digest of the latest premium and verified job openings on JobberRecruit for the week of <strong><?= esc($week_label ?? date('F j, Y')) ?></strong>.
            </p>

            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <?php if (!empty($job->is_featured) || !empty($job->is_urgent)): ?>
                            <span class="featured-pill"><?= !empty($job->is_urgent) ? '⚡ Urgent' : '★ Featured' ?></span>
                        <?php endif; ?>

                        <h3 class="job-title"><?= esc($job->title) ?></h3>
                        <div class="job-company"><?= !empty($job->is_anonymous) ? 'Confidential Employer' : esc($job->company_name ?? ($job->employer_name ?? 'Verified Employer')) ?></div>
                        
                        <div class="job-meta">
                            <?php if (!empty($job->location) || !empty($job->state_name)): ?>
                                <span>📍 <?= esc($job->state_name ?? $job->location) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($job->job_type) || !empty($job->employment_type)): ?>
                                <span>💼 <?= esc(ucwords(str_replace('_', ' ', $job->job_type ?? $job->employment_type))) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($job->salary_details) && $job->salary_details !== 'Negotiable'): ?>
                                <span>₦ <?= esc($job->salary_details) ?></span>
                            <?php elseif (!empty($job->salary_min) || !empty($job->salary_max)): ?>
                                <span>₦ <?= number_format($job->salary_min ?? 0) ?> - <?= number_format($job->salary_max ?? 0) ?></span>
                            <?php elseif (!empty($job->salary)): ?>
                                <span>₦ <?= esc($job->salary) ?></span>
                            <?php endif; ?>
                        </div>

                        <a href="<?= base_url('jobs/' . ($job->slug ?? $job->id)) ?>" class="btn-view">
                            View Vacancy &amp; Apply →
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;color:#64748b;padding:20px;">No new premium jobs found for this period. Check all open positions below.</p>
            <?php endif; ?>

            <div class="cta-box">
                <h3>Looking for more job matches?</h3>
                <p>Explore hundreds of active job listings tailored to your skills and preferences.</p>
                <a href="<?= base_url('jobs') ?>" class="btn-all">Explore All Available Jobs →</a>
            </div>
        </div>

        <div class="footer">
            <p>
                You are receiving this email because you are a registered candidate on JobberRecruit.<br>
                To adjust your email preferences, visit your <a href="<?= base_url('candidate/settings') ?>">Notification Settings</a> or <a href="<?= base_url('candidate/job-alerts') ?>">Job Alerts</a>.
            </p>
            <div class="socials">
                <a href="https://www.linkedin.com/company/jobber-recruit/">LinkedIn</a> ·
                <a href="https://www.instagram.com/jobberrecruit_ltd">Instagram</a> ·
                <a href="https://x.com/jobberrecruit">Twitter / X</a>
            </div>
            <p>&copy; <?= date('Y') ?> JobberRecruit Limited. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
