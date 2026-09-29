<?php $page_title = 'Browse Courses'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
.bc-container {
  max-width: 1240px;
  margin: 0 auto;
  padding: clamp(16px, 2.5vw, 32px);
  display: flex;
  flex-direction: column;
  gap: 28px;
}
.bc-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(10,47,87,.08);
}
.bc-header-title h1 {
  font-family: 'Sora', sans-serif;
  font-size: clamp(1.4rem, 2.4vw, 1.9rem);
  font-weight: 800;
  color: #0A2F57;
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  letter-spacing: -.02em;
}
.bc-header-title h1 svg {
  width: 26px; height: 26px; color: #0861A9; flex-shrink: 0; display: inline-block; vertical-align: middle;
}
.bc-header-title p {
  font-size: .88rem;
  color: #5b6577;
  margin: 4px 0 0;
}
.bc-btn-my {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #ffffff;
  color: #0861A9 !important;
  border: 1.5px solid #cbd5e1;
  font-family: 'Sora', sans-serif;
  font-weight: 700;
  font-size: .85rem;
  padding: 11px 20px;
  border-radius: 10px;
  transition: all .2s ease;
  text-decoration: none;
}
.bc-btn-my:hover {
  background: #0861A9;
  color: #ffffff !important;
  border-color: #0861A9;
  box-shadow: 0 4px 14px rgba(8,97,169,.2);
}
.bc-btn-my svg { width: 16px; height: 16px; flex-shrink: 0; display: inline-block; vertical-align: middle; }

.bc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
}

.bc-card {
  background: #ffffff;
  border: 1px solid #e2e8f2;
  border-radius: 16px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: all .25s cubic-bezier(.2,.8,.2,1);
  box-shadow: 0 2px 10px rgba(10,47,87,.04);
}
.bc-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 14px 34px rgba(10,47,87,.11);
  border-color: #cbd5e1;
}

.bc-card-cover {
  position: relative;
  height: 160px;
  background: linear-gradient(135deg, #0A2F57 0%, #0861A9 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}
.bc-card-cover img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .4s ease;
}
.bc-card:hover .bc-card-cover img {
  transform: scale(1.05);
}
.bc-card-cover-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(10,47,87,.1) 0%, rgba(10,47,87,.7) 100%);
  z-index: 1;
}
.bc-card-cover-icon {
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
.bc-card-cover-icon svg { width: 24px; height: 24px; flex-shrink: 0; }

.bc-badge-level {
  position: absolute;
  top: 14px;
  right: 14px;
  z-index: 2;
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .03em;
  padding: 4px 11px;
  border-radius: 20px;
  background: rgba(10, 47, 87, .8);
  color: #ffffff;
  backdrop-filter: blur(8px);
  text-transform: uppercase;
}

.bc-card-body {
  padding: 22px;
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 14px;
}
.bc-course-title {
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
.bc-course-desc {
  font-size: .82rem;
  color: #5b6577;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin: 0;
}
.bc-course-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: .8rem;
  color: #5b6577;
  margin-top: auto;
}
.bc-meta-item {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.bc-meta-item svg { width: 15px; height: 15px; min-width: 15px; min-height: 15px; color: #0861A9; flex-shrink: 0; display: inline-block; vertical-align: middle; }

.bc-card-footer {
  padding-top: 14px;
  border-top: 1px solid #f1f5f9;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.bc-price-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: .82rem;
}
.bc-price-val {
  font-family: 'Sora', sans-serif;
  font-weight: 800;
  font-size: .95rem;
  color: #0A2F57;
}

.bc-btn-action {
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
.bc-btn-enroll {
  background: #0861A9;
  color: #ffffff !important;
  border: 1px solid #0861A9;
}
.bc-btn-enroll:hover {
  background: #064A85;
  border-color: #064A85;
  box-shadow: 0 4px 12px rgba(8,97,169,.25);
}
.bc-btn-enrolled {
  background: #e2e8f2;
  color: #0A2F57 !important;
  border: 1px solid #cbd5e1;
}
.bc-btn-enrolled svg { width: 16px; height: 16px; min-width: 16px; min-height: 16px; color: #16a34a; flex-shrink: 0; display: inline-block; vertical-align: middle; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bc-container">

    <div class="bc-header">
        <div class="bc-header-title">
            <h1><svg aria-hidden="true"><use href="#i-book"/></svg> Course Catalog</h1>
            <p>Upgrade your career skills with accredited courses and professional certifications.</p>
        </div>
        <div>
            <a href="<?= base_url('candidate/my-courses') ?>" class="bc-btn-my">
                <svg aria-hidden="true"><use href="#i-bookmark"/></svg> My Enrolled Courses
            </a>
        </div>
    </div>

    <div class="bc-grid">
        <?php foreach ($courses as $c): ?>
            <?php
            $isEnrolled = in_array((int)$c->id, array_map('intval', $enrolledIds), true);
            $isPaid = (float)($c->price ?? 0) > 0;
            ?>
            <div class="bc-card">
                <div class="bc-card-cover">
                    <?php if (!empty($c->thumbnail)): ?>
                        <img src="<?= base_url($c->thumbnail) ?>" alt="<?= esc($c->title) ?>">
                        <div class="bc-card-cover-overlay"></div>
                    <?php endif; ?>
                    <div class="bc-card-cover-icon">
                        <svg aria-hidden="true"><use href="#i-book"/></svg>
                    </div>
                    <?php if (!empty($c->level)): ?>
                        <span class="bc-badge-level"><?= esc(ucfirst($c->level)) ?></span>
                    <?php endif; ?>
                </div>

                <div class="bc-card-body">
                    <h2 class="bc-course-title" title="<?= esc($c->title) ?>"><?= esc($c->title) ?></h2>
                    <p class="bc-course-desc"><?= esc($c->description) ?></p>

                    <div class="bc-course-meta">
                        <span class="bc-meta-item">
                            <svg aria-hidden="true"><use href="#i-users"/></svg>
                            <?= esc($c->instructor ?: 'JobberRecruit') ?>
                        </span>
                        <span class="bc-meta-item">
                            <svg aria-hidden="true"><use href="#i-clock"/></svg>
                            <?= esc($c->duration ?: 'Self-paced') ?>
                        </span>
                    </div>

                    <div class="bc-card-footer">
                        <div class="bc-price-row">
                            <span class="bc-price-val">
                                <?= $isPaid ? '₦' . number_format((float)$c->price, 2) : 'Free' ?>
                            </span>
                            <span style="font-size:.72rem;font-weight:700;color:#5b6577;">
                                <?= esc($c->content_source ?? 'Online') ?>
                            </span>
                        </div>

                        <?php if ($isEnrolled): ?>
                            <a href="<?= base_url('candidate/my-courses/' . $c->id) ?>" class="bc-btn-action bc-btn-enrolled">
                                <svg aria-hidden="true"><use href="#i-check-c"/></svg> Continue Learning
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('training/course/' . $c->id) ?>" class="bc-btn-action bc-btn-enroll">
                                View Course &amp; Enroll
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
<?= $this->endSection() ?>
