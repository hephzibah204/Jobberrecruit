<?php $page_title = 'My Courses'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<div class="page-hd">
    <div class="page-hd-left">
        <h1>My Enrolled Courses</h1>
        <p>Track your enrolled courses and download your certifications</p>
    </div>
    <div class="page-hd-actions">
        <a href="<?= base_url('training') ?>" class="emp-btn emp-btn-primary">
            <svg aria-hidden="true"><use href="#i-plus"/></svg> Browse Courses
        </a>
    </div>
</div>

<?php if (empty($enrollments)): ?>
    <div class="card">
        <div class="card-body empty-state" style="padding: 48px 24px;">
            <div class="empty-ic">
                <svg aria-hidden="true" style="width:28px; height:28px;"><use href="#i-book"/></svg>
            </div>
            <h3>No enrolled courses yet</h3>
            <p>Start your learning journey or upscale your team by enrolling in a course.</p>
            <a href="<?= base_url('training') ?>" class="emp-btn emp-btn-accent" style="margin-top:8px;">
                Explore Courses
            </a>
        </div>
    </div>
<?php else: ?>
    <!-- Premium course grid matching mobile/desktop viewport reflow -->
    <div class="tri" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
        <?php foreach ($enrollments as $enrollment): ?>
            <?php
            $isCompleted = $enrollment->status === 'completed';
            $isPaid = (float) ($enrollment->amount ?? 0) > 0;
            $rolePrefix = 'employer';
            ?>
            <div class="card">
                <div style="height: 160px; overflow: hidden; background: var(--brand-light); position: relative; display: flex; align-items: center; justify-content: center;">
                    <?php if ($enrollment->thumbnail): ?>
                        <img src="<?= base_url($enrollment->thumbnail) ?>" alt="<?= esc($enrollment->course_title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <svg aria-hidden="true" style="width: 48px; height: 48px; color: var(--brand);"><use href="#i-book"/></svg>
                    <?php endif; ?>
                    <span class="pill <?= $isCompleted ? 'pill--hired' : ($enrollment->status === 'enrolled' ? 'pill--reviewed' : 'pill--pending') ?>" style="position: absolute; top: 10px; right: 10px;">
                        <?= esc(ucfirst($enrollment->status)) ?>
                    </span>
                </div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 12px; padding: 16px;">
                    <h3 style="font-size: 0.92rem; font-weight: 700; color: var(--brand-deep); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="<?= esc($enrollment->course_title) ?>">
                        <?= esc($enrollment->course_title) ?>
                    </h3>
                    <div style="display: flex; align-items: center; gap: 12px; font-size: 0.74rem; color: var(--muted); flex-wrap: wrap;">
                        <span style="display:inline-flex; align-items:center; gap:4px;">
                            <svg aria-hidden="true" style="width:12px; height:12px;"><use href="#i-search-user"/></svg>
                            <?= esc($enrollment->instructor ?: 'JobberRecruit') ?>
                        </span>
                        <span style="display:inline-flex; align-items:center; gap:4px;">
                            <svg aria-hidden="true" style="width:12px; height:12px;"><use href="#i-clock"/></svg>
                            <?= esc($enrollment->duration ?: 'Self-paced') ?>
                        </span>
                    </div>

                    <?php if ($enrollment->progress !== null): ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.72rem; color: var(--muted); margin-bottom: 4px;">
                                <span>Progress</span>
                                <b><?= (int) $enrollment->progress ?>%</b>
                            </div>
                            <div class="ins-track" style="height: 6px;">
                                <div class="ins-fill <?= $isCompleted ? 'pill--hired' : 'emp-btn-primary' ?>" style="width: <?= (int) $enrollment->progress ?>%; background: <?= $isCompleted ? 'var(--success)' : 'var(--brand)' ?>; height: 100%;"></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($isPaid): ?>
                        <div style="font-size: 0.74rem; color: var(--muted); display: flex; align-items: center; gap: 6px;">
                            <svg aria-hidden="true" style="width:14px; height:14px; color: var(--success);"><use href="#i-card"/></svg>
                            <b>₦<?= number_format((float) $enrollment->amount, 2) ?></b>
                            <?php if ($enrollment->payment_reference): ?>
                                <span class="pill pill--hired" style="font-size: 0.6rem; padding: 2px 6px;">Paid</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div style="display: flex; gap: 8px; margin-top: 6px;">
                        <?php if ($isCompleted): ?>
                            <?php $cert = $certificates[$enrollment->course_id] ?? null; ?>
                            <?php if ($cert): ?>
                                <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" class="emp-btn emp-btn-accent emp-btn-sm" style="flex:1;">
                                    <svg aria-hidden="true"><use href="#i-check-c"/></svg> Certificate
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url($rolePrefix . '/my-courses/' . $enrollment->course_id) ?>" class="emp-btn emp-btn-outline emp-btn-sm" style="flex:1;">
                                <svg aria-hidden="true"><use href="#i-arrow-r"/></svg> Outline
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url($rolePrefix . '/my-courses/' . $enrollment->course_id) ?>" class="emp-btn emp-btn-primary emp-btn-sm" style="flex:1;">
                                <svg aria-hidden="true"><use href="#i-arrow-r"/></svg> View Outline
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
