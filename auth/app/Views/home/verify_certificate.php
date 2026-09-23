<?= $this->extend('templates/base') ?>

<?= $this->section('meta') ?>
<meta name="robots" content="noindex, follow">
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<?php if ($certificate ?? null): ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'DigitalDocument',
    'name'        => esc($certificate->course_name ?? 'Certificate'),
    'description' => 'Certificate issued to ' . esc($certificate->recipient_name ?? 'Recipient') . ' for ' . esc($certificate->course_name ?? ''),
    'provider'    => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
    ],
    'dateCreated'   => $certificate->issue_date ?? null,
    'identifier'    => $certificate->certificate_id ?? null,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">
  <section class="vrf-hero">
    <div class="container text-center">
      <nav class="pg-bc" aria-label="Breadcrumb">
        <a href="<?= base_url() ?>">Home</a>
        <svg aria-hidden="true" width="12" height="12"><use href="#i-chev-down"/></svg>
        <a href="<?= base_url('training') ?>">Training</a>
        <svg aria-hidden="true" width="12" height="12"><use href="#i-chev-down"/></svg>
        <span aria-current="page">Verify Certificate</span>
      </nav>
      <h1>Certificate Verification</h1>
      <p>Confirm the authenticity of any certificate issued by JobberRecruit.</p>
    </div>
  </section>

  <div class="vrf-wrap">
    <?php if ($certificate ?? null): ?>
    <div class="vrf-card" id="vrf-result">
      <?php $isValid = ($certificate->status ?? 'valid') === 'valid'; ?>
      <div class="vrf-status <?= $isValid ? 'valid' : 'invalid' ?>">
        <div class="vrf-status-ic">
          <?php if ($isValid): ?>
            <svg aria-hidden="true" width="28" height="28"><use href="#i-check"/></svg>
          <?php else: ?>
            <svg aria-hidden="true" width="28" height="28"><use href="#i-x-circle"/></svg>
          <?php endif; ?>
        </div>
        <div>
          <h2><?= $isValid ? 'Certificate Verified' : 'Certificate Not Found' ?></h2>
          <p><?= $isValid ? 'This certificate is authentic and was issued by JobberRecruit.' : 'No matching certificate record found. The ID may be incorrect or the certificate may not have been issued.' ?></p>
        </div>
      </div>

      <?php if ($isValid): ?>
      <div class="vrf-body">
        <div class="vrf-recipient">
          <div class="lbl">Awarded to</div>
          <div class="name"><?= esc($certificate->recipient_name ?? '') ?></div>
        </div>

        <div class="vrf-grid">
          <div class="vrf-field full">
            <div class="lbl">Programme</div>
            <div class="val"><?= esc($certificate->course_name ?? '') ?></div>
          </div>
          <div class="vrf-field">
            <div class="lbl">Certificate ID</div>
            <div class="val id"><?= esc($certificate->certificate_id ?? '') ?></div>
          </div>
          <div class="vrf-field">
            <div class="lbl">Issue Date</div>
            <div class="val"><?= esc($certificate->issue_date ?? '') ?></div>
          </div>
          <div class="vrf-field">
            <div class="lbl">Expiry Date</div>
            <div class="val"><?= esc($certificate->expiry_date ?? 'N/A') ?></div>
          </div>
          <div class="vrf-field">
            <div class="lbl">Issue Number</div>
            <div class="val"><?= esc($certificate->issue_number ?? 'N/A') ?></div>
          </div>
        </div>

        <div class="vrf-issuer">
          <div class="vrf-issuer-mark">
            <i class="ti ti-certificate text-white" style="font-size: 22px;"></i>
          </div>
          <div class="vrf-issuer-text">
            <strong>Issued by JobberRecruit Ltd</strong>
            <span>Lagos, Nigeria</span>
          </div>
        </div>

        <div class="vrf-actions">
          <a href="<?= base_url('training/certificate/view/' . esc($certificate->id ?? '')) ?>" class="btn btn-primary">
            <i class="ti ti-eye me-1"></i> View Certificate
          </a>
          <a href="<?= base_url('training/certificate/download/' . esc($certificate->id ?? '')) ?>" class="btn btn-outline">
            <i class="ti ti-download me-1"></i> Download PDF
          </a>
          <button type="button" class="btn btn-outline" onclick="shareCertificate()">
            <i class="ti ti-share me-1"></i> Share
          </button>
        </div>

        <div class="vrf-note">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-shield"/></svg>
          <span>This record is maintained by JobberRecruit. If the details above do not match the certificate presented to you, the document may have been altered &mdash; please <a href="<?= base_url('contact-us') ?>">contact us</a> to report it.</span>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="vrf-manual">
      <h3>Verify a different certificate</h3>
      <p>Enter a certificate ID to check its authenticity.</p>
      <form class="vrf-manual-form" action="<?= base_url('verify') ?>" method="get">
        <input type="text" name="id" placeholder="e.g. JR-CV-2026-8F3A21" aria-label="Certificate ID" value="<?= esc($searchQuery ?? '') ?>">
        <button type="submit" class="btn btn-primary">
          <i class="ti ti-search me-1"></i> Verify
        </button>
      </form>
    </div>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.vrf-hero {
  background: radial-gradient(1000px 500px at 80% -10%, rgba(237,144,32,.16), transparent 55%), linear-gradient(155deg, #07304F, #0A4D7E);
  color: #fff;
  padding: 48px 0;
  text-align: center;
}
.vrf-hero h1 {
  font-size: 1.9rem;
  font-weight: 800;
  letter-spacing: -.02em;
  margin-bottom: 8px;
  color: #fff;
}
.vrf-hero p {
  font-size: .98rem;
  color: rgba(255,255,255,.82);
  max-width: 520px;
  margin: 0 auto;
}
.pg-bc {
  display: flex;
  gap: 7px;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  font-size: .76rem;
  color: rgba(255,255,255,.6);
  margin-bottom: 18px;
}
.pg-bc a { color: rgba(255,255,255,.6); text-decoration: none; }
.pg-bc a:hover { color: #fff; }
.pg-bc svg { width: 12px; height: 12px; opacity: .5; transform: rotate(-90deg); }
.pg-bc [aria-current] { color: rgba(255,255,255,.85); font-weight: 600; }
.vrf-wrap {
  max-width: 720px;
  margin: -32px auto 0;
  padding: 0 20px 64px;
  position: relative;
  z-index: 2;
}
.vrf-card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 18px;
  box-shadow: 0 14px 40px rgba(10,47,87,.14);
  overflow: hidden;
}
.vrf-status {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 24px 28px;
  border-bottom: 1px solid var(--border);
}
.vrf-status.valid { background: #f0fdf4; }
.vrf-status.invalid { background: #fef2f2; }
.vrf-status-ic {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.vrf-status.valid .vrf-status-ic { background: #16a34a; color: #fff; }
.vrf-status.invalid .vrf-status-ic { background: #dc2626; color: #fff; }
.vrf-status h2 {
  font-size: 1.2rem;
  font-weight: 800;
  margin-bottom: 2px;
}
.vrf-status.valid h2 { color: #15803d; }
.vrf-status.invalid h2 { color: #b91c1c; }
.vrf-status p { font-size: .86rem; color: var(--muted); margin: 0; }
.vrf-body { padding: 28px; }
.vrf-recipient {
  text-align: center;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 24px;
}
.vrf-recipient .lbl {
  font-size: .7rem;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--muted);
  font-weight: 700;
  margin-bottom: 6px;
}
.vrf-recipient .name {
  font-family: 'Sora', sans-serif;
  font-size: 1.7rem;
  font-weight: 800;
  color: var(--brand-deep);
  line-height: 1.1;
}
.vrf-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}
.vrf-field .lbl {
  font-size: .68rem;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--muted);
  font-weight: 700;
  margin-bottom: 4px;
}
.vrf-field .val {
  font-size: .96rem;
  color: var(--text);
  font-weight: 600;
  line-height: 1.4;
}
.vrf-field.full { grid-column: 1 / -1; }
.vrf-field .val.id {
  font-family: 'Sora', sans-serif;
  letter-spacing: .02em;
  color: var(--brand);
}
.vrf-issuer {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid var(--border);
}
.vrf-issuer-mark {
  width: 40px;
  height: 40px;
  border-radius: 9px;
  background: linear-gradient(135deg, var(--brand), var(--brand-deep));
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.vrf-issuer-text strong {
  display: block;
  font-size: .9rem;
  color: var(--text);
}
.vrf-issuer-text span {
  font-size: .78rem;
  color: var(--muted);
}
.vrf-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
  flex-wrap: wrap;
}
.vrf-actions .btn {
  flex: 1;
  justify-content: center;
  min-width: 160px;
}
.vrf-note {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-top: 20px;
  padding: 14px 16px;
  background: #f7fafd;
  border: 1px solid #e4edf6;
  border-radius: 10px;
  font-size: .8rem;
  color: var(--muted);
  line-height: 1.5;
}
.vrf-note svg {
  width: 17px;
  height: 17px;
  color: var(--brand);
  flex-shrink: 0;
  margin-top: 1px;
}
.vrf-note a { color: var(--brand); text-decoration: underline; }
.vrf-manual {
  max-width: 520px;
  margin: 36px auto 0;
  text-align: center;
}
.vrf-manual h3 {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--brand-deep);
  margin-bottom: 10px;
}
.vrf-manual p {
  font-size: .88rem;
  color: var(--muted);
  margin-bottom: 0;
}
.vrf-manual-form {
  display: flex;
  gap: 10px;
  margin-top: 14px;
}
.vrf-manual-form input {
  flex: 1;
  min-height: 48px;
  padding: 12px 16px;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  font-family: 'Inter', sans-serif;
  font-size: 16px;
}
.vrf-manual-form input:focus {
  outline: none;
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgba(13,96,158,.12);
}
@media (max-width: 580px) {
  .vrf-hero h1 { font-size: 1.5rem; }
  .vrf-grid { grid-template-columns: 1fr; }
  .vrf-status { padding: 20px; }
  .vrf-body { padding: 22px; }
  .vrf-recipient .name { font-size: 1.4rem; }
  .vrf-manual-form { flex-direction: column; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function shareCertificate() {
  if (navigator.share) {
    navigator.share({
      title: 'JobberRecruit Certificate',
      text: 'Verify my JobberRecruit certificate: <?= esc($certificate->certificate_id ?? '') ?>',
      url: window.location.href,
    }).catch(function() {});
  }
}
</script>
<?= $this->endSection() ?>
