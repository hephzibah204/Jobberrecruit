<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Job Approved - <?= $job_title ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .success-badge {
            background: #d4edda;
            color: #155724;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            margin: 15px 0;
            font-weight: bold;
        }

        .job-card {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            margin-right: 10px;
        }

        .button-secondary {
            background: #6c757d;
        }

        .button-outline {
            background: transparent;
            border: 2px solid #28a745;
            color: #28a745;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }

        .plan-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .plan-unlimited {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .plan-subscription {
            background: #0d6efd;
            color: white;
        }

        .plan-bundle {
            background: #ffc107;
            color: #856404;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✅ Job Approved!</h1>
            <p>Your job posting is now live</p>
        </div>

        <div class="content">
            <p>Dear <strong><?= htmlspecialchars($employer_name) ?></strong>,</p>

            <div class="success-badge">
                🎉 Congratulations! Your job has been approved
            </div>

            <div class="job-card">
                <strong>📋 Job Details:</strong><br>
                <strong>Title:</strong> <?= htmlspecialchars($job_title) ?><br>
                <strong>Posted on:</strong> <?= $job_created_at ?><br>
                <strong>Status:</strong> <span style="color:#28a745;">● Live & Active</span>
            </div>

            <p>Your job posting has been reviewed and approved by our team. It is now visible to all job seekers on our platform.</p>

            <center>
                <a href="<?= $job_url ?>" class="button">🔍 View Your Job</a>
                <a href="<?= $dashboard_url ?>" class="button button-secondary">📊 Go to Dashboard</a>
            </center>

            <hr>

            <!-- Account Summary Section -->
            <h3>📊 Your Account Summary</h3>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($total_jobs_posted) ?></div>
                    <div class="stat-label">Total Jobs Posted</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($approved_jobs) ?></div>
                    <div class="stat-label">Approved Jobs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($pending_jobs) ?></div>
                    <div class="stat-label">Pending Review</div>
                </div>
            </div>

            <?php if ($has_unlimited_access): ?>
                <div class="stat-card" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); margin-top: 10px;">
                    <div class="plan-badge plan-unlimited" style="display: inline-block; margin-bottom: 10px;">🌟 UNLIMITED ACCESS</div>
                    <p style="margin: 5px 0 0; font-weight: bold;">You have unlimited job postings on your current plan!</p>
                    <small>No credit deductions apply to your account.</small>
                </div>
            <?php else: ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?= number_format($credit_balance) ?></div>
                        <div class="stat-label">Available Credits</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= htmlspecialchars($current_plan) ?></div>
                        <div class="stat-label">Current Plan</div>
                    </div>
                    <?php if ($subscription_ends_at): ?>
                        <div class="stat-card">
                            <div class="stat-number"><?= date('M d, Y', strtotime($subscription_ends_at)) ?></div>
                            <div class="stat-label">Subscription Expires</div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($credit_balance <= 2 && $credit_balance > 0): ?>
                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 15px 0; border-radius: 5px;">
                        <strong>⚠️ Low Credits Warning!</strong><br>
                        You only have <strong><?= $credit_balance ?></strong> credit(s) remaining.
                        <a href="<?= $pricing_url ?>" style="color: #856404;">Purchase more credits</a> to continue posting jobs.
                    </div>
                <?php elseif ($credit_balance <= 0 && !$has_unlimited_access): ?>
                    <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 12px; margin: 15px 0; border-radius: 5px;">
                        <strong>⚠️ No Credits Available!</strong><br>
                        You have 0 job credits.
                        <a href="<?= $pricing_url ?>" style="color: #721c24;">Purchase a bundle</a> or
                        <a href="<?= $pricing_url ?>" style="color: #721c24;">subscribe to a plan</a> to post more jobs.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <hr>

            <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <strong>💡 Quick Tips:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li>Share your job on social media to reach more candidates</li>
                    <li>Check your dashboard regularly for new applications</li>
                    <li>Respond to candidates promptly for better engagement</li>
                    <li>Feature your job to get more visibility (available on premium plans)</li>
                </ul>
            </div>

            <center>
                <a href="<?= $jobs_url ?>" class="button button-outline">📋 View All Your Jobs</a>
                <a href="<?= $pricing_url ?>" class="button button-outline">💳 Manage Plan</a>
            </center>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($platform_name) ?>. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
            <p>Need help? <a href="<?= base_url('contact') ?>">Contact Support</a></p>
        </div>
    </div>
</body>

</html>