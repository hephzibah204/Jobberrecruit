<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Course',
    'name'        => $course->title,
    'description' => mb_substr(strip_tags((string) $course->description), 0, 200),
    'provider'    => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'sameAs' => base_url(),
    ],
    'hasCourseInstance' => [
        '@type'       => 'CourseInstance',
        'courseMode'  => 'online',
        'duration'    => $course->duration ?: 'Self-paced',
        'instructor'  => [
            '@type' => 'Person',
            'name'  => $course->instructor ?: 'JobberRecruit',
        ],
    ],
    'offers' => [
        '@type'        => 'Offer',
        'price'        => (float) ($course->price ?? 0),
        'priceCurrency' => 'NGN',
        'availability' => 'https://schema.org/InStock',
        'category'     => (float) ($course->price ?? 0) > 0 ? 'Paid' : 'Free',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">
  <section class="course-detail">
    <!-- Hero Section -->
    <div class="course-hero">
      <div class="course-hero-grid"></div>
      <div class="container">
        <div class="course-hero-inner">
          <!-- Breadcrumbs -->
          <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= base_url() ?>">Home</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            <a href="<?= base_url('training') ?>">Training</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            <span aria-current="page"><?= esc($course->title) ?></span>
          </nav>

          <!-- Course Tags -->
          <div class="course-tags">
            <?php if (($course->item_type ?? 'course') === 'ebook'): ?>
              <span class="badge badge-blue">eBook (PDF)</span>
            <?php else: ?>
              <span class="level-tag level-<?= esc($course->level ?? 'beginner') ?>"><?= ucfirst(esc($course->level ?? 'beginner')) ?></span>
              <span class="badge badge-blue"><?= ucfirst(esc($course->content_source ?? 'none')) ?></span>
            <?php endif; ?>
            <span class="badge badge-blue"><?= (float) ($course->price ?? 0) > 0 ? 'Paid' : 'Free' ?></span>
          </div>

          <h1 class="course-hero-title"><?= esc($course->title) ?></h1>
          <p class="course-hero-sub">
            <?= esc(mb_substr(strip_tags((string) $course->description), 0, 220)) ?>...
          </p>

          <!-- Course Facts -->
          <div class="course-facts">
            <div class="fact-card">
              <div class="fact-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Instructor
              </div>
              <div class="fact-value"><?= esc($course->instructor ?: 'JobberRecruit') ?></div>
            </div>
            <div class="fact-card">
              <div class="fact-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Duration
              </div>
              <div class="fact-value"><?= esc($course->duration ?: 'Self-paced') ?></div>
            </div>
            <div class="fact-card">
              <div class="fact-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></svg>
                Price
              </div>
              <div class="fact-value">
                <?= (float) ($course->price ?? 0) > 0 ? '₦' . number_format((float) $course->price, 2) : 'Free' ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Layout -->
    <div class="course-body-wrap">
      <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-circle-x me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <div class="course-layout">
          <!-- Main Content -->
          <div class="course-main">
            <!-- About this course -->
            <div class="card">
              <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                About this course
              </h2>
              <div class="prose">
                <?= $course->description ?: '<p>No description provided.</p>' ?>
              </div>
            </div>

            <!-- Course curriculum -->
            <div class="card" id="curriculum">
              <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Course curriculum
              </h2>

              <?php if (! $enrollment): ?>
              <!-- Preview / unlock prompt — shown to users who haven't enrolled -->
              <div class="cur-preview">
                <div class="cur-preview-ic">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                  <h3>Curriculum preview</h3>
                  <p>Enrol in this course to immediately unlock all video lectures, downloadable guides, worksheets, and certificate generation on paid tiers.</p>
                  <a href="#" data-bs-toggle="modal" data-bs-target="<?= (float) ($course->price ?? 0) > 0 ? '#paymentModal' : '#enrollModal' ?>" class="btn btn-sm btn-accent">Enrol &amp; unlock</a>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($modules)): ?>
                <?php foreach ($modules as $index => $module): ?>
                  <details class="lesson" <?= $index === 0 ? 'open' : '' ?>>
                    <summary>
                      <span class="lesson-num"><?= $index + 1 ?></span>
                      <span class="lesson-title"><?= esc($module->title) ?></span>
                      
                      <?php if (!empty($module->content_source) || !empty($module->youtube_url)): ?>
                        <span class="lesson-type video">Video lesson</span>
                      <?php else: ?>
                        <span class="lesson-type text">Text guide</span>
                      <?php endif; ?>

                      <?php if ($enrollment): ?>
                        <span class="lesson-lock unlocked" style="background:#ecfdf5;color:#15803d;border:1px solid #a7f3d0;">
                          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg> Unlocked
                        </span>
                      <?php else: ?>
                        <span class="lesson-lock">
                          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Locked
                        </span>
                      <?php endif; ?>
                      <svg class="lesson-chev" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="lesson-body">
                      <p class="lesson-desc"><?= esc($module->description) ?></p>
                      <?php if ($enrollment): ?>
                        <div class="lesson-unlocked-row" style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;background:#f5f7fb;border:1px solid #e2e8f2;border-radius:10px;padding:13px 16px;">
                          <span style="font-size:.84rem;color:#5b6577;">You have access to this module. Go to classroom to view lectures and materials.</span>
                          <a href="<?= base_url('candidate/my-courses/' . $course->id) ?>" class="btn btn-primary btn-sm">Start Learning</a>
                        </div>
                      <?php else: ?>
                        <div class="lesson-locked-row">
                          <span>Enrol now to view lectures and download attached materials.</span>
                          <a href="#" data-bs-toggle="modal" data-bs-target="<?= (float) ($course->price ?? 0) > 0 ? '#paymentModal' : '#enrollModal' ?>" class="btn btn-outline btn-sm">Unlock lesson</a>
                        </div>
                      <?php endif; ?>
                    </div>
                  </details>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="muted text-center py-4">No curriculum modules uploaded yet.</p>
              <?php endif; ?>
            </div>

            <!-- Instructor Info -->
            <div class="card">
              <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Instructor
              </h2>
              <div class="prose">
                <p><strong><?= esc($course->instructor ?? 'JobberRecruit') ?></strong></p>
                <p>
                  <?php if (!empty($course->instructor_bio)): ?>
                    <?= esc($course->instructor_bio) ?>
                  <?php else: ?>
                    <span class="muted">Instructor bio not available.</span>
                  <?php endif; ?>
                </p>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <aside class="course-sidebar">
            <!-- Enrol Card -->
            <div class="enrol-card" data-inflow-cta>
              <div class="enrol-media">
                <?php if ($course->thumbnail): ?>
                  <img src="<?= base_url($course->thumbnail) ?>" alt="<?= esc($course->title) ?>">
                <?php else: ?>
                  <div class="enrol-media-fallback">
                    <svg viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="20" cy="20" r="18"/><path d="M12 12h16v16H12z"/><path d="M12 16h16M12 20h16M12 24h16"/></svg>
                    <span><?= esc($course->title) ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <div class="enrol-body">
                <div class="enrol-price-row">
                  <span class="enrol-price">
                    <?php if ((float) ($course->price ?? 0) > 0): ?>
                      ₦<?= number_format((float) $course->price, 2) ?>
                    <?php else: ?>
                      Free
                    <?php endif; ?>
                  </span>
                </div>

                <?php if ($enrollment): ?>
                  <p class="enroll-notice">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    You are enrolled in this training
                  </p>
                <?php endif; ?>

                <?php $isEbook = ($course->item_type ?? 'course') === 'ebook'; ?>
                <?php if ($enrollment): ?>
                  <?php if ($isEbook): ?>
                    <a href="#course-content" class="btn btn-primary btn-block">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                      Download eBook
                    </a>
                  <?php else: ?>
                    <a href="<?= base_url('candidate/my-courses/' . $course->id) ?>" class="btn btn-primary btn-block">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                      Go to Classroom
                    </a>
                  <?php endif; ?>
                <?php else: ?>
                  <?php if ((float) ($course->price ?? 0) > 0): ?>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#paymentModal" class="btn btn-primary btn-block">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                      Buy Course (₦<?= number_format((float)$course->price, 2) ?>)
                    </a>
                  <?php else: ?>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#enrollModal" class="btn btn-primary btn-block">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                      Enroll for Free
                    </a>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>

            <!-- What you'll gain -->
            <div class="card side-card">
              <h2 style="font-size:1.15rem;font-weight:800;color:var(--text);margin-bottom:16px;">What you'll gain</h2>
              <ul style="list-style:none;display:flex;flex-direction:column;gap:13px;padding:0;margin:0;">
                <li style="display:flex;gap:11px;align-items:flex-start;font-size:.88rem;color:var(--text);line-height:1.5;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="color:var(--brand);flex-shrink:0;margin-top:1px;"><path d="M20 6 9 17l-5-5"/></svg>
                  <span>Practical, job-ready skills tailored for Nigerian employers</span>
                </li>
                <li style="display:flex;gap:11px;align-items:flex-start;font-size:.88rem;color:var(--text);line-height:1.5;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="color:var(--brand);flex-shrink:0;margin-top:1px;"><path d="M20 6 9 17l-5-5"/></svg>
                  <span>Step-by-step video modules &amp; downloadable resources</span>
                </li>
                <li style="display:flex;gap:11px;align-items:flex-start;font-size:.88rem;color:var(--text);line-height:1.5;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="color:var(--brand);flex-shrink:0;margin-top:1px;"><path d="M20 6 9 17l-5-5"/></svg>
                  <span>Verified certificate linked directly to your JobberRecruit profile</span>
                </li>
              </ul>
            </div>

            <!-- Requirements -->
            <div class="card">
              <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Requirements
              </h2>
              <div class="prose">
                <ul>
                  <li>Basic computer skills</li>
                  <li>Internet connection</li>
                  <li>Web browser access</li>
                  <?php if (!empty($course->requirements)): ?>
                    <li><?= esc($course->requirements) ?></li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>

            <!-- What's Included -->
            <div class="card">
              <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                What's Included
              </h2>
              <ul class="enrol-list">
                <li>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <span>Access to all course materials</span>
                </li>
                <li>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <span>Certificate of completion</span>
                </li>
                <li>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <span>Lifetime access</span>
                </li>
                <li>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <span>Download resources</span>
                </li>
              </ul>
            </div>

            <?php if (!empty($relatedCourses)): ?>
              <div class="card side-card">
                <h2 style="font-size:1.15rem;font-weight:800;color:var(--text);margin-bottom:16px;">Related courses</h2>
                <div style="display:flex;flex-direction:column;gap:10px;">
                  <?php foreach ($relatedCourses as $rel): ?>
                    <a href="<?= base_url('training/' . esc($rel->slug ?? $rel->id)) ?>" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:var(--transition);" onmouseover="this.style.borderColor='var(--brand)'" onmouseout="this.style.borderColor='var(--border)'">
                      <div style="width:52px;height:52px;flex-shrink:0;border-radius:8px;overflow:hidden;background:linear-gradient(135deg,#0A2F57,#0861A9);display:flex;align-items:center;justify-content:center;color:#fff;">
                        <?php if (!empty($rel->thumbnail)): ?>
                          <img src="<?= base_url($rel->thumbnail) ?>" alt="<?= esc($rel->title) ?>" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg>
                        <?php endif; ?>
                      </div>
                      <div style="flex:1;min-width:0;display:flex;flex-direction:column;gap:4px;">
                        <div style="font-weight:700;font-size:.86rem;color:var(--text);line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($rel->title) ?></div>
                        <div style="font-size:.78rem;font-weight:700;color:<?= (float)($rel->price ?? 0) > 0 ? 'var(--brand)' : 'var(--success)' ?>;">
                          <?= (float)($rel->price ?? 0) > 0 ? '₦' . number_format((float)$rel->price, 2) : 'Free' ?>
                        </div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
          </aside>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- 8.3 Sticky bottom Enrol bar (Section 8 — Native Mobile App Feel) -->
