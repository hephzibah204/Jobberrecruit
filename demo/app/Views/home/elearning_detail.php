<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "<?= esc($course->title) ?>",
  "description": "<?= esc(mb_substr(strip_tags((string) $course->description), 0, 200)) ?>...",
  "provider": {
    "@type": "Organization",
    "name": "JobberRecruit",
    "sameAs": "<?= base_url() ?>"
  },
  "hasCourseInstance": {
    "@type": "CourseInstance",
    "courseMode": "online",
    "duration": "<?= esc($course->duration ?: 'Self-paced') ?>",
    "instructor": {
      "@type": "Person",
      "name": "<?= esc($course->instructor ?: 'JobberRecruit') ?>"
    }
  },
  "offers": {
    "@type": "Offer",
    "price": "<?= (float) ($course->price ?? 0) ?>",
    "priceCurrency": "NGN",
    "availability": "https://schema.org/InStock",
    "category": "<?= (float) ($course->price ?? 0) > 0 ? 'Paid' : 'Free' ?>"
  }
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="course-detail-hero">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <div class="detail-badges">
                    <?php if (($course->item_type ?? 'course') === 'ebook'): ?>
                        <span style="background:#198754;color:#fff;">eBook (PDF)</span>
                    <?php else: ?>
                        <span><?= ucfirst((string) ($course->level ?? 'beginner')) ?></span>
                        <span><?= ucfirst((string) ($course->content_source ?? 'none')) ?></span>
                    <?php endif; ?>
                    <span><?= (float) ($course->price ?? 0) > 0 ? 'Paid' : 'Free' ?></span>
                </div>
                <h1><?= esc($course->title) ?></h1>
                <p class="detail-lead"><?= esc(mb_substr(strip_tags((string) $course->description), 0, 220)) ?>...</p>
                <div class="detail-meta">
                    <div><strong>Instructor</strong><span><?= esc($course->instructor ?: 'JobberRecruit') ?></span></div>
                    <div><strong>Duration</strong><span><?= esc($course->duration ?: 'Self-paced') ?></span></div>
                    <div><strong>Price</strong><span><?= (float) ($course->price ?? 0) > 0 ? 'N' . number_format((float) $course->price, 2) : 'Free' ?></span></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="detail-card">
                    <img src="<?= $course->thumbnail ? base_url($course->thumbnail) : 'https://placehold.co/900x560/0d6efd/ffffff?text=' . urlencode($course->title) ?>" alt="<?= esc($course->title) ?>">
                    <div class="detail-card-body">
                        <?php if ($canAccessContent): ?>
                            <div class="access-state success">You can access this course content now.</div>
                        <?php else: ?>
                            <div class="access-state warning">Enroll to unlock the full content.</div>
                        <?php endif; ?>

                        <?php $isEbook = ($course->item_type ?? 'course') === 'ebook'; ?>
                        <?php if ((float) ($course->price ?? 0) > 0): ?>
                            <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary w-100">Buy <?= $isEbook ? 'eBook' : 'Course' ?></a>
                        <?php else: ?>
                            <a href="<?= $canAccessContent ? '#course-content' : base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary w-100">
                                <?= $canAccessContent ? ($isEbook ? 'Download eBook' : 'Go to Content') : ($isEbook ? 'Get eBook for Free' : 'Enroll for Free') ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($enrollment): ?>
                            <p class="enrollment-note">Enrollment status: <?= esc(ucfirst((string) ($enrollment->status ?? 'enrolled'))) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-card">
                    <h2>About this course</h2>
                    <div class="course-description"><?= $course->description ?></div>
                </div>

                <div class="content-card mt-4" id="course-content">
                    <h2><?= ($course->item_type ?? 'course') === 'ebook' ? 'eBook Download' : 'Course content' ?></h2>

                    <?php if (! $canAccessContent): ?>
                        <p class="text-muted mb-0">This content unlocks after <?= ($course->item_type ?? 'course') === 'ebook' ? 'purchase/enrollment' : 'enrollment' ?>.</p>
                    <?php elseif (!empty($modules)): ?>
                        <div class="accordion" id="modulesAccordion">
                            <?php foreach($modules as $idx => $mod): ?>
                                <div class="accordion-item mb-2 border rounded">
                                    <h2 class="accordion-header" id="heading<?= $mod->id ?>">
                                        <button class="accordion-button <?= $idx !== 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $mod->id ?>" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $mod->id ?>">
                                            Module <?= $idx + 1 ?>: <?= esc($mod->title) ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $mod->id ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $mod->id ?>" data-bs-parent="#modulesAccordion">
                                        <div class="accordion-body">
                                            <?php if ($mod->description): ?>
                                                <p class="mb-3 text-muted"><?= esc($mod->description) ?></p>
                                            <?php endif; ?>

                                            <?php if ($mod->content_source === 'youtube' && !empty($mod->youtube_url)): ?>
                                                <?php 
                                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $mod->youtube_url, $match);
                                                    $ytUrl = isset($match[1]) ? "https://www.youtube.com/embed/" . $match[1] : $mod->youtube_url;
                                                ?>
                                                <div class="ratio ratio-16x9">
                                                    <iframe src="<?= esc($ytUrl) ?>" allowfullscreen></iframe>
                                                </div>
                                            <?php elseif ($mod->content_source === 'upload' && !empty($mod->content_file)): ?>
                                                <div class="p-3 bg-light rounded text-center">
                                                    <i class="ti ti-file-download fs-1 text-primary mb-2 d-block"></i>
                                                    <a href="<?= base_url('training/content/' . $course->id . '?module_id=' . $mod->id) ?>" class="btn btn-primary">Download Attached File</a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- Legacy Single Video/File Fallback -->
                        <?php if (($course->content_source ?? 'none') === 'youtube' && !empty($youtubeEmbedUrl)): ?>
                            <div class="ratio ratio-16x9">
                                <iframe src="<?= esc($youtubeEmbedUrl) ?>" title="<?= esc($course->title) ?>" allowfullscreen></iframe>
                            </div>
                        <?php elseif (($course->content_source ?? 'none') === 'upload' && ! empty($course->content_file)): ?>
                            <p class="text-muted">Download the attached resource below.</p>
                            <a href="<?= base_url('training/content/' . $course->id) ?>" class="btn btn-outline-primary">Download <?= ($course->item_type ?? 'course') === 'ebook' ? 'eBook' : 'Course Resource' ?></a>
                        <?php else: ?>
                            <p class="text-muted mb-0">No course content has been attached yet.</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-card">
                    <h3>What makes this useful?</h3>
                    <ul class="value-list">
                        <li>Admin-managed pricing and visibility</li>
                        <li>Supports both free and paid enrollment</li>
                        <li>Delivers content via YouTube or secure file download</li>
                        <li>Works with the JobberRecruit candidate flow</li>
                    </ul>
                </div>

                <?php if (! empty($relatedCourses)): ?>
                    <div class="content-card mt-4">
                        <h3>Related courses</h3>
                        <div class="related-list">
                            <?php foreach ($relatedCourses as $related): ?>
                                <a href="<?= base_url('training/course/' . $related->id) ?>" class="related-item">
                                    <strong><?= esc($related->title) ?></strong>
                                    <span><?= (float) ($related->price ?? 0) > 0 ? 'N' . number_format((float) $related->price, 2) : 'Free' ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.course-detail-hero {
    padding: 110px 0 70px;
    background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
}
.detail-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}
.detail-badges span {
    background: #eff6ff;
    color: #0d6efd;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
}
.course-detail-hero h1 {
    font-size: clamp(2rem, 3vw, 3rem);
    margin-bottom: 16px;
}
.detail-lead {
    color: #4b5563;
    font-size: 1.05rem;
    margin-bottom: 22px;
}
.detail-meta {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}
.detail-meta div {
    background: #fff;
    border-radius: 18px;
    padding: 16px;
    border: 1px solid #e5e7eb;
}
.detail-meta strong,
.detail-meta span {
    display: block;
}
.detail-meta strong {
    color: #6b7280;
    margin-bottom: 6px;
    font-size: 0.85rem;
}
.detail-meta span {
    color: #111827;
    font-weight: 700;
}
.detail-card,
.content-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 22px;
    box-shadow: 0 14px 35px rgba(17, 24, 39, 0.06);
}
.detail-card {
    overflow: hidden;
}
.detail-card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
}
.detail-card-body,
.content-card {
    padding: 24px;
}
.access-state {
    border-radius: 14px;
    padding: 12px 14px;
    margin-bottom: 16px;
    font-weight: 600;
}
.access-state.success {
    background: #ecfdf3;
    color: #047857;
}
.access-state.warning {
    background: #fff7ed;
    color: #c2410c;
}
.enrollment-note {
    color: #6b7280;
    font-size: 0.95rem;
    margin-top: 12px;
    margin-bottom: 0;
}
.course-description {
    color: #374151;
    line-height: 1.8;
}
.value-list {
    padding-left: 18px;
    margin: 0;
    color: #4b5563;
}
.value-list li + li {
    margin-top: 10px;
}
.related-list {
    display: grid;
    gap: 12px;
}
.related-item {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    color: inherit;
    text-decoration: none;
}
.related-item strong {
    color: #111827;
}
.related-item span {
    color: #0d6efd;
    font-weight: 700;
}
@media (max-width: 767px) {
    .detail-meta {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>
