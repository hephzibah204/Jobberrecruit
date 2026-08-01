<?php $page_title = 'Transaction History'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.stats--txn { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
.txn-ref { font-family: 'Sora', sans-serif; font-size: .72rem; font-weight: 700;
  color: var(--muted); background: var(--bg); padding: 3px 8px; border-radius: 6px; }
.ic-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px;
  border-radius: 8px; border: 1.5px solid var(--border); background: #fff; color: var(--muted);
  cursor: pointer; transition: var(--transition); flex-shrink: 0; text-decoration: none; }
.ic-btn:hover { border-color: var(--brand); color: var(--brand); }
.ic-btn svg { width: 15px; height: 15px; }

/* wallet summary card */
.wallet-card { background: linear-gradient(135deg, var(--brand-deep) 0%, var(--brand) 100%);
  color: #fff; border-radius: var(--radius-lg); padding: 24px 28px; display: flex;
  align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.wallet-card-label { font-size: .74rem; font-weight: 600; letter-spacing: .08em;
  text-transform: uppercase; color: rgba(255,255,255,.65); margin-bottom: 6px; }
.wallet-card-amt { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 2rem; color: #fff; }
.wallet-card-actions { display: flex; gap: 10px; flex-wrap: wrap; }

@media (max-width:760px){
  .tbl--txn{min-width:0}
  .tbl--txn thead{display:none}
  .tbl--txn tr{display:block;border-bottom:1px solid var(--border);padding:13px 4px}
  .tbl--txn td{display:flex;align-items:center;justify-content:space-between;border:none;padding:5px 0;gap:10px}
  .tbl--txn td::before{content:attr(data-lbl);font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted)}
}
@media (max-width:600px) {
  /* .tbl { min-width: 600px; } - overridden by mobile view */
}

/* Premium Polish Layer */
:root{
  --shadow-xs:0 1px 3px rgba(10,47,87,.06);
  --shadow-sm:0 2px 10px rgba(10,47,87,.07);
  --shadow-md:0 6px 24px rgba(10,47,87,.10);
  --shadow-lg-p:0 16px 44px rgba(10,47,87,.16);
  --border-c:#e2e8f2;
}
.card,.dash-card,.set-card,.plan,.info-card,.job-card,.les-detail,.cur-card,
.at-card,.faq-item,.modal,.q-block{
  box-shadow:var(--shadow-xs);
  border-color:var(--border-c);
}
.card:hover,.dash-card:hover,.job-card:hover,.cs-tool:hover,.res-item:hover{
  box-shadow:var(--shadow-sm);
}
.modal{box-shadow:var(--shadow-lg-p)}
.btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.tpl-swatch,
.les,.res-item,.faq-item,.plan .btn,.icon-btn{
  transition:transform .12s cubic-bezier(.2,.8,.2,1),
             box-shadow .18s ease,
             background-color .18s ease,
             border-color .18s ease,
             opacity .18s ease;
}
.btn:active,.at-pal:active,.at-opt:active,.q-opt:active,.cs-tool:active,
.les:active,.res-item:active{
  transform:scale(.97);
}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){
  .btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.les,.res-item{
    transition:background-color .12s ease,border-color .12s ease!important;
  }
  .btn:active,.btn:hover{transform:none!important}
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Compute totals
$totalSpent     = 0;
$totalCredited  = 0;
$totalCount     = count($transactions ?? []);
$successCount   = 0;
foreach ($transactions ?? [] as $t) {
    if (strtolower($t->type ?? '') === 'credit' || str_contains(strtolower($t->description ?? ''), 'reward')) {
        $totalCredited += (float)($t->amount ?? 0);
    } else {
        $totalSpent += (float)($t->amount ?? 0);
    }
    if (in_array(strtolower($t->status ?? ''), ['success', 'successful', 'completed', 'credited'])) {
        $successCount++;
    }
}
?>
<div class="content">

    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg> Transaction History</h1>
            <p>Your wallet funding and payment history.</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('candidate/wallet') ?>" class="btn btn-accent">
                <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg> Fund Wallet
            </a>
        </div>
    </div>

    <!-- Wallet summary banner -->
    <?php
    $walletModel = new \App\Models\WalletModel();
    $wallet = $walletModel->where('user_id', session()->get('user_id'))->first();
    $walletBalance = $wallet ? $wallet->balance : 0;
    ?>
    <div class="wallet-card" role="region" aria-label="Wallet balance">
        <div>
            <div class="wallet-card-label">Current Wallet Balance</div>
            <div class="wallet-card-amt">&#8358;<?= number_format($walletBalance, 2) ?></div>
        </div>
        <div class="wallet-card-actions">
            <a href="<?= base_url('candidate/wallet') ?>" class="btn btn-ghost-w">
                <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg> Fund Wallet
            </a>
        </div>
    </div>

    <!-- KPI stats -->
    <section class="stats stats--txn" aria-label="Transaction statistics" style="display:grid;">
        <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
            <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg></span></div>
            <div class="stat-num">&#8358;<?= number_format($totalSpent, 2) ?></div>
            <div class="stat-lbl">Total Spent</div>
        </div>
        <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
            <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check-c"/></svg></span></div>
            <div class="stat-num"><?= $successCount ?></div>
            <div class="stat-lbl">Successful Transactions</div>
        </div>
        <div class="stat">
            <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg></span></div>
            <div class="stat-num"><?= $totalCount ?></div>
            <div class="stat-lbl">Total Transactions</div>
        </div>
    </section>

    <!-- Transactions table -->
    <section class="card" aria-label="All transactions">
        <div class="card-head">
            <span class="card-title">
                <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg>
                All Transactions
            </span>
            <div class="toolbar">
                <select class="select" id="txn-type-filter" aria-label="Filter by type" style="width:auto;min-width:160px;min-height:38px;font-size:.8rem;">
                    <option value="">All types</option>
                    <option value="wallet">Wallet funding</option>
                    <option value="course">Course payment</option>
                    <option value="premium">Premium plan</option>
                    <option value="referral">Referral reward</option>
                    <option value="reward">Profile reward</option>
                </select>
            </div>
        </div>

        <?php if (empty($transactions)): ?>
        <div class="empty">
            <span class="empty-ic"><svg aria-hidden="true" style="width:28px;height:28px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-receipt"/></svg></span>
            <h3>No transactions yet</h3>
            <p>Your payment history will appear here once you fund your wallet or purchase a course.</p>
            <a href="<?= base_url('candidate/wallet') ?>" class="btn btn-primary btn-sm">
                <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg>
                Fund Wallet
            </a>
        </div>
        <?php else: ?>
        <div class="tbl-wrap">
            <table class="tbl tbl--txn" id="transactions-table">
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
                    <?php
                        $isCredit = in_array(strtolower($txn->type ?? ''), ['credit', 'reward']) || str_contains(strtolower($txn->description ?? ''), 'reward');
                        $statusLow = strtolower($txn->status ?? '');
                        $pillClass = in_array($statusLow, ['success', 'successful', 'completed', 'credited']) ? 'pill--success' : ($statusLow === 'failed' ? 'pill--rejected' : 'pill--pending');
                        $statusLabel = ucfirst($txn->status ?? 'Pending');
                    ?>
                    <tr data-type="<?= esc($txn->type ?? '') ?>">
                        <td data-lbl="Reference">
                            <span class="txn-ref"><?= esc($txn->reference ?? $txn->id) ?></span>
                        </td>
                        <td data-lbl="Description"><?= esc($txn->description ?? 'Transaction') ?></td>
                        <td data-lbl="Date"><?= esc(date('d M Y', strtotime($txn->created_at))) ?></td>
                        <td data-lbl="Amount">
                            <b style="font-family:'Sora',sans-serif;color:<?= $isCredit ? 'var(--success)' : 'var(--brand-deep)' ?>">
                                <?= $isCredit ? '+' : '' ?>&#8358;<?= number_format((float)$txn->amount, 2) ?>
                            </b>
                        </td>
                        <td data-lbl="Status"><span class="pill <?= $pillClass ?>"><?= $statusLabel ?></span></td>
                        <td data-lbl="Receipt">
                            <?php if (!empty($txn->receipt_url)): ?>
                            <a href="<?= esc($txn->receipt_url) ?>" class="ic-btn" target="_blank" aria-label="Download receipt" title="Download receipt">
                                <svg aria-hidden="true"><use href="#i-download"/></svg>
                            </a>
                            <?php else: ?>
                            <button class="ic-btn" aria-label="No receipt available" title="No receipt" disabled style="opacity:.4;cursor:not-allowed;">
                                <svg aria-hidden="true"><use href="#i-download"/></svg>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Type filter
var typeFilter = document.getElementById('txn-type-filter');
if (typeFilter) {
    typeFilter.addEventListener('change', function() {
        var val = this.value.toLowerCase();
        document.querySelectorAll('#transactions-table tbody tr').forEach(function(row) {
            var type = (row.dataset.type || '').toLowerCase();
            row.style.display = (!val || type.includes(val)) ? '' : 'none';
        });
    });
}
</script>
<?= $this->endSection() ?>
