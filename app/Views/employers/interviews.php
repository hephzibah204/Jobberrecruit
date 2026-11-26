<?php $page_title = 'Interviews'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<?php
$modeLabels = ['in_person' => 'In Person', 'phone' => 'Phone', 'video' => 'Video'];
$statusPills = [
    'scheduled' => 'pill--reviewed',
    'confirmed' => 'pill--hired',
    'declined'  => 'pill--rejected',
    'cancelled' => 'pill--closed',
];
?>

<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-calendar"/></svg> Interviews</h1>
    <p>Scheduled interviews and each candidate's response. Schedule a new interview from an application's page.</p>
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

<?php
$renderRows = static function (array $rows) use ($modeLabels, $statusPills): void {
    foreach ($rows as $interview):
        $ts = strtotime((string) $interview->scheduled_at) ?: null;
        ?>
        <tr>
          <td>
            <strong><?= esc($interview->candidate_name) ?></strong><br>
            <span style="color:var(--muted);font-size:.8rem;"><?= esc($interview->candidate_email) ?></span>
          </td>
          <td><?= esc($interview->job_title) ?></td>
          <td><?= $ts ? esc(date('d M Y', $ts)) . '<br><span style="color:var(--muted);font-size:.8rem;">' . esc(date('g:i A', $ts)) . '</span>' : '—' ?></td>
          <td><?= esc($modeLabels[$interview->mode] ?? ucfirst((string) $interview->mode)) ?></td>
          <td><span class="pill <?= esc($statusPills[$interview->status] ?? 'pill--closed') ?>"><?= esc(ucfirst($interview->status)) ?></span></td>
          <td>
            <?php if (!empty($interview->responded_at) && in_array($interview->status, ['confirmed', 'declined'], true)): ?>
              <?= esc(ucfirst($interview->status)) ?> on <?= esc(date('d M Y', strtotime((string) $interview->responded_at))) ?>
            <?php elseif ($interview->status === 'scheduled'): ?>
              Awaiting response
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <a href="<?= base_url('interview/view/' . $interview->view_token) ?>" target="_blank" rel="noopener" class="emp-btn emp-btn-outline emp-btn-sm">View Page</a>
              <?php if (in_array($interview->status, ['scheduled', 'confirmed'], true)): ?>
                <form method="post" action="<?= site_url('employer/interviews/cancel/' . $interview->id) ?>" onsubmit="return confirm('Cancel this interview? The candidate will see it as cancelled on their invitation page.');" style="margin:0;">
                  <?= csrf_field() ?>
                  <button type="submit" class="emp-btn emp-btn-outline emp-btn-sm" style="color:var(--danger); border-color:var(--danger);">Cancel</button>
                </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php
    endforeach;
};
?>

<section class="card" aria-label="Upcoming interviews">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Candidate</th>
          <th>Job Title</th>
          <th>Date &amp; Time</th>
          <th>Mode</th>
          <th>Status</th>
          <th>Response</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($upcoming)): ?>
          <tr>
            <td colspan="7" class="no-lbl">
              <div class="empty">
                <div class="empty-ic"><svg aria-hidden="true"><use href="#i-calendar"/></svg></div>
                <h3>No upcoming interviews</h3>
                <p>Open any application and choose "Schedule Interview" to invite a candidate.</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php $renderRows($upcoming); ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php if (!empty($past)): ?>
<section class="card" aria-label="Past interviews" style="margin-top:20px;">
  <div class="card-head">
    <h2 style="margin:0;font-size:1rem;">Past Interviews</h2>
  </div>
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Candidate</th>
          <th>Job Title</th>
          <th>Date &amp; Time</th>
          <th>Mode</th>
          <th>Status</th>
          <th>Response</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $renderRows($past); ?>
      </tbody>
    </table>
  </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>
