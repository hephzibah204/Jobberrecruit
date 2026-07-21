<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.stats--jobs {
    grid-template-columns: repeat(4, 1fr);
}
@media (max-width: 1100px) {
    .stats--jobs {
        grid-template-columns: repeat(2, 1fr);
    }
}
.job-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
.job-cell-title {
    font-weight: 600;
    font-size: .85rem;
    color: var(--brand-deep);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.job-cell-title a {
    color: inherit;
}
.job-cell-title a:hover {
    color: var(--brand);
}
.job-cell-sub {
    font-size: .7rem;
    color: var(--muted);
    margin-top: 2px;
}
.metric {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    color: var(--brand-deep);
}
.metric i {
    font-style: normal;
    display: block;
    font-size: .62rem;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
}
@media (max-width: 760px) {
    .tbl--jobs {
        min-width: 0;
    }
    .tbl--jobs thead {
        display: none;
    }
    .tbl--jobs tr {
        display: block;
        border-bottom: 1px solid var(--border);
        padding: 14px 4px;
    }
    .tbl--jobs td {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: none;
        padding: 5px 0;
        gap: 10px;
    }
    .tbl--jobs td::before {
        content: attr(data-lbl);
        font-size: .66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--muted);
    }
    .tbl--jobs td.no-lbl::before {
        display: none;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Safely compute or get statistics from passed variables / fallback
$statTotalJobs = isset($totalJobs) ? $totalJobs : count($jobs);
$statOpenJobs = isset($activeJobs) ? $activeJobs : 0;
$statTotalViews = isset($totalClicks) ? $totalClicks : 0;
$statTotalApplicants = isset($totalApplications) ? $totalApplications : 0;

if (!isset($activeJobs) || !isset($totalClicks) || !isset($totalApplications)) {
    $statOpenJobs = 0;
    $statTotalViews = 0;
    $statTotalApplicants = 0;
    foreach ($jobs as $job) {
        $status = strtolower(is_object($job) ? ($job->status ?? '') : ($job['status'] ?? ''));
        if (in_array($status, ['open', 'active', 'success'])) {
            $statOpenJobs++;
        }
        $statTotalViews += intval(is_object($job) ? ($job->views ?? 0) : ($job['views'] ?? 0));
        $statTotalApplicants += intval(is_object($job) ? ($job->applicants_count ?? $job['applicants'] ?? 0) : ($job['applicants_count'] ?? $job['applicants'] ?? 0));
    }
}
?>

<div class="page-head">
    <div class="page-head-left">
        <h1><svg aria-hidden="true"><use href="#i-briefcase"/></svg> My Jobs</h1>
        <p>Manage all your job postings in one place.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('employer/jobs/export') ?>" class="emp-btn emp-btn-outline emp-btn-sm"><svg aria-hidden="true"><use href="#i-download"/></svg> Export</a>
        <a href="<?= base_url('employer/post-job') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-plus"/></svg> Post New Job</a>
    </div>
</div>

<section class="stats stats--jobs" aria-label="Job statistics">
    <div class="stat">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($statTotalJobs)) ?></div>
        <div class="stat-lbl">Total Jobs</div>
    </div>
    <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-zap"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($statOpenJobs)) ?></div>
        <div class="stat-lbl">Open</div>
    </div>
    <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-eye"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($statTotalViews)) ?></div>
        <div class="stat-lbl">Total Views</div>
    </div>
    <div class="stat" style="--st-bar:var(--brand-dark)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span></div>
        <div class="stat-num"><?= esc(number_format($statTotalApplicants)) ?></div>
        <div class="stat-lbl">Total Applicants</div>
    </div>
</section>

<section class="card" aria-label="Job listings">
    <div class="card-head">
        <div class="toolbar" style="flex:1">
            <div class="search-wrap">
                <svg aria-hidden="true"><use href="#i-search"/></svg>
                <input class="input" id="job-search" type="search" placeholder="Search your jobs…" aria-label="Search jobs">
            </div>
            <select class="select" id="status-filter" aria-label="Filter by status">
                <option value="all">All statuses</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>

    <?php if (empty($jobs)): ?>
        <div class="empty">
            <div class="empty-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></div>
            <h3>No Jobs Found</h3>
            <p>You haven't posted any jobs yet. Create a new job listing to start receiving applications.</p>
        </div>
    <?php else: ?>
        <div class="tbl-wrap">
            <table class="tbl tbl--jobs" id="jobs-table">
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Applicants</th>
                        <th>Closes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): 
                        $jobId = is_object($job) ? $job->id : $job['id'];
                        $title = is_object($job) ? $job->title : $job['title'];
                        $jobType = is_object($job) ? $job->job_type : $job['job_type'];
                        $createdAt = is_object($job) ? $job->created_at : $job['created_at'];
                        $deadline = is_object($job) ? $job->deadline : $job['deadline'];
                        $statusVal = is_object($job) ? $job->status : $job['status'];
                        $viewsVal = is_object($job) ? ($job->views ?? 0) : ($job['views'] ?? 0);
                        $applicantsVal = is_object($job) ? ($job->applicants_count ?? $job['applicants'] ?? 0) : ($job['applicants_count'] ?? $job['applicants'] ?? 0);

                        $status = strtolower($statusVal);
                        $pillClass = 'pill--closed';
                        if (in_array($status, ['pending'])) {
                            $pillClass = 'pill--pending';
                        } elseif (in_array($status, ['reviewed'])) {
                            $pillClass = 'pill--reviewed';
                        } elseif (in_array($status, ['shortlisted'])) {
                            $pillClass = 'pill--shortlisted';
                        } elseif (in_array($status, ['hired', 'open', 'active', 'success'])) {
                            $pillClass = 'pill--open';
                        } elseif (in_array($status, ['rejected', 'closed', 'expired'])) {
                            $pillClass = 'pill--closed';
                        }
                    ?>
                        <tr data-status="<?= esc($status) ?>">
                            <td class="no-lbl">
                                <div class="job-cell">
                                    <span class="ava" aria-hidden="true">
                                        <svg style="width:16px;height:16px" aria-hidden="true"><use href="#i-briefcase"/></svg>
                                    </span>
                                    <div>
                                        <div class="job-cell-title">
                                            <a href="<?= base_url('employer/jobs/view/' . esc($jobId)) ?>"><?= esc($title) ?></a>
                                        </div>
                                        <div class="job-cell-sub">
                                            <?= esc(ucwords(str_replace('-', ' ', $jobType))) ?> · Posted <?= date('d M Y', strtotime($createdAt)) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-lbl="Status">
                                <span class="pill <?= $pillClass ?>"><?= esc(ucfirst($statusVal)) ?></span>
                            </td>
                            <td data-lbl="Views">
                                <span class="metric"><?= esc(number_format($viewsVal)) ?><i>views</i></span>
                            </td>
                            <td data-lbl="Applicants">
                                <span class="metric"><?= esc(number_format($applicantsVal)) ?><i>applicants</i></span>
                            </td>
                            <td data-lbl="Closes">
                                <?= !empty($deadline) ? date('d M Y', strtotime($deadline)) : 'N/A' ?>
                            </td>
                            <td data-lbl="Actions">
                                <div class="row-actions">
                                    <a href="<?= base_url('employer/jobs/view/' . esc($jobId)) ?>" class="ic-btn" aria-label="View job" title="View">
                                        <svg aria-hidden="true"><use href="#i-eye"/></svg>
                                    </a>
                                    <a href="<?= base_url('employer/jobs/edit/' . esc($jobId)) ?>" class="ic-btn" aria-label="Edit job" title="Edit">
                                        <svg aria-hidden="true"><use href="#i-edit"/></svg>
                                    </a>
                                    <a href="<?= base_url('employer/jobs/pause/' . esc($jobId)) ?>" class="ic-btn" aria-label="Pause job" title="Pause">
                                        <svg aria-hidden="true"><use href="#i-pause"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/jobs/export') ?>" class="emp-btn emp-btn-outline"><svg aria-hidden="true"><use href="#i-download"/></svg> Export</a>
<a href="<?= base_url('employer/post-job') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-plus"/></svg> Post New Job</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
    'use strict';
    
    // Client-side quick filter
    var q = document.getElementById('job-search');
    var statusSelect = document.getElementById('status-filter');
    var rows = document.querySelectorAll('#jobs-table tbody tr');
    
    function filterTable() {
        var queryValue = q ? q.value.toLowerCase() : '';
        var statusValue = statusSelect ? statusSelect.value.toLowerCase() : 'all';
        
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var rowStatus = row.getAttribute('data-status') || '';
            
            var matchesQuery = text.indexOf(queryValue) > -1;
            var matchesStatus = statusValue === 'all' || rowStatus === statusValue;
            
            if (matchesQuery && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    if (q) q.addEventListener('input', filterTable);
    if (statusSelect) statusSelect.addEventListener('change', filterTable);
})();
</script>
<?= $this->endSection() ?>