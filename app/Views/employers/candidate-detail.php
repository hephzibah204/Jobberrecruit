<?php $page_title = 'Candidate Profile'; ?>
<?= $this->extend('layouts/employer') ?>

<?php
// Normalize the unlocked variable
$unlocked = isset($unlocked) ? (bool)$unlocked : (isset($isUnlocked) ? (bool)$isUnlocked : false);

// Initials generation
$candName = $candidate->full_name ?? 'Candidate';
$candInitials = '';
$words = explode(' ', preg_replace('/\s+/', ' ', trim($candName)));
$candInitials = count($words) >= 2
    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
    : strtoupper(substr($candName, 0, 2));

// Email and Phone masking functions
if (!function_exists('maskEmail')) {
    function maskEmail($email) {
        if (empty($email)) return '••••••••@••••.•••';
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        $len = strlen($name);
        if ($len <= 2) {
            return '••@' . $domain;
        }
        return substr($name, 0, 2) . str_repeat('•', max(3, $len - 3)) . substr($name, -1) . '@' . $domain;
    }
}
if (!function_exists('maskPhone')) {
    function maskPhone($phone) {
        if (empty($phone)) return '•••• ••• •••';
        $len = strlen($phone);
        if ($len <= 7) {
            return str_repeat('•', $len);
        }
        return substr($phone, 0, 4) . ' ••• ••' . substr($phone, -2);
    }
}

// Get wallet balance for view
$walletBalance = 0;
$user = auth()->user();
if (isset($user)) {
    try {
        $walletRow = model(\App\Models\WalletModel::class)->where('user_id', $user->id)->first();
        $walletBalance = $walletRow ? (float) $walletRow->balance : 0;
    } catch (\Throwable $e) {
        $walletBalance = 0;
    }
}
?>

<?= $this->section('content') ?>
<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-users"/></svg> Candidate Profile</h1>
    <p>Review this candidate's full profile.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/candidates') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Search
    </a>
  </div>
</div>

