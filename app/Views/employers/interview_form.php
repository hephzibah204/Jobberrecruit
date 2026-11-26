<?php $page_title = 'Schedule Interview'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-calendar"/></svg> Schedule Interview</h1>
    <p>Interview for <?= esc($prefill['candidate_name'] ?: 'the candidate') ?> — <?= esc($job->title ?? '') ?>. Fields marked * are required.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/applications/view/' . $application->id) ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Application
    </a>
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

<section class="card" aria-label="Schedule interview form">
  <div class="card-body">
    <form method="post" action="<?= site_url('employer/interviews/save') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="application_id" value="<?= esc($application->id) ?>">

      <div class="form-grid">
        <div class="form-field">
          <label for="candidate_name">Candidate name <span class="required-star">*</span></label>
          <input type="text" id="candidate_name" name="candidate_name" required maxlength="190" value="<?= esc(old('candidate_name', $prefill['candidate_name'])) ?>">
        </div>
        <div class="form-field">
          <label for="candidate_email">Candidate email <span class="required-star">*</span></label>
          <input type="email" id="candidate_email" name="candidate_email" required maxlength="190" value="<?= esc(old('candidate_email', $prefill['candidate_email'])) ?>">
        </div>
        <div class="form-field">
          <label for="job_title">Job title <span class="required-star">*</span></label>
          <input type="text" id="job_title" name="job_title" required maxlength="190" value="<?= esc(old('job_title', $prefill['job_title'])) ?>">
        </div>
        <div class="form-field">
          <label for="mode">Interview mode</label>
          <?php $modeOptions = ['in_person' => 'In Person', 'phone' => 'Phone Call', 'video' => 'Video Call']; ?>
          <?php $currentMode = old('mode', $prefill['mode']); ?>
          <select id="mode" name="mode">
            <?php foreach ($modeOptions as $value => $label): ?>
              <option value="<?= $value ?>" <?= $currentMode === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field">
          <label for="scheduled_at">Date &amp; time <span class="required-star">*</span></label>
          <input type="datetime-local" id="scheduled_at" name="scheduled_at" required value="<?= esc(old('scheduled_at', $prefill['scheduled_at'])) ?>">
        </div>
        <div class="form-field">
          <label for="duration_minutes">Duration (minutes)</label>
          <input type="number" id="duration_minutes" name="duration_minutes" min="5" max="480" step="5" value="<?= esc(old('duration_minutes', $prefill['duration_minutes'])) ?>">
        </div>
        <div class="form-field">
          <label for="location">Venue / Meeting link</label>
          <input type="text" id="location" name="location" maxlength="500" placeholder="Office address, meeting link, or phone note" value="<?= esc(old('location', $prefill['location'])) ?>">
        </div>
      </div>

      <div class="form-field" style="margin-top:16px;">
        <label for="notes">Note to candidate</label>
        <textarea id="notes" name="notes" rows="4" style="width:100%;" placeholder="Anything the candidate should know — who to ask for, what to bring, how to join."><?= esc(old('notes', $prefill['notes'])) ?></textarea>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
        <button type="submit" class="emp-btn emp-btn-primary">
          <svg aria-hidden="true"><use href="#i-calendar"/></svg> Schedule &amp; Send Invitation
        </button>
      </div>
      <p style="font-size:.78rem;color:var(--muted);margin-top:10px;">Scheduling emails the candidate a secure link where they can see the interview details and confirm or decline. Their response appears on your Interviews page. If this application is still at an early stage, it also moves to the Interview stage.</p>
    </form>
  </div>
</section>

<?= $this->endSection() ?>