<?php $jrIsEbook = ($course->item_type ?? 'course') === 'ebook'; ?>
<div class="jr-action-bar">
  <div class="jr-action-meta">
    <?php if ($enrollment): ?>
      <span class="label">Status</span>
      <span class="value">Enrolled</span>
    <?php else: ?>
      <span class="label">Price</span>
      <span class="value">
        <?= (float) ($course->price ?? 0) > 0 ? '₦' . number_format((float) $course->price, 2) : 'Free' ?>
      </span>
    <?php endif; ?>
  </div>
  <div class="jr-action-cta">
    <?php if ($enrollment): ?>
      <?php if ($jrIsEbook): ?>
        <a class="btn btn-primary" href="#course-content">Download eBook</a>
      <?php else: ?>
        <a class="btn btn-primary" href="<?= base_url('candidate/my-courses/' . $course->id) ?>">Go to Classroom</a>
      <?php endif; ?>
    <?php else: ?>
      <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="<?= (float) ($course->price ?? 0) > 0 ? '#paymentModal' : '#enrollModal' ?>">
        <?= (float) ($course->price ?? 0) > 0 ? 'Buy Course' : 'Enrol Free' ?>
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- Enrollment Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border:none;border-radius:12px;overflow:hidden;box-shadow:0 14px 40px rgba(10,47,87,.16)">
      <div class="modal-header" style="background:var(--brand-light);color:var(--brand);padding:18px 22px">
        <h5 class="modal-title fw-bold" id="enrollModalLabel">Enroll in Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="padding:22px">
        <p>You are about to enroll in <strong><?= esc($course->title) ?></strong> for free.</p>
        <p>Click "Confirm Enrollment" to add this course to your learning dashboard and get full access.</p>
      </div>
      <div class="modal-footer" style="padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary">Confirm Enrollment</a>
      </div>
    </div>
  </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border:none;border-radius:12px;overflow:hidden;box-shadow:0 14px 40px rgba(10,47,87,.16)">
      <div class="modal-header" style="background:var(--brand-light);color:var(--brand);padding:18px 22px">
        <h5 class="modal-title fw-bold" id="paymentModalLabel">Purchase Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="padding:22px">
        <p>You are about to purchase <strong><?= esc($course->title) ?></strong>.</p>
        <div style="background:#f5f7fb;padding:15px;border-radius:8px;margin-bottom:15px;border:1px solid var(--border);">
          <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span style="color:var(--muted)">Price:</span>
            <strong>₦<?= number_format((float)$course->price, 2) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--muted)">Total:</span>
            <strong style="color:var(--brand);font-size:1.1rem">₦<?= number_format((float)$course->price, 2) ?></strong>
          </div>
        </div>
        <p style="font-size:0.9rem;color:var(--muted)"><i class="ti ti-lock"></i> Secure payment processed by Paystack.</p>
      </div>
      <div class="modal-footer" style="padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary">Proceed to Payment</a>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ═══════════════════════════════════════════════════════════════════
   TRAINING DETAIL PAGE — Brand colors: var(--brand) (blue), var(--accent) (orange)
   ═══════════════════════════════════════════════════════════════════ */

