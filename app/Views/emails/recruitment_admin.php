<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Recruitment Inquiry - JobberRecruit</title>
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
            background-color: #0D609E;
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
            color: #ED9020;
            margin-top: 0;
        }

        .content hr {
            border: none;
            border-top: 1px solid #eeeeee;
            margin: 20px 0;
        }

        .footer {
            background-color: #ED9020;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <table class="container" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td class="header">
                <img src="<?= base_url('images/logo-white.png') ?>" alt="JobberRecruit Logo">
            </td>
        </tr>
        <tr>
            <td class="content">
                <h2>New Recruitment Inquiry Received</h2>
                <p>A user has submitted a new done-for-you recruitment inquiry from the website.</p>
                <hr>
                <p><strong>Name:</strong> <?= esc($fullName) ?></p>
                <p><strong>Company Name:</strong> <?= esc($companyName) ?></p>
                <p><strong>Email:</strong> <?= esc($email) ?></p>
                <p><strong>Phone:</strong> <?= esc($phone) ?></p>
                <p><strong>Role to Hire:</strong> <?= esc($role) ?></p>
                <p><strong>Experience Level:</strong> <?= esc($experience) ?></p>
                <p><strong>Budget / Salary Range:</strong> <?= esc($budget ?: 'Not specified') ?></p>
                <p><strong>Working Schedule:</strong> <?= esc($schedule ?: 'Not specified') ?></p>
                <p><strong>Location:</strong> <?= esc($location ?: 'Not specified') ?></p>
                <hr>
                <p><strong>Message / Requirements:</strong></p>
                <p><?= nl2br(esc($message)) ?></p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                &copy; <?= date('Y') ?> JobberRecruit. All rights reserved.
            </td>
        </tr>
    </table>
</body>

</html>
