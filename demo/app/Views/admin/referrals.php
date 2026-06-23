<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">
    <div class="page-header-breadcrumb mb-3">
        <div class="page-title">
            <h1 class="page-title fw-medium fs-18 mb-0">Affiliate Referral Program</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Referrals Tracking</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Top Referrers</div>
                    <a href="<?= base_url('admin/affiliate/settings') ?>" class="btn btn-primary btn-sm"><i class="ti ti-settings me-1"></i> Program Settings</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Referrer Name</th>
                                    <th>User Type</th>
                                    <th>Total Referrals</th>
                                    <th>Active Referrals (Paid/Verified)</th>
                                    <th>Wallet Balance Earned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($referrers)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="ti ti-users-x fs-1 mb-2 d-block"></i>
                                            No referral data found yet.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $rank = 1; foreach ($referrers as $referrer): ?>
                                        <tr>
                                            <td class="fw-bold">#<?= $rank++ ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm bg-primary-transparent rounded-circle me-2">
                                                        <i class="ti ti-user"></i>
                                                    </span>
                                                    <span class="fw-semibold"><?= esc($referrer->name) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $referrer->user_type === 'employer' ? 'info' : 'secondary' ?>-transparent">
                                                    <?= ucfirst(str_replace('_', ' ', $referrer->user_type)) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary rounded-pill px-3"><?= (int) $referrer->total_referrals ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success rounded-pill px-3"><?= (int) $referrer->active_referrals ?></span>
                                            </td>
                                            <td class="fw-bold text-success">
                                                ₦<?= number_format((float) $referrer->wallet_balance, 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
