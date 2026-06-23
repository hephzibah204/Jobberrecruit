<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <!-- Page Header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">
                <?= esc($candidate->full_name) ?>
            </h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Jobs</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/candidates') ?>">Candidates</a></li>
                <li class="breadcrumb-item active"><?= esc($candidate->full_name) ?></li>
            </ol>
        </div>
    </div>

    <div class="row">

        <!-- MAIN CONTENT -->
        <div class="col-xxl-9 col-xl-8">

            <!-- Profile Card -->
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-3">

                        <div class="d-flex gap-3 w-75">
                            <span class="avatar avatar-xxl">
                                <?php if ($candidate->profile_picture): ?>
                                    <img src="<?= base_url($candidate->profile_picture) ?>" alt="">
                                <?php else: ?>
                                    <?= strtoupper(substr($candidate->full_name, 0, 1)) ?>
                                <?php endif ?>
                            </span>

                            <div class="w-100">
                                <h4 class="fw-medium mb-1"><?= esc($candidate->full_name) ?></h4>

                                <div class="mb-1">
                                    <i class="bi bi-briefcase me-1"></i>
                                    <?= esc($candidate->job_title ?? '—') ?>
                                </div>

                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-info-transparent">
                                        <?= ucfirst($candidate->employment_type ?? '—') ?>
                                    </span>
                                    <span class="badge bg-danger-transparent">
                                        <?= esc($candidate->availability ?? '—') ?>
                                    </span>
                                </div>

                                <div class="row mt-3 gy-2">
                                    <div class="col-md-6">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?= esc($candidate->state_name ?? '—') ?>,
                                        <?= esc($candidate->country_name ?? '') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <i class="bi bi-briefcase me-1"></i>
                                        <?= (int)$candidate->experience_years ?> Years Experience
                                    </div>
                                    <div class="col-md-6">
                                        <i class="bi bi-coin me-1"></i>
                                        <?= esc($candidate->desired_salary ?? '—') ?>
                                        <?= esc($candidate->salary_type ?? '') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <i class="bi bi-mortarboard me-1"></i>
                                        <?= esc($candidate->education_level ?? '—') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btn-list">
                            <a href="<?= base_url('admin/candidates/download-cv/' . $candidate->id) ?>"
                                class="btn btn-primary">
                                <i class="bi bi-download me-1"></i> Download CV
                            </a>
                            <button class="btn btn-danger"
                                onclick="deleteCandidate(<?= $candidate->id ?>)"
                                title="Delete this candidate">
                                <i class="bi bi-trash me-1"></i> Delete Candidate
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">About</h6>
                    <p class="text-muted">
                        <?= nl2br(esc($candidate->bio ?? 'No bio provided.')) ?>
                    </p>
                </div>
            </div>

            <!-- Industries -->
            <?php if (!empty($industries)): ?>
                <div class="card custom-card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Industries</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($industries as $industry): ?>
                                <span class="badge bg-primary-transparent">
                                    <?= esc($industry->name) ?>
                                </span>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <!-- SIDEBAR -->
        <div class="col-xxl-3 col-xl-4">

            <!-- Personal Info -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Personal Information</div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Email:</strong> <?= esc($candidate->email) ?></li>
                        <li class="mb-2"><strong>Phone:</strong> <?= esc($candidate->phone ?? '—') ?></li>
                        <li class="mb-2"><strong>Gender:</strong> <?= esc($candidate->gender ?? '—') ?></li>
                        <li class="mb-2"><strong>DOB:</strong> <?= esc($candidate->dob ?? '—') ?></li>
                        <li class="mb-2"><strong>Nationality:</strong> <?= esc($candidate->nationality ?? 'Nigeria') ?></li>
                    </ul>
                </div>
            </div>

            <?php
            $skills = array_filter(array_map('trim', explode(',', $candidate->skills ?? '')));
            ?>

            <?php if (!empty($skills)): ?>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Skills</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($skills as $skill): ?>
                                <span class="badge bg-primary-transparent">
                                    <?= esc($skill) ?>
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
    function deleteCandidate(candidateId) {
        if (confirm('Are you sure you want to delete this candidate? This action cannot be undone. All related applications and alerts will be permanently deleted.')) {
            fetch('<?= base_url("admin/candidates/delete") ?>/' + candidateId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        toastr.success(res.message);
                        setTimeout(() => window.location.href = '<?= base_url("admin/candidates") ?>', 1000);
                    } else {
                        toastr.error(res.message);
                    }
                })
                .catch(err => {
                    toastr.error('Error deleting candidate');
                    console.error(err);
                });
        }
    }
</script>
<?= $this->endSection() ?>