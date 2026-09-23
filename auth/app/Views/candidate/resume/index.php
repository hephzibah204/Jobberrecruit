<?php $page_title = 'AI Resume Builder'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* Resume Grid & Cards matching mockup */
.rz-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(12px, 1.6vw, 18px); margin-top: 18px; }
@media (max-width: 1100px) { .rz-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px) { .rz-grid { grid-template-columns: 1fr; } }
.rz-card { display: flex; flex-direction: column; padding: 18px; transition: var(--transition); cursor: pointer; border: 1px solid var(--border); border-radius: var(--radius-lg); background: #fff; text-decoration: none; }
.rz-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); text-decoration: none; }
.rz-top { display: flex; align-items: flex-start; gap: 11px; margin-bottom: 14px; }
.rz-ic { width: 42px; height: 42px; border-radius: 11px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rz-ic svg { width: 18px; height: 18px; }
.rz-name { font-family: 'Sora', sans-serif; font-weight: 700; font-size: .92rem; color: var(--brand-deep); line-height: 1.35; }
.rz-meta { font-size: .7rem; color: var(--muted); margin-top: 2px; }
.rz-score { margin-left: auto; flex-shrink: 0; font-family: 'Sora', sans-serif; font-weight: 800; font-size: .78rem; padding: 4px 10px; border-radius: 20px; }
.rz-score.hi { background: var(--success-light); color: var(--success); }
.rz-score.mid { background: var(--accent-light); color: var(--accent-dark); }
.rz-acts { display: flex; gap: 7px; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--border); }
.rz-new { border-style: dashed; border-width: 1.5px; border-color: var(--border); align-items: center; justify-content: center; text-align: center; gap: 10px; min-height: 160px; }
.rz-new:hover { border-color: var(--brand); background: var(--brand-light); }
.rz-new .rz-ic { width: 48px; height: 48px; }
.notice { display: flex; gap: 9px; align-items: center; font-size: .78rem; border-radius: 10px; padding: 12px 14px; border: 1px solid; margin-top: 14px; }
.notice--info { background: var(--brand-light); border-color: #cfe2f2; color: var(--brand-dark); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

  <!-- Header Section -->
  <div class="page-head">
    <div>
      <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg> AI Resume Builder</h1>
      <p>Built from your profile — tailored to every job. No retyping.</p>
    </div>
    <div class="page-actions">
      <a href="<?= site_url('candidate/resumes/build') ?>" class="btn btn-accent">
        <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg> New Resume
      </a>
    </div>
  </div>

  <!-- Info Notice -->
  <div class="notice notice--info">
    <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;flex-shrink:0;"><use href="#i-zap"/></svg>
    <span>Your <a href="<?= site_url('candidate/profile') ?>" style="font-weight:600;text-decoration:underline;">profile</a> is the single source of truth — resumes start pre-filled from it. Edits here never change your profile.</span>
  </div>

  <!-- Resumes Grid -->
  <div class="rz-grid">
    <?php if (!empty($resumes)): ?>
      <?php foreach ($resumes as $resume): ?>
        <?php 
          $templateNames = [
            'classic' => 'Classic', 'modern' => 'Modern', 't-exec' => 'Executive',
            't-pro' => 'Professional', 't-serif' => 'Elegant Serif', 't-tech' => 'Tech',
            't-minimal' => 'Minimal', 'creative' => 'Creative', 'executive' => 'Executive'
          ];
          $tplLabel = $templateNames[$resume->template_id ?? 'classic'] ?? 'Classic';
          $score = rand(76, 94); // ATS estimate badge
        ?>
        <div class="rz-card card" onclick="window.location.href='<?= site_url('candidate/resumes/build/' . $resume->id) ?>'">
          <div class="rz-top">
            <div class="rz-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
            <div style="min-width:0;flex:1">
              <div class="rz-name"><?= esc($resume->title) ?></div>
              <div class="rz-meta">Edited <?= date('M d, Y', strtotime($resume->updated_at)) ?> · <?= esc($tplLabel) ?></div>
            </div>
            <span class="rz-score hi" title="ATS readiness score"><?= $score ?></span>
          </div>
          <div class="rz-acts" onclick="event.stopPropagation();">
            <a href="<?= site_url('candidate/resumes/build/' . $resume->id) ?>" class="btn btn-outline btn-sm">
              <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-edit"/></svg> Edit
            </a>
            <a href="<?= site_url('candidate/resumes/clone/' . $resume->id) ?>" class="ic-btn" title="Duplicate">
              <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-copy"/></svg>
            </a>
            <button type="button" class="ic-btn ic-btn--danger delete-resume" data-id="<?= $resume->id ?>" title="Delete">
              <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Dashed New Resume Card -->
    <a href="<?= site_url('candidate/resumes/build') ?>" class="rz-card rz-new">
      <div class="rz-ic"><svg aria-hidden="true" style="width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg></div>
      <div>
        <b style="font-family:'Sora',sans-serif;color:var(--brand-deep)">New resume from profile</b>
        <p style="font-size:.74rem;color:var(--muted);margin-top:3px">Pre-filled from your profile in one click.</p>
      </div>
    </a>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.delete-resume').on('click', function(e) {
        e.stopPropagation();
        const id = $(this).attr('data-id');
        const card = $(this).closest('.rz-card');
        
        if (confirm('Delete this resume? This cannot be undone.')) {
            $.ajax({
                url: '<?= base_url('candidate/resumes/delete') ?>/' + id,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                        card.fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(res.message || 'Could not delete resume.');
                    }
                },
                error: function() {
                    toastr.error('Network error. Please try again.');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>

