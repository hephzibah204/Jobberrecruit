<?php $page_title = 'Wallet'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<?php
$totalSpent = 0;
$successfulTransactions = 0;
$totalTransactions = count($transactions ?? []);
if (!empty($transactions)) {
    foreach ($transactions as $tx) {
        $status = strtolower($tx->status ?? 'completed');
        if ($status === 'completed' || $status === 'success' || $status === 'successful' || $status === 'approved') {
            $successfulTransactions++;
            if (($tx->type ?? '') === 'debit') {
                $totalSpent += (float)$tx->amount;
            }
        }
    }
}
?>

<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-receipt"/></svg> Transaction History</h1>
    <p>Your wallet funding and payment history.</p>
  </div>
  <div class="page-actions">
    <a href="#" data-bs-toggle="modal" data-bs-target="#fundWalletModal" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet</a>
  </div>
</div>

<!-- Transaction statistics -->
<section class="stats stats--txn" aria-label="Transaction statistics">
  <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-wallet"/></svg></span>
    </div>
    <div class="stat-num">&#8358;<?= number_format($totalSpent, 2) ?></div>
    <div class="stat-lbl">Total Spent</div>
  </div>
  <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-check-c"/></svg></span>
    </div>
    <div class="stat-num"><?= esc($successfulTransactions) ?></div>
    <div class="stat-lbl">Successful Transactions</div>
  </div>
  <div class="stat">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-receipt"/></svg></span>
    </div>
    <div class="stat-num"><?= esc($totalTransactions) ?></div>
    <div class="stat-lbl">Total Transactions</div>
  </div>
</section>

<!-- All transactions -->
<section class="card" aria-label="All transactions">
  <div class="card-head">
    <span class="card-title"><svg aria-hidden="true"><use href="#i-receipt"/></svg> All Transactions</span>
    <div class="toolbar">
      <select class="select" aria-label="Filter by type" style="min-height:38px;font-size:.8rem">
        <option>All types</option>
        <option>Wallet funding</option>
        <option>Job post</option>
        <option>Subscription</option>
        <option>Referral reward</option>
      </select>
    </div>
  </div>
  
  <?php if (empty($transactions)): ?>
    <div class="empty">
      <span class="empty-ic"><svg aria-hidden="true"><use href="#i-receipt"/></svg></span>
      <h3>No transactions yet</h3>
      <p>Your transaction history will appear here once you make a payment. Fund your wallet or buy a job bundle to get started.</p>
      <div style="display:flex;gap:9px;flex-wrap:wrap;justify-content:center">
        <a href="#" data-bs-toggle="modal" data-bs-target="#fundWalletModal" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true"><use href="#i-plus"/></svg> Make Your First Payment</a>
        <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-outline emp-btn-sm">View Plans</a>
      </div>
    </div>
  <?php else: ?>
    <div class="tbl-wrap">
      <table class="tbl">
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
          <?php foreach ($transactions as $tx): ?>
            <tr>
              <td><b><?= esc($tx->reference) ?></b></td>
              <td><?= esc($tx->description) ?></td>
              <td><?= date('M d, Y H:i A', strtotime($tx->created_at)) ?></td>
              <td>
                <b style="color: <?= ($tx->type === 'credit') ? 'var(--success)' : 'var(--text)' ?>">
                  <?= ($tx->type === 'credit') ? '+' : '-' ?>₦<?= number_format((float)$tx->amount, 2) ?>
                </b>
              </td>
              <td>
                <?php
                  $status = strtolower($tx->status ?? 'completed');
                  $pillClass = 'pill--closed';
                  if ($status === 'completed' || $status === 'success' || $status === 'successful' || $status === 'approved' || $status === 'hired' || $status === 'open' || $status === 'active') {
                      $pillClass = 'pill--hired';
                  } elseif ($status === 'pending') {
                      $pillClass = 'pill--pending';
                  } elseif ($status === 'rejected' || $status === 'failed') {
                      $pillClass = 'pill--rejected';
                  }
                ?>
                <span class="pill <?= $pillClass ?>">
                  <?= esc(ucfirst($status)) ?>
                </span>
              </td>
              <td>
                <a href="<?= base_url('employer/wallet/receipt/' . esc($tx->reference)) ?>" class="emp-btn emp-btn-outline emp-btn-sm" aria-label="Download receipt" target="_blank">
                  <svg aria-hidden="true"><use href="#i-download"/></svg>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<!-- Fund Wallet Modal -->
