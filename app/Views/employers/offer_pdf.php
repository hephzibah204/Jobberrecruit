<?php
// Dompdf-safe letter markup: literal colours only, no CSS variables,
// no gradients — same constraint as the resume templates (Round 23).
$companyName = $employer->company_name ?? 'Employer';
$letterDate  = !empty($offer->sent_at) ? $offer->sent_at : $offer->created_at;
$paragraphs  = preg_split('/\n{2,}/', trim((string) $offer->letter_body)) ?: [];
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<title>Offer of Employment — <?= esc($offer->candidate_name) ?></title>
<style>
  body { font-family: DejaVu Sans, sans-serif; color: #15233a; font-size: 12.5px; line-height: 1.65; margin: 48px 56px; }
  .letterhead { text-align: center; border-bottom: 3px solid #0A2F57; padding-bottom: 14px; margin-bottom: 8px; }
  .letterhead .company { font-size: 22px; font-weight: bold; color: #0A2F57; letter-spacing: .3px; }
  .letterhead .sub { font-size: 10.5px; color: #5b6577; text-transform: uppercase; letter-spacing: 2px; margin-top: 2px; }
  .date { text-align: right; color: #5b6577; margin: 14px 0 20px; }
  h1 { font-size: 16px; color: #0A2F57; text-align: center; margin: 0 0 18px; text-transform: uppercase; letter-spacing: 1px; }
  p { margin: 0 0 12px; text-align: justify; }
  .acceptance { margin-top: 34px; border: 1.5px solid #0A2F57; padding: 14px 18px; background: #f2f6fb; }
  .acceptance .a-title { font-weight: bold; color: #0A2F57; margin-bottom: 4px; }
  .footer { margin-top: 40px; padding-top: 10px; border-top: 1px solid #d9e2ee; font-size: 9.5px; color: #5b6577; text-align: center; }
</style>
</head>
<body>
  <div class="letterhead">
    <div class="company"><?= esc($companyName) ?></div>
    <div class="sub">Offer of Employment</div>
  </div>

  <div class="date"><?= esc(date('d F Y', strtotime((string) $letterDate))) ?></div>

  <?php foreach ($paragraphs as $para): ?>
    <p><?= nl2br(esc(trim($para))) ?></p>
  <?php endforeach; ?>

  <?php if ($offer->status === 'accepted'): ?>
    <div class="acceptance">
      <div class="a-title">Accepted</div>
      This offer was accepted by <?= esc($offer->candidate_name) ?> on <?= esc(date('d F Y', strtotime((string) $offer->responded_at))) ?>.
    </div>
  <?php elseif ($offer->status === 'declined'): ?>
    <div class="acceptance">
      <div class="a-title">Declined</div>
      This offer was declined by <?= esc($offer->candidate_name) ?> on <?= esc(date('d F Y', strtotime((string) $offer->responded_at))) ?>.
    </div>
  <?php endif; ?>

  <div class="footer">Generated via JobberRecruit on behalf of <?= esc($companyName) ?></div>
</body>
</html>
