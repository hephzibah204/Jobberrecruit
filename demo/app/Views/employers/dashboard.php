<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header -->
    <div class="d-lg-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1">Welcome, <?= esc($employer->company_name) ?></h2>
            <p>You have <span class="text-primary fw-bold"><?= number_format($totalApplicants) ?></span> Applications Today</p>
        </div>
        <ul class="table-top-head">
            <!-- <li>
                <div class="input-icon-start position-relative">
                    <span class="input-icon-addon fs-16 text-gray-9">
                        <i class="ti ti-calendar"></i>
                    </span>
                    <input type="text" class="form-control date-range bookingrange" placeholder="Search Jobs">
                </div>
            </li> -->
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header">
                    <i data-feather="chevron-up" class="feather-16"></i>
                </a>
            </li>
        </ul>
    </div>

    <!-- Welcome Card -->
    <div class="glass-card mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(2, 6, 23, 0.8) 100%) !important;">
        <div class="card-body p-4 position-relative z-1">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="mb-3">
                    <h2 class="mb-1 text-gradient fw-bold">Welcome Back, <?= esc($employer->company_name) ?></h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-cpu-fill me-2 text-primary"></i>
                        AI Assistant: <span class="text-main fw-bold"><?= number_format($totalApplicants) ?></span> new matching profiles found today.
                    </p>
                </div>
                <div class="d-flex align-items-center flex-wrap mb-1">
                    <a href="<?= base_url('employer/post-job') ?>" class="btn btn-primary btn-md rounded-pill me-2 mb-2">
                        <i class="bi bi-plus-circle me-1"></i>Post New Job
                    </a>
                    <a href="<?= base_url('employer/applications') ?>" class="btn btn-outline-primary btn-md rounded-pill mb-2">
                        View Applicants
                    </a>
                </div>
            </div>
        </div>
        <!-- Decorative AI Pattern -->
        <div class="position-absolute top-0 end-0 opacity-10 p-4">
            <i class="bi bi-graph-up-arrow display-1 text-primary"></i>
        </div>
    </div>

    <?php if (isset($hasCACDocument) && !$hasCACDocument): ?>
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i data-feather="alert-triangle" class="me-2 flex-shrink-0"></i>
            <div>
                <strong>Action Required:</strong> Please upload your CAC certificate to verify your company and enable job posting.
                <a href="<?= base_url('employer/profile/upload-document') ?>" class="alert-link ms-2">Upload now</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Total Jobs Posted -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="avatar avatar-md rounded-3 bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-briefcase fs-4"></i>
                        </span>
                        <span class="badge bg-success-transparent text-success">+12%</span>
                    </div>
                    <h2 class="mb-1 text-main fw-bold"><?= number_format($totalJobs) ?></h2>
                    <p class="text-muted fs-13 mb-0">Total Jobs Posted</p>
                </div>
            </div>
        </div>

        <!-- Active Jobs -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="avatar avatar-md rounded-3 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-activity fs-4"></i>
                        </span>
                    </div>
                    <h2 class="mb-1 text-main fw-bold"><?= number_format($activeJobs) ?></h2>
                    <p class="text-muted fs-13 mb-0">Active Jobs</p>
                </div>
            </div>
        </div>

        <!-- Total Applicants -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="avatar avatar-md rounded-3 bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-people fs-4"></i>
                        </span>
                        <span class="badge bg-primary-transparent text-primary">New</span>
                    </div>
                    <h2 class="mb-1 text-main fw-bold"><?= number_format($totalApplicants) ?></h2>
                    <p class="text-muted fs-13 mb-0">Total Applicants</p>
                </div>
            </div>
        </div>

        <!-- Total Hires -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="avatar avatar-md rounded-3 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-person-check fs-4"></i>
                        </span>
                    </div>
                    <h2 class="mb-1 text-main fw-bold"><?= number_format($totalHires) ?></h2>
                    <p class="text-muted fs-13 mb-0">Total Hires</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Jobs Posted Chart -->
        <div class="col-xxl-3 col-lg-6 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5>Jobs Posted (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="jobsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Applications Chart -->
        <div class="col-lg-6 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5>Applications (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="col-xxl-3 col-xl-12 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main mb-0">Top Categories</h5>
                </div>
                <div class="card-body">

                    <?php if (empty($categoryCounts)): ?>
                        <p>No category data available.</p>
                    <?php endif; ?>

                    <?php foreach ($categoryCounts as $cat): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="f-13 mb-0"><i class="ti ti-circle-filled text-primary me-1"></i><?= esc($cat->name) ?> </p>
                            <p class="f-13 fw-medium text-gray-9"><?= $cat->total ?> jobs</p>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Recent Applications -->
        <div class="col-xxl-4 col-xl-12 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-main mb-0">Recent Applications</h5>
                    <a href="<?= base_url('employer/applications') ?>" class="btn btn-outline-primary btn-sm rounded-pill">View All</a>
                </div>
                <div class="card-body pb-2">

                    <?php if (empty($recentApplications)): ?>
                        <p class="text-muted">No applications yet.</p>
                    <?php endif; ?>

                    <?php foreach ($recentApplications as $app): ?>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div>
                                <h6 class="mb-1 text-main fw-semibold"><?= esc($app->job_title) ?></h6>
                                <p class="text-muted fs-13 mb-0">Applied on <?= date('M d, Y', strtotime($app->created_at)) ?></p>
                            </div>
                            <span class="badge bg-primary-transparent text-primary rounded-pill px-3"><?= ucfirst($app->status) ?></span>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- Recently Posted Jobs -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="card flex-fill">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-main mb-0">Recently Posted Jobs</h5>
                    <a href="<?= base_url('employer/jobs') ?>" class="btn btn-outline-primary btn-sm rounded-pill">View All</a>
                </div>
                <div class="card-body pb-2">

                    <?php if (empty($recentJobs)): ?>
                        <p class="text-muted">No jobs posted yet.</p>
                    <?php endif; ?>

                    <?php foreach ($recentJobs as $job): ?>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div>
                                <h6 class="mb-1"><?= esc($job->title) ?></h6>
                                <p class="fs-13">Posted: <?= date('d M Y', strtotime($job->created_at)) ?></p>
                            </div>
                            <h6><?= $job->views ?> Views</h6>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- Recent Hires -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main mb-0">Recent Hires</h5>
                </div>
                <div class="card-body">

                    <?php foreach ($recentApplications as $app): ?>
                        <?php if ($app->status == 'hired'): ?>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div>
                                    <h6 class="mb-1"><?= esc($app->first_name . " " . $app->last_name) ?></h6>
                                    <p class="fs-13">Hired for: <?= esc($app->job_title) ?></p>
                                </div>
                                <span class="badge bg-success">Onboarded</span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const jobsData = <?= json_encode(array_values($jobsChart)) ?>;
    const jobsLabels = <?= json_encode(array_keys($jobsChart)) ?>;

    const appsData = <?= json_encode(array_values($appsChart)) ?>;
    const appsLabels = <?= json_encode(array_keys($appsChart)) ?>;

    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';

    new Chart(document.getElementById("jobsChart"), {
        type: 'line',
        data: {
            labels: jobsLabels,
            datasets: [{
                label: "Jobs Posted",
                data: jobsData,
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById("applicationsChart"), {
        type: 'bar',
        data: {
            labels: appsLabels,
            datasets: [{
                label: "Applications",
                data: appsData,
                backgroundColor: '#0ea5e9',
                borderRadius: 8,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<?= $this->endSection() ?>