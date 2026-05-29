<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    /* Premium Welcome Wrap */
    .premium-welcome-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(2, 6, 23, 0.95) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 24px !important;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        z-index: 1;
    }

    .premium-welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0) 70%);
        z-index: -1;
        pointer-events: none;
    }

    .premium-welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0) 70%);
        z-index: -1;
        pointer-events: none;
    }

    .welcome-decor-grid {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.8;
        pointer-events: none;
        z-index: -1;
    }

    /* World-Class Stats Cards */
    .stat-card-premium {
        background: rgba(15, 23, 42, 0.6) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 20px !important;
        padding: 1.5rem !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative;
        overflow: hidden;
    }

    .stat-card-premium:hover {
        border-color: rgba(14, 165, 233, 0.35) !important;
        box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
        transform: translateY(-4px);
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        position: relative;
    }

    .stat-icon-wrapper::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 12px;
        opacity: 0.15;
    }

    /* Color variations for icon wrappers */
    .wrapper-blue { color: #0ea5e9; }
    .wrapper-blue::after { background-color: #0ea5e9; }
    .wrapper-warning { color: #f59e0b; }
    .wrapper-warning::after { background-color: #f59e0b; }
    .wrapper-info { color: #06b6d4; }
    .wrapper-info::after { background-color: #06b6d4; }
    .wrapper-success { color: #10b981; }
    .wrapper-success::after { background-color: #10b981; }

    /* Quick Action Buttons */
    .action-btn-pill {
        border-radius: 50px !important;
        padding: 0.625rem 1.25rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .action-btn-pill:hover {
        transform: translateY(-2px);
    }

    /* List item visual improvements */
    .list-item-glass {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        transition: all 0.25s ease;
    }

    .list-item-glass:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(14, 165, 233, 0.2);
        padding-left: 12px !important;
    }

    .text-gradient {
        background: linear-gradient(90deg, #f8fafc 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .text-main {
        color: #f8fafc !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="d-lg-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1 text-gradient">Welcome, <?= esc($employer->company_name) ?></h2>
            <p class="text-muted mb-0">You have <span class="text-primary fw-bold"><?= number_format($totalApplicants) ?></span> Applications Today</p>
        </div>
        <div class="table-top-head mt-3 mt-lg-0">
            <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" class="btn btn-outline-secondary btn-sm rounded-circle p-2">
                <i class="ti ti-chevron-up feather-16"></i>
            </a>
        </div>
    </div>

    <!-- Welcome Premium Banner -->
    <div class="premium-welcome-card p-4 mb-4">
        <div class="welcome-decor-grid"></div>
        <div class="d-flex align-items-center justify-content-between flex-wrap position-relative" style="z-index: 2;">
            <div class="mb-3 mb-md-0">
                <span class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="ti ti-sparkles me-1"></i> JobberRecruit AI Recruiter Active
                </span>
                <h3 class="mb-2 fw-bold text-white">Find the perfect match, faster.</h3>
                <p class="text-muted mb-0">
                    <i class="ti ti-cpu me-1 text-primary"></i>
                    AI Assistant: <span class="text-white fw-bold"><?= number_format($totalApplicants) ?></span> new matching profiles found today.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('employer/post-job') ?>" class="btn btn-primary action-btn-pill">
                    <i class="ti ti-plus me-1"></i>Post New Job
                </a>
                <a href="<?= base_url('employer/applications') ?>" class="btn btn-outline-primary action-btn-pill">
                    View Applicants
                </a>
                <a href="<?= base_url('recruitment') ?>" class="btn btn-success action-btn-pill">
                    <i class="ti ti-users me-1"></i>Let Us Help You Hire
                </a>
            </div>
        </div>
    </div>

    <?php if (isset($hasCACDocument) && !$hasCACDocument): ?>
        <div class="alert alert-info d-flex align-items-center mb-4 border-opacity-10" style="background: rgba(14, 165, 233, 0.15); border: 1px solid rgba(14, 165, 233, 0.3);" role="alert">
            <i class="ti ti-info-circle me-2 text-primary flex-shrink-0 fs-5"></i>
            <div class="text-main">
                <strong>Recommendation:</strong> Upload your CAC certificate to get a verified badge and increase trust with job seekers.
                <a href="<?= base_url('employer/profile/upload-document') ?>" class="alert-link text-primary text-decoration-underline ms-2">Upload now</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Metrics Stats Grid -->
    <div class="row">
        <!-- Stat Card 1 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon-wrapper wrapper-blue">
                    <i class="ti ti-briefcase fs-4"></i>
                </div>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= number_format($totalJobs) ?></h2>
                <p class="text-muted fs-13 mb-0">Total Jobs Posted</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="stat-icon-wrapper wrapper-info">
                    <i class="ti ti-activity fs-4"></i>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= number_format($activeJobs) ?></h2>
                <p class="text-muted fs-13 mb-0">Active Jobs</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="d-flex align-items-center justify-content-between">
                <div class="stat-icon-wrapper wrapper-warning">
                    <i class="ti ti-users fs-4"></i>
                </div>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= number_format($totalApplicants) ?></h2>
                <p class="text-muted fs-13 mb-0">Total Applicants</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="stat-icon-wrapper wrapper-success">
                    <i class="ti ti-user-check fs-4"></i>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= number_format($totalHires) ?></h2>
                <p class="text-muted fs-13 mb-0">Total Hires</p>
            </div>
        </div>
    </div>

    <!-- Charts & Category Section -->
    <div class="row">
        <!-- Jobs Posted Chart -->
        <div class="col-lg-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-chart-area me-2 text-primary"></i>Jobs Posted (Last 7 Days)</h5>
                </div>
                <div id="jobs-chart-container" style="min-height: 250px;"></div>
            </div>
        </div>

        <!-- Applications Chart -->
        <div class="col-lg-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-chart-bar me-2 text-primary"></i>Applications Received (Last 7 Days)</h5>
                </div>
                <div id="apps-chart-container" style="min-height: 250px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Categories -->
        <div class="col-xxl-4 col-xl-12 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-circle-half-2 me-2 text-primary"></i>Top Job Categories</h5>
                </div>
                <div class="p-4">
                    <?php if (empty($categoryCounts)): ?>
                        <p class="text-muted text-center py-4 mb-0">No category data available.</p>
                    <?php endif; ?>

                    <?php foreach ($categoryCounts as $cat): ?>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-10 last-border-none">
                            <p class="mb-0 text-muted"><i class="ti ti-circle-filled text-primary me-2"></i><?= esc($cat->name) ?> </p>
                            <p class="mb-0 fw-semibold text-white"><?= $cat->total ?> jobs</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-history me-2 text-primary"></i>Recent Applications</h5>
                    <a href="<?= base_url('employer/applications') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">View All</a>
                </div>
                <div class="p-4">
                    <?php if (empty($recentApplications)): ?>
                        <p class="text-muted text-center py-4 mb-0">No applications yet.</p>
                    <?php endif; ?>

                    <?php foreach ($recentApplications as $app): ?>
                        <div class="d-sm-flex justify-content-between align-items-center mb-3 p-3 list-item-glass">
                            <div>
                                <h6 class="mb-1 text-white fw-semibold"><?= esc($app->job_title) ?></h6>
                                <p class="text-muted fs-12 mb-0"><i class="ti ti-calendar me-1"></i>Applied on <?= date('M d, Y', strtotime($app->created_at)) ?></p>
                            </div>
                            <span class="badge bg-primary-transparent text-primary rounded-pill px-3 py-2 mt-2 mt-sm-0"><?= ucfirst($app->status) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recently Posted Jobs -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-briefcase me-2 text-primary"></i>Recently Posted Jobs</h5>
                    <a href="<?= base_url('employer/jobs') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">View All</a>
                </div>
                <div class="p-4">
                    <?php if (empty($recentJobs)): ?>
                        <p class="text-muted text-center py-4 mb-0">No jobs posted yet.</p>
                    <?php endif; ?>

                    <?php foreach ($recentJobs as $job): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-10 last-border-none">
                            <div>
                                <h6 class="fw-semibold text-white mb-1"><?= esc($job->title) ?></h6>
                                <p class="text-muted fs-12 mb-0"><i class="ti ti-clock me-1"></i>Posted <?= date('d M Y', strtotime($job->created_at)) ?></p>
                            </div>
                            <h6 class="text-primary mb-0 fw-bold"><?= $job->views ?> Views</h6>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Hires -->
    <div class="row">
        <div class="col-12 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-user-check me-2 text-primary"></i>Recent Onboarded Hires</h5>
                </div>
                <div class="p-4">
                    <?php $hasHires = false; foreach ($recentApplications as $app): ?>
                        <?php if ($app->status == 'hired'): $hasHires = true; ?>
                            <div class="d-sm-flex align-items-center justify-content-between mb-3 p-3 list-item-glass">
                                <div>
                                    <h6 class="mb-1 text-white fw-semibold"><?= esc($app->first_name . " " . $app->last_name) ?></h6>
                                    <p class="text-muted fs-12 mb-0">Hired for: <?= esc($app->job_title) ?></p>
                                </div>
                                <span class="badge bg-success-transparent text-success rounded-pill px-3 py-2 mt-2 mt-sm-0">Onboarded</span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!$hasHires): ?>
                        <p class="text-muted text-center py-4 mb-0">No onboarded hires yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const jobsData = <?= json_encode(array_values($jobsChart)) ?>;
        const jobsLabels = <?= json_encode(array_keys($jobsChart)) ?>;

        const appsData = <?= json_encode(array_values($appsChart)) ?>;
        const appsLabels = <?= json_encode(array_keys($appsChart)) ?>;

        // Apex Chart for Jobs Posted (Spline Area)
        var jobsOptions = {
            chart: {
                height: 250,
                type: 'area',
                toolbar: { show: false },
                background: 'transparent',
                foreColor: '#94a3b8'
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100],
                    colorStops: [
                        {
                            offset: 0,
                            color: '#0ea5e9',
                            opacity: 0.35
                        },
                        {
                            offset: 100,
                            color: '#0ea5e9',
                            opacity: 0.01
                        }
                    ]
                }
            },
            series: [{
                name: 'Jobs Posted',
                data: jobsData
            }],
            xaxis: {
                categories: jobsLabels,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            grid: {
                borderColor: 'rgba(255,255,255,0.05)',
                strokeDashArray: 4
            },
            colors: ['#0ea5e9'],
            tooltip: { theme: 'dark' }
        };
        var jobsChart = new ApexCharts(document.querySelector("#jobs-chart-container"), jobsOptions);
        jobsChart.render();

        // Apex Chart for Applications (Column Chart)
        var appsOptions = {
            chart: {
                height: 250,
                type: 'bar',
                toolbar: { show: false },
                background: 'transparent',
                foreColor: '#94a3b8'
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '45%'
                }
            },
            series: [{
                name: 'Applications',
                data: appsData
            }],
            xaxis: {
                categories: appsLabels,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            grid: {
                borderColor: 'rgba(255,255,255,0.05)',
                strokeDashArray: 4
            },
            colors: ['#10b981'],
            tooltip: { theme: 'dark' }
        };
        var appsChart = new ApexCharts(document.querySelector("#apps-chart-container"), appsOptions);
        appsChart.render();
    });
</script>
<?= $this->endSection() ?>