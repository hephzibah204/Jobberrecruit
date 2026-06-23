<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Application - JobberRecruit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #005DA8;
            /* Secondary Color */
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        .content {
            padding: 30px 20px;
        }

        .content h2 {
            color: #F5A623;
            /* Primary Color */
            margin-top: 0;
        }

        .content ul {
            padding-left: 20px;
        }

        .content li {
            margin-bottom: 8px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #F5A623;
            /* Primary Color */
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            background-color: #005DA8;
            /* Secondary Color */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 8px;
        }

        .social-icons {
            margin-top: 10px;
        }

        hr {
            border: none;
            border-top: 1px solid #eeeeee;
            margin: 30px 0 15px;
        }

        small {
            color: #999999;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="header">
                <img src="<?= base_url('images/logo-white.png') ?>" alt="JobberRecruit Logo">
            </td>
        </tr>
        <tr>
            <td class="content">
                <h2>New Job Application Received</h2>

                <p>Hello <strong><?= esc($employer_name) ?></strong>,</p>

                <p>
                    A new candidate has applied for the position of
                    <strong><?= esc($job_title) ?></strong>.
                </p>

                <p><strong>Candidate Details:</strong></p>
                <ul>
                    <li>Name: <?= esc($candidate_name) ?></li>
                    <li>Email: <?= esc($candidate_email) ?></li>
                    <li>Phone: <?= esc($candidate_phone) ?></li>
                </ul>

                <p><strong>Application Summary:</strong></p>
                <ul>
                    <li>Availability: <?= esc($availability ?? 'N/A') ?></li>
                    <li>Salary Expectation: <?= esc($salary_expectation ?? 'N/A') ?></li>
                    <li>Status: Pending Review</li>
                    <li>Date Applied: <?= esc($applied_at) ?></li>
                </ul>

                <p>
                    You can review this application from your employer dashboard.
                </p>

                <a href="<?= esc($dashboard_url) ?>" class="button">
                    View Application
                </a>

                <p style="margin-top:30px;">
                    Regards,<br>
                    <strong>JobberRecruit Team</strong>
                </p>

                <hr>
                <small>This is an automated notification.</small>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p>
                    6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria<br>
                    Phone: +234 901 480 8902 | Email: <a href="mailto:support@jobberrecruit.com">support@jobberrecruit.com</a>
                </p>
                <div class="social-icons">
                    <a href="https://www.instagram.com/jobberrecruit_ltd?igsh=YWFheGE0eDJ6NXh2">Instagram</a> |
                    <a href="https://www.linkedin.com/company/jobber-recruit/">LinkedIn</a> |
                    <a href="https://www.tiktok.com/@jobberecruit">TikTok</a> |
                    <a href="https://x.com/jobberrecruit?s=21&t=-feIW_cwkJ1KudODM2mONQ">X (Twitter)</a> |
                    <a href="https://t.me/jobberecruit">Telegram</a> |
                    <a href="https://wa.me/message/GZ266BV42CQUK1">WhatsApp</a>
                </div>
                <p>&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>