<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Alert - New Opportunities - JobberRecruit</title>
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

        .job-item {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #eeeeee;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .job-item h4 {
            margin: 0 0 10px 0;
            color: #005DA8;
            font-size: 18px;
        }

        .job-item p {
            margin: 5px 0;
            color: #555555;
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
            margin-top: 10px;
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

        .tracking-pixel {
            display: none;
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
                <h2>Hello <?= esc($candidate->full_name) ?>,</h2>

                <p>We found new job opportunities that match your alert preferences:</p>

                <?php foreach ($jobs as $job): ?>
                    <div class="job-item">
                        <h4><?= esc($job->title) ?></h4>
                        <p>
                            <?= esc($job->company_name) ?><br>
                            <?= esc($job->location) ?>
                        </p>
                        <a href="<?= site_url('track/click/' . $alert->id . '/' . $job->id) ?>" class="button">
                            View Job
                        </a>
                    </div>
                <?php endforeach; ?>

                <!-- Open tracking pixel (hidden) -->
                <img src="<?= site_url('track/open/' . $alert->id) ?>" width="1" height="1" class="tracking-pixel" alt="">

                <p style="margin-top:30px;">
                    You can manage your alerts from your dashboard.
                </p>
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