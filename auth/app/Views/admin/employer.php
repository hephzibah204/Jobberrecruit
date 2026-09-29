<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- Header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">
                <?= esc($employer->company_name) ?>
            </h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/employers') ?>">Employers</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>

    <div class="row">

        <!-- Main -->
        <div class="col-xl-9">

            <!-- Employer Card -->
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex gap-3 align-items-start">
                        <span class="avatar avatar-xl">
                            <?php if ($employer->logo): ?>
                                <img src="<?= base_url($employer->logo) ?>">
                            <?php else: ?>
                                <?= strtoupper(substr($employer->company_name, 0, 1)) ?>
                            <?php endif ?>
                        </span>

                        <div class="flex-fill">
                            <h4 class="fw-semibold mb-1">
                                <?= esc($employer->company_name) ?>
                                <?php if ($employer->is_verified): ?>
                                    <span class="badge bg-success-transparent ms-2">Verified</span>
                                <?php endif ?>
                                <?php if ($hasUnlimitedAccess): ?>
                                    <span class="badge bg-info-transparent ms-2">
                                        <i class="ti ti-infinity"></i> Unlimited Access
                                    </span>
                                <?php endif ?>
                            </h4>

                            <div class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i>
                                <?= esc($employer->state_name . ' State' ?? '—') ?>
                            </div>

                            <!-- Industries -->
                            <div class="mb-3">
                                <?php foreach ($industries as $ind): ?>
                                    <span class="badge bg-primary-transparent">
                                        <?= esc($ind->name) ?>
                                    </span>
                                <?php endforeach ?>
                            </div>

                            <p class="mb-0 text-muted">
                                <?= esc($employer->description ?? 'No company description provided.') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAC Certificate Section -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ti ti-certificate me-2"></i>CAC Certificate Verification
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($cacDocument): ?>
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-2">
                                    <strong>Document:</strong>
                                    <a href="<?= base_url($cacDocument['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-file-text me-1"></i> View CAC Certificate
                                    </a>
                                </p>
                                <p class="mb-2">
                                    <strong>Uploaded:</strong> <?= date('M d, Y H:i', strtotime($cacDocument['uploaded_at'])) ?>
                                </p>
                                <p class="mb-2">
                                    <strong>File Name:</strong> <?= esc($cacDocument['file_name']) ?>
                                </p>
                                <p class="mb-2">
                                    <strong>File Size:</strong> <?= number_format($cacDocument['file_size'] / 1024, 2) ?> KB
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <?php
                                $statusBadges = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $badgeClass = $statusBadges[$cacDocument['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badgeClass ?>-transparent fs-12 p-2">
                                    <i class="ti ti-<?= $cacDocument['status'] == 'approved' ? 'check' : ($cacDocument['status'] == 'pending' ? 'clock' : 'x') ?> me-1"></i>
                                    <?= ucfirst($cacDocument['status']) ?>
                                </span>

                                <?php if ($cacDocument['status'] == 'pending'): ?>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-success me-1" onclick="verifyDocument(<?= $cacDocument['id'] ?>)">
                                            <i class="ti ti-check"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectDocument(<?= $cacDocument['id'] ?>)">
                                            <i class="ti ti-x"></i> Reject
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($cacDocument['status'] == 'rejected' && $employer->rejection_reason): ?>
                                    <div class="alert alert-danger mt-3 mb-0">
                                        <strong>Rejection Reason:</strong><br>
                                        <?= esc($employer->rejection_reason) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="ti ti-file-text fs-48 text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">No CAC Certificate Uploaded</h6>
                            <p class="text-muted small">This employer hasn't uploaded their CAC certificate yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Jobs -->
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Recent Job Openings</div>
                    <span class="badge bg-primary-transparent">
                        <?= (int) $employer->total_jobs ?> Jobs
                    </span>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Posted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($jobs): ?>
                                <?php foreach ($jobs as $job): ?>
                                    <tr>
                                        <td><?= esc($job->title) ?></td>
                                        <td><?= ucfirst($job->job_type) ?></td>
                                        <td><?= date('M d, Y', strtotime($job->created_at)) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        No jobs posted yet
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-xl-3">

            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Verification Status</div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <?php if ($employer->is_verified): ?>
                            <span class="badge bg-success fs-14 p-2">
                                <i class="ti ti-check-circle me-1"></i> Verified Company
                            </span>
                        <?php elseif ($cacDocument && $cacDocument->status == 'pending'): ?>
                            <span class="badge bg-warning fs-14 p-2">
                                <i class="ti ti-clock me-1"></i> Pending Verification
                            </span>
                        <?php elseif ($cacDocument && $cacDocument->status == 'rejected'): ?>
                            <span class="badge bg-danger fs-14 p-2">
                                <i class="ti ti-x-circle me-1"></i> Verification Failed
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-14 p-2">
                                <i class="ti ti-alert-circle me-1"></i> Not Verified
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($employer->verified_at): ?>
                        <hr>
                        <p class="mb-1"><strong>Verified On:</strong></p>
                        <p class="text-muted"><?= date('M d, Y H:i', strtotime($employer->verified_at)) ?></p>
                    <?php endif; ?>

                    <?php if ($employer->verified_by): ?>
                        <p class="mb-1"><strong>Verified By:</strong></p>
                        <p class="text-muted">Admin ID: <?= $employer->verified_by ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Contact Info</div>
                </div>
                <div class="card-body">
                    <p><strong>Contact:</strong><br><?= esc($employer->contact_name ?? '—') ?></p>
                    <p><strong>Email:</strong><br><?= esc($employer->contact_email ?? '—') ?></p>
                    <p><strong>Phone:</strong><br><?= esc($employer->contact_phone ?? '—') ?></p>
                    <p><strong>Website:</strong><br>
                        <?php if ($employer->website): ?>
                            <a href="<?= esc($employer->website) ?>" target="_blank">
                                <?= esc($employer->website) ?>
                            </a>
                        <?php else: ?>
                            —
                        <?php endif ?>
                    </p>
                </div>
            </div>

            <?php if ($hasUnlimitedAccess): ?>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Unlimited Access</div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success mb-0">
                            <i class="ti ti-infinity me-2"></i>
                            <strong>Enterprise Plan</strong><br>
                            This employer has unlimited access to all features.
                            <?php if ($employer->unlimited_until): ?>
                                <hr class="my-2">
                                <small>Expires: <?= date('M d, Y', strtotime($employer->unlimited_until)) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Unlimited Access Toggle (Admin Action) -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Manage Access</div>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="unlimitedAccessToggle"
                            <?= $hasUnlimitedAccess ? 'checked' : '' ?>
                            onchange="toggleUnlimitedAccess(<?= $employer->id ?>, this.checked)">
                        <label class="form-check-label" for="unlimitedAccessToggle">
                            <i class="ti ti-infinity"></i> Unlimited Access
                        </label>
                    </div>

                    <div id="unlimitedUntilDiv" style="display: <?= $hasUnlimitedAccess ? 'block' : 'none' ?>;">
                        <label class="form-label">Expiry Date (Optional)</label>
                        <input type="datetime-local" id="unlimitedUntil" class="form-control mb-2"
                            value="<?= $employer->unlimited_until ? date('Y-m-d\TH:i', strtotime($employer->unlimited_until)) : '' ?>">
                        <button class="btn btn-sm btn-primary w-100" onclick="updateUnlimitedExpiry(<?= $employer->id ?>)">
                            Update Expiry
                        </button>
                    </div>

                    <hr>

                    <button class="btn btn-danger w-100" onclick="deleteEmployer(<?= $employer->id ?>)">
                        <i class="ti ti-trash me-2"></i> Delete Employer
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Verify CAC Document
    function verifyDocument(documentId) {
        if (confirm('Approve this CAC certificate?')) {
            fetch('<?= base_url("admin/employers/documents/verify") ?>/' + documentId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        document_id: documentId
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        toastr.success('Document verified successfully');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(res.message);
                    }
                });
        }
    }

    // Reject CAC Document
    function rejectDocument(documentId) {
        let reason = prompt('Please provide a reason for rejection:');
        if (reason) {
            fetch('<?= base_url("admin/employers/documents/reject") ?>/' + documentId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        document_id: documentId,
                        reason: reason
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        toastr.success('Document rejected');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(res.message);
                    }
                });
        }
    }

    // Toggle Unlimited Access
    function toggleUnlimitedAccess(employerId, isChecked) {
        fetch('<?= base_url("admin/employers/toggle-unlimited-access") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    employer_id: employerId,
                    enabled: isChecked
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    document.getElementById('unlimitedUntilDiv').style.display = isChecked ? 'block' : 'none';
                } else {
                    toastr.error(res.message);
                    document.getElementById('unlimitedAccessToggle').checked = !isChecked;
                }
            });
    }

    // Update Unlimited Access Expiry
    function updateUnlimitedExpiry(employerId) {
        let until = document.getElementById('unlimitedUntil').value;

        fetch('<?= base_url("admin/employers/update-unlimited-expiry") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    employer_id: employerId,
                    unlimited_until: until
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success('Expiry date updated');
                } else {
                    toastr.error(res.message);
                }
            });
    }

    // Delete Employer
    function deleteEmployer(employerId) {
        if (confirm('Are you sure you want to delete this employer? This action cannot be undone. All related jobs, applications, and documents will be permanently deleted.')) {
            fetch('<?= base_url("admin/employers/delete") ?>/' + employerId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        toastr.success(res.message);
                        setTimeout(() => window.location.href = '<?= base_url("admin/employers") ?>', 1500);
                    } else {
                        toastr.error(res.message);
                    }
                })
                .catch(err => {
                    toastr.error('Error deleting employer');
                    console.error(err);
                });
        }
    }
</script>

<?= $this->endSection() ?>