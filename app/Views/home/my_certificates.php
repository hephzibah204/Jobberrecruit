<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.certs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(12px, 1.6vw, 18px);
    margin-top: 20px;
}
@media (max-width: 800px) {
    .certs { grid-template-columns: 1fr; }
}
.cert {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 22px 24px;
}
.cert-medal {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 2px solid #bfdbfe;
}
.cert-medal svg {
    width: 24px;
    height: 24px;
    stroke: #2563eb;
    fill: none;
    stroke-width: 2;
}
.cert-info {
    flex: 1;
    min-width: 0;
}
.cert-title {
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: 1rem;
    color: var(--brand-deep);
    margin-bottom: 4px;
}
.cert-issued {
    font-size: .8rem;
    color: var(--muted);
    margin-bottom: 6px;
}
.cert-code {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .78rem;
    font-family: monospace;
    background: var(--bg);
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid var(--border);
    color: var(--brand-deep);
    margin-bottom: 14px;
}
.cert-code svg {
    flex-shrink: 0;
}
.cert-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.share-hint {
    font-size: .76rem;
    color: var(--muted);
    margin: 0;
}
@media (max-width: 600px) {
    .cert { flex-direction: column; gap: 14px; }
    .cert-medal { width: 44px; height: 44px; }
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
            <a href="<?= base_url('training') ?>" class="btn btn-accent btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-grad"/></svg> Browse Courses
            </a>
        </div>
    </div>

    <!-- Certificates List -->
    <?php if (empty($certificates)): ?>
        <div class="card">
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true" style="width:28px;height:28px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-award"/></svg></span>
                <h3>No certificates yet</h3>
                <p>Complete a course to earn a verifiable certificate that automatically appears on your profile and employer-facing CV.</p>
                <a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm">Browse Training Catalog</a>
            </div>
        </div>
    <?php else: ?>
        <div class="certs">
            <?php foreach ($certificates as $cert): ?>
            <section class="card cert" aria-label="Certificate: <?= esc($cert['course_name'] ?? 'Course Certificate') ?>">
                <span class="cert-medal" aria-hidden="true">
                    <svg><use href="#i-award"/></svg>
                </span>
                <div class="cert-info">
                    <div class="cert-title"><?= esc($cert['course_name'] ?? 'Certificate') ?></div>
                    <div class="cert-issued">
                        Issued <?= date('d M Y', strtotime($cert['issued_at'])) ?>
                        <?php if (!empty($cert['instructor_name'])): ?>
                            · <?= esc($cert['instructor_name']) ?>
                        <?php endif; ?>
                    </div>
                    <div class="cert-code">
                        <svg style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;" aria-hidden="true"><use href="#i-shield"/></svg>
                        <?= esc($cert['certificate_code']) ?>
                    </div>
                    <div class="cert-actions">
                        <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>"
                           class="btn btn-primary btn-sm"
                           target="_blank">
                            <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-download"/></svg>
                            Download PDF
                        </a>
                        <a href="<?= base_url('certificates/verify?id=' . rawurlencode($cert['certificate_code'])) ?>"
                           class="btn btn-outline btn-sm"
                           target="_blank" rel="noopener">
                            <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg>
                            Verify Online
                        </a>
                    </div>
                    <p class="share-hint">Add the verify link to your CV or LinkedIn — employers can confirm it instantly.</p>
                </div>
            </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="notice notice--info" style="margin-top: 20px;">
        <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
        <span>Every JobberRecruit certificate carries a unique code and a public verification page, so employers can trust it at a glance. Earn more certificates in the <a href="<?= base_url('training') ?>">Training Catalog</a>.</span>
    </div>
</div>
<?= $this->endSection() ?>
