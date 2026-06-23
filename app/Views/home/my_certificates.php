<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold"><i data-feather="award" class="me-2"></i>My Certificates</h4>
            <h6>Certificates for completed courses</h6>
        </div>
    </div>

    <div class="row">
        <?php if (empty($certificates)): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i data-feather="award" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                        <h5 class="text-muted">No certificates yet</h5>
                        <p class="text-muted">Complete a course to earn your certificate.</p>
                        <a href="<?= base_url('training') ?>" class="btn btn-primary">Browse Courses</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($certificates as $cert): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <i data-feather="award" class="text-primary mb-3" style="width: 48px; height: 48px;"></i>
                            <h6 class="fw-semibold"><?= esc($cert['course_name']) ?></h6>
                            <p class="text-muted small mb-2">Issued: <?= date('M j, Y', strtotime($cert['issued_at'])) ?></p>
                            <p class="text-muted small mb-3">Code: <code><?= esc($cert['certificate_code']) ?></code></p>
                            <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" class="btn btn-outline-primary btn-sm">
                                <i data-feather="download" class="me-1"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