<div class="modal fade" id="fundWalletModal" tabindex="-1" aria-labelledby="fundWalletModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: #white; border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
      <div class="modal-header border-bottom p-4" style="border-color: var(--border) !important; display: flex; align-items: center; justify-content: space-between;">
        <h5 class="modal-title fw-bold text-main" id="fundWalletModalLabel" style="font-family:'Sora',sans-serif;font-weight:700;color:var(--brand-deep)">
          <svg aria-hidden="true" style="width:18px;height:18px;margin-right:8px;vertical-align:middle"><use href="#i-wallet"/></svg>Fund Wallet Balance
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--muted)">
          <svg aria-hidden="true" style="width:16px;height:16px"><use href="#i-x"/></svg>
        </button>
      </div>
      <form action="<?= base_url('wallet/initialize') ?>" method="POST" id="fundWalletForm">
        <?= csrf_field() ?>
        <div class="modal-body p-4">
          <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 20px;">Select or enter the amount you wish to deposit. You will be redirected to Paystack to complete your payment.</p>
          
          <!-- Predefined values -->
          <div class="row g-2 mb-3" style="display: flex; gap: 8px; margin-bottom: 16px;">
            <div style="flex: 1;">
              <button type="button" class="emp-btn emp-btn-outline w-100 preset-amount-btn" data-val="10000">₦10,000</button>
            </div>
            <div style="flex: 1;">
              <button type="button" class="emp-btn emp-btn-outline w-100 preset-amount-btn" data-val="25000">₦25,000</button>
            </div>
            <div style="flex: 1;">
              <button type="button" class="emp-btn emp-btn-outline w-100 preset-amount-btn" data-val="50000">₦50,000</button>
            </div>
          </div>

          <div class="form-group mb-0">
            <label class="form-label" style="display:block;font-size:.74rem;font-weight:600;color:var(--muted);margin-bottom:6px">CUSTOM DEPOSIT AMOUNT (NGN)</label>
            <div style="position: relative; display: flex; align-items: center;">
              <span style="position: absolute; left: 14px; font-weight: 600; color: var(--muted);">₦</span>
              <input type="number" name="amount" id="depositAmount" class="input" min="100" step="100" placeholder="Minimum 100" required style="padding-left: 30px; width: 100%; min-height: 44px; border: 1.5px solid var(--border); border-radius: 9px;">
            </div>
          </div>
        </div>
        <div class="modal-footer p-4" style="border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" class="emp-btn emp-btn-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="emp-btn emp-btn-primary"><svg aria-hidden="true" style="width:16px;height:16px;margin-right:6px"><use href="#i-card"/></svg> Proceed to Pay</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-outline">View Plans</a>
<a href="#" data-bs-toggle="modal" data-bs-target="#fundWalletModal" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const presetBtns = document.querySelectorAll(".preset-amount-btn");
    const amountInput = document.getElementById("depositAmount");

    presetBtns.forEach(btn => {
        btn.addEventListener("click", function () {
            presetBtns.forEach(b => {
                b.classList.remove("emp-btn-primary");
                b.classList.add("emp-btn-outline");
            });

            this.classList.remove("emp-btn-outline");
            this.classList.add("emp-btn-primary");
            
            amountInput.value = this.getAttribute("data-val");
        });
    });

    amountInput.addEventListener("input", function() {
        presetBtns.forEach(b => {
            b.classList.remove("emp-btn-primary");
            b.classList.add("emp-btn-outline");
        });
    });
});
</script>
<?= $this->endSection() ?>
