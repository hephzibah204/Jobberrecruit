<?= $this->extend('auth/base') ?>

<?= $this->section('styles') ?>
<style>
/* ── Auth Layout ── */
.auth-shell{display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 75px)}
.auth-formcol{display:flex;align-items:flex-start;justify-content:center;padding:48px 24px 64px}
.auth-form-inner{width:100%;max-width:440px}
.auth-brand{background:radial-gradient(1200px 600px at 80% -10%,rgba(240,143,26,.18),transparent 55%),radial-gradient(900px 500px at -10% 110%,rgba(13,96,158,.35),transparent 55%),linear-gradient(155deg,var(--brand-deep) 0%,var(--brand-deep) 40%,var(--brand-dark) 100%);color:#fff;padding:56px 52px;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.auth-brand-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:44px 44px;mask-image:radial-gradient(circle at 50% 40%,#000,transparent 75%)}
.auth-brand-inner{position:relative;z-index:1;max-width:420px}
.auth-brand h2{font-size:1.9rem;font-weight:800;line-height:1.2;margin-bottom:16px;color:#fff}
.auth-brand h2 span{color:var(--accent)}
.auth-brand-lede{font-size:1rem;color:rgba(255,255,255,.82);line-height:1.6;margin-bottom:32px}
.auth-trust{display:flex;flex-direction:column;gap:16px}
.auth-trust-item{display:flex;align-items:flex-start;gap:12px}
.auth-trust-item svg{width:22px;height:22px;color:var(--accent);flex-shrink:0;margin-top:1px}
.auth-trust-item strong{display:block;font-size:.92rem;font-weight:700;margin-bottom:2px}
.auth-trust-item span{font-size:.82rem;color:rgba(255,255,255,.72);line-height:1.5}
.auth-brand-stats{display:flex;gap:28px;margin-top:36px;padding-top:28px;border-top:1px solid rgba(255,255,255,.14)}
.auth-brand-stat .n{font-family:'Sora',sans-serif;font-size:1.5rem;font-weight:800;color:#fff}
.auth-brand-stat .l{font-size:.74rem;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:2px}
.auth-title{font-size:1.7rem;font-weight:800;color:var(--brand-deep);margin-bottom:6px}
.auth-sub{font-size:.92rem;color:var(--muted);margin-bottom:26px}
.auth-sub a{font-weight:700}
.auth-alert{display:flex;align-items:flex-start;gap:11px;background:var(--warning-light);border:1px solid var(--warning-color);border-radius:10px;padding:13px 15px;margin-bottom:22px}
.auth-alert svg{width:19px;height:19px;color:var(--accent-dark);flex-shrink:0;margin-top:1px}
.auth-alert-body{display:flex;flex-direction:column;gap:2px}
.auth-alert-body strong{font-size:.86rem;font-weight:700;color:var(--warning-dark)}
.auth-alert-body span{font-size:.82rem;color:var(--warning-dark);line-height:1.5}
.auth-alert-body a{font-weight:700;color:var(--brand)}
.social-row{display:flex;flex-direction:column;gap:10px;margin-bottom:22px}
.social-btn{display:flex;align-items:center;justify-content:center;gap:11px;width:100%;min-height:48px;padding:12px;border:1.5px solid var(--border);border-radius:10px;background:#fff;font-family:'Inter',sans-serif;font-size:.9rem;font-weight:600;color:var(--text);cursor:pointer;transition:var(--transition)}
.social-btn:hover{border-color:var(--brand);background:var(--brand-light)}
.social-btn svg{width:20px;height:20px;flex-shrink:0}
.auth-divider{display:flex;align-items:center;gap:14px;margin:22px 0;color:var(--muted);font-size:.8rem}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:var(--border)}
.field{margin-bottom:16px}
.field label{display:block;font-size:.84rem;font-weight:600;color:var(--text);margin-bottom:7px}
.field-input{position:relative}
.field-input>svg.lead{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--muted);pointer-events:none}
.field input,.field select{width:100%;min-height:48px;padding:12px 14px 12px 42px;border:1.5px solid var(--border);border-radius:10px;font-family:'Inter',sans-serif;font-size:16px;color:var(--text);background:#fff;transition:var(--transition)}
.field input::placeholder{color:var(--muted)}
.field input:focus,.field select:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px rgba(13,96,158,.12)}
.field input.err{border-color:var(--danger)}
.field-toggle{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:var(--muted);cursor:pointer;border-radius:8px}
.field-toggle:hover{color:var(--brand);background:var(--brand-light)}
.field-toggle svg{width:19px;height:19px}
.field-msg{font-size:.76rem;margin-top:6px;display:none}
.field-msg.show{display:block}
.field-msg.error{color:var(--danger)}
.field-msg.hint{color:var(--muted);display:block}
.role-cards{border:none;margin:0 0 24px;padding:0}
.role-cards-legend{font-size:1rem;font-weight:800;color:var(--brand-deep);margin-bottom:13px;padding:0;display:flex;flex-direction:column;gap:2px;width:100%}
.role-cards-hint{font-size:.78rem;font-weight:500;color:var(--muted)}
.role-cards-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.role-card{position:relative;display:flex;flex-direction:column;align-items:flex-start;text-align:left;gap:7px;padding:20px 16px;border:2px solid var(--border);border-radius:14px;background:#fff;cursor:pointer;transition:var(--transition);font-family:'Inter',sans-serif;width:100%}
.role-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-lg)}
.role-card-ic{display:flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:12px;margin-bottom:4px;transition:var(--transition)}
.role-card-ic svg{width:26px;height:26px}
.role-card-title{font-size:.95rem;font-weight:700;color:var(--text);line-height:1.2}
.role-card-desc{font-size:.77rem;color:var(--muted);line-height:1.45}
.role-card-check{position:absolute;top:13px;right:13px;width:24px;height:24px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;opacity:0;transform:scale(.5);transition:var(--transition)}
.role-card-check svg{width:14px;height:14px}
.rc-seeker{background:var(--brand-light);border-color:#b8d6ee}
.rc-seeker .role-card-ic{background:var(--brand);color:#fff}
.rc-seeker:hover{border-color:var(--brand)}
.rc-seeker.selected{border-color:var(--brand);background:#dce9f5;box-shadow:0 0 0 3px rgba(13,96,158,.14)}
.rc-seeker .role-card-check{background:var(--brand)}
.rc-employer{background:#f0f2f5;border-color:#d0d6e0}
.rc-employer .role-card-ic{background:var(--brand-deep);color:#fff}
.rc-employer:hover{border-color:var(--brand-deep)}
.rc-employer.selected{border-color:var(--brand-deep);background:#e2e6ec;box-shadow:0 0 0 3px rgba(7,48,79,.14)}
.rc-employer .role-card-check{background:var(--brand-deep)}
.role-card.selected .role-card-check{opacity:1;transform:scale(1)}
.role-cards.err .role-card{border-color:var(--danger)}
.form-locked{opacity:.45;pointer-events:none;filter:saturate(.5)}
.pw-strength{margin-top:10px;display:none}
.pw-strength.show{display:block}
.pw-bars{display:flex;gap:5px;margin-bottom:6px}
.pw-bar{height:5px;flex:1;background:var(--border);border-radius:3px;transition:var(--transition)}
.pw-bar.f1{background:var(--danger)}
.pw-bar.f2{background:var(--accent)}
.pw-bar.f3{background:var(--brand)}
.pw-bar.f4{background:var(--success)}
.pw-label{font-size:.74rem;color:var(--muted)}
.pw-label strong{font-weight:700}
.check-row{display:flex;align-items:flex-start;gap:10px;margin:6px 0 22px}
.check-row input[type="checkbox"]{width:18px;height:18px;margin-top:2px;accent-color:var(--brand);flex-shrink:0;cursor:pointer}
.check-row label{font-size:.82rem;color:var(--muted);line-height:1.5;cursor:pointer}
.check-row a{font-weight:600}
.auth-submit{width:100%;min-height:50px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-family:'Inter',sans-serif;font-size:.96rem;font-weight:700;cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:9px;box-shadow:0 6px 18px rgba(240,143,26,.28)}
.auth-submit:hover{background:var(--accent-dark)}
.auth-submit:disabled{opacity:.7;cursor:not-allowed}
.auth-submit svg{width:18px;height:18px}
.auth-foot-note{text-align:center;font-size:.84rem;color:var(--muted);margin-top:22px}
.auth-foot-note a{font-weight:700}
.auth-secure{display:flex;align-items:center;justify-content:center;gap:7px;font-size:.76rem;color:var(--muted);margin-top:18px}
.auth-secure svg{width:14px;height:14px;color:var(--success)}
.auth-promise{display:flex;align-items:flex-start;gap:9px;background:var(--success-light);border:1px solid #86efac;border-radius:10px;padding:12px 14px;margin-top:18px}
.auth-promise svg{width:18px;height:18px;color:var(--success);flex-shrink:0;margin-top:1px}
.auth-promise span{font-size:.8rem;color:var(--success-dark);line-height:1.5}
.auth-promise strong{font-weight:800}
.auth-mobrand{display:none}
@media(max-width:900px){
  .auth-shell{grid-template-columns:1fr;min-height:auto}
  .auth-brand{display:none}
  .auth-mobrand{display:block;background:linear-gradient(135deg,var(--brand-deep),var(--brand-dark));color:#fff;padding:22px 24px;text-align:center}
  .auth-mobrand p{font-size:.86rem;color:rgba(255,255,255,.85);display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap}
  .auth-mobrand svg{width:16px;height:16px;color:var(--accent)}
  .auth-formcol{padding:34px 20px 52px}
}
@media(max-width:580px){
  .auth-title{font-size:1.45rem}
  .auth-form-inner{max-width:100%}
  input,select,textarea{font-size:16px!important}
}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="auth-mobrand">
  <p><svg aria-hidden="true" width="16" height="16"><use href="#i-shield"/></svg> Verified employers only &middot; Free for job seekers</p>
</div>

<main id="auth-main" class="auth-shell">
  <section class="auth-formcol" aria-labelledby="auth-h">
    <div class="auth-form-inner">
      <h1 class="auth-title" id="auth-h">Create your free account</h1>
      <p class="auth-sub" id="auth-sub-text">Start finding verified jobs in minutes. Already registered? <a href="<?= base_url('login') ?>">Log in here</a>.</p>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="auth-alert" role="alert">
        <svg aria-hidden="true" width="19" height="19"><use href="#i-bell"/></svg>
        <div class="auth-alert-body">
          <strong>Registration Error</strong>
          <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
      <div class="auth-alert" role="alert">
        <svg aria-hidden="true" width="19" height="19"><use href="#i-x-circle"/></svg>
        <div class="auth-alert-body">
          <strong>Please fix the following errors:</strong>
          <span><?= implode('<br>', array_map('esc', $errors)) ?></span>
        </div>
      </div>
      <?php endif; ?>

      <fieldset class="role-cards" id="role-cards">
        <legend class="role-cards-legend">Who are you signing up as?<span class="role-cards-hint">Choose one to get started</span></legend>
        <div class="role-cards-grid" role="radiogroup" aria-label="Account type" aria-required="true">
          <button type="button" class="role-card rc-seeker" role="radio" aria-checked="false" id="role-seeker" onclick="setRole('seeker')">
            <span class="role-card-ic"><svg aria-hidden="true" width="26" height="26"><use href="#i-user"/></svg></span>
            <span class="role-card-title">I'm looking for a job</span>
            <span class="role-card-desc">Find verified roles, build a CV, and apply</span>
            <span class="role-card-check"><svg aria-hidden="true" width="14" height="14"><use href="#i-check"/></svg></span>
          </button>
          <button type="button" class="role-card rc-employer" role="radio" aria-checked="false" id="role-employer" onclick="setRole('employer')">
            <span class="role-card-ic"><svg aria-hidden="true" width="26" height="26"><use href="#i-briefcase"/></svg></span>
            <span class="role-card-title">I'm hiring</span>
            <span class="role-card-desc">Post jobs and reach verified candidates</span>
            <span class="role-card-check"><svg aria-hidden="true" width="14" height="14"><use href="#i-check"/></svg></span>
          </button>
        </div>
        <p class="field-msg error" id="m-role">Please choose whether you're looking for a job or hiring.</p>
      </fieldset>

      <div id="form-lock" class="form-locked">
      <div class="social-row">
        <button type="button" class="social-btn" onclick="socialAuth('google')">
          <svg aria-hidden="true" width="20" height="20"><use href="#i-google"/></svg> Continue with Google
        </button>
        <button type="button" class="social-btn" onclick="socialAuth('linkedin')">
          <svg aria-hidden="true" width="20" height="20"><use href="#i-linkedin"/></svg> Continue with LinkedIn
        </button>
      </div>

      <div class="auth-divider">or sign up with email</div>

      <form id="register-form" action="<?= base_url('register') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="role" id="role-input" value="">
        <input type="hidden" name="referral_code" value="<?= esc(request()->getGet('ref')) ?>">

        <div class="field" id="f-name">
          <label for="reg-name">Full name</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-user"/></svg>
            <input type="text" id="reg-name" name="full_name" placeholder="e.g. Chidi Okafor" autocomplete="name" required>
          </div>
          <p class="field-msg error" id="m-name">Please enter your full name.</p>
        </div>

        <div class="field" id="f-company" style="display:none">
          <label for="reg-company">Company name</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-building"/></svg>
            <input type="text" id="reg-company" name="company_name" placeholder="e.g. Acme Technologies Ltd" autocomplete="organization">
          </div>
          <p class="field-msg error" id="m-company">Please enter your company name.</p>
        </div>

        <div class="field" id="f-email">
          <label for="reg-email">Email address</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-mail"/></svg>
            <input type="email" id="reg-email" name="email" placeholder="you@example.com" autocomplete="email" required>
          </div>
          <p class="field-msg error" id="m-email">Please enter a valid email address.</p>
        </div>

        <div class="field" id="f-phone">
          <label for="reg-phone">Phone number</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-phone"/></svg>
            <input type="tel" id="reg-phone" name="phone" placeholder="0801 234 5678" autocomplete="tel" inputmode="tel" required>
          </div>
          <p class="field-msg hint" id="h-phone">Required so verified employers can reach you about roles.</p>
          <p class="field-msg error" id="m-phone">Please enter a valid Nigerian phone number.</p>
        </div>

        <div class="field" id="f-pass">
          <label for="reg-pass">Password</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-lock"/></svg>
            <input type="password" id="reg-pass" name="password" placeholder="At least 8 characters" autocomplete="new-password" required>
            <button type="button" class="field-toggle" aria-label="Show password" onclick="togglePw('reg-pass', this)">
              <svg aria-hidden="true" width="19" height="19"><use href="#i-eye"/></svg>
            </button>
          </div>
          <div class="pw-strength" id="pw-strength">
            <div class="pw-bars" aria-hidden="true">
              <span class="pw-bar" id="b1"></span><span class="pw-bar" id="b2"></span><span class="pw-bar" id="b3"></span><span class="pw-bar" id="b4"></span>
            </div>
            <p class="pw-label">Password strength: <strong id="pw-text">&mdash;</strong></p>
          </div>
          <p class="field-msg error" id="m-pass">Password must be at least 8 characters.</p>
        </div>

        <div class="field" id="f-pass2">
          <label for="reg-pass2">Confirm password</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-lock"/></svg>
            <input type="password" id="reg-pass2" name="password_confirm" placeholder="Re-enter your password" autocomplete="new-password" required>
            <button type="button" class="field-toggle" aria-label="Show password" onclick="togglePw('reg-pass2', this)">
              <svg aria-hidden="true" width="19" height="19"><use href="#i-eye"/></svg>
            </button>
          </div>
          <p class="field-msg error" id="m-pass2">Passwords do not match.</p>
        </div>

        <div class="check-row">
          <input type="checkbox" id="reg-terms" name="agree_terms" required>
          <label for="reg-terms">I agree to JobberRecruit's <a href="<?= base_url('terms-of-service') ?>">Terms of Service</a> and <a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>.</label>
        </div>

        <button type="submit" class="auth-submit" id="register-btn">
          Create free account <svg aria-hidden="true" width="18" height="18"><use href="#i-arrow-up"/></svg>
        </button>

        <div class="auth-promise">
          <svg aria-hidden="true" width="18" height="18"><use href="#i-shield"/></svg>
          <span>We will <strong>never</strong> ask you to pay for a job, an application, or an interview &mdash; ever.</span>
        </div>
      </form>
      </div>

      <p class="auth-foot-note">Already have an account? <a href="<?= base_url('login') ?>">Log in</a></p>
    </div>
  </section>

  <aside class="auth-brand" aria-label="Why join JobberRecruit">
      <div class="auth-brand-grid" aria-hidden="true"></div>
      <div class="auth-brand-inner">
        <h2>Find a job that <span>actually fits</span> &mdash; free, forever.</h2>
        <p class="auth-brand-lede">Join thousands of Nigerian professionals using JobberRecruit to discover verified roles, build standout CVs, and land their next opportunity.</p>
        <div class="auth-trust">
          <div class="auth-trust-item">
            <svg aria-hidden="true" width="22" height="22"><use href="#i-shield"/></svg>
            <div><strong>Verified employers only</strong><span>Every company is screened before they can post. No scams, no fake jobs.</span></div>
          </div>
          <div class="auth-trust-item">
            <svg aria-hidden="true" width="22" height="22"><use href="#i-lock"/></svg>
            <div><strong>We never charge job seekers</strong><span>Searching and applying for jobs is 100% free &mdash; and always will be.</span></div>
          </div>
          <div class="auth-trust-item">
            <svg aria-hidden="true" width="22" height="22"><use href="#i-spark"/></svg>
            <div><strong>Free career tools</strong><span>ATS-ready CV builder, mock interviews, and salary insights &mdash; all included.</span></div>
          </div>
        </div>
        <div class="auth-brand-stats">
          <div class="auth-brand-stat"><div class="n"><?= $liveJobsCount ?? '12K' ?>+</div><div class="l">Live jobs</div></div>
          <div class="auth-brand-stat"><div class="n"><?= $employerCount ?? '2K' ?>+</div><div class="l">Employers</div></div>
          <div class="auth-brand-stat"><div class="n"><?= $candidateCount ?? '50K' ?>+</div><div class="l">Job seekers</div></div>
        </div>
      </div>
    </aside>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (env('recaptcha_site_key')): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key') ?>"></script>
<?php endif; ?>
<script>
function setRole(role){
  document.getElementById('role-seeker').classList.toggle('selected', role==='seeker');
  document.getElementById('role-seeker').setAttribute('aria-checked', role==='seeker');
  document.getElementById('role-employer').classList.toggle('selected', role==='employer');
  document.getElementById('role-employer').setAttribute('aria-checked', role==='employer');
  document.getElementById('role-input').value = role;
  document.getElementById('role-cards').classList.remove('err');
  document.getElementById('m-role').classList.remove('show');
  var locked=document.getElementById('form-lock');
  if(locked) locked.classList.remove('form-locked');
  var company = document.getElementById('f-company');
  var nameField = document.getElementById('reg-name');
  var sub = document.getElementById('auth-sub-text');
  if(role==='employer'){
    company.style.display='block';
    document.getElementById('reg-company').setAttribute('required','required');
    nameField.placeholder='Your full name';
    sub.innerHTML='Post jobs and reach verified candidates. Already registered? <a href="<?= base_url('login') ?>">Log in here</a>.';
  } else {
    company.style.display='none';
    document.getElementById('reg-company').removeAttribute('required');
    nameField.placeholder='e.g. Chidi Okafor';
    sub.innerHTML='Start finding verified jobs in minutes. Already registered? <a href="<?= base_url('login') ?>">Log in here</a>.';
  }
}

function togglePw(id, btn){
  var input=document.getElementById(id);
  var show = input.type==='password';
  input.type = show ? 'text' : 'password';
  btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
}

function socialAuth(provider){
  var role = document.getElementById('role-input').value;
  window.location.href = '<?= base_url('auth/') ?>' + provider + '?role=' + role;
}

document.addEventListener('DOMContentLoaded', function() {
  // Password strength
  var pwInput = document.getElementById('reg-pass');
  if(pwInput){
    pwInput.addEventListener('input', function(){
      var v=this.value;
      var strength=document.getElementById('pw-strength');
      if(v.length>0){ strength.classList.add('show'); } else { strength.classList.remove('show'); }
      var score=0;
      if(v.length>=8) score++;
      if(/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
      if(/[0-9]/.test(v)) score++;
      if(/[^A-Za-z0-9]/.test(v)) score++;
      var bars=['b1','b2','b3','b4'];
      var labels=['Weak','Fair','Good','Strong'];
      bars.forEach(function(b,i){
        var el=document.getElementById(b);
        el.className='pw-bar';
        if(i<score) el.classList.add('f'+score);
      });
      document.getElementById('pw-text').textContent = score>0 ? labels[score-1] : '\u2014';
    });
  }

  var form = document.getElementById('register-form');
  form.addEventListener('submit', function(e){
    e.preventDefault();
    var ok=true;
    var role=document.getElementById('role-input').value;
    function setErr(fieldId, msgId, cond){
      var input=document.querySelector('#'+fieldId+' input, #'+fieldId+' select');
      var msg=document.getElementById(msgId);
      if(cond){ input.classList.add('err'); msg.classList.add('show'); ok=false; }
      else { input.classList.remove('err'); msg.classList.remove('show'); }
    }
    if(role!=='seeker' && role!=='employer'){
      document.getElementById('role-cards').classList.add('err');
      document.getElementById('m-role').classList.add('show');
      document.getElementById('role-cards').scrollIntoView({behavior:'smooth', block:'center'});
      ok=false;
    }
    setErr('f-name','m-name', document.getElementById('reg-name').value.trim()==='');
    if(role==='employer') setErr('f-company','m-company', document.getElementById('reg-company').value.trim()==='');
    var email=document.getElementById('reg-email').value.trim();
    setErr('f-email','m-email', !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email));
    var phone=document.getElementById('reg-phone').value.replace(/[\s-]/g,'');
    setErr('f-phone','m-phone', !/^(0\d{10}|(\+?234)\d{10})$/.test(phone));
    var pass=document.getElementById('reg-pass').value;
    setErr('f-pass','m-pass', pass.length<8);
    var pass2=document.getElementById('reg-pass2').value;
    setErr('f-pass2','m-pass2', pass2==='' || pass2!==pass);
    if(!document.getElementById('reg-terms').checked) ok=false;
    if(!ok) return;

    var recaptchaKey = '<?= env('recaptcha_site_key') ?>';
    var isValidKey = recaptchaKey && recaptchaKey.trim() !== '' && recaptchaKey !== 'recaptcha_site_key' && recaptchaKey.length > 15;

    if (typeof grecaptcha !== 'undefined' && isValidKey) {
      grecaptcha.ready(function() {
        grecaptcha.execute(recaptchaKey, {action: 'register'}).then(function(token) {
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'g-recaptcha-response';
          input.value = token;
          form.appendChild(input);
          submitForm();
        }).catch(function() {
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'g-recaptcha-response';
          input.value = 'dev-bypass';
          form.appendChild(input);
          submitForm();
        });
      });
    } else {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'g-recaptcha-response';
      input.value = 'dev-bypass';
      form.appendChild(input);
      submitForm();
    }

    function submitForm() {
      var btn = document.getElementById('register-btn');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating Account...';
      var formData = new FormData(form);
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if(data.status === 'success') {
          toastr.success(data.message || 'Registered successfully!');
          setTimeout(function() { window.location.href = data.redirect_url || '<?= base_url('login') ?>'; }, 1200);
        } else {
          if(data.errors) {
            Object.entries(data.errors).forEach(function(e) {
              var input = form.querySelector('[name="'+e[0]+'"]');
              if(input) { input.classList.add('err'); var fb = input.closest('.field').querySelector('.field-msg'); if(fb) { fb.textContent = e[1]; fb.classList.add('show'); } }
            });
          }
          toastr.error(data.message || 'Please fix the errors.');
          btn.disabled = false;
          btn.innerHTML = 'Create free account <svg aria-hidden="true" width="18" height="18"><use href="#i-arrow-up"/></svg>';
        }
      })
      .catch(function() {
        toastr.error('Network error. Please check your connection.');
        btn.disabled = false;
        btn.innerHTML = 'Create free account <svg aria-hidden="true" width="18" height="18"><use href="#i-arrow-up"/></svg>';
      });
    }
  });

  // Live validation
  function mark(fieldId, msgId, bad){
    var input=document.querySelector('#'+fieldId+' input, #'+fieldId+' select');
    var msg=document.getElementById(msgId);
    if(!input||!msg) return;
    if(bad){ input.classList.add('err'); msg.classList.add('show'); }
    else { input.classList.remove('err'); msg.classList.remove('show'); }
  }
  var emailEl=document.getElementById('reg-email');
  var phoneEl=document.getElementById('reg-phone');
  var nameEl=document.getElementById('reg-name');
  var passEl=document.getElementById('reg-pass');
  var pass2El=document.getElementById('reg-pass2');
  var companyEl=document.getElementById('reg-company');
  function vName(){ if(nameEl.value!=='') mark('f-name','m-name', nameEl.value.trim()===''); }
  function vEmail(){ if(emailEl.value!=='') mark('f-email','m-email', !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(emailEl.value.trim())); }
  function vPhone(){ if(phoneEl.value!=='') mark('f-phone','m-phone', !/^(0\d{10}|(\+?234)\d{10})$/.test(phoneEl.value.replace(/[\s-]/g,''))); }
  function vPass(){ if(passEl.value!=='') mark('f-pass','m-pass', passEl.value.length<8); }
  function vPass2(){ if(pass2El.value!=='') mark('f-pass2','m-pass2', pass2El.value!==passEl.value); }
  function vCompany(){ if(companyEl.value!=='') mark('f-company','m-company', companyEl.value.trim()===''); }
  nameEl.addEventListener('blur', vName);
  emailEl.addEventListener('blur', vEmail);
  phoneEl.addEventListener('blur', vPhone);
  passEl.addEventListener('blur', vPass);
  pass2El.addEventListener('blur', vPass2);
  companyEl.addEventListener('blur', vCompany);
  emailEl.addEventListener('input', function(){ if(emailEl.classList.contains('err')) vEmail(); });
  phoneEl.addEventListener('input', function(){ if(phoneEl.classList.contains('err')) vPhone(); });
  nameEl.addEventListener('input', function(){ if(nameEl.classList.contains('err')) vName(); });
  passEl.addEventListener('input', function(){ if(passEl.classList.contains('err')) vPass(); if(pass2El.value!=='') vPass2(); });
  pass2El.addEventListener('input', function(){ if(pass2El.classList.contains('err')) vPass2(); });
  companyEl.addEventListener('input', function(){ if(companyEl.classList.contains('err')) vCompany(); });
});
</script>
<?= $this->endSection() ?>
