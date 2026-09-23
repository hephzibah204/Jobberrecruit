<?php $page_title = 'My Jobs'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.row-actions {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  flex-wrap: nowrap;
}
.ic-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 6px;
  border: 1px solid var(--border, #e2e8f0);
  background: #ffffff;
  color: var(--brand, #0861A9);
  cursor: pointer;
  transition: all 0.15s ease;
  text-decoration: none;
  flex-shrink: 0;
}
.ic-btn:hover {
  background: #f1f5f9;
  border-color: var(--brand, #0861A9);
  color: var(--brand-dark, #064A85);
}
.ic-btn svg {
  width: 16px;
  height: 16px;
  stroke: currentColor;
  fill: none;
  flex-shrink: 0;
}
.ic-btn--preview {
  background: #eff6ff !important;
  border-color: #bfdbfe !important;
  color: #1d4ed8 !important;
}
.ic-btn--preview:hover {
  background: #1d4ed8 !important;
  border-color: #1d4ed8 !important;
  color: #ffffff !important;
}
.ic-btn--preview svg {
  stroke: currentColor !important;
  fill: none !important;
}
.ic-btn--close {
  border-color: #fee2e2 !important;
  background: #fef2f2 !important;
  color: #ef4444 !important;
}
.ic-btn--close:hover {
  background: #ef4444 !important;
  border-color: #ef4444 !important;
  color: #ffffff !important;
}
.ic-btn--close:hover svg {
  color: #ffffff !important;
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
    <div>
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
                <option value="open">Open (Active)</option>
                <option value="closed">Closed</option>
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
                        $jobSlug = is_object($job) ? ($job->slug ?? $jobId) : ($job['slug'] ?? $jobId);
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

                        // 1-Hour Edit Window restriction
                        $createdTimestamp = !empty($createdAt) ? strtotime($createdAt) : 0;
                        $isEditable = ($createdTimestamp >= (time() - 3600));
                        $isOpen = in_array($status, ['open', 'active', 'success']);
                        $rowCategory = $isOpen ? 'open' : ($status === 'pending' ? 'pending' : 'closed');
                    ?>
                        <tr data-status="<?= esc($status) ?>" data-category="<?= esc($rowCategory) ?>">
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
                                    <a href="<?= base_url('employer/jobs/view/' . esc($jobId)) ?>" class="ic-btn" aria-label="View job" title="View Details">
                                        <svg aria-hidden="true"><use href="#i-eye"/></svg>
                                    </a>
                                    
                                    <?php if ($isEditable): ?>
                                        <a href="<?= base_url('employer/jobs/edit/' . esc($jobId)) ?>" class="ic-btn" aria-label="Edit job" title="Edit Listing (within 1h window)">
                                            <svg aria-hidden="true"><use href="#i-edit"/></svg>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="ic-btn" style="opacity:0.6;cursor:pointer" onclick="showEditRestrictedModal('<?= esc($title, 'js') ?>')" aria-label="Edit restricted" title="Edit restricted (after 1 hour)">
                                            <svg aria-hidden="true" style="color:var(--muted);"><use href="#i-edit"/></svg>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Public Preview Icon -->
                                    <?php $previewUrl = !empty($jobSlug) ? base_url('jobs/' . esc($jobSlug)) : base_url('employer/jobs/preview/' . esc($jobId)); ?>
                                    <a href="<?= $previewUrl ?>" class="ic-btn ic-btn--preview" aria-label="Preview job listing on public site" title="Preview on public site" target="_blank">
                                        <svg aria-hidden="true"><use href="#i-external-link"/></svg>
                                    </a>

                                    <!-- Repost Action -->
                                    <button type="button" class="ic-btn" onclick="openRepostModal(<?= esc($jobId) ?>, '<?= esc($title, 'js') ?>')" aria-label="Repost job" title="Repost this job (new 30-day cycle)">
                                        <svg aria-hidden="true" style="color:var(--brand);"><use href="#i-refresh"/></svg>
                                    </button>

                                    <!-- Close Job Action (Replaces Pause) -->
                                    <?php if ($isOpen): ?>
                                        <form action="<?= base_url('employer/jobs/close/' . esc($jobId)) ?>" method="post" class="d-inline" onsubmit="return confirm('Close this job? The listing will no longer accept new applications, but all existing applications will remain accessible.');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="ic-btn ic-btn--close" aria-label="Close job" title="Close Job (stop accepting applications)">
                                                <svg aria-hidden="true"><use href="#i-x"/></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Repost Job Modal -->
    <div class="modal" id="repostModal" style="display:none;" aria-hidden="true">
        <div class="modal-card" style="max-width:480px;background:var(--card,#fff);border-radius:12px;padding:24px;box-shadow:0 10px 25px rgba(0,0,0,0.15);position:relative;margin:auto;">
            <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px;color:var(--brand-deep)">
                <svg style="width:18px;height:18px;vertical-align:-2px;color:var(--brand);margin-right:6px;" aria-hidden="true"><use href="#i-refresh"/></svg>
                Repost Job Listing
            </h3>
            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:16px;">
                You are about to repost <strong id="repostJobTitle" style="color:var(--text)"></strong>.
            </p>
            <div style="background:var(--bg,#f8fafc);border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:0.82rem;line-height:1.5;border-left:4px solid var(--brand)">
                <p style="margin:0 0 6px 0;"><strong>Reposting Details:</strong></p>
                <ul style="margin:0;padding-left:18px;color:var(--muted)">
                    <li>Consumes <strong>1 Job Credit</strong> from your subscription quota, or standard pay-as-you-go fee.</li>
                    <li>Starts a brand new <strong>30-day posting cycle</strong>.</li>
                    <li>Pins your listing as recently updated for maximum candidate reach.</li>
                </ul>
            </div>
            <form id="repostJobForm" method="post" action="">
                <?= csrf_field() ?>
                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                    <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" onclick="closeRepostModal()">Cancel</button>
                    <button type="submit" class="emp-btn emp-btn-accent emp-btn-sm">Confirm &amp; Repost</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 1-Hour Edit Restricted Notice Modal -->
    <div class="modal" id="editRestrictedModal" style="display:none;" aria-hidden="true">
        <div class="modal-card" style="max-width:460px;background:var(--card,#fff);border-radius:12px;padding:24px;box-shadow:0 10px 25px rgba(0,0,0,0.15);position:relative;margin:auto;">
            <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px;color:var(--brand-deep)">
                <svg style="width:18px;height:18px;vertical-align:-2px;color:var(--warning,#f59e0b);margin-right:6px;" aria-hidden="true"><use href="#i-clock"/></svg>
                Editing Window Expired
            </h3>
            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:16px;line-height:1.5;">
                Direct job listing edits are only permitted within <strong>1 hour</strong> of posting to protect application integrity.
            </p>
            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:20px;line-height:1.5;">
                Need to make modifications to <strong id="restrictedJobTitle" style="color:var(--text)"></strong>? Our support team will assist you immediately!
            </p>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <a href="https://wa.me/2349014808902?text=Hello%20JobberRecruit%20Support%2C%20I%20need%20to%20request%20an%20edit%20to%20my%20job%20listing." target="_blank" class="emp-btn emp-btn-secondary emp-btn-sm" style="justify-content:center;">
                    <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-whatsapp"/></svg> Contact Support on WhatsApp
                </a>
                <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" onclick="closeEditRestrictedModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pager">
        <span>Showing <b><?= count($jobs) ?></b> of <b><?= count($jobs) ?></b> jobs</span>
        <div class="pager-nav" id="pagination-controls">
            <a class="pager-btn" href="#" aria-disabled="true" aria-label="Previous page"><svg style="width:14px;height:14px" aria-hidden="true"><use href="#i-arrow-l"/></svg></a>
            <a class="pager-btn on" href="#" aria-current="page">1</a>
            <a class="pager-btn" href="#" aria-disabled="true" aria-label="Next page"><svg style="width:14px;height:14px" aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
        </div>
    </div>
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
            var rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
            var rowCategory = (row.getAttribute('data-category') || rowStatus).toLowerCase();
            var matchesQuery = text.indexOf(queryValue) > -1;
            var matchesStatus = statusValue === 'all' || rowStatus === statusValue || rowCategory === statusValue;
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

function openRepostModal(jobId, jobTitle) {
    var modal = document.getElementById('repostModal');
    var titleEl = document.getElementById('repostJobTitle');
    var formEl = document.getElementById('repostJobForm');
    if (titleEl) titleEl.textContent = '"' + jobTitle + '"';
    if (formEl) formEl.action = '<?= base_url('employer/jobs/repost/') ?>/' + jobId;
    if (modal) {
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }
}

function closeRepostModal() {
    var modal = document.getElementById('repostModal');
    if (modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }
}

function showEditRestrictedModal(jobTitle) {
    var modal = document.getElementById('editRestrictedModal');
    var titleEl = document.getElementById('restrictedJobTitle');
    if (titleEl) titleEl.textContent = '"' + jobTitle + '"';
    if (modal) {
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }
}

function closeEditRestrictedModal() {
    var modal = document.getElementById('editRestrictedModal');
    if (modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }
}
</script>
<?= $this->endSection() ?>