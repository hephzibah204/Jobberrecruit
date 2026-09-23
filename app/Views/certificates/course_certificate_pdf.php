<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate of Completion — <?= esc($user->full_name ?? $user->username ?? 'Participant') ?></title>
<style>
@page {
    size: 297mm 210mm landscape;
    margin: 8mm 12mm;
}
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    background-color: #fdfbf4;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    color: #15233a;
    -webkit-print-color-adjust: exact;
}
.cert-border-outer {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 2pt solid #0A2F57;
}
.cert-border-inner {
    position: fixed;
    top: 2mm;
    left: 2mm;
    right: 2mm;
    bottom: 2mm;
    border: 1pt solid #C9A24B;
}
.cert-corner {
    position: fixed;
    width: 12mm;
    height: 12mm;
    border: 0 solid #ED9020;
}
.cc-tl { top: 1mm; left: 1mm; border-top: 2.5pt solid #ED9020; border-left: 2.5pt solid #ED9020; }
.cc-tr { top: 1mm; right: 1mm; border-top: 2.5pt solid #ED9020; border-right: 2.5pt solid #ED9020; }
.cc-bl { bottom: 1mm; left: 1mm; border-bottom: 2.5pt solid #ED9020; border-left: 2.5pt solid #ED9020; }
.cc-br { bottom: 1mm; right: 1mm; border-bottom: 2.5pt solid #ED9020; border-right: 2.5pt solid #ED9020; }

.cert-content {
    position: relative;
    z-index: 10;
    text-align: center;
    width: 100%;
}
.logo-wrap {
    margin-top: 3mm;
    margin-bottom: 2mm;
    text-align: center;
}
.logo-img {
    height: 38px;
    max-width: 220px;
}
.cert-ribbon {
    display: inline-block;
    font-size: 7.5pt;
    font-weight: bold;
    letter-spacing: 1.2pt;
    text-transform: uppercase;
    color: #0861A9;
    background-color: #eef5fb;
    border: 1pt solid #d2e4f3;
    border-radius: 12pt;
    padding: 3pt 14pt;
    margin-bottom: 3.5mm;
}
.cert-title {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 26pt;
    font-weight: bold;
    color: #0A2F57;
    margin-bottom: 1.5mm;
    line-height: 1;
}
.cert-sub {
    font-size: 8pt;
    letter-spacing: 3pt;
    text-transform: uppercase;
    color: #5b6577;
    font-weight: bold;
    margin-bottom: 2.5mm;
}
.cert-divider {
    width: 130px;
    height: 1.5pt;
    background-color: #C9A24B;
    margin: 0 auto 3mm;
}
.cert-name {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 28pt;
    font-weight: bold;
    color: #0A2F57;
    margin-bottom: 1.5mm;
    line-height: 1.05;
}
.cert-name-rule {
    width: 280px;
    height: 1pt;
    background-color: #d9e2ee;
    margin: 0 auto 2.5mm;
}
.cert-statement {
    font-size: 9pt;
    color: #5b6577;
    margin-bottom: 2mm;
    line-height: 1.4;
}
.cert-course {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 13pt;
    font-style: italic;
    font-weight: bold;
    color: #064A85;
    margin-bottom: 3.5mm;
    line-height: 1.2;
}
.cert-additional {
    font-size: 7.5pt;
    font-weight: bold;
    color: #0A2F57;
    letter-spacing: 0.5pt;
    margin-bottom: 3mm;
}
.meta-table {
    width: 55%;
    margin: 0 auto 3.5mm;
    text-align: center;
}
.meta-lbl {
    font-size: 6.5pt;
    letter-spacing: 1pt;
    text-transform: uppercase;
    color: #5b6577;
    font-weight: bold;
    margin-bottom: 1pt;
}
.meta-val {
    font-size: 9pt;
    font-weight: bold;
    color: #0A2F57;
}
.bottom-table {
    width: 100%;
    margin-top: 1mm;
}
.seal-disc {
    width: 76px;
    height: 76px;
    border: 2pt solid #C9A24B;
    border-radius: 50%;
    background-color: #0A2F57;
    margin: 0 auto;
    text-align: center;
}
.seal-text {
    color: #E4C878;
    font-size: 6.5pt;
    font-weight: bold;
    letter-spacing: 1pt;
    line-height: 1.3;
}
.sign-block {
    width: 160px;
    margin: 0 auto;
    text-align: center;
}
.sign-img {
    height: 42px;
    max-width: 150px;
    margin-bottom: 1pt;
}
.sign-line {
    height: 1pt;
    background-color: #15233a;
    opacity: 0.35;
    margin-bottom: 3pt;
}
.sign-role {
    font-size: 6.5pt;
    letter-spacing: 0.8pt;
    text-transform: uppercase;
    color: #5b6577;
    font-weight: bold;
}
.sign-company {
    font-size: 6.5pt;
    letter-spacing: 0.5pt;
    text-transform: uppercase;
    color: #0A2F57;
    font-weight: bold;
    margin-top: 1pt;
}
.verify-table {
    width: 100%;
    border-top: 1pt solid #E4C878;
    margin-top: 3mm;
    padding-top: 2.5mm;
}
.vt-lbl {
    font-size: 6pt;
    letter-spacing: 1pt;
    text-transform: uppercase;
    color: #5b6577;
    font-weight: bold;
}
.vt-id {
    font-size: 8.5pt;
    font-weight: bold;
    color: #0A2F57;
    letter-spacing: 0.5pt;
}
.vt-url {
    font-size: 7pt;
    color: #0861A9;
    font-weight: bold;
}
.badge-verified {
    display: inline-block;
    font-size: 7pt;
    font-weight: bold;
    color: #0A2F57;
    background-color: #f7f1e1;
    border: 1pt solid #C9A24B;
    border-radius: 10pt;
    padding: 2pt 8pt;
}
</style>
</head>
<body>
<?php
  $layoutState = [];
  if (!empty($template['layout_json'])) {
      $layoutState = is_string($template['layout_json']) ? json_decode($template['layout_json'], true) : $template['layout_json'];
      if (!is_array($layoutState)) $layoutState = [];
  }

  $certType = $template['cert_type'] ?? 'training';
  $ribbonText = 'Professional Training Programme';
  $statementText = 'has successfully completed all requirements of the professional training programme';
  if ($certType === 'webinar') {
      $ribbonText = 'Professional Webinar';
      $statementText = 'attended and successfully completed the professional webinar';
  } elseif ($certType === 'course') {
      $ribbonText = 'Course Completion';
      $statementText = 'has successfully completed the online course';
  }

  $courseTitle = is_array($course) ? ($course['title'] ?? '') : ($course->title ?? '');
  $courseDuration = is_array($course) ? ($course['duration'] ?? '') : ($course->duration ?? '');
  $fullName = $user->full_name ?? $user->username ?? 'Participant';
  $certCode = $certificate['certificate_code'] ?? '';
  $issuedAt = date('F j, Y', strtotime($certificate['issued_at'] ?? 'now'));
  $logoPath = file_exists(FCPATH . 'auth/img/logo.png') ? FCPATH . 'auth/img/logo.png' : base_url('auth/img/logo.png');
?>
    <div class="cert-border-outer"></div>
    <div class="cert-border-inner"></div>
    <span class="cert-corner cc-tl"></span>
    <span class="cert-corner cc-tr"></span>
    <span class="cert-corner cc-bl"></span>
    <span class="cert-corner cc-br"></span>

    <div class="cert-content">
        <!-- Logo -->
        <?php if (($layoutState['logo']['visible'] ?? true) !== false): ?>
          <div class="logo-wrap">
              <img src="<?= $logoPath ?>" class="logo-img" alt="JobberRecruit">
          </div>
        <?php endif; ?>

        <!-- Ribbon -->
        <?php if (($layoutState['ribbon']['visible'] ?? true) !== false): ?>
          <div>
              <span class="cert-ribbon"><?= esc($layoutState['ribbon']['text'] ?? $ribbonText) ?></span>
          </div>
        <?php endif; ?>

        <!-- Title -->
        <?php if (($layoutState['title']['visible'] ?? true) !== false): ?>
          <div class="cert-title"><?= esc($layoutState['title']['text'] ?? 'Certificate of Completion') ?></div>
        <?php endif; ?>

        <!-- Subtitle -->
        <?php if (($layoutState['sub']['visible'] ?? true) !== false): ?>
          <div class="cert-sub">This certifies that</div>
        <?php endif; ?>

        <div class="cert-divider"></div>

        <!-- Name -->
        <?php if (($layoutState['name']['visible'] ?? true) !== false): ?>
          <div class="cert-name"><?= esc($fullName) ?></div>
          <div class="cert-name-rule"></div>
        <?php endif; ?>

        <!-- Statement -->
        <?php if (($layoutState['statement']['visible'] ?? true) !== false): ?>
          <div class="cert-statement"><?= esc($layoutState['statement']['text'] ?? $statementText) ?></div>
        <?php endif; ?>

        <!-- Course Title -->
        <?php if (($layoutState['course']['visible'] ?? true) !== false): ?>
          <div class="cert-course"><?= esc($courseTitle) ?></div>
        <?php endif; ?>

        <!-- Additional text -->
        <?php if (!empty($template['additional_text']) && ($layoutState['additional_text']['visible'] ?? true) !== false): ?>
          <div class="cert-additional"><?= esc($template['additional_text']) ?></div>
        <?php endif; ?>

        <!-- Meta Table -->
        <?php if (($layoutState['meta']['visible'] ?? true) !== false): ?>
          <table class="meta-table" align="center">
              <tr>
                  <td width="50%">
                      <div class="meta-lbl">Date Issued</div>
                      <div class="meta-val"><?= esc($issuedAt) ?></div>
                  </td>
                  <?php if (!empty($courseDuration)): ?>
                    <td width="50%">
                        <div class="meta-lbl">Duration</div>
                        <div class="meta-val"><?= esc($courseDuration) ?></div>
                    </td>
                  <?php endif; ?>
              </tr>
          </table>
        <?php endif; ?>

        <!-- Bottom Table: Seal and Signature -->
        <table class="bottom-table">
            <tr>
                <td width="30%" align="center" valign="middle">
                    <?php if (($layoutState['seal']['visible'] ?? true) !== false): ?>
                      <table class="seal-disc">
                          <tr>
                              <td align="center" valign="middle" class="seal-text">
                                  JOBBERRECRUIT<br>
                                  <span style="font-size:5pt;color:#ffffff;">★ CERTIFIED ★</span>
                              </td>
                          </tr>
                      </table>
                    <?php endif; ?>
                </td>
                <td width="40%"></td>
                <td width="30%" align="center" valign="bottom">
                    <?php if (($layoutState['sign']['visible'] ?? true) !== false): ?>
                      <div class="sign-block">
                          <?php if (function_exists('setting') && setting('Elearning.certificate_signature') && file_exists(FCPATH . setting('Elearning.certificate_signature'))): ?>
                              <img src="<?= FCPATH . setting('Elearning.certificate_signature') ?>" class="sign-img" alt="Signature">
                          <?php else: ?>
                              <div style="font-family:cursive;font-size:15pt;color:#0A2F57;margin-bottom:-1pt;">Jane Smith</div>
                          <?php endif; ?>
                          <div class="sign-line"></div>
                          <div class="sign-role">Authorised Signatory</div>
                          <div class="sign-company">JobberRecruit Ltd</div>
                      </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <!-- Verification Table -->
        <?php if (($layoutState['verify']['visible'] ?? true) !== false): ?>
          <table class="verify-table">
              <tr>
                  <td width="45px" align="left" valign="middle">
                      <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?= urlencode(base_url('verify/' . $certCode)) ?>" style="width:40px;height:40px;border:1pt solid #d9e2ee;border-radius:3pt;" alt="QR">
                  </td>
                  <td align="left" valign="middle" style="padding-left:6px;">
                      <div class="vt-lbl">Certificate ID</div>
                      <div class="vt-id"><?= esc($certCode) ?></div>
                      <div class="vt-url">Verify at <?= base_url('verify/' . esc($certCode)) ?></div>
                  </td>
                  <td align="right" valign="middle">
                      <span class="badge-verified">✔ Authentic &amp; Verifiable</span>
                  </td>
              </tr>
          </table>
        <?php endif; ?>

        <!-- Custom Elements from layout_json -->
        <?php foreach ($layoutState as $elemId => $cfg): ?>
          <?php if (str_starts_with($elemId, 'custom_') && ($cfg['visible'] ?? true) !== false): ?>
            <?php if (($cfg['type'] ?? '') === 'text'): ?>
              <div style="position:absolute;left:<?= intval($cfg['left'] ?? 0) ?>px;top:<?= intval($cfg['top'] ?? 0) ?>px;font-size:<?= esc($cfg['fontSize'] ?? '12pt') ?>;color:<?= esc($cfg['color'] ?? '#15233a') ?>;font-weight:<?= esc($cfg['fontWeight'] ?? 'bold') ?>;text-align:<?= esc($cfg['textAlign'] ?? 'left') ?>;z-index:20;">
                <?= esc($cfg['text'] ?? '') ?>
              </div>
            <?php elseif (($cfg['type'] ?? '') === 'image'): ?>
              <div style="position:absolute;left:<?= intval($cfg['left'] ?? 0) ?>px;top:<?= intval($cfg['top'] ?? 0) ?>px;z-index:20;">
                <img src="<?= esc($cfg['url'] ?? '') ?>" style="max-width:100px;max-height:100px;" alt="">
              </div>
            <?php elseif (($cfg['type'] ?? '') === 'line'): ?>
              <div style="position:absolute;left:<?= intval($cfg['left'] ?? 0) ?>px;top:<?= intval($cfg['top'] ?? 0) ?>px;width:<?= esc($cfg['width'] ?? '300px') ?>;height:<?= esc($cfg['height'] ?? '2px') ?>;background-color:<?= esc($cfg['backgroundColor'] ?? '#C9A24B') ?>;z-index:20;"></div>
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>
