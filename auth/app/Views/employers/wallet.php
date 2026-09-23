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
    <button type="button" onclick="openFundWalletModal()" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet</button>
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
        <button type="button" onclick="openFundWalletModal()" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true"><use href="#i-plus"/></svg> Make Your First Payment</button>
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
                <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" aria-label="Receipt download not yet available" title="Receipt download coming soon" disabled>
                  <svg aria-hidden="true"><use href="#i-download"/></svg>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<!-- Fund Wallet Modal -->
<div class="modal-scrim" id="fund-wallet-scrim" hidden>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="fundWalletModalLabel">
    <div class="modal-head">
      <span class="modal-title" id="fundWalletModalLabel"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet Balance</span>
      <button type="button" class="modal-close" id="fund-wallet-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <form action="<?= base_url('wallet/initialize') ?>" method="POST" id="fundWalletForm">
      <?= csrf_field() ?>
      <div class="modal-body">
        <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 20px;">Select or enter the amount you wish to deposit. You will be redirected to Paystack to complete your payment.</p>

        <!-- Predefined values -->
        <div style="display: flex; gap: 8px; margin-bottom: 16px;">
          <div style="flex: 1;">
            <button type="button" class="emp-btn emp-btn-outline" style="width:100%" data-val="10000">₦10,000</button>
          </div>
          <div style="flex: 1;">
            <button type="button" class="emp-btn emp-btn-outline" style="width:100%" data-val="25000">₦25,000</button>
          </div>
          <div style="flex: 1;">
            <button type="button" class="emp-btn emp-btn-outline" style="width:100%" data-val="50000">₦50,000</button>
          </div>
        </div>

        <div class="form-field">
          <label class="lbl" for="depositAmount">Custom deposit amount (NGN)</label>
          <div style="position: relative; display: flex; align-items: center;">
            <span style="position: absolute; left: 14px; font-weight: 600; color: var(--muted);">₦</span>
            <input type="number" name="amount" id="depositAmount" class="input" min="100" step="100" placeholder="Minimum 100" required style="padding-left: 30px; width: 100%; min-height: 44px; border: 1.5px solid var(--border); border-radius: 9px;">
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="emp-btn emp-btn-outline" id="fund-wallet-cancel">Cancel</button>
        <button type="submit" class="emp-btn emp-btn-primary"><svg aria-hidden="true"><use href="#i-card"/></svg> Proceed to Pay</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-outline">View Plans</a>
<button type="button" onclick="openFundWalletModal()" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet</button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
  var scrim = document.getElementById('fund-wallet-scrim');
  var presetBtns = scrim.querySelectorAll('[data-val]');
  var amountInput = document.getElementById('depositAmount');

  window.openFundWalletModal = function() {
    scrim.hidden = false;
    requestAnimationFrame(function() { scrim.classList.add('show'); });
  };

  function close() {
    scrim.classList.remove('show');
    setTimeout(function() { if (!scrim.classList.contains('show')) scrim.hidden = true; }, 240);
  }

  document.getElementById('fund-wallet-close').addEventListener('click', close);
  document.getElementById('fund-wallet-cancel').addEventListener('click', close);
  scrim.addEventListener('click', function(e) { if (e.target === scrim) close(); });
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && scrim.classList.contains('show')) close(); });

  presetBtns.forEach(function(btn) {
    btn.addEventListener('click', function () {
      presetBtns.forEach(function(b) {
        b.classList.remove('emp-btn-primary');
        b.classList.add('emp-btn-outline');
      });
      btn.classList.remove('emp-btn-outline');
      btn.classList.add('emp-btn-primary');
      amountInput.value = btn.getAttribute('data-val');
    });
  });

  amountInput.addEventListener('input', function() {
    presetBtns.forEach(function(b) {
      b.classList.remove('emp-btn-primary');
      b.classList.add('emp-btn-outline');
    });
  });
})();
</script>
<?= $this->endSection() ?>
