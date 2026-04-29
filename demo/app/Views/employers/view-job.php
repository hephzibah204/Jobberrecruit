<?= $this->extend('layouts/app') ?>
<?= $this->section('styles') ?>
<style>
    .stats-card {
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .application-card {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }

    .application-card:hover {
        background-color: #f8f9fa;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
    }

    .featured-badge {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #856404;
    }

    .countdown-timer {
        font-family: monospace;
        font-size: 0.875rem;
    }

    .job-detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .job-detail-value {
        font-size: 1rem;
        font-weight: 500;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Job Details</h4>
                <h6>View and manage job posting</h6>
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
        <div class="page-btn mt-0">
            <a href="<?= site_url('employer/my-jobs') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Jobs
            </a>
        </div>
    </div>

    <!-- Job Header Card -->
    <div class="card custom-card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <?php if ($employer->logo): ?>
                            <img src="<?= base_url($employer->logo) ?>" alt="Company Logo" class="rounded-circle" width="60" height="60">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="ti ti-building fs-30 text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="mb-1">
                            <?= esc($job->title) ?>
                            <?php if ($job->is_featured && strtotime($job->featured_until) > time()): ?>
                                <span class="badge featured-badge ms-2">⭐ Featured</span>
                            <?php endif; ?>
                            <?php if ($job->is_anonymous): ?>
                                <span class="badge bg-info ms-2">Anonymous</span>
                            <?php endif; ?>
                        </h3>
                        <p class="text-muted mb-2">
                            <i class="ti ti-building me-1"></i><?= esc($employer->company_name) ?>
                            <span class="mx-2">•</span>
                            <i class="ti ti-map-pin me-1"></i><?= esc($job->location ?? 'Remote') ?>
                            <span class="mx-2">•</span>
                            <i class="ti ti-briefcase me-1"></i><?= ucfirst(str_replace('-', ' ', $job->job_type)) ?>
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary-transparent">
                                <i class="ti ti-tag me-1"></i><?= esc($job->category_name ?? 'N/A') ?>
                            </span>
                            <span class="badge bg-info-transparent">
                                <i class="ti ti-building me-1"></i><?= esc($job->industry_name ?? 'N/A') ?>
                            </span>
                            <span class="badge bg-success-transparent">
                                <i class="ti ti-credit-card me-1"></i><?= esc($job->salary_details ?? 'Negotiable') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mt-sm-0">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-settings me-1"></i>Manage Job
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?= site_url('employer/jobs/edit/' . $job->id) ?>">
                                    <i class="ti ti-edit me-2"></i>Edit Job
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="toggleFeatureJob(<?= $job->id ?>)">
                                    <i class="ti ti-star me-2"></i>
                                    <?php if ($job->is_featured && strtotime($job->featured_until) > time()): ?>
                                        Remove Featured
                                    <?php else: ?>
                                        Feature Job
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteJob(<?= $job->id ?>)">
                                    <i class="ti ti-trash me-2"></i>Delete Job
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if ($job->featured_until && strtotime($job->featured_until) > time()): ?>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="ti ti-clock me-2"></i>
                    <strong>Featured until:</strong>
                    <span class="countdown-timer" data-end="<?= $job->featured_until ?>">Calculating...</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6>Total Applications</h6>
                            <h4 class="mb-0"><?= number_format($applicationStats['total']) ?></h4>
                        </div>
                        <div class="avatar bg-primary-transparent">
                            <i class="ti ti-users fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6>Pending Review</h6>
                            <h4 class="mb-0 text-warning"><?= number_format($applicationStats['pending']) ?></h4>
                        </div>
                        <div class="avatar bg-warning-transparent">
                            <i class="ti ti-hourglass fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6>Shortlisted</h6>
                            <h4 class="mb-0 text-success"><?= number_format($applicationStats['shortlisted']) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent">
                            <i class="ti ti-star fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6>Total Views</h6>
                            <h4 class="mb-0"><?= number_format($totalClicks) ?></h4>
                        </div>
                        <div class="avatar bg-info-transparent">
                            <i class="ti ti-eye fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Applications Trend (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="applicationsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Views Trend (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Details Tabs -->
    <div class="card custom-card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#job-details" role="tab">
                        <i class="ti ti-info-circle me-1"></i>Job Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#applications" role="tab">
                        <i class="ti ti-users me-1"></i>Applications
                        <span class="badge bg-primary ms-1"><?= $applicationStats['total'] ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#analytics" role="tab">
                        <i class="ti ti-chart-bar me-1"></i>Analytics
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Job Details Tab -->
                <div class="tab-pane fade show active" id="job-details" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="job-detail-label">Job Description</div>
                                <div class="job-detail-value"><?= htmlspecialchars_decode(esc($job->description)) ?></div>
                            </div>

                            <?php if ($job->requirements): ?>
                                <div class="mb-4">
                                    <div class="job-detail-label">Requirements</div>
                                    <div class="job-detail-value"><?= htmlspecialchars_decode(esc($job->requirements)) ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($job->skills): ?>
                                <div class="mb-4">
                                    <div class="job-detail-label">Required Skills</div>
                                    <div class="job-detail-value">
                                        <?php foreach (explode(',', $job->skills) as $skill): ?>
                                            <span class="badge bg-light text-dark border me-1 mb-1"><?= esc(trim($skill)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="job-detail-label">Job Type</div>
                                <div class="job-detail-value"><?= ucfirst(str_replace('-', ' ', $job->job_type)) ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="job-detail-label">Location Type</div>
                                <div class="job-detail-value"><?= ucfirst($job->location_type ?? 'N/A') ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="job-detail-label">Salary</div>
                                <div class="job-detail-value"><?= esc($job->salary_details ?? 'Negotiable') ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="job-detail-label">Education Level</div>
                                <div class="job-detail-value"><?= esc($job->education_level ?? 'N/A') ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="job-detail-label">Experience Level</div>
                                <div class="job-detail-value"><?= esc($job->experience_level ?? 'N/A') ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="job-detail-label">Accommodation</div>
                                <div class="job-detail-value"><?= ucfirst($job->accommodation ?? 'Not Available') ?></div>
                            </div>

                            <div class="mb-4">
                                <div class="job-detail-label">Application Method</div>
                                <div class="job-detail-value">
                                    <?= ucfirst($job->application_method) ?>
                                    <?php if ($job->application_method === 'whatsapp' && $job->whatsapp_link): ?>
                                        <a href="<?= esc($job->whatsapp_link) ?>" target="_blank" class="ms-2">(View)</a>
                                    <?php elseif ($job->application_method === 'email' && $job->application_email): ?>
                                        <a href="mailto:<?= esc($job->application_email) ?>" class="ms-2">(<?= esc($job->application_email) ?>)</a>
                                    <?php elseif ($job->application_method === 'external' && $job->external_url): ?>
                                        <a href="<?= esc($job->external_url) ?>" target="_blank" class="ms-2">(External Link)</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Tab -->
                <div class="tab-pane fade" id="applications" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>All Applications (<?= $applicationStats['total'] ?>)</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                Filter by Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item filter-status" href="#" data-status="all">All</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="pending">Pending</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="reviewed">Reviewed</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="shortlisted">Shortlisted</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="rejected">Rejected</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="hired">Hired</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="applications-table">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Contact</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($applications)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="ti ti-user-off fs-48 text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">No applications yet</h5>
                                            <p class="text-muted">Applications will appear here when candidates apply.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($applications as $app): ?>
                                        <tr data-status="<?= $app->status ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <?php if ($app->avatar): ?>
                                                            <img src="<?= base_url($app->avatar) ?>" class="rounded-circle" width="40" height="40">
                                                        <?php else: ?>
                                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="ti ti-user fs-20 text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <strong><?= esc($app->fullname ?? 'N/A') ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div><i class="ti ti-mail me-1"></i> <?= esc($app->email ?? 'N/A') ?></div>
                                                    <div><i class="ti ti-phone me-1"></i> <?= esc($app->phone ?? 'N/A') ?></div>
                                                </div>
                                            </td>
                                            <td><?= date('M d, Y H:i', strtotime($app->created_at)) ?></td>
                                            <td>
                                                <select class="form-select form-select-sm status-select" style="width: 130px;" data-id="<?= $app->id ?>">
                                                    <option value="pending" <?= $app->status == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                                                    <option value="reviewed" <?= $app->status == 'reviewed' ? 'selected' : '' ?>>👁️ Reviewed</option>
                                                    <option value="shortlisted" <?= $app->status == 'shortlisted' ? 'selected' : '' ?>>⭐ Shortlisted</option>
                                                    <option value="rejected" <?= $app->status == 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                                                    <option value="hired" <?= $app->status == 'hired' ? 'selected' : '' ?>>✅ Hired</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('employer/applications/view/' . $app->id) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Analytics Tab -->
                <div class="tab-pane fade" id="analytics" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <h3 class="mb-0"><?= number_format($applicationStats['total']) ?></h3>
                                    <small class="text-muted">Total Applications</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <h3 class="mb-0"><?= number_format($applicationStats['shortlisted']) ?></h3>
                                    <small class="text-muted">Shortlisted</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <h3 class="mb-0"><?= number_format($applicationStats['hired']) ?></h3>
                                    <small class="text-muted">Hired</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Conversion Funnel</h6>
                        <div class="progress mb-2" style="height: 30px;">
                            <div class="progress-bar bg-warning" style="width: <?= $applicationStats['total'] > 0 ? ($applicationStats['pending'] / $applicationStats['total']) * 100 : 0 ?>%">
                                Pending: <?= $applicationStats['pending'] ?>
                            </div>
                            <div class="progress-bar bg-info" style="width: <?= $applicationStats['total'] > 0 ? ($applicationStats['reviewed'] / $applicationStats['total']) * 100 : 0 ?>%">
                                Reviewed: <?= $applicationStats['reviewed'] ?>
                            </div>
                            <div class="progress-bar bg-success" style="width: <?= $applicationStats['total'] > 0 ? ($applicationStats['shortlisted'] / $applicationStats['total']) * 100 : 0 ?>%">
                                Shortlisted: <?= $applicationStats['shortlisted'] ?>
                            </div>
                            <div class="progress-bar bg-danger" style="width: <?= $applicationStats['total'] > 0 ? ($applicationStats['rejected'] / $applicationStats['total']) * 100 : 0 ?>%">
                                Rejected: <?= $applicationStats['rejected'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Countdown timer for featured jobs
    function updateCountdowns() {
        $('.countdown-timer').each(function() {
            const endTime = new Date($(this).data('end')).getTime();
            if (isNaN(endTime)) return;

            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                $(this).text('Expired');
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

            let text = '';
            if (days > 0) text += `${days}d `;
            if (hours > 0 || days > 0) text += `${hours}h `;
            text += `${minutes}m left`;

            $(this).text(text);
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 60000);

    // Applications Chart
    const appCtx = document.getElementById('applicationsChart').getContext('2d');
    new Chart(appCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($dailyTrend, 'date')) ?>,
            datasets: [{
                label: 'Applications',
                data: <?= json_encode(array_column($dailyTrend, 'count')) ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    // Views Chart
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($dailyClicks, 'date')) ?>,
            datasets: [{
                label: 'Views',
                data: <?= json_encode(array_column($dailyClicks, 'count')) ?>,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    // Update application status
    $('.status-select').on('change', function() {
        const select = $(this);
        const applicationId = select.data('id');
        const status = select.val();

        $.ajax({
            url: '<?= site_url('employer/applications/update-status') ?>',
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
                    // Update row data-status
                    select.closest('tr').attr('data-status', status);
                } else {
                    toastr.error(response.message);
                    // Revert select value
                    select.val(select.closest('tr').attr('data-status'));
                }
            },
            error: function() {
                toastr.error('Failed to update status');
                select.val(select.closest('tr').attr('data-status'));
            }
        });
    });

    // Filter applications by status
    $('.filter-status').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');

        $('#applications-table tbody tr').each(function() {
            if (status === 'all') {
                $(this).show();
            } else {
                if ($(this).data('status') === status) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });

        // Update button text
        $(this).closest('.dropdown').find('.dropdown-toggle').html($(this).text() + ' <i class="ti ti-chevron-down ms-1"></i>');
    });

    // Toggle featured job
    function toggleFeatureJob(jobId) {
        $.ajax({
            url: '<?= site_url('employer/jobs/feature/') ?>' + jobId,
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Failed to update featured status');
            }
        });
    }

    // Delete job
    function deleteJob(jobId) {
        if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
            $.ajax({
                url: '<?= site_url('employer/jobs/delete/') ?>' + jobId,
                type: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = '<?= site_url('employer/my-jobs') ?>';
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Failed to delete job');
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>