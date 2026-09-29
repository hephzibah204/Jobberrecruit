<?php $page_title = 'My Courses'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
.mc-container {
  max-width: 1240px;
  margin: 0 auto;
  padding: clamp(16px, 2.5vw, 32px);
  display: flex;
  flex-direction: column;
  gap: 28px;
}

/* Header styling */
.mc-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(10,47,87,.08);
}
.mc-header-title h1 {
  font-family: 'Sora', sans-serif;
  font-size: clamp(1.4rem, 2.4vw, 1.9rem);
  font-weight: 800;
  color: var(--brand-deep, #0A2F57);
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  letter-spacing: -.02em;
}
.mc-header-title h1 svg {
  width: 26px; height: 26px; color: var(--brand, #0861A9); flex-shrink: 0; display: inline-block; vertical-align: middle;
}
.mc-btn-browse svg { width: 16px; height: 16px; flex-shrink: 0; display: inline-block; vertical-align: middle; }
svg { flex-shrink: 0; }

/* Grid Layout */
.mc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
}

/* Card Component */
.mc-card {
  background: #ffffff;
  border: 1px solid #e2e8f2;
  border-radius: 16px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: all .25s cubic-bezier(.2,.8,.2,1);
  box-shadow: 0 2px 10px rgba(10,47,87,.04);
  position: relative;
}
.mc-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 14px 34px rgba(10,47,87,.11);
  border-color: #cbd5e1;
}

/* Card Cover Header */
.mc-card-cover {
  position: relative;
  height: 160px;
  background: linear-gradient(135deg, #0A2F57 0%, #0861A9 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.mc-card-cover img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .4s ease;
}
.mc-card:hover .mc-card-cover img {
  transform: scale(1.05);
}
.mc-card-cover-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(10,47,87,.1) 0%, rgba(10,47,87,.7) 100%);
  z-index: 1;
}
.mc-card-cover-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: rgba(255,255,255,.15);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  position: relative;
  z-index: 2;
}
.mc-card-cover-icon svg { width: 24px; height: 24px; }

/* Badges */
.mc-badge-status {
  position: absolute;
  top: 14px;
  right: 14px;
  z-index: 2;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .03em;
  padding: 5px 12px;
  border-radius: 20px;
  backdrop-filter: blur(10px);
  text-transform: uppercase;
}
.mc-badge-completed {
  background: rgba(22, 163, 74, .9);
  color: #ffffff;
  box-shadow: 0 2px 8px rgba(22, 163, 74, .3);
}
.mc-badge-in_progress {
  background: rgba(237, 144, 32, .95);
  color: #0A2F57;
  font-weight: 800;
  box-shadow: 0 2px 8px rgba(237, 144, 32, .3);
}

