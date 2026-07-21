<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg> Transaction History</h1>
            <p>Your wallet funding and payment history.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('candidate/wallet') ?>" class="btn btn-accent btn-sm">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg> Fund Wallet
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <section class="stats stats--txn" aria-label="Transaction statistics" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg></span>
            </div>
            <div class="stat-num">₦<?= number_format($totalSpent, 2) ?></div>
            <div class="stat-lbl">Total Spent</div>
        </div>
        <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check-c"/></svg></span>
            </div>
            <div class="stat-num"><?= count(array_filter($transactions, fn($t) => in_array($t['status'] ?? '', ['paid', 'completed']))) ?></div>
            <div class="stat-lbl">Successful Transactions</div>
        </div>
        <div class="stat">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg></span>
            </div>
            <div class="stat-num"><?= count($transactions) ?></div>
            <div class="stat-lbl">Total Transactions</div>
        </div>
    </section>

    <!-- Transactions Table -->
    <section class="card" aria-label="All transactions">
        <div class="card-head">
            <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg> All Transactions</span>
            <div class="toolbar">
                <select class="select" id="txnTypeFilter" aria-label="Filter by type" style="min-height:36px;font-size:.8rem;">
                    <option value="">All types</option>
                    <option value="wallet">Wallet funding</option>
                    <option value="course">Course payment</option>
                    <option value="premium">Premium plan</option>
                    <option value="referral">Referral reward</option>
                    <option value="reward">Profile reward</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (empty($transactions)): ?>
                <div class="empty">
                    <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg></span>
                    <h3>No transactions yet</h3>
                    <p>Your transaction history will appear here when you purchase a course or subscribe to a plan.</p>
                    <a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg> Browse Courses</a>
                </div>
            <?php else: ?>
                <div class="tbl-wrap">
                    <table class="tbl tbl--txn" id="transactions-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $txn): ?>
                                <tr>
                                    <td><span class="txn-ref" style="font-family:monospace;font-size:.78rem;color:var(--muted);"><?= esc($txn['reference']) ?></span></td>
                                    <td><?= esc($txn['description']) ?></td>
                                    <td>
                                        <b><?= date('d M Y', strtotime($txn['date'])) ?></b>
                                        <div style="font-size: 0.72rem; color: var(--muted);"><?= date('h:i A', strtotime($txn['date'])) ?></div>
                                    </td>
                                    <td>
                                        <?php if ((float) $txn['amount'] > 0): ?>
                                            <b style="font-family:'Sora',sans-serif;color:var(--brand-deep)">₦<?= number_format((float) $txn['amount'], 2) ?></b>
                                        <?php else: ?>
                                            <span class="pill pill--success">Free</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (in_array($txn['status'], ['paid', 'completed'])): ?>
                                            <span class="pill pill--success">Success</span>
                                        <?php elseif ($txn['status'] === 'free'): ?>
                                            <span class="pill pill--reviewed">Free</span>
                                        <?php elseif ($txn['status'] === 'pending'): ?>
                                            <span class="pill pill--pending">Pending</span>
                                        <?php else: ?>
                                            <span class="pill pill--rejected">Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="ic-btn" aria-label="Download receipt" title="Download receipt">
                                            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-download"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable && $('#transactions-table').length) {
            var txnTable = $('#transactions-table').DataTable({
                order: [[2, 'desc']],
                pageLength: 10,
                dom: '<"card-head"><"toolbar"f>t<"pager"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search transactions..."
                }
            });
            // Style search field
            $('.dataTables_filter input').addClass('input').css({ 'width': '220px', 'display': 'inline-block' });
            $('.dataTables_filter label').contents().filter(function() { return this.nodeType === 3; }).remove();

            // Wire type filter dropdown
            $('#txnTypeFilter').on('change', function() {
                txnTable.column(1).search($(this).val()).draw();
            });
        }
    });
</script>
<?= $this->endSection() ?>
