<?= $this->extend('layouts/app') ?>
<?= $this->section('styles') ?>
<style>
    .countdown-timer .time-left {
        font-size: 0.875rem;
    }

    .text-danger {
        font-weight: 500;
    }

    .featured-badge {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #856404;
    }

    .unlimited-badge {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold text-gradient">My Jobs</h4>
                <h6 class="text-muted">Manage your job postings</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="<?= site_url('employer/post-job') ?>" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i>Post New Job
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="glass-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Jobs Posted</h6>
                            <h4 class="mb-0 fw-bold"><?= number_format($totalJobs) ?></h4>
                        </div>
                        <div class="avatar bg-primary-transparent rounded-circle p-2">
                            <i class="ti ti-briefcase fs-20 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="glass-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Active Jobs</h6>
                            <h4 class="mb-0 fw-bold"><?= number_format($activeJobs) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent rounded-circle p-2">
                            <i class="ti ti-check-circle fs-20 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="glass-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Applications</h6>
                            <h4 class="mb-0 fw-bold"><?= number_format($totalApplications) ?></h4>
                        </div>
                        <div class="avatar bg-info-transparent rounded-circle p-2">
                            <i class="ti ti-user-check fs-20 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="glass-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Clicks</h6>
                            <h4 class="mb-0 fw-bold"><?= number_format($totalClicks) ?></h4>
                        </div>
                        <div class="avatar bg-warning-transparent rounded-circle p-2">
                            <i class="ti ti-eye fs-20 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unlimited Access Banner -->
    <?php if ($hasUnlimitedAccess): ?>
        <div class="alert alert-success d-flex align-items-center mb-4">
            <i class="ti ti-infinity fs-3 me-3"></i>
            <div>
                <strong class="fs-5">Unlimited Access Plan</strong>
                <p class="mb-0">You have unlimited job postings with all premium features included. No credit deductions apply.</p>
                <?php if ($employer->unlimited_until): ?>
                    <small class="mt-1 d-block">Valid until: <?= date('M d, Y', strtotime($employer->unlimited_until)) ?></small>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Credit Balance Warning -->
    <?php if (!$hasUnlimitedAccess && $creditBalance <= 2 && $creditBalance > 0): ?>
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="ti ti-alert-triangle me-3 fs-4"></i>
            <div>
                <strong>Low Credits Warning!</strong>
                <p class="mb-0">You only have <strong><?= $creditBalance ?></strong> job credit(s) remaining.
                    <a href="<?= site_url('employer/pricing') ?>" class="alert-link">Purchase more credits</a> to continue posting jobs.
                </p>
            </div>
        </div>
    <?php elseif (!$hasUnlimitedAccess && $creditBalance <= 0): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="ti ti-circle-x me-3 fs-4"></i>
            <div>
                <strong>No Credits Available!</strong>
                <p class="mb-0">You have 0 job credits.
                    <a href="<?= site_url('employer/pricing') ?>" class="alert-link">Purchase a bundle</a> or
                    <a href="<?= site_url('employer/pricing') ?>" class="alert-link">subscribe to a plan</a> to post new jobs.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Performance (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Performing Jobs</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($topJobs)): ?>
                            <div class="list-group-item text-center text-muted py-3">
                                No applications yet
                            </div>
                        <?php else: ?>
                            <?php foreach ($topJobs as $job): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-medium text-truncate" style="max-width: 200px;"><?= esc($job->title) ?></span>
                                    <span class="badge bg-primary-transparent">
                                        <?= $job->applications ?> apps
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Info -->
    <?php if ($activeSubscription): ?>
        <div class="card custom-card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Subscription</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Plan:</strong>
                            <?= esc($activeSubscription['plan_name'] ?? 'No Plan') ?>
                            <?php if ($hasUnlimitedAccess): ?>
                                <span class="badge unlimited-badge ms-2">Unlimited</span>
                            <?php endif; ?>
                        </p>
                        <?php if ($activeSubscription['starts_at']): ?>
                            <p class="mb-2">
                                <strong>Start Date:</strong>
                                <?= date('M d, Y', strtotime($activeSubscription['starts_at'])) ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($activeSubscription['ends_at']): ?>
                            <p class="mb-2">
                                <strong>End Date:</strong>
                                <?= date('M d, Y', strtotime($activeSubscription['ends_at'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Job Credits:</strong>
                            <?= $hasUnlimitedAccess ? 'Unlimited' : number_format($creditBalance) ?>
                        </p>
                        <p class="mb-2"><strong>Features:</strong></p>
                        <?php if (!empty($activeSubscription['features_array'])): ?>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <?php foreach ($activeSubscription['features_array'] as $feature => $enabled): ?>
                                    <?php if ($enabled): ?>
                                        <span class="badge bg-primary">
                                            <?= ucwords(str_replace('_', ' ', $feature)) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">No special features</span>
                        <?php endif; ?>
                        <p class="mb-0">
                            <strong>Status:</strong>
                            <span class="badge bg-success">Active</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Promotion Status Card -->
    <div class="alert <?= $canFeature ? 'alert-info' : 'alert-secondary' ?> d-flex align-items-center mb-4">
        <i class="ti ti-star me-3 fs-4"></i>
        <div>
            <strong>Featured Job Status:</strong>

            <?php if ($hasUnlimitedAccess): ?>
                <span class="text-success">✓ Unlimited featured slots available</span>
                <br>
                <small>Your unlimited access plan allows you to feature all your jobs at no extra cost.</small>

            <?php elseif ($canFeature): ?>
                <span class="text-success">✓ Featured jobs available on your plan</span>
                <br>
                <small>You have <strong><?= $featuredUsed ?></strong> featured job(s) active. You can feature unlimited jobs.</small>

            <?php else: ?>
                <span class="text-muted">
                    Featured jobs are not available on your current plan.
                </span>
                <br>
                <a href="<?= site_url('employer/pricing') ?>" class="btn btn-sm btn-primary mt-2">
                    <i class="ti ti-crown me-1"></i> Upgrade to Feature Jobs
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Job list -->
    <div class="glass-card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
            <div class="search-set">
                <div class="search-input">
                    <input type="text" id="search-input" class="form-control" placeholder="Search jobs...">
                    <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                </div>
            </div>
            <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                        Category
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <li><a href="javascript:void(0);" class="dropdown-item rounded-1" data-filter="all" data-type="category">All Categories</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li><a href="javascript:void(0);" class="dropdown-item rounded-1" data-filter="category-<?= $category->id ?>" data-type="category"><?= esc($category->name) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                        Industry
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <li><a href="javascript:void(0);" class="dropdown-item rounded-1" data-filter="all" data-type="industry">All Industries</a></li>
                        <?php foreach ($industries as $industry): ?>
                            <li><a href="javascript:void(0);" class="dropdown-item rounded-1" data-filter="industry-<?= $industry->id ?>" data-type="industry"><?= esc($industry->name) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="jobs-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="no-sort">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Location</th>
                            <th>Salary</th>
                            <th>Category</th>
                            <th>Industry</th>
                            <th>Status</th>
                            <th>Featured Until</th>
                            <th>Created Date</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr data-category="category-<?= $job->category_id ?>" data-industry="industry-<?= $job->industry_id ?>">
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox" class="job-checkbox" data-id="<?= $job->id ?>">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td>
                                    <?php if ($job->is_featured && strtotime($job->featured_until) > time()): ?>
                                        <span class="badge featured-badge me-1">⭐ Featured</span>
                                    <?php endif; ?>
                                    <?php if ($job->anonymous): ?>
                                        <span class="badge bg-info me-1">Anonymous</span>
                                    <?php endif; ?>
                                    <?= esc($job->title) ?>
                                    <?php if ($job->admin_status === 'pending'): ?>
                                        <br><small class="text-warning">⏳ Pending approval</small>
                                    <?php elseif ($job->admin_status === 'rejected'): ?>
                                        <br><small class="text-danger">❌ Rejected: <?= esc($job->admin_notes) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc(ucfirst(str_replace('-', ' ', $job->job_type))) ?></td>
                                <td><?= esc($job->location ?? 'N/A') ?></td>
                                <td><?= esc($job->salary_details ?? 'Negotiable') ?></td>
                                <td><?= esc($job->category_name ?? 'N/A') ?></td>
                                <td><?= esc($job->industry_name ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($job->status === 'open' && $job->admin_status === 'approved'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($job->status === 'closed'): ?>
                                        <span class="badge bg-secondary">Closed</span>
                                    <?php elseif ($job->admin_status === 'pending'): ?>
                                        <span class="badge bg-warning">Pending Review</span>
                                    <?php elseif ($job->admin_status === 'rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($job->is_featured && $job->featured_until): ?>
                                        <?php if (strtotime($job->featured_until) > time()): ?>
                                            <span class="countdown-timer d-inline-block" data-end="<?= $job->featured_until ?>">
                                                <span class="time-left text-primary fw-medium">Calculating...</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Expired</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($job->created_at)) ?></td>
                                <td class="action-table-data">
                                    <div class="edit-delete-action d-flex align-items-center gap-2">
                                        <a class="p-2" href="<?= site_url('employer/jobs/view/' . $job->id) ?>" data-bs-toggle="tooltip" title="View">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        <a class="p-2" href="<?= site_url('employer/jobs/edit/' . $job->id) ?>" data-bs-toggle="tooltip" title="Edit">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a class="p-2 text-danger delete-job" href="javascript:void(0);" data-id="<?= $job->id ?>" data-bs-toggle="tooltip" title="Delete">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($jobs)): ?>
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <i class="ti ti-briefcase fs-48 text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No jobs posted yet</h5>
                                    <p class="text-muted">Click the "Post New Job" button to create your first job posting.</p>
                                    <a href="<?= site_url('employer/post-job') ?>" class="btn btn-primary mt-2">
                                        <i class="ti ti-circle-plus me-1"></i>Post Your First Job
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this job? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete Job</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    $('#search-input').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#jobs-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Category and Industry filters
    $('.dropdown-menu a[data-filter]').on('click', function(e) {
        e.preventDefault();
        const filterValue = $(this).data('filter');
        const filterType = $(this).data('type');
        const filterText = $(this).text();

        // Update dropdown button text
        $(this).closest('.dropdown').find('.dropdown-toggle').html(filterText + ' <i class="ti ti-chevron-down ms-2"></i>');

        // Filter the table
        $('#jobs-table tbody tr').each(function() {
            const row = $(this);
            if (filterValue === 'all') {
                row.show();
            } else if (filterType === 'category') {
                if (row.data('category') === filterValue) {
                    row.show();
                } else {
                    row.hide();
                }
            } else if (filterType === 'industry') {
                if (row.data('industry') === filterValue) {
                    row.show();
                } else {
                    row.hide();
                }
            }
        });
    });

    // Select all checkboxes
    $('#select-all').on('change', function() {
        $('.job-checkbox').prop('checked', this.checked);
    });

    // Delete job via AJAX
    let jobIdToDelete = null;
    $('.delete-job').on('click', function() {
        jobIdToDelete = $(this).data('id');
        $('#delete-modal').modal('show');
    });

    $('#confirm-delete').on('click', function() {
        const button = $(this);
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Deleting...');

        $.ajax({
            url: '<?= site_url('employer/jobs/delete') ?>/' + jobIdToDelete,
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                button.prop('disabled', false).html('Delete Job');
                $('#delete-modal').modal('hide');

                if (response.success) {
                    toastr.success(response.message || 'Job deleted successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Failed to delete job.');
                }
            },
            error: function(xhr) {
                button.prop('disabled', false).html('Delete Job');
                $('#delete-modal').modal('hide');
                let errorMsg = 'An error occurred while deleting the job.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
            }
        });
    });

    // Featured Job Countdown Timers
    function updateCountdowns() {
        $('.countdown-timer').each(function() {
            const endTime = new Date($(this).data('end')).getTime();
            if (isNaN(endTime)) {
                $(this).find('.time-left').text('Expired');
                return;
            }
            const now = new Date().getTime();
            const distance = endTime - now;
            if (distance < 0) {
                $(this).find('.time-left').html('<span class="text-danger">Expired</span>');
                return;
            }
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let text = '';
            if (days > 0) text += `${days}d `;
            if (hours > 0 || days > 0) text += `${hours}h `;
            text += `${minutes}m left`;
            $(this).find('.time-left').text(text);
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 60000);

    // Chart Data
    const monthlyData = <?= json_encode($monthlyData) ?>;
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(m => m.month),
            datasets: [{
                    label: 'Jobs Posted',
                    data: monthlyData.map(m => m.jobs_posted),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Applications',
                    data: monthlyData.map(m => m.applications),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Views',
                    data: monthlyData.map(m => m.views),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>