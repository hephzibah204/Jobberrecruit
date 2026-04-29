<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">My Resumes</h4>
                <h6>Create and manage your AI-powered resumes</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Create New Resume
            </a>
        </div>
    </div>

    <div class="row">
        <?php if (empty($resumes)): ?>
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="ti ti-file-text text-muted" style="font-size: 64px;"></i>
                </div>
                <h5>No resumes found</h5>
                <p class="text-muted">Start building your professional resume with AI assistance today.</p>
                <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-primary mt-2">
                    Start Building
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($resumes as $resume): ?>
                <div class="col-xl-4 col-md-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1"><?= esc($resume->title) ?></h5>
                                    <small class="text-muted">Last updated: <?= date('M d, Y', strtotime($resume->updated_at)) ?></small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="ti ti-settings"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?= site_url('candidate/resumes/build/' . $resume->id) ?>"><i class="ti ti-edit me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="ti ti-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="<?= site_url('candidate/resumes/download/' . $resume->id) ?>" class="btn btn-outline-primary">
                                    <i class="ti ti-download me-1"></i>Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
