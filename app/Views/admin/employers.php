<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>
<?php
$employerStatusUrl = static fn ($status) => base_url('admin/employers') . '?' . http_build_query([
    'status' => $status,
    'q' => $search ?? '',
    'industries' => $selectedIndustries ?? [],
]);
?>

<div class="container-fluid page-container main-body-container">

    <!-- PAGE HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Employers</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active">Employers</li>
            </ol>
            <a href="<?= base_url('admin/employers/export') ?>" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- VERIFICATION STATUS TABS -->
    <div class="card custom-card mb-3">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-header">
                <li class="nav-item">
                    <a href="<?= esc($employerStatusUrl('all'), 'attr') ?>"
                        class="nav-link <?= $currentStatus == 'all' ? 'active' : '' ?>">
                        All Employers
                        <span class="badge bg-dark ms-1"><?= $verificationStats['total'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= esc($employerStatusUrl('pending'), 'attr') ?>"
                        class="nav-link <?= $currentStatus == 'pending' ? 'active' : '' ?>">
                        <i class="ti ti-hourglass-high text-warning"></i>
                        Pending Verification
                        <span class="badge bg-warning ms-1"><?= $verificationStats['pending'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= esc($employerStatusUrl('unverified'), 'attr') ?>"
                        class="nav-link <?= $currentStatus == 'unverified' ? 'active' : '' ?>">
                        <i class="ti ti-file-off text-muted"></i>
                        Unverified (No Docs)
                        <span class="badge bg-secondary ms-1"><?= $verificationStats['unverified'] ?? 0 ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= esc($employerStatusUrl('verified'), 'attr') ?>"
                        class="nav-link <?= $currentStatus == 'verified' ? 'active' : '' ?>">
                        <i class="ti ti-checkbox text-success"></i>
                        Verified
                        <span class="badge bg-info ms-1"><?= $verificationStats['verified'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= esc($employerStatusUrl('rejected'), 'attr') ?>"
                        class="nav-link <?= $currentStatus == 'rejected' ? 'active' : '' ?>">
                        <i class="ti ti-x text-danger"></i>
                        Rejected
                        <span class="badge bg-purple ms-1"><?= $verificationStats['rejected'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= esc($employerStatusUrl('document_required'), 'attr') ?>"
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
                                    <?= in_array((int) $industry->id, $selectedIndustries ?? [], true) ? 'checked' : '' ?>
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
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="mb-0">
                        <span id="employer-result-count" aria-live="polite"><?= number_format($total) ?></span>
                        <span class="fw-normal fs-14">Employers found</span>
                    </h5>
                    <form id="employer-search-form" method="get" action="<?= base_url('admin/employers') ?>" class="d-flex flex-wrap gap-2 align-items-end">
                        <input type="hidden" name="status" value="<?= esc($currentStatus, 'attr') ?>">
                        <div>
                            <label for="employer-search" class="form-label mb-1">Search employers</label>
                            <input id="employer-search" name="q" type="search" maxlength="200" class="form-control" value="<?= esc($search ?? '', 'attr') ?>" placeholder="Company, email, phone, or ID">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="button" id="employer-search-clear" class="btn btn-light">Clear</button>
                    </form>
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
                        'Content-Type': 'application/json',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
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
                        'Content-Type': 'application/json',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
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
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            })
            .catch(error => {
                body.innerHTML = '<div class="text-center py-5"><i class="ti ti-alert-circle fs-48 text-danger mb-3 d-block"></i><h6 class="text-danger">Failed to load documents</h6><p class="text-muted small">Please try again later.</p></div>';
                console.error(error);
            });
    };

    let employerRequest;
    document.getElementById('employer-search-form').addEventListener('submit', function(event) {
        event.preventDefault();
        applyIndustryFilter();
    });
    document.getElementById('employer-search-clear').addEventListener('click', function() {
        document.getElementById('employer-search').value = '';
        applyIndustryFilter();
    });

    // Apply search and filters together, before server-side pagination.
    function applyIndustryFilter(page = 1) {
        employerRequest?.abort();
        employerRequest = new AbortController();
        const params = new URLSearchParams();
        params.append('page', page);
        params.append('status', '<?= $currentStatus ?>');
        params.set('q', document.getElementById('employer-search').value.trim());

        document.querySelectorAll('.industry-filter:checked').forEach(el => {
            params.append('industries[]', el.value);
        });

        fetch("<?= base_url('admin/employers/filter') ?>?" + params.toString(), {
                signal: employerRequest.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Unable to load employers');
                return res.text();
            })
            .then(html => {
                document.getElementById('employers-table-wrapper').innerHTML = html;
                const count = document.querySelector('[data-employer-total]')?.dataset.employerTotal;
                if (count !== undefined) document.getElementById('employer-result-count').textContent = Number(count).toLocaleString();
                history.replaceState({}, '', "<?= base_url('admin/employers') ?>?" + params.toString());
                document.querySelectorAll('.nav-tabs-header a').forEach(link => {
                    const url = new URL(link.href);
                    url.searchParams.set('q', params.get('q'));
                    url.searchParams.delete('page');
                    url.searchParams.delete('industries[]');
                    params.getAll('industries[]').forEach(id => url.searchParams.append('industries[]', id));
                    link.href = url.toString();
                });
            })
            .catch(error => {
                if (error.name !== 'AbortError') toastr.error('Could not load employers. Please try again.');
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

    // View documents alias
    function viewDocumentss(employerId) {
        viewDocuments(employerId);
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
        const link = e.target.closest('#employers-table-wrapper .pagination a');
        if (!link) return;

        e.preventDefault();

        applyIndustryFilter(new URL(link.href).searchParams.get('page') || 1);
    });

    // Delete Employer
    function deleteEmployer(employerId) {
        if (confirm('Are you sure you want to delete this employer? This action cannot be undone. All related jobs, applications, and documents will be permanently deleted.')) {
            fetch('<?= base_url("admin/employers/delete") ?>/' + employerId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({ '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
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
