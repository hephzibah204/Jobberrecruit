<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- PAGE HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Job Details</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/jobs') ?>">Jobs</a>
                    </li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>

            <div class="btn-list">
                <?php if ($job->admin_status === 'pending'): ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="openApproveModal()">
                        <i class="ti ti-check"></i> Approve Job
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="openRejectModal()">
                        <i class="ti ti-x"></i> Reject Job
                    </button>
                <?php endif; ?>

                <a href="<?= base_url('admin/jobs/edit/' . $job->id) ?>" class="btn btn-primary btn-sm">
                    <i class="ti ti-edit"></i> Edit Job
                </a>

                <a href="<?= base_url('admin/jobs/delete/' . $job->id) ?>" onclick="return confirm('Delete this job?')" class="btn btn-danger btn-sm">
                    <i class="ti ti-trash"></i> Delete
                </a>
            </div>
        </div>
    </div>

    <!-- JOB META & STATUS -->
    <div class="card custom-card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-semibold mb-1"><?= esc($job->title) ?></h4>
                <div class="text-muted">
                    Posted on <?= date('M d, Y', strtotime($job->created_at)) ?> by <?= esc($job->company_name) ?>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Admin Status Badge -->
                <?php
                $statusColors = [
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ];
                $statusColor = $statusColors[$job->admin_status] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $statusColor ?>-transparent fs-14 p-2">
                    <?php if ($job->admin_status === 'pending'): ?>
                        <i class="ti ti-hourglass me-1"></i>
                    <?php elseif ($job->admin_status === 'approved'): ?>
                        <i class="ti ti-check me-1"></i>
                    <?php elseif ($job->admin_status === 'rejected'): ?>
                        <i class="ti ti-x me-1"></i>
                    <?php endif; ?>
                    Admin: <?= ucfirst($job->admin_status) ?>
                </span>

                <!-- Job Status Badge -->
                <span class="badge bg-<?= $job->status === 'open' ? 'success' : 'secondary' ?>-transparent fs-14 p-2">
                    <i class="ti ti-<?= $job->status === 'open' ? 'circle-check' : 'circle-x' ?> me-1"></i>
                    <?= ucfirst($job->status) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="card custom-card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Performance (Last 7 Days)</h5>
        </div>
        <div class="card-body">
            <canvas id="jobPerformanceChart" height="250"></canvas>
        </div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-details">
                <i class="ti ti-info-circle me-1"></i>Details
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-applications">
                <i class="ti ti-users me-1"></i>Applications
                <span class="badge bg-secondary ms-1"><?= $applicationsCount ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-analytics">
                <i class="ti ti-chart-bar me-1"></i>Analytics
            </button>
        </li>
    </ul>

    <div class="tab-content">

        <!-- DETAILS TAB -->
        <div class="tab-pane fade show active" id="tab-details">
            <div class="row">
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-body">
                            <!-- DESCRIPTION -->
                            <h6 class="fw-semibold mb-2">Job Description</h6>
                            <p class="text-muted"><?= nl2br(html_entity_decode($job->description)) ?></p>

                            <!-- REQUIREMENTS -->
                            <?php if (!empty($job->requirements)): ?>
                                <hr>
                                <h6 class="fw-semibold mb-2">Requirements</h6>
                                <p class="text-muted"><?= nl2br(html_entity_decode($job->requirements)) ?></p>
                            <?php endif; ?>

                            <?php if ($job->admin_notes): ?>
                                <hr>
                                <div class="alert alert-danger">
                                    <strong>Rejection Reason:</strong><br>
                                    <?= esc($job->admin_notes) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <!-- Job Information -->
                    <div class="card custom-card mb-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Job Information</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Company:</strong> <?= esc($job->company_name) ?></li>
                                <li class="mb-2"><strong>Location:</strong> <?= esc($job->state_name ?? '—') ?></li>
                                <li class="mb-2"><strong>Location Type:</strong> <?= ucfirst($job->location_type) ?></li>
                                <li class="mb-2"><strong>Job Type:</strong> <?= ucfirst($job->job_type) ?></li>
                                <li class="mb-2"><strong>Experience:</strong> <?= esc($job->experience_level) ?></li>
                                <li class="mb-2"><strong>Education:</strong> <?= esc($job->education_level) ?></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Skills -->
                    <?php if ($job->skills): ?>
                        <div class="card custom-card mb-3">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Required Skills</h6>
                                <?php foreach (explode(',', $job->skills) as $skill): ?>
                                    <span class="badge bg-light text-dark me-1 mb-1">
                                        <?= esc(trim($skill)) ?>
                                    </span>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Salary -->
                    <div class="card custom-card mb-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Salary</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Type:</strong> <?= ucfirst($job->salary_type) ?></li>
                                <li class="mb-2"><strong>Period:</strong> <?= ucfirst($job->salary_period) ?></li>
                                <li class="mb-2"><strong>Amount:</strong> <?= esc($job->salary ?: 'Negotiable') ?></li>
                                <?php if ($job->salary_details): ?>
                                    <li class="mb-2 text-muted"><?= esc($job->salary_details) ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Application Method -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Application Method</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Method:</strong> <?= ucfirst($job->application_method) ?></li>
                                <?php if ($job->application_email): ?>
                                    <li class="mb-2"><strong>Email:</strong> <?= esc($job->application_email) ?></li>
                                <?php endif; ?>
                                <?php if ($job->whatsapp_link): ?>
                                    <li class="mb-2"><strong>WhatsApp:</strong>
                                        <a href="<?= esc($job->whatsapp_link) ?>" target="_blank"><?= esc($job->whatsapp_link) ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($job->external_url): ?>
                                    <li class="mb-2"><strong>External URL:</strong>
                                        <a href="<?= esc($job->external_url) ?>" target="_blank">Apply Externally</a>
                                    </li>
                                <?php endif; ?>
                                <li class="mb-2"><strong>Deadline:</strong>
                                    <?= $job->application_deadline ? date('M d, Y', strtotime($job->application_deadline)) : '—' ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- APPLICATIONS TAB -->
        <div class="tab-pane fade" id="tab-applications">
            <div class="card custom-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($applications): ?>
                                    <?php foreach ($applications as $app): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <?= esc($app->first_name . ' ' . $app->last_name) ?>
                                                <?php if ($app->is_guest): ?>
                                                    <span class="badge bg-secondary-transparent ms-1">Guest</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($app->email) ?></td>
                                            <td><?= esc($app->phone) ?></td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'reviewed' => 'info',
                                                    'shortlisted' => 'success',
                                                    'rejected' => 'danger',
                                                    'hired' => 'primary'
                                                ];
                                                $appColor = $statusColors[$app->status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $appColor ?>">
                                                    <?= ucfirst($app->status) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>
                                            <td>
                                                <a href="<?= base_url($app->cv_path) ?>" target="_blank" class="btn btn-sm btn-light" title="Download CV">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                                <a href="<?= base_url('admin/applications/view/' . $app->id) ?>" class="btn btn-sm btn-light" title="View Details">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No applications found
                                        </td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ANALYTICS TAB -->
        <div class="tab-pane fade" id="tab-analytics">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card custom-card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Total Views</h6>
                            <h3 class="text-primary"><?= number_format($analytics['views']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card custom-card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Applications</h6>
                            <h3 class="text-success"><?= number_format($analytics['applications']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card custom-card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">External Clicks</h6>
                            <h3 class="text-info"><?= number_format($analytics['clicks']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card custom-card text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Conversion Rate</h6>
                            <h3 class="text-warning"><?= $analytics['conversion'] ?>%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daily Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Views</th>
                                    <th>Applications</th>
                                    <th>Conversion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dailyAnalytics as $day): ?>
                                    <tr>
                                        <td><?= $day['date'] ?></td>
                                        <td><?= number_format($day['views']) ?></td>
                                        <td><?= number_format($day['applications']) ?></td>
                                        <td>
                                            <?php
                                            $dayConversion = $day['views'] > 0
                                                ? round(($day['applications'] / $day['views']) * 100, 2)
                                                : 0;
                                            ?>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" style="width: <?= $dayConversion ?>%">
                                                    <?= $dayConversion ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve <strong><?= esc($job->title) ?></strong>?</p>
                <p class="text-muted">The job will be published and visible to job seekers immediately.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprove">Approve Job</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Please provide a reason for rejecting <strong><?= esc($job->title) ?></strong>:</p>
                <textarea id="rejectReason" class="form-control" rows="4" placeholder="Enter rejection reason..."></textarea>
                <small class="text-muted">This reason will be sent to the employer.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject Job</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart data
    const dailyData = <?= json_encode($dailyAnalytics) ?>;

    const ctx = document.getElementById('jobPerformanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                    label: 'Views',
                    data: dailyData.map(d => d.views),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Applications',
                    data: dailyData.map(d => d.applications),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
            }
        }
    });

    // Approve Job
    function openApproveModal() {
        $('#approveModal').modal('show');
    }

    $('#confirmApprove').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Approving...');

        $.ajax({
            url: '<?= base_url("admin/jobs/approve") ?>',
            type: 'POST',
            data: {
                job_id: <?= $job->id ?>,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message);
                }
                btn.prop('disabled', false).html('Approve Job');
                $('#approveModal').modal('hide');
            },
            error: function() {
                btn.prop('disabled', false).html('Approve Job');
                toastr.error('Failed to approve job');
                $('#approveModal').modal('hide');
            }
        });
    });

    // Reject Job
    function openRejectModal() {
        $('#rejectModal').modal('show');
    }

    $('#confirmReject').on('click', function() {
        const reason = $('#rejectReason').val().trim();

        if (!reason) {
            toastr.warning('Please provide a rejection reason');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Rejecting...');

        $.ajax({
            url: '<?= base_url("admin/jobs/reject") ?>',
            type: 'POST',
            data: {
                job_id: <?= $job->id ?>,
                reason: reason,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message);
                }
                btn.prop('disabled', false).html('Reject Job');
                $('#rejectModal').modal('hide');
            },
            error: function() {
                btn.prop('disabled', false).html('Reject Job');
                toastr.error('Failed to reject job');
                $('#rejectModal').modal('hide');
            }
        });
    });
</script>
<?= $this->endSection() ?>