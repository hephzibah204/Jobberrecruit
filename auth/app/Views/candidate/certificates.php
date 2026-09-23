<?php $page_title = 'My Certificates'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.certs { display: flex; flex-direction: column; gap: clamp(14px,2vw,20px); }
.cert { display: flex; align-items: flex-start; gap: 18px; padding: 22px 24px; }
.cert-medal { flex-shrink: 0; width: 48px; height: 48px; border-radius: 12px;
  background: linear-gradient(135deg, var(--accent-light), var(--accent));
  display: flex; align-items: center; justify-content: center; color: var(--accent-dark); }
.cert-medal svg { width: 24px; height: 24px; }
.cert-info { flex: 1; min-width: 0; }
.cert-title { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1rem;
  color: var(--brand-deep); margin-bottom: 4px; }
.cert-issued { font-size: .78rem; color: var(--muted); margin-bottom: 6px; }
.cert-code { font-size: .74rem; color: var(--brand); font-weight: 600;
  display: inline-flex; align-items: center; gap: 5px; margin-bottom: 14px;
  background: var(--brand-light); padding: 4px 10px; border-radius: 20px; }
.cert-code svg { flex-shrink: 0; }
.cert-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.share-hint { font-size: .72rem; color: var(--muted); margin-top: 12px; }
.notice { display: flex; align-items: flex-start; gap: 12px; background: var(--brand-light);
  border: 1px solid #bbd3ef; border-radius: var(--radius-lg); padding: 16px 20px;
  font-size: .84rem; color: var(--brand-deep); }
.notice svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; color: var(--brand); }
@media (max-width:600px) {
  .cert { flex-direction: column; }
  .cert-medal { width: 40px; height: 40px; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-award"/></svg> My Certificates</h1>
            <p>Certificates for your completed courses — verifiable by any employer.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('training') ?>" class="btn btn-outline btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-book"/></svg> Explore Courses
            </a>
        </div>
    </div>

    <?php if (!empty($certificates)): ?>
    <div class="certs">
        <?php foreach ($certificates as $cert): ?>
        <section class="card cert" aria-label="Certificate: <?= esc($cert['course_name'] ?? 'Certificate') ?>">
            <span class="cert-medal" aria-hidden="true">
                <svg><use href="#i-award"/></svg>
            </span>
            <div class="cert-info">
                <div class="cert-title"><?= esc($cert['course_name'] ?? 'Course Certificate') ?></div>
                <div class="cert-issued">Issued <?= esc(date('d M Y', strtotime($cert['issued_at']))) ?> · JobberRecruit Training</div>
                <div class="cert-code">
                    <svg style="width:12px;height:12px;" aria-hidden="true"><use href="#i-shield"/></svg>
                    <?= esc($cert['certificate_code']) ?>
                </div>
                <div class="cert-actions">
                    <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" class="btn btn-primary btn-sm" target="_blank">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-download"/></svg> Download PDF
                    </a>
                    <a href="<?= base_url('verify/' . $cert['certificate_code']) ?>" class="btn btn-outline btn-sm" target="_blank">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg> Verify Online
                    </a>
                    <button class="btn btn-outline btn-sm" onclick="copyVerifyLink('<?= base_url('verify/' . $cert['certificate_code']) ?>')" type="button">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-copy"/></svg> Copy Link
                    </button>
                </div>
                <p class="share-hint">Add the verify link to your CV or LinkedIn — employers can confirm it instantly at a glance.</p>
            </div>
        </section>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <section class="card" aria-label="No certificates">
        <div class="empty">
            <span class="empty-ic">
                <svg aria-hidden="true" style="width:30px;height:30px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-award"/></svg>
            </span>
            <h3>No certificates yet</h3>
            <p>Complete a course in the Training Catalog to earn a verifiable certificate that attaches to your profile automatically.</p>
            <a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm">
                <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-book"/></svg>
                Browse Training Courses
            </a>
        </div>
    </section>
    <?php endif; ?>

    <div class="notice" role="note">
        <svg aria-hidden="true"><use href="#i-bulb"/></svg>
        <span>Every JobberRecruit certificate carries a unique code and a public verification page, so employers can trust it at a glance. Earn more certificates in the <a href="<?= base_url('training') ?>">Training Catalog</a>.</span>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function copyVerifyLink(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            if (typeof toastr !== 'undefined') toastr.success('Verify link copied to clipboard!');
        });
    } else {
        var inp = document.createElement('input');
        inp.value = url;
        document.body.appendChild(inp);
        inp.select();
        document.execCommand('copy');
        document.body.removeChild(inp);
        if (typeof toastr !== 'undefined') toastr.success('Verify link copied!');
    }
}
</script>
<?= $this->endSection() ?>
