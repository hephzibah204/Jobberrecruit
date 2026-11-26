<?php
$companyName = $employer->company_name ?? 'The Employer';
$symbols = ['NGN' => '₦', 'USD' => '$', 'GBP' => '£', 'EUR' => '€'];
$salaryDisplay = '';
if (!empty($offer->salary)) {
    $salaryDisplay = ($symbols[$offer->currency] ?? ($offer->currency . ' ')) . $offer->salary;
}
$paragraphs = preg_split('/\n{2,}/', trim((string) $offer->letter_body)) ?: [];
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<meta name="robots" content="noindex, nofollow">
<title>Offer of Employment — <?= esc($companyName) ?></title>
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
  .status--accepted{background:#e7f6ec;border:1px solid #b8dfc6;color:#1a5c32}
  .status--declined{background:#fdeeee;border:1px solid #f2c1c1;color:#8c2323}
  table.details{width:100%;border-collapse:collapse;margin-bottom:22px;font-size:14.5px}
  table.details td{padding:8px 10px;border-bottom:1px solid #d9e2ee;vertical-align:top}
  table.details td.k{width:170px;color:#5b6577;font-weight:600}
  .letter{border:1px solid #d9e2ee;border-radius:8px;padding:22px 24px;background:#fdfcf8;font-size:15px;line-height:1.7}
  .letter p{margin-bottom:13px}
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
      <div class="sub">Offer of Employment</div>
    </div>
    <div class="body">

      <?php if (session()->has('success')): ?>
        <div class="flash flash--success" role="status"><?= esc(session('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->has('error')): ?>
        <div class="flash flash--error" role="status"><?= esc(session('error')) ?></div>
      <?php endif; ?>

      <?php if ($offer->status === 'accepted'): ?>
        <div class="status status--accepted">You accepted this offer on <?= esc(date('d F Y', strtotime((string) $offer->responded_at))) ?>. Congratulations!</div>
      <?php elseif ($offer->status === 'declined'): ?>
        <div class="status status--declined">You declined this offer on <?= esc(date('d F Y', strtotime((string) $offer->responded_at))) ?>.</div>
      <?php endif; ?>

      <table class="details">
        <tr><td class="k">Position</td><td><?= esc($offer->job_title) ?></td></tr>
        <tr><td class="k">Company</td><td><?= esc($companyName) ?></td></tr>
        <?php if (!empty($offer->employment_type)): ?>
          <tr><td class="k">Employment Type</td><td><?= esc(ucwords(str_replace('-', ' ', $offer->employment_type))) ?></td></tr>
        <?php endif; ?>
        <?php if ($salaryDisplay !== ''): ?>
          <tr><td class="k">Salary</td><td><?= esc($salaryDisplay) ?></td></tr>
        <?php endif; ?>
        <?php if (!empty($offer->start_date)): ?>
          <tr><td class="k">Start Date</td><td><?= esc(date('d F Y', strtotime((string) $offer->start_date))) ?></td></tr>
        <?php endif; ?>
        <?php if (!empty($offer->probation_months)): ?>
          <tr><td class="k">Probation</td><td><?= esc($offer->probation_months) ?> month(s)</td></tr>
        <?php endif; ?>
      </table>

      <div class="letter">
        <?php foreach ($paragraphs as $para): ?>
          <p><?= nl2br(esc(trim($para))) ?></p>
        <?php endforeach; ?>
      </div>

      <?php if ($offer->status === 'sent'): ?>
        <div class="respond">
          <p>Please respond to this offer below. Your answer is final and will be shown to the employer.</p>
          <form method="post" action="<?= site_url('offer/respond/' . $offer->view_token) ?>">
            <?= csrf_field() ?>
            <button type="submit" name="decision" value="accept" class="btn btn--accept" onclick="return confirm('Accept this offer of employment?');">Accept Offer</button>
            <button type="submit" name="decision" value="decline" class="btn btn--decline" onclick="return confirm('Decline this offer? This cannot be undone.');">Decline</button>
          </form>
        </div>
      <?php endif; ?>

    </div>
  </div>
  <p class="foot">Sent via JobberRecruit on behalf of <?= esc($companyName) ?>.<br>This link is unique to you — please do not share it.</p>
</div>
</body>
</html>
