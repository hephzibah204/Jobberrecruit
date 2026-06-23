<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('styles') ?>
<style>
    .badge.cursor-pointer {
        cursor: pointer !important;
    }

    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- Page Header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Jobs Management</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Jobs</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Jobs</h6>
                            <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                        </div>
                        <div class="avatar bg-primary-transparent">
                            <i class="ti ti-briefcase fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Pending Approval</h6>
                            <h4 class="mb-0 text-warning"><?= number_format($stats['pending_approval']) ?></h4>
                        </div>
                        <div class="avatar bg-warning-transparent">
                            <i class="ti ti-hourglass fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Active Jobs</h6>
                            <h4 class="mb-0 text-success"><?= number_format($stats['open']) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent">
                            <i class="ti ti-circle-check fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-secondary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Closed Jobs</h6>
                            <h4 class="mb-0"><?= number_format($stats['closed']) ?></h4>
                        </div>
                        <div class="avatar bg-secondary-transparent">
                            <i class="ti ti-circle-x fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-danger bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Rejected</h6>
                            <h4 class="mb-0 text-danger"><?= number_format($stats['rejected']) ?></h4>
                        </div>
                        <div class="avatar bg-danger-transparent">
                            <i class="ti ti-x fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card custom-card mb-3">
        <div class="card-body d-flex gap-2 flex-wrap align-items-center">
            <select class="form-select w-auto" id="filter-status">
                <option value="">All Status</option>
                <option value="pending_approval">Pending Approval</option>
                <option value="open">Open / Active</option>
                <option value="closed">Closed</option>
                <option value="rejected">Rejected</option>
            </select>

            <input type="date" class="form-control w-auto" id="filter-from" placeholder="From Date">
            <input type="date" class="form-control w-auto" id="filter-to" placeholder="To Date">

            <button class="btn btn-primary" onclick="applyFilters()">
                <i class="ti ti-filter me-1"></i>Apply Filters
            </button>

            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="ti ti-refresh me-1"></i>Reset
            </button>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card custom-card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Job Postings</h5>
        </div>
        <div class="card-body p-0" id="jobs-table-wrapper">
            <?= view('admin/partials/jobs_results', [
                'jobs'  => $jobs,
                'pager' => $pager
            ]) ?>
        </div>
    </div>

</div>