/* ── Hero Section ── */
.course-detail { padding-bottom: 80px; }
.course-hero {
  background:
    radial-gradient(ellipse 70% 60% at 85% 15%, rgba(245,160,32,.18) 0%, transparent 55%),
    radial-gradient(ellipse 80% 70% at 5% 95%, rgba(8,97,169,.34) 0%, transparent 55%),
    linear-gradient(160deg, #0A2F57 0%, #064A85 55%, var(--brand) 100%);
  color: var(--white);
  position: relative;
  overflow: hidden;
  padding: 40px 0 56px;
  padding-top: max(40px, calc(40px + env(safe-area-inset-top, 0px)));
}
.course-hero-grid {
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .5;
  background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 46px 46px;
  -webkit-mask-image: radial-gradient(ellipse 90% 80% at 60% 30%, #000 30%, transparent 80%);
  mask-image: radial-gradient(ellipse 90% 80% at 60% 30%, #000 30%, transparent 80%);
}
.course-hero-inner {
  position: relative;
  z-index: 1;
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 40px;
  align-items: start;
}

/* Breadcrumbs */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: .78rem;
  margin-bottom: 22px;
  color: rgba(255,255,255,.7);
  flex-wrap: wrap;
}
.breadcrumb a {
  color: rgba(255,255,255,.8);
  text-decoration: none;
}
.breadcrumb a:hover {
  color: var(--white);
  text-decoration: underline;
}
.breadcrumb svg {
  width: 13px; height: 13px; opacity: .6;
}
.breadcrumb .current {
  color: var(--white); font-weight: 500;
}

/* Course Tags */
.course-tags {
  display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px;
}
.badge {
  display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px;
  border-radius: 20px; font-size: .72rem; font-weight: 600;
}
.badge-blue { background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); }
.level-tag {
  font-size: .68rem; font-weight: 700; letter-spacing: .03em; padding: 3px 10px; border-radius: 20px;
}
.level-beginner { background: #15803d; color: #ffffff; }
.level-intermediate { background: #e07b12; color: #ffffff; }
.level-advanced { background: #b91c1c; color: #ffffff; }

.course-hero-title {
  font-size: clamp(1.9rem, 4vw, 2.9rem);
  font-weight: 800; line-height: 1.1; margin-bottom: 16px;
  color: #ffffff;
}
.course-hero-sub {
  font-size: 1rem; opacity: .9; max-width: 540px; margin-bottom: 28px;
  color: rgba(255,255,255,0.9);
}

/* Course Facts */
.course-facts {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; max-width: 560px;
  min-width: 0;
}
.fact-card {
  background: rgba(10,30,55,.8);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 10px;
  padding: 14px 16px;
  backdrop-filter: blur(6px);
  min-width: 0;
}
.fact-label {
  display: flex; align-items: center; gap: 6px;
  font-size: .68rem; font-weight: 700; letter-spacing: .08em;
  text-transform: uppercase; color: rgba(255,255,255,.75); margin-bottom: 6px;
}
.fact-label svg { width: 13px; height: 13px; color: var(--accent); }
.fact-value {
  font-family: 'Sora', sans-serif; font-size: 1.05rem; font-weight: 700; color: #ffffff;
}

/* Content Layout */
.course-body-wrap { padding: 56px 0 72px; }
.course-layout {
  display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start;
}
/* Prevent grid blowout: 1fr items default to min-width:auto and stretch to
   their content's min-content width, overflowing the viewport on mobile. */
.course-layout, .course-main, .course-sidebar { min-width: 0; }
.course-hero-inner, .course-hero-inner > * { min-width: 0; }
.course-main img, .course-sidebar img, .enrol-media img { max-width: 100%; height: auto; }

/* Cards */
.card {
  background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 30px; margin-bottom: 24px;
}
.card-title {
  font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
}
.card-title svg { width: 22px; height: 22px; color: var(--brand); }
.prose {
  color: var(--muted); font-size: .95rem; line-height: 1.8;
}
.prose p { margin-bottom: 12px; }
.prose p:last-child { margin-bottom: 0; }
.prose ul { padding-left: 1.2rem; margin-bottom: 12px; }
.prose ul li { margin-bottom: .6rem; }
.prose .muted { color: var(--muted); }

/* Curriculum Preview */
.cur-preview {
  background: linear-gradient(120deg, #fffbeb, #fef3c7);
  border: 1px solid #fde68a; border-radius: 10px;
  padding: 20px 22px; margin-bottom: 20px;
  display: flex; gap: 16px; align-items: flex-start;
}
.cur-preview-ic {
  width: 44px; height: 44px; flex-shrink: 0; border-radius: 12px;
  background: var(--accent); color: var(--brand-deep);
  display: flex; align-items: center; justify-content: center;
}
.cur-preview-ic svg { width: 22px; height: 22px; }
.cur-preview h3 {
  font-family: 'Sora', sans-serif; font-size: 1rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 6px;
}
.cur-preview p {
  font-size: .85rem; color: #8a6d2f; line-height: 1.6; margin-bottom: 14px;
}
.cur-preview .btn {
  background: var(--accent); color: var(--brand-deep); border-color: var(--accent);
}
.cur-preview .btn:hover { background: var(--accent-dark); border-color: var(--accent-dark); }

/* Curriculum Lessons Accordion */
.lesson { border: 1px solid var(--border); border-radius: 10px; margin-bottom: 10px; overflow: hidden; background: var(--white); transition: var(--transition); }
.lesson:hover { border-color: #cdd9ea; }
.lesson[open] { border-color: var(--brand); box-shadow: var(--shadow); }
.lesson summary { list-style: none; cursor: pointer; padding: 16px 18px; display: flex; align-items: center; gap: 14px; user-select: none; min-height: 56px; -webkit-tap-highlight-color: transparent; }
.lesson summary::-webkit-details-marker { display: none; }
.lesson-num { width: 28px; height: 28px; flex-shrink: 0; border-radius: 50%; background: var(--brand-light); color: var(--brand); font-family: 'Sora', sans-serif; font-weight: 700; font-size: .8rem; display: flex; align-items: center; justify-content: center; }
.lesson[open] .lesson-num { background: var(--brand); color: var(--white); }
.lesson-title { flex: 1; font-weight: 700; font-size: .92rem; color: var(--text); min-width: 0; }
.lesson-type { font-size: .64rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: 4px 9px; border-radius: 6px; flex-shrink: 0; }
.lesson-type.video { background: var(--brand-light); color: var(--brand); }
.lesson-type.text { background: #fff3e0; color: var(--accent-dark); }
.lesson-lock { display: inline-flex; align-items: center; gap: 4px; font-size: .64rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: var(--muted); background: var(--bg); border: 1px solid var(--border); padding: 4px 9px; border-radius: 6px; flex-shrink: 0; }
.lesson-lock svg { width: 11px; height: 11px; }
.lesson-chev { width: 18px; height: 18px; color: var(--muted); transition: transform .2s; flex-shrink: 0; }
.lesson[open] .lesson-chev { transform: rotate(180deg); }
.lesson-body { padding: 0 18px 18px 60px; }
.lesson-desc { font-size: .88rem; color: var(--muted); line-height: 1.7; margin-bottom: 14px; }
.lesson-locked-row { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 13px 16px; }
.lesson-locked-row span { font-size: .84rem; color: var(--muted); }

@media (max-width: 640px) { .lesson-body { padding-left: 18px; } }

/* Sidebar */
.course-sidebar { position: sticky; top: 86px; }

/* Enrol Card */
.enrol-card {
  background: var(--white); border-radius: 14px;
  box-shadow: 0 14px 40px rgba(10,47,87,.16);
  overflow: hidden; margin-bottom: 24px;
}
.enrol-media {
  aspect-ratio: 16/9; background: linear-gradient(135deg, #0D609E, #064A85);
  display: flex; align-items: center; justify-content: center;
  position: relative; overflow: hidden;
}
.enrol-media img {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
}
.enrol-media-fallback {
  display: flex; flex-direction: column; align-items: center; gap: 10px;
  color: rgba(255,255,255,.95); text-align: center; padding: 20px;
}
.enrol-media-fallback svg { width: 40px; height: 40px; }
.enrol-media-fallback span {
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1rem; line-height: 1.3;
}
.enrol-media .play-badge {
  position: absolute; bottom: 12px; right: 12px;
  background: rgba(10,47,87,.7); color: #fff;
  font-size: .68rem; font-weight: 600; padding: 4px 10px;
  border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;
  backdrop-filter: blur(4px);
}
.enrol-media .play-badge svg { width: 12px; height: 12px; }
.enrol-body { padding: 22px; }
.enrol-price-row {
  display: flex; align-items: baseline; gap: 8px; margin-bottom: 4px;
}
.enrol-price {
  font-family: 'Sora', sans-serif; font-size: 1.9rem; font-weight: 800; color: var(--text);
}
.enrol-price.is-free { color: var(--accent-dark); }
.enroll-notice {
  display: flex; gap: 9px; align-items: flex-start; background: #fff8ef;
  border: 1px solid #fde3bf; border-radius: 10px; padding: 11px 13px; margin-bottom: 16px;
}
.enroll-notice svg { width: 16px; height: 16px; color: var(--accent-dark); flex-shrink: 0; margin-top: 2px; }
.enroll-notice p { font-size: .8rem; color: var(--accent-dark); font-weight: 600; line-height: 1.5; }
.enrol-list {
  list-style: none; margin-top: 18px; display: flex; flex-direction: column; gap: 11px;
}
.enrol-list li {
  display: flex; align-items: flex-start; gap: 10px; font-size: .84rem; color: var(--text);
}
.enrol-list li svg { width: 16px; height: 16px; color: var(--success); flex-shrink: 0; margin-top: 2px; }
.enrol-list li .muted { color: var(--muted); }

/* Buttons */
.btn-block { width: 100%; }
.btn-block:hover { text-decoration: none; }

/* Mobile */
@media (max-width: 900px) {
  .course-hero-inner { grid-template-columns: 1fr; }
  .course-facts { max-width: 100%; }
  .course-layout { grid-template-columns: 1fr; }
  .course-sidebar { position: static; }
}
@media (max-width: 580px) {
  .course-facts { grid-template-columns: 1fr; }
  .card { padding: 20px 16px; }
  .cur-preview { flex-direction: column; gap: 12px; }
  .lesson summary { gap: 8px; padding: 12px; }
  .enrol-body { padding: 16px; }
}
</style>
<?= $this->endSection() ?>