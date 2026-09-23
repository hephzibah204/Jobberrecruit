<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Completed Aptitude Test - JobberRecruit</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f9;
            color: #333333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #005DA8 0%, #003a6b 100%);
            padding: 35px 25px;
            text-align: center;
            color: #ffffff;
        }
        .header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }
        .content {
            padding: 35px 30px;
        }
        .score-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .score-number {
            font-size: 36px;
            font-weight: 800;
            color: #005DA8;
            margin: 10px 0;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            text-align: left;
        }
        .table-info th {
            padding: 8px;
            color: #64748b;
            font-size: 13px;
            width: 40%;
        }
        .table-info td {
            padding: 8px;
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
        }
        .btn-action {
            display: inline-block;
            background: #005DA8;
            color: #ffffff !important;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?= esc($logoUrl ?? base_url('images/logo.png')) ?>" alt="JobberRecruit Logo">
            <h1>Candidate Assessment Completed</h1>
        </div>
        <div class="content">
            <p>Dear <strong><?= esc($company_name ?? 'Employer') ?></strong> Recruitment Team,</p>
            <p>A candidate has just finished an aptitude assessment for your job opening.</p>

            <div class="score-box">
                <div style="font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Assessment Score</div>
                <div class="score-number"><?= esc($score_percentage ?? 0) ?>%</div>
                <div style="font-size: 14px; font-weight: 600; color: <?= !empty($passed) ? '#10b981' : '#ef4444' ?>;">
                    <?= !empty($passed) ? '✓ Met Passing Benchmark' : '⚠️ Below Passing Threshold' ?>
                </div>
            </div>

            <table class="table-info">
                <tr>
                    <th>Candidate Name:</th>
                    <td><strong><?= esc($candidate_name ?? 'Candidate') ?></strong></td>
                </tr>
                <tr>
                    <th>Job Position:</th>
                    <td><?= esc($job_title ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Assessment:</th>
                    <td><?= esc($test_title ?? 'Aptitude Test') ?></td>
                </tr>
                <tr>
                    <th>Completed At:</th>
                    <td><?= esc($completed_at ?? date('F j, Y, g:i A')) ?></td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="<?= esc($result_url ?? base_url('employer/candidates')) ?>" class="btn-action">View Candidate Profile &amp; Test Breakdown</a>
            </div>

            <p style="margin-top: 25px;">Best regards,<br><strong>The JobberRecruit Assessment Platform</strong></p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
