<?= $this->extend('employers/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <!-- Start::page-header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Candidate Profile</h1>
            <div class="ms-auto">
                <a href="<?= base_url('employer/candidates') ?>" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line"></i> Back to Search
                </a>
            </div>
        </div>
    </div>
    <!-- End::page-header -->

    <div class="row">
        <!-- LEFT COLUMN: AVATAR & INFO -->
        <div class="col-xxl-4 col-xl-5">
            <div class="card custom-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <span class="avatar avatar-xxl avatar-rounded online shadow-sm">
                            <?php if (!empty($candidate->profile_picture)): ?>
                                <img src="<?= base_url($candidate->profile_picture) ?>" alt="img">
                            <?php else: ?>
                                <div class="avatar avatar-xxl bg-primary-transparent rounded-circle fs-30">
                                    <?= substr($candidate->full_name, 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1">
                        <?= esc($candidate->full_name) ?>
                        <?php if ($candidate->is_verified): ?>
                            <span class="text-primary fs-18" title="Verified Candidate">
                                <i class="ri-checkbox-circle-fill"></i>
                            </span>
                        <?php endif; ?>
                    </h5>
                    <p class="text-muted mb-3"><?= esc($candidate->job_title ?? 'Professional') ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge bg-primary-transparent text-primary"><?= esc($candidate->experience_years ?? 0) ?> Years Exp.</span>
                        <span class="badge bg-success-transparent text-success"><?= esc($candidate->employment_type ?? 'Full-time') ?></span>
                        <span class="badge bg-info-transparent text-info"><?= esc($candidate->availability ?? 'Immediate') ?></span>
                    </div>

                    <!-- CONTACT DETAILS SECTION (GATED) -->
                    <div class="card bg-light border-0">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-3 text-start">Contact Information</h6>
                            
                            <?php if ($isUnlocked): ?>
                                <!-- UNLOCKED STATE -->
                                <div class="text-start">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2"><i class="ri-mail-line text-primary"></i></div>
                                        <div><span class="fw-medium">Email:</span> <?= esc($candidate->email) ?></div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2"><i class="ri-phone-line text-primary"></i></div>
                                        <div><span class="fw-medium">Phone:</span> <?= esc($candidate->phone) ?></div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2"><i class="ri-map-pin-line text-primary"></i></div>
                                        <div><span class="fw-medium">Location:</span> <?= esc($candidate->state_name) ?>, Nigeria</div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="<?= base_url('employer/download-cv/' . $candidate->id) ?>" class="btn btn-primary w-100 mb-2">
                                        <i class="ri-download-line me-1"></i> Download Resume
                                    </a>
                                    <button class="btn btn-outline-primary w-100" onclick="startMessage(<?= $candidate->id ?>)">
                                        <i class="ri-message-2-line me-1"></i> Send Message
                                    </button>
                                </div>
                            <?php else: ?>
                                <!-- LOCKED STATE -->
                                <div class="text-start blur-content">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2"><i class="ri-mail-line text-muted"></i></div>
                                        <div class="blurred-text">example@email.com</div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2"><i class="ri-phone-line text-muted"></i></div>
                                        <div class="blurred-text">+234 800 000 0000</div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-3 bg-white rounded border border-primary border-dashed">
                                    <p class="small text-muted mb-3">Contact details are hidden. Use 1 credit to unlock this candidate's full profile and resume.</p>
                                    <button class="btn btn-primary w-100" onclick="unlockCandidate(<?= $candidate->id ?>)">
                                        <i class="ri-lock-unlock-line me-1"></i> Unlock Contact Details
                                    </button>
                                    <?php if($hasUnlimited): ?>
                                        <div class="mt-2 small text-success fw-bold">You have Unlimited Access! Click above to reveal.</div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: BIO & DETAILS -->
        <div class="col-xxl-8 col-xl-7">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Professional Biography</h6>
                    <p class="text-muted"><?= nl2br(esc($candidate->bio ?? 'No biography provided.')) ?></p>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Education Level</h6>
                            <p class="text-primary fw-medium"><?= esc($candidate->education_level ?? 'Not specified') ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Salary Expectation</h6>
                            <p class="text-success fw-bold">₦<?= number_format((float)($candidate->desired_salary ?? 0)) ?> (<?= esc($candidate->salary_type ?? 'Monthly') ?>)</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">Core Skills & Competencies</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php 
                        $skills = explode(',', $candidate->skills ?? '');
                        if (!empty($skills[0])):
                            foreach ($skills as $s): ?>
                                <span class="badge bg-light text-dark fs-12 p-2 border"><?= esc(trim($s)) ?></span>
                            <?php endforeach;
                        else: ?>
                            <span class="text-muted">No skills specified.</span>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">Preferred Industries</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($industries): foreach ($industries as $ind): ?>
                            <span class="badge bg-primary-transparent text-primary p-2"><?= esc($ind->name) ?></span>
                        <?php endforeach; else: ?>
                            <span class="text-muted">No specific industries preferred.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.blur-content {
    filter: blur(4px);
    user-select: none;
    pointer-events: none;
}
.blurred-text {
    background: #eee;
    color: transparent;
    border-radius: 4px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function unlockCandidate(id) {
    if (confirm('Unlock this candidate for 1 credit? This will give you permanent access to their contact details and resume.')) {
        // Show loading state
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line animation-spin"></i> Unlocking...';

        fetch('<?= base_url("employer/candidates/unlock") ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            },
            body: 'candidate_id=' + id
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                toastr.success(res.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                toastr.error(res.message);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                
                if (res.message.includes('credits') || res.message.includes('subscription')) {
                    // Redirect to pricing if needed
                    // setTimeout(() => window.location.href = '<?= base_url("employer/pricing") ?>', 2000);
                }
            }
        })
        .catch(err => {
            toastr.error('Error unlocking candidate');
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }

    function startMessage(candidateId) {
        const formData = new FormData();
        formData.append('seeker_id', candidateId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url("employer/messages/start") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (res.success && res.redirect) {
                window.location.href = res.redirect;
            } else {
                toastr.error(res.message || 'Failed to start conversation');
            }
        })
        .catch(err => {
            toastr.error('Error starting conversation');
            console.error(err);
        });
    }
}
</script>
<?= $this->endSection() ?>
