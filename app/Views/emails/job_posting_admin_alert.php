<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Alert: Job Posting Awaiting Approval - JobberRecruit</title>
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
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 30px 25px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }
        .content {
            padding: 35px 30px;
        }
        .alert-card {
            background: #fffbeb;
            color: #92400e;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table-info th {
            text-align: left;
            padding: 8px;
            color: #64748b;
            font-size: 13px;
            width: 35%;
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
            <h1>📋 Admin Alert: New Job Awaiting Approval</h1>
        </div>
        <div class="content">
            <p>Hello Admin,</p>
            <p>A new job posting has been submitted by an employer and is awaiting moderation approval before going live.</p>

            <div class="alert-card">
                <h4 style="margin:0 0 4px 0;">Job Submission Details</h4>
                <table class="table-info">
                    <tr>
                        <th>Job Title:</th>
                        <td><strong><?= esc($job_title ?? 'N/A') ?></strong></td>
                    </tr>
                    <tr>
                        <th>Company / Employer:</th>
                        <td><?= esc($company_name ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>Location:</th>
                        <td><?= esc($location ?? 'Nigeria') ?></td>
                    </tr>
                    <tr>
                        <th>Submitted At:</th>
                        <td><?= esc($submitted_at ?? date('F j, Y, g:i A')) ?></td>
                    </tr>
                </table>
            </div>

            <div style="text-align: center;">
                <a href="<?= base_url('admin/jobs?status=pending_approval') ?>" class="btn-action">Review Job in Admin Panel</a>
            </div>
        </div>
        <div class="footer">
            <p>JobberRecruit Administration System &bull; Confidential</p>
        </div>
    </div>
</body>
</html>
