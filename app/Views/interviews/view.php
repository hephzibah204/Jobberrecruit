<?php
$companyName = $employer->company_name ?? 'The Employer';
$modeLabels = ['in_person' => 'In Person', 'phone' => 'Phone Call', 'video' => 'Video Call'];
$modeLabel = $modeLabels[$interview->mode] ?? ucfirst((string) $interview->mode);
$scheduledTs = strtotime((string) $interview->scheduled_at) ?: null;
$dateDisplay = $scheduledTs ? date('l, d F Y', $scheduledTs) : '';
$timeDisplay = $scheduledTs ? date('g:i A', $scheduledTs) : '';
$duration = (int) ($interview->duration_minutes ?: 60);
$durationDisplay = $duration >= 60 && $duration % 60 === 0
    ? ($duration / 60) . ' hour' . ($duration === 60 ? '' : 's')
    : $duration . ' minutes';
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<meta name="robots" content="noindex, nofollow">
<title>Interview Invitation — <?= esc($companyName) ?></title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Segoe UI',Arial,sans-serif;background:#e9eef4;color:#15233a;padding:28px 16px;-webkit-font-smoothing:antialiased}
  .wrap{max-width:760px;margin:0 auto}
  .card{background:#ffffff;border-radius:10px;box-shadow:0 10px 34px rgba(10,47,87,.16);overflow:hidden}
  .head{background:#0A2F57;color:#ffffff;padding:26px 32px;text-align:center}
  .head .company{font-size:22px;font-weight:700;letter-spacing:.3px}
  .head .sub{font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#c9d6e8;margin-top:3px}
  .body{padding:28px 32px}
  .flash{border-radius:8px;padding:12px 16px;font-size:14px;margin-bottom:18px}
  .flash--success{background:#e7f6ec;border:1px solid #b8dfc6;color:#1a5c32}
  .flash--error{background:#fdeeee;border:1px solid #f2c1c1;color:#8c2323}
  .status{border-radius:8px;padding:14px 16px;font-size:14.5px;margin-bottom:18px;text-align:center;font-weight:600}
  .status--confirmed{background:#e7f6ec;border:1px solid #b8dfc6;color:#1a5c32}
  .status--declined{background:#fdeeee;border:1px solid #f2c1c1;color:#8c2323}
  .status--cancelled{background:#fdeeee;border:1px solid #f2c1c1;color:#8c2323}
  table.details{width:100%;border-collapse:collapse;margin-bottom:22px;font-size:14.5px}
  table.details td{padding:8px 10px;border-bottom:1px solid #d9e2ee;vertical-align:top}
  table.details td.k{width:170px;color:#5b6577;font-weight:600}
  .notes{border:1px solid #d9e2ee;border-radius:8px;padding:18px 20px;background:#fdfcf8;font-size:14.5px;line-height:1.65;margin-bottom:6px}
  .notes .notes-title{font-weight:700;margin-bottom:8px}
  .respond{margin-top:24px;text-align:center}
  .respond p{font-size:14px;color:#5b6577;margin-bottom:14px}
  .btn{display:inline-block;border:none;cursor:pointer;font-size:15px;font-weight:700;border-radius:8px;padding:13px 30px;margin:0 5px}
  .btn--accept{background:#1a7f43;color:#ffffff}
  .btn--decline{background:#ffffff;color:#8c2323;border:1.5px solid #c0392b}
  .foot{text-align:center;font-size:12px;color:#5b6577;margin-top:18px;line-height:1.6}
  @media (max-width:560px){.body{padding:22px 18px}.head{padding:22px 18px}table.details td.k{width:120px}}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="head">
      <div class="company"><?= esc($companyName) ?></div>
      <div class="sub">Interview Invitation</div>
    </div>
    <div class="body">

      <?php if (session()->has('success')): ?>
        <div class="flash flash--success" role="status"><?= esc(session('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->has('error')): ?>
        <div class="flash flash--error" role="status"><?= esc(session('error')) ?></div>
      <?php endif; ?>

      <?php if ($interview->status === 'confirmed'): ?>
        <div class="status status--confirmed">You confirmed this interview on <?= esc(date('d F Y', strtotime((string) $interview->responded_at))) ?>. We look forward to speaking with you.</div>
      <?php elseif ($interview->status === 'declined'): ?>
        <div class="status status--declined">You declined this interview on <?= esc(date('d F Y', strtotime((string) $interview->responded_at))) ?>.</div>
      <?php elseif ($interview->status === 'cancelled'): ?>
        <div class="status status--cancelled">This interview has been cancelled by <?= esc($companyName) ?>. Please contact the employer if you would like to reschedule.</div>
      <?php endif; ?>

      <table class="details">
        <tr><td class="k">Position</td><td><?= esc($interview->job_title) ?></td></tr>
        <tr><td class="k">Company</td><td><?= esc($companyName) ?></td></tr>
        <tr><td class="k">Date</td><td><?= esc($dateDisplay) ?></td></tr>
        <tr><td class="k">Time</td><td><?= esc($timeDisplay) ?></td></tr>
        <tr><td class="k">Duration</td><td><?= esc($durationDisplay) ?></td></tr>
        <tr><td class="k">Mode</td><td><?= esc($modeLabel) ?></td></tr>
        <?php if (!empty($interview->location)): ?>
          <tr><td class="k"><?= $interview->mode === 'video' ? 'Meeting Link' : ($interview->mode === 'phone' ? 'Phone' : 'Venue') ?></td><td><?= esc($interview->location) ?></td></tr>
        <?php endif; ?>
      </table>

      <?php if (!empty($interview->notes)): ?>
        <div class="notes">
          <div class="notes-title">Note from <?= esc($companyName) ?></div>
          <p><?= nl2br(esc(trim((string) $interview->notes))) ?></p>
        </div>
      <?php endif; ?>

      <?php if ($interview->status === 'scheduled'): ?>
        <div class="respond">
          <p>Please confirm whether you will attend this interview. Your answer will be shown to the employer.</p>
          <form method="post" action="<?= site_url('interview/respond/' . $interview->view_token) ?>">
            <?= csrf_field() ?>
            <button type="submit" name="decision" value="confirm" class="btn btn--accept" onclick="return confirm('Confirm that you will attend this interview?');">Confirm Attendance</button>
            <button type="submit" name="decision" value="decline" class="btn btn--decline" onclick="return confirm('Decline this interview invitation? This cannot be undone.');">Decline</button>
          </form>
        </div>
      <?php endif; ?>

    </div>
  </div>
  <p class="foot">Sent via JobberRecruit on behalf of <?= esc($companyName) ?>.<br>This link is unique to you — please do not share it.</p>
</div>
</body>
</html>
