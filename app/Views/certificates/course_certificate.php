<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background: #f5f5f5;
        }
        .certificate {
            width: 100%;
            min-height: 595px;
            padding: 40px;
            background: #ffffff;
            border: 20px solid #1a73e8;
            box-sizing: border-box;
            position: relative;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 2px solid #1a73e8;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 36px;
            color: #1a73e8;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
        .header h2 {
            font-size: 22px;
            color: #333;
            margin: 10px 0 0 0;
            font-weight: normal;
        }
        .recipient {
            text-align: center;
            margin: 30px 0;
        }
        .recipient p {
            font-size: 16px;
            color: #666;
            margin: 0 0 10px 0;
        }
        .recipient .name {
            font-size: 32px;
            color: #1a73e8;
            font-weight: bold;
            margin: 10px 0;
            border-bottom: 2px solid #1a73e8;
            display: inline-block;
            padding-bottom: 5px;
        }
        .course-info {
            text-align: center;
            margin: 25px 0;
        }
        .course-info p {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }
        .course-info .course-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a73e8;
        }
        .footer {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        .footer-cell {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
        }
        .footer-cell .line {
            border-top: 1px solid #333;
            margin: 30px 20px 5px 20px;
        }
        .footer-cell p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }
        .certificate-code {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <h1>Certificate of Completion</h1>
            <h2>JobberRecruit E-Learning Platform</h2>
        </div>

        <div class="recipient">
            <p>This is to certify that</p>
            <div class="name"><?= esc($user->full_name ?? $user->username ?? 'Participant') ?></div>
            <p>has successfully completed the course</p>
        </div>

        <div class="course-info">
            <p class="course-name"><?= esc($course->title) ?></p>
            <?php if (!empty($course->instructor)): ?>
                <p>Instructor: <?= esc($course->instructor) ?></p>
            <?php endif; ?>
            <?php if (!empty($course->duration)): ?>
                <p>Duration: <?= esc($course->duration) ?></p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <div class="footer-cell">
                <div class="line"></div>
                <p>Date Issued</p>
                <p><?= date('F j, Y', strtotime($certificate['issued_at'])) ?></p>
            </div>
            <div class="footer-cell">
                <div class="line"></div>
                <p>JobberRecruit</p>
                <p>Platform Administrator</p>
            </div>
            <div class="footer-cell">
                <div class="line"></div>
                <p>Certificate Code</p>
                <p><?= esc($certificate['certificate_code']) ?></p>
            </div>
        </div>

        <div class="certificate-code">
            Verify this certificate at <?= base_url() ?> using code: <?= esc($certificate['certificate_code']) ?>
        </div>
    </div>
</body>
</html>
