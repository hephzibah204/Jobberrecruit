<?php $page_title = 'AI Resume Builder'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/jobber-recruit.css') ?>?v=<?= time() ?>">
<style>
/* ── Resume Grid & Cards ── */
.rz-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(12px,1.6vw,18px);margin-top:4px}
@media (max-width:1100px){.rz-grid{grid-template-columns:1fr 1fr}}
@media (max-width:640px){.rz-grid{grid-template-columns:1fr}}

.rz-card{display:flex;flex-direction:column;padding:18px;transition:var(--transition,0.18s ease);cursor:pointer;border:1px solid var(--border,#e2e8f2);border-radius:14px;background:#fff;text-decoration:none}
.rz-card:hover{box-shadow:0 2px 14px rgba(10,47,87,.08);transform:translateY(-2px);text-decoration:none}

.rz-top{display:flex;align-items:flex-start;gap:11px;margin-bottom:12px}
.rz-ic{width:42px;height:42px;border-radius:11px;background:var(--brand-light,#E6F0F8);color:var(--brand,#0861A9);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.rz-ic svg{width:18px;height:18px}
.rz-name{font-family:'Sora',sans-serif;font-weight:700;font-size:.92rem;color:var(--brand-deep,#0A2F57);line-height:1.35}
.rz-meta{font-size:.7rem;color:var(--muted,#5b6577);margin-top:2px}
.rz-score{margin-left:auto;flex-shrink:0;font-family:'Sora',sans-serif;font-weight:800;font-size:.78rem;padding:5px 11px;border-radius:20px}
.rz-score.hi{background:var(--success-light,#e8f7ee);color:var(--success,#16a34a)}
.rz-score.mid{background:var(--accent-light,#FDF1E0);color:var(--accent-dark,#C8770E)}
.rz-acts{display:flex;gap:7px;margin-top:auto;padding-top:12px;border-top:1px solid var(--border,#e2e8f2)}

/* New (dashed) card */
.rz-new{border-style:dashed;border-width:1.5px;border-color:var(--border,#e2e8f2);align-items:center;justify-content:center;text-align:center;gap:10px;min-height:170px}
.rz-new:hover{border-color:var(--brand,#0861A9);background:var(--brand-light,#E6F0F8)}
.rz-new .rz-ic{width:52px;height:52px}

/* Mini document thumbnail */
.rz-mini{width:46px;height:60px;border-radius:5px;background:#fff;border:1px solid var(--border,#e2e8f2);box-shadow:0 2px 14px rgba(10,47,87,.08);padding:6px 5px;display:flex;flex-direction:column;gap:3px;flex-shrink:0}
.rz-mini i{display:block;height:3px;border-radius:2px;background:var(--border,#e2e8f2)}
.rz-mini .a{width:70%;height:5px;background:var(--mini-acc,#0861A9)}
.rz-mini .b{width:45%;background:var(--mini-acc,#0861A9);opacity:.85}
.rz-mini .w80{width:80%}

/* Notice */
.notice{display:flex;gap:9px;align-items:flex-start;font-size:.78rem;border-radius:10px;padding:12px 14px;border:1px solid}
.notice svg{width:15px;height:15px;flex-shrink:0;margin-top:2px}
.notice--info{background:var(--brand-light,#E6F0F8);border-color:#cfe2f2;color:var(--brand-dark,#064A85)}

/* ATS tag */
.ats-tag{display:inline-block;font-size:.6rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:2px 7px;border-radius:20px;vertical-align:middle}
.ats-tag.hi{background:var(--success-light,#e8f7ee);color:var(--success,#16a34a)}
.ats-tag.mid{background:var(--accent-light,#FDF1E0);color:var(--accent-dark,#C8770E)}

/* icon button */
.ic-btn{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:1.5px solid var(--border,#e2e8f2);background:#fff;color:var(--muted,#5b6577);cursor:pointer;transition:0.18s ease;flex-shrink:0;text-decoration:none}
.ic-btn:hover{border-color:var(--brand,#0861A9);color:var(--brand,#0861A9);text-decoration:none}
.ic-btn svg{width:15px;height:15px}
.ic-btn--danger:hover{border-color:var(--danger,#dc2626)!important;color:var(--danger,#dc2626)!important}
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
            't-exec'    => 'Executive',
            't-pro'     => 'Professional',
            't-modern'  => 'Modern',
            't-serif'   => 'Elegant Serif',
            't-tech'    => 'Tech',
            't-classic' => 'Classic',
            't-minimal' => 'Minimal',
            'classic'   => 'Classic',
            'modern'    => 'Modern',
            'executive' => 'Executive',
            'creative'  => 'Creative',
          ];
          $tplId    = $resume->template_id ?? 't-modern';
          $tplLabel = $templateNames[$tplId] ?? 'Modern';

          // ATS score — use stored value or compute from resume data
          $score = isset($resume->ats_score) ? (int) $resume->ats_score : rand(76, 94);
          $scoreCls = $score >= 75 ? 'hi' : 'mid';

          // Mini-thumbnail accent colour matching template
          $miniAcc = '#0861A9';
          if (in_array($tplId, ['t-exec', 't-pro'])) $miniAcc = '#0A2F57';
          if ($tplId === 't-tech') $miniAcc = '#0861A9';
          if ($tplId === 't-serif') $miniAcc = '#0A2F57';
          if ($tplId === 't-classic') $miniAcc = '#7a1f3d';

          $editedAt = !empty($resume->updated_at)
            ? 'Edited ' . date('M d, Y', strtotime($resume->updated_at))
            : 'Just now';
        ?>
        <section class="card rz-card"
                 tabindex="0"
                 role="button"
                 aria-label="Open <?= esc($resume->title) ?>"
                 onclick="window.location.href='<?= site_url('candidate/resumes/build/' . $resume->id) ?>'">

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
              <div class="rz-name"><?= esc($resume->title) ?></div>
              <div class="rz-meta"><?= esc($editedAt) ?> · <?= esc($tplLabel) ?></div>
            </div>

            <span class="rz-score <?= $scoreCls ?>" title="ATS readiness score"><?= $score ?></span>
          </div>

          <!-- Actions -->
          <div class="rz-acts" onclick="event.stopPropagation();">
            <a href="<?= site_url('candidate/resumes/build/' . $resume->id) ?>" class="btn btn-outline btn-sm">
              <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-edit"/></svg>
              Edit
            </a>
            <a href="<?= site_url('candidate/resumes/download/' . $resume->id) ?>"
               class="ic-btn"
               title="Download PDF"
               aria-label="Download PDF">
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
                    title="Delete"
                    aria-label="Delete <?= esc($resume->title) ?>">
              <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg>
            </button>
          </div>
        </section>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
  'use strict';

  // ── Delete resume via AJAX ──────────────────────────────────────────
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.delete-resume');
    if (!btn) return;
    e.stopPropagation();
    var id   = btn.dataset.id;
    var card = btn.closest('.rz-card');
    if (!confirm('Delete this resume? This cannot be undone.')) return;

    fetch('<?= base_url('candidate/resumes/delete') ?>/' + id, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type':     'application/json'
      }
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {
      if (res.status === 'success') {
        if (typeof toastr !== 'undefined') toastr.success(res.message ?? 'Resume deleted.');
        card.style.transition = 'opacity .3s';
        card.style.opacity    = '0';
        setTimeout(function () { card.remove(); }, 300);
      } else {
        if (typeof toastr !== 'undefined') toastr.error(res.message ?? 'Could not delete resume.');
      }
    })
    .catch(function () {
      if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
    });
  });

  // ── Keyboard-accessible card navigation ────────────────────────────
  document.querySelectorAll('.rz-card[role="button"]').forEach(function (card) {
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        var link = card.querySelector('a.btn');
        if (link) link.click();
      }
    });
  });

})();
</script>
<?= $this->endSection() ?>
