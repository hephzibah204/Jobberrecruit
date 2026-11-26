<?php $page_title = 'Offer Letter'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-send"/></svg> <?= !empty($offer) ? 'Edit Offer Draft' : 'Send Offer Letter' ?></h1>
    <p>Offer for <?= esc($prefill['candidate_name'] ?: 'the candidate') ?> — <?= esc($job->title ?? '') ?>. Fields marked * are required.</p>
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

<section class="card" aria-label="Offer letter form">
  <div class="card-body">
    <form method="post" action="<?= site_url('employer/offers/save') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="application_id" value="<?= esc($application->id) ?>">
      <input type="hidden" name="offer_id" value="<?= esc($offer->id ?? '') ?>">

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
          <label for="employment_type">Employment type</label>
          <?php $typeOptions = ['full-time' => 'Full-time', 'part-time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance']; ?>
          <?php $currentType = old('employment_type', $prefill['employment_type']); ?>
          <select id="employment_type" name="employment_type">
            <option value="">Select type</option>
            <?php if (!empty($currentType) && !isset($typeOptions[$currentType])): ?>
              <option value="<?= esc($currentType) ?>" selected><?= esc(ucwords(str_replace('-', ' ', $currentType))) ?></option>
            <?php endif; ?>
            <?php foreach ($typeOptions as $value => $label): ?>
              <option value="<?= $value ?>" <?= $currentType === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field">
          <label for="salary">Salary</label>
          <input type="text" id="salary" name="salary" maxlength="100" placeholder="e.g. 500000 or 400,000 – 600,000" value="<?= esc(old('salary', $prefill['salary'])) ?>">
        </div>
        <div class="form-field">
          <label for="currency">Currency</label>
          <?php $currentCurrency = old('currency', $prefill['currency']); ?>
          <select id="currency" name="currency">
            <?php foreach (['NGN', 'USD', 'GBP', 'EUR'] as $code): ?>
              <option value="<?= $code ?>" <?= $currentCurrency === $code ? 'selected' : '' ?>><?= $code ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field">
          <label for="start_date">Start date</label>
          <input type="date" id="start_date" name="start_date" value="<?= esc(old('start_date', $prefill['start_date'])) ?>">
        </div>
        <div class="form-field">
          <label for="probation_months">Probation (months)</label>
          <input type="number" id="probation_months" name="probation_months" min="0" max="24" step="1" value="<?= esc(old('probation_months', $prefill['probation_months'])) ?>">
        </div>
      </div>

      <div class="form-field" style="margin-top:16px;">
        <label for="letter_body">Letter content <span class="required-star">*</span></label>
        <textarea id="letter_body" name="letter_body" rows="18" required style="width:100%;"><?= esc(old('letter_body', $prefill['letter_body'])) ?></textarea>
        <p style="font-size:.78rem;color:var(--muted);margin-top:6px;">This is the exact text the candidate will read. Edit it freely before sending — what you save here is what goes out.</p>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
        <button type="submit" name="action" value="draft" class="emp-btn emp-btn-outline">
          <svg aria-hidden="true"><use href="#i-doc"/></svg> Save Draft
        </button>
        <button type="submit" name="action" value="send" class="emp-btn emp-btn-primary">
          <svg aria-hidden="true"><use href="#i-send"/></svg> Send Offer to Candidate
        </button>
      </div>
      <p style="font-size:.78rem;color:var(--muted);margin-top:10px;">Sending emails the candidate a secure link where they can read the letter, download it as a PDF, and accept or decline. Their response appears on your Offer Letters page.</p>
    </form>
  </div>
</section>

<?= $this->endSection() ?>
