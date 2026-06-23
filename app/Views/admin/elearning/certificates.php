<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Issued Certificates</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Manage Issued Certificates</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Participant</th>
                                        <th>Course</th>
                                        <th>Issued At</th>
                                        <th>Certificate Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($certificates)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No certificates issued yet.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($certificates as $cert): ?>
                                            <tr>
                                                <td><span class="fw-semibold text-primary"><?= esc($cert['certificate_code']) ?></span></td>
                                                <td>
                                                    <?= esc($cert['full_name'] ?: $cert['username']) ?>
                                                </td>
                                                <td><?= esc($cert['course_title']) ?></td>
                                                <td><?= date('M j, Y H:i', strtotime($cert['issued_at'])) ?></td>
                                                <td>
                                                    <?php if (!empty($cert['manual_certificate'])): ?>
                                                        <span class="badge bg-success-transparent">Manual Override</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-info-transparent">System Generated</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" class="btn btn-sm btn-icon btn-secondary-light" target="_blank" title="Download Current">
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-primary-light" data-bs-toggle="modal" data-bs-target="#uploadModal<?= $cert['id'] ?>" title="Upload Manual Certificate">
                                                        Upload Manual
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Upload Modal -->
                                            <div class="modal fade" id="uploadModal<?= $cert['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="<?= base_url('admin/elearning/certificates/upload-manual/' . $cert['id']) ?>" method="POST" enctype="multipart/form-data">
                                                            <?= csrf_field() ?>
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Upload Manual Certificate</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-muted mb-4">
                                                                    Uploading a manual PDF certificate here will override the system-generated certificate for <strong><?= esc($cert['full_name'] ?: $cert['username']) ?></strong>.
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Select PDF Certificate</label>
                                                                    <input type="file" class="form-control" name="manual_certificate" accept="application/pdf" required>
                                                                </div>
                                                                <?php if (!empty($cert['manual_certificate'])): ?>
                                                                    <div class="alert alert-warning mt-3">
                                                                        <i class="ti ti-alert-triangle me-2"></i> This user already has a manual certificate. Uploading a new one will replace it.
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Upload & Override</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>