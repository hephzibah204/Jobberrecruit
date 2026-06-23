<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold"><i class="ti ti-receipt me-2"></i>Transaction History</h4>
            <h6>View your wallet funding and payment history</h6>
        </div>
        <div class="page-btn">
            <a href="<?= base_url('employer/pricing') ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Fund Wallet
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="card custom-card bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Successful Transactions</h6>
                            <h4 class="mb-0"><?= count(array_filter($transactions, fn($t) => $t['status'] === 'paid')) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent">
                            <i class="ti ti-check fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card bg-info bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Transactions</h6>
                            <h4 class="mb-0"><?= count($transactions) ?></h4>
                        </div>
                        <div class="avatar bg-info-transparent">
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
                    <p class="text-muted">Your transaction history will appear here once you make a payment.</p>
                    <a href="<?= base_url('employer/pricing') ?>" class="btn btn-primary mt-2">
                        <i class="ti ti-plus me-1"></i>Make Your First Payment
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= date('M d, Y', strtotime($transaction['created_at'])) ?></div>
                                        <small class="text-muted"><?= date('h:i A', strtotime($transaction['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <code class="small"><?= esc($transaction['reference']) ?></code>
                                    </td>
                                    <td>
                                        <?php
                                        $metadata = json_decode($transaction['metadata'] ?? '{}', true);
                                        $type = $metadata['app_data']['type'] ?? $metadata['type'] ?? 'payment';
                                        $description = $type === 'subscription' ? 'Subscription Payment' : 
                                                     ($type === 'bundle' ? 'Credit Bundle Purchase' : 'Payment');
                                        ?>
                                        <div class="fw-semibold"><?= $description ?></div>
                                        <?php if ($type === 'bundle' && !empty($metadata['app_data']['credits'])): ?>
                                            <small class="text-muted"><?= $metadata['app_data']['credits'] ?> credits</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold">₦<?= number_format($transaction['amount'], 2) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="ti ti-credit-card me-1"></i><?= ucfirst($transaction['payment_method'] ?? 'Card') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($transaction['status'] === 'paid'): ?>
                                            <span class="badge bg-success-transparent text-success">
                                                <i class="ti ti-check me-1"></i>Completed
                                            </span>
                                        <?php elseif ($transaction['status'] === 'pending'): ?>
                                            <span class="badge bg-warning-transparent text-warning">
                                                <i class="ti ti-clock me-1"></i>Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-transparent text-danger">
                                                <i class="ti ti-x me-1"></i>Failed
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary" onclick="showReceipt('<?= $transaction['reference'] ?>', '<?= $transaction['amount'] ?>', '<?= date('M d, Y h:i A', strtotime($transaction['created_at'])) ?>', '<?= $description ?>')">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
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

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="ti ti-receipt me-2"></i>Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="<?= base_url('auth/img/logo.png') ?>" alt="JobberRecruit" style="max-height: 50px;">
                    <h5 class="mt-3 mb-1">Payment Receipt</h5>
                    <p class="text-muted small">JobberRecruit Platform</p>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Reference:</span>
                        <strong id="receipt-ref"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Date:</span>
                        <strong id="receipt-date"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Description:</span>
                        <strong id="receipt-desc"></strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted fw-bold">Amount Paid:</span>
                        <strong class="text-primary fs-18" id="receipt-amount"></strong>
                    </div>
                </div>
                <div class="alert alert-success small mb-0">
                    <i class="ti ti-check-circle me-1"></i>Payment completed successfully
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="ti ti-printer me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showReceipt(ref, amount, date, desc) {
    document.getElementById('receipt-ref').textContent = ref;
    document.getElementById('receipt-amount').textContent = '₦' + parseFloat(amount).toLocaleString('en-NG', {minimumFractionDigits: 2});
    document.getElementById('receipt-date').textContent = date;
    document.getElementById('receipt-desc').textContent = desc;
    
    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>
