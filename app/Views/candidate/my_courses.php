<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bookmark"/></svg> My Courses</h1>
            <p>Track your enrolled courses and progress.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('training') ?>" class="btn btn-accent btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg> Browse Courses
            </a>
        </div>
    </div>

    <?php if (empty($enrollments)): ?>
        <div class="card">
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-book"/></svg></span>
                <h3>No enrolled courses yet</h3>
                <p>Start your learning journey by enrolling in a course and boosting your career opportunities.</p>
                <a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg> Explore Courses</a>
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
                    <div class="course-cover" style="position: relative; overflow: hidden; background: #f0f4ff; height: 160px; display: flex; align-items: center; justify-content: center;">
                        <?php if ($enrollment->thumbnail): ?>
                            <img src="<?= base_url($enrollment->thumbnail) ?>" alt="<?= esc($enrollment->course_title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <svg aria-hidden="true" style="width:48px;height:48px;color:var(--brand);fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-book"/></svg>
                        <?php endif; ?>
                        
                        <span class="pill <?= $isCompleted ? 'pill--success' : 'pill--immediate' ?>" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                            <?= $isCompleted ? 'Completed' : ucfirst($enrollment->status) ?>
                        </span>
                    </div>
                    
                    <div class="course-body" style="padding: 16px; display: flex; flex-direction: column; gap: 10px; flex: 1;">
                        <div class="course-title" style="font-family: 'Sora', sans-serif; font-weight: 700; font-size: 0.94rem; color: var(--brand-deep); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em;">
                            <?= esc($enrollment->course_title) ?>
                        </div>
                        
                        <div class="course-meta" style="display: flex; gap: 12px; font-size: 0.76rem; color: var(--muted); align-items: center;">
                            <span style="display: inline-flex; align-items: center; gap: 4px;">
                                <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-users"/></svg>
                                <?= esc($enrollment->instructor ?: 'JobberRecruit') ?>
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 4px;">
                                <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-clock"/></svg>
                                <?= esc($enrollment->duration ?: 'Self-paced') ?>
                            </span>
                        </div>

                        <?php if ($enrollment->progress !== null): ?>
                            <div style="margin-top: 5px;">
                                <div class="course-prog" style="display: flex; justify-content: space-between; font-size: 0.76rem; font-weight: 600; color: var(--text); margin-bottom: 4px;">
                                    <span>Progress</span>
                                    <b><?= (int) $enrollment->progress ?>%</b>
                                </div>
                                <div class="prog-track" style="width: 100%; height: 6px; background: var(--border); border-radius: 20px; overflow: hidden; position: relative;">
                                    <div class="prog-fill" style="width: <?= (int) $enrollment->progress ?>%; height: 100%; background: var(--brand); transition: width 0.6s ease;"></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="course-pay" style="font-size: 0.78rem; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 6px; margin-top: 5px;">
                            <svg style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;" aria-hidden="true"><use href="#i-wallet"/></svg>
                            <?php if ($isPaid): ?>
                                ₦<?= number_format((float) $enrollment->amount, 2) ?>
                                <span class="pill pill--reviewed" style="font-size: 0.6rem; padding: 2px 6px;">Paid</span>
                            <?php else: ?>
                                Free Course
                            <?php endif; ?>
                        </div>

                        <div style="margin-top: auto; padding-top: 10px;">
                            <?php if ($isCompleted): ?>
                                <a href="<?= base_url('candidate/my-courses/' . $enrollment->course_id) ?>" class="btn btn-outline btn-sm btn-block">
                                    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-award"/></svg> View Classroom &amp; Cert
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('candidate/my-courses/' . $enrollment->course_id) ?>" class="btn btn-primary btn-sm btn-block">
                                    Enter Classroom
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>

            <!-- suggested next (engagement) -->
            <section class="card course" aria-label="Suggested course" style="border-style:dashed">
                <div class="course-cover" style="background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;height:160px;">
                    <svg aria-hidden="true" style="width:48px;height:48px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg>
                </div>
                <div class="course-body" style="padding: 16px; display: flex; flex-direction: column; gap: 10px; flex: 1;">
                    <div class="course-title" style="font-family: 'Sora', sans-serif; font-weight: 700; font-size: 0.94rem; color: var(--brand-deep);">
                        Suggested: Customer Service Excellence
                    </div>
                    <div class="course-meta" style="display: flex; gap: 12px; font-size: 0.76rem; color: var(--muted); align-items: center;">
                        <span style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg> Matches your skill profile
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

