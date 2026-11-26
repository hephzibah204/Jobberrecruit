<?php $page_title = 'Offer Letters'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<?php
$symbols = ['NGN' => '₦', 'USD' => '$', 'GBP' => '£', 'EUR' => '€'];
$statusPills = [
    'draft'    => 'pill--closed',
    'sent'     => 'pill--reviewed',
    'accepted' => 'pill--hired',
    'declined' => 'pill--rejected',
];
?>

<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-send"/></svg> Offer Letters</h1>
    <p>Drafts and sent offers, with each candidate's response. Create a new offer from an application's page.</p>
  </div>
</div>

<?php if (session()->has('success')): ?>
  <div class="notice notice--info" role="status">
    <svg aria-hidden="true"><use href="#i-check-c"/></svg>
    <span><?= esc(session('success')) ?></span>
  </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
  <div class="notice notice--warn" role="status">
    <svg aria-hidden="true"><use href="#i-shield"/></svg>
    <span><?= esc(session('error')) ?></span>
  </div>
<?php endif; ?>

<section class="card" aria-label="Offer letters list">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Candidate</th>
          <th>Job Title</th>
          <th>Salary</th>
          <th>Status</th>
          <th>Sent On</th>
          <th>Response</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($offers)): ?>
          <tr>
            <td colspan="7" class="no-lbl">
              <div class="empty">
                <div class="empty-ic"><svg aria-hidden="true"><use href="#i-send"/></svg></div>
                <h3>No offer letters yet</h3>
                <p>Open any application and choose "Send Offer Letter" to create your first offer.</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($offers as $offer): ?>
            <tr>
              <td>
                <strong><?= esc($offer->candidate_name) ?></strong><br>
                <span style="color:var(--muted);font-size:.8rem;"><?= esc($offer->candidate_email) ?></span>
              </td>
              <td><?= esc($offer->job_title) ?></td>
              <td>
                <?php if (!empty($offer->salary)): ?>
                  <?= esc(($symbols[$offer->currency] ?? ($offer->currency . ' ')) . $offer->salary) ?>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
              <td><span class="pill <?= esc($statusPills[$offer->status] ?? 'pill--closed') ?>"><?= esc(ucfirst($offer->status)) ?></span></td>
              <td><?= !empty($offer->sent_at) ? esc(date('d M Y', strtotime((string) $offer->sent_at))) : '—' ?></td>
              <td>
                <?php if (!empty($offer->responded_at)): ?>
                  <?= esc(ucfirst($offer->status)) ?> on <?= esc(date('d M Y', strtotime((string) $offer->responded_at))) ?>
                <?php elseif ($offer->status === 'sent'): ?>
                  Awaiting response
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                  <?php if ($offer->status === 'draft'): ?>
                    <a href="<?= base_url('employer/offers/create/' . $offer->application_id) ?>" class="emp-btn emp-btn-primary emp-btn-sm">Edit &amp; Send</a>
                  <?php else: ?>
                    <a href="<?= base_url('offer/view/' . $offer->view_token) ?>" target="_blank" rel="noopener" class="emp-btn emp-btn-outline emp-btn-sm">View Page</a>
                  <?php endif; ?>
                  <a href="<?= base_url('employer/offers/pdf/' . $offer->id) ?>" class="emp-btn emp-btn-outline emp-btn-sm">
                    <svg aria-hidden="true"><use href="#i-download"/></svg> PDF
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?= $this->endSection() ?>
