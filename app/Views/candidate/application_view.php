<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
    .app-detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-top: 20px;
    }
    @media (min-width: 992px) {
        .app-detail-grid {
            grid-template-columns: 1fr 1.8fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg> Application Details</h1>
            <p><?= esc($job->title) ?></p>
        </div>
        <div class="page-actions">
            <a href="<?= site_url('candidate/applications') ?>" class="btn btn-outline btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-l"/></svg> Back to Applications
            </a>
        </div>
    </div>

    <div class="app-detail-grid">

        <!-- LEFT SIDEBAR -->
        <section class="card" aria-label="Job metadata" style="padding: 24px; display:flex; flex-direction:column; gap:16px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.05rem; font-weight:800; color:var(--brand-deep); margin:0; border-bottom:1px solid var(--border); padding-bottom:12px;">
                <?= esc($job->title) ?>
            </h3>

            <div>
                <span class="lbl">Company</span>
                <p style="font-size:0.86rem; color:var(--text); font-weight:600; margin:4px 0 0;"><?= esc($job->company_name ?? 'N/A') ?></p>
            </div>

            <div>
                <span class="lbl">Location</span>
                <p style="font-size:0.86rem; color:var(--text); font-weight:600; margin:4px 0 0;"><?= !empty($job->location) ? esc($job->location) . ' State' : 'N/A' ?></p>
            </div>

            <div>
                <span class="lbl">Applied On</span>
                <p style="font-size:0.86rem; color:var(--text); font-weight:600; margin:4px 0 0;"><?= date('M j, Y', strtotime($application->created_at)) ?></p>
            </div>

            <div>
                <span class="lbl">Application Status</span>
                <div style="margin-top:6px;">
                    <?php
                    $colors = [
                        'pending' => 'pending',
                        'reviewed' => 'info',
                        'shortlisted' => 'brand',
                        'rejected' => 'closed',
                        'hired' => 'success'
                    ];
                    $color = $colors[strtolower($application->status)] ?? 'muted';
                    ?>
                    <span class="pill pill--<?= $color ?>" style="font-size:0.78rem; padding:4px 12px; font-weight:700;">
                        <?= ucfirst($application->status) ?>
                    </span>
                </div>
            </div>

            <?php
            // --- Status timeline ---
            $appStatus  = strtolower($application->status);
            $isRejected = $appStatus === 'rejected';
            $rank       = ['pending' => 0, 'reviewed' => 1, 'shortlisted' => 2, 'hired' => 3][$appStatus] ?? 0;
            $reviewedAt = $application->reviewed_at ?? null;

            $steps = [
                [
                    'label' => 'Application submitted',
                    'done'  => true,
                    'date'  => date('M j, Y', strtotime($application->created_at)),
                    'color' => 'var(--brand)',
                ],
                [
                    'label' => 'Viewed by employer',
                    'done'  => $rank >= 1 || $isRejected,
                    'date'  => $reviewedAt ? date('M j, Y', strtotime($reviewedAt)) : null,
                    'color' => 'var(--brand)',
                ],
                [
                    'label' => 'Shortlisted',
                    'done'  => $rank >= 2,
                    'date'  => null,
                    'color' => 'var(--brand)',
                ],
                [
                    'label' => $isRejected ? 'Not selected' : ($appStatus === 'hired' ? 'Hired' : 'Final decision'),
                    'done'  => $appStatus === 'hired' || $isRejected,
                    'date'  => null,
                    'color' => $isRejected ? '#dc3545' : '#198754',
                ],
            ];
            ?>
            <div style="border-top:1px solid var(--border); padding-top:14px;">
                <span class="lbl">Progress</span>
                <div style="display:flex; flex-direction:column; gap:10px; margin-top:8px;">
                    <?php foreach ($steps as $step): ?>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="width:12px; height:12px; border-radius:50%; flex-shrink:0;
                                <?= $step['done']
                                    ? 'background:' . $step['color'] . '; border:2px solid ' . $step['color'] . ';'
                                    : 'background:transparent; border:2px solid var(--border);' ?>"></span>
                            <span style="font-size:0.82rem; <?= $step['done']
                                ? 'font-weight:600; color:' . ($step['color'] === 'var(--brand)' ? 'var(--text)' : $step['color']) . ';'
                                : 'color:var(--muted);' ?>">
                                <?= esc($step['label']) ?>
                            </span>
                            <?php if (!empty($step['date'])): ?>
                                <span style="font-size:0.72rem; color:var(--muted); margin-left:auto;"><?= esc($step['date']) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($application->status_message)): ?>
                <div class="notice notice--info" style="margin-top:12px;">
                    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
                    <div>
                        <strong style="display:block; font-size:0.8rem; margin-bottom:4px;">Message from Employer</strong>
                        <p style="font-size:0.76rem; margin:0; line-height:1.4;"><?= nl2br(esc($application->status_message)) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($application->cv_path): ?>
                <a href="<?= base_url($application->cv_path) ?>" download class="btn btn-primary" style="margin-top:12px;">
                    Download CV
                </a>
            <?php endif; ?>
        </section>

        <!-- RIGHT CONTENT -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- Cover Letter -->
            <section class="card" aria-label="Cover Letter" style="padding: 24px;">
                <h3 style="font-family:'Sora',sans-serif; font-size:0.96rem; font-weight:800; color:var(--brand-deep); margin-bottom:12px;">Cover Letter</h3>
                <div style="font-size:0.86rem; color:var(--muted); line-height:1.6;">
                    <?php if ($application->cover_letter): ?>
                        <p style="margin:0;"><?= nl2br(esc($application->cover_letter)) ?></p>
                    <?php else: ?>
                        <p style="margin:0;" class="text-muted">No cover letter provided.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Additional Info -->
            <section class="card" aria-label="Additional Information" style="padding: 24px;">
                <h3 style="font-family:'Sora',sans-serif; font-size:0.96rem; font-weight:800; color:var(--brand-deep); margin-bottom:18px;">Additional Information</h3>
                
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
                    <div>
                        <span class="lbl">Availability</span>
                        <strong style="display:block; font-size:0.88rem; color:var(--brand-deep); margin-top:4px;">
                            <?= esc(ucfirst(str_replace('_', ' ', $application->availability))) ?>
                        </strong>
                    </div>

                    <div>
                        <span class="lbl">Salary Expectation</span>
                        <strong style="display:block; font-size:0.88rem; color:var(--brand-deep); margin-top:4px;">
                            <?= esc($application->salary_expectation ?: 'Not provided') ?>
                        </strong>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <span class="lbl">Work Eligibility</span>
                        <strong style="display:block; font-size:0.88rem; color:var(--brand-deep); margin-top:4px;">
                            <?= $application->work_eligibility === 'yes' ? 'Eligible' : 'Requires sponsorship' ?>
                        </strong>
                    </div>
                </div>
            </section>

            <!-- References -->
            <section class="card" aria-label="References" style="padding: 24px;">
                <h3 style="font-family:'Sora',sans-serif; font-size:0.96rem; font-weight:800; color:var(--brand-deep); margin-bottom:16px;">References</h3>
                
                <?php if (!empty($references)): ?>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <?php foreach ($references as $ref): ?>
                            <div style="border: 1px solid var(--border); border-radius:8px; padding:16px; display:flex; flex-direction:column; gap:4px;">
                                <strong style="font-size:0.86rem; color:var(--brand-deep);"><?= esc($ref['name']) ?></strong>
                                <span style="font-size:0.78rem; color:var(--muted);"><?= esc($ref['title']) ?></span>
                                <span style="font-size:0.78rem; color:var(--brand);"><?= esc($ref['email']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="font-size:0.84rem; color:var(--muted); margin:0;">No references provided.</p>
                <?php endif; ?>
            </section>

        </div>

    </div>

</div>
<?= $this->endSection() ?>