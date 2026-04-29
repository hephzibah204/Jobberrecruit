<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Affiliate & Referral Settings</h4>
            <h6>Configure reward amounts and program status</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-8">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="<?= site_url('admin/affiliate/settings') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <?php foreach ($settings as $setting): ?>
                            <div class="mb-4">
                                <label class="form-label fw-semibold"><?= esc($setting->description) ?></label>
                                <div class="input-group">
                                    <?php if ($setting->key === 'referral_program_active'): ?>
                                        <select name="settings[<?= $setting->key ?>]" class="form-control">
                                            <option value="1" <?= $setting->value == '1' ? 'selected' : '' ?>>Enabled</option>
                                            <option value="0" <?= $setting->value == '0' ? 'selected' : '' ?>>Disabled</option>
                                        </select>
                                    <?php else: ?>
                                        <span class="input-group-text">₦</span>
                                        <input type="number" name="settings[<?= $setting->key ?>]" class="form-control" value="<?= esc($setting->value) ?>" step="0.01">
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">Last updated: <?= date('M d, Y H:i', strtotime($setting->updated_at)) ?></small>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-4">
            <div class="card bg-primary-transparent border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="ti ti-info-circle me-2"></i>Program Rules</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="ti ti-check text-primary me-2 mt-1"></i>
                            <span><strong>Employer Reward:</strong> Granted when the referred employer makes their first successful subscription or bundle purchase.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="ti ti-check text-primary me-2 mt-1"></i>
                            <span><strong>Candidate Reward:</strong> Granted when the referred candidate verifies their email address.</span>
                        </li>
                        <li class="mb-0 d-flex align-items-start">
                            <i class="ti ti-check text-primary me-2 mt-1"></i>
                            <span><strong>Wallet Integration:</strong> Rewards are automatically credited to the referrer's wallet.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
