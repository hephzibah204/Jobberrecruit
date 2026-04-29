<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">
                        <i class="ti ti-certificate text-primary me-2"></i>
                        Upload CAC Certificate
                    </h4>
                </div>

                <div class="card-body">
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($hasDocument && $existingDoc): ?>
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Existing Document:</strong> You have already uploaded a CAC certificate.
                            <?php if ($existingDoc->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending Review</span>
                            <?php elseif ($existingDoc->status == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($existingDoc->status == 'rejected'): ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                            <br>
                            <a href="<?= base_url($existingDoc->file_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="ti ti-eye"></i> View Current Document
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Why do we need this?</strong><br>
                        To verify your business legitimacy, we require your CAC (Corporate Affairs Commission) certificate.
                        This helps us maintain a trusted marketplace for all users.
                    </div>

                    <form action="<?= base_url('employer/profile/process-document-upload') ?>"
                        method="POST"
                        enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                CAC Certificate <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                name="cac_document"
                                class="form-control <?= session('errors.cac_document') ? 'is-invalid' : '' ?>"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required>
                            <small class="text-muted">
                                Accepted formats: PDF, JPG, JPEG, PNG (Max size: 5MB)
                            </small>
                            <?php if (session('errors.cac_document')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.cac_document') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="confirm" required>
                                <label class="form-check-label" for="confirm">
                                    I confirm that the uploaded document is authentic and belongs to my company.
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ti ti-upload me-2"></i>
                                <?= $hasDocument ? 'Replace Certificate' : 'Upload Certificate' ?>
                            </button>
                            <a href="<?= base_url('employer/profile') ?>" class="btn btn-light">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>