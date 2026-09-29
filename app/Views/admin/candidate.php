<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <!-- Page Header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">
                    <?= esc($candidate->full_name) ?>
                </h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/candidates') ?>">Candidates</a></li>
                    <li class="breadcrumb-item active"><?= esc($candidate->full_name) ?></li>
                </ol>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('admin/candidates') ?>" class="btn btn-sm btn-light border">
                    <i class="ti ti-arrow-left me-1"></i> Back to Candidates
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- MAIN CONTENT -->
        <div class="col-xxl-8 col-xl-8">

            <!-- Profile Card -->
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <?php
                            $candName = !empty($candidate->full_name) ? $candidate->full_name : ($candidate->username ?? 'Candidate');
                            $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode(trim($candName)) . "&background=0A2F57&color=fff&size=128&bold=true";
                            $avatarUrl = resolve_image_url($candidate->profile_picture ?? '', 'candidate', $candName);
                            $isVerified = !empty($candidate->is_verified);
                            $isVisible = !empty($candidate->is_visible);
                            $hasResume = !empty($candidate->resume);
                        ?>
                        <div class="d-flex gap-3 align-items-center">
                            <span class="avatar avatar-xxl rounded-circle flex-shrink-0" style="background:#f1f5f9; border:2px solid #e2e8f0; width:72px; height:72px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                <img src="<?= esc($avatarUrl) ?>" alt="<?= esc($candName) ?>" style="width:100%; height:100%; object-fit:cover;" onerror="this.onerror=null; this.src='<?= esc($fallbackAvatar) ?>';">
                            </span>

                            <div>
                                <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                                    <?= esc($candName) ?>
                                    <span id="verificationBadge" class="badge <?= $isVerified ? 'bg-success' : 'bg-secondary' ?> fs-12">
                                        <i class="ti <?= $isVerified ? 'ti-shield-check' : 'ti-shield-off' ?> me-1"></i>
                                        <?= $isVerified ? 'Verified' : 'Unverified' ?>
                                    </span>
                                    <span class="badge <?= $isVisible ? 'bg-primary-transparent' : 'bg-danger-transparent' ?> fs-12">
                                        <?= $isVisible ? 'Visible in Search' : 'Hidden Profile' ?>
                                    </span>
                                </h4>

                                <div class="text-muted fs-14 mb-2">
                                    <i class="ti ti-briefcase me-1"></i>
                                    <?= esc($candidate->job_title ?? 'No title provided') ?>
                                </div>

                                <div class="d-flex gap-2 flex-wrap fs-12">
                                    <?php if (!empty($candidate->employment_type)): ?>
                                        <span class="badge bg-primary-transparent">
                                            <?= esc($candidate->employment_type) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($candidate->availability)): ?>
                                        <span class="badge bg-warning-transparent">
                                            <i class="ti ti-clock me-1"></i> <?= esc($candidate->availability) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($candidate->experience_years)): ?>
                                        <span class="badge bg-light text-dark border">
                                            <?= (int)$candidate->experience_years ?> Years Experience
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="btn-list d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleVerification(<?= $candidate->id ?>)">
                                <i class="ti ti-shield me-1"></i> Toggle Verification
                            </button>
                            <?php if ($hasResume): ?>
                                <a href="<?= base_url('admin/candidates/download-cv/' . $candidate->id) ?>" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="ti ti-download me-1"></i> Download CV
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-danger" onclick="deleteCandidate(<?= $candidate->id ?>)">
                                <i class="ti ti-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row g-3 fs-13">
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted d-block mb-1"><i class="ti ti-map-pin me-1 text-primary"></i> Location</span>
                            <span class="fw-semibold text-dark"><?= esc($candidate->state_name ?? $candidate->location ?? 'Nigeria') ?></span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted d-block mb-1"><i class="ti ti-cash me-1 text-success"></i> Desired Salary</span>
                            <span class="fw-semibold text-dark">
                                <?php if (!empty($candidate->desired_salary)): ?>
                                    ₦<?= is_numeric($candidate->desired_salary) ? number_format((float)$candidate->desired_salary) : esc($candidate->desired_salary) ?> <small class="text-muted">/ <?= esc($candidate->salary_type ?? 'mo') ?></small>
                                <?php else: ?>
                                    Negotiable
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted d-block mb-1"><i class="ti ti-school me-1 text-info"></i> Education</span>
                            <span class="fw-semibold text-dark"><?= esc($candidate->education_level ?? 'Not specified') ?></span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted d-block mb-1"><i class="ti ti-calendar me-1 text-purple"></i> Registered</span>
                            <span class="fw-semibold text-dark"><?= !empty($candidate->created_at) ? date('M d, Y', strtotime($candidate->created_at)) : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About / Bio -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Professional Summary / Bio</div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0 lh-base">
                        <?= !empty($candidate->bio) ? nl2br(esc($candidate->bio)) : 'No professional summary provided by candidate.' ?>
                    </p>
                </div>
            </div>

            <!-- Job Applications History -->
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Job Applications History</div>
                    <span class="badge bg-primary"><?= count($applications ?? []) ?> Total</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($applications)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Employer</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applications as $app): ?>
                                        <tr>
                                            <td class="fw-semibold text-dark"><?= esc($app['job_title'] ?? 'Job #' . $app['job_id']) ?></td>
                                            <td><?= esc($app['company_name'] ?? 'Company') ?></td>
                                            <td>
                                                <span class="badge bg-info-transparent"><?= ucfirst(esc($app['status'] ?? 'pending')) ?></span>
                                            </td>
                                            <td class="fs-12 text-muted"><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">
                            <i class="ti ti-folder-off fs-24 mb-1 d-block"></i>
                            No job applications submitted yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- E-Learning Courses Enrolled -->
            <?php if (!empty($courses)): ?>
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">E-Learning & Enrolled Courses</div>
                        <span class="badge bg-purple"><?= count($courses) ?> Total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Enrolled Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $crs): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= esc($crs['course_title'] ?? 'Course #' . $crs['course_id']) ?></td>
                                            <td><span class="badge bg-success-transparent"><?= ucfirst(esc($crs['status'] ?? 'active')) ?></span></td>
                                            <td><?= (int)($crs['progress'] ?? 0) ?>%</td>
                                            <td class="fs-12 text-muted"><?= date('M d, Y', strtotime($crs['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <!-- SIDEBAR -->
        <div class="col-xxl-4 col-xl-4">

            <!-- Contact & Personal Details -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Contact & Personal Details</div>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 fs-13">
                        <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted"><i class="ti ti-mail me-1"></i> Email:</span>
                            <span class="fw-medium text-dark"><?= esc($candidate->email ?? 'Not provided') ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted"><i class="ti ti-phone me-1"></i> Phone:</span>
                            <span class="fw-medium text-dark"><?= esc($candidate->phone ?? 'Not provided') ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted"><i class="ti ti-gender-bigender me-1"></i> Gender:</span>
                            <span class="fw-medium text-dark"><?= ucfirst(esc($candidate->gender ?? 'Not specified')) ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted"><i class="ti ti-calendar me-1"></i> Date of Birth:</span>
                            <span class="fw-medium text-dark"><?= esc($candidate->dob ?? 'Not provided') ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="ti ti-world me-1"></i> Nationality:</span>
                            <span class="fw-medium text-dark"><?= esc($candidate->nationality ?? 'Nigerian') ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Skills -->
            <?php
                $skills = array_filter(array_map('trim', explode(',', $candidate->skills ?? '')));
            ?>
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Skills (<?= count($skills) ?>)</div>
                </div>
                <div class="card-body">
                    <?php if (!empty($skills)): ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($skills as $skill): ?>
                                <span class="badge bg-light text-dark border fs-12 px-2 py-1">
                                    <?= esc($skill) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-muted fs-13">No skills added yet.</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Industries -->
            <?php if (!empty($industries)): ?>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Target Industries</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($industries as $industry): ?>
                                <span class="badge bg-primary-transparent fs-12">
                                    <?= esc($industry->name) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const csrfTokenName = '<?= csrf_token() ?>';
    const csrfTokenHash = '<?= csrf_hash() ?>';

    function toggleVerification(candidateId) {
        fetch('<?= base_url("admin/candidates/toggle-verification") ?>/' + candidateId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                [csrfTokenName]: csrfTokenHash
            }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof toastr !== 'undefined') toastr.success(res.message);
                const badge = document.getElementById('verificationBadge');
                if (badge) {
                    if (res.is_verified) {
                        badge.className = 'badge bg-success fs-12';
                        badge.innerHTML = '<i class="ti ti-shield-check me-1"></i> Verified';
                    } else {
                        badge.className = 'badge bg-secondary fs-12';
                        badge.innerHTML = '<i class="ti ti-shield-off me-1"></i> Unverified';
                    }
                }
            } else {
                if (typeof toastr !== 'undefined') toastr.error(res.message || 'Action failed');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof toastr !== 'undefined') toastr.error('Network error updating verification');
        });
    }

    function deleteCandidate(candidateId) {
        if (!confirm('Are you sure you want to delete this candidate? All applications, saved alerts, and user credentials will be permanently removed.')) {
            return;
        }

        fetch('<?= base_url("admin/candidates/delete") ?>/' + candidateId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                [csrfTokenName]: csrfTokenHash
            }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof toastr !== 'undefined') toastr.success(res.message || 'Candidate deleted successfully');
                setTimeout(() => window.location.href = '<?= base_url("admin/candidates") ?>', 800);
            } else {
                if (typeof toastr !== 'undefined') toastr.error(res.message || 'Delete failed');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof toastr !== 'undefined') toastr.error('Network error deleting candidate');
        });
    }
</script>
<?= $this->endSection() ?>