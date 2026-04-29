<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- PAGE HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Employers</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active">Employers</li>
            </ol>
        </div>
    </div>

    <!-- VERIFICATION STATUS TABS -->
    <div class="card custom-card mb-3">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-header">
                <li class="nav-item">
                    <a href="<?= base_url('admin/employers?status=all') ?>"
                        class="nav-link <?= $currentStatus == 'all' ? 'active' : '' ?>">
                        All Employers
                        <span class="badge bg-dark ms-1"><?= $verificationStats['total'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/employers?status=pending') ?>"
                        class="nav-link <?= $currentStatus == 'pending' ? 'active' : '' ?>">
                        <i class="ti ti-hourglass-high text-warning"></i>
                        Pending Verification
                        <?php if ($verificationStats['pending'] > 0): ?>
                            <span class="badge bg-warning ms-1"><?= $verificationStats['pending'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/employers?status=verified') ?>"
                        class="nav-link <?= $currentStatus == 'verified' ? 'active' : '' ?>">
                        <i class="ti ti-checkbox text-success"></i>
                        Verified
                        <span class="badge bg-info ms-1"><?= $verificationStats['verified'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/employers?status=rejected') ?>"
                        class="nav-link <?= $currentStatus == 'rejected' ? 'active' : '' ?>">
                        <i class="ti ti-x text-danger"></i>
                        Rejected
                        <span class="badge bg-purple ms-1"><?= $verificationStats['rejected'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/employers?status=document_required') ?>"
                        class="nav-link <?= $currentStatus == 'document_required' ? 'active' : '' ?>">
                        <i class="ti ti-file-text text-info"></i>
                        Documents Required
                        <span class="badge bg-danger ms-1"><?= $verificationStats['document_required'] ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-xxl-3">
            <div class="card custom-card">
                <div class="card-body p-0">
                    <div class="px-3 py-4 border-bottom">
                        <h6 class="fw-medium mb-3">Industries</h6>
                        <?php foreach ($industryCounts as $industry): ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input industry-filter"
                                    type="checkbox"
                                    value="<?= esc($industry->id) ?>">
                                <label class="form-check-label">
                                    <?= esc($industry->name) ?>
                                </label>
                                <span class="badge bg-light text-default float-end">
                                    <?= number_format($industry->total) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-xxl-9">
            <div class="card custom-card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?= number_format($total) ?>
                        <span class="fw-normal fs-14">Employers found</span>
                    </h5>
                </div>
            </div>

            <div id="employers-table-wrapper">
                <?= view('admin/partials/employers_results', [
                    'employers' => $employers,
                    'pager' => $pager,
                    'currentStatus' => $currentStatus
                ]) ?>
            </div>
        </div>
    </div>
</div>

<!-- VERIFICATION MODAL -->
<div class="modal fade" id="verificationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Employer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="verificationForm">
                <?= csrf_field() ?>
                <input type="hidden" name="employer_id" id="verify_employer_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Verify Employer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- REJECTION MODAL -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Employer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectionForm">
                <?= csrf_field() ?>
                <input type="hidden" name="employer_id" id="reject_employer_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason *</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                        <small class="text-muted">This will be sent to the employer</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Employer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DOCUMENTS MODAL -->
<div class="modal fade" id="documentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employer Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="documentsModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading documents...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Global functions for document verification
    window.verifyDocument = function(documentId) {
        if (confirm('Approve this document?')) {
            fetch('<?= base_url("admin/documents/verify") ?>/' + documentId, {
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
                })
                .catch(error => {
                    toastr.error('An error occurred');
                    console.error(error);
                });
        }
    };

    window.rejectDocument = function(documentId) {
        if (confirm('Reject this document?')) {
            fetch('<?= base_url("admin/documents/reject") ?>/' + documentId, {
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
                        toastr.success('Document rejected');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(res.message);
                    }
                })
                .catch(error => {
                    toastr.error('An error occurred');
                    console.error(error);
                });
        }
    };

    // View documents function
    window.viewDocuments = function(employerId) {
        const modal = new bootstrap.Modal(document.getElementById('documentsModal'));
        const body = document.getElementById('documentsModalBody');

        body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading documents...</p></div>';
        modal.show();

        fetch(`<?= base_url('admin/employers/documents/') ?>${employerId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                body.innerHTML = html;
                // Re-initialize feather icons if needed
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            })
            .catch(error => {
                body.innerHTML = '<div class="text-center py-5"><i class="ti ti-alert-circle fs-48 text-danger mb-3 d-block"></i><h6 class="text-danger">Failed to load documents</h6><p class="text-muted small">Please try again later.</p></div>';
                console.error(error);
            });
    };

    // Apply industry filter
    function applyIndustryFilter(page = 1) {
        const params = new URLSearchParams();
        params.append('page', page);
        params.append('status', '<?= $currentStatus ?>');

        document.querySelectorAll('.industry-filter:checked').forEach(el => {
            params.append('industries[]', el.value);
        });

        fetch("<?= base_url('admin/employers/filter') ?>?" + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('employers-table-wrapper').innerHTML = html;
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
    }

    // Verification form submission
    document.getElementById('verificationForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Verifying...';

        const employerId = document.getElementById('verify_employer_id').value;

        fetch(`<?= base_url('admin/employers/verify/') ?>${employerId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(res.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Verify Employer';
                }
            });
    });

    // Rejection form submission
    document.getElementById('rejectionForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Rejecting...';

        const employerId = document.getElementById('reject_employer_id').value;

        fetch(`<?= base_url('admin/employers/reject/') ?>${employerId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(res.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Reject Employer';
                }
            });
    });

    // View documents
    function viewDocumentss(employerId) {
        const modal = new bootstrap.Modal(document.getElementById('documentsModal'));
        const body = document.getElementById('documentsModalBody');

        body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading documents...</p></div>';
        modal.show();

        fetch(`<?= base_url('admin/employers/documents/') ?>${employerId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                body.innerHTML = html;
            });
    }

    // Open verification modal
    function openVerifyModal(employerId) {
        document.getElementById('verify_employer_id').value = employerId;
        new bootstrap.Modal(document.getElementById('verificationModal')).show();
    }

    // Open rejection modal
    function openRejectModal(employerId) {
        document.getElementById('reject_employer_id').value = employerId;
        new bootstrap.Modal(document.getElementById('rejectionModal')).show();
    }

    // Event listeners
    document.querySelectorAll('.industry-filter').forEach(el => {
        el.addEventListener('change', () => applyIndustryFilter());
    });

    // Pagination with AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();

        fetch(link.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('employers-table-wrapper').innerHTML = html;
                history.pushState({}, '', link.href);
            });
    });

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
                        setTimeout(() => location.reload(), 1000);
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