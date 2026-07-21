<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.stats--txn{grid-template-columns:repeat(3,1fr)}
@media (max-width:800px){.stats--txn{grid-template-columns:1fr 1fr}}
@media (max-width:520px){.stats--txn{grid-template-columns:1fr}}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$totalSpent = 0;
$successfulTxns = 0;
$totalTxns = count($transactions ?? []);

if (!empty($transactions)) {
    foreach ($transactions as $txn) {
        $status = strtolower($txn['status'] ?? '');
        if (in_array($status, ['success', 'hired', 'open', 'active', 'completed', 'approved'])) {
            $totalSpent += ($txn['amount'] ?? 0);
            $successfulTxns++;
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
    <a href="<?= base_url('employer/wallet') ?>" class="btn btn-accent">
      <svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet
    </a>
  </div>
</div>

<section class="stats stats--txn" aria-label="Transaction statistics">
  <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-wallet"/></svg></span>
    </div>
    <div class="stat-num">&#8358;<?= esc(number_format($totalSpent, 2)) ?></div>
    <div class="stat-lbl">Total Spent</div>
  </div>
  <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-check-c"/></svg></span>
    </div>
    <div class="stat-num"><?= esc($successfulTxns) ?></div>
    <div class="stat-lbl">Successful Transactions</div>
  </div>
  <div class="stat">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-receipt"/></svg></span>
    </div>
    <div class="stat-num"><?= esc($totalTxns) ?></div>
    <div class="stat-lbl">Total Transactions</div>
  </div>
</section>

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
        <a href="<?= base_url('employer/wallet') ?>" class="btn btn-primary btn-sm">
          <svg aria-hidden="true"><use href="#i-plus"/></svg> Make Your First Payment
        </a>
        <a href="<?= base_url('employer/pricing') ?>" class="btn btn-outline btn-sm">View Plans</a>
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
          <?php foreach ($transactions as $txn): ?>
            <?php
              $status = strtolower($txn['status'] ?? '');
              $statusClass = 'pill--pending';
              if (in_array($status, ['success', 'hired', 'open', 'active', 'completed', 'approved'])) {
                  $statusClass = 'pill--success';
              } elseif (in_array($status, ['rejected', 'failed'])) {
                  $statusClass = 'pill--rejected';
              } elseif (in_array($status, ['closed', 'expired'])) {
                  $statusClass = 'pill--closed';
              } elseif (in_array($status, ['reviewed', 'shortlisted'])) {
                  $statusClass = 'pill--reviewed';
              }
            ?>
            <tr>
              <td><b><?= esc($txn['reference'] ?? '-') ?></b></td>
              <td><?= esc($txn['description'] ?? '-') ?></td>
              <td><?= esc(!empty($txn['created_at']) ? date('M d, Y', strtotime($txn['created_at'])) : '-') ?></td>
              <td>&#8358;<?= esc(number_format($txn['amount'] ?? 0, 2)) ?></td>
              <td>
                <span class="pill <?= $statusClass ?>"><?= esc(ucfirst($txn['status'] ?? 'pending')) ?></span>
              </td>
              <td>
                <a href="<?= base_url('employer/transactions/receipt/' . esc($txn['id'] ?? $txn['reference'] ?? '')) ?>" class="ic-btn" title="Download Receipt" download>
                  <svg aria-hidden="true"><use href="#i-download"/></svg>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (isset($pager)): ?>
      <div class="pager">
        <?= $pager->links() ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/pricing') ?>" class="btn btn-outline">View Plans</a>
<a href="<?= base_url('employer/wallet') ?>" class="btn btn-accent">
  <svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet
</a>
<?= $this->endSection() ?>
