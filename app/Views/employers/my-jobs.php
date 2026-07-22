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
.pill--paused {
    background: #f0f4f8;
    color: var(--muted);
    border: 1px solid var(--border);
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
        $statTotalApplicants += intval(is_object($job) ? ($job->applicants_count ?? $job->applicants ?? 0) : ($job['applicants_count'] ?? $job['applicants'] ?? 0));
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
                <option value="paused">Paused</option>
                <option value="pending">Pending</option>
            </select>
            <select class="select" id="sort-filter" aria-label="Sort jobs">
                <option value="newest">Newest first</option>
                <option value="views">Most views</option>
                <option value="applicants">Most applicants</option>
                <option value="closing">Closing soon</option>
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
                        $applicantsVal = is_object($job) ? ($job->applicants_count ?? $job->applicants ?? 0) : ($job['applicants_count'] ?? $job['applicants'] ?? 0);

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
                        } elseif (in_array($status, ['paused'])) {
                            $pillClass = 'pill--paused';
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
                                    <?php $isOpen = strtolower((string) $statusVal) === 'open'; ?>
                                    <form action="<?= base_url('employer/jobs/pause/' . esc($jobId)) ?>" method="post" class="d-inline" onsubmit="return confirm('<?= $isOpen ? 'Pause this job? It will be hidden from job seekers.' : 'Reopen this job so it appears in listings again?' ?>');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="ic-btn" aria-label="<?= $isOpen ? 'Pause job' : 'Reopen job' ?>" title="<?= $isOpen ? 'Pause' : 'Reopen' ?>">
                                            <svg aria-hidden="true"><use href="#<?= $isOpen ? 'i-pause' : 'i-refresh' ?>"/></svg>
                                        </button>
                                    </form>
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
    
    // Client-side quick filter + sort
    var q = document.getElementById('job-search');
    var statusSelect = document.getElementById('status-filter');
    var sortSelect = document.getElementById('sort-filter');
    var tbody = document.querySelector('#jobs-table tbody');
    var rows = Array.from(document.querySelectorAll('#jobs-table tbody tr'));
    
    function filterAndSort() {
        var queryValue = q ? q.value.toLowerCase() : '';
        var statusValue = statusSelect ? statusSelect.value.toLowerCase() : 'all';
        var sortValue = sortSelect ? sortSelect.value : 'newest';
        
        // Filter
        var visible = rows.filter(function(row) {
            var text = row.textContent.toLowerCase();
            var rowStatus = row.getAttribute('data-status') || '';
            var matchesQuery = text.indexOf(queryValue) > -1;
            var matchesStatus = statusValue === 'all' || rowStatus === statusValue;
            row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
            return matchesQuery && matchesStatus;
        });

        // Sort visible rows in the DOM
        var sorted = visible.slice().sort(function(a, b) {
            if (sortValue === 'views') {
                var va = parseInt(a.querySelector('[data-lbl="Views"] .metric') ? a.querySelector('[data-lbl="Views"] .metric').textContent.replace(/,/g,'') : 0, 10) || 0;
                var vb = parseInt(b.querySelector('[data-lbl="Views"] .metric') ? b.querySelector('[data-lbl="Views"] .metric').textContent.replace(/,/g,'') : 0, 10) || 0;
                return vb - va;
            } else if (sortValue === 'applicants') {
                var aa = parseInt(a.querySelector('[data-lbl="Applicants"] .metric') ? a.querySelector('[data-lbl="Applicants"] .metric').textContent.replace(/,/g,'') : 0, 10) || 0;
                var ab = parseInt(b.querySelector('[data-lbl="Applicants"] .metric') ? b.querySelector('[data-lbl="Applicants"] .metric').textContent.replace(/,/g,'') : 0, 10) || 0;
                return ab - aa;
            } else if (sortValue === 'closing') {
                var da = Date.parse((a.querySelector('[data-lbl="Closes"]') || {}).textContent || '2099') || Infinity;
                var db = Date.parse((b.querySelector('[data-lbl="Closes"]') || {}).textContent || '2099') || Infinity;
                return da - db;
            }
            // Default: newest first — preserve original DOM order
            return rows.indexOf(a) - rows.indexOf(b);
        });

        // Re-append in sorted order
        sorted.forEach(function(row) { tbody.appendChild(row); });
    }
    
    if (q) q.addEventListener('input', filterAndSort);
    if (statusSelect) statusSelect.addEventListener('change', filterAndSort);
    if (sortSelect) sortSelect.addEventListener('change', filterAndSort);
})();
</script>
<?= $this->endSection() ?>