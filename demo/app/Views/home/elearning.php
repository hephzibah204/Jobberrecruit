<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "JobberRecruit Professional E-Learning Courses",
  "description": "Browse admin-curated professional training courses, career development guides, and tech certifications on JobberRecruit.",
  "url": "<?= current_url() ?>",
  "numberOfItems": <?= count($courses) ?>,
  "itemListElement": [
    <?php foreach (array_slice($courses, 0, 10) as $index => $course): ?>
    {
      "@type": "ListItem",
      "position": <?= $index + 1 ?>,
      "item": {
        "@type": "Course",
        "name": "<?= esc($course->title) ?>",
        "description": "<?= esc(mb_substr(strip_tags((string) $course->description), 0, 150)) ?>...",
        "provider": {
          "@type": "Organization",
          "name": "JobberRecruit",
          "sameAs": "<?= base_url() ?>"
        },
        "hasCourseInstance": {
          "@type": "CourseInstance",
          "courseMode": "online",
          "duration": "<?= esc($course->duration ?: 'Self-paced') ?>",
          "offers": {
            "@type": "Offer",
            "price": "<?= (float) ($course->price ?? 0) ?>",
            "priceCurrency": "NGN",
            "category": "<?= (float) ($course->price ?? 0) > 0 ? 'Paid' : 'Free' ?>"
          }
        }
      }
    }<?= $index < min(count($courses) - 1, 9) ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="course-hero">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <span class="eyebrow">Training Marketplace</span>
                <h1 class="hero-title">Learn practical skills with admin-curated free and premium courses.</h1>
                <p class="hero-copy">Explore job-ready training, interview prep, hiring playbooks, and AI-driven career development resources built for JobberRecruit users.</p>
                <div class="hero-actions">
                    <a href="#course-catalog" class="btn btn-primary">Browse Courses</a>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline-primary">Create Account</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-panel">
                    <div class="hero-stat">
                        <strong><?= count($courses) ?></strong>
                        <span>Active courses</span>
                    </div>
                    <div class="hero-stat">
                        <strong><?= $freeCount ?></strong>
                        <span>Free courses</span>
                    </div>
                    <div class="hero-stat">
                        <strong><?= $paidCount ?></strong>
                        <span>Paid courses</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (! empty($featuredCourses)): ?>
<section class="section-box mt-70">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="eyebrow">Featured Courses</span>
                <h2>Top picks for candidates and employers</h2>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredCourses as $course): ?>
                <div class="col-lg-4">
                    <div class="featured-course-card">
                        <div class="featured-course-meta">
                            <?php if (($course->item_type ?? 'course') === 'ebook'): ?>
                                <span class="badge-soft" style="background:#198754;color:#fff;">EBOOK (PDF)</span>
                            <?php else: ?>
                                <span class="badge-soft"><?= strtoupper((string) ($course->level ?? 'beginner')) ?></span>
                            <?php endif; ?>
                            <span class="badge-soft"><?= (float) $course->price > 0 ? 'PAID' : 'FREE' ?></span>
                        </div>
                        <h3><?= esc($course->title) ?></h3>
                        <p><?= esc(mb_substr(strip_tags((string) $course->description), 0, 130)) ?>...</p>
                        <div class="featured-course-footer">
                            <span><?= esc($course->duration ?: 'Self-paced') ?></span>
                            <a href="<?= base_url('training/course/' . $course->id) ?>">View Course</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section-box mt-70" id="course-catalog">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="eyebrow">Course Catalog</span>
                <h2>Find the right training path</h2>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($courses)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <h4>No courses available yet.</h4>
                        <p>Create some courses from the admin dashboard to populate this landing page.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-xl-4 col-md-6">
                        <div class="course-card">
                            <div class="course-card-image">
                                <img src="<?= $course->thumbnail ? base_url($course->thumbnail) : 'https://placehold.co/900x560/0d6efd/ffffff?text=' . urlencode($course->title) ?>" alt="<?= esc($course->title) ?>">
                                <div class="course-card-badges">
                                    <?php if (($course->item_type ?? 'course') === 'ebook'): ?>
                                        <span style="background:#198754;color:#fff;">eBook (PDF)</span>
                                    <?php else: ?>
                                        <span><?= ucfirst((string) ($course->level ?? 'beginner')) ?></span>
                                        <span><?= ucfirst((string) ($course->content_source ?? 'none')) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="course-card-body">
                                <div class="course-price-row">
                                    <strong><?= (float) $course->price > 0 ? 'N' . number_format((float) $course->price, 2) : 'Free' ?></strong>
                                    <span><?= esc($course->duration ?: 'Self-paced') ?></span>
                                </div>
                                <h3><?= esc($course->title) ?></h3>
                                <p><?= esc(mb_substr(strip_tags((string) $course->description), 0, 120)) ?>...</p>
                                <div class="course-instructor">By <?= esc($course->instructor ?: 'JobberRecruit') ?></div>
                            </div>
                            <div class="course-card-footer">
                                <a href="<?= base_url('training/course/' . $course->id) ?>" class="btn btn-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.course-hero {
    padding: 110px 0 80px;
    background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
}
.eyebrow {
    display: inline-block;
    margin-bottom: 14px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.12em;
    color: #0d6efd;
    text-transform: uppercase;
}
.hero-title {
    font-size: clamp(2.2rem, 4vw, 3.4rem);
    line-height: 1.1;
    margin-bottom: 18px;
    color: #111827;
}
.hero-copy {
    font-size: 1.05rem;
    color: #4b5563;
    margin-bottom: 24px;
    max-width: 640px;
}
.hero-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.hero-panel {
    background: #111827;
    color: #fff;
    border-radius: 24px;
    padding: 28px;
    display: grid;
    gap: 16px;
}
.hero-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    padding-bottom: 12px;
}
.hero-stat strong {
    font-size: 1.7rem;
}
.hero-note {
    color: rgba(255, 255, 255, 0.82);
    font-size: 0.95rem;
}
.section-header {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: end;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.section-header h2 {
    margin: 0;
    font-size: 2rem;
}
.section-copy {
    max-width: 560px;
    color: #6b7280;
    margin: 0;
}
.featured-course-card,
.course-card,
.empty-state {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 22px;
    box-shadow: 0 14px 35px rgba(17, 24, 39, 0.06);
}
.featured-course-card {
    padding: 26px;
    height: 100%;
}
.featured-course-meta,
.course-card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 18px;
}
.badge-soft,
.course-card-badges span {
    border-radius: 999px;
    background: #eff6ff;
    color: #0d6efd;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 12px;
}
.featured-course-card h3,
.course-card h3 {
    font-size: 1.25rem;
    margin-bottom: 12px;
}
.featured-course-card p,
.course-card p {
    color: #6b7280;
    margin-bottom: 18px;
}
.featured-course-footer,
.course-price-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
}
.featured-course-footer a {
    color: #0d6efd;
    font-weight: 600;
}
.course-card {
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.course-card-image {
    position: relative;
}
.course-card-image img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}
.course-card-badges {
    position: absolute;
    top: 16px;
    left: 16px;
    margin-bottom: 0;
}
.course-card-body {
    padding: 24px;
    flex: 1;
}
.course-price-row {
    margin-bottom: 14px;
    color: #6b7280;
    font-size: 0.92rem;
}
.course-price-row strong {
    color: #111827;
    font-size: 1.1rem;
}
.course-instructor {
    color: #374151;
    font-weight: 600;
}
.course-card-footer {
    padding: 0 24px 24px;
}
.empty-state {
    padding: 48px 24px;
    text-align: center;
}
</style>
<?= $this->endSection() ?>
