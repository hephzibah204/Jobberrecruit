<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="content">
    <div class="page-header mb-4 d-flex flex-wrap justify-content-between align-items-center">
        <div class="page-title">
            <h4 class="fw-bold">CV Review Management</h4>
            <p class="text-muted">Manage incoming CV review requests, run AI reviews, and deliver feedback.</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-info fs-12 px-3 py-2">Auto Mode: <?= env('cv_review_mode', 'semi') === 'auto' ? 'ON' : 'OFF' ?></span>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Candidate</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Review Mode</th>
                            <th>Submitted</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="ti ti-file-search" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                                No CV review requests yet.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($reviews as $r): ?>
                        <tr>
                            <td class="ps-4 fw-semibold">#<?= $r->id ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($r->full_name ?: 'N/A') ?></div>
                                <small class="text-muted"><?= esc($r->email) ?></small>
                            </td>
                            <td><span class="badge bg-<?= $r->plan === 'premium' ? 'warning' : ($r->plan === 'professional' ? 'primary' : 'secondary') ?>-transparent text-<?= $r->plan === 'premium' ? 'warning' : ($r->plan === 'professional' ? 'primary' : 'secondary') ?>"><?= ucfirst($r->plan) ?></span></td>
                            <td><?= $r->amount > 0 ? '&#8358;' . number_format($r->amount, 0) : 'Free' ?></td>
                            <td>
                                <?php
                                $statusBadges = [
                                    'pending'    => ['bg-warning-transparent', 'text-warning'],
                                    'in_review'  => ['bg-info-transparent', 'text-info'],
                                    'completed'  => ['bg-success-transparent', 'text-success'],
                                    'rejected'   => ['bg-danger-transparent', 'text-danger'],
                                ];
                                $badge = $statusBadges[$r->status] ?? ['bg-secondary-transparent', 'text-secondary'];
                                ?>
                                <span class="badge <?= $badge[0] ?> <?= $badge[1] ?> px-3 py-2"><?= ucfirst(str_replace('_', ' ', $r->status)) ?></span>
                            </td>
                            <td>
                                <?php if ($r->payment_status === 'paid'): ?>
                                    <span class="badge bg-success-transparent text-success">Paid</span>
                                <?php elseif ($r->payment_status === 'free'): ?>
                                    <span class="badge bg-secondary-transparent text-secondary">Free</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-transparent text-danger"><?= ucfirst($r->payment_status) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r->review_mode === 'auto'): ?>
                                    <span class="badge bg-info-transparent text-info">Auto</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-transparent text-secondary">Semi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= date('d M Y', strtotime($r->created_at)) ?></small>
                            </td>
                            <td class="pe-4">
                                <a href="<?= base_url('admin/cv-reviews/view/' . $r->id) ?>" class="btn btn-sm btn-primary">
                                    <i class="ti ti-eye me-1"></i>View
                                </a>
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
<?= $this->endSection() ?>
