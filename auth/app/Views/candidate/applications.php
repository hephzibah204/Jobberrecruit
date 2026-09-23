<?php
$page_title = 'My Applications';
/**
 * ════════════════════════════════════════════════════════════════════════
 *  JOBBERRECRUIT — CANDIDATE APPLICATIONS PAGE
 *  Refactored to match the design spec (candidate-applications.html)
 * ════════════════════════════════════════════════════════════════════════
 */
?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
@keyframes rise{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
html.anim-ready .content>*{animation:rise .34s ease both}
html.anim-ready .content>*:nth-child(2){animation-delay:.05s}
html.anim-ready .content>*:nth-child(3){animation-delay:.1s}
html.anim-ready .content>*:nth-child(4){animation-delay:.15s}
html.anim-ready .content>*:nth-child(5){animation-delay:.2s}
html.anim-ready .content>*:nth-child(n+6){animation-delay:.24s}
.card{box-shadow:0 1px 3px rgba(10,47,87,.06);transition:var(--transition)}
.card:hover{box-shadow:0 2px 10px rgba(10,47,87,.07)}
.btn{transition:transform .12s cubic-bezier(.2,.8,.2,1),box-shadow .18s ease,background-color .18s ease,border-color .18s ease}
.btn:active{transform:scale(.97)}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){.btn{transition:background-color .12s ease,border-color .12s ease!important}.btn:active,.btn:hover{transform:none!important}}
.how-strip .how-step{display:flex;gap:12px;align-items:flex-start}
.how-n{width:32px;height:32px;border-radius:50%;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.82rem;flex-shrink:0}
.how-step b{display:block;font-size:.82rem;color:var(--brand-deep);margin-bottom:2px}
.how-step p{font-size:.74rem;color:var(--muted);line-height:1.5}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- ══ PAGE HEADER ══ -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;"><use href="#i-doc"/></svg> My Applications</h1>
            <p>Track the status of every job you've applied to.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('jobs') ?>" class="btn btn-accent btn-sm">
                <svg aria-hidden="true"><use href="#i-search"/></svg> Browse Jobs
            </a>
        </div>
    </div>

    <!-- ══ STATS ROW ══ -->
    <?php if (!empty($applications)): ?>
    <div class="stats" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: clamp(10px, 1.4vw, 16px);">
        <div class="stat stat--apps">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
            </div>
            <div class="stat-num"><?= count($applications) ?></div>
            <div class="stat-lbl">Total Applications</div>
        </div>

        <?php
        $pending  = 0; $reviewed = 0; $shortlisted = 0; $hired = 0;
        foreach ($applications as $app) {
            $s = strtolower($app->status ?? '');
            if ($s === 'pending')              $pending++;
            elseif (in_array($s, ['reviewed', 'shortlisted'])) $reviewed++;
            elseif ($s === 'hired' || $s === 'accepted')        $hired++;
        }
        ?>
        <div class="stat">
            <div class="stat-top">
                <span class="stat-ic" style="--st-icbg:var(--accent-light);--st-ic:var(--accent-dark);">
                    <svg aria-hidden="true"><use href="#i-clock"/></svg>
                </span>
            </div>
            <div class="stat-num"><?= $pending ?></div>
            <div class="stat-lbl">Pending Review</div>
        </div>

        <div class="stat stat--apps">
            <div class="stat-top">
                <span class="stat-ic" style="--st-icbg:var(--brand-light);--st-ic:var(--brand);">
                    <svg aria-hidden="true"><use href="#i-search-user"/></svg>
                </span>
            </div>
            <div class="stat-num"><?= $reviewed ?></div>
            <div class="stat-lbl">Reviewed / Shortlisted</div>
        </div>

        <div class="stat stat--hires">
            <div class="stat-top">
                <span class="stat-ic" style="--st-icbg:var(--success-light);--st-ic:var(--success);">
                    <svg aria-hidden="true"><use href="#i-user-check"/></svg>
                </span>
            </div>
            <div class="stat-num"><?= $hired ?></div>
            <div class="stat-lbl">Hired / Accepted</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══ APPLICATIONS CARD ══ -->
    <section class="card" aria-label="Applications">
        <div class="card-head">
            <span class="card-title">
                <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-doc"/></svg>
                All Applications
            </span>
            <div class="toolbar">
                <div class="search-wrap">
                    <svg aria-hidden="true"><use href="#i-search"/></svg>
                    <input type="text" id="app-search" class="input" placeholder="Search your applications…" aria-label="Search applications">
                </div>
                <select class="select" id="app-status-filter" aria-label="Filter by status">
                    <option value="">All statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Shortlisted">Shortlisted</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Hired">Hired</option>
                </select>
            </div>
        </div>

        <?php if (empty($applications)): ?>
            <!-- ══ EMPTY STATE ══ -->
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
                <h3>No applications yet</h3>
                <p>When you apply for jobs, you'll track every status change here — and we'll notify you the moment an employer responds.</p>
                <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">
                    <svg aria-hidden="true"><use href="#i-search"/></svg> Find Jobs to Apply
                </a>
            </div>
        <?php else: ?>
            <!-- ══ POPULATED TABLE ══ -->
            <div class="card-body p-0">
                <div class="tbl-wrap">
                    <table class="tbl" id="applications-table">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Applied On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <?php
                                $status      = $app->status ?? 'Pending';
                                $statusLower = strtolower($status);
                                $statusClass = 'pill--pending';
                                $statusLabel = 'Pending';
                                if (in_array($statusLower, ['hired', 'accepted'])) {
                                    $statusClass = 'pill--hired';
                                    $statusLabel = 'Hired';
                                } elseif ($statusLower === 'rejected') {
                                    $statusClass = 'pill--rejected';
                                    $statusLabel = 'Rejected';
                                } elseif (in_array($statusLower, ['reviewed', 'shortlisted'])) {
                                    $statusClass = 'pill--reviewed';
                                    $statusLabel = 'Shortlisted';
                                } elseif ($statusLower === 'withdrawn') {
                                    $statusClass = 'pill--closed';
                                    $statusLabel = 'Withdrawn';
                                }
                                ?>
                                <tr>
                                    <td><b><?= esc($app->job_title ?? '—') ?></b></td>
                                    <td><?= esc($app->company_name ?? 'N/A') ?></td>
                                    <td>
                                        <span class="pill <?= $statusClass ?>"><?= esc($statusLabel) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($app->created_at ?? 'now')) ?></td>
                                    <td>
                                        <div class="row-actions">
                                            <a href="<?= base_url('job/view/' . ($app->job_id ?? 0)) ?>" class="btn btn-outline btn-sm">
                                                <svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-eye"/></svg> View
                                            </a>
                                            <a href="<?= base_url('candidate/applications/view/' . ($app->id ?? 0)) ?>" class="btn btn-primary btn-sm">
                                                <svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-doc"/></svg> Details
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ══ PAGINATION ══ -->
            <div class="pager">
                <span>Showing <?= min(count($applications), 10) ?> of <?= count($applications) ?> applications</span>
                <nav class="pager-nav" aria-label="Pagination">
                    <a href="#" class="pager-btn on" aria-current="page">1</a>
                    <a href="#" class="pager-btn">2</a>
                    <a href="#" class="pager-btn">3</a>
                    <a href="#" class="pager-btn" aria-label="Next page">
                        <svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-arrow-r"/></svg>
                    </a>
                </nav>
            </div>
        <?php endif; ?>
    </section>

    <!-- ══ HOW IT WORKS ══ -->
    <section class="card" aria-label="How applications work" style="margin-top:0">
        <div class="card-head">
            <span class="card-title">
                <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-bulb"/></svg>
                How It Works
            </span>
        </div>
        <div class="card-body">
            <div class="how-strip" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
                <div class="how-step">
                    <span class="how-n">1</span>
                    <div>
                        <b>Apply</b>
                        <p>Submit your CV and cover message in about 2 minutes.</p>
                    </div>
                </div>
                <div class="how-step">
                    <span class="how-n">2</span>
                    <div>
                        <b>Pending</b>
                        <p>The employer receives your application instantly.</p>
                    </div>
                </div>
                <div class="how-step">
                    <span class="how-n">3</span>
                    <div>
                        <b>Reviewed / Shortlisted</b>
                        <p>We email you the moment your status changes.</p>
                    </div>
                </div>
                <div class="how-step">
                    <span class="how-n">4</span>
                    <div>
                        <b>Hired 🎉</b>
                        <p>A complete profile makes every application stronger.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
    'use strict';
    // ── Client-side status filter ──
    var filter = document.getElementById('app-status-filter');
    var table  = document.getElementById('applications-table');

    if (filter && table) {
        var rows = table.querySelectorAll('tbody tr');

        filter.addEventListener('change', function() {
            var val = this.value.toLowerCase().trim();
            rows.forEach(function(row) {
                var statusCell = row.querySelector('.pill');
                if (!statusCell) return;
                var text = (statusCell.textContent || '').trim().toLowerCase();
                if (!val || text === val) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // ── Client-side search ──
    var search = document.getElementById('app-search');
    if (search && table) {
        var rows = table.querySelectorAll('tbody tr');
        search.addEventListener('input', function() {
            var q = this.value.toLowerCase().trim();
            rows.forEach(function(row) {
                var match = false;
                var cells = row.querySelectorAll('td');
                for (var i = 0; i < cells.length; i++) {
                    if ((cells[i].textContent || '').toLowerCase().indexOf(q) !== -1) {
                        match = true;
                        break;
                    }
                }
                row.style.display = match ? '' : 'none';
            });
        });
    }
requestAnimationFrame(function(){document.documentElement.classList.add('anim-ready')});
})();
</script>
<?= $this->endSection() ?>