/* Card Body */
.mc-card-body {
  padding: 22px;
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 16px;
}
.mc-course-title {
  font-family: 'Sora', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  color: #0A2F57;
  line-height: 1.4;
  margin: 0;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 2.8em;
}
.mc-course-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: .8rem;
  color: #5b6577;
}
.mc-meta-item {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.mc-meta-item svg { width: 15px; height: 15px; min-width: 15px; min-height: 15px; color: #0861A9; flex-shrink: 0; display: inline-block; vertical-align: middle; }

/* Progress Section */
.mc-prog-wrap {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 4px;
}
.mc-prog-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: .82rem;
}
.mc-prog-lbl { font-weight: 600; color: #5b6577; }
.mc-prog-pct { font-family: 'Sora', sans-serif; font-weight: 800; color: #0A2F57; }
.mc-prog-bar {
  height: 8px;
  border-radius: 20px;
  background: #e2e8f2;
  overflow: hidden;
  position: relative;
}
.mc-prog-fill {
  height: 100%;
  border-radius: 20px;
  background: linear-gradient(90deg, #0861A9 0%, #16a34a 100%);
  transition: width .6s ease;
}

/* Footer & CTA */
.mc-card-footer {
  margin-top: auto;
  padding-top: 14px;
  border-top: 1px solid #f1f5f9;
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.mc-price-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: .82rem;
}
.mc-price-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-weight: 700;
  color: #0A2F57;
}
.mc-price-tag svg { width: 15px; height: 15px; min-width: 15px; min-height: 15px; color: #5b6577; flex-shrink: 0; display: inline-block; vertical-align: middle; }
.mc-pill-paid {
  background: #e0f2fe;
  color: #0369a1;
  font-size: .7rem;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 12px;
}
.mc-pill-free {
  background: #f1f5f9;
  color: #475569;
  font-size: .7rem;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 12px;
}

.mc-btn-cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 11px;
  border-radius: 10px;
  font-family: 'Sora', sans-serif;
  font-size: .84rem;
  font-weight: 700;
  text-decoration: none;
  transition: all .2s ease;
}
.mc-btn-cta-primary {
  background: #0861A9;
  color: #ffffff !important;
  border: 1px solid #0861A9;
}
.mc-btn-cta-primary:hover {
  background: #064A85;
  border-color: #064A85;
  box-shadow: 0 4px 12px rgba(8,97,169,.25);
}
.mc-btn-cta-completed {
  background: #f8fafc;
  color: #0A2F57 !important;
  border: 1.5px solid #cbd5e1;
}
.mc-btn-cta-completed:hover {
  background: #0861A9;
  color: #ffffff !important;
  border-color: #0861A9;
}
.mc-btn-cta svg {
  width: 16px; height: 16px; min-width: 16px; min-height: 16px; flex-shrink: 0; display: inline-block; vertical-align: middle;
}
.mc-btn-cta-completed svg { color: #ED9020; }

/* Suggested Card */
.mc-card-suggested {
  border: 2px dashed #cbd5e1;
  background: #f8fafc;
}
.mc-card-suggested:hover {
  border-color: #0861A9;
  background: #ffffff;
}
.mc-suggested-header {
  height: 120px;
  background: linear-gradient(135deg, #e0f2fe 0%, #fef3c7 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.mc-suggested-icon {
  width: 44px; height: 44px;
  border-radius: 12px;
  background: #ffffff;
  color: #0861A9;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(10,47,87,.08);
}
.mc-suggested-icon svg { width: 22px; height: 22px; }

/* Empty state */
.mc-empty-card {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 20px;
  padding: 60px 24px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(10,47,87,.04);
}
.mc-empty-ic {
  width: 72px; height: 72px;
  border-radius: 50%;
  background: #e6f0f8;
  color: #0861A9;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
}
.mc-empty-ic svg { width: 36px; height: 36px; }
.mc-empty-card h3 {
  font-family: 'Sora', sans-serif;
  font-size: 1.25rem;
  font-weight: 800;
  color: #0A2F57;
  margin: 0 0 8px;
}
.mc-empty-card p {
  font-size: .9rem;
  color: #5b6577;
  max-width: 440px;
  margin: 0 auto 24px;
  line-height: 1.5;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mc-container">

    <!-- Header Section -->
    <div class="mc-header">
        <div class="mc-header-title">
            <h1><svg aria-hidden="true"><use href="#i-bookmark"/></svg> My Courses</h1>
            <p>Track your active learning, completed certifications, and skill recommendations.</p>
        </div>
        <div>
            <a href="<?= base_url('candidate/courses') ?>" class="mc-btn-browse">
                <svg aria-hidden="true"><use href="#i-plus"/></svg> Browse All Courses
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <?php if (empty($enrollments)): ?>
        <div class="mc-empty-card">
            <div class="mc-empty-ic">
                <svg aria-hidden="true"><use href="#i-book"/></svg>
            </div>
            <h3>No Enrolled Courses Yet</h3>
            <p>Start building your professional skills today. Complete courses to earn industry-verified certificates that employers love.</p>
            <a href="<?= base_url('training') ?>" class="mc-btn-browse" style="display:inline-flex;">
                <svg aria-hidden="true"><use href="#i-plus"/></svg> Explore Course Catalog
            </a>
        </div>
    <?php else: ?>
        <div class="mc-grid">
            <?php foreach ($enrollments as $enrollment): ?>
                <?php
                $isCompleted = $enrollment->status === 'completed';
                $isPaid = (float) ($enrollment->amount ?? 0) > 0;
                $progressPct = (int) ($enrollment->progress ?? 0);
                ?>
                <div class="mc-card">
                    <!-- Cover Image & Status Badge -->
                    <div class="mc-card-cover">
                        <?php if ($enrollment->thumbnail): ?>
                            <img src="<?= base_url($enrollment->thumbnail) ?>" alt="<?= esc($enrollment->course_title) ?>">
                            <div class="mc-card-cover-overlay"></div>
                        <?php endif; ?>
                        <div class="mc-card-cover-icon">
                            <svg aria-hidden="true"><use href="#i-book"/></svg>
                        </div>
                        <span class="mc-badge-status <?= $isCompleted ? 'mc-badge-completed' : 'mc-badge-in_progress' ?>">
                            <?= $isCompleted ? 'Completed' : 'In Progress' ?>
                        </span>
                    </div>

                    <!-- Card Content Body -->
                    <div class="mc-card-body">
                        <h2 class="mc-course-title" title="<?= esc($enrollment->course_title) ?>">
                            <?= esc($enrollment->course_title) ?>
                        </h2>

                        <div class="mc-course-meta">
                            <span class="mc-meta-item">
                                <svg aria-hidden="true"><use href="#i-users"/></svg>
                                <?= esc($enrollment->instructor ?: 'JobberRecruit') ?>
                            </span>
                            <span class="mc-meta-item">
                                <svg aria-hidden="true"><use href="#i-clock"/></svg>
                                <?= esc($enrollment->duration ?: 'Self-paced') ?>
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mc-prog-wrap">
                            <div class="mc-prog-header">
                                <span class="mc-prog-lbl">Progress</span>
                                <span class="mc-prog-pct"><?= $progressPct ?>%</span>
                            </div>
                            <div class="mc-prog-bar">
                                <div class="mc-prog-fill" style="width: <?= $progressPct ?>%;"></div>
                            </div>
                        </div>

                        <!-- Card Footer & Actions -->
                        <div class="mc-card-footer">
                            <div class="mc-price-row">
                                <span class="mc-price-tag">
                                    <svg aria-hidden="true"><use href="#i-card"/></svg>
                                    <?= $isPaid ? '₦' . number_format((float)$enrollment->amount, 2) : 'Free Access' ?>
                                </span>
                                <span class="<?= $isPaid ? 'mc-pill-paid' : 'mc-pill-free' ?>">
                                    <?= $isPaid ? 'Paid' : 'Free' ?>
                                </span>
                            </div>

                            <?php if ($isCompleted): ?>
                                <a href="<?= base_url('candidate/my-courses/' . $enrollment->course_id) ?>" class="mc-btn-cta mc-btn-cta-completed">
                                    <svg aria-hidden="true"><use href="#i-award"/></svg> View Classroom &amp; Cert
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('candidate/my-courses/' . $enrollment->course_id) ?>" class="mc-btn-cta mc-btn-cta-primary">
                                    Continue Learning
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Suggested Course Card -->
            <div class="mc-card mc-card-suggested">
                <div class="mc-suggested-header">
                    <div class="mc-suggested-icon">
                        <svg aria-hidden="true"><use href="#i-zap"/></svg>
                    </div>
                </div>
                <div class="mc-card-body">
                    <div style="font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#ED9020;">Recommended for You</div>
                    <h2 class="mc-course-title" style="min-height:auto;">Customer Service Excellence</h2>
                    <p style="font-size:.82rem;color:#5b6577;line-height:1.5;margin:0 0 12px;">Adding certified skills to your profile boosts employer visibility by up to 3x in candidate searches.</p>
                    
                    <div class="mc-card-footer">
                        <a href="<?= base_url('candidate/courses') ?>" class="mc-btn-cta mc-btn-cta-primary">
                            Explore Suggested Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>


