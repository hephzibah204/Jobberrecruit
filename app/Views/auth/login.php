<?= $this->extend('auth/base') ?>

<?= $this->section('styles') ?>
<style>
/* ── Auth Layout ── */
.auth-shell{display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 75px)}
.auth-formcol{display:flex;align-items:flex-start;justify-content:center;padding:48px 24px 64px}
.auth-form-inner{width:100%;max-width:440px}
.auth-brand{background:radial-gradient(1200px 600px at 80% -10%,rgba(237,144,32,.18),transparent 55%),radial-gradient(900px 500px at -10% 110%,rgba(8,97,169,.35),transparent 55%),linear-gradient(155deg,#0A2F57 0%,#0A2F57 40%,#064A85 100%);color:#fff;padding:56px 52px;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
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
.auth-alert{display:flex;align-items:flex-start;gap:11px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:13px 15px;margin-bottom:22px}
.auth-alert svg{width:19px;height:19px;color:var(--accent-dark);flex-shrink:0;margin-top:1px}
.auth-alert-body{display:flex;flex-direction:column;gap:2px}
.auth-alert-body strong{font-size:.86rem;font-weight:700;color:#92400e}
.auth-alert-body span{font-size:.82rem;color:#92400e;line-height:1.5}
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
.field input{width:100%;min-height:48px;padding:12px 14px 12px 42px;border:1.5px solid var(--border);border-radius:10px;font-family:'Inter',sans-serif;font-size:16px;color:var(--text);background:#fff;transition:var(--transition)}
.field input::placeholder{color:#9aa6b6}
.field input:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px rgba(8,97,169,.12)}
.field input.err{border-color:var(--danger)}
.field-toggle{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:var(--muted);cursor:pointer;border-radius:8px}
.field-toggle:hover{color:var(--brand);background:var(--brand-light)}
.field-toggle svg{width:19px;height:19px}
.field-msg{font-size:.76rem;margin-top:6px;display:none}
.field-msg.show{display:block}
.field-msg.error{color:var(--danger)}
.opt-row{display:flex;align-items:center;justify-content:flex-end;margin:4px 0 22px;gap:12px;flex-wrap:wrap}
.opt-forgot{font-size:.84rem;font-weight:600}
.auth-submit{width:100%;min-height:50px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-family:'Inter',sans-serif;font-size:.96rem;font-weight:700;cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:9px;box-shadow:0 6px 18px rgba(237,144,32,.28)}
.auth-submit:hover{background:var(--accent-dark)}
.auth-submit:disabled{opacity:.7;cursor:not-allowed}
.auth-submit svg{width:18px;height:18px}
.auth-foot-note{text-align:center;font-size:.84rem;color:var(--muted);margin-top:22px}
.auth-foot-note a{font-weight:700}
.auth-secure{display:flex;align-items:center;justify-content:center;gap:7px;font-size:.76rem;color:var(--muted);margin-top:18px}
.auth-secure svg{width:14px;height:14px;color:var(--success)}
.auth-mobrand{display:none}
@media(max-width:900px){
  .auth-shell{grid-template-columns:1fr;min-height:auto}
  .auth-brand{display:none}
  .auth-mobrand{display:block;background:linear-gradient(135deg,#0A2F57,#064A85);color:#fff;padding:22px 24px;text-align:center}
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
      <h1 class="auth-title" id="auth-h">Log in to your account</h1>
      <p class="auth-sub">Welcome back. Don't have an account? <a href="<?= base_url('register') ?>">Sign up free</a>.</p>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="auth-alert" role="alert">
        <svg aria-hidden="true" width="19" height="19"><use href="#i-bell"/></svg>
        <div class="auth-alert-body">
          <strong>Login Error</strong>
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

      <div class="social-row">
        <button type="button" class="social-btn" onclick="window.location.href='<?= base_url('auth/google') ?>'">
          <svg aria-hidden="true" width="20" height="20"><use href="#i-google"/></svg> Continue with Google
        </button>
        <button type="button" class="social-btn" onclick="window.location.href='<?= base_url('auth/linkedin') ?>'">
          <svg aria-hidden="true" width="20" height="20"><use href="#i-linkedin"/></svg> Continue with LinkedIn
        </button>
      </div>

      <div class="auth-divider">or log in with email</div>

      <form id="login-form" action="<?= base_url('login') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div class="field" id="f-email">
          <label for="login-email">Email address</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-mail"/></svg>
            <input type="email" id="login-email" name="email" placeholder="you@example.com" autocomplete="email" required>
          </div>
          <p class="field-msg error" id="m-email">Please enter a valid email address.</p>
        </div>

        <div class="field" id="f-pass">
          <label for="login-pass">Password</label>
          <div class="field-input">
            <svg class="lead" aria-hidden="true" width="18" height="18"><use href="#i-lock"/></svg>
            <input type="password" id="login-pass" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            <button type="button" class="field-toggle" aria-label="Show password" onclick="togglePw('login-pass', this)">
              <svg aria-hidden="true" width="19" height="19"><use href="#i-eye"/></svg>
            </button>
          </div>
          <p class="field-msg error" id="m-pass">Please enter your password.</p>
        </div>

        <div class="opt-row">
          <a href="<?= base_url('forgot-password') ?>" class="opt-forgot">Forgot password?</a>
        </div>

        <button type="submit" class="auth-submit" id="login-btn">
          Log in <svg aria-hidden="true" width="18" height="18"><use href="#i-arrow-up"/></svg>
        </button>

        <div class="auth-secure">
          <svg aria-hidden="true" width="14" height="14"><use href="#i-lock"/></svg> Your information is encrypted and secure.
        </div>
      </form>

      <p class="auth-foot-note">Don't have an account? <a href="<?= base_url('register') ?>">Create one free</a></p>
    </div>
  </section>

  <aside class="auth-brand" aria-label="Welcome back to JobberRecruit">
      <div class="auth-brand-grid" aria-hidden="true"></div>
      <div class="auth-brand-inner">
        <h2>Welcome back. Your next role is <span>waiting</span>.</h2>
        <p class="auth-brand-lede">Log in to pick up where you left off — track your applications, save jobs, and apply to verified roles across Nigeria.</p>
        <div class="auth-trust">
          <div class="auth-trust-item">
            <svg aria-hidden="true" width="22" height="22"><use href="#i-bookmark"/></svg>
            <div><strong>Your saved jobs &amp; applications</strong><span>Everything you were working on, right where you left it.</span></div>
          </div>
          <div class="auth-trust-item">
            <svg aria-hidden="true" width="22" height="22"><use href="#i-bell"/></svg>
            <div><strong>New matches since you left</strong><span>Fresh roles that fit your profile, posted by verified employers.</span></div>
          </div>
          <div class="auth-trust-item">
            <svg aria-hidden="true" width="22" height="22"><use href="#i-shield"/></svg>
            <div><strong>Safe &amp; secure</strong><span>Verified employers only. We never charge job seekers.</span></div>
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
function togglePw(id, btn){
  var input=document.getElementById(id);
  var show = input.type==='password';
  input.type = show ? 'text' : 'password';
  btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
}

document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('login-form');
  var emailInput = document.getElementById('login-email');
  var passInput = document.getElementById('login-pass');

  emailInput.focus();

  function showSuccess(msg) {
    if (typeof toastr !== 'undefined') {
      toastr.success(msg);
    } else {
      alert(msg);
    }
  }

  function showError(msg) {
    if (typeof toastr !== 'undefined') {
      toastr.error(msg);
    } else {
      alert(msg);
    }
  }

  function submitViaAjax(token) {
    var btn = document.getElementById('login-btn');
    var ogText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Logging in...';

    var formData = new FormData(form);
    formData.append('g-recaptcha-response', token);

    fetch(form.action || window.location.href, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.status === 'success') {
        showSuccess(data.message || 'Login successful!');
        setTimeout(function() {
          window.location.href = data.redirect_url;
        }, 1000);
      } else {
        showError(data.message || 'Login failed. Please try again.');
        btn.disabled = false;
        btn.innerHTML = ogText;
      }
    })
    .catch(function() {
      showError('A network error occurred. Please check your connection.');
      btn.disabled = false;
      btn.innerHTML = ogText;
    });
  }

  form.addEventListener('submit', function(e){
    e.preventDefault();
    var ok=true;
    function setErr(fieldId, msgId, cond){
      var input=document.querySelector('#'+fieldId+' input');
      var msg=document.getElementById(msgId);
      if(cond){ input.classList.add('err'); msg.classList.add('show'); ok=false; }
      else { input.classList.remove('err'); msg.classList.remove('show'); }
    }
    var email=emailInput.value.trim();
    setErr('f-email','m-email', !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email));
    setErr('f-pass','m-pass', passInput.value==='');
    if(!ok) return;

    var recaptchaKey = '<?= env('recaptcha_site_key') ?>';
    var isValidKey = recaptchaKey && recaptchaKey.trim() !== '' && recaptchaKey !== 'recaptcha_site_key' && recaptchaKey.length > 15;

    if (typeof grecaptcha !== 'undefined' && isValidKey) {
      grecaptcha.ready(function() {
        grecaptcha.execute(recaptchaKey, {action: 'login'}).then(function(token) {
          submitViaAjax(token);
        }).catch(function() {
          submitViaAjax('dev-bypass');
        });
      });
    } else {
      submitViaAjax('dev-bypass');
    }
  });

  // Live validation
  function mark(fieldId, msgId, bad){
    var input=document.querySelector('#'+fieldId+' input');
    var msg=document.getElementById(msgId);
    if(!input||!msg) return;
    if(bad){ input.classList.add('err'); msg.classList.add('show'); }
    else { input.classList.remove('err'); msg.classList.remove('show'); }
  }
  function vEmail(){ if(emailInput.value!=='') mark('f-email','m-email', !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(emailInput.value.trim())); }
  emailInput.addEventListener('blur', vEmail);
  emailInput.addEventListener('input', function(){ if(emailInput.classList.contains('err')) vEmail(); });
  passInput.addEventListener('input', function(){ if(passInput.value!=='' && passInput.classList.contains('err')) mark('f-pass','m-pass', false); });
});
</script>
<?= $this->endSection() ?>
