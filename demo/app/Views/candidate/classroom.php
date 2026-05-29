<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="page-header mb-4">
        <div class="page-title">
            <h4 class="fw-bold"><i class="ti ti-book-open me-2"></i>Classroom: <?= esc($course->title) ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('candidate/my-courses') ?>">My Courses</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($course->title) ?></li>
                </ol>
            </nav>
        </div>
        <div class="page-btn">
            <a href="<?= base_url('candidate/my-courses') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Celebration Screen (If Course Completed) -->
    <?php if ($enrollment->status === 'completed'): ?>
        <div class="alert alert-success border-0 shadow-sm p-4 mb-4 rounded-3 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
            <div class="celebration-particles">🎉 ✨ 🎓 🏆 ✨ 🎉</div>
            <div class="mx-auto bg-success text-white rounded-circle d-flex align-items-center justify-content-center mb-3 shadow" style="width: 70px; height: 70px;">
                <i class="ti ti-award" style="font-size: 38px;"></i>
            </div>
            <h3 class="fw-bold text-success-emphasis mb-2">Congratulations! You Completed This Course</h3>
            <p class="text-success-emphasis opacity-75 mb-4">You have successfully mastered all topics and modules in this training. Your certificate is ready!</p>
            
            <div class="d-flex justify-content-center gap-3">
                <?php if (!empty($certificate)): ?>
                    <?php 
                        $certId = is_object($certificate) ? $certificate->id : ($certificate['id'] ?? null); 
                        $certCode = is_object($certificate) ? $certificate->certificate_code : ($certificate['certificate_code'] ?? '');
                    ?>
                    <?php if ($certId): ?>
                        <a href="<?= base_url('training/certificate/download/' . $certId) ?>" class="btn btn-success btn-lg px-4 py-3 fw-bold shadow-sm d-flex align-items-center transition-all" style="border-radius: 12px;">
                            <i class="ti ti-download me-2 fs-4"></i> Download PDF Certificate
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="<?= base_url('candidate/my-courses') ?>" class="btn btn-outline-success btn-lg px-4 py-3 fw-semibold" style="border-radius: 12px;">
                    Browse Enrolled Courses
                </a>
            </div>
            
            <?php if (!empty($certCode)): ?>
                <div class="mt-3 text-success-emphasis small opacity-75">
                    <strong>Verification Code:</strong> <?= esc($certCode) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar Curriculum -->
        <div class="col-lg-4 col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="ti ti-list me-2"></i>Course Curriculum</h5>
                </div>
                <div class="card-body p-0">
                    <div class="classroom-curriculum-list">
                        <?php if (empty($modules)): ?>
                            <div class="p-4 text-center text-muted">
                                <i class="ti ti-file-info fs-1 mb-2 d-block text-muted"></i>
                                No active modules have been added to this course curriculum yet.
                            </div>
                        <?php else: ?>
                            <?php foreach ($modules as $idx => $mod): ?>
                                <?php 
                                    $isActive = $activeModule && (int)$activeModule->id === (int)$mod->id;
                                ?>
                                <a href="<?= base_url('candidate/my-courses/' . $course->id . '?module_id=' . $mod->id) ?>" 
                                   class="curriculum-item d-flex align-items-center justify-content-between p-3 border-bottom text-decoration-none transition-all <?= $isActive ? 'active-curriculum' : '' ?>"
                                   style="color: inherit; transition: background 0.2s;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="module-index d-flex align-items-center justify-content-center rounded-circle fw-bold <?= $isActive ? 'bg-primary text-white' : 'bg-light text-muted' ?>" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                            <?= $idx + 1 ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 <?= $isActive ? 'text-primary' : 'text-dark' ?>" style="font-size: 0.9rem;"><?= esc($mod->title) ?></h6>
                                            <span class="text-muted small">
                                                <i class="ti ti-info-circle me-1"></i><?= ucfirst(esc($mod->content_source ?? 'none')) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="curriculum-status">
                                        <?php if ($isActive): ?>
                                            <span class="badge bg-primary-light text-primary py-1 px-2 rounded-3 small">Learning</span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Learning Telemetry / Completion Widget -->
            <div class="card border-0 shadow-sm mt-4 p-4" style="border-radius: 16px; background-color: #fcfdff;">
                <h6 class="fw-bold text-dark mb-3"><i class="ti ti-shield me-2 text-primary"></i>Your Progress</h6>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between text-muted mb-2 small">
                        <span>Course Status</span>
                        <span class="fw-bold text-dark"><?= ucfirst(esc($enrollment->status)) ?></span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 99px;">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: <?= $enrollment->status === 'completed' ? '100' : '50' ?>%"></div>
                    </div>
                </div>

                <?php if ($enrollment->status !== 'completed'): ?>
                    <button type="button" id="complete-course-classroom-btn" class="btn btn-success w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 10px;">
                        <i class="ti ti-award me-2 fs-5"></i> Complete Course & Get Certified
                    </button>
                <?php else: ?>
                    <div class="text-success text-center py-2 fw-semibold">
                        <i class="ti ti-circle-check-filled me-1 fs-5"></i> Course 100% Completed
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Workspace Pane -->
        <div class="col-lg-8 col-md-7">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <?php if ($activeModule): ?>
                    <!-- Media / Learning Player Panel -->
                    <div class="classroom-player-wrapper bg-dark position-relative" style="min-height: 250px;">
                        <?php if ($activeModule->content_source === 'youtube' && !empty($youtubeEmbedUrl)): ?>
                            <div class="ratio ratio-16x9">
                                <iframe src="<?= esc($youtubeEmbedUrl) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php elseif ($activeModule->content_source === 'upload' && !empty($activeModule->content_file)): ?>
                            <div class="d-flex flex-column align-items-center justify-content-center text-center p-5 text-white bg-dark gap-3" style="min-height: 400px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center p-4 shadow-lg mb-2">
                                    <i class="ti ti-file-download" style="font-size: 48px;"></i>
                                </div>
                                <h4 class="fw-bold mb-2">Course File Resource Attached</h4>
                                <p class="text-muted small mx-auto" style="max-width: 450px;">This learning module includes a secure file resource. Download the document below to read and complete the training segment.</p>
                                <a href="<?= base_url('training/content/' . $course->id . '?module_id=' . $activeModule->id) ?>" class="btn btn-primary btn-lg px-4 py-3 fw-bold d-inline-flex align-items-center shadow-lg transition-all" style="border-radius: 12px;">
                                    <i class="ti ti-download me-2"></i> Download Document Resource
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column align-items-center justify-content-center text-center p-5 text-white bg-dark gap-3" style="min-height: 400px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                <i class="ti ti-align-left text-muted mb-2" style="font-size: 52px;"></i>
                                <h4 class="fw-bold mb-1">Text-Based Learning Module</h4>
                                <p class="text-muted small">Please read the syllabus instructions and description below to complete this module.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Module Overview & Meta -->
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                            <div>
                                <span class="badge bg-light text-dark py-1.5 px-3 rounded-pill small mb-2 d-inline-block fw-bold border">
                                    MODULE SOURCE: <?= strtoupper(esc($activeModule->content_source)) ?>
                                </span>
                                <h3 class="fw-bold text-dark mb-0"><?= esc($activeModule->title) ?></h3>
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark mb-3">Module Description</h5>
                        <div class="module-content-text text-secondary lh-lg" style="font-size: 0.98rem;">
                            <?= !empty($activeModule->description) ? nl2br($activeModule->description) : '<p class="text-muted italic">No written description has been provided for this learning module yet.</p>' ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body p-5 text-center text-muted">
                        <i class="ti ti-device-laptop-off fs-1 text-muted mb-3 d-block"></i>
                        <h4 class="fw-bold text-dark mb-2">No Active Curriculum Modules</h4>
                        <p class="text-muted">Curriculum items are currently being set up. Please check back later for full access.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.curriculum-item {
    transition: all 0.2s ease-in-out;
}
.curriculum-item:hover {
    background-color: #f8fafc;
}
.active-curriculum {
    background-color: #eff6ff !important;
    border-left: 4px solid #3b82f6;
}
.active-curriculum .module-index {
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.25);
}
.bg-primary-light {
    background-color: rgba(59, 130, 246, 0.1) !important;
}
.celebration-particles {
    position: absolute;
    top: 5px;
    width: 100%;
    left: 0;
    font-size: 1.25rem;
    opacity: 0.25;
    letter-spacing: 12px;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // AJAX Course Completion Handler
    $('#complete-course-classroom-btn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="ti ti-loader-2 spinner-border spinner-border-sm me-2"></i>Completing...');

        $.ajax({
            url: '<?= base_url("training/complete/" . $course->id) ?>',
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Course completed successfully!');
                    // Reload classroom to display confetti/congratulations certificate and issue details
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Verification failed. Please try again.');
                    btn.prop('disabled', false).html('<i class="ti ti-award me-2 fs-5"></i> Complete Course & Get Certified');
                }
            },
            error: function(xhr) {
                toastr.error('Failed to register course completion. Please refresh the page.');
                btn.prop('disabled', false).html('<i class="ti ti-award me-2 fs-5"></i> Complete Course & Get Certified');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
