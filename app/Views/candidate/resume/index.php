<?php $page_title = 'AI Resume Builder'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/jobber-recruit.css') ?>?v=<?= time() ?>">
<style>
/* ── Resume Grid & Cards ── */
.rz-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: clamp(12px, 1.6vw, 18px);
  margin-top: 4px;
}
@media (max-width: 1100px) { .rz-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px) { .rz-grid { grid-template-columns: 1fr; } }

.rz-card {
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 18px;
  transition: var(--transition, 0.18s ease);
  border: 1px solid var(--border, #e2e8f2);
  border-radius: 14px;
  background: #fff;
  text-decoration: none;
}
.rz-card:hover {
  box-shadow: 0 4px 18px rgba(10, 47, 87, 0.09);
  transform: translateY(-2px);
}

.rz-top {
  display: flex;
  align-items: flex-start;
  gap: 11px;
  margin-bottom: 12px;
}
.rz-ic {
  width: 42px;
  height: 42px;
  border-radius: 11px;
  background: var(--brand-light, #E6F0F8);
  color: var(--brand, #0861A9);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.rz-ic svg { width: 18px; height: 18px; }

.rz-name-link {
  font-family: 'Sora', sans-serif;
  font-weight: 700;
  font-size: .95rem;
  color: var(--brand-deep, #0A2F57);
  line-height: 1.35;
  text-decoration: none;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.rz-name-link:hover, .rz-name-link:focus-visible {
  color: var(--brand, #0861A9);
  text-decoration: underline;
}

.rz-meta {
  font-size: .74rem;
  color: var(--muted, #5b6577);
  margin-top: 3px;
}

.rz-score {
  margin-left: auto;
  flex-shrink: 0;
  font-family: 'Sora', sans-serif;
  font-weight: 800;
  font-size: .78rem;
  padding: 4px 10px;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}
.rz-score.hi { background: var(--success-light, #e8f7ee); color: var(--success, #16a34a); }
.rz-score.mid { background: var(--accent-light, #FDF1E0); color: var(--accent-dark, #C8770E); }
.rz-score.low { background: #fee2e2; color: #dc2626; }
.rz-score.none { background: #f1f5f9; color: var(--muted, #5b6577); font-weight: 600; font-size: .72rem; }

.rz-acts {
  display: flex;
  align-items: center;
  gap: 7px;
  margin-top: auto;
  padding-top: 14px;
  border-top: 1px solid var(--border, #e2e8f2);
}

/* New (dashed) card */
.rz-new {
  border-style: dashed;
  border-width: 1.5px;
  border-color: var(--border, #e2e8f2);
  align-items: center;
  justify-content: center;
  text-align: center;
  gap: 10px;
  min-height: 170px;
}
.rz-new:hover {
  border-color: var(--brand, #0861A9);
  background: var(--brand-light, #E6F0F8);
  text-decoration: none;
}
.rz-new .rz-ic { width: 52px; height: 52px; }

/* Mini document thumbnail */
.rz-mini {
  width: 46px;
  height: 60px;
  border-radius: 5px;
  background: #fff;
  border: 1px solid var(--border, #e2e8f2);
  box-shadow: 0 2px 10px rgba(10, 47, 87, .06);
  padding: 6px 5px;
  display: flex;
  flex-direction: column;
  gap: 3px;
  flex-shrink: 0;
}
.rz-mini i {
  display: block;
  height: 3px;
  border-radius: 2px;
  background: var(--border, #e2e8f2);
}
.rz-mini .a { width: 70%; height: 5px; background: var(--mini-acc, #0861A9); }
.rz-mini .b { width: 45%; background: var(--mini-acc, #0861A9); opacity: .85; }
.rz-mini .w80 { width: 80%; }

/* Notice */
.notice {
  display: flex;
  gap: 10px;
  align-items: flex-start;
  font-size: .82rem;
  border-radius: 10px;
  padding: 12px 16px;
  border: 1px solid;
}
.notice svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }
.notice--info {
  background: var(--brand-light, #E6F0F8);
  border-color: #cfe2f2;
  color: var(--brand-dark, #064A85);
}

/* Icon button */
.ic-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  border: 1.5px solid var(--border, #e2e8f2);
  background: #fff;
  color: var(--muted, #5b6577);
  cursor: pointer;
  transition: 0.18s ease;
  flex-shrink: 0;
  text-decoration: none;
}
.ic-btn:hover, .ic-btn:focus-visible {
  border-color: var(--brand, #0861A9);
  color: var(--brand, #0861A9);
  background: var(--brand-light, #E6F0F8);
  text-decoration: none;
}
.ic-btn svg { width: 15px; height: 15px; }
.ic-btn--danger:hover, .ic-btn--danger:focus-visible {
  border-color: var(--danger, #dc2626) !important;
  color: var(--danger, #dc2626) !important;
  background: #fee2e2 !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Extra SVG symbols needed on this page only -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4-4"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></symbol>
  </defs>
</svg>

<div class="content">

  <!-- Page Header -->
  <div class="page-head">
    <div>
      <h1>
        <svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg>
        AI Resume Builder
      </h1>
      <p>Built from your profile — tailored to every job. No retyping.</p>
    </div>
    <div class="page-actions">
      <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-accent" id="new-resume">
        <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2.2;"><use href="#i-plus"/></svg>
        New Resume
      </a>
    </div>
  </div>

  <!-- Info Notice -->
  <div class="notice notice--info" role="note">
    <svg aria-hidden="true"><use href="#i-zap"/></svg>
    <span>Your <a href="<?= site_url('candidate/profile') ?>" style="font-weight:600;text-decoration:underline;">profile</a> is the single source of truth — resumes start pre-filled from it. Edits here never change your profile.</span>
  </div>

  <!-- Resumes Grid -->
  <div class="rz-grid" id="rz-grid">

    <?php if (!empty($resumes)): ?>
      <?php foreach ($resumes as $resume): ?>
        <?php
          $templateNames = [
            't-exec'      => 'Executive',
            't-pro'       => 'Professional',
            't-modern'    => 'Modern',
            't-serif'     => 'Elegant Serif',
            't-tech'      => 'Tech',
            't-classic'   => 'Classic',
            't-minimal'   => 'Minimal',
            't-creative'  => 'Creative',
            'classic'     => 'Classic',
            'modern'      => 'Modern',
            'executive'   => 'Executive',
            'creative'    => 'Creative',
            'tech'        => 'Tech',
            'serif'       => 'Serif',
            'minimalist'  => 'Minimalist',
            'pro'         => 'Professional',
          ];
          $tplId    = $resume->template_id ?? 't-modern';
          $tplLabel = $templateNames[$tplId] ?? 'Modern';

          // ATS score — use real calculated score if present, otherwise indicate unrated
          $score = (isset($resume->ats_score) && $resume->ats_score !== null && $resume->ats_score !== '') ? (int) $resume->ats_score : null;
          if ($score !== null) {
            $scoreCls = $score >= 75 ? 'hi' : ($score >= 50 ? 'mid' : 'low');
            $scoreLabel = $score . '%';
            $scoreTitle = 'ATS Readiness Score: ' . $score . '%';
          } else {
            $scoreCls = 'none';
            $scoreLabel = 'ATS —';
            $scoreTitle = 'Open in Builder to calculate ATS Score';
          }

          // Mini-thumbnail accent colour matching template
          $miniAcc = '#0861A9';
          if (in_array($tplId, ['t-exec', 't-pro', 'executive', 'pro'])) $miniAcc = '#0A2F57';
          if (in_array($tplId, ['t-tech', 'tech'])) $miniAcc = '#0861A9';
          if (in_array($tplId, ['t-serif', 'serif'])) $miniAcc = '#4b5563';
          if (in_array($tplId, ['t-classic', 'classic'])) $miniAcc = '#7a1f3d';
          if (in_array($tplId, ['t-creative', 'creative'])) $miniAcc = '#8b5cf6';

          $editedAt = !empty($resume->updated_at)
            ? 'Edited ' . date('M d, Y', strtotime($resume->updated_at))
            : 'Just now';
        ?>
        <article class="card rz-card" data-resume-id="<?= $resume->id ?>">
          <div class="rz-top">
            <!-- Mini document thumbnail -->
            <div class="rz-mini" aria-hidden="true" style="--mini-acc:<?= esc($miniAcc) ?>">
              <i class="a"></i>
              <i class="w80"></i>
              <i></i>
              <i class="b w80"></i>
              <i></i>
              <i class="w80"></i>
              <i></i>
            </div>

            <div style="min-width:0;flex:1">
              <a href="<?= site_url('candidate/resumes/build/' . $resume->id) ?>" class="rz-name-link" title="Open <?= esc($resume->title) ?>">
                <?= esc($resume->title) ?>
              </a>
              <div class="rz-meta"><?= esc($editedAt) ?> · <?= esc($tplLabel) ?></div>
            </div>

            <span class="rz-score <?= $scoreCls ?>" title="<?= esc($scoreTitle) ?>" aria-label="<?= esc($scoreTitle) ?>">
              <?= esc($scoreLabel) ?>
            </span>
          </div>

          <!-- Actions -->
          <div class="rz-acts">
            <a href="<?= site_url('candidate/resumes/build/' . $resume->id) ?>" class="btn btn-outline btn-sm">
              <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-edit"/></svg>
              Edit
            </a>
            <a href="<?= site_url('candidate/resumes/download/' . $resume->id) ?>"
               class="ic-btn"
               title="Download PDF"
               aria-label="Download PDF for <?= esc($resume->title) ?>">
              <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg>
            </a>
            <a href="<?= site_url('candidate/resumes/clone/' . $resume->id) ?>"
               class="ic-btn"
               title="Duplicate"
               aria-label="Duplicate <?= esc($resume->title) ?>">
              <svg aria-hidden="true"><use href="#i-copy"/></svg>
            </a>
            <button type="button"
                    class="ic-btn ic-btn--danger delete-resume"
                    data-id="<?= $resume->id ?>"
                    data-title="<?= esc($resume->title) ?>"
                    title="Delete"
                    aria-label="Delete <?= esc($resume->title) ?>">
              <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg>
            </button>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Dashed "New Resume" card -->
    <a href="<?= site_url('candidate/resumes/build') ?>"
       class="rz-card rz-new card"
       id="rz-new-card"
       aria-label="Create new resume from profile">
      <span class="rz-ic" aria-hidden="true">
        <svg style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2.2;"><use href="#i-plus"/></svg>
      </span>
      <div>
        <b style="font-family:'Sora',sans-serif;color:var(--brand-deep,#0A2F57)">New resume from profile</b>
        <p style="font-size:.74rem;color:var(--muted,#5b6577);margin-top:3px">Pre-filled from your profile in one click.</p>
      </div>
    </a>

  </div><!-- /rz-grid -->

  <!-- Mobile sticky CTA bar -->
  <div class="mobile-cta" role="region" aria-label="Quick actions">
    <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-accent">
      <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2.2;"><use href="#i-plus"/></svg>
      New Resume
    </a>
  </div>

</div><!-- /content -->

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteResumeModal" tabindex="-1" aria-labelledby="deleteResumeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title font-weight-bold" id="deleteResumeModalLabel" style="font-family:'Sora',sans-serif;color:var(--brand-deep,#0A2F57);">
          Delete Resume
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-3">
        <p class="mb-1">Are you sure you want to delete <strong id="deleteResumeTitle">this resume</strong>?</p>
        <p class="text-muted small mb-0">This action cannot be undone. Any tailored content in this resume will be permanently removed.</p>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteResumeBtn">
          <span class="spinner-border spinner-border-sm me-1 d-none" id="deleteSpinner" role="status" aria-hidden="true"></span>
          Delete Resume
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
  'use strict';

  var pendingDeleteId = null;
  var pendingDeleteCard = null;
  var deleteModalEl = document.getElementById('deleteResumeModal');
  var deleteModal = null;

  if (deleteModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
    deleteModal = new bootstrap.Modal(deleteModalEl);
  }

  // ── Trigger Delete Modal ──────────────────────────────────────────
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.delete-resume');
    if (!btn) return;
    
    pendingDeleteId = btn.dataset.id;
    pendingDeleteCard = btn.closest('.rz-card');
    var resumeTitle = btn.dataset.title || 'this resume';

    var titleEl = document.getElementById('deleteResumeTitle');
    if (titleEl) titleEl.textContent = '"' + resumeTitle + '"';

    if (deleteModal) {
      deleteModal.show();
    } else if (confirm('Are you sure you want to delete "' + resumeTitle + '"? This cannot be undone.')) {
      executeDelete();
    }
  });

  // ── Confirm Delete Handler ─────────────────────────────────────────
  var confirmBtn = document.getElementById('confirmDeleteResumeBtn');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', function() {
      executeDelete();
    });
  }

  function executeDelete() {
    if (!pendingDeleteId) return;

    var spinner = document.getElementById('deleteSpinner');
    if (confirmBtn) {
      confirmBtn.disabled = true;
      if (spinner) spinner.classList.remove('d-none');
    }

    fetch('<?= base_url('candidate/resumes/delete') ?>/' + pendingDeleteId, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '<?= csrf_hash() ?>',
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type':     'application/json'
      }
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {
      if (deleteModal) deleteModal.hide();

      if (res.status === 'success' || res.success) {
        if (typeof toastr !== 'undefined') toastr.success(res.message ?? 'Resume deleted successfully.');
        if (pendingDeleteCard) {
          pendingDeleteCard.style.transition = 'opacity .3s, transform .3s';
          pendingDeleteCard.style.opacity = '0';
          pendingDeleteCard.style.transform = 'scale(0.95)';
          setTimeout(function () { pendingDeleteCard.remove(); }, 300);
        }
      } else {
        if (typeof toastr !== 'undefined') toastr.error(res.message ?? 'Could not delete resume.');
      }
    })
    .catch(function () {
      if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
    })
    .finally(function() {
      if (confirmBtn) {
        confirmBtn.disabled = false;
        if (spinner) spinner.classList.add('d-none');
      }
      pendingDeleteId = null;
      pendingDeleteCard = null;
    });
  }
})();
</script>
<?= $this->endSection() ?>