<!-- Change Job Status Modal -->
<div class="modal fade" id="jobStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Job Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-job-id">
                <input type="hidden" id="modal-current-status">
                <input type="hidden" id="modal-current-admin-status">

                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <p class="fw-semibold" id="modal-job-title"></p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Status</label>
                    <p><span class="badge bg-secondary" id="modal-current-badge"></span></p>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Status</label>
                    <select class="form-select" id="modal-new-status">
                        <!-- options injected dynamically -->
                    </select>
                </div>

                <div class="alert alert-warning small mb-0">
                    <i class="ti ti-info-circle me-1"></i>
                    This action is performed by an administrator and affects job visibility.
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirmStatusChange">
                    Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="delete-job-title"></strong>?</p>
                <p class="text-danger">This action cannot be undone. All applications for this job will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" id="confirmDelete">Delete Job</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const statusTransitions = {
        pending_approval: ['open', 'rejected'],
        open: ['closed', 'pending_approval'],
        closed: ['open', 'pending_approval'],
        rejected: ['open', 'pending_approval', 'closed']
    };

    const statusLabels = {
        pending_approval: 'Pending Approval',
        open: 'Open / Active',
        closed: 'Closed',
        rejected: 'Rejected'
    };

    const adminStatusColors = {
        pending: 'warning',
        approved: 'success',
        rejected: 'danger'
    };

    function applyFilters(page = 1) {
        const params = new URLSearchParams({
            status: document.getElementById('filter-status').value,
            from: document.getElementById('filter-from').value,
            to: document.getElementById('filter-to').value,
            page: page
        });

        fetch("<?= base_url('admin/jobs/filter') ?>?" + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('jobs-table-wrapper').innerHTML = html;
            });
    }

    function resetFilters() {
        document.getElementById('filter-status').value = '';
        document.getElementById('filter-from').value = '';
        document.getElementById('filter-to').value = '';
        applyFilters();
    }

    // Pagination handler
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();
        const url = new URL(link.href);
        const page = url.searchParams.get('page') || 1;

        applyFilters(page);
        window.history.pushState({}, '', link.href);
    });

    function toggleStatus(jobId, currentStatus, currentAdminStatus) {
        let allowedStatuses = [];

        if (currentAdminStatus === 'pending') {
            allowedStatuses = ['open', 'rejected'];
        } else {
            allowedStatuses = statusTransitions[currentStatus] || [];
        }

        if (!allowedStatuses.length) {
            toastr.warning('This job status cannot be changed.');
            return;
        }

        // Get job title from the row
        const row = document.querySelector(`button[data-id="${jobId}"]`).closest('tr');
        const jobTitle = row.querySelector('td:nth-child(2) .fw-semibold').innerText;

        $('#modal-job-id').val(jobId);
        $('#modal-current-status').val(currentStatus);
        $('#modal-current-admin-status').val(currentAdminStatus);
        $('#modal-job-title').text(jobTitle);

        const currentBadge = document.getElementById('modal-current-badge');
        if (currentAdminStatus === 'pending') {
            currentBadge.className = 'badge bg-warning';
            currentBadge.innerHTML = '<i class="ti ti-hourglass me-1"></i> Pending Approval';
        } else {
            currentBadge.className = `badge bg-${currentStatus === 'open' ? 'success' : 'secondary'}`;
            currentBadge.innerHTML = currentStatus === 'open' ? 'Open / Active' : 'Closed';
        }

        const $select = $('#modal-new-status');
        $select.empty();

        allowedStatuses.forEach(status => {
            $select.append(`<option value="${status}">${statusLabels[status]}</option>`);
        });

        $('#jobStatusModal').modal('show');
    }

    $('#confirmStatusChange').on('click', function() {
        const jobId = $('#modal-job-id').val();
        const newStatus = $('#modal-new-status').val();

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

        $.ajax({
            url: `<?= base_url('admin/jobs/update-status') ?>/${jobId}`,
            method: 'POST',
            data: {
                status: newStatus,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    toastr.success('Job status updated successfully');
                    $('#jobStatusModal').modal('hide');
                    applyFilters();
                } else {
                    toastr.error(resp.message || 'Failed to update status');
                }
            },
            error: function() {
                toastr.error('Server error occurred');
            },
            complete: function() {
                $('#confirmStatusChange').prop('disabled', false).html('Update Status');
            }
        });
    });

    // Delete job handler
    let deleteJobId = null;
    $('.delete-job').on('click', function() {
        deleteJobId = $(this).data('id');
        const jobTitle = $(this).data('title');
        $('#delete-job-title').text(jobTitle);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');

        $.ajax({
            url: '<?= base_url("admin/jobs/delete") ?>',
            method: 'POST',
            data: {
                job_id: deleteJobId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    toastr.success(resp.message);
                    $('#deleteModal').modal('hide');
                    applyFilters();
                } else {
                    toastr.error(resp.message || 'Failed to delete job');
                }
            },
            error: function() {
                toastr.error('Server error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('Delete Job');
            }
        });
    });

    function toggleVerification(jobId, isVerified) {
        const action = isVerified ? 'unverify' : 'verify';
        const confirmMsg = isVerified ? 
            'Remove verification badge from this job?' : 
            'Mark this job as verified? This adds a trust badge to the listing.';

        if (confirm(confirmMsg)) {
            $.ajax({
                url: `<?= base_url('admin/jobs') ?>/${action}/${jobId}`,
                method: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.success) {
                        toastr.success(resp.message);
                        applyFilters();
                    } else {
                        toastr.error(resp.message || `Failed to ${action} job`);
                    }
                },
                error: function() {
                    toastr.error('Server error occurred');
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>