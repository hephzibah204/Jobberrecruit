<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold text-gradient">Candidate Profile</h4>
                <h6 class="text-muted">View and manage your job seeker profile</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                    <i class="ti ti-chevron-up"></i>
                </a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="<?= base_url('candidate/dashboard') ?>" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Overview -->
        <div class="col-lg-4 col-md-12">
            <div class="glass-card mb-4 text-center">
                <div class="card-body">

                    <!-- Profile Image -->
                    <div class="profile-image mb-3">
                        <?php if (!empty($candidate->profile_picture)): ?>
                            <img src="<?= base_url($candidate->profile_picture) ?>"
                                alt="Profile Photo"
                                class="rounded-circle shadow-lg"
                                width="120" height="120" style="border: 3px solid var(--border);">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow-sm"
                                style="width: 120px; height: 120px; border: 3px solid var(--border);">
                                <i data-feather="user" class="text-muted" style="width: 60px; height: 60px;"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h4 class="mb-1 text-gradient fw-bold"><?= esc($candidate->full_name ?? 'Not Set') ?></h4>
                    <p class="text-muted mb-3"><?= esc($user->email) ?></p>

                    <!-- Resume Status -->
                    <div class="verification-badge mb-3">
                        <?php if (!empty($candidate->resume)): ?>
                            <span class="badge-verified bg-opacity-10" style="background: rgba(34, 197, 94, 0.1) !important; color: #22c55e !important; border-color: rgba(34, 197, 94, 0.2) !important;">
                                <i class="bi bi-file-earmark-check-fill me-1"></i>Resume Added
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>No Resume</span>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-primary rounded-pill">
                            <i class="bi bi-pencil-square me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Completion -->
            <div class="glass-card mt-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main mb-0">Profile Completion</h5>
                </div>
                <div class="card-body">
                    <?php
                    $fields = [
                        'full_name',
                        'dob',
                        'gender',
                        'phone',
                        'location',
                        'job_title',
                        'employment_type',
                        'skills',
                        'experience_years',
                        'education_level',
                    ];

                    $completed = 0;
                    foreach ($fields as $f) {
                        if (!empty($candidate->$f)) $completed++;
                    }

                    // Resume check
                    if (!empty($candidate->resume)) $completed++;

                    $totalFields = count($fields) + 1;
                    $completion = round(($completed / $totalFields) * 100);
                    ?>

                    <div class="progress mb-3" style="height: 10px; background: rgba(255,255,255,0.05);">
                        <div class="progress-bar bg-primary"
                            style="width: <?= $completion ?>%;"
                            role="progressbar">
                        </div>
                    </div>

                    <p class="text-center mb-0 text-main fw-bold"><?= $completion ?>% Complete</p>
                    <small class="text-muted text-center d-block">
                        <?= $completed ?> of <?= $totalFields ?> fields filled
                    </small>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-lg-8 col-md-12">

            <!-- Personal Info -->
            <div class="glass-card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main mb-0">Personal Information</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->full_name ?? 'Not Set') ?></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Gender</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->gender ?? 'Not Set') ?></p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date of Birth</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->dob ?? 'Not Set') ?></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="mb-0 text-main fw-medium">
                                <?php if (!empty($candidate->phone)): ?>
                                    <a href="tel:<?= esc($candidate->phone) ?>" class="text-decoration-none">
                                        <?= esc($candidate->phone) ?>
                                    </a>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </p>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Location</label>
                            <p class="mb-0 text-main fw-medium">
                                <?= esc($candidate->location ? $candidate->location . ' State' : 'Not Set') ?>
                            </p>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">User ID</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->user_id) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Career Information -->
            <div class="glass-card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main mb-0">Career Information</h5>
                </div>
 
                <div class="card-body">
                    <div class="row">
 
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Job Title</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->job_title ?? 'Not Set') ?></p>
                        </div>
 
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Employment Type</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->employment_type ?? 'Not Set') ?></p>
                        </div>
                    </div>
 
                    <div class="row">
 
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Experience (Years)</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->experience_years ?? 'Not Set') ?></p>
                        </div>
 
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Education Level</label>
                            <p class="mb-0 text-main fw-medium"><?= esc($candidate->education_level ?? 'Not Set') ?></p>
                        </div>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label text-muted">Skills</label>
                        <div class="rounded p-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border);">
                            <?= !empty($candidate->skills)
                                ? nl2br(esc($candidate->skills))
                                : '<p class="text-muted mb-0">No skills added</p>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="glass-card">
                <div class="card-header border-bottom">
                    <h5 class="fw-bold text-main mb-0">Documents</h5>
                </div>
 
                <div class="card-body">
                    <div class="row align-items-center">
 
                        <div class="col-md-8">
                            <label class="form-label text-muted">Resume / CV</label>
                            <p class="mb-0">
                                <?php if (!empty($candidate->resume)): ?>
                                    <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="text-main fw-bold text-decoration-none d-inline-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill me-2 fs-5 text-primary"></i> View Resume
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No resume uploaded</span>
                                <?php endif; ?>
                            </p>
                        </div>
 
                        <div class="col-md-4 text-end">
                            <?php if (!empty($candidate->resume)): ?>
                                <span class="badge-verified bg-opacity-10" style="background: rgba(34, 197, 94, 0.1) !important; color: #22c55e !important; border-color: rgba(34, 197, 94, 0.2) !important;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Uploaded
                                </span>
                            <?php else: ?>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                    <i class="bi bi-clock-history me-1"></i>Upload Required
                                </span>
                            <?php endif; ?>
                        </div>
 
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') feather.replace();
    });
</script>
<?= $this->endSection() ?>