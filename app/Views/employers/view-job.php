<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
    /* Page specific styles */
    .stats--jobs {
        grid-template-columns: repeat(4, 1fr);
    }
    @media (max-width: 1100px) {
        .stats--jobs {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .countdown-timer {
        font-family: monospace;
        font-size: 0.875rem;
    }
    .job-detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--muted);
        margin-bottom: 4px;
        font-weight: 600;
    }
    .job-detail-value {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 20px;
    }
    .nav-tabs {
        display: flex;
        gap: 16px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 24px;
    }
    .nav-tabs .nav-link {
        color: var(--muted);
        font-weight: 600;
        padding: 10px 4px;
        border: none;
        background: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
    }
    .nav-tabs .nav-link:hover {
        color: var(--brand);
    }
    .nav-tabs .nav-link.active {
        color: var(--brand);
        border-bottom-color: var(--brand);
    }
    .tab-content .tab-pane {
        display: none;
    }
    .tab-content .tab-pane.active {
        display: block;
    }
    /* Charts */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-head">
    <div class="page-head-left">
        <h1><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job Details</h1>
        <p>View details and manage applications for "<?= esc($job->title) ?>"</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('employer/my-jobs') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
            <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Jobs
        </a>
    </div>
</div>

<!-- Job Header Card -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                <div style="flex-shrink: 0;">
                    <?php if ($employer->logo): ?>
                        <img src="<?= (str_starts_with($employer->logo, 'http://') || str_starts_with($employer->logo, 'https://')) ? $employer->logo : base_url($employer->logo) ?>" alt="Company Logo" style="border-radius: var(--radius); width: 60px; height: 60px; object-fit: cover; border: 1px solid var(--border);">
                    <?php else: ?>
                        <div style="border-radius: var(--radius); background: var(--bg); display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; border: 1px solid var(--border);">
                            <svg aria-hidden="true" style="width: 28px; height: 28px; color: var(--muted);"><use href="#i-building"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--brand-deep); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin: 0;">
                        <?= esc($job->title) ?>
                        <?php if ($job->is_featured && strtotime($job->featured_until) > time()): ?>
                            <span class="pill pill--pending">⭐ Featured</span>
                        <?php endif; ?>
                        <?php if ($job->is_anonymous): ?>
                            <span class="pill pill--closed">Anonymous</span>
                        <?php endif; ?>
                    </h2>
                    <p style="color: var(--muted); font-size: 0.86rem; margin-top: 4px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 0;">
                        <span><svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-building"/></svg><?= esc($employer->company_name) ?></span>
                        <span>•</span>
                        <span><i class="ti ti-map-pin" style="margin-right: 4px;"></i><?= esc($job->location ?? 'Remote') ?></span>
                        <span>•</span>
                        <span><svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-briefcase"/></svg><?= ucfirst(str_replace('-', ' ', $job->job_type)) ?></span>
                    </p>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px;">
                        <span class="chip">
                            <svg aria-hidden="true" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-tag"/></svg><?= esc($job->category_name ?? 'N/A') ?>
                        </span>
                        <span class="chip">
                            <svg aria-hidden="true" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-building"/></svg><?= esc($job->industry_name ?? 'N/A') ?>
                        </span>
                        <span class="chip">
                            <svg aria-hidden="true" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-card"/></svg><?= esc($job->salary_details ?? 'Negotiable') ?>
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <div class="dropdown">
                    <button class="emp-btn emp-btn-outline emp-btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-cog"/></svg>Manage Job
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= base_url('employer/jobs/edit/' . $job->id) ?>">
                                <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"><use href="#i-edit"/></svg>Edit Job
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="toggleFeatureJob(<?= $job->id ?>); return false;">
                                <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"><use href="#i-star"/></svg>
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
                            <a class="dropdown-item text-danger" href="#" onclick="deleteJob(<?= $job->id ?>); return false;">
                                <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px; color: var(--danger);"><use href="#i-trash"/></svg>Delete Job
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <?php if ($job->featured_until && strtotime($job->featured_until) > time()): ?>
            <div class="notice notice--warn" style="margin-top: 16px;">
                <svg aria-hidden="true"><use href="#i-clock"/></svg>
                <div>
                    <strong>Featured until:</strong>
                    <span class="countdown-timer" data-end="<?= $job->featured_until ?>">Calculating...</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Stats Grid -->
