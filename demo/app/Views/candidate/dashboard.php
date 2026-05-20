<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header -->
    <div class="d-lg-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1 text-gradient">Welcome, <?= esc($candidate->full_name) ?></h2>
            <p class="text-muted">You have <span class="text-main fw-bold"><?= $totalApplications ?></span> Applications total</p>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="input-icon-start position-relative">
                    <span class="input-icon-addon fs-16 text-gray-9">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search Jobs">
                </div>
            </li>
        </ul>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-wrap mb-4" style="background: linear-gradient(135deg, var(--midnight-bg) 0%, var(--surface) 100%); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; position: relative; padding: 40px;">
        <div class="d-flex align-items-center justify-content-between flex-wrap position-relative" style="z-index: 2;">
            <div class="mb-3">
                <h2 class="mb-1 text-gradient">Good to see you again, <?= esc($candidate->full_name) ?> </h2>
                <p class="text-muted"><?= $recentApplicationsCount ?? 0 ?> new application updates today</p>
            </div>
            <div class="d-flex flex-wrap mb-1">
                <a href="<?= base_url('candidate/applications') ?>" class="btn btn-primary btn-md me-2 mb-2">
                    My Applications
                </a>
                <a href="<?= base_url('candidate/saved-jobs') ?>" class="btn btn-outline-primary btn-md mb-2">
                    Saved Jobs
                </a>
                <?php if (env('feature_ai_career_tools', 'true') === 'true'): ?>
                <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-outline-info btn-md me-2 mb-2">
                    AI Career Tools
                </a>
                <a href="<?= base_url('candidate/career-tools/mock-interview') ?>" class="btn btn-outline-success btn-md mb-2" target="_blank" rel="noopener">
                    Start Mock Interview
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">

        <!-- Total Applications -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <span class="avatar avatar-md bg-primary-transparent rounded-circle p-2 mb-3">
                        <i class="ti ti-file fs-16 text-primary"></i>
                    </span>
                    <h2 class="mb-1 fw-bold"><?= $totalApplications ?></h2>
                    <p class="text-muted fs-13">Total Applications</p>
                </div>
            </div>
        </div>

        <!-- Saved Jobs -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <span class="avatar avatar-md bg-warning-transparent rounded-circle p-2 mb-3">
                        <i class="ti ti-bookmark fs-16 text-warning"></i>
                    </span>
                    <h2 class="mb-1 fw-bold"><?= $savedJobs ?></h2>
                    <p class="text-muted fs-13">Saved Jobs</p>
                </div>
            </div>
        </div>

        <!-- Jobs Viewed -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <span class="avatar avatar-md bg-info-transparent rounded-circle p-2 mb-3">
                        <i class="ti ti-eye fs-16 text-info"></i>
                    </span>
                    <h2 class="mb-1 fw-bold"><?= $jobsViewed ?></h2>
                    <p class="text-muted fs-13">Jobs Viewed</p>
                </div>
            </div>
        </div>

        <!-- Profile Completion -->
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-body">
                    <span class="avatar avatar-md bg-success-transparent rounded-circle p-2 mb-3">
                        <i class="ti ti-user-check fs-16 text-success"></i>
                    </span>
                    <h2 class="mb-1 fw-bold text-gradient"><?= $profileCompletion ?>%</h2>
                    <p class="text-muted fs-13">Profile Completion</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Recommended Jobs -->
        <div class="col-xxl-3 col-lg-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main">Recommended Jobs</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recommendedJobs)): ?>
                        <p class="text-muted text-center py-3">No recommendations yet.</p>
                    <?php endif; ?>
                    <?php foreach ($recommendedJobs as $job): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1 fw-semibold"><?= esc($job->title) ?></h6>
                                <p class="text-muted fs-13 mb-0"><?= esc($job->location) ?></p>
                            </div>
                            <a href="<?= base_url('job/' . $job->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Applications Chart (Placeholder) -->
        <div class="col-lg-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main">Applications Overview</h5>
                </div>
                <div class="card-body">
                    <div id="applications-chart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>

        <!-- Top Categories (Static example) -->
        <div class="col-xxl-3 col-xl-12 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main">Top Categories</h5>
                </div>
                <div class="card-body">
                    <div id="category-overview" style="min-height: 250px;"></div>
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
                    <a href="<?= base_url('candidate/applications') ?>" class="btn btn-outline-primary btn-sm rounded-pill">View All</a>
                </div>
                <div class="card-body pb-2">
                    <?php foreach ($recentApplications as $app): ?>
                        <div class="d-sm-flex justify-content-between align-items-center mb-3 p-2 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border);">
                            <div>
                                <h6 class="mb-1 fw-semibold"><?= esc($app->job_title) ?></h6>
                                <p class="text-muted fs-13 mb-0">Applied on <?= date('M d, Y', strtotime($app->created_at)) ?></p>
                            </div>
                            <span class="badge bg-primary-transparent text-primary rounded-pill px-3"><?= ucfirst($app->status) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recently Posted Jobs (Example Static Section) -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main">Latest Jobs</h5>
                </div>
                <div class="card-body pb-2">
                    <?php foreach ($latestJobs as $job): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-semibold mb-1"><?= esc($job->title) ?></h6>
                                <p class="text-muted fs-13 mb-0">Posted <?= date('M d, Y', strtotime($job->created_at)) ?></p>
                            </div>
                            <a href="<?= base_url('job/view/' . $job->id) ?>" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Popular Job Categories -->
        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="glass-card flex-fill mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main">Popular Categories</h5>
                </div>
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Software Development</span>
                        <span class="text-main fw-bold">35%</span>
                    </div>
                    <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-primary" style="width: 35%"></div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Marketing</span>
                        <span class="text-main fw-bold">20%</span>
                    </div>
                    <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-info" style="width: 20%"></div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">UI/UX Design</span>
                        <span class="text-main fw-bold">15%</span>
                    </div>
                    <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-success" style="width: 15%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection() ?>