<div class="cp-grid">
  <!-- ══ LEFT · identity + paywalled contact ══ -->
  <div class="cp-col cp-sticky">
    <section class="card cp-id" aria-label="Candidate identity">
      <div class="cp-ava" aria-hidden="true">
        <?php if (!empty($candidate->profile_picture)): ?>
          <img src="<?= base_url($candidate->profile_picture) ?>" alt="img" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
        <?php else: ?>
          <?= esc($candInitials) ?>
        <?php endif; ?>
        <span class="dot" title="Open to work"></span>
      </div>
      <div class="cp-name"><?= esc($candidate->full_name) ?></div>
      <div class="cp-role"><?= esc($candidate->job_title ?? 'Professional') ?></div>
      <div class="cp-pills">
        <span class="chip"><?= esc($candidate->experience_years ?? 0) ?> years experience</span>
        <span class="chip"><?= esc($candidate->employment_type ?? 'Full Time') ?></span>
        <span class="pill pill--immediate"><?= esc($candidate->availability ?? 'Available immediately') ?></span>
      </div>
    </section>

    <section class="card" aria-label="Contact information">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-mail"/></svg> Contact Information
        </span>
      </div>
      <div class="card-body">
        <div class="lock-row">
          <svg aria-hidden="true"><use href="#i-mail"/></svg>
          <span>
            <span class="lbl2">Email</span>
            <b><?= $unlocked ? esc($candidate->email) : maskEmail($candidate->email) ?></b>
          </span>
          <?php if (!$unlocked): ?>
            <span class="lock-tag"><svg aria-hidden="true"><use href="#i-shield"/></svg> Locked</span>
          <?php endif; ?>
        </div>
        <div class="lock-row">
          <svg aria-hidden="true"><use href="#i-phone"/></svg>
          <span>
            <span class="lbl2">Phone</span>
            <b><?= $unlocked ? esc($candidate->phone) : maskPhone($candidate->phone) ?></b>
          </span>
          <?php if (!$unlocked): ?>
            <span class="lock-tag"><svg aria-hidden="true"><use href="#i-shield"/></svg> Locked</span>
          <?php endif; ?>
        </div>
        <div class="lock-row">
          <svg aria-hidden="true"><use href="#i-building"/></svg>
          <span>
            <span class="lbl2">Location</span>
            <b style="font-family:'Inter',sans-serif"><?= esc($candidate->state_name ?? 'Nigeria') ?></b>
          </span>
        </div>
        <div class="lock-row">
          <svg aria-hidden="true"><use href="#i-doc"/></svg>
          <span>
            <span class="lbl2">CV / Resume</span>
            <b style="font-family:'Inter',sans-serif"><?= $unlocked ? 'Available' : 'Available after unlock' ?></b>
          </span>
          <?php if (!$unlocked): ?>
            <span class="lock-tag"><svg aria-hidden="true"><use href="#i-shield"/></svg> Locked</span>
          <?php endif; ?>
        </div>

        <div class="cp-actions">
          <button onclick="startMessage(<?= (int)$candidate->id ?>)" class="emp-btn emp-btn-outline emp-btn-block">
            <svg aria-hidden="true"><use href="#i-chat"/></svg> Send Message — Free
          </button>
          <?php if ($unlocked): ?>
            <a href="<?= base_url('employer/download-cv/' . $candidate->id) ?>" class="emp-btn emp-btn-primary emp-btn-block">
              <svg aria-hidden="true"><use href="#i-download"/></svg> Download Resume
            </a>
          <?php else: ?>
            <button class="emp-btn emp-btn-primary emp-btn-block" data-unlock="<?= esc($candidate->full_name) ?>">
              <svg aria-hidden="true"><use href="#i-shield"/></svg> Unlock Contact &amp; CV · ₦5,000
            </button>
          <?php endif; ?>
        </div>
        <p style="font-size:.72rem;color:var(--muted);margin-top:10px;text-align:center">One-time fee per candidate, charged from your wallet. Messaging is always free.</p>
      </div>
    </section>
  </div>

  <!-- ══ RIGHT · profile sections ══ -->
  <div class="cp-col">
    <section class="card" aria-label="Professional summary">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-note"/></svg> Professional Summary
        </span>
      </div>
      <div class="card-body">
        <?php if (!empty($candidate->bio)): ?>
          <p style="white-space: pre-line;"><?= esc($candidate->bio) ?></p>
        <?php else: ?>
          <p class="cp-empty">This candidate hasn't added a professional summary yet.</p>
        <?php endif; ?>
      </div>
    </section>

    <section class="card" aria-label="Job preferences">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-star"/></svg> Job Preferences
        </span>
      </div>
      <div class="card-body">
        <div class="kv">
          <div>
            <div class="k">Salary expectation</div>
            <div class="v">
              <?php if (!empty($candidate->desired_salary)): ?>
                ₦<?= number_format((float)$candidate->desired_salary) ?> / <?= esc($candidate->salary_type ?? 'month') ?>
              <?php else: ?>
                Not specified
              <?php endif; ?>
            </div>
          </div>
          <div>
            <div class="k">Employment type</div>
            <div class="v"><?= esc($candidate->employment_type ?? 'Not specified') ?></div>
          </div>
          <div>
            <div class="k">Availability</div>
            <div class="v"><?= esc($candidate->availability ?? 'Not specified') ?></div>
          </div>
          <div>
            <div class="k">Preferred location</div>
            <div class="v"><?= esc($candidate->state_name ?? 'Not specified') ?></div>
          </div>
        </div>
        
        <?php if (!empty($industries)): ?>
          <div class="sec-title2" style="margin-top:18px">Preferred industries</div>
          <div class="chips">
            <?php foreach ($industries as $ind): ?>
              <span class="chip"><?= esc($ind->name) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="card" aria-label="Skills">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-zap"/></svg> Core Skills &amp; Competencies
        </span>
      </div>
      <div class="card-body">
        <?php 
        $skillsList = [];
        if (isset($skills) && is_array($skills)) {
            $skillsList = $skills;
        } elseif (isset($candidate->skills) && !empty($candidate->skills)) {
            $skillsList = is_array($candidate->skills) ? $candidate->skills : explode(',', $candidate->skills);
        }
        ?>
        <?php if (!empty($skillsList)): ?>
          <div class="chips">
            <?php foreach ($skillsList as $s): ?>
              <?php if (trim($s) !== ''): ?>
                <span class="chip"><?= esc(trim($s)) ?></span>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="cp-empty">No skills added yet.</p>
        <?php endif; ?>
      </div>
    </section>

    <section class="card" aria-label="Work experience">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-briefcase"/></svg> Work Experience
        </span>
      </div>
      <div class="card-body">
        <?php if (!empty($experience)): ?>
          <?php foreach ($experience as $xp): ?>
            <div class="xp">
              <span class="xp-ic" aria-hidden="true"><svg><use href="#i-briefcase"/></svg></span>
              <div>
                <?php
                $xpStart = !empty($xp->start_date) ? date('Y', strtotime($xp->start_date)) : '';
                $xpEnd   = !empty($xp->is_current) ? 'present'
                    : (!empty($xp->end_date) ? date('Y', strtotime($xp->end_date)) : 'present');
                ?>
                <b><?= esc($xp->job_title ?? '') ?></b>
                <i><?= esc($xp->company ?? '') ?> &middot; <?= esc($xp->location ?? '') ?> &middot; <?= esc($xpStart) ?> &ndash; <?= esc($xpEnd) ?></i>
                <?php if (!empty($xp->description)): ?>
                  <p><?= nl2br(esc($xp->description)) ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="cp-empty">No work experience added yet.</p>
        <?php endif; ?>
      </div>
    </section>

    <div class="cp-grid" style="grid-template-columns:1fr 1fr;gap:clamp(14px,1.8vw,20px)">
      <section class="card" aria-label="Education" style="min-width:0">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-grad"/></svg> Education
          </span>
        </div>
        <div class="card-body">
          <?php if (!empty($education)): ?>
            <?php foreach ($education as $edu): ?>
              <div class="xp" style="border:none;padding:2px 0">
                <span class="xp-ic" aria-hidden="true"><svg><use href="#i-grad"/></svg></span>
                <div>
                  <b><?= esc($edu->degree ?? '') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?></b>
                  <i><?= esc($edu->school ?? '') ?> &middot; <?= esc($edu->start_year ?? '') ?> &ndash; <?= esc($edu->end_year ?? 'Completed') ?></i>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="cp-empty">No education details added yet.</p>
          <?php endif; ?>
        </div>
      </section>

      <section class="card" aria-label="Languages" style="min-width:0">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-chat"/></svg> Languages
          </span>
        </div>
        <div class="card-body">
          <?php 
          $langList = [];
          if (isset($languages) && is_array($languages)) {
              $langList = $languages;
          } elseif (isset($candidate->languages) && !empty($candidate->languages)) {
              $langList = is_array($candidate->languages) ? $candidate->languages : explode(',', $candidate->languages);
          }
          ?>
          <?php if (!empty($langList)): ?>
            <div class="chips">
              <?php foreach ($langList as $l): ?>
                <?php if (trim($l) !== ''): ?>
                  <span class="chip"><?= esc(trim($l)) ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="cp-empty">No languages specified.</p>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</div>

