<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.active-curriculum {
    background-color: #eff6ff !important;
    border-left: 4px solid var(--brand);
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
@media (min-width: 992px) {
    .classroom-grid { grid-template-columns: 280px 1fr !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    
    <!-- Breadcrumb & Header -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-book"/></svg> Classroom: <?= esc($course->title) ?></h1>
            <p>Track your enrolled courses and progress.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('candidate/my-courses') ?>" class="btn btn-outline btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-l"/></svg> Back to List
            </a>
        </div>
    </div>

    <!-- Celebration Screen (If Course Completed) -->
    <?php if ($enrollment->status === 'completed'): ?>
        <div class="card" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: none; position: relative; overflow: hidden; padding: clamp(20px, 4vw, 36px); text-align: center;">
            <div class="celebration-particles" aria-hidden="true">🎉 ✨ 🎓 🏆 ✨ 🎉</div>
            <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--success); color: #fff; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 4px 10px rgba(22,163,74,0.3);">
                <svg aria-hidden="true" style="width:36px;height:36px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-award"/></svg>
            </div>
            <h3 style="font-family: 'Sora', sans-serif; font-weight: 800; color: #065f46; font-size: 1.4rem; margin-bottom: 8px;">Congratulations! You Completed This Course</h3>
            <p style="color: #065f46; font-size: 0.9rem; max-width: 500px; margin: 0 auto 20px; opacity: 0.85;">You have successfully mastered all topics and modules in this training. Your certificate is ready!</p>
            
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <?php if (!empty($certificate)): ?>
                    <?php 
                        $certId = is_object($certificate) ? $certificate->id : ($certificate['id'] ?? null); 
                        $certCode = is_object($certificate) ? $certificate->certificate_code : ($certificate['certificate_code'] ?? '');
                    ?>
                    <?php if ($certId): ?>
                        <a href="<?= base_url('training/certificate/download/' . $certId) ?>" class="btn btn-primary" style="background: #059669; border-color: #059669;">
                            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg> Download PDF Certificate
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="<?= base_url('candidate/my-courses') ?>" class="btn btn-outline" style="color: #065f46; border-color: #a7f3d0; background: transparent;">
                    Browse Courses
                </a>
            </div>
            
            <?php if (!empty($certCode)): ?>
                <div style="margin-top: 15px; font-size: 0.78rem; color: #065f46; opacity: 0.8;">
                    <strong>Verification Code:</strong> <?= esc($certCode) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="classroom-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
        
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Sidebar Curriculum -->
            <section class="card" aria-label="Course Curriculum">
                <div class="card-head">
                    <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bookmark"/></svg> Course Curriculum</span>
                </div>
                <div class="card-body p-0">
                    <div style="display: flex; flex-direction: column;">
                        <?php if (empty($modules)): ?>
                            <div style="padding: 24px; text-align: center; color: var(--muted); font-size: 0.86rem;">
                                No active modules have been added to this course curriculum yet.
                            </div>
                        <?php else: ?>
                            <?php foreach ($modules as $idx => $mod): ?>
                                <?php 
                                    $isActive = $activeModule && (int)$activeModule->id === (int)$mod->id;
                                ?>
                                <a href="<?= base_url('candidate/my-courses/' . $course->id . '?module_id=' . $mod->id) ?>" 
                                   class="curriculum-item <?= $isActive ? 'active-curriculum' : '' ?>"
                                   style="display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit;">
                                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; background: <?= $isActive ? 'var(--brand)' : 'var(--border)' ?>; color: <?= $isActive ? '#fff' : 'var(--muted)' ?>; flex-shrink: 0;">
                                            <?= $idx + 1 ?>
                                        </div>
                                        <div style="min-width: 0;">
                                            <h6 style="font-size: 0.84rem; font-weight: 700; margin: 0; color: <?= $isActive ? 'var(--brand)' : 'var(--brand-deep)' ?>; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($mod->title) ?></h6>
                                            <span style="font-size: 0.7rem; color: var(--muted);"><?= ucfirst(esc($mod->content_source ?? 'none')) ?></span>
                                        </div>
                                    </div>
                                    <?php if ($isActive): ?>
                                        <span class="pill pill--reviewed" style="font-size: 0.6rem; padding: 2px 6px;">Learning</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Learning Telemetry / Completion Widget -->
            <section class="card" aria-label="Learning Progress" style="padding: 16px;">
                <h3 style="font-family: 'Sora', sans-serif; font-size: 0.94rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check-c"/></svg> Your Progress
                </h3>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; color: var(--muted); margin-bottom: 6px;">
                        <span>Course Status</span>
                        <strong style="color: var(--brand-deep);"><?= ucfirst(esc($enrollment->status)) ?></strong>
                    </div>
                    <div style="width: 100%; height: 6px; background: var(--border); border-radius: 10px; overflow: hidden;">
                        <div style="width: <?= $enrollment->status === 'completed' ? '100' : '50' ?>%; height: 100%; background: var(--success);" class="prog-fill"></div>
                    </div>
                </div>

                <?php if ($enrollment->status !== 'completed'): ?>
                    <button type="button" id="complete-course-classroom-btn" class="btn btn-primary btn-sm btn-block">
                        Complete Course &amp; Get Certified
                    </button>
                <?php else: ?>
                    <div style="text-align: center; color: var(--success); font-size: 0.86rem; font-weight: 600;">
                        <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;display:inline-block;vertical-align:middle;margin-right:4px;"><use href="#i-check"/></svg> Course 100% Completed
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <!-- Main Workspace Pane -->
        <section class="card" aria-label="Classroom Workspace" style="min-width: 0;">
            <?php if ($activeModule): ?>
                <!-- Media / Learning Player Panel -->
                <div style="background: #0f172a; position: relative;">
                    <?php if ($activeModule->content_source === 'youtube' && !empty($youtubeEmbedUrl)): ?>
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
                            <iframe src="<?= esc($youtubeEmbedUrl) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"></iframe>
                        </div>
                    <?php elseif ($activeModule->content_source === 'upload' && !empty($activeModule->content_file)): ?>
                        <div style="padding: 40px 20px; text-align: center; color: #fff; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); display: flex; flex-direction: column; align-items: center; gap: 15px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.1); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                                <svg aria-hidden="true" style="width:30px;height:30px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg>
                            </div>
                            <h4 style="font-family: 'Sora', sans-serif; font-size: 1.15rem; font-weight: 700; margin: 0;">Course File Resource Attached</h4>
                            <p style="font-size: 0.8rem; color: #94a3b8; max-width: 400px; margin: 0;">This learning module includes a secure file resource. Download the document below to read and complete the training segment.</p>
                            <a href="<?= base_url('training/content/' . $course->id . '?module_id=' . $activeModule->id) ?>" class="btn btn-accent btn-sm">
                                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg> Download Document
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px 20px; text-align: center; color: #fff; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); display: flex; flex-direction: column; align-items: center; gap: 10px;">
                            <svg aria-hidden="true" style="width:32px;height:32px;color:#64748b;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg>
                            <h4 style="font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; margin: 0;">Text-Based Learning Module</h4>
                            <p style="font-size: 0.8rem; color: #94a3b8; margin: 0;">Please read the syllabus instructions and description below to complete this module.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Module Overview & Meta -->
                <div class="card-body">
                    <div style="border-bottom: 1px solid var(--border); padding-bottom: 15px; margin-bottom: 15px;">
                        <span class="pill pill--reviewed" style="font-size: 0.65rem; padding: 3px 8px; font-weight: 700; margin-bottom: 8px; display: inline-block;">
                            MODULE SOURCE: <?= strtoupper(esc($activeModule->content_source)) ?>
                        </span>
                        <h3 style="font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.2rem; color: var(--brand-deep); margin: 0;"><?= esc($activeModule->title) ?></h3>
                    </div>

                    <h4 style="font-family: 'Sora', sans-serif; font-size: 0.94rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 10px;">Module Description</h4>
                    <div style="font-size: 0.86rem; color: var(--text); line-height: 1.7;">
                        <?= !empty($activeModule->description) ? nl2br($activeModule->description) : '<p class="text-muted italic">No written description has been provided for this learning module yet.</p>' ?>
                    </div>
                </div>
            <?php else: ?>
                <div style="padding: 48px; text-align: center; color: var(--muted);">
                    <svg aria-hidden="true" style="width:48px;height:48px;color:var(--border);margin-bottom:12px;fill:none;stroke:currentColor;stroke-width:2;display:block;margin-left:auto;margin-right:auto;"><use href="#i-x"/></svg>
                    <h4 style="font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 6px;">No Active Curriculum Modules</h4>
                    <p style="font-size: 0.8rem; max-width: 320px; margin: 0 auto;">Curriculum items are currently being set up. Please check back later for full access.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // AJAX Course Completion Handler
    $('#complete-course-classroom-btn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).text('Completing...');

        $.ajax({
            url: '<?= base_url("training/complete/" . $course->id) ?>',
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Course completed successfully!');
                    if (typeof celebrateSuccess === 'function') {
                        celebrateSuccess();
                    }
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Verification failed. Please try again.');
                    btn.prop('disabled', false).text('Complete Course & Get Certified');
                }
            },
            error: function(xhr) {
                toastr.error('Failed to register course completion. Please refresh the page.');
                btn.prop('disabled', false).text('Complete Course & Get Certified');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

