<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.cp-grid{display:grid;grid-template-columns:330px 1fr;gap:clamp(14px,1.8vw,20px);align-items:start}
@media (max-width:1024px){.cp-grid{grid-template-columns:1fr}}
.cp-col{display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px)}
.cp-sticky{position:sticky;top:82px}
@media (max-width:1024px){.cp-sticky{position:static}}
.cp-id{text-align:center;padding:26px 22px 22px}
.cp-ava{width:96px;height:96px;border-radius:50%;margin:0 auto 14px;background:var(--brand-light);border:4px solid #fff;box-shadow:var(--shadow);color:var(--brand);font-family:'Sora',sans-serif;font-weight:800;font-size:1.6rem;display:flex;align-items:center;justify-content:center;position:relative}
.cp-ava .dot{position:absolute;right:5px;bottom:5px;width:15px;height:15px;border-radius:50%;background:var(--success);border:3px solid #fff}
.cp-name{font-family:'Sora',sans-serif;font-weight:800;font-size:1.15rem;color:var(--brand-deep)}
.cp-role{font-size:.84rem;color:var(--muted);margin:1px 0 12px}
.cp-pills{display:flex;gap:7px;justify-content:center;flex-wrap:wrap}
.lock-row{display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid var(--border);font-size:.84rem}
.lock-row:last-of-type{border-bottom:none}
.lock-row svg{width:15px;height:15px;color:var(--brand);flex-shrink:0}
.lock-row .lbl2{font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);display:block}
.lock-row b{font-weight:600;color:var(--brand-deep);font-family:ui-monospace,Menlo,Consolas,monospace;font-size:.82rem;letter-spacing:.04em}
.lock-tag{margin-left:auto;display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;color:var(--accent-dark);background:var(--accent-light);border-radius:20px;padding:3px 10px;flex-shrink:0}
.lock-tag svg{width:10px;height:10px;color:var(--accent-dark)}
.cp-actions{display:flex;flex-direction:column;gap:9px;margin-top:16px}
.sec-title2{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin:16px 0 8px}
.sec-title2:first-child{margin-top:0}
.cp-empty{font-size:.84rem;color:var(--muted);font-style:italic}
.kv{display:grid;grid-template-columns:1fr 1fr;gap:14px 24px}
@media (max-width:560px){.kv{grid-template-columns:1fr}}
.kv .k{font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:3px}
.kv .v{font-size:.88rem;font-weight:600;color:var(--brand-deep)}
.xp{display:flex;gap:13px;padding:13px 0;border-bottom:1px solid var(--border)}
.xp:first-of-type{padding-top:2px}
.xp:last-of-type{border-bottom:none;padding-bottom:2px}
.xp-ic{width:38px;height:38px;border-radius:10px;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--brand);flex-shrink:0}
.xp-ic svg{width:16px;height:16px}
.xp b{font-size:.86rem;color:var(--brand-deep);display:block}
.xp i{font-style:normal;font-size:.74rem;color:var(--muted)}
.xp p{font-size:.8rem;color:var(--text);margin-top:4px;line-height:1.6}

/* unlock modal */
.modal-scrim{position:fixed;inset:0;background:rgba(10,25,45,.55);backdrop-filter:blur(2px);z-index:1400;display:none;align-items:flex-end;justify-content:center;padding:0}
@media (min-width:641px){.modal-scrim{align-items:center;padding:24px}}
.modal-scrim.show{display:flex}
.modal{background:#fff;border-radius:16px 16px 0 0;width:100%;max-width:480px;max-height:calc(100vh - 40px);display:flex;flex-direction:column;box-shadow:var(--shadow-lg)}
@media (min-width:641px){.modal{border-radius:16px}}
.modal-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;border-bottom:1px solid var(--border)}
.modal-title{font-family:'Sora',sans-serif;font-weight:800;font-size:1.02rem;color:var(--brand-deep);display:inline-flex;align-items:center;gap:9px}
.modal-title svg{width:18px;height:18px;color:var(--brand)}
.modal-close{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--muted);cursor:pointer;transition:var(--transition)}
.modal-close:hover{border-color:var(--danger);color:var(--danger)}
.modal-close svg{width:16px;height:16px}
.modal-body{padding:18px 22px;overflow-y:auto}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 22px;padding-bottom:max(14px,env(safe-area-inset-bottom,0));border-top:1px solid var(--border);flex-wrap:wrap}
@media (max-width:480px){.modal-foot .btn{flex:1}}
.unlock-list{list-style:none;display:flex;flex-direction:column;gap:8px;margin:14px 0}
.unlock-list li{display:flex;gap:9px;align-items:center;font-size:.82rem;color:var(--text)}
.unlock-list svg{width:15px;height:15px;color:var(--brand);flex-shrink:0}
.unlock-total{display:flex;align-items:center;justify-content:space-between;background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:12px 15px;font-size:.84rem;font-weight:600;color:var(--brand-deep)}
.unlock-total b{font-family:'Sora',sans-serif;font-size:1.05rem}
</style>
<?= $this->endSection() ?>

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
<div class="page-hd">
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
                <b><?= esc($xp->job_title ?? $xp['job_title'] ?? $xp->title ?? $xp['title'] ?? '') ?></b>
                <i><?= esc($xp->company ?? $xp['company'] ?? '') ?> &middot; <?= esc($xp->location ?? $xp['location'] ?? '') ?> &middot; <?= esc($xp->start_year ?? $xp['start_year'] ?? '') ?> &ndash; <?= !empty($xp->currently_working) || !empty($xp['currently_working']) || !empty($xp->present) || !empty($xp['present']) ? 'present' : esc($xp->end_year ?? $xp['end_year'] ?? 'present') ?></i>
                <?php if (!empty($xp->description ?? $xp['description'] ?? '')): ?>
                  <p><?= nl2br(esc($xp->description ?? $xp['description'] ?? '')) ?></p>
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
                  <b><?= esc($edu->degree ?? $edu['degree'] ?? '') ?> in <?= esc($edu->field_of_study ?? $edu['field_of_study'] ?? '') ?></b>
                  <i><?= esc($edu->institution ?? $edu['institution'] ?? '') ?> &middot; <?= esc($edu->start_year ?? $edu['start_year'] ?? '') ?> &ndash; <?= esc($edu->end_year ?? $edu['end_year'] ?? 'Completed') ?></i>
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
    </div>
    <div class="modal-foot" style="flex-direction: column; gap: 10px; align-items: stretch; width: 100%;">
      <div style="display: flex; gap: 8px; justify-content: flex-end; width: 100%;">
        <button class="emp-btn emp-btn-outline" id="unlock-cancel" style="flex: 1;">Cancel</button>
        <?php if ($walletBalance >= 5000): ?>
          <button class="emp-btn emp-btn-primary" id="confirm-unlock-btn" onclick="executeUnlock(<?= (int)$candidate->id ?>)" style="flex: 2;">
            <svg aria-hidden="true"><use href="#i-wallet"/></svg> Use Wallet (₦5,000)
          </button>
        <?php endif; ?>
      </div>
      <button class="emp-btn emp-btn-accent" id="unlock-paystack-btn" style="width: 100%;">
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
  });
  
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
