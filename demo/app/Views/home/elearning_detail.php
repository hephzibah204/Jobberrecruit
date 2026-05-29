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
                        <?php if ($enrollment): ?>
                            <div class="access-state success" style="background:#ecfdf5;color:#047857; border-radius:14px; padding:12px 14px; margin-bottom:16px; font-weight:600;"><i class="ti ti-circle-check-filled me-1"></i> You are enrolled in this training</div>
                        <?php else: ?>
                            <div class="access-state warning" style="background:#fff7ed;color:#c2410c; border-radius:14px; padding:12px 14px; margin-bottom:16px; font-weight:600;"><i class="ti ti-lock me-1"></i> Enroll to unlock learning access</div>
                        <?php endif; ?>

                        <?php $isEbook = ($course->item_type ?? 'course') === 'ebook'; ?>
                        <?php if ($enrollment): ?>
                            <?php if ($isEbook): ?>
                                <a href="#course-content" class="btn btn-success w-100 shadow-sm d-flex align-items-center justify-content-center py-2.5">
                                    <i class="ti ti-download me-2"></i> Download eBook File
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('candidate/my-courses/' . $course->id) ?>" class="btn btn-success w-100 shadow-sm d-flex align-items-center justify-content-center py-2.5" style="border-radius:12px;font-weight:700;">
                                    <i class="ti ti-device-laptop me-2"></i> Go to Candidate Classroom
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ((float) ($course->price ?? 0) > 0): ?>
                                <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary w-100 py-2.5 shadow-sm" style="border-radius:12px;font-weight:700;">
                                    <i class="ti ti-shopping-cart me-2"></i>Buy <?= $isEbook ? 'eBook' : 'Course' ?> (₦<?= number_format((float)$course->price, 2) ?>)
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary w-100 py-2.5 shadow-sm" style="border-radius:12px;font-weight:700;">
                                    <i class="ti ti-user-plus me-2"></i>Enroll for Free
                                </a>
                            <?php endif; ?>
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
                    <h2><?= ($course->item_type ?? 'course') === 'ebook' ? 'eBook Access' : 'Course Curriculum' ?></h2>

                    <?php if ($isEbook): ?>
                        <?php if ($enrollment): ?>
                            <div class="p-4 bg-light rounded text-center border mt-3" style="border-radius: 16px !important;">
                                <i class="ti ti-file-download text-success mb-2" style="font-size: 52px; display:block;"></i>
                                <h4 class="fw-bold text-dark">Your eBook is Unlocked</h4>
                                <p class="text-secondary small mb-3">Thank you for enrolling! Click below to download the high-fidelity PDF eBook resource directly to your local device.</p>
                                <a href="<?= base_url('training/content/' . $course->id) ?>" class="btn btn-success btn-lg px-4 py-2.5 fw-bold shadow-sm" style="border-radius: 12px;">
                                    <i class="ti ti-download me-1"></i> Download eBook (PDF)
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="p-4 bg-light rounded text-center border mt-3" style="border-radius: 16px !important;">
                                <i class="ti ti-lock text-muted mb-2" style="font-size: 52px; display:block;"></i>
                                <h4 class="fw-bold text-dark">eBook is Locked</h4>
                                <p class="text-secondary small mb-3">Enroll in this eBook training course to download your copy and access the workbook.</p>
                                <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary fw-bold" style="border-radius: 10px;">
                                    <i class="ti ti-key me-1"></i> Enroll & Unlock
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Classroom Navigation Call-To-Action Banner -->
                        <?php if ($enrollment): ?>
                            <div class="alert alert-primary border-0 p-4 mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 16px;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px; flex-shrink: 0;">
                                        <i class="ti ti-device-laptop fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-primary mb-1">You are Enrolled!</h5>
                                        <p class="text-secondary small mb-0">Your student workspace is ready. Enter the interactive classroom to play video lessons, download guides, and claim your completion certificate.</p>
                                    </div>
                                </div>
                                <a href="<?= base_url('candidate/my-courses/' . $course->id) ?>" class="btn btn-primary px-4 py-2.5 fw-bold shadow-sm" style="border-radius: 10px;">
                                    <i class="ti ti-player-play me-1"></i> Go to Classroom
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning border-0 p-4 mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 16px;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-warning text-white rounded-circle p-3 d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px; flex-shrink: 0;">
                                        <i class="ti ti-lock fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-warning-emphasis mb-1">Curriculum Preview</h5>
                                        <p class="text-secondary small mb-0">Enroll in this training course to immediately unlock all interactive video lectures, downloadable guides, worksheets, and certificate generation.</p>
                                    </div>
                                </div>
                                <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-warning px-4 py-2.5 fw-bold text-dark shadow-sm" style="border-radius: 10px;">
                                    <i class="ti ti-key me-1"></i> Enroll & Unlock
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Gorgeous, locked preview list of syllabus modules -->
                        <?php if (!empty($modules)): ?>
                            <div class="accordion mt-3" id="modulesAccordion">
                                <?php foreach($modules as $idx => $mod): ?>
                                    <?php 
                                        $sourceType = $mod->content_source ?? 'none';
                                        $icon = 'ti-align-left';
                                        $badgeText = 'Text Guide';
                                        if ($sourceType === 'youtube') {
                                            $icon = 'ti-video';
                                            $badgeText = 'Video Lesson';
                                        } elseif ($sourceType === 'upload') {
                                            $icon = 'ti-file-download';
                                            $badgeText = 'Downloadable Resource';
                                        }
                                    ?>
                                    <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                                        <h2 class="accordion-header" id="heading<?= $mod->id ?>">
                                            <button class="accordion-button <?= $idx !== 0 ? 'collapsed' : '' ?> fw-bold py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $mod->id ?>" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $mod->id ?>" style="font-size: 1.05rem;">
                                                <div class="d-flex align-items-center justify-content-between w-100 pe-3 flex-wrap gap-2">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="d-flex align-items-center justify-content-center bg-light text-muted rounded-circle fw-bold" style="width: 28px; height: 28px; font-size: 0.85rem; flex-shrink: 0;">
                                                            <?= $idx + 1 ?>
                                                        </span>
                                                        <span class="text-dark text-start"><?= esc($mod->title) ?></span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-pill small fw-semibold" style="font-size: 0.75rem;">
                                                            <i class="ti <?= $icon ?> me-1"></i><?= $badgeText ?>
                                                        </span>
                                                        <?php if ($enrollment): ?>
                                                            <span class="badge bg-success text-white px-2 py-1.5 rounded-pill small fw-bold" style="font-size: 0.75rem;">
                                                                <i class="ti ti-lock-open me-0.5"></i> Unlocked
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light text-muted border px-2 py-1.5 rounded-pill small fw-bold" style="font-size: 0.75rem;">
                                                                <i class="ti ti-lock me-0.5"></i> Locked
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse<?= $mod->id ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $mod->id ?>" data-bs-parent="#modulesAccordion">
                                            <div class="accordion-body p-4 bg-white border-top">
                                                <?php if ($mod->description): ?>
                                                    <div class="text-secondary lh-lg mb-3" style="font-size: 0.95rem;">
                                                        <?= nl2br(esc($mod->description)) ?>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Visual locked/unlocked preview window instead of standard video playback -->
                                                <?php if ($enrollment): ?>
                                                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border mt-3 flex-wrap gap-2" style="background-color: rgba(13, 110, 253, 0.05); border-color: rgba(13, 110, 253, 0.15) !important;">
                                                        <div class="d-flex align-items-center gap-2 text-primary">
                                                            <i class="ti ti-circle-check-filled fs-5"></i>
                                                            <span class="small fw-bold">This segment is fully ready inside your workspace.</span>
                                                        </div>
                                                        <a href="<?= base_url('candidate/my-courses/' . $course->id . '?module_id=' . $mod->id) ?>" class="btn btn-primary btn-sm fw-bold px-3" style="border-radius: 8px;">
                                                            <i class="ti ti-player-play me-1"></i> Start Lesson
                                                        </a>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light mt-3 flex-wrap gap-2">
                                                        <div class="d-flex align-items-center gap-2 text-muted">
                                                            <i class="ti ti-lock-filled fs-5"></i>
                                                            <span class="small">Enroll now to view lectures and download attached materials.</span>
                                                        </div>
                                                        <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-outline-secondary btn-sm fw-bold px-3" style="border-radius: 8px;">
                                                            Unlock Lesson
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Legacy Single Video/File Fallback preview -->
                            <?php if ($enrollment): ?>
                                <div class="p-4 bg-light rounded text-center border mt-3">
                                    <i class="ti ti-circle-check text-success fs-1 mb-2 d-block"></i>
                                    <h5 class="fw-bold">Interactive Learning Workspace is Active</h5>
                                    <p class="text-secondary small mb-3">You are successfully enrolled in this course. Access the classroom to view the full video and learning guides.</p>
                                    <a href="<?= base_url('candidate/my-courses/' . $course->id) ?>" class="btn btn-primary fw-bold" style="border-radius: 10px;">
                                        <i class="ti ti-device-laptop me-1"></i> Launch Interactive Classroom
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="p-4 bg-light rounded text-center border mt-3">
                                    <i class="ti ti-lock text-muted fs-1 mb-2 d-block"></i>
                                    <h5 class="fw-bold">Course Syllabus & Assets are Locked</h5>
                                    <p class="text-secondary small mb-3">Enroll in this training program to immediately unlock all interactive video material, guides, and certificate options.</p>
                                    <a href="<?= base_url('training/enroll/' . $course->id) ?>" class="btn btn-primary fw-bold" style="border-radius: 10px;">
                                        <i class="ti ti-key me-1"></i> Enroll & Unlock
                                    </a>
                                </div>
                            <?php endif; ?>
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
