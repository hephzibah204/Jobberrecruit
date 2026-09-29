<?php $page_title = 'Viewed Jobs'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-search"/></svg> Recently Viewed Jobs</h1>
            <p>Jobs you've recently explored</p>
        </div>
    </div>

    <!-- Table Card -->
    <section class="card" aria-label="Viewed Jobs list" style="padding: 24px;">
        <div class="table-responsive">
            <table class="table" id="viewed-jobs-table" style="width:100%; border-collapse:collapse; font-size:0.84rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border); text-align: left; color: var(--muted); font-weight: 700;">
                        <th style="padding:12px 8px;">Job Title</th>
                        <th style="padding:12px 8px;">Company</th>
                        <th style="padding:12px 8px;">Location</th>
                        <th style="padding:12px 8px;">Status</th>
                        <th style="padding:12px 8px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($viewedJobs)): ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:32px; color:var(--muted);">
                                <p style="margin-bottom:12px;">You haven't viewed any jobs recently.</p>
                                <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Browse Jobs</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($viewedJobs as $job): ?>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding:14px 8px; font-weight:600; color:var(--brand-deep);"><?= esc($job->title) ?></td>
                                <td style="padding:14px 8px; color:var(--text);"><?= esc($job->company_name ?: 'N/A') ?></td>
                                <td style="padding:14px 8px; color:var(--muted);"><?= esc($job->location ?: 'N/A') ?></td>
                                <td style="padding:14px 8px;">
                                    <span class="pill pill--<?= $job->status === 'open' ? 'success' : 'closed' ?>" style="font-size:0.7rem; padding:2px 8px;">
                                        <?= ucfirst($job->status) ?>
                                    </span>
                                </td>
                                <td style="padding:14px 8px;">
                                    <a href="<?= site_url('job/view/' . $job->id) ?>" class="btn btn-primary btn-sm">
                                        View Again
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>
<?= $this->endSection() ?>

