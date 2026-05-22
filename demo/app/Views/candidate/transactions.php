<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold"><i class="ti ti-receipt me-2"></i>Transaction History</h4>
            <h6>View your course purchases and subscription payments</h6>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card custom-card bg-primary bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Spent</h6>
                            <h4 class="mb-0">₦<?= number_format($totalSpent, 2) ?></h4>
                        </div>
                        <div class="avatar bg-primary-transparent">
                            <i class="ti ti-wallet fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Transactions</h6>
                            <h4 class="mb-0"><?= count($transactions) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent">
                            <i class="ti ti-list fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card custom-card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">All Transactions</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($transactions)): ?>
                <div class="text-center py-5">
                    <i class="ti ti-receipt-off fs-64 text-muted mb-3"></i>
                    <h5 class="text-muted">No transactions yet</h5>
                    <p class="text-muted">Your transaction history will appear here when you purchase a course or subscribe to a plan.</p>
                    <a href="<?= base_url('training') ?>" class="btn btn-primary mt-2">
                        <i class="ti ti-plus me-1"></i>Browse Courses
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $txn): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= date('M d, Y', strtotime($txn['date'])) ?></div>
                                        <small class="text-muted"><?= date('h:i A', strtotime($txn['date'])) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="<?= $txn['icon'] ?> me-1"></i>
                                            <?= ucfirst($txn['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($txn['description']) ?></div>
                                    </td>
                                    <td>
                                        <code class="small"><?= esc($txn['reference']) ?></code>
                                    </td>
                                    <td>
                                        <?php if ((float) $txn['amount'] > 0): ?>
                                            <div class="fw-bold">₦<?= number_format((float) $txn['amount'], 2) ?></div>
                                        <?php else: ?>
                                            <span class="badge bg-success-transparent text-success">Free</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (in_array($txn['status'], ['paid', 'completed'])): ?>
                                            <span class="badge bg-success-transparent text-success">
                                                <i class="ti ti-check me-1"></i>Completed
                                            </span>
                                        <?php elseif ($txn['status'] === 'free'): ?>
                                            <span class="badge bg-info-transparent text-info">
                                                <i class="ti ti-check me-1"></i>Free
                                            </span>
                                        <?php elseif ($txn['status'] === 'pending'): ?>
                                            <span class="badge bg-warning-transparent text-warning">
                                                <i class="ti ti-clock me-1"></i>Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-transparent text-danger">
                                                <i class="ti ti-x me-1"></i>Failed
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
