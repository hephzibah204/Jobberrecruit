<?php $page_title = 'My Courses'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* Premium Polish Layer */
:root{
  --shadow-xs:0 1px 3px rgba(10,47,87,.06);
  --shadow-sm:0 2px 10px rgba(10,47,87,.07);
  --shadow-md:0 6px 24px rgba(10,47,87,.10);
  --shadow-lg-p:0 16px 44px rgba(10,47,87,.16);
  --border-c:#e2e8f2;
}
.card,.dash-card,.set-card,.plan,.info-card,.job-card,.les-detail,.cur-card,
.at-card,.faq-item,.modal,.q-block{
  box-shadow:var(--shadow-xs);
  border-color:var(--border-c);
}
.card:hover,.dash-card:hover,.job-card:hover,.cs-tool:hover,.res-item:hover{
  box-shadow:var(--shadow-sm);
}
.modal{box-shadow:var(--shadow-lg-p)}
.btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.tpl-swatch,
.les,.res-item,.faq-item,.plan .btn,.icon-btn{
  transition:transform .12s cubic-bezier(.2,.8,.2,1),
             box-shadow .18s ease,
             background-color .18s ease,
             border-color .18s ease,
             opacity .18s ease;
}
.btn:active,.at-pal:active,.at-opt:active,.q-opt:active,.cs-tool:active,
.les:active,.res-item:active{
  transform:scale(.97);
}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){
  .btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.les,.res-item{
    transition:background-color .12s ease,border-color .12s ease!important;
  }
  .btn:active,.btn:hover{transform:none!important}
}
</style>
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

