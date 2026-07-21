<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<?php
$listItems = [];
foreach (array_slice($courses, 0, 10) as $index => $course) {
    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $index + 1,
        'item'     => [
            '@type'       => 'Course',
            'name'        => $course->title,
            'description' => mb_substr(strip_tags((string) $course->description), 0, 150),
            'provider'    => [
                '@type' => 'Organization',
                'name'  => 'JobberRecruit',
                'sameAs' => base_url(),
            ],
            'hasCourseInstance' => [
                '@type'      => 'CourseInstance',
                'courseMode' => 'online',
                'duration'   => $course->duration ?: 'Self-paced',
                'offers'     => [
                    '@type'        => 'Offer',
                    'price'        => (float) ($course->price ?? 0),
                    'priceCurrency' => 'NGN',
                    'availability' => 'https://schema.org/InStock',
                    'category'     => (float) ($course->price ?? 0) > 0 ? 'Paid' : 'Free',
                ],
                'instructor' => [
                    '@type' => 'Person',
                    'name'  => $course->instructor ?: 'JobberRecruit',
                ],
            ],
        ],
    ];
}
?>
<script type="application/ld+json">
<?= json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'ItemList',
    'name'        => 'JobberRecruit Professional E-Learning Courses',
    'description' => 'Browse admin-curated professional training courses, career development guides, and tech certifications on JobberRecruit.',
    'url'          => current_url(),
    'numberOfItems' => count($courses),
    'itemListElement' => $listItems,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Reusable SVG icon sprite from mockup -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></symbol>
    <symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M5 12H1M23 12h-4M6.3 6.3 3.5 3.5M20.5 20.5l-2.8-2.8M17.7 6.3l2.8-2.8M3.5 20.5l2.8-2.8"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M9 14a5 5 0 1 1 6 0c-.7.5-1 1.2-1 2H10c0-.8-.3-1.5-1-2Z"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></symbol>
    <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="i-chev-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-youtube" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c2 .6 9.4.6 9.4.6s7.4 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.5V8.5L15.8 12l-6.2 3.5Z"/></symbol>
    <symbol id="i-upload" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
    <symbol id="i-mega" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"/><path d="M15 8a4 4 0 0 1 0 8M18 5a8 8 0 0 1 0 14"/></symbol>
  </defs>
</svg>