<!-- ══ UNLOCK MODAL ══ -->
<div class="modal-scrim" id="unlock-scrim">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="unlock-title">
    <div class="modal-head">
      <span class="modal-title" id="unlock-title"><svg aria-hidden="true"><use href="#i-shield"/></svg> Unlock Candidate Details</span>
      <button class="modal-close" id="unlock-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body">
      <p style="font-size:.86rem">Unlock <b id="unlock-name">this candidate</b> to get:</p>
      <ul class="unlock-list">
        <li><svg aria-hidden="true"><use href="#i-phone"/></svg> Direct phone number</li>
        <li><svg aria-hidden="true"><use href="#i-mail"/></svg> Email address</li>
        <li><svg aria-hidden="true"><use href="#i-download"/></svg> Full CV / resume download</li>
      </ul>
      <div class="unlock-total"><span>One-time unlock fee</span><b>₦5,000</b></div>
      
      <?php if ($walletBalance < 5000): ?>
        <div class="notice notice--warn" id="unlock-warn" style="margin-top:12px">
          <svg aria-hidden="true"><use href="#i-wallet"/></svg>
          <span>Your wallet balance is <b>₦<?= number_format($walletBalance, 2) ?></b> — fund your wallet to unlock this candidate.</span>
        </div>
      <?php endif; ?>
      
      <p style="font-size:.72rem;color:var(--muted);margin-top:12px">The fee is deducted from your wallet. Unlocks are one-time per candidate, permanent for your account, and non-refundable. Messaging this candidate remains free without unlocking.</p>
    </div>
    <div class="modal-foot modal-foot--stacked">
      <div class="modal-foot-row">
        <button class="emp-btn emp-btn-outline" id="unlock-cancel">Cancel</button>
        <?php if ($walletBalance >= 5000): ?>
          <button class="emp-btn emp-btn-primary" id="confirm-unlock-btn" onclick="executeUnlock(<?= (int)$candidate->id ?>)">
            <svg aria-hidden="true"><use href="#i-wallet"/></svg> Use Wallet (₦5,000)
          </button>
        <?php endif; ?>
      </div>
      <button class="emp-btn emp-btn-accent" id="unlock-paystack-btn">
        <svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)
      </button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
  <button onclick="startMessage(<?= (int)$candidate->id ?>)" class="emp-btn emp-btn-outline">
    <svg aria-hidden="true"><use href="#i-chat"/></svg> Message
  </button>
  <?php if ($unlocked): ?>
    <a href="<?= base_url('employer/download-cv/' . $candidate->id) ?>" class="emp-btn emp-btn-primary">
      <svg aria-hidden="true"><use href="#i-download"/></svg> Download CV
    </a>
  <?php else: ?>
    <button class="emp-btn emp-btn-primary" data-unlock="<?= esc($candidate->full_name) ?>">
      <svg aria-hidden="true"><use href="#i-shield"/></svg> Unlock · ₦5,000
    </button>
  <?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