<section class="stats stats--jobs" aria-label="Job statistics" style="margin-bottom: 24px;">
    <div class="stat">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($applicationStats['total'])) ?></div>
        <div class="stat-lbl">Total Applications</div>
    </div>
    <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($applicationStats['pending'])) ?></div>
        <div class="stat-lbl">Pending Review</div>
    </div>
    <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-user-check"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($applicationStats['shortlisted'])) ?></div>
        <div class="stat-lbl">Shortlisted</div>
    </div>
    <div class="stat" style="--st-bar:var(--brand-dark)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-eye"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($totalClicks)) ?></div>
        <div class="stat-lbl">Total Views</div>
    </div>
</section>

<!-- Trend Charts -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card">
        <div class="card-head">
            <h3 class="card-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Applications Trend (Last 7 Days)</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-head">
            <h3 class="card-title"><svg aria-hidden="true"><use href="#i-eye"/></svg> Views Trend (Last 7 Days)</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Section -->
<div class="card">
    <div class="card-head" style="padding-bottom: 0; border-bottom: none;">
        <nav class="nav-tabs" role="tablist" style="width: 100%;">
            <button class="nav-link active" data-tab="job-details" role="tab">
                <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"><use href="#i-note"/></svg>Job Details
            </button>
            <button class="nav-link" data-tab="applications" role="tab">
                <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"><use href="#i-users"/></svg>Applications
                <span class="pill pill--reviewed" style="margin-left: 6px;"><?= $applicationStats['total'] ?></span>
            </button>
            <button class="nav-link" data-tab="analytics" role="tab">
                <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"><use href="#i-chart"/></svg>Analytics
            </button>
        </nav>
    </div>
    <div class="card-body" style="padding-top: 0;">
        <div class="tab-content">
            <!-- Job Details Tab -->
            <div class="tab-pane active" id="job-details" role="tabpanel">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 10px;">
                    <div>
                        <div class="job-detail-label">Job Description</div>
                        <div class="job-detail-value" style="line-height: 1.6; color: var(--text);"><?= htmlspecialchars_decode(esc($job->description)) ?></div>

                        <?php if ($job->requirements): ?>
                            <div class="job-detail-label">Requirements</div>
                            <div class="job-detail-value" style="line-height: 1.6; color: var(--text);"><?= htmlspecialchars_decode(esc($job->requirements)) ?></div>
                        <?php endif; ?>

                        <?php if ($job->skills): ?>
                            <div class="job-detail-label">Required Skills</div>
                            <div class="chips" style="margin-bottom: 20px;">
                                <?php foreach (explode(',', $job->skills) as $skill): ?>
                                    <span class="chip"><?= esc(trim($skill)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="job-detail-label">Job Type</div>
                        <div class="job-detail-value"><?= ucfirst(str_replace('-', ' ', $job->job_type)) ?></div>

                        <div class="job-detail-label">Location Type</div>
                        <div class="job-detail-value"><?= ucfirst($job->location_type ?? 'N/A') ?></div>

                        <div class="job-detail-label">Salary</div>
                        <div class="job-detail-value"><?= esc($job->salary_details ?? 'Negotiable') ?></div>

                        <div class="job-detail-label">Education Level</div>
                        <div class="job-detail-value"><?= esc($job->education_level ?? 'N/A') ?></div>

                        <div class="job-detail-label">Experience Level</div>
                        <div class="job-detail-value"><?= esc($job->experience_level ?? 'N/A') ?></div>

                        <div class="job-detail-label">Accommodation</div>
                        <div class="job-detail-value"><?= ucfirst($job->accommodation ?? 'Not Available') ?></div>

                        <div class="job-detail-label">Application Method</div>
                        <div class="job-detail-value">
                            <?= ucfirst($job->application_method) ?>
                            <?php if ($job->application_method === 'whatsapp' && $job->whatsapp_link): ?>
                                <a href="<?= esc($job->whatsapp_link) ?>" target="_blank" class="card-link" style="margin-left: 8px;">
                                    <svg aria-hidden="true"><use href="#i-link"/></svg> View Link
                                </a>
                            <?php elseif ($job->application_method === 'email' && $job->application_email): ?>
                                <a href="mailto:<?= esc($job->application_email) ?>" class="card-link" style="margin-left: 8px;">
                                    <svg aria-hidden="true"><use href="#i-mail"/></svg> <?= esc($job->application_email) ?>
                                </a>
                            <?php elseif ($job->application_method === 'external' && $job->external_url): ?>
                                <a href="<?= esc($job->external_url) ?>" target="_blank" class="card-link" style="margin-left: 8px;">
                                    <svg aria-hidden="true"><use href="#i-link"/></svg> External Link
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applications Tab -->
            <div class="tab-pane" id="applications" role="tabpanel">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
                    <h4 style="font-size: 1rem; font-weight: 700; color: var(--brand-deep); margin: 0;">All Applications (<?= $applicationStats['total'] ?>)</h4>
                    <div class="dropdown">
                        <button class="emp-btn emp-btn-outline emp-btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="filter-btn">
                            <svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-filter"/></svg>Filter by Status
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item filter-status" href="#" data-status="all">All</a></li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="pending">Pending</a></li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="reviewed">Reviewed</a></li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="shortlisted">Shortlisted</a></li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="rejected">Rejected</a></li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="hired">Hired</a></li>
                        </ul>
                    </div>
                </div>

                <div class="tbl-wrap">
                    <table class="tbl tbl--jobs" id="applications-table">
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
                                    <td colspan="5" class="no-lbl">
                                        <div class="empty">
                                            <div class="empty-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></div>
                                            <h3>No applications yet</h3>
                                            <p>Applications will appear here when candidates apply for this job.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($applications as $app): ?>
                                    <tr data-status="<?= esc($app->status) ?>">
                                        <td class="no-lbl">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <?php if ($app->avatar): ?>
                                                    <img src="<?= base_url($app->avatar) ?>" style="border-radius: 50%; width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div style="border-radius: 50%; background: var(--bg); display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 1px solid var(--border);">
                                                        <svg aria-hidden="true" style="width: 20px; height: 20px; color: var(--muted);"><use href="#i-search-user"/></svg>
                                                    </div>
                                                <?php endif; ?>
                                                <strong style="color: var(--brand-deep);"><?= esc($app->fullname ?? 'N/A') ?></strong>
                                            </div>
                                        </td>
                                        <td data-lbl="Contact">
                                            <div style="font-size: 0.8rem; color: var(--muted);">
                                                <div><i class="ti ti-mail"></i> <?= esc($app->email ?? 'N/A') ?></div>
                                                <div><i class="ti ti-phone"></i> <?= esc($app->phone ?? 'N/A') ?></div>
                                            </div>
                                        </td>
                                        <td data-lbl="Applied Date">
                                            <?= date('M d, Y H:i', strtotime($app->created_at)) ?>
                                        </td>
                                        <td data-lbl="Status">
                                            <select class="select status-select" data-id="<?= $app->id ?>" style="min-height: 34px; padding: 4px 30px 4px 10px; font-size: 0.8rem; width: auto; display: inline-block; margin: 0;">
                                                <option value="pending" <?= $app->status == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                                                <option value="reviewed" <?= $app->status == 'reviewed' ? 'selected' : '' ?>>👁️ Reviewed</option>
                                                <option value="shortlisted" <?= $app->status == 'shortlisted' ? 'selected' : '' ?>>⭐ Shortlisted</option>
                                                <option value="rejected" <?= $app->status == 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                                                <option value="hired" <?= $app->status == 'hired' ? 'selected' : '' ?>>✅ Hired</option>
                                            </select>
                                        </td>
                                        <td data-lbl="Actions">
                                            <div class="row-actions">
                                                <a href="<?= base_url('employer/applications/view/' . $app->id) ?>" class="ic-btn" title="View Application">
                                                    <svg aria-hidden="true"><use href="#i-eye"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div class="tab-pane" id="analytics" role="tabpanel">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 10px; margin-bottom: 24px;">
                    <div style="background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; text-align: center;">
                        <h3 style="font-family: 'Sora', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--brand-deep); margin: 0 0 4px 0;"><?= number_format($applicationStats['total']) ?></h3>
                        <small style="color: var(--muted); font-weight: 500;">Total Applications</small>
                    </div>
                    <div style="background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; text-align: center;">
                        <h3 style="font-family: 'Sora', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--success); margin: 0 0 4px 0;"><?= number_format($applicationStats['shortlisted']) ?></h3>
                        <small style="color: var(--muted); font-weight: 500;">Shortlisted</small>
                    </div>
                    <div style="background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; text-align: center;">
                        <h3 style="font-family: 'Sora', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--brand); margin: 0 0 4px 0;"><?= number_format($applicationStats['hired']) ?></h3>
                        <small style="color: var(--muted); font-weight: 500;">Hired</small>
                    </div>
                </div>

                <div style="margin-top: 24px;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 12px;">Conversion Funnel</h4>
                    <?php 
                        $total = $applicationStats['total'];
                        $pendingPct = $total > 0 ? ($applicationStats['pending'] / $total) * 100 : 0;
                        $reviewedPct = $total > 0 ? ($applicationStats['reviewed'] / $total) * 100 : 0;
                        $shortlistedPct = $total > 0 ? ($applicationStats['shortlisted'] / $total) * 100 : 0;
                        $rejectedPct = $total > 0 ? ($applicationStats['rejected'] / $total) * 100 : 0;
                        $hiredPct = $total > 0 ? ($applicationStats['hired'] / $total) * 100 : 0;
                    ?>
                    <div class="progress" style="height: 24px; border-radius: 6px; overflow: hidden; display: flex; background: var(--border);">
                        <?php if ($pendingPct > 0): ?>
                            <div class="progress-bar" style="width: <?= $pendingPct ?>%; background-color: var(--accent); color: var(--brand-deep); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;" title="Pending: <?= $applicationStats['pending'] ?>">
                                <?= round($pendingPct) ?>%
                            </div>
                        <?php endif; ?>
                        <?php if ($reviewedPct > 0): ?>
                            <div class="progress-bar" style="width: <?= $reviewedPct ?>%; background-color: var(--brand); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;" title="Reviewed: <?= $applicationStats['reviewed'] ?>">
                                <?= round($reviewedPct) ?>%
                            </div>
                        <?php endif; ?>
                        <?php if ($shortlistedPct > 0): ?>
                            <div class="progress-bar" style="width: <?= $shortlistedPct ?>%; background-color: #7c3aed; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;" title="Shortlisted: <?= $applicationStats['shortlisted'] ?>">
                                <?= round($shortlistedPct) ?>%
                            </div>
                        <?php endif; ?>
                        <?php if ($hiredPct > 0): ?>
                            <div class="progress-bar" style="width: <?= $hiredPct ?>%; background-color: var(--success); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;" title="Hired: <?= $applicationStats['hired'] ?>">
                                <?= round($hiredPct) ?>%
                            </div>
                        <?php endif; ?>
                        <?php if ($rejectedPct > 0): ?>
                            <div class="progress-bar" style="width: <?= $rejectedPct ?>%; background-color: var(--danger); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;" title="Rejected: <?= $applicationStats['rejected'] ?>">
                                <?= round($rejectedPct) ?>%
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 16px; font-size: 0.8rem; font-weight: 500;">
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><span style="width: 12px; height: 12px; background: var(--accent); border-radius: 3px; display: inline-block;"></span> Pending Review (<?= $applicationStats['pending'] ?>)</span>
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><span style="width: 12px; height: 12px; background: var(--brand); border-radius: 3px; display: inline-block;"></span> Reviewed (<?= $applicationStats['reviewed'] ?>)</span>
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><span style="width: 12px; height: 12px; background: #7c3aed; border-radius: 3px; display: inline-block;"></span> Shortlisted (<?= $applicationStats['shortlisted'] ?>)</span>
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><span style="width: 12px; height: 12px; background: var(--success); border-radius: 3px; display: inline-block;"></span> Hired (<?= $applicationStats['hired'] ?>)</span>
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><span style="width: 12px; height: 12px; background: var(--danger); border-radius: 3px; display: inline-block;"></span> Rejected (<?= $applicationStats['rejected'] ?>)</span>
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
    // Tab Switcher
    document.querySelectorAll('.nav-tabs .nav-link').forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            
            document.querySelectorAll('.nav-tabs .nav-link').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content .tab-pane').forEach(pane => pane.classList.remove('active'));
            
            button.classList.add('active');
            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });

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
                borderColor: '#0861A9',
                backgroundColor: 'rgba(8, 97, 169, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    // Update application status
    $(document).on('change', '.status-select', function() {
        const select = $(this);
        const applicationId = select.data('id');
        const status = select.val();

        $.ajax({
            url: '<?= base_url('employer/applications/update-status') ?>',
            type: 'POST',
            data: {
                application_id: applicationId,
                status: status,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    select.closest('tr').attr('data-status', status);
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message);
                    } else {
                        alert(response.message);
                    }
                    select.val(select.closest('tr').attr('data-status'));
                }
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to update status');
                } else {
                    alert('Failed to update status');
                }
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
                if ($(this).attr('data-status') === status) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });

        // Update button text
        const text = $(this).text();
        $('#filter-btn').html(`<svg aria-hidden="true" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;"><use href="#i-filter"/></svg>Filter: ${text}`);
    });

    // Toggle featured job
    function toggleFeatureJob(jobId) {
        $.ajax({
            url: '<?= base_url('employer/jobs/feature/') ?>/' + jobId,
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to update featured status');
                } else {
                    alert('Failed to update featured status');
                }
            }
        });
    }

    // Delete job
    function deleteJob(jobId) {
        if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
            $.ajax({
                url: '<?= base_url('employer/jobs/delete/') ?>/' + jobId,
                type: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        setTimeout(() => {
                            window.location.href = '<?= base_url('employer/my-jobs') ?>';
                        }, 1000);
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to delete job');
                    } else {
                        alert('Failed to delete job');
                    }
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>