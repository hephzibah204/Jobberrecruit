<?php $page_title = 'Application Details'; ?>
<?= $this->extend('layouts/employer') ?>

<?php
$fullName = trim(($applicant->first_name ?? $application->first_name ?? '') . ' ' . ($applicant->last_name ?? $application->last_name ?? ''));
if (empty($fullName)) {
    $fullName = 'Guest Applicant';
}

$initials = '';
$parts = explode(' ', $fullName);
$initials = strtoupper(substr($parts[0] ?? 'G', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

$appliedDate = !empty($application->created_at) ? date('d M Y, H:i', strtotime($application->created_at)) : 'N/A';
?>

<?= $this->section('content') ?>
<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-doc"/></svg> Application Details</h1>
    <p>Review this candidate's application.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Applications
    </a>
  </div>
</div>

<div class="detail-grid">
  <div style="display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px)">

    <!-- Candidate Info Card -->
    <section class="card" aria-label="Candidate information">
      <div class="card-body">
        <div class="cand-head">
          <span class="ava" aria-hidden="true"><?= esc($initials) ?></span>
          <div>
            <h2>
              <?= esc($fullName) ?>
              <?php if (!empty($application->is_guest)): ?>
                <span class="pill pill--closed" style="margin-left: 8px;">Guest</span>
              <?php endif; ?>
            </h2>
            <p>Applied for <b><?= esc($application->job_title ?? '') ?></b> · <?= esc($appliedDate) ?></p>
          </div>
          <div class="status-select">
            <label class="lbl" for="status" style="margin:0">Status</label>
            <select class="select" id="status" aria-label="Application status">
              <option value="pending" <?= ($application->status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="reviewed" <?= ($application->status ?? '') === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
              <option value="shortlisted" <?= ($application->status ?? '') === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
              <option value="rejected" <?= ($application->status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
              <option value="hired" <?= ($application->status ?? '') === 'hired' ? 'selected' : '' ?>>Hired</option>
            </select>
          </div>
        </div>
        <div class="info-grid">
          <div><div class="info-lbl">Email address</div><div class="info-val"><a href="mailto:<?= esc($applicant->email ?? $application->email ?? '') ?>"><?= esc($applicant->email ?? $application->email ?? '') ?></a></div></div>
          <div><div class="info-lbl">Phone number</div><div class="info-val"><a href="tel:<?= esc($applicant->phone ?? $application->phone ?? '') ?>"><?= esc($applicant->phone ?? $application->phone ?? '') ?></a></div></div>
          <div><div class="info-lbl">Years of experience</div><div class="info-val"><?= esc($application->experience ?? $resume->experience ?? 'Not specified') ?></div></div>
          <div><div class="info-lbl">Education level</div><div class="info-val"><?= esc($application->education ?? $resume->education ?? 'Not specified') ?></div></div>
        </div>

        <?php 
        $skills_list = [];
        if (!empty($application->skills)) {
            $skills_list = array_filter(array_map('trim', explode(',', $application->skills)));
        } elseif (!empty($resume) && !empty($resume->skills)) {
            if (is_array($resume->skills)) {
                $skills_list = $resume->skills;
            } else {
                $skills_list = array_filter(array_map('trim', explode(',', $resume->skills)));
            }
        }
        if (!empty($skills_list)):
        ?>
          <div style="margin-top: 20px;">
            <div class="info-lbl">Skills</div>
            <div class="chips" style="margin-top: 6px;">
              <?php foreach ($skills_list as $sk): ?>
                <?php $sk_val = is_object($sk) ? ($sk->skill_name ?? '') : $sk; ?>
                <?php if (!empty($sk_val)): ?>
                  <span class="chip"><?= esc($sk_val) ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Pre-screening Answers (ATS) -->
    <?php if (!empty($answers)): ?>
      <section class="card" aria-label="Pre-screening Questionnaire Answers">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-bulb"/></svg> Pre-screening Answers
          </span>
        </div>
        <div class="card-body">
          <?php foreach ($answers as $ans): ?>
            <div style="margin-bottom: 20px; &:last-child { margin-bottom: 0; }">
              <div class="info-lbl"><?= esc($ans->question) ?></div>
              <div style="padding: 12px; background: var(--bg); border-left: 4px solid var(--brand); border-radius: 6px; margin-top: 6px;">
                <p style="font-weight: 600; margin: 0; font-size: 0.88rem;">
                  <?php if (($ans->type ?? '') === 'checkbox'): ?>
                    <?php 
                      $vals = explode(', ', $ans->answer);
                      foreach($vals as $v):
                    ?>
                      <span class="chip" style="background: var(--brand-light); color: var(--brand); border-color: var(--brand-light); display: inline-block; margin-right: 4px; margin-bottom: 4px;"><?= esc($v) ?></span>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <?= nl2br(esc($ans->answer)) ?>
                  <?php endif; ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Cover Letter / Message -->
    <?php if (!empty($application->cover_letter)): ?>
      <section class="card" aria-label="Cover letter">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-mail"/></svg> Cover Letter / Message
          </span>
        </div>
        <div class="card-body">
          <p class="cover"><?= nl2br(esc($application->cover_letter)) ?></p>
        </div>
      </section>
    <?php endif; ?>

    <!-- Detailed Experience List (if available) -->
    <?php if (!empty($experience) && is_iterable($experience)): ?>
      <section class="card" aria-label="Work Experience">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-briefcase"/></svg> Detailed Work Experience
          </span>
        </div>
        <div class="card-body" style="display:flex; flex-direction:column; gap:16px;">
          <?php foreach ($experience as $exp): ?>
            <div style="border-bottom: 1px solid var(--border); padding-bottom: 14px; &:last-child { border-bottom: none; padding-bottom: 0; }">
              <h4 style="font-weight:700; color:var(--brand-deep); font-size:0.94rem; margin-bottom:2px;"><?= esc($exp->position ?? 'Position') ?></h4>
              <p style="font-size:0.8rem; color:var(--muted); margin-bottom:8px;">
                <b><?= esc($exp->company ?? 'Company') ?></b> &middot; 
                <?= date('M Y', strtotime($exp->start_date ?? 'now')) ?> &ndash; 
                <?= !empty($exp->is_current) ? 'Present' : (!empty($exp->end_date) ? date('M Y', strtotime($exp->end_date)) : '') ?>
              </p>
              <?php if (!empty($exp->description)): ?>
                <p style="font-size:0.84rem; color:var(--text);"><?= nl2br(esc($exp->description)) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Detailed Education List (if available) -->
    <?php if (!empty($education) && is_iterable($education)): ?>
      <section class="card" aria-label="Education History">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-grad"/></svg> Detailed Education
          </span>
        </div>
        <div class="card-body" style="display:flex; flex-direction:column; gap:16px;">
          <?php foreach ($education as $edu): ?>
            <div style="border-bottom: 1px solid var(--border); padding-bottom: 14px; &:last-child { border-bottom: none; padding-bottom: 0; }">
              <h4 style="font-weight:700; color:var(--brand-deep); font-size:0.94rem; margin-bottom:2px;">
                <?= esc($edu->degree ?? 'Degree') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?>
              </h4>
              <p style="font-size:0.8rem; color:var(--muted); margin:0;">
                <b><?= esc($edu->institution ?? 'Institution') ?></b> &middot; 
                <?= !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '' ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Job Applied For Details -->
    <section class="card" aria-label="Job details">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job Applied For
        </span>
        <?php if (!empty($application->job_id)): ?>
          <a href="<?= base_url('jobs/' . $application->job_id) ?>" class="card-link" target="_blank">
            View job post <svg aria-hidden="true"><use href="#i-arrow-r"/></svg>
          </a>
        <?php endif; ?>
      </div>
      <div class="card-body jd">
        <b style="font-family:'Sora',sans-serif;color:var(--brand-deep);font-size:1rem;display:block;margin-bottom:12px;">
          <?= esc($application->job_title ?? '') ?>
        </b>
        <?php if (!empty($application->job_description)): ?>
          <h4>Description</h4>
          <div><?= html_entity_decode(esc($application->job_description)) ?></div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <div class="sticky-col">
    <!-- Quick Actions -->
    <section class="card" aria-label="Quick actions">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-zap"/></svg> Quick Actions
        </span>
      </div>
      <div class="card-body">
        <div class="qa-stack">
          <button class="emp-btn emp-btn-primary emp-btn-block" data-status="shortlisted"><svg aria-hidden="true"><use href="#i-star"/></svg> Shortlist Candidate</button>
          <button class="emp-btn emp-btn-outline emp-btn-block" data-status="reviewed"><svg aria-hidden="true"><use href="#i-eye"/></svg> Mark as Reviewed</button>
          <button class="emp-btn emp-btn-outline emp-btn-block" data-status="hired"><svg aria-hidden="true"><use href="#i-user-check"/></svg> Mark as Hired</button>
          <button class="emp-btn emp-btn-danger emp-btn-block" data-status="rejected"><svg aria-hidden="true"><use href="#i-x"/></svg> Reject Candidate</button>
        </div>
        <p class="qa-note">The candidate is notified by email when the status changes.</p>
        <hr style="border:none;border-top:1px solid var(--border);margin:14px 0">
        <div class="qa-stack">
          <a href="mailto:<?= esc($applicant->email ?? $application->email ?? '') ?>" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm"><svg aria-hidden="true"><use href="#i-mail"/></svg> Send Email</a>
          <?php if (!empty($application->cv_path)): ?>
            <a href="<?= base_url($application->cv_path) ?>" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm" download><svg aria-hidden="true"><use href="#i-download"/></svg> Download CV/Resume</a>
          <?php endif; ?>
          <?php if (!empty($application->job_seeker_id)): ?>
            <a href="<?= base_url('employer/messages') ?>?candidate=<?= esc($application->job_seeker_id) ?>" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm"><svg aria-hidden="true"><use href="#i-chat"/></svg> Message Candidate</a>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Notes Card -->
    <section class="card" aria-label="Internal notes">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-note"/></svg> Notes 
          <span class="pill pill--reviewed" id="note-count"><?= count($notes ?? []) ?></span>
        </span>
      </div>
      <div class="card-body">
        <p style="font-size:.78rem;color:var(--muted);text-align:center; <?= !empty($notes) ? 'display:none;' : '' ?>" id="note-empty">
          No notes yet. Notes are only visible to your team.
        </p>
        <div id="note-list" style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px;">
          <?php if (!empty($notes)): ?>
            <?php foreach ($notes as $note): ?>
              <div class="note-item" data-id="<?= $note->id ?>">
                <div style="font-weight: 500; margin-bottom: 4px;"><?= nl2br(esc($note->note)) ?></div>
                <div style="font-size: 0.68rem; color: var(--muted); display: flex; justify-content: space-between; align-items: center;">
                  <span>By <?= esc($note->created_by_name ?? 'System') ?> &middot; <?= date('d M, H:i', strtotime($note->created_at)) ?></span>
                  <a href="#" class="delete-note text-danger" data-id="<?= $note->id ?>" style="display: inline-flex; align-items: center;" aria-label="Delete">
                    <svg style="width:12px; height:12px;"><use href="#i-trash"/></svg>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="note-input">
          <textarea class="input" id="note-text" rows="3" placeholder="Write an internal note…" aria-label="Write a note"></textarea>
          <select id="note-type" class="select" style="min-height:36px; font-size:.8rem; padding: 6px 12px; margin-bottom: 4px;">
            <option value="internal">📝 Internal Note</option>
            <option value="feedback">💬 Feedback</option>
            <option value="reminder">⏰ Reminder</option>
          </select>
          <button class="emp-btn emp-btn-primary emp-btn-sm" id="note-add"><svg aria-hidden="true"><use href="#i-plus"/></svg> Add Note</button>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Modal update screen -->
<div class="modal-scrim" id="modal-scrim">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-head">
      <span class="modal-title" id="modal-title"><svg aria-hidden="true"><use href="#i-x" id="modal-title-icon"/></svg> <span id="modal-title-text">Update Status</span></span>
      <button class="modal-close" id="modal-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body">
      <div class="notice notice--info"><svg aria-hidden="true"><use href="#i-bulb"/></svg>
        <span><b>Note:</b> the candidate will receive an email with your message below.</span></div>
      <label class="lbl" for="modal-msg" style="margin-top:12px; display:block;">Message to candidate <span class="req" aria-hidden="true">*</span></label>
      <textarea class="input" id="modal-msg" rows="6" required aria-describedby="modal-hint"></textarea>
      <p class="modal-hint" id="modal-hint">This message will be included in the email sent to the candidate.</p>
    </div>
    <div class="modal-foot">
      <button class="emp-btn emp-btn-outline" id="modal-cancel">Cancel</button>
      <button class="emp-btn emp-btn-primary" id="modal-send"><svg aria-hidden="true"><use href="#i-send"/></svg> Send &amp; Update Status</button>
    </div>
  </div>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"><svg aria-hidden="true"><use href="#i-check-c"/></svg><span id="toast-text"></span></div>

<!-- Delete Note Confirmation Modal -->
<div class="modal-scrim" id="delete-note-scrim">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="del-note-title">
    <div class="modal-head">
      <span class="modal-title" id="del-note-title"><svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note</span>
      <button class="modal-close" id="del-note-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body">
      <p style="font-size:0.9rem; color:var(--text); line-height:1.6;">Are you sure you want to delete this internal note? This action cannot be undone.</p>
    </div>
    <div class="modal-foot">
      <button class="emp-btn emp-btn-outline" id="del-note-cancel">Cancel</button>
      <button class="emp-btn emp-btn-danger" id="del-note-confirm"><svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note</button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button class="emp-btn emp-btn-primary" data-status="shortlisted"><svg aria-hidden="true"><use href="#i-star"/></svg> Shortlist</button>
<button class="emp-btn emp-btn-outline" data-status="rejected"><svg aria-hidden="true"><use href="#i-x"/></svg> Reject</button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* Notes and status update controllers */
(function(){
  'use strict';

  var noteText = document.getElementById('note-text'),
      noteType = document.getElementById('note-type'),
      noteAdd = document.getElementById('note-add'),
      noteList = document.getElementById('note-list'),
      noteCount = document.getElementById('note-count'),
      noteEmpty = document.getElementById('note-empty');

  // Add Note
  noteAdd.addEventListener('click', function() {
    var val = noteText.value.trim();
    var type = noteType.value;
    if (!val) return;

    noteAdd.disabled = true;
    noteAdd.innerHTML = 'Adding...';

    var data = new URLSearchParams();
    data.append('application_id', '<?= $application->id ?>');
    data.append('note', val);
    data.append('type', type);
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/add-note") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      noteAdd.disabled = false;
      noteAdd.innerHTML = '<svg aria-hidden="true"><use href="#i-plus"/></svg> Add Note';

      if (response.success) {
        showToast('Note added successfully.');
        noteText.value = '';

        var div = document.createElement('div');
        div.className = 'note-item';
        div.setAttribute('data-id', response.note.id);
        div.innerHTML = '<div style="font-weight: 500; margin-bottom: 4px;">' + escapeHtml(response.note.note) + '</div>' +
                        '<div style="font-size: 0.68rem; color: var(--muted); display: flex; justify-content: space-between; align-items: center;">' +
                          '<span>By ' + escapeHtml(response.note.created_by_name || 'System') + ' &middot; ' + response.note.created_at + '</span>' +
                          '<a href="#" class="delete-note text-danger" data-id="' + response.note.id + '" style="display: inline-flex; align-items: center;" aria-label="Delete">' +
                            '<svg style="width:12px; height:12px;"><use href="#i-trash"/></svg>' +
                          '</a>' +
                        '</div>';
        
        noteList.insertBefore(div, noteList.firstChild);
        noteEmpty.style.display = 'none';
        noteCount.textContent = parseInt(noteCount.textContent || 0) + 1;
        attachDeleteHandler(div.querySelector('.delete-note'));
      } else {
        toastr.error(response.message || 'Failed to add note');
      }
    })
    .catch(function(err) {
      noteAdd.disabled = false;
      noteAdd.innerHTML = '<svg aria-hidden="true"><use href="#i-plus"/></svg> Add Note';
      toastr.error('Error connecting to the server');
    });
  });

  // Delete note modal state
  var deleteNoteScrim = document.getElementById('delete-note-scrim'),
      deleteNoteConfirm = document.getElementById('del-note-confirm'),
      deleteNoteCancel = document.getElementById('del-note-cancel'),
      deleteNoteClose = document.getElementById('del-note-close'),
      pendingDeleteId = null;

  function closeDeleteModal() {
    deleteNoteScrim.classList.remove('show');
    document.body.style.overflow = '';
    pendingDeleteId = null;
  }

  function openDeleteModal(noteId) {
    pendingDeleteId = noteId;
    deleteNoteScrim.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  deleteNoteCancel.addEventListener('click', closeDeleteModal);
  deleteNoteClose.addEventListener('click', closeDeleteModal);
  deleteNoteScrim.addEventListener('click', function(e) { if (e.target === deleteNoteScrim) closeDeleteModal(); });

  deleteNoteConfirm.addEventListener('click', function() {
    if (!pendingDeleteId) return;
    var id = pendingDeleteId;
    deleteNoteConfirm.disabled = true;
    deleteNoteConfirm.innerHTML = 'Deleting...';

    var data = new URLSearchParams();
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/delete-note") ?>/' + id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      deleteNoteConfirm.disabled = false;
      deleteNoteConfirm.innerHTML = '<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note';
      if (response.success) {
        closeDeleteModal();
        showToast('Note deleted.');
        var item = document.querySelector('.note-item[data-id="' + id + '"]');
        if (item) {
          item.remove();
        }
        var count = Math.max(0, parseInt(noteCount.textContent || 0) - 1);
        noteCount.textContent = count;
        if (count === 0) {
          noteEmpty.style.display = 'block';
        }
      } else {
        toastr.error(response.message || 'Failed to delete note');
      }
    })
    .catch(function() {
      deleteNoteConfirm.disabled = false;
      deleteNoteConfirm.innerHTML = '<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note';
      toastr.error('Error connecting to server');
    });
  });

  // Attach delete notes
  function attachDeleteHandler(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var id = btn.getAttribute('data-id');
      if (!id) return;
      openDeleteModal(id);
    });
  }

  document.querySelectorAll('.delete-note').forEach(attachDeleteHandler);

  function escapeHtml(text) {
    if (!text) return '';
    var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }

  /* Status Modal Updates */
  var scrim = document.getElementById('modal-scrim'),
      titleT = document.getElementById('modal-title-text'),
      titleWrap = document.querySelector('.modal-title'),
      titleIcon = document.getElementById('modal-title-icon'),
      msg = document.getElementById('modal-msg'),
      closeB = document.getElementById('modal-close'),
      cancelB = document.getElementById('modal-cancel'),
      sendB = document.getElementById('modal-send'),
      statusSel = document.getElementById('status'),
      toast = document.getElementById('toast'),
      toastText = document.getElementById('toast-text'),
      lastFocus = null, current = null;

  var CFG = {
    shortlisted: {
      title: 'Shortlist Candidate',
      icon: '#i-star',
      cls: 't-shortlist',
      tpl: 'Congratulations! After reviewing your application, we are pleased to inform you that you have been shortlisted for the position.\n\nWe will contact you shortly with details of the next stage of the process.'
    },
    reviewed: {
      title: 'Mark as Reviewed',
      icon: '#i-eye',
      cls: 't-reviewed',
      tpl: 'Thank you for applying.\n\nThis is to confirm that your application has been received and reviewed by our team. We will be in touch regarding the next steps.'
    },
    hired: {
      title: 'Mark as Hired',
      icon: '#i-user-check',
      cls: 't-hired',
      tpl: 'Congratulations! We are delighted to inform you that you have been selected for the position.\n\nOur team will contact you shortly with your offer details and onboarding information.'
    },
    rejected: {
      title: 'Reject Candidate',
      icon: '#i-x',
      cls: 't-reject',
      tpl: 'Thank you for your interest in this position.\n\nAfter careful review of all applications, we regret to inform you that we have decided to move forward with other candidates whose qualifications more closely match our current needs.\n\nWe encourage you to apply for future openings that match your skills.'
    }
  };

  function openModal(status) {
    var c = CFG[status.toLowerCase()];
    if (!c) return;
    current = status.toLowerCase();
    lastFocus = document.activeElement;
    titleT.textContent = c.title;
    titleIcon.setAttribute('href', c.icon);
    titleWrap.className = 'modal-title ' + c.cls;
    msg.value = c.tpl;
    scrim.classList.add('show');
    document.body.style.overflow = 'hidden';
    setTimeout(function() { msg.focus(); msg.setSelectionRange(0, 0); }, 60);
  }

  function closeModal() {
    scrim.classList.remove('show');
    document.body.style.overflow = '';
    current = null;
    if (lastFocus) lastFocus.focus();
  }

  function showToast(t) {
    toastText.textContent = t;
    toast.classList.add('show');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function() { toast.classList.remove('show'); }, 3200);
  }

  /* Quick action buttons listener */
  document.querySelectorAll('[data-status]').forEach(function(b) {
    b.addEventListener('click', function() {
      openModal(b.getAttribute('data-status'));
    });
  });

  /* Status dropdown select listener */
  var prevStatus = statusSel.value;
  statusSel.addEventListener('change', function() {
    if (statusSel.value === 'pending') {
      // Direct status update for pending status since it has no template
      updateStatusDirectly('pending');
      prevStatus = 'pending';
      return;
    }
    openModal(statusSel.value);
    statusSel.value = prevStatus; // Revert select value until confirmed
  });

  function updateStatusDirectly(status) {
    var data = new URLSearchParams();
    data.append('application_id', '<?= $application->id ?>');
    data.append('status', status);
    data.append('message_to_candidate', 'Your application status has been updated.');
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/update-status") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      if (response.success) {
        showToast('Status updated to Pending.');
        setTimeout(function() { location.reload(); }, 1200);
      } else {
        toastr.error(response.message || 'Failed to update status');
      }
    });
  }

  sendB.addEventListener('click', function() {
    var messageVal = msg.value.trim();
    if (!messageVal) {
      msg.focus();
      return;
    }

    sendB.disabled = true;
    sendB.innerHTML = 'Updating...';

    var data = new URLSearchParams();
    data.append('application_id', '<?= $application->id ?>');
    data.append('status', current);
    data.append('message_to_candidate', messageVal);
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/update-status") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      sendB.disabled = false;
      sendB.innerHTML = '<svg aria-hidden="true"><use href="#i-send"/></svg> Send &amp; Update Status';
      if (response.success) {
        statusSel.value = current;
        prevStatus = current;
        showToast('Status updated to ' + current + ' — email sent.');
        closeModal();
        setTimeout(function() { location.reload(); }, 1200);
      } else {
        toastr.error(response.message || 'Failed to update status');
      }
    })
    .catch(function() {
      sendB.disabled = false;
      sendB.innerHTML = '<svg aria-hidden="true"><use href="#i-send"/></svg> Send &amp; Update Status';
      toastr.error('Error updating status');
    });
  });

  cancelB.addEventListener('click', closeModal);
  closeB.addEventListener('click', closeModal);
  scrim.addEventListener('click', function(e) { if (e.target === scrim) closeModal(); });
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && scrim.classList.contains('show')) closeModal(); });

})();
</script>
<?= $this->endSection() ?>