(function(){
  'use strict';
  var scrim=document.getElementById('unlock-scrim'),
      nameEl=document.getElementById('unlock-name'),
      closeB=document.getElementById('unlock-close'),
      cancelB=document.getElementById('unlock-cancel'),
      last=null;
      
  function open(name){
    nameEl.textContent=name;
    last=document.activeElement;
    scrim.classList.add('show');
    document.body.style.overflow='hidden';
  }
  
  function close(){
    scrim.classList.remove('show');
    document.body.style.overflow='';
    if(last)last.focus();
  }
  
  document.querySelectorAll('[data-unlock]').forEach(function(b){
    b.addEventListener('click',function(){open(b.getAttribute('data-unlock'))});
  });
  
  if (closeB) closeB.addEventListener('click',close);
  if (cancelB) cancelB.addEventListener('click',close);
  if (scrim) {
    scrim.addEventListener('click',function(e){if(e.target===scrim)close()});
  }
  
  const paystackB = document.getElementById('unlock-paystack-btn');
  if (paystackB) {
      paystackB.addEventListener('click', function() {
          paystackB.disabled = true;
          paystackB.textContent = 'Processing...';

          fetch("<?= base_url('employer/initiate-payment') ?>", {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({
                  type: 'unlock',
                  candidate_id: <?= (int)$candidate->id ?>,
                  email: '<?= esc($user->email ?? '') ?>',
                  full_name: '<?= esc($employer->company_name ?? '') ?>'
              })
          })
          .then(function(r) { return r.json(); })
          .then(function(res) {
              if (res.success && res.paystack) {
                  let handler = PaystackPop.setup({
                      key: res.paystack,
                      email: res.email,
                      amount: res.amount,
                      ref: res.reference,
                      channels: [res.method || 'card'],
                      metadata: res.metadata,
                      callback: function(response) {
                          // Verify payment via Ajax
                          var verifyData = new FormData();
                          verifyData.append('reference', response.reference);
                          verifyData.append('candidate_id', <?= (int)$candidate->id ?>);
                          verifyData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                          fetch("<?= base_url('employer/candidates/unlock-verify') ?>", {
                              method: 'POST',
                              headers: { 'X-Requested-With': 'XMLHttpRequest' },
                              body: verifyData
                          })
                          .then(function(vr) { return vr.json(); })
                          .then(function(vres) {
                              if (vres.success) {
                                  location.reload();
                              } else {
                                  alert(vres.message || 'Payment verification failed.');
                                  paystackB.disabled = false;
                                  paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                              }
                          })
                          .catch(function() {
                              alert('Error verifying transaction.');
                              paystackB.disabled = false;
                              paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                          });
                      },
                      onClose: function() {
                          paystackB.disabled = false;
                          paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                      }
                  });
                  handler.openIframe();
              } else {
                  alert(res.message || 'Failed to initialize payment.');
                  paystackB.disabled = false;
                  paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
              }
          })
          .catch(function(err) {
              alert('Connection error.');
              paystackB.disabled = false;
              paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
          });
      });
  }
})();

function showNotification(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        alert(message);
    }
}

function executeUnlock(id) {
    const btn = document.getElementById('confirm-unlock-btn');
    if (!btn) return;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Unlocking...';

    const formData = new FormData();
    formData.append('candidate_id', id);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url("employer/candidates/unlock") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showNotification('success', res.message || 'Unlocked successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', res.message || 'Failed to unlock');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        showNotification('error', 'An error occurred. Please try again.');
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function startMessage(candidateId) {
    const formData = new FormData();
    formData.append('seeker_id', candidateId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url("employer/messages/start") ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.success && res.redirect) {
            window.location.href = res.redirect;
        } else {
            showNotification('error', res.message || 'Failed to start conversation');
        }
    })
    .catch(err => {
        showNotification('error', 'Error starting conversation');
        console.error(err);
    });
}
</script>
<?= $this->endSection() ?>
