<?= $this->extend('emails/layouts/main') ?>

<?= $this->section('content') ?>

<h2 style="color: #1a1a1a; margin-top: 0; font-size: 24px; font-weight: 700;">Assessment Completed</h2>

<p style="font-size: 16px; color: #444; line-height: 1.5; margin-bottom: 20px;">
    Hello <?= esc($employer_name) ?>,
</p>

<p style="font-size: 16px; color: #444; line-height: 1.5; margin-bottom: 20px;">
    Great news! <strong><?= esc($candidate_name) ?></strong> has just completed the assessment test you shared via your standalone tracking link.
</p>

<div style="background-color: #f8f9fa; border-left: 4px solid #0d6efd; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
    <p style="margin: 0 0 10px; font-size: 16px;">
        <strong>Assessment Title:</strong> <span style="color: #333;"><?= esc($test_title) ?></span>
    </p>
    <p style="margin: 0 0 10px; font-size: 16px;">
        <strong>Completion Date:</strong> <span style="color: #333;"><?= esc($completed_at) ?></span>
    </p>
    <p style="margin: 0; font-size: 18px;">
        <strong>Score:</strong> 
        <span style="color: <?= $passed ? '#198754' : '#dc3545' ?>; font-weight: 700; font-size: 22px;">
            <?= number_format($score_percentage, 1) ?>%
        </span>
    </p>
</div>

<p style="font-size: 16px; color: #444; line-height: 1.5; margin-bottom: 30px;">
    You can view the candidate's full profile, contact details, and test history on your dashboard by clicking the button below.
</p>

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 30px;">
    <tr>
        <td align="center">
            <a href="<?= esc($candidate_profile_url) ?>" 
               style="display: inline-block; padding: 12px 24px; background-color: #0d6efd; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 6px;">
                View Candidate Profile
            </a>
        </td>
    </tr>
</table>

<p style="font-size: 15px; color: #666; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
    <strong>Tip:</strong> You can continue to share your standalone assessment links anywhere (LinkedIn, WhatsApp, Job Boards). As long as candidates use your specific link, their results will be tracked and sent directly to you!
</p>

<?= $this->endSection() ?>
