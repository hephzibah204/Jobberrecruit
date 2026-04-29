<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Applications</h4>
                <h6>View and manage applications for your job postings</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload();">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                    <i class="ti ti-chevron-up"></i>
                </a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="<?= site_url('employer/applications/export') ?>" class="btn btn-success me-2">
                <i class="ti ti-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total</h6>
                            <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                        </div>
                        <div class="avatar bg-primary-transparent">
                            <i class="ti ti-users fs-20"></i>
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
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h4 class="mb-0 text-warning"><?= number_format($stats['pending']) ?></h4>
                        </div>
                        <div class="avatar bg-warning-transparent">
                            <i class="ti ti-hourglass fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Reviewed</h6>
                            <h4 class="mb-0 text-info"><?= number_format($stats['reviewed']) ?></h4>
                        </div>
                        <div class="avatar bg-info-transparent">
                            <i class="ti ti-eye fs-20"></i>
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
                            <h6 class="text-muted mb-1">Shortlisted</h6>
                            <h4 class="mb-0 text-success"><?= number_format($stats['shortlisted']) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent">
                            <i class="ti ti-star fs-20"></i>
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
        <div class="col-xl-2 col-md-4">
            <div class="card custom-card stats-card bg-dark bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Hired</h6>
                            <h4 class="mb-0"><?= number_format($stats['hired']) ?></h4>
                        </div>
                        <div class="avatar bg-dark-transparent">
                            <i class="ti ti-user-check fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Card -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

            <!-- SEARCH -->
            <div class="search-set">
                <div class="search-input">
                    <input type="text" id="search-input" class="form-control" placeholder="Search applications...">
                    <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="d-flex table-dropdown align-items-center gap-2 flex-wrap">

                <!-- Filter by Job -->
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle btn btn-white btn-md" data-bs-toggle="dropdown">
                        Filter by Job
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <li><a class="dropdown-item" data-filter="all" data-type="job">All Jobs</a></li>
                        <?php foreach ($jobs as $job): ?>
                            <li>
                                <a class="dropdown-item" data-filter="job-<?= $job->id ?>" data-type="job">
                                    <?= esc($job->title) ?> (<?= $job->application_count ?>)
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Filter by Status -->
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle btn btn-white btn-md" data-bs-toggle="dropdown">
                        Filter by Status
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <li><a class="dropdown-item" data-filter="all" data-type="status">All Status</a></li>
                        <li><a class="dropdown-item" data-filter="pending" data-type="status">Pending</a></li>
                        <li><a class="dropdown-item" data-filter="reviewed" data-type="status">Reviewed</a></li>
                        <li><a class="dropdown-item" data-filter="shortlisted" data-type="status">Shortlisted</a></li>
                        <li><a class="dropdown-item" data-filter="rejected" data-type="status">Rejected</a></li>
                        <li><a class="dropdown-item" data-filter="hired" data-type="status">Hired</a></li>
                    </ul>
                </div>

                <!-- Bulk Actions -->
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle btn btn-white btn-md" data-bs-toggle="dropdown">
                        Bulk Actions
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <li><a class="dropdown-item" href="#" id="bulk-shortlist">Shortlist Selected</a></li>
                        <li><a class="dropdown-item" href="#" id="bulk-review">Mark as Reviewed</a></li>
                        <li><a class="dropdown-item" href="#" id="bulk-reject">Reject Selected</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#" id="bulk-delete">Delete Selected</a></li>
                    </ul>
                </div>

                <!-- Refresh Button -->
                <button class="btn btn-outline-secondary btn-md" onclick="location.reload();">
                    <i class="ti ti-refresh"></i>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table datatable" id="applications-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="no-sort">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr data-id="<?= $app->id ?>"
                                data-job="job-<?= $app->job_id ?>"
                                data-status="<?= strtolower($app->status) ?>">
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox" class="app-checkbox" data-id="<?= $app->id ?>">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td class="fw-semibold">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <?php if ($app->avatar): ?>
                                                <img src="<?= base_url($app->avatar) ?>" class="rounded-circle" width="35" height="35">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    <i class="ti ti-user fs-18 text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <?= esc($app->first_name . ' ' . $app->last_name) ?>
                                            <?php if ($app->is_guest): ?>
                                                <span class="badge bg-secondary-transparent ms-1" data-bs-toggle="tooltip" title="Guest Applicant - No account">
                                                    <i class="ti ti-user-off"></i> Guest
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($app->job_title) ?></td>
                                <td><?= esc($app->email) ?></td>
                                <td><?= esc($app->phone) ?></td>
                                <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>
                                <td>
                                    <?= ucwords($app->status) ?>
                                </td>
                                <td class="action-table-data">
                                    <div class="edit-delete-action d-flex align-items-center gap-2">
                                        <!-- VIEW -->
                                        <a href="<?= site_url('employer/applications/view/' . $app->id) ?>" class="p-2" data-bs-toggle="tooltip" title="View Details">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>

                                        <!-- DOWNLOAD CV -->
                                        <?php if ($app->cv_path): ?>
                                            <a href="<?= base_url($app->cv_path) ?>" class="p-2" download data-bs-toggle="tooltip" title="Download CV">
                                                <i data-feather="download" class="text-primary"></i>
                                            </a>
                                        <?php endif; ?>

                                        <!-- DELETE -->
                                        <a href="javascript:void(0);" class="p-2 text-danger delete-app" data-id="<?= $app->id ?>" data-bs-toggle="tooltip" title="Delete">
                                            <i data-feather="trash-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Delete Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this application? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div class="modal fade" id="bulk-delete-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Bulk Delete Applications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <span id="bulk-count">0</span> selected applications? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="confirm-bulk-delete">Delete All</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 4000
    };

    let dataTable;
    let isInitialLoad = true;

    $(document).ready(function() {
        // Initialize DataTable
        if (!$.fn.DataTable.isDataTable('#applications-table')) {
            dataTable = $('#applications-table').DataTable({
                order: [
                    [5, 'desc']
                ],
                pageLength: 25,
                language: {
                    emptyTable: "No applications found"
                },
                drawCallback: function() {
                    // Re-attach event handlers after table redraw
                    attachStatusChangeHandler();
                    attachDeleteHandlers();
                }
            });
        } else {
            dataTable = $('#applications-table').DataTable();
        }

        // Attach handlers after initial load
        setTimeout(() => {
            attachStatusChangeHandler();
            attachDeleteHandlers();
            isInitialLoad = false;
        }, 100);
    });

    // Status change handler - prevents auto-trigger on load
    function attachStatusChangeHandler() {
        $('.status-select').off('change').on('change', function() {
            // Skip if this is the initial load trigger
            if (isInitialLoad) {
                return;
            }

            const select = $(this);
            const applicationId = select.data('id');
            const status = select.val();
            const originalStatus = select.data('original-status') || select.find('option[selected]').val();

            // Store original status if not set
            if (!select.data('original-status')) {
                select.data('original-status', originalStatus);
            }

            // Show loading state
            select.prop('disabled', true);

            $.ajax({
                url: '<?= site_url("employer/applications/update-status") ?>',
                type: 'POST',
                data: {
                    application_id: applicationId,
                    status: status,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Update the row data-status attribute
                        select.closest('tr').attr('data-status', status);
                        // Update original status
                        select.data('original-status', status);
                        // Update selected attribute
                        select.find('option').removeAttr('selected');
                        select.find('option[value="' + status + '"]').attr('selected', 'selected');

                        // Update stats counts without page reload
                        updateStatsCounts(status, select.closest('tr').data('status'));
                    } else {
                        toastr.error(response.message);
                        // Revert to original status
                        select.val(select.data('original-status'));
                    }
                },
                error: function() {
                    toastr.error('Failed to update status');
                    // Revert to original status
                    select.val(select.data('original-status'));
                },
                complete: function() {
                    select.prop('disabled', false);
                }
            });
        });

        // Store original status for each select
        $('.status-select').each(function() {
            const select = $(this);
            select.data('original-status', select.val());
        });
    }

    // Update stats counts dynamically
    function updateStatsCounts(newStatus, oldStatus) {
        // Decrement old status count
        const oldStatusElement = $(`.stats-card:contains('${oldStatus.charAt(0).toUpperCase() + oldStatus.slice(1)}') .mb-0`);
        if (oldStatusElement.length) {
            let oldCount = parseInt(oldStatusElement.text()) || 0;
            oldStatusElement.text(Math.max(0, oldCount - 1));
        }

        // Increment new status count
        const newStatusElement = $(`.stats-card:contains('${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}') .mb-0`);
        if (newStatusElement.length) {
            let newCount = parseInt(newStatusElement.text()) || 0;
            newStatusElement.text(newCount + 1);
        }

        // Update total if needed (if status changed from/to something)
        if (oldStatus !== newStatus) {
            const totalElement = $(`.stats-card:contains('Total') .mb-0`);
            // Total stays the same, no change needed
        }
    }

    // Delete handlers
    function attachDeleteHandlers() {
        $('.delete-app').off('click').on('click', function() {
            deleteId = $(this).data('id');
            $('#delete-modal').modal('show');
        });
    }

    // Search functionality
    $('#search-input').on('keyup', function() {
        dataTable.search(this.value).draw();
    });

    // Select all checkboxes
    $('#select-all').on('change', function() {
        const isChecked = this.checked;
        $('.app-checkbox').prop('checked', isChecked);
        dataTable.rows().nodes().to$().find('.app-checkbox').prop('checked', isChecked);
    });

    // Filter by Job
    $('.dropdown-menu a[data-filter][data-type="job"]').on('click', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        const filterText = $(this).text();

        $(this).closest('.dropdown').find('.dropdown-toggle').html(filterText + ' <i class="ti ti-chevron-down ms-1"></i>');

        if (filter === 'all') {
            dataTable.column(2).search('').draw();
        } else {
            dataTable.column(2).search(filter.replace('job-', '')).draw();
        }
    });

    // Filter by Status
    $('.dropdown-menu a[data-filter][data-type="status"]').on('click', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        const filterText = $(this).text();

        $(this).closest('.dropdown').find('.dropdown-toggle').html(filterText + ' <i class="ti ti-chevron-down ms-1"></i>');

        if (filter === 'all') {
            dataTable.column(6).search('').draw();
        } else {
            dataTable.column(6).search(filter).draw();
        }
    });

    // Single delete
    let deleteId = null;

    $('#confirm-delete').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');

        $.ajax({
            url: '<?= site_url("employer/applications/delete") ?>/' + deleteId,
            type: "POST",
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: (response) => {
                btn.prop('disabled', false).html('Delete');
                $('#delete-modal').modal('hide');

                if (response.success) {
                    toastr.success(response.message);
                    // Remove the row from table
                    const row = $(`.delete-app[data-id="${deleteId}"]`).closest('tr');
                    dataTable.row(row).remove().draw();

                    // Update stats
                    const status = row.data('status');
                    const statusElement = $(`.stats-card:contains('${status.charAt(0).toUpperCase() + status.slice(1)}') .mb-0`);
                    if (statusElement.length) {
                        let count = parseInt(statusElement.text()) || 0;
                        statusElement.text(Math.max(0, count - 1));
                    }
                    const totalElement = $(`.stats-card:contains('Total') .mb-0`);
                    if (totalElement.length) {
                        let total = parseInt(totalElement.text()) || 0;
                        totalElement.text(Math.max(0, total - 1));
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: () => {
                btn.prop('disabled', false).html('Delete');
                $('#delete-modal').modal('hide');
                toastr.error("Request failed. Try again.");
            }
        });
    });

    // Bulk Shortlist
    $('#bulk-shortlist').on('click', function(e) {
        e.preventDefault();
        const selected = getSelectedIds();

        if (selected.length === 0) {
            toastr.warning('Please select at least one application');
            return;
        }

        if (confirm('Shortlist ' + selected.length + ' application(s)?')) {
            bulkUpdateStatus(selected, 'shortlisted');
        }
    });

    // Bulk Review
    $('#bulk-review').on('click', function(e) {
        e.preventDefault();
        const selected = getSelectedIds();

        if (selected.length === 0) {
            toastr.warning('Please select at least one application');
            return;
        }

        if (confirm('Mark ' + selected.length + ' application(s) as reviewed?')) {
            bulkUpdateStatus(selected, 'reviewed');
        }
    });

    // Bulk Reject
    $('#bulk-reject').on('click', function(e) {
        e.preventDefault();
        const selected = getSelectedIds();

        if (selected.length === 0) {
            toastr.warning('Please select at least one application');
            return;
        }

        if (confirm('Reject ' + selected.length + ' application(s)?')) {
            bulkUpdateStatus(selected, 'rejected');
        }
    });

    // Bulk Delete
    $('#bulk-delete').on('click', function(e) {
        e.preventDefault();
        const selected = getSelectedIds();

        if (selected.length === 0) {
            toastr.warning('Please select at least one application');
            return;
        }

        $('#bulk-count').text(selected.length);
        $('#bulk-delete-modal').modal('show');

        $('#confirm-bulk-delete').off('click').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');

            $.ajax({
                url: '<?= site_url("employer/applications/bulk-delete") ?>',
                type: 'POST',
                data: {
                    ids: selected,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    btn.prop('disabled', false).html('Delete All');
                    $('#bulk-delete-modal').modal('hide');

                    if (response.success) {
                        toastr.success(response.message);
                        // Remove selected rows from table
                        selected.forEach(id => {
                            const row = $(`.app-checkbox[data-id="${id}"]`).closest('tr');
                            const status = row.data('status');
                            dataTable.row(row).remove();

                            // Update stats
                            const statusElement = $(`.stats-card:contains('${status.charAt(0).toUpperCase() + status.slice(1)}') .mb-0`);
                            if (statusElement.length) {
                                let count = parseInt(statusElement.text()) || 0;
                                statusElement.text(Math.max(0, count - 1));
                            }
                        });
                        dataTable.draw();

                        const totalElement = $(`.stats-card:contains('Total') .mb-0`);
                        if (totalElement.length) {
                            let total = parseInt(totalElement.text()) || 0;
                            totalElement.text(Math.max(0, total - selected.length));
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('Delete All');
                    $('#bulk-delete-modal').modal('hide');
                    toastr.error('Failed to delete applications');
                }
            });
        });
    });

    function getSelectedIds() {
        const selected = [];
        $('.app-checkbox:checked').each(function() {
            selected.push($(this).data('id'));
        });
        return selected;
    }

    function bulkUpdateStatus(ids, status) {
        // Show loading state on bulk action button
        const btn = $(`#bulk-${status}`);
        const originalText = btn.text();
        btn.text('Updating...').prop('disabled', true);

        $.ajax({
            url: '<?= site_url("employer/applications/bulk-update-status") ?>',
            type: 'POST',
            data: {
                ids: ids,
                status: status,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Update each row's status
                    ids.forEach(id => {
                        const row = $(`.app-checkbox[data-id="${id}"]`).closest('tr');
                        const select = row.find('.status-select');
                        select.val(status);
                        select.data('original-status', status);
                        row.attr('data-status', status);

                        // Update select option selected attribute
                        select.find('option').removeAttr('selected');
                        select.find('option[value="' + status + '"]').attr('selected', 'selected');
                    });

                    // Reload to update stats properly
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Failed to update statuses');
            },
            complete: function() {
                btn.text(originalText).prop('disabled', false);
            }
        });
    }
</script>
<?= $this->endSection() ?>