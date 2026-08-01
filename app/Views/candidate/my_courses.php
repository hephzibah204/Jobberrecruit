<?php $page_title = 'My Courses'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true"><use href="#i-bookmark"/></svg> My Courses</h1>
            <p>Track your enrolled courses and progress.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('training') ?>" class="btn btn-accent btn-sm">
                <svg aria-hidden="true"><use href="#i-plus"/></svg> Browse Courses
            </a>
        </div>
    </div>

    <?php if (empty($enrollments)): ?>
        <div class="card">
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true"><use href="#i-book"/></svg></span>
                <h3>No enrolled courses yet</h3>
                <p>Start your learning journey by enrolling in a course and boosting your career opportunities.</p>
                <a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true"><use href="#i-plus"/></svg> Explore Courses</a>
            </div>
        </div>
    <?php else: ?>
        <div class="courses">
            <?php foreach ($enrollments as $enrollment): ?>
                <?php
                $isCompleted = $enrollment->status === 'completed';
                $isPaid = (float) ($enrollment->amount ?? 0) > 0;
                ?>
                <section class="card course" aria-label="Course: <?= esc($enrollment->course_title) ?>">
                    <div class="course-cover">
                        <?php if ($enrollment->thumbnail): ?>
                            <img src="<?= base_url($enrollment->thumbnail) ?>" alt="<?= esc($enrollment->course_title) ?>" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: 0;">
                        <?php endif; ?>
                        <svg aria-hidden="true" style="position: relative; z-index: 1;"><use href="#i-book"/></svg>
                        <span class="pill <?= $isCompleted ? 'pill--success' : 'pill--immediate' ?>" style="z-index: 1;">
                            <?= $isCompleted ? 'Completed' : ucfirst($enrollment->status) ?>
                        </span>
                    </div>
                    
                    <div class="course-body">
                        <div class="course-title">
                            <?= esc($enrollment->course_title) ?>
                        </div>
                        
                        <div class="course-meta">
                            <span>
                                <svg aria-hidden="true"><use href="#i-users"/></svg>
                                <?= esc($enrollment->instructor ?: 'JobberRecruit') ?>
                            </span>
                            <span>
                                <svg aria-hidden="true"><use href="#i-clock"/></svg>
                                <?= esc($enrollment->duration ?: 'Self-paced') ?>
                            </span>
                        </div>

                        <?php if ($enrollment->progress !== null): ?>
                            <div class="course-prog">
                                <span>Progress</span>
                                <b><?= (int) $enrollment->progress ?>%</b>
                            </div>
                            <div class="prog-track">
                                <div class="prog-fill" style="width: <?= (int) $enrollment->progress ?>%;"></div>
                            </div>
                        <?php endif; ?>

                        <div class="course-pay">
                            <svg aria-hidden="true"><use href="#i-card"/></svg>
                            <?php if ($isPaid): ?>
                                ₦<?= number_format((float) $enrollment->amount, 2) ?>
                                <span class="pill pill--reviewed">Paid</span>
                            <?php else: ?>
                                Free course
                            <?php endif; ?>
                        </div>

                        <?php if ($isCompleted): ?>
                            <a href="<?= base_url('candidate/my-courses/' . $enrollment->course_id) ?>" class="btn btn-outline btn-sm btn-block">
                                <svg aria-hidden="true"><use href="#i-award"/></svg> View Classroom &amp; Cert
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('candidate/my-courses/' . $enrollment->course_id) ?>" class="btn btn-primary btn-sm btn-block">
                                Enter Classroom
                            </a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>

            <!-- suggested next (engagement) -->
            <section class="card course" aria-label="Suggested course" style="border-style:dashed">
                <div class="course-cover" style="background:var(--brand-light);color:var(--brand)">
                    <svg aria-hidden="true"><use href="#i-plus"/></svg>
                </div>
                <div class="course-body">
                    <div class="course-title">
                        Suggested: Customer Service Excellence
                    </div>
                    <div class="course-meta">
                        <span>
                            <svg aria-hidden="true"><use href="#i-zap"/></svg> Matches your skill profile
                        </span>
                    </div>
                    <p style="font-size:.78rem;color:var(--muted);margin-bottom:12px;flex:1">Adding certificates strengthens your profile in employer searches.</p>
                    <a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm btn-block">View Course</a>
                </div>
            </section>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

