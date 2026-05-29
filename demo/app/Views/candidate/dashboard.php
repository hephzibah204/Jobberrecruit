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
        background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0) 70%);
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

    /* Custom neon-glowing metrics bar */
    .custom-progress-container {
        position: relative;
        width: 100%;
        height: 6px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .custom-progress-bar {
        height: 100%;
        border-radius: 3px;
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
    }

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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="d-lg-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1 text-gradient">Welcome, <?= esc($candidate->full_name) ?></h2>
            <p class="text-muted mb-0">You have submitted <span class="text-primary fw-bold"><?= $totalApplications ?></span> applications in total.</p>
        </div>
        <div class="table-top-head mt-3 mt-lg-0">
            <form action="<?= base_url('jobs') ?>" method="GET" class="input-icon-start position-relative">
                <span class="input-icon-addon fs-16 text-gray-9">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" name="q" class="form-control" placeholder="Search Jobs..." style="min-width: 250px; background: rgba(15, 23, 42, 0.5) !important;">
            </form>
        </div>
    </div>

    <!-- Welcome Premium Banner -->
    <div class="premium-welcome-card p-4 mb-4">
        <div class="welcome-decor-grid"></div>
        <div class="d-flex align-items-center justify-content-between flex-wrap position-relative" style="z-index: 2;">
            <div class="mb-3 mb-md-0">
                <span class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="ti ti-sparkles me-1"></i> JobberRecruit AI Engine Active
                </span>
                <h3 class="mb-2 fw-bold text-white">Let's accelerate your career path.</h3>
                <p class="text-muted mb-0">You have <span class="text-white fw-semibold"><?= $recentApplicationsCount ?? 0 ?></span> new updates and recommendations waiting for your action.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('candidate/applications') ?>" class="btn btn-primary action-btn-pill">
                    My Applications
                </a>
                <a href="<?= base_url('jobs') ?>" class="btn btn-outline-primary action-btn-pill">
                    Browse Jobs
                </a>
                <?php if (env('feature_ai_career_tools', 'true') == 'true'): ?>
                <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-outline-info action-btn-pill">
                    <i class="ti ti-cpu me-1"></i> AI Career Tools
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Metrics Stats Grid -->
    <div class="row">
        <!-- Stat Card 1 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="stat-icon-wrapper wrapper-blue">
                    <i class="ti ti-file-description fs-20"></i>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= $totalApplications ?></h2>
                <p class="text-muted fs-13 mb-0">Total Applications</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="stat-icon-wrapper wrapper-warning">
                    <i class="ti ti-bookmark fs-20"></i>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= $savedJobs ?></h2>
                <p class="text-muted fs-13 mb-0">Saved Jobs</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="stat-icon-wrapper wrapper-info">
                    <i class="ti ti-eye fs-20"></i>
                </div>
                <h2 class="mb-1 text-white fw-bold"><?= $jobsViewed ?></h2>
                <p class="text-muted fs-13 mb-0">Jobs Viewed</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="stat-icon-wrapper wrapper-success">
                    <i class="ti ti-progress fs-20"></i>
                </div>
                <h2 class="mb-1 text-gradient fw-bold"><?= $profileCompletion ?>%</h2>
                <p class="text-muted fs-13 mb-0">Profile Completion</p>
                <div class="custom-progress-container">
                    <div class="custom-progress-bar" style="width: <?= $profileCompletion ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Category Section -->
    <div class="row">
        <!-- Recommended Jobs List -->
        <div class="col-xxl-3 col-lg-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-star me-2 text-primary"></i>Recommended Jobs</h5>
                </div>
                <div class="p-4" style="max-height: 310px; overflow-y: auto;">
                    <?php if (empty($recommendedJobs)): ?>
                        <p class="text-muted text-center py-4 mb-0">No job matches found for your current profile. Update your industry preferences to see recommendations.</p>
                    <?php endif; ?>
                    <?php foreach ($recommendedJobs as $job): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-10 last-border-none">
                            <div>
                                <h6 class="mb-1 text-white fw-semibold"><?= esc($job->title) ?></h6>
                                <p class="text-muted fs-12 mb-0"><i class="ti ti-map-pin me-1"></i><?= esc($job->location) ?></p>
                            </div>
                            <a href="<?= base_url('job/view/' . $job->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Spline Area interactive Chart -->
        <div class="col-lg-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-chart-area me-2 text-primary"></i>Applications & Engagement</h5>
                </div>
                <div id="applications-chart" style="min-height: 250px;"></div>
            </div>
        </div>

        <!-- Matching Donut Chart -->
        <div class="col-xxl-3 col-lg-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-chart-donut me-2 text-primary"></i>Skill Match Hub</h5>
                </div>
                <div id="category-overview" style="min-height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Recent Actions and Activities -->
    <div class="row">
        <!-- Recent Applications -->
        <div class="col-xxl-4 col-xl-12 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-history me-2 text-primary"></i>Recent Applications</h5>
                    <a href="<?= base_url('candidate/applications') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">View All</a>
                </div>
                <div class="p-4">
                    <?php if (empty($recentApplications)): ?>
                        <p class="text-muted text-center py-4 mb-0">You haven't applied to any jobs yet.</p>
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

        <!-- Latest Job Listings -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4 p-0">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-news me-2 text-primary"></i>Latest Job Openings</h5>
                </div>
                <div class="p-4">
                    <?php if (empty($latestJobs)): ?>
                        <p class="text-muted text-center py-4 mb-0">No new jobs posted recently.</p>
                    <?php endif; ?>
                    <?php foreach ($latestJobs as $job): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-10 last-border-none">
                            <div>
                                <h6 class="fw-semibold text-white mb-1"><?= esc($job->title) ?></h6>
                                <p class="text-muted fs-12 mb-0"><i class="ti ti-clock me-1"></i>Posted <?= date('M d, Y', strtotime($job->created_at)) ?></p>
                            </div>
                            <a href="<?= base_url('job/view/' . $job->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Skill categories layout -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="stat-card-premium flex-fill mb-4">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-circle-half-2 me-2 text-primary"></i>Matching Categories</h5>
                </div>
                <div>
                    <?php if (!empty($skillCategories)): ?>
                        <?php foreach ($skillCategories as $cat): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted"><?= esc($cat->name) ?></span>
                                <span class="text-primary fw-bold"><?= $cat->match ?>%</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px;">
                                <div class="progress-bar bg-primary" style="width: <?= $cat->match ?>%; border-radius: 3px;"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4 mb-0">Complete your profile with skills to see category matches.</p>
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
        // Applications & Engagement Spline Area Chart
        var applicationsOptions = {
            chart: {
                height: 250,
                type: 'area',
                toolbar: { show: false },
                sparkline: { enabled: false },
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
                name: 'Applications',
                data: <?= json_encode($weeklyChartData ?? [1, 2, 4, 3, 5, 8, 10]) ?>
            }],
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (val) { return Math.round(val); }
                }
            },
            grid: {
                borderColor: 'rgba(255,255,255,0.05)',
                strokeDashArray: 4
            },
            colors: ['#0ea5e9'],
            tooltip: {
                theme: 'dark'
            }
        };
        var applicationsChart = new ApexCharts(document.querySelector("#applications-chart"), applicationsOptions);
        applicationsChart.render();

        // Skill Match Donut Chart
        var categoryOptions = {
            chart: {
                height: 250,
                type: 'donut',
                background: 'transparent'
            },
            stroke: {
                show: false
            },
            series: [40, 25, 20, 15],
            labels: ['Tech Skills', 'Soft Skills', 'Domain Knowledge', 'Other matches'],
            colors: ['#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6'],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                labels: {
                    colors: '#94a3b8'
                }
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                color: '#94a3b8'
                            },
                            value: {
                                show: true,
                                color: '#f8fafc',
                                formatter: function (val) { return val + "%"; }
                            },
                            total: {
                                show: true,
                                label: 'Match Rate',
                                color: '#94a3b8',
                                formatter: function (w) { return "85%"; }
                            }
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark'
            }
        };
        var categoryChart = new ApexCharts(document.querySelector("#category-overview"), categoryOptions);
        categoryChart.render();
    });
</script>
<?= $this->endSection() ?>