<main id="main">

  <!-- TRAINING HERO -->
  <section class="tr-hero" aria-label="Training marketplace">
    <span class="tr-hero-grid-bg" aria-hidden="true"></span>
    <div class="container tr-hero-inner">
      <div class="tr-hero-text">
        <p class="tr-hero-tag"><svg aria-hidden="true"><use href="#i-cap"/></svg> Training marketplace</p>
        <h1>Learn practical skills with <span style="color:var(--accent)">admin-curated</span> free &amp; premium courses</h1>
        <p class="tr-hero-sub">Explore job-ready training, interview prep, hiring playbooks, and AI-driven career development resources built for JobberRecruit users.</p>
        <div class="tr-hero-actions">
          <a href="#catalog" class="btn btn-accent"><svg aria-hidden="true"><use href="#i-search"/></svg> Browse courses</a>
          <a href="<?= base_url('services/cv-review') ?>" class="btn btn-white">Get CV reviewed</a>
        </div>
      </div>

      <!-- LIVE COUNTS -->
      <div class="tr-stats" aria-label="Course catalog stats">
        <div class="tr-stat-row">
          <span class="tr-stat-num live-count" data-count="<?= $totalActive ?? count($courses) ?>"><?= $totalActive ?? count($courses) ?></span>
          <span class="tr-stat-label">Active courses</span>
        </div>
        <div class="tr-stat-row">
          <span class="tr-stat-num live-count" data-count="<?= $freeCount ?>"><?= $freeCount ?></span>
          <span class="tr-stat-label">Free courses</span>
        </div>
        <div class="tr-stat-row">
          <span class="tr-stat-num live-count" data-count="<?= $paidCount ?>"><?= $paidCount ?></span>
          <span class="tr-stat-label">Paid courses</span>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURED COURSES -->
  <?php if (!empty($featuredCourses)): ?>
    <section class="section" id="featured" aria-labelledby="feat-h">
      <div class="container">
        <p class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured courses</p>
        <h2 class="section-title" id="feat-h">Top picks for <span>candidates &amp; employers</span></h2>
        <p class="section-sub">Hand-picked by our admin team — courses marked "Feature this course" appear here first.</p>

        <div class="feat-course-grid">
          <?php foreach ($featuredCourses as $index => $course): 
              $isPaidCourse = (float)($course->price ?? 0) > 0;
              $fallbackNum = ($index % 4) + 1;
          ?>
            <article class="feat-course-card <?= $isPaidCourse ? 'is-paid' : '' ?>">
              <span class="fc-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
              <div class="fc-thumb fc-thumb-<?= $fallbackNum ?>">
                <?php if ($course->thumbnail): ?>
                  <img class="thumb-img" src="<?= base_url($course->thumbnail) ?>" alt="<?= esc($course->title) ?>" loading="lazy">
                <?php else: ?>
                  <svg class="fc-thumb-icon" aria-hidden="true">
                    <use href="<?= ($course->item_type ?? 'course') === 'ebook' ? '#i-doc' : '#i-cap' ?>"/>
                  </svg>
                <?php endif; ?>
              </div>
              <div class="fc-body">
                <div class="fc-tags">
                  <span class="level-tag level-<?= esc($course->level ?? 'beginner') ?>"><?= ucfirst(esc($course->level ?? 'beginner')) ?></span>
                  <?php if ($isPaidCourse): ?>
                    <span class="badge" style="background:#fff7ed;color:#7c2d12;border:1px solid #fde3bf">Paid</span>
                    <span class="course-cert-badge"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span>
                  <?php else: ?>
                    <span class="badge badge-blue">Free</span>
                  <?php endif; ?>
                </div>
                <h3 class="fc-title"><?= esc($course->title) ?></h3>
                <p class="fc-desc"><?= esc(mb_substr(strip_tags((string)$course->description), 0, 140)) ?>...</p>
                <div class="fc-meta-row">
                  <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($course->duration ?: 'Self-paced') ?></span>
                  <a href="<?= base_url('training/course/' . $course->id) ?>">View course →</a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- FILTER & CATALOG -->
  <section class="section hiw-bg" id="catalog" aria-labelledby="cat-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-bag"/></svg> Course catalog</p>
      <div class="jobs-header" style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px;margin-bottom:6px;">
        <h2 class="section-title" id="cat-h">Find the right <span>training path</span></h2>
        <span class="section-sub" style="margin:0">Showing <strong style="color:var(--brand)"><?= count($courses) ?></strong> of <strong class="live-count" data-count="<?= $totalActive ?? count($courses) ?>" style="color:var(--brand)"><?= $totalActive ?? count($courses) ?></strong> courses</span>
      </div>
      <p class="section-sub">Filter by level, content type, or price to find a course that fits your goals.</p>

      <!-- Filter bar -->
      <form class="filter-bar" action="<?= base_url('training') ?>" method="get" role="search" aria-label="Course filter">
        <div class="filter-field">
          <svg aria-hidden="true"><use href="#i-search"/></svg>
          <input type="search" name="q" placeholder="Search courses…" value="<?= esc($q ?? '') ?>" autocomplete="off">
        </div>
        <div class="filter-field" style="flex:0 1 160px">
          <svg aria-hidden="true"><use href="#i-cap"/></svg>
          <select name="level" aria-label="Level">
            <option value="">All levels</option>
            <option value="beginner" <?= ($level ?? '') == 'beginner' ? 'selected' : '' ?>>Beginner</option>
            <option value="intermediate" <?= ($level ?? '') == 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
            <option value="advanced" <?= ($level ?? '') == 'advanced' ? 'selected' : '' ?>>Advanced</option>
          </select>
        </div>
        <div class="filter-field" style="flex:0 1 170px">
          <svg aria-hidden="true"><use href="#i-doc"/></svg>
          <select name="type" aria-label="Item type">
            <option value="">Course &amp; eBook</option>
            <option value="course" <?= ($type ?? '') == 'course' ? 'selected' : '' ?>>Course</option>
            <option value="ebook" <?= ($type ?? '') == 'ebook' ? 'selected' : '' ?>>eBook (PDF)</option>
          </select>
        </div>
        <div class="filter-field" style="flex:0 1 150px">
          <svg aria-hidden="true"><use href="#i-bag"/></svg>
          <select name="price" aria-label="Price">
            <option value="">Any price</option>
            <option value="free" <?= ($price ?? '') == 'free' ? 'selected' : '' ?>>Free</option>
            <option value="paid" <?= ($price ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
          </select>
        </div>
        <button type="submit"><svg aria-hidden="true"><use href="#i-search"/></svg> Filter</button>
      </form>

      <div class="filter-chips" aria-label="Quick filters">
        <button type="button" class="filter-chip active">All courses</button>
        <button type="button" class="filter-chip">Free</button>
        <button type="button" class="filter-chip">Certificate</button>
        <button type="button" class="filter-chip">Beginner</button>
        <button type="button" class="filter-chip">Intermediate</button>
        <button type="button" class="filter-chip">Advanced</button>
        <button type="button" class="filter-chip">eBooks</button>
      </div>

      <!-- Catalog Course Grid -->
      <div class="course-grid" id="courseCatalog">
        <?php if (empty($courses)): ?>
          <div class="col-12 py-5 text-center text-muted">
            <p class="mb-0">No courses match your active search filters.</p>
          </div>
        <?php else: ?>
          <?php foreach ($courses as $index => $course): 
              $isPaidCourse = (float)($course->price ?? 0) > 0;
              $fallbackNum = ($index % 4) + 1;
          ?>
            <article class="course-card" 
                     data-level="<?= esc($course->level ?? 'beginner') ?>" 
                     data-price="<?= $isPaidCourse ? 'paid' : 'free' ?>" 
                     data-type="<?= esc($course->item_type ?? 'course') ?>">
              
              <div class="course-thumb fc-thumb-<?= $fallbackNum ?>">
                <?php if ($course->is_featured): ?>
                  <span class="fc-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
                <?php endif; ?>
                
                <?php if ($course->thumbnail): ?>
                  <img class="thumb-img" src="<?= base_url($course->thumbnail) ?>" alt="<?= esc($course->title) ?>" loading="lazy">
                <?php else: ?>
                  <svg class="thumb-fallback-icon" aria-hidden="true" style="width:36px;height:36px;color:rgba(255,255,255,0.85)">
                    <use href="<?= ($course->item_type ?? 'course') === 'ebook' ? '#i-doc' : '#i-cap' ?>"/>
                  </svg>
                <?php endif; ?>

                <div class="thumb-type-badge">
                  <svg aria-hidden="true" width="11" height="11">
                    <use href="<?= ($course->item_type ?? 'course') === 'ebook' ? '#i-doc' : '#i-cap' ?>"/>
                  </svg>
                  <?= ($course->item_type ?? 'course') === 'ebook' ? 'eBook' : 'Course' ?>
                </div>
              </div>

              <div class="course-body">
                <div class="course-tags">
                  <span class="level-tag level-<?= esc($course->level ?? 'beginner') ?>"><?= ucfirst(esc($course->level ?? 'beginner')) ?></span>
                  <?php if ($isPaidCourse): ?>
                    <span class="badge" style="background:#fff7ed;color:#7c2d12;border:1px solid #fde3bf">Paid</span>
                    <span class="course-cert-badge"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span>
                  <?php else: ?>
                    <span class="badge badge-blue">Free</span>
                  <?php endif; ?>
                </div>

                <h3 class="course-title">
                  <a href="<?= base_url('training/course/' . $course->id) ?>">
                    <?= esc($course->title) ?>
                  </a>
                </h3>

                <p class="course-desc"><?= esc(mb_substr(strip_tags((string)$course->description), 0, 130)) ?>...</p>
                
                <div class="course-meta">
                  <span>
                    <svg aria-hidden="true" width="13" height="13"><use href="#i-users"/></svg>
                    <strong><?= esc($course->instructor ?: 'JobberRecruit') ?></strong>
                  </span>
                  <span>
                    <svg aria-hidden="true" width="13" height="13"><use href="#i-clock"/></svg>
                    <?= esc($course->duration ?: 'Self-paced') ?>
                  </span>
                </div>
              </div>

              <div class="course-footer">
                <div class="course-price">
                  <?php if ($isPaidCourse): ?>
                    <span class="course-price--paid">₦<?= number_format((float)$course->price, 2) ?></span>
                  <?php else: ?>
                    <span class="course-price--free">Free</span>
                  <?php endif; ?>
                </div>
                <a href="<?= base_url('training/course/' . $course->id) ?>" class="btn btn-primary btn-sm">
                  View Course
                </a>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="section" aria-labelledby="hiw-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-spark"/></svg> How it works</p>
      <h2 class="section-title" id="hiw-h">Simple steps to <span>advance your career</span></h2>
      <p class="section-sub">Upgrade your credentials and stand out to recruiters in four simple steps.</p>

      <div class="steps-grid">
        <div class="step-card">
          <div class="step-num"></div>
          <div class="step-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
          <h3 class="step-title">Choose your path</h3>
          <p class="step-desc">Select from technology, business administration, interview prep or leadership courses curated by our team.</p>
        </div>
        <div class="step-card">
          <div class="step-num"></div>
          <div class="step-ic"><svg aria-hidden="true"><use href="#i-bulb"/></svg></div>
          <h3 class="step-title">Study at your pace</h3>
          <p class="step-desc">Access video lectures, structured worksheets, and downloadable eBooks on any mobile or desktop web browser.</p>
        </div>
        <div class="step-card">
          <div class="step-num"></div>
          <div class="step-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
          <h3 class="step-title">Take the assessment</h3>
          <p class="step-desc">Pass the course assessment at the end of modules to prove your mastery of the core subject curriculum.</p>
        </div>
        <div class="step-card">
          <div class="step-num"></div>
          <div class="step-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></div>
          <h3 class="step-title">Get certified</h3>
          <p class="step-desc">Paid courses issue secure JobberRecruit digital certificates you can download, verify, and link to your profile.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CERTIFICATE BAND -->
  <section class="section hiw-bg" style="padding-top:0" aria-label="Certifications info">
    <div class="container">
      <div class="cert-band">
        <div class="cert-band-text">
          <div class="cert-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></div>
          <div>
            <h3 class="cert-band-title">Earn employer-recognised <span>JobberRecruit Certificates</span></h3>
            <p class="cert-band-sub">Attach completed certificates to your job applications automatically. Candidates with verified certificates are <strong>3x more likely</strong> to be shortlisted.</p>
          </div>
        </div>
        <div class="cert-band-cta">
          <a href="#catalog" class="btn btn-accent btn-lg">Browse paid courses</a>
        </div>
      </div>
    </div>
  </section>

  <!-- STUDENT TESTIMONIALS -->
  <section class="section testi-bg" aria-labelledby="testi-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-users"/></svg> Success stories</p>
      <h2 class="section-title" id="testi-h">Hear from our <span>successful alumni</span></h2>
      <p class="section-sub">Thousands of Nigerian job seekers have used our courses to level up their careers.</p>

      <div class="testi-grid">
        <div class="testi-card">
          <div class="testi-stars">
            <?php for($i=0;$i<5;$i++): ?><svg aria-hidden="true"><use href="#i-star"/></svg><?php endfor; ?>
          </div>
          <p class="testi-text">"The Customer Success playbook course was exactly what I needed. I added the JobberRecruit certificate to my CV and landed a remote support specialist job in Lagos within two weeks!"</p>
          <div class="testi-author">
            <div class="testi-avatar">CO</div>
            <div>
              <div class="testi-name">Chidinma O.</div>
              <div class="testi-role">Customer Success Specialist</div>
            </div>
          </div>
        </div>

        <div class="testi-card">
          <div class="testi-stars">
            <?php for($i=0;$i<5;$i++): ?><svg aria-hidden="true"><use href="#i-star"/></svg><?php endfor; ?>
          </div>
          <p class="testi-text">"Highly recommend the Agile Project Management eBook. The templates are practical, and the interview prep module gave me the confidence to ace my Scrum Master interview."</p>
          <div class="testi-author">
            <div class="testi-avatar">TB</div>
            <div>
              <div class="testi-name">Tunde B.</div>
              <div class="testi-role">Project Manager</div>
            </div>
          </div>
        </div>

        <div class="testi-card">
          <div class="testi-stars">
            <?php for($i=0;$i<5;$i++): ?><svg aria-hidden="true"><use href="#i-star"/></svg><?php endfor; ?>
          </div>
          <p class="testi-text">"Having a verified certificate directly linked to my JobberRecruit profile caught the eye of two hiring managers. The assessment structure is rigorous but completely worth it."</p>
          <div class="testi-author">
            <div class="testi-avatar">AE</div>
            <div>
              <div class="testi-name">Aminat E.</div>
              <div class="testi-role">HR Coordinator</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQS -->
  <section class="section faq-bg" aria-labelledby="faq-h">
    <div class="container" style="max-width:800px">
      <div class="text-center" style="margin-bottom:40px">
        <p class="section-label"><svg aria-hidden="true"><use href="#i-bulb"/></svg> FAQ</p>
        <h2 class="section-title" id="faq-h">Frequently asked <span>questions</span></h2>
        <p class="section-sub" style="margin:0 auto">Find quick answers to common questions about our marketplace.</p>
      </div>

      <div class="faq-list">
        <details class="faq-item" open>
          <summary>Are the certificates really free? <svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary>
          <div class="faq-answer"><p>Free courses do not issue certificates. Digital certificates are earned upon passing modules/assessments exclusively on paid courses.</p></div>
        </details>
        <details class="faq-item">
          <summary>How do I download my certificates? <svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary>
          <div class="faq-answer"><p>Once you complete all lessons in a paid course and pass the assessment, a download link will activate in your candidate dashboard under "My Certificates".</p></div>
        </details>
        <details class="faq-item">
          <summary>Can I access courses on my phone? <svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary>
          <div class="faq-answer"><p>Yes. All courses, including YouTube-hosted lessons and uploaded files, are fully accessible on mobile browsers — no separate app required.</p></div>
        </details>
      </div>
    </div>
  </section>

  <!-- DUAL CTA -->
  <section class="section" style="padding-top:0" aria-label="Call to action">
    <div class="container">
      <div class="dual-cta">
        <div class="cta-panel blue">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-cap"/></svg></div>
          <h2>Ready to upskill?</h2>
          <p>Create a free account to enrol, track progress, and download certificates.</p>
          <ul class="cta-list" aria-label="Learner benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Free &amp; paid courses</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Certificates on paid courses</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Learn on any device</li>
          </ul>
          <a href="<?= base_url('register') ?>" class="btn btn-accent">Create free account →</a>
        </div>
        <div class="cta-panel light">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div>
          <h2>Want to publish a course?</h2>
          <p>Partner with JobberRecruit to reach job seekers across Nigeria.</p>
          <ul class="cta-list" aria-label="Instructor benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Reach a built-in audience</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> YouTube or upload hosting</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Free or paid pricing</li>
          </ul>
          <a href="<?= base_url('contact-us') ?>" class="btn btn-primary">Become an instructor →</a>
        </div>
      </div>
    </div>
  </section>

</main>

<button id="btt" aria-label="Back to top" title="Back to top"><svg aria-hidden="true"><use href="#i-arrow-up"/></svg></button>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ═══════════════════════════════════════════════════════════════════
       TRAINING LISTING PAGE — Brand colors: var(--brand) (blue), var(--accent) (orange)
       ═══════════════════════════════════════════════════════════════════ */
    .filter-bar > button svg {
      width: 16px;
      height: 16px;
      stroke-width: 2.5;
      fill: none;
      stroke: currentColor;
    }
    :root {
      --brand:        #0D609E;
      --brand-dark:   #0A4D7E;
      --brand-deep:   #07304F;
      --brand-light:  #E6F0F9;
      --accent:       #F08F1A;
      --accent-dark:  #C8750E;
      --text:         #1E293B;
      --muted:        #64748B;
      --bg:           #F8F9FA;
      --white:        #ffffff;
      --border:       #e2e8f0;
      --success:      #10B981;
      --radius:       12px;
      --shadow:       0 2px 14px rgba(13,96,158,.08);
      --shadow-lg:    0 14px 40px rgba(13,96,158,.12);
      --transition:   .2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] {
      --white:        #0F172A;
      --bg:           #1E293B;
      --text:         #F1F5F9;
      --muted:        #94A3B8;
      --border:       #334155;
      --brand-light:  rgba(13, 96, 158, 0.2);
      --shadow:       none;
      --shadow-lg:    none;
    }

    /* Buttons */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      padding: 11px 22px; border-radius: 8px;
      font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 600;
      cursor: pointer; border: 1.5px solid transparent;
      transition: var(--transition); text-decoration: none;
      -webkit-tap-highlight-color: transparent; touch-action: manipulation;
    }
    .btn svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.2; }
    .btn-primary  { background: var(--brand);  color: var(--white); border-color: var(--brand); }
    .btn-primary:hover  { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; }
    .btn-outline  { background: transparent; color: var(--brand); border-color: var(--border); }
    .btn-outline:hover  { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
    .btn-accent   { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
    .btn-accent:hover   { background: var(--accent-dark); border-color: var(--accent-dark); color: var(--brand-deep); text-decoration: none; }
    .btn-white    { background: var(--white); color: var(--brand); border-color: var(--white); }
    .btn-white:hover    { background: var(--brand-light); text-decoration: none; }
    .btn-sm       { padding: 8px 14px; font-size: .78rem; }
    .btn-lg       { padding: 14px 32px; font-size: .95rem; }
    .btn-block    { width: 100%; }

    .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .badge svg { width: 12px; height: 12px; }
    .badge-blue  { background: var(--brand-light); color: var(--brand); }

    /* ── Hero Section ── */
    .tr-hero {
      background:
        radial-gradient(ellipse 70% 60% at 82% 20%, rgba(240,143,26,.12) 0%, transparent 55%),
        radial-gradient(ellipse 80% 70% at 10% 90%, rgba(13,96,158,.28) 0%, transparent 55%),
        linear-gradient(160deg, var(--brand-deep) 0%, var(--brand-dark) 55%, var(--brand) 100%);
      color: #ffffff;
      padding: 64px 0 56px;
      position: relative; 
      overflow: hidden;
    }
    .tr-hero-grid-bg {
      position: absolute; inset: 0; pointer-events: none; opacity: .4;
      background-image:
        linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
      background-size: 46px 46px;
      -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
              mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
    }
    .tr-hero-inner { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; gap: 28px 36px; flex-wrap: wrap; }
    .tr-hero-text { flex: 1 1 480px; max-width: 600px; }
    .tr-hero-tag {
      display: inline-flex; align-items: center; gap: 7px;
      font-size: .75rem; font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--accent) !important; margin-bottom: 16px;
    }
    .tr-hero-tag svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.2; }
    .tr-hero h1 { font-family: 'Sora', sans-serif; font-size: clamp(1.9rem, 4.4vw, 2.85rem); font-weight: 800; line-height: 1.15; margin-bottom: 16px; }
    .tr-hero-sub { font-size: 1rem; color: rgba(255, 255, 255, 0.95) !important; max-width: 520px; margin-bottom: 28px; }
    .tr-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }

    /* Stat panel */
    .tr-stats {
      flex: 0 1 320px; background: rgba(7,48,79,.65); border: 1px solid rgba(255,255,255,.14);
      border-radius: 14px; padding: 6px; backdrop-filter: blur(6px);
    }
    .tr-stat-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; }
    .tr-stat-row + .tr-stat-row { border-top: 1px solid rgba(255,255,255,.1); }
    .tr-stat-num { font-family: 'Sora', sans-serif; font-size: 1.7rem; font-weight: 800; color: #ffffff; }
    .tr-stat-label { font-size: .8rem; color: rgba(255,255,255,.8); font-weight: 500; }

    /* ── FEATURED GRID ── */
    .section { padding: 76px 0; }
    .section-label {
      display: inline-flex; align-items: center; gap: 7px;
      font-size: .72rem; font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: var(--brand);
      background: var(--brand-light); padding: 5px 13px;
      border-radius: 20px; margin-bottom: 14px;
    }
    .section-label svg { width: 13px; height: 13px; }
    .section-title {
      font-size: clamp(1.6rem, 2.9vw, 2.25rem);
      font-weight: 800; line-height: 1.15; margin-bottom: 12px;
      font-family: 'Sora', sans-serif;
    }
    .section-title span { color: var(--brand); }
    .section-sub { color: var(--muted); font-size: .95rem; max-width: 560px; margin-bottom: 24px; }

    .feat-course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; margin-top: 28px; }
    .feat-course-card {
      position: relative;
      background: var(--white); border: 1px solid var(--border); border-left: 4px solid var(--accent); border-radius: var(--radius);
      overflow: hidden; transition: var(--transition); display: flex; flex-direction: column;
    }
    .feat-course-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); border-left-color: var(--accent); transform: translateY(-3px); }
    .feat-course-card.is-paid { border-color: rgba(240,143,26,.3); }
    .fc-featured-badge {
      position: absolute; top: 12px; left: 12px; z-index: 3;
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent); color: var(--brand-deep);
      font-size: .66rem; font-weight: 800; letter-spacing: .03em;
      padding: 4px 10px; border-radius: 20px; box-shadow: 0 2px 6px rgba(13,96,158,.22);
    }
    .fc-featured-badge svg { width: 11px; height: 11px; }
    
    .fc-thumb { position: relative; height: 140px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.92); overflow: hidden; }
    .fc-thumb svg.fc-thumb-icon { width: 40px; height: 40px; color: rgba(255,255,255,0.85); }
    .fc-thumb img.thumb-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .fc-body { padding: 18px 20px 20px; display: flex; flex-direction: column; gap: 11px; flex: 1; }
    .fc-tags { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
    .fc-title { font-size: 1rem; font-weight: 700; color: var(--text); }
    .fc-desc { font-size: .82rem; color: var(--muted); flex: 1; }
    .fc-meta-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; font-size: .8rem; color: var(--muted); }
    .fc-meta-row span { display: inline-flex; align-items: center; gap: 5px; }
    .fc-meta-row svg { width: 13px; height: 13px; }
    .fc-meta-row a { font-weight: 600; color: var(--brand); }
    
    .fc-thumb-1 { background: linear-gradient(135deg, var(--brand-deep), var(--brand)); }
    .fc-thumb-2 { background: linear-gradient(135deg, var(--brand-dark), #2575fc); }
    .fc-thumb-3 { background: linear-gradient(135deg, var(--brand), var(--accent-dark)); }
    .fc-thumb-4 { background: linear-gradient(135deg, var(--brand-deep), var(--accent)); }

    /* ── FILTER BAR ── */
    .filter-bar {
      background: var(--white); border: 1px solid var(--border); border-radius: 14px;
      padding: 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
      box-shadow: var(--shadow); margin-top: 28px;
    }
    .filter-field { position: relative; flex: 1 1 180px; display: flex; align-items: center; }
    .filter-field svg { position: absolute; left: 12px; width: 16px; height: 16px; color: var(--muted); pointer-events: none; stroke: currentColor; fill: none; stroke-width: 2.2; }
    .filter-bar input, .filter-bar select {
      width: 100%; border: 1px solid var(--border); border-radius: 8px;
      padding: 10px 14px 10px 36px; font-family: 'Inter', sans-serif; font-size: .9rem;
      color: var(--text); background: var(--bg); outline: none;
      appearance: none; -webkit-appearance: none; min-height: 42px;
    }
    .filter-bar select { padding-left: 36px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color: var(--brand); background: var(--white); }
    .filter-bar > button {
      flex: 0 0 auto; padding: 10px 22px; background: var(--brand); color: #ffffff;
      border: none; border-radius: 8px; font-family: 'Inter', sans-serif;
      font-size: .9rem; font-weight: 600; cursor: pointer; transition: var(--transition);
      min-height: 42px; display: inline-flex; align-items: center; gap: 7px;
    }
    .filter-bar > button:hover { background: var(--brand-dark); }
    .filter-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
    .filter-chip {
      display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px;
      border-radius: 20px; font-size: .8rem; font-weight: 600; color: var(--text);
      background: var(--white); border: 1.5px solid var(--border); cursor: pointer; transition: var(--transition);
    }
    .filter-chip:hover { border-color: var(--brand); color: var(--brand); }
    .filter-chip.active { background: var(--brand); color: #ffffff; border-color: var(--brand); }

    /* ── COURSE CATALOG GRID ── */
    .course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 18px; margin-top: 28px; }
    .course-card { position: relative; border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; background: var(--white); transition: var(--transition); display: flex; flex-direction: column; }
    .course-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); border-color: var(--brand); }

    .course-thumb {
      height: 140px; position: relative; display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.92); overflow: hidden; background-size: cover; background-position: center;
    }
    .course-thumb img.thumb-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .thumb-type-badge {
      position: absolute; top: 10px; left: 10px; z-index: 2;
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--accent); color: var(--brand-deep);
      font-size: .66rem; font-weight: 700; letter-spacing: .03em; text-transform: uppercase;
      padding: 4px 9px; border-radius: 20px;
    }
    .thumb-type-badge svg { width: 11px; height: 11px; }

    .course-body { padding: 18px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
    .course-tags { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
    .level-tag { font-size: .68rem; font-weight: 700; letter-spacing: .03em; padding: 3px 10px; border-radius: 20px; }
    .level-beginner     { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
    .level-intermediate { background: #fff7ed; color: #7c2d12; border: 1px solid #fde3bf; }
    .level-advanced     { background: #fdecec; color: #b91c1c; border: 1px solid #fca5a5; }
    .course-cert-badge {
      display: inline-flex; align-items: center; gap: 4px;
      font-size: .68rem; font-weight: 700; letter-spacing: .02em;
      padding: 3px 9px; border-radius: 20px;
      background: var(--brand-light); color: var(--brand);
    }
    .course-cert-badge svg { width: 11px; height: 11px; }
    .course-title { font-weight: 700; font-size: .92rem; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em; }
    .course-title a { color: var(--text); text-decoration: none; }
    .course-title a:hover { color: var(--brand); }
    .course-instructor { font-size: .78rem; color: var(--muted); }
    .course-desc { font-size: .8rem; color: var(--muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1; }
    .course-meta { font-size: .76rem; color: var(--muted); display: flex; gap: 14px; }
    .course-meta span { display: inline-flex; align-items: center; gap: 5px; }
    .course-meta svg { width: 13px; height: 13px; }
    .course-footer { padding: 14px 18px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .course-price { font-size: .92rem; font-weight: 800; }
    .course-price--free { color: var(--accent-dark); }
    .course-price--paid { color: var(--brand); }

    /* ── HOW TRAINING WORKS ── */
    .hiw-bg { background: var(--white); }
    .steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px; margin-top: 40px; counter-reset: step; }
    .step-card { position: relative; border: 1px solid var(--border); border-radius: var(--radius); padding: 30px 24px 26px; background: var(--bg); transition: var(--transition); }
    .step-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); background: var(--white); }
    .step-num { counter-increment: step; font-family: 'Sora', sans-serif; font-size: 2.4rem; font-weight: 800; color: var(--brand-light); line-height: 1; margin-bottom: 4px; }
    .step-num::before { content: counter(step, decimal-leading-zero); }
    .step-card:last-child .step-num { color: #fbe6c2; }
    .step-ic { width: 40px; height: 40px; border-radius: 10px; background: var(--brand); color: #ffffff; display: flex; align-items: center; justify-content: center; margin: -22px 0 14px auto; }
    .step-card:last-child .step-ic { background: var(--accent); color: var(--brand-deep); }
    .step-ic svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 2.2; }
    .step-title { font-weight: 700; font-size: .98rem; color: var(--text); margin-bottom: 8px; }
    .step-desc  { font-size: .83rem; color: var(--muted); line-height: 1.65; }

    /* ── CERT BAND ── */
    .cert-band { background: linear-gradient(120deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; border-radius: 14px; padding: 24px 28px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; margin-top: 40px; }
    [data-theme="dark"] .cert-band { background: linear-gradient(120deg, var(--brand-deep), var(--brand-dark)); border-color: var(--border); }
    .cert-band-text { display: flex; align-items: center; gap: 18px; flex: 1 1 420px; }
    .cert-ic { width: 54px; height: 54px; border-radius: 12px; background: var(--accent); color: var(--brand-deep); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cert-ic svg { width: 26px; height: 26px; }
    .cert-band-title { font-family: 'Sora', sans-serif; font-size: clamp(1.15rem, 2vw, 1.45rem); font-weight: 800; line-height: 1.25; letter-spacing: -.01em; margin-bottom: 4px; color: #1E293B; }
    [data-theme="dark"] .cert-band-title { color: #F1F5F9; }
    .cert-band-title span { color: var(--accent-dark); }
    [data-theme="dark"] .cert-band-title span { color: var(--accent); }
    .cert-band-sub { font-size: .86rem; color: var(--muted); max-width: 520px; }
    .cert-band-cta { flex-shrink: 0; }

    /* ── TESTIMONIALS ── */
    .testi-bg { background: var(--white); }
    .testi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 36px; }
    .testi-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 26px; }
    .testi-stars { color: var(--accent); display: flex; gap: 2px; margin-bottom: 14px; }
    .testi-stars svg { width: 16px; height: 16px; }
    .testi-text { font-size: .88rem; color: var(--text); line-height: 1.75; margin-bottom: 16px; }
    .testi-author { display: flex; align-items: center; gap: 11px; }
    .testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--brand); color: #ffffff; font-family: 'Sora', sans-serif; font-weight: 600; font-size: .82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .testi-name { font-weight: 700; font-size: .85rem; color: var(--text); }
    .testi-role { font-size: .74rem; color: var(--muted); }

    /* ── FAQ ── */
    .faq-bg { background: var(--white); }
    .faq-list { display: flex; flex-direction: column; gap: 12px; margin-top: 30px; }
    .faq-item { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; transition: var(--transition); }
    .faq-item summary { padding: 18px 24px; font-weight: 700; font-size: .95rem; cursor: pointer; display: flex; align-items: center; justify-content: space-between; list-style: none; user-select: none; color: var(--text) !important; }
    .faq-item summary::-webkit-details-marker { display: none; }
    .faq-chev { width: 16px; height: 16px; color: var(--muted); transition: transform .2s ease; }
    .faq-chev svg { color: var(--text) !important; }
    .faq-item[open] summary .faq-chev { transform: rotate(180deg); }
    .faq-answer { padding: 0 24px 20px; border-top: 1px solid transparent; font-size: .88rem; color: var(--muted) !important; line-height: 1.65; }
    .faq-answer p { color: var(--muted) !important; }
    .faq-item[open] { background: var(--white); border-color: var(--brand); }

    /* ── DUAL CTA ── */
    .dual-cta { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 40px; }
    .cta-panel { border-radius: 16px; padding: 40px; display: flex; flex-direction: column; align-items: flex-start; gap: 16px; position: relative; overflow: hidden; }
    .cta-panel.blue { background: linear-gradient(135deg, var(--brand-deep), var(--brand)); color: #ffffff; }
.cta-panel.blue h2, .cta-panel.blue p, .cta-panel.blue li, .cta-panel.blue strong, .cta-panel.blue a { color: var(--white) !important; }
    .cta-panel.light { background: var(--bg); border: 1px solid var(--border); color: var(--text); }
    .cta-ic { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .cta-panel.blue .cta-ic { background: rgba(255,255,255,.1); color: var(--accent); }
    .cta-panel.light .cta-ic { background: var(--brand-light); color: var(--brand); }
    .cta-ic svg { width: 24px; height: 24px; fill: none; stroke: currentColor; stroke-width: 2.2; }
    .cta-panel h2 { font-family: 'Sora', sans-serif; font-size: 1.6rem; font-weight: 800; color: inherit; }
    .cta-panel p { font-size: .95rem; opacity: .9; color: inherit; }
    .cta-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .cta-list li { display: flex; align-items: center; gap: 10px; font-size: .88rem; color: inherit; }
    .cta-list li svg { width: 14px; height: 14px; color: var(--accent); }
    .cta-panel.light .cta-list li svg { color: var(--brand); }

    /* ── Back to top button ── */
    #btt {
      position: fixed; bottom: 24px; right: 24px; z-index: 99;
      width: 44px; height: 44px; border-radius: 50%;
      background: var(--brand); color: #ffffff; border: none;
      box-shadow: 0 4px 10px rgba(13,96,158,.3);
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      opacity: 0; visibility: hidden; transition: var(--transition);
    }
    #btt.show { opacity: 1; visibility: visible; }
    #btt svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 2.5; }

    @media (max-width: 768px) {
      .tr-stats { flex: 1 1 100%; }
      .course-grid { grid-template-columns: 1fr; }
      .dual-cta { grid-template-columns: 1fr; }
      .cta-panel { padding: 24px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
  /* ── Back to top ── */
  const btt = $('#btt');
  $(window).on('scroll', function() {
    if ($(window).scrollTop() > 400) {
      btt.addClass('show');
    } else {
      btt.removeClass('show');
    }
  });
  btt.on('click', function() {
    $('html, body').animate({ scrollTop: 0 }, 'smooth');
  });

  /* ── Quick filter chips ── */
  const chips = $('.filter-chip');
  const cards = $('.course-grid .course-card');
  const chipMap = {
    'All courses': null,
    'Free': { price: 'free' },
    'Certificate': { price: 'paid' }, 
    'Beginner': { level: 'beginner' },
    'Intermediate': { level: 'intermediate' },
    'Advanced': { level: 'advanced' },
    'eBooks': { type: 'ebook' }
  };

  chips.on('click', function() {
    chips.removeClass('active');
    $(this).addClass('active');
    const label = $(this).text().trim();
    const rule = chipMap[label];

    cards.each(function() {
      const card = $(this);
      if (!rule) {
        card.show();
        return;
      }
      let match = true;
      if (rule.level && card.attr('data-level') !== rule.level) match = false;
      if (rule.price && card.attr('data-price') !== rule.price) match = false;
      if (rule.type  && card.attr('data-type')  !== rule.type)  match = false;
      
      if (match) card.show();
      else card.hide();
    });
  });
});
</script>
<?= $this->endSection() ?>