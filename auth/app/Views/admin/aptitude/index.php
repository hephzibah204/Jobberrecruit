<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Aptitude Tests</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Aptitude Tests</li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/aptitude/create') ?>" class="btn btn-primary btn-wave d-inline-flex align-items-center">
                    <i class="ri-add-line me-1"></i> Create Test
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Aptitude Test Templates</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Test Title</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Questions</th>
                            <th scope="col">Pass Threshold</th>
                            <th scope="col">Difficulty</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tests)): ?>
                            <?php foreach ($tests as $test): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="fw-semibold d-block"><?= esc($test['title'] ?? $test->title ?? 'N/A') ?></span>
                                                <span class="text-muted fs-11"><?= esc($test['description'] ?? $test->description ?? 'No description') ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($test['duration_mins'] ?? $test->duration_mins ?? '0') ?> mins</td>
                                    <td><?= esc($test['num_questions'] ?? $test->num_questions ?? '0') ?> Qs</td>
                                    <td><?= esc($test['pass_threshold'] ?? $test->pass_threshold ?? '50') ?>%</td>
                                    <td>
                                        <?php 
                                        $difficulty = strtolower($test['difficulty'] ?? $test->difficulty ?? 'medium');
                                        $badgeClass = 'bg-secondary';
                                        if ($difficulty === 'easy') $badgeClass = 'bg-success';
                                        elseif ($difficulty === 'medium') $badgeClass = 'bg-warning';
                                        elseif ($difficulty === 'hard') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($difficulty) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="<?= base_url('admin/aptitude/import/' . ($test['id'] ?? $test->id ?? $test['id'])) ?>" class="btn btn-sm btn-info-light btn-wave">
                                                <i class="ri-upload-line me-1"></i> Import CSV
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No tests configured yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
