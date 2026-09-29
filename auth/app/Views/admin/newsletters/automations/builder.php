<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><?= esc($title) ?></h1>
            <div class="ms-md-1 ms-0">
                <a href="<?= base_url('admin/newsletters/automations') ?>" class="btn btn-light btn-wave">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Automation Settings</div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/newsletters/automations/save') ?>" method="POST">
                            <?= csrf_field() ?>
                            <?php if ($automation): ?>
                                <input type="hidden" name="id" value="<?= $automation->id ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="<?= esc($automation->name ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trigger Event</label>
                                <select name="trigger_event" class="form-select" required>
                                    <option value="user_registered" <?= ($automation->trigger_event ?? '') === 'user_registered' ? 'selected' : '' ?>>User Registered</option>
                                    <option value="course_completed" <?= ($automation->trigger_event ?? '') === 'course_completed' ? 'selected' : '' ?>>Course Completed</option>
                                    <option value="subscription_active" <?= ($automation->trigger_event ?? '') === 'subscription_active' ? 'selected' : '' ?>>Subscription Activated</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft" <?= ($automation->status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="active" <?= ($automation->status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Campaign Steps</div>
                    </div>
                    <div class="card-body">
                        <?php if (!$automation): ?>
                            <div class="alert alert-info">Save the automation settings first to add steps.</div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($steps as $index => $step): ?>
                                    <div class="card border border-primary mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="fw-semibold mb-1">Step <?= $index + 1 ?></h6>
                                                <span class="badge bg-info-transparent">Wait: <?= $step->delay_minutes ?> mins</span>
                                            </div>
                                            <p class="text-muted fs-13 mb-0">Template ID: <?= esc($step->template_id) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center mt-4">
                                    <button class="btn btn-outline-primary" onclick="alert('Step builder implementation pending...')">
                                        <i class="ti ti-plus me-1"></i> Add Step
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
