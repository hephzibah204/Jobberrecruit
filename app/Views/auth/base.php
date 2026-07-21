<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="color-scheme" content="light">
<title><?= $title ?? 'JobberRecruit' ?></title>

<meta name="robots" content="noindex, follow">






<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{color-scheme:light;--brand:#0D609E;--brand-dark:#064A85;--brand-deep:#0A2F57;--brand-light:#E6F0F8;--accent:#ED9020;--accent-dark:#C8770E;--text:#141926;--muted:#5b6577;--bg:#f5f7fb;--white:#ffffff;--border:#e2e8f2;--success:#16a34a;--danger:#dc2626;--radius:10px;--shadow:0 2px 14px rgba(10,47,87,.08);--shadow-lg:0 14px 40px rgba(10,47,87,.16);--transition:.18s ease}
html{scroll-behavior:smooth}
body{font-family:'Inter','Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--text);font-size:15px;line-height:1.6;overflow-x:hidden;max-width:100%;-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:100%}
h1,h2,h3,.nav-logo{font-family:'Sora','Inter',sans-serif;letter-spacing:-.02em}
a{color:var(--brand);text-decoration:none}
img{max-width:100%;display:block}
:focus-visible{outline:2px solid var(--brand);outline-offset:2px}
.skip-link{position:absolute;left:-9999px;top:0;background:var(--brand);color:#fff;padding:10px 16px;border-radius:0 0 8px 0;z-index:999}
.skip-link:focus{left:0}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0}

/* minimal auth header */
.auth-head{background:#fff;border-bottom:1px solid var(--border);position:sticky;top:0;z-index:50}
.auth-head-in{max-width:1280px;margin:0 auto;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px}
.auth-logo-img{height:46px;width:auto}

.auth-head-alt{font-size:.86rem;color:var(--muted)}
.auth-head-alt a{font-weight:700}

/* layout */
.auth-shell{display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 75px)}
.auth-formcol{display:flex;align-items:flex-start;justify-content:center;padding:48px 24px 64px}
.auth-form-inner{width:100%;max-width:440px}

/* brand panel */
.auth-brand{background:radial-gradient(1200px 600px at 80% -10%,rgba(237,144,32,.18),transparent 55%),radial-gradient(900px 500px at -10% 110%,rgba(8,97,169,.35),transparent 55%),linear-gradient(155deg,#0A2F57 0%,#0A2F57 40%,#064A85 100%);color:#fff;padding:56px 52px;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.auth-brand-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:44px 44px;mask-image:radial-gradient(circle at 50% 40%,#000,transparent 75%)}
.auth-brand-inner{position:relative;z-index:1;max-width:420px}
.auth-brand h2{font-size:1.9rem;font-weight:800;line-height:1.2;margin-bottom:16px}
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

/* form heading */
.auth-title{font-size:1.7rem;font-weight:800;color:var(--brand-deep);margin-bottom:6px}
.auth-sub{font-size:.92rem;color:var(--muted);margin-bottom:26px}
.auth-sub a{font-weight:700}

/* role toggle */
.role-toggle{display:grid;grid-template-columns:1fr 1fr;gap:8px;background:var(--brand-light);border-radius:12px;padding:5px;margin-bottom:26px}
.role-btn{display:flex;align-items:center;justify-content:center;gap:8px;padding:11px 10px;border:none;background:transparent;border-radius:9px;font-family:'Inter',sans-serif;font-size:.88rem;font-weight:600;color:var(--brand-dark);cursor:pointer;transition:var(--transition)}
.role-btn svg{width:17px;height:17px}
.role-btn.active{background:#fff;color:var(--brand);box-shadow:var(--shadow)}

/* social buttons */
/* form-level alert */
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

/* divider */
.auth-divider{display:flex;align-items:center;gap:14px;margin:22px 0;color:var(--muted);font-size:.8rem}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:var(--border)}

/* form fields */
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
.field-msg.hint{color:var(--muted);display:block}

/* password strength */
.pw-strength{margin-top:10px;display:none}
.pw-strength.show{display:block}
.pw-bars{display:flex;gap:5px;margin-bottom:6px}
.pw-bar{height:5px;flex:1;background:var(--border);border-radius:3px;transition:var(--transition)}
.pw-bar.f1{background:var(--danger)}
.pw-bar.f2{background:var(--accent)}
.pw-bar.f3{background:#eab308}
.pw-bar.f4{background:var(--success)}
.pw-label{font-size:.74rem;color:var(--muted)}
.pw-label strong{font-weight:700}

/* checkbox row */
.check-row{display:flex;align-items:flex-start;gap:10px;margin:6px 0 22px}
.check-row input{width:18px;height:18px;margin-top:2px;accent-color:var(--brand);flex-shrink:0;cursor:pointer}
.check-row label{font-size:.82rem;color:var(--muted);line-height:1.5;cursor:pointer}
.check-row a{font-weight:600}

/* options row (remember + forgot) */
.opt-row{display:flex;align-items:center;justify-content:flex-end;margin:4px 0 22px;gap:12px;flex-wrap:wrap}
.opt-forgot{font-size:.84rem;font-weight:600}

/* submit */
.auth-submit{width:100%;min-height:50px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-family:'Inter',sans-serif;font-size:.96rem;font-weight:700;cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:9px;box-shadow:0 6px 18px rgba(237,144,32,.28)}
.auth-submit:hover{background:var(--accent-dark)}
.auth-submit svg{width:18px;height:18px}

/* footer note under form */
.auth-foot-note{text-align:center;font-size:.84rem;color:var(--muted);margin-top:22px}
.auth-foot-note a{font-weight:700}

/* security badge */
.auth-secure{display:flex;align-items:center;justify-content:center;gap:7px;font-size:.76rem;color:var(--muted);margin-top:18px}
.auth-secure svg{width:14px;height:14px;color:var(--success)}

/* mobile brand strip (shown only on mobile, replaces panel) */
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
  .auth-head-alt{display:none}
  .auth-title{font-size:1.45rem}
  .auth-form-inner{max-width:100%}
  input,select,textarea{font-size:16px!important}
}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
/* ── FOOTER ── */
.container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }
/* ══ FOOTER ══ */
.footer { background: #0A2F57; color: rgba(255,255,255,.78); padding: 56px 0 0; padding-bottom: env(safe-area-inset-bottom, 0); }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 36px; margin-bottom: 44px; }
.footer-logo { display: flex; align-items: center; text-decoration: none; margin-bottom: 14px; }
.footer-logo-img { height: 52px; width: auto; }
.footer-brand p { font-size: .83rem; line-height: 1.75; opacity: .78; margin-bottom: 18px; }
.footer-socials { display: flex; gap: 8px; flex-wrap: wrap; }
.footer-socials a { width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,.09); color: var(--white); display: flex; align-items: center; justify-content: center; transition: var(--transition); text-decoration: none; }
.footer-socials a svg { width: 17px; height: 17px; }
.footer-socials a:hover { background: var(--brand); }
.footer-col h3 { font-family: 'Sora', sans-serif; font-size: .78rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 15px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul a { font-size: .82rem; color: rgba(255,255,255,.68); transition: var(--transition); min-height: 26px; display: inline-flex; align-items: center; }
.footer-col ul a:hover { color: var(--white); text-decoration: none; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding: 18px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; font-size: .78rem; color: rgba(255,255,255,.45); }
.footer-bottom a { color: rgba(255,255,255,.55); }
.footer-bottom a:hover { color: var(--white); }
.footer-links { display: flex; gap: 18px; flex-wrap: wrap; }

/* Back to top */
#btt { position: fixed; bottom: 24px; right: 24px; bottom: max(24px, calc(24px + env(safe-area-inset-bottom, 0px))); width: 46px; height: 46px; border-radius: 50%; background: var(--brand); color: var(--white); border: none; cursor: pointer; box-shadow: var(--shadow-lg); display: none; align-items: center; justify-content: center; z-index: 900; transition: var(--transition); }
#btt svg { width: 20px; height: 20px; }
#btt.show { display: flex; }
#btt:hover { background: var(--brand-dark); }

/* Arrow icon inline */
.ic-arrow-right { display: inline-block; }
@media (max-width: 860px){.footer-grid{grid-template-columns: 1fr 1fr;}}
@media (max-width: 580px){.footer-grid{grid-template-columns: 1fr; gap: 24px;}.footer-bottom{flex-direction: column; text-align: center;}.footer-links{justify-content: center;}}
</style>
<link rel="stylesheet" href="<?= base_url('admin/css/toastr.min.css') ?>">
<?= $this->renderSection('styles') ?>
</head>
<body>

<header class="auth-head">
  <div class="auth-head-in">
    <a href="<?= base_url('') ?>" class="auth-logo" aria-label="JobberRecruit Home">
        <img class="auth-logo-img" src="<?= base_url('images/logo.png') ?>" alt="JobberRecruit" width="232" height="60" loading="eager" decoding="async" fetchpriority="high">
      </a>
    <div class="auth-head-alt">New to JobberRecruit? <a href="<?= base_url('register') ?>"/>Create an account</a></div>
  </div>
</header>

<main>
    <?= $this->renderSection('content') ?>
</main>


<script src="<?= base_url('admin/code.jquery.com/jquery-3.6.1.min.js') ?>"></script>
<script src="<?= base_url('admin/js/toastr.min.js') ?>"></script>
<script>
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 4000
  };
</script>
<?= $this->renderSection('scripts') ?>

</body>
</html>