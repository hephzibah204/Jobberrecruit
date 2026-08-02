<?= $this->extend('templates/base') ?>
<?= $this->section('schema') ?>
<?php
include_once APPPATH . 'Views/partials/schema/job_posting.php';
$jobSchema = jobPostingSchema($job, base_url());
$jobSchema['identifier'] = [
    '@type' => 'PropertyValue',
    'name'  => 'JobberRecruit',
    'value' => 'JR-' . $job->id,
];
?>
<script type="application/ld+json">
<?= json_encode($jobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Jobs', 'item' => base_url('jobs')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $job->title, 'item' => current_url()],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<script>
// Toggle inline application form
function toggleApplyForm() {
  const form = document.getElementById('apply-form-section');
  if (form) {
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
  }
}

// Cancel apply form
document.getElementById('cancelApply')?.addEventListener('click', function() {
  const form = document.getElementById('apply-form-section');
  if (form) {
    form.style.display = 'none';
  }
});

// Submit application
document.getElementById('inlineApplyForm')?.addEventListener('submit', function(e) {
  e.preventDefault();
  
  // Client-side validation
  const fullName = this.querySelector('[name="full_name"]');
  const email = this.querySelector('[name="email"]');
  const phone = this.querySelector('[name="phone"]');
  const resume = this.querySelector('[name="resume"]');
  const referral = this.querySelector('[name="referral"]');
  let isValid = true;
  
  // Clear previous errors
  this.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));
  this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  
  // Validate full name
  if (!fullName.value.trim() || fullName.value.trim().length < 2) {
    isValid = false;
    fullName.classList.add('is-invalid');
    const error = fullName.parentNode.querySelector('.form-error') || createError(fullName, 'Please enter your full name');
    error.classList.add('show');
  }
  
  // Validate email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value)) {
    isValid = false;
    email.classList.add('is-invalid');
    const error = email.parentNode.querySelector('.form-error') || createError(email, 'Please enter a valid email address');
    error.classList.add('show');
  }
  
  // Validate phone
  if (!phone.value.trim()) {
    isValid = false;
    phone.classList.add('is-invalid');
    const error = phone.parentNode.querySelector('.form-error') || createError(phone, 'Please enter your phone number');
    error.classList.add('show');
  }
  
  // Validate resume
  if (!resume.files.length) {
    isValid = false;
    resume.classList.add('is-invalid');
    const error = resume.parentNode.querySelector('.form-error') || createError(resume, 'Please upload your CV');
    error.classList.add('show');
  } else {
    const file = resume.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
    if (file.size > maxSize) {
      isValid = false;
      resume.classList.add('is-invalid');
      const error = resume.parentNode.querySelector('.form-error') || createError(resume, 'File size must be under 5MB');
      error.classList.add('show');
    }
  }
  
  // Validate referral
  if (!referral.value) {
    isValid = false;
    referral.classList.add('is-invalid');
    const error = referral.parentNode.querySelector('.form-error') || createError(referral, 'Please select an option');
    error.classList.add('show');
  }
  
  if (!isValid) {
    this.querySelector('.is-invalid')?.focus();
    return;
  }
  
  const submitBtn = document.getElementById('submitApply');
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Submitting...';

  // Guest (not-logged-in) applicants: the backend reads separate
  // first_name/last_name fields, but this form only collects one
  // full_name field - split it so guest applications don't save blank names.
  const fullNameParts = fullName.value.trim().split(/\s+/);
  this.querySelector('[name="first_name"]')?.remove();
  this.querySelector('[name="last_name"]')?.remove();
  const firstNameInput = document.createElement('input');
  firstNameInput.type = 'hidden';
  firstNameInput.name = 'first_name';
  firstNameInput.value = fullNameParts[0] || '';
  const lastNameInput = document.createElement('input');
  lastNameInput.type = 'hidden';
  lastNameInput.name = 'last_name';
  lastNameInput.value = fullNameParts.slice(1).join(' ') || '';
  this.appendChild(firstNameInput);
  this.appendChild(lastNameInput);

  // NOTE: this previously posted to base_url('jobs/apply'), a route that has
  // never existed - every submission silently 404'd and landed in the
  // .catch() below. The real endpoint is Home::apply_job via
  // POST job/application/(:num), which returns
  // {status:'success'|'error', message, redirect?} as JSON.
  const formData = new FormData(this);
  fetch('<?= base_url('job/application/' . $job->id) ?>', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'error') {
      if (typeof toastr !== 'undefined') {
        toastr.error(data.message || 'Failed to submit application. Please try again.');
      } else {
        console.error(data.message);
      }
      submitBtn.disabled = false;
      submitBtn.textContent = 'Submit Application';
      return;
    }

    if (typeof toastr !== 'undefined') {
      toastr.success(data.message || 'Application submitted successfully!');
    }
    bootstrap.Modal.getInstance(document.getElementById('ModalApplyJobForm'))?.hide();
    this.reset();
    if (data.redirect) {
      window.location.href = data.redirect;
    } else {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Submit Application';
    }
  })
  .catch(error => {
    console.error('Application error:', error);
    if (typeof toastr !== 'undefined') {
      toastr.error('Failed to submit application. Please try again.');
    }
    submitBtn.disabled = false;
    submitBtn.textContent = 'Submit Application';
  });
});

// Helper to create error messages
function createError(input, message) {
  const error = document.createElement('div');
  error.className = 'form-error show';
  error.textContent = message;
  input.parentNode.appendChild(error);
  return error;
}

// Copy link
function copyLink() {
  var linkInput = document.getElementById('share-link-input');
  linkInput.select();
  linkInput.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(linkInput.value).then(function() {
    var btn = document.querySelector('.share-copy-btn');
    var originalText = btn.innerText;
    btn.innerText = 'Copied!';
    setTimeout(function() { btn.innerText = originalText; }, 2000);
  });
}
</script>
<?= $this->endSection() ?>

<?php
// â”€â”€ Application method logic â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
$trackUrl = base_url("job/start-application/{$job->id}");
$defaultLabel = 'Apply Now';
$defaultIcon = 'i-send';

switch ($job->application_method ?? 'form') {
    case 'whatsapp':
        $url = esc($job->whatsapp_link, 'url');
        $label = 'Apply via WhatsApp';
        $icon  = 'i-chat';
        $btnBg = '#25D366';
        $target = '_blank';
        break;
    case 'email':
        $email = esc($job->application_email ?? $job->contact_email);
        $subject = rawurlencode("Application: {$job->title}");
        $url = "mailto:{$email}?subject={$subject}";
        $label = 'Apply via Email';
        $icon  = 'i-mail';
        $btnBg = 'var(--brand)';
        $target = '';
        break;
    case 'external':
        $url = esc($job->external_url, 'url');
        $label = 'Apply on External Site';
        $icon  = 'i-rocket';
        $btnBg = 'var(--accent)';
        $target = '_blank';
        break;
    case 'form':
    default:
        $url = '#ModalApplyJobForm';
        $label = $defaultLabel;
        $icon  = $defaultIcon;
        $btnBg = 'var(--brand)';
        $target = '';
        $isInlineForm = true;
        break;
}
$targetAttr = $target ? "target='_blank' rel='noopener'" : '';

$coName  = (!empty($job->anonymous) || !empty($job->is_anonymous)) ? 'Confidential Employer' : esc($job->employer_name);
$coLogo  = (!empty($job->anonymous) || !empty($job->is_anonymous)) ? base_url('images/favicon.png') : $job->company_logo;
$initials = '';
foreach (explode(' ', $coName) as $p) { $initials .= substr($p, 0, 1); }
$initials = strtoupper(substr($initials, 0, 2));
$salary  = esc($job->salary_range ?? 'Negotiable');
$salary  = $salary ?: 'Negotiable';

$methodLabel = ucfirst($job->application_method ?? 'form');
switch ($job->application_method ?? 'form') {
    case 'whatsapp': $methodLabel = 'WhatsApp'; break;
    case 'email':    $methodLabel = 'Email'; break;
    case 'external': $methodLabel = 'External Link'; break;
    default:         $methodLabel = 'Application Form'; break;
}
$showImage = false;
if (!empty($coLogo)) {
    $relPath = str_replace(base_url(), '', $coLogo);
    if (file_exists(FCPATH . ltrim($relPath, '/')) || strpos($coLogo, 'images/favicon.png') !== false) {
        $showImage = true;
    } elseif (filter_var($coLogo, FILTER_VALIDATE_URL) && strpos($coLogo, base_url()) === false) {
        $showImage = true;
    }
}
?>

<?= $this->section('styles') ?>
<style>

/* â”€â”€ Reset â”€â”€ */


/* â”€â”€ Brand Tokens â”€â”€ */







img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }

/* â”€â”€ Utility â”€â”€ */
.container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }
.section   { padding: 76px 0; }
.sr-only   { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }

.text-center   { text-align: center; }
.mb-4  { margin-bottom: 4px; }
.mt-24 { margin-top: 24px; }
.mt-28 { margin-top: 28px; }

.ic { display: inline-flex; align-items: center; justify-content: center; line-height: 0; }

.section-label {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 5px 13px;
  border-radius: 20px; margin-bottom: 14px;
}
.section-label svg { width: 13px; height: 13px; }
.section-title {
  font-size: clamp(1.6rem, 2.9vw, 2.25rem);
  font-weight: 800; line-height: 1.15; margin-bottom: 12px;
}
.section-title span { color: var(--brand); }
.section-sub { color: var(--muted); font-size: .95rem; max-width: 560px; }

/* Buttons */
















.badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.badge svg { width: 12px; height: 12px; }
.badge-blue  { background: var(--brand-light); color: var(--brand); }
.badge-featured {
  background: var(--accent); color: var(--brand-deep);
  font-size: .68rem; font-weight: 700; padding: 4px 11px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px; letter-spacing: .03em;
}
.badge-featured svg { width: 12px; height: 12px; }

/* Closed position card */
.job-card--fired { opacity: .85; }
.job-card--fired .job-title { color: var(--muted); }
.job-salary--fired { color: var(--muted); text-decoration: line-through; }
.badge-fired {
  background: #fdecec; color: #b91c1c;
  font-size: .68rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px;
}
.badge-fired svg { width: 11px; height: 11px; }

.badge-verified {
  background: var(--brand); color: #fff;
  font-size: .68rem; font-weight: 700; padding: 3px 10px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px;
  border: 1px solid var(--brand);
}
.badge-verified svg { width: 12px; height: 12px; }

.ai-badge--ai    { background: var(--brand-light); color: var(--brand); }

/* Skip link */
.skip-link {
  position: absolute; top: -50px; left: 16px;
  background: var(--brand); color: var(--white);
  padding: 8px 16px; border-radius: 0 0 6px 6px;
  font-weight: 600; z-index: 9999; transition: top .2s;
}
.skip-link:focus { top: 0; }



/* â•â• NAVBAR â•â• */


.nav-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
.nav-logo img { height: 60px; width: auto; display: block; }




.nav-caret { width: 13px; height: 13px; transition: transform var(--transition); }





.mob-group-label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); padding: 4px 0; margin-top: 6px; }
.mob-group 
.nav-actions { display: flex; align-items: center; gap: 8px; }
.nav-actions 
.nav-actions 




/* Signature: a faint grid of "open roles" that drifts upward behind the hero */
.hero-grid-bg {
  position: absolute; inset: 0; pointer-events: none; opacity: .5;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 46px 46px;
  -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
          mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
}
/* Signature: the logo's magnifying-glass-as-person mark, scaled up as a hero graphic */
.hero-motif {
  position: absolute; top: 46%; right: -50px; transform: translateY(-50%);
  width: min(500px, 44vw); height: auto; pointer-events: none; z-index: 0;
  opacity: .55;
}
.hero-motif .ring { animation: motif-float 7s ease-in-out infinite; transform-origin: center; }
.hero-motif .head { animation: motif-bob 7s ease-in-out infinite; transform-origin: center; }
.hero-motif .scan { transform-origin: 50% 50%; }
@keyframes motif-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes motif-bob   { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@media (max-width: 900px) {
  .hero-motif {
    top: -30px; right: -70px; transform: none;
    width: 240px; opacity: .28;
  }
}
@media (max-width: 580px) {
  .hero-motif { top: -24px; right: -80px; width: 190px; opacity: .22; }
}
.hero-inner { position: relative; z-index: 1; padding-bottom: 44px; }
#hero-seeker:not([hidden]), #hero-employer:not([hidden]) { animation: hero-fade .32s ease; }
@keyframes hero-fade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.hero-tabs {
  display: inline-flex; background: rgba(255,255,255,.10);
  border: 1px solid rgba(255,255,255,.18);
  border-radius: 10px; padding: 4px; margin-bottom: 26px;
}
.hero-tabs button {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 18px; border-radius: 7px; border: none;
  background: transparent; color: rgba(255,255,255,.92);
  font-family: 'Inter', sans-serif; font-size: .83rem; font-weight: 600;
  cursor: pointer; transition: var(--transition); min-height: 40px;
  -webkit-tap-highlight-color: transparent;
}
.hero-tabs button:not(.active):hover { background: rgba(255,255,255,.12); }
.hero-tabs button svg { width: 15px; height: 15px; }
.hero-tabs button.active { background: var(--white); color: var(--brand); }
.hero-tag {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .12em;
  text-transform: uppercase; color: var(--accent); margin-bottom: 16px;
}
.hero-tag svg { width: 14px; height: 14px; }
.hero h1 { font-size: clamp(1.9rem, 4.8vw, 3.1rem); font-weight: 800; line-height: 1.1; margin-bottom: 16px; }
.hero h1 em { font-style: normal; color: var(--accent); }
.hero-sub { font-size: 1rem; opacity: .9; max-width: 560px; margin-bottom: 28px; }
.hero-trust { display: flex; flex-wrap: wrap; gap: 18px; font-size: .82rem; margin-bottom: 28px; opacity: .94; }
.hero-trust span { display: flex; align-items: center; gap: 6px; }
.hero-trust svg { width: 15px; height: 15px; color: var(--accent); }

/* Search */
.search-card {
  background: var(--white); border-radius: 12px;
  padding: 10px; display: flex; flex-wrap: wrap; gap: 8px;
  box-shadow: var(--shadow-lg); max-width: 820px;
}
.search-field { position: relative; flex: 1 1 150px; display: flex; align-items: center; }
.search-field svg { position: absolute; left: 12px; width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.search-card input,
.search-card select {
  width: 100%; border: 1px solid var(--border); border-radius: 7px;
  padding: 11px 14px 11px 38px; font-family: 'Inter', sans-serif; font-size: 1rem;
  color: var(--text); background: var(--bg); outline: none;
  appearance: none; -webkit-appearance: none; min-height: 46px;
}
.search-card select { padding-left: 38px; }
.search-card input:focus,
.search-card select:focus { border-color: var(--brand); background: var(--white); }
.search-card > button {
  flex: 0 0 auto; padding: 11px 24px; background: var(--accent); color: var(--brand-deep);
  border: none; border-radius: 7px; font-family: 'Inter', sans-serif;
  font-size: 1rem; font-weight: 600; cursor: pointer; transition: var(--transition);
  min-height: 46px; display: inline-flex; align-items: center; gap: 7px;
  -webkit-tap-highlight-color: transparent; touch-action: manipulation;
}
.search-card > button svg { width: 17px; height: 17px; }
.search-card > button:hover { background: var(--accent-dark); }

.trending { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 18px; font-size: .8rem; }
.trending strong { opacity: .8; letter-spacing: .04em; }
.trending 
.trending 
.hero-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 22px; }
.hero-pills 
.hero-pills a svg { width: 15px; height: 15px; color: var(--accent); }
.hero-pills 

.hero-employer-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 8px; margin-bottom: 28px; }
.hero-employer-h2 { font-size: clamp(1.9rem, 4.8vw, 3.1rem); font-weight: 800; line-height: 1.1; margin-bottom: 16px; }
.hero-employer-h2 em { font-style: normal; color: var(--accent); }

/* â•â• SIGNATURE: LIVE JOB TICKER â•â• */
.ticker {
  position: relative; z-index: 1;
  background: rgba(7,48,79,.55);
  border-top: 1px solid rgba(255,255,255,.12);
  backdrop-filter: blur(6px);
  overflow: hidden; display: flex; align-items: stretch;
  max-width: 100%; width: 100%;
}
.ticker-label {
  flex-shrink: 0; display: flex; align-items: center; gap: 8px;
  background: var(--accent); color: var(--brand-deep);
  font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  padding: 0 16px; z-index: 2;
}
.ticker-dot { width: 9px; height: 9px; border-radius: 50%; background: #fff; box-shadow: 0 0 0 1.5px rgba(7,48,79,.55); animation: pulse 1.5s ease-in-out infinite; }
@keyframes pulse { 0%,100% { transform: scale(1); opacity: 1; } 50% { transform: scale(.72); opacity: .7; } }
.ticker-viewport { flex: 1 1 0%; min-width: 0; overflow: hidden; position: relative; -webkit-mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent); mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent); }
.ticker-track { display: inline-flex; align-items: center; white-space: nowrap; padding: 12px 0; will-change: transform; animation: ticker-scroll 48s linear infinite; }
.ticker-viewport:hover .ticker-track { animation-play-state: paused; }
@keyframes ticker-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.ticker-item {
  display: inline-flex; align-items: center; gap: 8px;
  color: rgba(255,255,255,.92); font-size: .82rem; padding: 0 22px;
  border-right: 1px solid rgba(255,255,255,.1); text-decoration: none;
}
.ticker-item:hover { text-decoration: none; color: var(--white); }
.ticker-item:hover .ticker-role { color: var(--accent); }
.ticker-role { font-weight: 600; }
.ticker-co { opacity: .65; }
.ticker-loc { display: inline-flex; align-items: center; gap: 4px; opacity: .65; font-size: .78rem; }
.ticker-loc svg { width: 11px; height: 11px; }
.ticker-new { background: var(--accent); color: var(--brand-deep); font-size: .64rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; letter-spacing: .04em; }

/* â•â• JOBS â•â• */
.jobs-header { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px; margin-bottom: 6px; }
.jobs-header-ct
.jobs-total { font-size: .82rem; color: var(--muted); }
.jobs-total strong { color: var(--brand); font-weight: 700; }
.jobs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; margin-top: 28px; }
.job-card {
  position: relative;
  background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
  padding: 22px; transition: var(--transition); display: flex; flex-direction: column; gap: 11px;
}
.job-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); }
.job-card--featured { background: linear-gradient(180deg, #fffaf0, #fff); border-color: rgba(245,160,32,.35); border-left: 3px solid var(--accent); }
.job-card--featured:hover { border-color: var(--accent); }
/* Featured badge as a corner ribbon â€” top-left, where nothing else sits â€” so it
   doesn't take a content row and featured/non-featured cards stay aligned. */
.job-card .badge-featured {
  position: absolute; top: -9px; left: 16px; z-index: 2;
  box-shadow: 0 2px 6px rgba(7,48,79,.18);
}
.job-card--featured { padding-top: 24px; }
.job-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.job-logo { width: 44px; height: 44px; border-radius: 9px; background: var(--brand-light); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 700; font-size: .9rem; color: var(--brand); flex-shrink: 0; }
.job-title   { font-size: 1rem; font-weight: 700; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.job-company { font-size: .82rem; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; max-width: 100%; }
.job-company-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }
.verified-check {
  position: relative;
  display: inline-flex; align-items: center; justify-content: center;
  color: var(--brand); flex-shrink: 0;
  background: none; border: none; padding: 0 0 0 1px; margin: 0;
  cursor: pointer; line-height: 0; vertical-align: middle;
  -webkit-tap-highlight-color: transparent;
}
.verified-check svg { width: 14px; height: 14px; pointer-events: none; }
.verified-check:hover { color: var(--brand-dark); }
.verified-tip {
  position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%) translateY(4px);
  background: #ffffff; color: var(--text);
  font-size: .72rem; font-weight: 600; line-height: 1.4; letter-spacing: 0;
  white-space: nowrap; padding: 7px 11px; border-radius: 8px;
  border: 1px solid var(--border);
  box-shadow: 0 8px 24px rgba(7,48,79,.16);
  opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity .16s ease, transform .16s ease; z-index: 40;
  display: inline-flex; align-items: center; gap: 6px;
}
.verified-tip::after {
  content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
  border: 6px solid transparent; border-top-color: #ffffff;
  filter: drop-shadow(0 1px 0 var(--border));
}
.verified-tip svg { width: 13px; height: 13px; color: var(--success); }
.verified-tip strong { font-weight: 700; }
.verified-check.open .verified-tip,
.verified-check:hover .verified-tip {
  opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0);
}
.job-card-top > div:first-child { min-width: 0; }
.job-met
.job-meta span { display: inline-flex; align-items: center; gap: 5px; }
.job-meta svg { width: 13px; height: 13px; color: var(--muted); }
.job-salary  { font-size: .92rem; font-weight: 700; color: var(--accent-dark); }
.job-salary-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.job-actions { display: flex; gap: 8px; margin-top: 4px; }
.job-actions 
.save-btn { background: none; border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 13px; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; font-family: 'Inter', sans-serif; transition: var(--transition); min-height: 44px; -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
.save-btn svg { width: 15px; height: 15px; }
.save-btn:hover { border-color: var(--brand); color: var(--brand); }
.save-btn[data-saved="true"] { color: var(--success); border-color: var(--success); }

.live-count { font-weight: inherit; color: inherit; }

/* â•â• NEWSLETTER â•â• */
.newsletter-band { background: linear-gradient(120deg, var(--brand-light) 0%, #dce9f8 100%); padding: 40px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
.newsletter-inner { display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
.newsletter-text { flex: 1 1 380px; }
.newsletter-text .section-label { margin-bottom: 10px; }
.newsletter-title { font-family: 'Sora', sans-serif; font-size: clamp(1.25rem, 2.2vw, 1.6rem); font-weight: 800; line-height: 1.2; letter-spacing: -.02em; margin-bottom: 8px; }
.newsletter-title span { color: var(--brand); }
.newsletter-sub { color: var(--muted); font-size: .88rem; max-width: 460px; }
.newsletter-form { flex: 0 1 420px; display: flex; gap: 8px; }
.newsletter-field { position: relative; flex: 1; display: flex; align-items: center; }
.newsletter-field svg { position: absolute; left: 13px; width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.newsletter-field input {
  width: 100%; border: 1px solid var(--border); border-radius: 8px;
  padding: 12px 14px 12px 38px; font-family: 'Inter', sans-serif; font-size: 1rem;
  color: var(--text); background: var(--white); outline: none; min-height: 46px;
}
.newsletter-field input:focus { border-color: var(--brand); }
.newsletter-form 
.newsletter-form 

/* â•â• CATEGORIES â•â• */
.cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 28px; }
.cat-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 16px; text-align: center; text-decoration: none; display: block; transition: var(--transition); }
.cat-card:hover { border-color: var(--brand); box-shadow: var(--shadow); transform: translateY(-3px); text-decoration: none; }
.cat-icon { width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 12px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; }
.cat-icon svg { width: 23px; height: 23px; }
.cat-card:hover .cat-icon { background: var(--brand); color: var(--white); }
.cat-name { font-weight: 700; font-size: .88rem; color: var(--text); margin-bottom: 3px; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em; }
.cat-count { font-size: .76rem; color: var(--muted); }

/* â•â• FEATURE CARDS (static) â•â• */
.feat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; margin-top: 28px; }
.feat-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px 20px; display: flex; gap: 14px; align-items: flex-start; }
.feat-icon { width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; }
.feat-icon svg { width: 21px; height: 21px; }
.feat-name { font-weight: 700; font-size: .9rem; margin-bottom: 3px; }
.feat-desc { font-size: .8rem; color: var(--muted); line-height: 1.55; }

/* â•â• HOW IT WORKS â•â• */
.hiw-bg { background: var(--white); }
.steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px; margin-top: 40px; counter-reset: step; }
.step-card { position: relative; border: 1px solid var(--border); border-radius: var(--radius); padding: 30px 24px 26px; background: var(--bg); transition: var(--transition); }
.step-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); background: var(--white); }
.step-num { counter-increment: step; font-family: 'Sora', sans-serif; font-size: 2.4rem; font-weight: 800; color: var(--brand-light); line-height: 1; margin-bottom: 4px; }
.step-num::before { content: counter(step, decimal-leading-zero); }
.step-card:last-child .step-num { color: #fbe6c2; }
.step-ic { width: 40px; height: 40px; border-radius: 10px; background: var(--brand); color: var(--white); display: flex; align-items: center; justify-content: center; margin: -22px 0 14px auto; }
.step-card:last-child .step-ic { background: var(--accent); color: var(--brand-deep); }
.step-ic svg { width: 20px; height: 20px; }
.step-title { font-weight: 700; font-size: .98rem; margin-bottom: 8px; }
.step-desc  { font-size: .83rem; color: var(--muted); line-height: 1.65; }

/* â•â• AI TOOLS â•â• */
.ai-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; margin-top: 18px; }
.ai-card { border-radius: var(--radius); padding: 30px 26px; display: flex; flex-direction: column; }
.ai-card.dark { background: linear-gradient(150deg, var(--brand-deep), var(--brand)); color: var(--white); }
.ai-card.light { background: var(--white); border: 1px solid var(--border); color: var(--text); }
.ai-icon { width: 50px; height: 50px; border-radius: 13px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; background: var(--brand-light); color: var(--brand); }
.ai-card.dark .ai-icon { background: rgba(255,255,255,.12); color: var(--white); }
.ai-icon svg { width: 26px; height: 26px; }
.ai-card h3 { font-size: 1.08rem; font-weight: 700; margin-bottom: 8px; }
.ai-card p  { font-size: .85rem; margin-bottom: 18px; flex: 1; }
.ai-card.dark p { opacity: .86; }
.ai-card.light p { color: var(--muted); }
.ai-badge { display: inline-flex; align-items: center; gap: 5px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; padding: 4px 11px; border-radius: 20px; margin-bottom: 14px; width: fit-content; }
.ai-badge svg { width: 12px; height: 12px; }
.ai-card.dark .ai-badge--ai { background: rgba(255,255,255,.15); color: var(--white); }

/* â•â• TRAINING â•â• */
.training-bg { background: var(--white); }
.course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 18px; margin-top: 28px; }
.course-card { border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; background: var(--white); transition: var(--transition); }
.course-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.course-thumb { height: 110px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.92); }
.course-thumb svg { width: 42px; height: 42px; }
.thumb-blue   { background: linear-gradient(135deg, var(--brand-deep), var(--brand)); }
.thumb-purple { background: linear-gradient(135deg, #064A85, #1d6fb8); }
.thumb-green  { background: linear-gradient(135deg, var(--brand), var(--accent-dark)); }
.thumb-orange { background: linear-gradient(135deg, var(--brand-deep), var(--accent)); }
.course-
.course-title { font-weight: 700; font-size: .9rem; margin-bottom: 6px; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.5em; }
.course-met
.course-meta span { display: inline-flex; align-items: center; gap: 5px; }
.course-meta svg { width: 13px; height: 13px; }
.course-footer { padding: 12px 18px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 10px; }
.course-cert { font-size: .72rem; color: var(--brand); font-weight: 600; display: inline-flex; align-items: center; gap: 5px; align-self: flex-start; background: var(--brand-light); padding: 3px 9px; border-radius: 20px; }
.course-cert svg { width: 13px; height: 13px; }
.course-price-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.course-price { font-size: .9rem; font-weight: 800; }
.course-price--free { color: var(--accent-dark); }
.course-price--paid { color: var(--brand); }

/* â•â• LOCATIONS â•â• */
.loc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-top: 28px; }
.loc-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 14px; text-align: center; text-decoration: none; transition: var(--transition); }
.loc-card:hover { border-color: var(--brand); box-shadow: var(--shadow); transform: translateY(-3px); text-decoration: none; }
.loc-card.featured { background: var(--brand); border-color: var(--brand); }
.loc-ic { width: 38px; height: 38px; margin: 0 auto 8px; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; }
.loc-ic svg { width: 19px; height: 19px; }
.loc-card.featured .loc-ic { background: rgba(255,255,255,.16); color: var(--white); }
.loc-name { font-weight: 700; font-size: .85rem; color: var(--text); }
.loc-count { font-size: .74rem; color: var(--muted); margin-top: 2px; }
.loc-card.featured .loc-name, .loc-card.featured .loc-count { color: var(--white); }

/* â•â• TESTIMONIALS â•â• */
.testi-bg { background: var(--white); }
.testi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 36px; }
.testi-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 26px; }
.testi-stars { color: var(--accent); display: flex; gap: 2px; margin-bottom: 14px; }
.testi-stars svg { width: 16px; height: 16px; }
.testi-text { font-size: .88rem; color: var(--text); line-height: 1.75; margin-bottom: 16px; }
.testi-author { display: flex; align-items: center; gap: 11px; }
.testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--brand); color: var(--white); font-family: 'Sora', sans-serif; font-weight: 600; font-size: .82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.testi-name { font-weight: 700; font-size: .85rem; }
.testi-role { font-size: .74rem; color: var(--muted); }

/* â•â• REFERRAL â•â• */
.referral-band { background: linear-gradient(120deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; border-radius: 14px; padding: 24px 28px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; }
.referral-band-text { display: flex; align-items: center; gap: 18px; flex: 1 1 420px; }
.ref-ic { width: 46px; height: 46px; border-radius: 12px; background: var(--accent); color: var(--brand-deep); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ref-ic svg { width: 22px; height: 22px; }
.ref-ic--lg { width: 54px; height: 54px; }
.ref-ic--lg svg { width: 26px; height: 26px; }
.referral-band-title { font-family: 'Sora', sans-serif; font-size: clamp(1.15rem, 2vw, 1.45rem); font-weight: 800; line-height: 1.25; letter-spacing: -.01em; margin-bottom: 4px; }
.referral-band-title span { color: var(--accent-dark); }
.referral-band-sub { font-size: .86rem; color: var(--muted); max-width: 520px; }
.referral-band-ct

/* â•â• FAQ â•â• */
.faq-bg { background: var(--white); }
.faq-list { max-width: 760px; margin: 32px auto 0; }
details.faq-item { border: 1px solid var(--border); border-radius: var(--radius); background: var(--white); margin-bottom: 8px; overflow: hidden; }
details.faq-item[open] { border-color: var(--brand); }
details.faq-item summary { padding: 18px 22px; font-weight: 600; font-size: .9rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; gap: 12px; user-select: none; min-height: 48px; -webkit-tap-highlight-color: transparent; }
details.faq-item summary::-webkit-details-marker { display: none; }
.faq-chev { flex-shrink: 0; width: 18px; height: 18px; color: var(--brand); transition: transform .2s; }
details.faq-item[open] .faq-chev { transform: rotate(180deg); }
.faq-answer { padding: 0 22px 18px; font-size: .87rem; color: var(--muted); line-height: 1.75; }
.faq-more { text-align: center; margin-top: 24px; }

/* â•â• DUAL CTA â•â• */
.dual-ct
.cta-panel { border-radius: 12px; padding: 44px 32px; }
.cta-panel.blue { background: linear-gradient(150deg, var(--brand-deep), var(--brand)); color: var(--white); }
.cta-panel.blue h2, .cta-panel.blue p, .cta-panel.blue li, .cta-panel.blue strong, .cta-panel.blue a { color: var(--white) !important; }
.cta-panel.light { background: var(--white); color: var(--text); border: 1px solid var(--border); }
.cta-ic { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.cta-panel.blue .cta-ic { background: rgba(255,255,255,.14); color: var(--white); }
.cta-panel.light .cta-ic { background: var(--brand-light); color: var(--brand); }
.cta-ic svg { width: 25px; height: 25px; }
.cta-panel h2 { font-size: 1.35rem; font-weight: 700; margin-bottom: 10px; }
.cta-panel p { font-size: .87rem; margin-bottom: 22px; }
.cta-panel.blue p { opacity: .86; }
.cta-panel.light p { color: var(--muted); }
.cta-list { list-style: none; margin-bottom: 26px; display: flex; flex-direction: column; gap: 9px; }
.cta-list li { display: flex; align-items: center; gap: 9px; font-size: .85rem; }
.cta-tag { font-size: .64rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: var(--accent); color: var(--brand-deep); padding: 1px 7px; border-radius: 20px; margin-left: 2px; }
.cta-list li svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--accent); }

/* â•â• FOOTER â•â• */
.footer { background: var(--brand-deep); color: rgba(255,255,255,.78); padding: 56px 0 0; padding-bottom: env(safe-area-inset-bottom, 0); }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 36px; margin-bottom: 44px; }
.footer-logo { display: flex; align-items: center; text-decoration: none; margin-bottom: 14px; }
.footer-logo-img { height: 52px; width: auto; }
.footer-brand p { font-size: .83rem; line-height: 1.75; opacity: .78; margin-bottom: 18px; }
.footer-socials { display: flex; gap: 8px; flex-wrap: wrap; }
.footer-socials 
.footer-socials a svg { width: 17px; height: 17px; }
.footer-socials 
.footer-col h3 { font-family: 'Sora', sans-serif; font-size: .78rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 15px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul 
.footer-col ul 
.footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding: 18px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; font-size: .78rem; color: rgba(255,255,255,.45); }
.footer-bottom 
.footer-bottom 
.footer-links { display: flex; gap: 18px; flex-wrap: wrap; }

/* Back to top */
#btt { position: fixed; bottom: 24px; right: 24px; bottom: max(24px, calc(24px + env(safe-area-inset-bottom, 0px))); width: 46px; height: 46px; border-radius: 50%; background: var(--brand); color: var(--white); border: none; cursor: pointer; box-shadow: var(--shadow-lg); display: none; align-items: center; justify-content: center; z-index: 900; transition: var(--transition); -webkit-tap-highlight-color: transparent; }
#btt svg { width: 20px; height: 20px; }
#btt.show { display: flex; }
#btt:hover { background: var(--brand-dark); }

/* â•â• RESPONSIVE â•â• */
@media (max-width: 860px) {
  
  
  .dual-ct
  .footer-grid { grid-template-columns: 1fr 1fr; }
  .cat-grid { grid-template-columns: repeat(2, 1fr); }
  .ticker-label { padding: 0 12px; font-size: .68rem; }
}
@media (max-width: 580px) {
  .section { padding: 54px 0; }
  .hero { padding-top: max(56px, calc(56px + env(safe-area-inset-top, 0px))); }
  .container { padding: 0 16px; }
  .footer-grid { grid-template-columns: 1fr; gap: 24px; }
  .search-card { flex-direction: column; }
  .search-field { flex: 1 1 auto; width: 100%; }
  .search-card > button { width: 100%; justify-content: center; }
  .cta-panel { padding: 30px 22px; }
  .referral-band { flex-direction: column; align-items: flex-start; text-align: left; padding: 22px 20px; }
  .referral-band-ct
  .hero-tabs { width: 100%; }
  .hero-tabs button { flex: 1; justify-content: center; padding: 8px 10px; font-size: .78rem; }
  .hero-trust { gap: 12px; font-size: .78rem; }
  .jobs-grid { grid-template-columns: 1fr; }
  .ai-grid { grid-template-columns: 1fr; }
  .newsletter-form { flex: 1 1 100%; width: 100%; }
  .cat-grid, .loc-grid { grid-template-columns: repeat(2, 1fr); }
  .footer-bottom { flex-direction: column; text-align: center; }
  .footer-links { justify-content: center; }
  .ticker-label { padding: 0 10px; font-size: .6rem; letter-spacing: .04em; }
}
@media (max-width: 380px) {
  .container { padding: 0 14px; }
  .nav-logo img { height: 50px; }
  .cat-grid, .loc-grid { grid-template-columns: repeat(2, 1fr); }
  .steps-grid, .course-grid { grid-template-columns: 1fr; }
}
@media (prefers-reduced-motion: reduce) {
  
  .ticker-track { animation: none !important; transform: none !important; }
  .ticker-dot { animation: none !important; }
  .hero-motif .ring, .hero-motif .head { animation: none !important; }
  .hero-motif .scan animateTransform { display: none; }
}
/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   /jobs PAGE â€” page-specific styles (extends the shared homepage system)
   â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */

/* â”€â”€ Compact page hero (shorter than homepage hero) â”€â”€ */
.jobs-hero {
  background:
    radial-gradient(ellipse 60% 70% at 88% 30%, rgba(245,160,32,.16) 0%, transparent 55%),
    linear-gradient(150deg, var(--brand-deep) 0%, var(--brand-dark) 60%, var(--brand) 100%);
  color: var(--white);
  position: relative; overflow: hidden;
  padding: 44px 0;
  padding-top: max(44px, calc(44px + env(safe-area-inset-top, 0px)));
}
.jobs-hero-grid {
  position: absolute; inset: 0; pointer-events: none; opacity: .4;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 42px 42px;
  -webkit-mask-image: radial-gradient(ellipse 90% 90% at 30% 30%, #000 30%, transparent 85%);
          mask-image: radial-gradient(ellipse 90% 90% at 30% 30%, #000 30%, transparent 85%);
}
.jobs-hero-inner { position: relative; z-index: 1; }
.jobs-hero h1 { font-size: clamp(1.7rem, 3.6vw, 2.5rem); font-weight: 800; line-height: 1.12; margin-bottom: 10px; color: #fff; }
.jobs-hero h1 em { font-style: normal; color: var(--accent); }
.jobs-hero p { font-size: .94rem; color: #fff; opacity: .9; max-width: 560px; margin-bottom: 24px; }
.jobs-hero .search-card { margin-bottom: 16px; }
.jobs-hero-actions { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.jobs-hero-alert {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: .84rem; font-weight: 600; color: var(--white);
  background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.22);
  padding: 9px 16px; border-radius: 8px; transition: var(--transition);
  min-height: 40px;
}
.jobs-hero-alert:hover { background: rgba(255,255,255,.2); text-decoration: none; }
.jobs-hero-alert svg { width: 16px; height: 16px; color: var(--accent); }
/* magnifying-glass-person motif, echoing the homepage hero */
.jobs-hero-motif {
  position: absolute; top: 50%; right: -40px; transform: translateY(-50%);
  width: min(330px, 30vw); height: auto; pointer-events: none; z-index: 0; opacity: .5;
}
@media (max-width: 900px) {
  .jobs-hero-motif { top: -20px; right: -60px; transform: none; width: 200px; opacity: .26; }
}
@media (max-width: 580px) {
  .jobs-hero-motif { width: 150px; right: -54px; opacity: .2; }
}
.jobs-hero .breadcrumb { display: flex; align-items: center; gap: 7px; font-size: .76rem; opacity: .82; margin-bottom: 14px; }
.jobs-hero .breadcrumb 
.jobs-hero .breadcrumb 
.jobs-hero .breadcrumb svg { width: 13px; height: 13px; opacity: .6; }

/* â”€â”€ Two-column layout â”€â”€ */
.jobs-layout {
  display: grid; grid-template-columns: 286px 1fr; gap: 28px;
  align-items: start;
  padding: 40px 0 64px;
}

/* â”€â”€ Filter sidebar â”€â”€ */
.filters {
  background: var(--white); border: 1px solid var(--border); border-radius: 14px;
  padding: 0; position: sticky; top: 86px; overflow: hidden;
}
.filters-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 18px; border-bottom: 1px solid var(--border);
  background: var(--brand-light);
}
.filters-head-title { display: flex; align-items: center; gap: 8px; font-family: 'Sora', sans-serif; font-weight: 700; font-size: .95rem; color: var(--brand-deep); }
.filters-head-title svg { width: 16px; height: 16px; color: var(--brand); }
.filters-count { background: var(--brand); color: #fff; font-size: .68rem; font-weight: 700; min-width: 20px; height: 20px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0 6px; }
.filters-
.filter-group { padding: 16px 0; border-bottom: 1px solid var(--border); }
.filter-group:last-of-type { border-bottom: none; }
.filter-label { display: flex; align-items: center; gap: 7px; font-size: .76rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: var(--text); margin-bottom: 11px; }
.filter-label svg { width: 14px; height: 14px; color: var(--muted); }

.filter-input, .filter-select {
  width: 100%; border: 1px solid var(--border); border-radius: 8px;
  padding: 10px 12px; font-family: 'Inter', sans-serif; font-size: .86rem;
  color: var(--text); background: var(--bg); outline: none; min-height: 42px;
  appearance: none; -webkit-appearance: none;
}
.filter-select {
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>");
  background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px;
}
.filter-input:focus, .filter-select:focus { border-color: var(--brand); background: var(--white); }
.filter-input-wrap { position: relative; display: flex; align-items: center; }
.filter-input-wrap svg { position: absolute; left: 11px; width: 15px; height: 15px; color: var(--muted); pointer-events: none; }
.filter-input-wrap .filter-input { padding-left: 34px; }
.filter-input-wrap .filter-clear {
  position: absolute; right: 8px; width: 24px; height: 24px; border: none; background: none;
  color: var(--muted); cursor: pointer; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;
}
.filter-input-wrap .filter-clear:hover { background: var(--bg); color: var(--text); }
.filter-input-wrap .filter-clear svg { position: static; width: 14px; height: 14px; }

/* Checkbox rows (job type) */
.filter-checks { display: flex; flex-direction: column; gap: 2px; }
.filter-check {
  display: flex; align-items: center; gap: 10px; padding: 7px 8px; border-radius: 8px;
  cursor: pointer; font-size: .85rem; color: var(--text); transition: background var(--transition);
  user-select: none; min-height: 38px;
}
.filter-check:hover { background: var(--bg); }
.filter-check input { position: absolute; opacity: 0; width: 0; height: 0; }
.filter-check .box {
  width: 18px; height: 18px; border-radius: 5px; border: 1.6px solid var(--border);
  display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
  transition: var(--transition); background: var(--white);
}
.filter-check .box svg { width: 12px; height: 12px; color: #fff; opacity: 0; }
.filter-check input:checked + .box { background: var(--brand); border-color: var(--brand); }
.filter-check input:checked + .box svg { opacity: 1; }
.filter-check input:focus-visible + .box { outline: 3px solid var(--accent); outline-offset: 2px; }
.filter-check .ct { margin-left: auto; font-size: .76rem; color: var(--muted); }

/* Salary range */
.filter-range { display: flex; align-items: center; gap: 8px; }
.filter-range .filter-input { padding-left: 12px; }
.filter-range span { color: var(--muted); font-size: .8rem; }

.filters-actions { display: flex; flex-direction: column; gap: 8px; padding-top: 16px; }
.filters-actions 

/* Mobile filter toggle (hidden on desktop) */
.filters-toggle { display: none; }

/* â”€â”€ Results column â”€â”€ */
.results-toolbar {
  display: flex; align-items: center; justify-content: space-between; gap: 14px;
  flex-wrap: wrap; margin-bottom: 22px;
}
.results-count { font-family: 'Sora', sans-serif; }
.results-count strong { font-size: 1.15rem; font-weight: 800; color: var(--text); }
.results-count span { display: block; font-family: 'Inter', sans-serif; font-size: .8rem; color: var(--muted); margin-top: 2px; }
.results-tools { display: flex; align-items: center; gap: 12px; }
.results-sort { display: flex; align-items: center; gap: 7px; font-size: .8rem; color: var(--muted); }
.results-sort .filter-select { min-height: 38px; padding-top: 7px; padding-bottom: 7px; width: auto; min-width: 140px; }

/* Grid / list view toggle */
.view-toggle { display: inline-flex; background: var(--bg); border: 1px solid var(--border); border-radius: 9px; padding: 3px; }
.view-toggle button {
  width: 36px; height: 32px; border: none; background: none; border-radius: 6px;
  color: var(--muted); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
  transition: var(--transition); -webkit-tap-highlight-color: transparent;
}
.view-toggle button svg { width: 17px; height: 17px; }
.view-toggle button.active { background: var(--brand); color: #fff; box-shadow: 0 1px 4px rgba(13,96,158,.3); }
.view-toggle button:not(.active):hover { color: var(--text); }

/* Active filter chips */
.active-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
.filter-chip {
  display: inline-flex; align-items: center; gap: 6px; background: var(--brand-light);
  color: var(--brand); font-size: .78rem; font-weight: 600; padding: 5px 8px 5px 12px;
  border-radius: 20px; border: 1px solid rgba(8,97,169,.18);
}
.filter-chip button { width: 18px; height: 18px; border: none; background: rgba(13,96,158,.14); color: var(--brand); border-radius: 50%; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
.filter-chip button svg { width: 11px; height: 11px; }
.filter-chip button:hover { background: var(--brand); color: #fff; }
.active-filters .clear-all { font-size: .78rem; font-weight: 600; color: var(--muted); background: none; border: none; cursor: pointer; padding: 5px 8px; }
.active-filters .clear-all:hover { color: var(--brand); text-decoration: underline; }

/* Promoted badge (sits with featured corner ribbon) */
.badge-promoted {
  background: var(--accent); color: var(--brand-deep);
  font-size: .64rem; font-weight: 800; padding: 3px 9px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 4px; letter-spacing: .04em;
}
.badge-promoted svg { width: 11px; height: 11px; }

/* The results grid reuses .jobs-grid; here it starts at top with no extra margin */
#results.jobs-grid { margin-top: 0; }

/* tag row under the title (FULL-TIME / Verified) */
.job-tags { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.tag-type {
  background: var(--brand-light); color: var(--brand);
  font-size: .66rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
  padding: 3px 9px; border-radius: 5px;
}
.tag-verified { display: inline-flex; align-items: center; gap: 4px; font-size: .74rem; color: var(--brand); font-weight: 600; }
.tag-verified svg { width: 13px; height: 13px; }
.job-salary-amount { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.15rem; color: var(--brand); }
.job-salary-period { font-size: .74rem; color: var(--muted); }

/* â”€â”€ LIST VIEW â”€â”€ */
.jobs-grid.is-list { grid-template-columns: 1fr; gap: 12px; }
.jobs-grid.is-list .job-card {
  display: grid;
  grid-template-columns: 52px 1fr auto;
  grid-template-areas:
    "logo body action";
  align-items: center; gap: 18px; padding: 18px 22px;
}
.jobs-grid.is-list .job-card .job-logo { grid-area: logo; width: 52px; height: 52px; }
.jobs-grid.is-list .job-card .list-
.jobs-grid.is-list .job-card .list-action { grid-area: action; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0; }
.jobs-grid.is-list .job-card .list-action .job-salary-amount { font-size: 1.05rem; }
.jobs-grid.is-list .job-card .list-met
.jobs-grid.is-list .job-card .list-meta span { display: inline-flex; align-items: center; gap: 5px; }
.jobs-grid.is-list .job-card .list-meta svg { width: 13px; height: 13px; }
.jobs-grid.is-list .job-card .list-title-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.jobs-grid.is-list .job-card .job-title { font-size: 1.02rem; -webkit-line-clamp: 1; }
.jobs-grid.is-list .job-card .badge-featured { position: static; box-shadow: none; }
.jobs-grid.is-list .job-card--featured {
  padding-top: 18px;
  border-top: 1px solid rgba(245,160,32,.35);
}
/* In list mode, the absolutely-positioned Featured ribbon is moved inline into
   the title row instead; hide the corner ribbon to avoid overflow. */
.jobs-grid.is-list .job-card > .badge-featured { display: none; }
.jobs-grid.is-list .job-card .list-featured {
  display: inline-flex; align-items: center; gap: 4px;
  background: var(--accent); color: var(--brand-deep);
  font-size: .64rem; font-weight: 800; padding: 3px 9px; border-radius: 20px; letter-spacing: .04em;
}
.jobs-grid:not(.is-list) .job-card .list-featured { display: none; }
.jobs-grid.is-list .list-action-btns { display: flex; gap: 8px; }
/* hide the grid-only / list-only pieces depending on mode.
   GRID mode (default): show .grid-only, hide the list-view direct children
   (the bare .job-logo, .list-body, .list-action that are siblings of .grid-only).
   LIST mode: hide .grid-only, show the list-view children via grid areas. */
.jobs-grid:not(.is-list) .job-card > .job-logo,
.jobs-grid:not(.is-list) .job-card > .list-body,
.jobs-grid:not(.is-list) .job-card > .list-action { display: none !important; }
.jobs-grid.is-list .job-card > .grid-only { display: none !important; }
.jobs-grid.is-list .job-card > .job-logo { display: flex; }
.jobs-grid.is-list .job-card > .list-
.jobs-grid.is-list .job-card > .list-action { display: flex; }

/* Empty state */
.results-empty { text-align: center; padding: 60px 20px; border: 1px dashed var(--border); border-radius: 14px; background: var(--white); }
.results-empty .ic { width: 56px; height: 56px; border-radius: 14px; background: var(--brand-light); color: var(--brand); margin: 0 auto 16px; }
.results-empty .ic svg { width: 26px; height: 26px; }
.results-empty h3 { font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
.results-empty p { font-size: .88rem; color: var(--muted); max-width: 380px; margin: 0 auto 18px; }

/* â”€â”€ Pagination â”€â”€ */
.pagination { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 36px; flex-wrap: wrap; }
.pagination a, .pagination span {
  min-width: 40px; height: 40px; padding: 0 12px; border-radius: 9px;
  border: 1px solid var(--border); background: var(--white); color: var(--text);
  display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 600;
  transition: var(--transition); text-decoration: none;
}
.pagination 
.pagination .current { background: var(--brand); border-color: var(--brand); color: #fff; }
.pagination .ellipsis { border: none; background: none; color: var(--muted); min-width: 24px; }
.pagination .nav-btn svg { width: 16px; height: 16px; }
.pagination .disabled { opacity: .4; pointer-events: none; }

/* â”€â”€ Responsive â”€â”€ */
@media (max-width: 900px) {
  .jobs-layout { grid-template-columns: 1fr; gap: 0; padding-top: 20px; }
  .filters {
    position: fixed; top: 0; left: 0; bottom: 0; width: min(340px, 86vw); z-index: 1200;
    border-radius: 0; transform: translateX(-100%); transition: transform .26s ease;
    overflow-y: auto; box-shadow: var(--shadow-lg);
  }
  .filters.open { transform: translateX(0); }
  .filters-overlay {
    position: fixed; inset: 0; background: rgba(7,48,79,.5); z-index: 1100;
    opacity: 0; visibility: hidden; transition: opacity .2s; backdrop-filter: blur(2px);
  }
  .filters-overlay.open { opacity: 1; visibility: visible; }
  .filters-toggle {
    display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px;
  }
  .filters-head .filters-close { display: inline-flex; }
}
@media (min-width: 901px) {
  .filters-head .filters-close { display: none; }
}
@media (max-width: 560px) {
  .jobs-hero-inner { flex-direction: column; align-items: flex-start; }
  .jobs-hero-ct
  .jobs-hero-cta 
  .results-toolbar { align-items: flex-start; }
  .jobs-grid.is-list .job-card {
    grid-template-columns: 44px 1fr;
    grid-template-areas:
      "logo body"
      "action action";
  }
  .jobs-grid.is-list .job-card .list-action { align-items: stretch; flex-direction: row; justify-content: space-between; margin-top: 4px; }
  .results-sort { display: none; }
}


/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   iOS / mobile AUTO-DARK DEFEAT
   Some mobile browsers (Safari "Smart Invert" / auto dark website tint,
   and FB/IG/WhatsApp in-app webviews) darken backgrounds that rely on
   INHERITED color while leaving explicitly-set text colors alone â€” which
   makes dark navy headings vanish on a forced-dark fill. The meta tag and
   :root color-scheme help, but the reliable defeat is to set EXPLICIT
   backgrounds (and re-assert text color) on the wrapper surfaces, so the
   browser has nothing to override. Belt-and-braces, harmless in light.
   â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
html, 
main, .section, .jobs-layout, .container,
.results-toolbar, .results-count, .pagination {
  background-color: transparent;          /* sit on the forced body bg */
}
.section, main { background-color: var(--bg); }
/* Re-assert text colors so they can't be left dark on a darkened fill */
.results-count strong, .section-title, .job-title, .cta-panel.light h2 { color: var(--text); }
.section-title span { color: var(--brand); }
.results-count span, .section-sub { color: var(--muted); }
/* Cards & key surfaces: explicit, never inherited */
.job-card { background: #ffffff; }
.job-card--featured { background: linear-gradient(180deg, #fff8ee, #fff); }
.filters, .filters-body, .view-toggle button.active ~ *, .pagination a, .pagination span { }
.filters { background: #ffffff; }
.filters-head { background: var(--brand-light); }
.results-empty { background: #ffffff; }

/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   Job detail page styles are now centralized in demo/css/jobber-recruit.css
   â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
.detail-body ul li { display: flex; align-items: flex-start; gap: 10px; font-size: .9rem; }
.detail-body ul li svg { width: 17px; height: 17px; color: var(--success); flex-shrink: 0; margin-top: 2px; }
.detail-body strong { font-weight: 700; color: var(--text); }

/* Tags */
.detail-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 18px; }
.detail-tag {
  font-size: .78rem; color: var(--brand); background: var(--brand-light);
  padding: 5px 13px; border-radius: 20px; font-weight: 500;
}

/* Share row */
.detail-share { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
.detail-share-label { font-size: .82rem; color: var(--muted); font-weight: 600; }
.detail-share 
.detail-share a svg { width: 17px; height: 17px; }
.detail-share 

/* â”€â”€ Apply sidebar â”€â”€ */
.detail-aside { position: sticky; top: 86px; display: flex; flex-direction: column; gap: 18px; }
.apply-card {
  background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 22px;
}
.apply-card--featured { border-color: rgba(245,160,32,.4); border-top: 3px solid var(--accent); }
.apply-salary { font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--accent-dark); line-height: 1.1; }
.apply-salary-period { font-size: .8rem; color: var(--muted); font-weight: 500; margin-top: 2px; }
.apply-deadline {
  display: flex; align-items: center; gap: 8px; font-size: .8rem; color: var(--muted);
  margin: 16px 0; padding: 10px 12px; background: var(--bg); border-radius: 9px;
}
.apply-deadline svg { width: 15px; height: 15px; color: var(--accent-dark); flex-shrink: 0; }
.apply-deadline strong { color: var(--text); font-weight: 600; }
.apply-actions { display: flex; flex-direction: column; gap: 9px; }
.apply-actions 
/* Apply card auth note (guests on internal applications) */
.apply-auth-note {
  display: flex; align-items: center; gap: 6px;
  font-size: .76rem; color: var(--muted);
  text-align: center; justify-content: center;
  margin-top: 8px; line-height: 1.5;
}
.apply-auth-note svg { width: 13px; height: 13px; flex-shrink: 0; color: var(--muted); }
.apply-auth-note 

.apply-quick { border-top: 1px solid var(--border); margin-top: 18px; padding-top: 16px; }
.apply-quick-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; font-size: .82rem; padding: 6px 0; }
.apply-quick-row span:first-child { color: var(--muted); }
.apply-quick-row span:last-child { font-weight: 600; color: var(--text); text-align: right; }

/* Company mini-card */
.company-card { background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
.company-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.company-card-logo {
  width: 46px; height: 46px; border-radius: 10px; background: var(--brand-light);
  display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif;
  font-weight: 700; color: var(--brand); flex-shrink: 0;
}
.company-card-name { font-weight: 700; font-size: .92rem; display: inline-flex; align-items: center; gap: 5px; }
.company-card-name .verified-check { color: var(--brand); }
.company-card-met
.company-card p { font-size: .82rem; color: var(--muted); line-height: 1.65; margin-bottom: 14px; }
.company-card 

/* â”€â”€ Related jobs â”€â”€ */
.related-section { padding: 0 0 64px; }
.related-head { margin-bottom: 22px; }
.related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 18px; }

/* â”€â”€ Responsive â”€â”€ */
@media (max-width: 900px) {
  .detail-layout { grid-template-columns: 1fr; gap: 20px; }
  .detail-aside { position: static; display: flex; flex-direction: column; gap: 18px; }
  /* Mobile reading order: overview â†’ company â†’ apply card (description first, CTA last) */
  .detail-aside .overview-card { order: 1; }
  .detail-aside .company-card  { order: 2; }
  .detail-aside .apply-card    { order: 3; }
}
@media (max-width: 560px) {
  .detail-card { padding: 20px; }
  .detail-head { gap: 14px; }
  .detail-logo { width: 52px; height: 52px; }
  .detail-met
  .detail-meta-item { flex: 1 1 45%; }
}

/* Sticky mobile apply bar */
.mobile-apply-bar {
  display: none;
  position: fixed; left: 0; right: 0; bottom: 0; z-index: 950;
  background: #ffffff; border-top: 1px solid var(--border);
  padding: 12px 16px; padding-bottom: max(12px, calc(12px + env(safe-area-inset-bottom, 0px)));
  box-shadow: 0 -4px 20px rgba(7,48,79,.1);
  align-items: center; gap: 12px;
}
.mobile-apply-bar .mab-salary { font-family: 'Sora', sans-serif; font-weight: 800; color: var(--accent-dark); font-size: 1.05rem; flex-shrink: 0; }
.mobile-apply-bar 
@media (max-width: 900px) {
  .mobile-apply-bar { display: flex; }
  
}


/* â”€â”€ Job Overview panel â”€â”€ */
.overview-card { background:#fff; border:1px solid var(--border); border-radius:14px; padding:20px 22px; }
.overview-title { font-family:'Sora',sans-serif; font-size:1rem; font-weight:700; margin-bottom:14px; }
.overview-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:13px; }
.overview-list li { display:flex; align-items:center; gap:12px; }
.overview-ic { width:32px; height:32px; border-radius:8px; background:var(--brand-light); color:var(--brand); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.overview-ic svg { width:16px; height:16px; }
.overview-kv { display:flex; flex-direction:column; line-height:1.3; }
.overview-k { font-size:.72rem; color:var(--muted); text-transform:uppercase; letter-spacing:.03em; }
.overview-v { font-size:.88rem; font-weight:600; color:var(--text); }
.status-open { display:inline-flex; align-items:center; gap:5px; color:var(--success); font-weight:700; }
.status-open::before { content:""; width:7px; height:7px; border-radius:50%; background:var(--success); display:inline-block; }

/* â”€â”€ Accent button + report/external apply â”€â”€ */


.apply-external svg { width:16px; height:16px; }

/* â”€â”€ Urgently Hiring badge â”€â”€ */
.db-urgent { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
.db-style  { background: var(--brand-light); color: var(--brand); }

/* â”€â”€ Benefits & Perks pills â”€â”€ */
.benefits-pills { display: flex; flex-wrap: wrap; gap: 8px; }
.benefit-pill {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 13px; border-radius: 20px;
  background: var(--brand-light); color: var(--brand);
  font-size: .82rem; font-weight: 600; border: 1px solid var(--border);
}
.benefit-pill svg { width: 14px; height: 14px; }

/* â”€â”€ Job Conditions grid â”€â”€ */
.conditions-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
}
.condition-item {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 14px; background: var(--bg);
  border-radius: var(--radius); border: 1px solid var(--border);
}
.condition-ic {
  width: 32px; height: 32px; border-radius: 8px;
  background: var(--brand-light); color: var(--brand);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.condition-ic svg { width: 15px; height: 15px; }
.condition-kv { display: flex; flex-direction: column; line-height: 1.3; }
.condition-k { font-size: .68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .04em; }
.condition-v { font-size: .85rem; font-weight: 600; color: var(--text); }
@media (max-width: 560px) { .conditions-grid { grid-template-columns: 1fr; } }

/* â”€â”€ Application spots remaining â”€â”€ */
.apply-spots {
  display: flex; align-items: center; gap: 7px;
  font-size: .78rem; color: #7c2d12;
  background: #fff7ed; border: 1px solid #fde3bf;
  border-radius: 8px; padding: 9px 12px; margin-bottom: 12px;
}
.apply-spots svg { width: 14px; height: 14px; flex-shrink: 0; }
.apply-spots strong { font-weight: 700; }

/* â”€â”€ Application requirements (CV / cover letter) â”€â”€ */
.apply-reqs {
  border-top: 1px solid var(--border); margin-top: 14px; padding-top: 14px;
  display: flex; flex-direction: column; gap: 7px;
}
.apply-req-item {
  display: flex; align-items: center; gap: 8px;
  font-size: .8rem; color: var(--text-muted);
}
.apply-req-item svg { width: 14px; height: 14px; color: var(--brand); flex-shrink: 0; }

/* â”€â”€ ATS pre-screening note â”€â”€ */
.ats-notice {
  display: flex; align-items: flex-start; gap: 8px;
  background: var(--brand-light); border: 1px solid var(--border);
  border-radius: 9px; padding: 10px 12px;
  font-size: .78rem; color: var(--brand-deep); line-height: 1.55;
  margin-top: 12px;
}
.ats-notice svg { width: 14px; height: 14px; color: var(--brand); flex-shrink: 0; margin-top: 1px; }




/* â”€â”€ Report modal (single instance) â”€â”€ */
.report-modal { position:fixed; inset:0; z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; }
.report-modal[hidden] { display:none; }
.report-overlay { position:absolute; inset:0; background:rgba(7,48,79,.5); backdrop-filter:blur(2px); }
.report-dialog { position:relative; background:#fff; border-radius:16px; width:100%; max-width:460px; box-shadow:0 24px 60px rgba(7,48,79,.3); overflow:hidden; animation:reportIn .18s ease; }
@keyframes reportIn { from{opacity:0; transform:translateY(10px) scale(.98);} to{opacity:1; transform:none;} }
.report-head { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; background:linear-gradient(180deg,var(--brand-deep),var(--brand)); color:#fff; }
.report-head h3 { font-family:'Sora',sans-serif; font-size:1rem; font-weight:700; display:flex; align-items:center; gap:8px; }
.report-head h3 svg { width:18px; height:18px; color:var(--accent); }
.report-close { background:none; border:none; color:#fff; font-size:1.6rem; line-height:1; cursor:pointer; opacity:.85; padding:0 4px; }
.report-close:hover { opacity:1; }
.report-
.report-intro { font-size:.86rem; color:var(--muted); line-height:1.6; margin-bottom:16px; }
.report-label { display:block; font-size:.82rem; font-weight:700; color:var(--text); margin:0 0 7px; }
.report-select, .report-textare
.report-select:focus, .report-textarea:focus { outline:none; border-color:var(--brand); box-shadow:0 0 0 3px rgba(13,96,158,.12); }
.report-textare
.report-foot { display:flex; justify-content:flex-end; gap:10px; padding:16px 20px; border-top:1px solid var(--border); }


/* â”€â”€ Apply-method variants (external / email / internal) â”€â”€ */
.apply-method-note { font-size:.78rem; color:var(--muted); line-height:1.5; margin-top:10px; display:flex; align-items:flex-start; gap:7px; }
.apply-method-note svg { width:14px; height:14px; flex-shrink:0; margin-top:1px; color:var(--accent-dark); }
.apply-email-box { background:var(--brand-light); border:1px solid #cfe2f3; border-radius:10px; padding:12px 14px; margin-top:10px; }
.apply-email-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; color:var(--muted); font-weight:700; margin-bottom:4px; }
.apply-email-addr { font-size:.92rem; font-weight:700; color:var(--brand); word-break:break-all; }
.apply-email-copy { margin-top:8px; font-size:.76rem; font-weight:600; color:var(--brand); background:none; border:none; cursor:pointer; padding:0; display:inline-flex; align-items:center; gap:5px; }
.apply-email-copy svg { width:13px; height:13px; }

/* â”€â”€ Ad unit â€” labelled, reserved height, distinct from content (policy-safe) â”€â”€ */
.ad-unit { background:#f0f3f8; border:1px solid var(--border); border-radius:12px; padding:12px; text-align:center; overflow:hidden; min-height:140px; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.ad-unit::before { content:"Advertisement"; display:block; font-size:.64rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); margin-bottom:8px; }
.ad-unit .adsbygoogle { width:100%; display:block; }
.detail-ad { margin: 0 0 20px; }

/* â”€â”€ Mobile: trim meta strip to 3 items â”€â”€ */
@media (max-width: 640px) {
  .meta-secondary { display: none; }
}

/* â”€â”€ Mobile: collapse overview list (show 4 key items) â”€â”€ */
@media (max-width: 900px) {
  .overview-list li:not([data-key]) { display: none; }
  .overview-card.expanded .overview-list li { display: flex !important; }
  .overview-toggle {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; margin-top: 12px; padding: 8px 0;
    font-size: .8rem; font-weight: 600; color: var(--brand);
    background: none; border: 1px solid var(--border); border-radius: 8px;
    cursor: pointer; transition: var(--transition);
  }
  .overview-toggle:hover { background: var(--brand-light); }
  .overview-toggle svg { width: 14px; height: 14px; transition: transform .2s; }
  .overview-card.expanded .overview-toggle svg { transform: rotate(180deg); }
}
@media (min-width: 901px) { .overview-toggle { display: none; } }

/* â”€â”€ Mobile: description read-more collapse â”€â”€ */
@media (max-width: 900px) {
  .detail-body.desc-collapsed {
    position: relative; max-height: 260px; overflow: hidden;
  }
  .detail-body.desc-collapsed::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0;
    height: 90px; background: linear-gradient(transparent, var(--white));
    pointer-events: none;
  }
  .desc-expand-btn {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; margin-top: 10px; padding: 11px;
    font-size: .85rem; font-weight: 600; color: var(--brand);
    background: var(--bg); border: 1px solid var(--border);
    border-radius: var(--radius); cursor: pointer; transition: var(--transition);
  }
  .desc-expand-btn:hover { background: var(--brand-light); border-color: var(--brand); }
  .desc-expand-btn svg { width: 15px; height: 15px; }
}
@media (min-width: 901px) { .desc-expand-btn { display: none; } }

/* â”€â”€ Mobile touch targets: Apple HIG minimum 44px â”€â”€ */
@media (max-width: 900px) {
  
  
  
  /* Nav hamburger and save buttons */
  
  .save-btn, 
}


/* Custom adjustments */
.job-logo-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 8px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Reusable SVG icon sprite -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></symbol>
    <symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M5 12H1M23 12h-4M6.3 6.3 3.5 3.5M20.5 20.5l-2.8-2.8M17.7 6.3l2.8-2.8M3.5 20.5l2.8-2.8"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M9 14a5 5 0 1 1 6 0c-.7.5-1 1.2-1 2H10c0-.8-.3-1.5-1-2Z"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></symbol>
    <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="i-chev-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-youtube" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c2 .6 9.4.6 9.4.6s7.4 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.5V8.5L15.8 12l-6.2 3.5Z"/></symbol>
    <symbol id="i-upload" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
    <symbol id="i-mega" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"/><path d="M15 8a4 4 0 0 1 0 8M18 5a8 8 0 0 1 0 14"/></symbol>
    <symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="i-verified-disc" viewBox="0 0 24 24" fill="currentColor"><path d="m22.12 11.21-1.63-2.02a1.35 1.35 0 0 1-.29-1.25l.5-2.52a1.35 1.35 0 0 0-1.63-1.6l-2.48.56a1.35 1.35 0 0 1-1.23-.28L13.3 2.5a1.35 1.35 0 0 0-2.26 0L9 4.09a1.35 1.35 0 0 1-1.23.28l-2.48-.56a1.35 1.35 0 0 0-1.63 1.6l.5 2.52a1.35 1.35 0 0 1-.29 1.25l-1.63 2.02a1.35 1.35 0 0 0 0 1.76l1.63 2.02c.3.36.4.85.29 1.25l-.5 2.52a1.35 1.35 0 0 0 1.63 1.6l2.48-.56c.41-.09.83.01 1.23.28l2.06 1.59a1.35 1.35 0 0 0 2.26 0l2.06-1.59a1.35 1.35 0 0 1 1.23-.28l2.48.56a1.35 1.35 0 0 0 1.63-1.6l-.5-2.52a1.35 1.35 0 0 1 .29-1.25l1.63-2.02a1.35 1.35 0 0 0 0-1.76Zm-11.83 4.1-3.29-3.29 1.41-1.41 1.88 1.88 4.67-4.67 1.41 1.41-6.08 6.08Z"/></symbol>
  </defs>
</svg>
<main id="main-content">


  <!-- HERO STRIP -->
  <section class="detail-hero" aria-label="Job details">
    <span class="detail-hero-grid" aria-hidden="true"></span>
    <div class="container">
      <nav class="detail-breadcrumb" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg>
        <a href="/jobs">Find Jobs</a>
        <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg>
        <span class="current"><?= esc($job->title) ?></span>
      </nav>
    </div>
  </section>

  <div class="container">
    <div class="detail-layout">

      <!-- MAIN COLUMN -->
      <div class="detail-main">

        <!-- Job header -->
        <div class="detail-card">
          <div class="detail-head">
            <div class="detail-logo" aria-hidden="true" style="overflow:hidden; display:flex; align-items:center; justify-content:center;">
              <?php if ($showImage): ?>
                <img src="<?= esc($coLogo) ?>" alt="<?= esc($coName) ?> logo" class="job-logo-img">
              <?php else: ?>
                <?= esc($initials) ?>
              <?php endif; ?>
            </div>
            <div class="detail-head-body">
              <h1 class="detail-title"><?= esc($job->title) ?></h1>
              <div class="detail-company">
                at <strong><?= esc($coName) ?></strong>
                <?php if (empty($job->anonymous) && empty($job->is_anonymous) && !empty($job->is_verified)): ?>
                  <button type="button" class="verified-check" aria-label="Verified employer â€” tap for details"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span></button>
                <?php endif; ?>
              </div>
              <div class="detail-badges">
                <?php if ($job->is_featured || ($job->featured_until && strtotime($job->featured_until) > time())): ?>
                  <span class="detail-badge db-urgent"><svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 4.1 13H11l-2 9 10.9-11H13l2-9z"/></svg> Urgently Hiring</span>
                  <span class="detail-badge db-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
                <?php endif; ?>
                <span class="detail-badge db-type"><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(ucfirst($job->job_type)) ?></span>
                <span class="detail-badge db-style"><svg aria-hidden="true"><use href="#i-globe"/></svg> <?= esc(ucfirst($job->location_type ?? 'On-site')) ?></span>
              </div>
            </div>
          </div>
          <div class="detail-meta">
            <div class="detail-meta-item">
              <span class="detail-meta-ic"><svg aria-hidden="true"><use href="#i-pin"/></svg></span>
              <span><span class="detail-meta-label">Location</span><br><span class="detail-meta-value"><?= esc($job->state_name ?? 'Nigeria') ?></span></span>
            </div>
            <div class="detail-meta-item">
              <span class="detail-meta-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></span>
              <span><span class="detail-meta-label">Job type</span><br><span class="detail-meta-value"><?= esc(ucfirst($job->job_type)) ?></span></span>
            </div>
            <div class="detail-meta-item">
              <span class="detail-meta-ic"><svg aria-hidden="true"><use href="#i-chart"/></svg></span>
              <span><span class="detail-meta-label">Experience</span><br><span class="detail-meta-value"><?= esc(ucfirst($job->experience_level ?: 'Not specified')) ?></span></span>
            </div>
            <div class="detail-meta-item meta-secondary">
              <span class="detail-meta-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></span>
              <span><span class="detail-meta-label">Work style</span><br><span class="detail-meta-value"><?= esc(ucfirst($job->location_type ?? 'On-site')) ?></span></span>
            </div>
            <?php if (!empty($job->num_vacancies)): ?>
            <div class="detail-meta-item meta-secondary">
              <span class="detail-meta-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span>
              <span><span class="detail-meta-label">Vacancies</span><br><span class="detail-meta-value"><?= esc($job->num_vacancies) ?> <?= is_numeric($job->num_vacancies) && (int)$job->num_vacancies === 1 ? 'opening' : 'openings' ?></span></span>
            </div>
            <?php endif; ?>
            <div class="detail-meta-item">
              <span class="detail-meta-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span>
              <span><span class="detail-meta-label">Posted</span><br><span class="detail-meta-value"><?= date('d M, Y', strtotime($job->created_at)) ?></span></span>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="detail-card">
          <h2 class="detail-section-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> Job description</h2>
          <div class="detail-body desc-collapsed" id="detail-body">
            <?= $job->description ?>
            
            <?php if (!empty($job->requirements)): ?>
              <h3>Requirements</h3>
              <?= $job->requirements ?>
            <?php endif; ?>
            
            <?php if (!empty($job->application)): ?>
              <h3>Application Guidelines</h3>
              <?= $job->application ?>
            <?php endif; ?>
          </div>
          <!-- Read-more button â€” hidden on desktop, shown on mobile only -->
          <button class="desc-expand-btn" id="desc-expand-btn" onclick="expandDescription()">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            Read full description
          </button>
          
          <?php if (!empty($job->skills)): ?>
            <?php 
              $skillsList = is_string($job->skills) ? explode(',', $job->skills) : $job->skills;
            ?>
            <div class="detail-tags" aria-label="Skills">
              <?php foreach ($skillsList as $sk): ?>
                <?php if (trim($sk) !== ''): ?>
                  <span class="detail-tag"><?= esc(trim($sk)) ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Share -->
        <div class="detail-card">
          <h2 class="detail-section-title"><svg aria-hidden="true"><use href="#i-mega"/></svg> Share this job</h2>
          <div class="detail-share">
            <span class="detail-share-label">Help someone find this role:</span>
            <?php 
              $shareUrl = current_url();
              $shareText = rawurlencode("{$job->title} at {$coName} {$shareUrl}");
            ?>
            <a href="https://wa.me/?text=<?= $shareText ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.52 3.449C18.24 1.245 15.24 0 12.05 0 5.495 0 .153 5.341.151 11.893c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.896-5.341 11.898-11.893 0-3.181-1.24-6.171-3.428-8.358v.106z"/></svg></a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($shareUrl) ?>&text=<?= urlencode($job->title . ' at ' . $coName) ?>" target="_blank" rel="noopener" aria-label="Share on X"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932L18.901 1.153Z"/></svg></a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($shareUrl) ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.137 1.445-2.137 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 1 1 0-4.124 2.062 2.062 0 0 1 0 4.124zM7.119 20.452H3.555V9h3.564v11.452z"/></svg></a>
          </div>
        </div>

        <?php if (!empty($job->job_schedule) || !empty($job->working_hours) || !empty($job->accommodation) || !empty($job->probation_period)): ?>
        <!-- Job Conditions â€” schedule, hours, accommodation, probation -->
        <div class="detail-card">
          <h2 class="detail-section-title"><svg aria-hidden="true"><use href="#i-calendar"/></svg> Job Conditions</h2>
          <div class="conditions-grid">
            <?php if (!empty($job->job_schedule)): ?>
            <div class="condition-item">
              <span class="condition-ic"><svg aria-hidden="true"><use href="#i-calendar"/></svg></span>
              <span class="condition-kv"><span class="condition-k">Work schedule</span><span class="condition-v"><?= esc($job->job_schedule) ?></span></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($job->working_hours)): ?>
            <div class="condition-item">
              <span class="condition-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span>
              <span class="condition-kv"><span class="condition-k">Working hours</span><span class="condition-v"><?= esc($job->working_hours) ?></span></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($job->accommodation)): ?>
            <div class="condition-item">
              <span class="condition-ic"><svg aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></span>
              <span class="condition-kv"><span class="condition-k">Accommodation</span><span class="condition-v"><?= esc($job->accommodation) ?></span></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($job->probation_period)): ?>
            <div class="condition-item">
              <span class="condition-ic"><svg aria-hidden="true" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
              <span class="condition-kv"><span class="condition-k">Probation period</span><span class="condition-v"><?= esc($job->probation_period) ?></span></span>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>

      <!-- APPLY SIDEBAR -->
      <aside class="detail-aside" aria-label="Apply">
        <div class="apply-card apply-card--featured">
          <div class="apply-salary"><?= esc($salary) ?></div>
          <div class="apply-salary-period">Salary</div>
          
          <!-- Urgently hiring strip (show if urgent/featured) -->
          <?php if ($job->is_featured || ($job->featured_until && strtotime($job->featured_until) > time())): ?>
            <div style="display:flex;align-items:center;gap:7px;font-size:.78rem;font-weight:700;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:8px 12px;margin-bottom:12px">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2 4.1 13H11l-2 9 10.9-11H13l2-9z"/></svg> Urgently hiring â€” apply before deadline
            </div>
          <?php endif; ?>

          <?php 
            // Calculate remaining spots / limit display dynamically
            // Note: Since application limit was saved in job settings, we can read application count from $application_count if available.
            // Let's check if $application_count is set, otherwise default to a reasonable computation or hide it.
            $appCount = isset($application_count) ? (int)$application_count : 0;
            $appLimit = !empty($job->application_limit) ? (int)$job->application_limit : 0;
          ?>
          <?php if ($appLimit > 0): ?>
            <?php 
              $spotsRemaining = max(0, $appLimit - $appCount); 
            ?>
            <div style="display:flex;align-items:center;gap:7px;font-size:.78rem;font-weight:700;color:#7c2d12;background:#fff7ed;border:1px solid #ffedd5;border-radius:8px;padding:8px 12px;margin-bottom:12px">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <?= $spotsRemaining ?> spots remaining â€” closes at <?= $appLimit ?> applications
            </div>
          <?php endif; ?>

          <?php if ($job->application_deadline): ?>
            <div class="apply-deadline">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="width:16px;height:16px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>Apply before <strong><?= date('d M Y', strtotime($job->application_deadline)) ?></strong></span>
            </div>
          <?php endif; ?>

          <div class="apply-actions">
            <?php 
              // check application access settings
              $requiresAuth = isset($job->application_access) && $job->application_access === 'authenticated';
            ?>
            <?php if (auth()->loggedIn() || !$requiresAuth): ?>
              <?php if (($job->application_method ?? 'form') === 'form'): ?>
                  <button class="btn btn-primary btn-lg apply-external" style="width:100%;justify-content:center" data-bs-toggle="modal" data-bs-target="#ModalApplyJobForm">
                  <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Quick apply
                </button>
              <?php else: ?>
                <a href="<?= $url ?>" class="btn btn-primary btn-lg apply-external" <?= $targetAttr ?> style="width:100%;justify-content:center">
                  <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> <?= $label ?>
                </a>
              <?php endif; ?>
            <?php else: ?>
              <a href="/login?redirect=/jobs/<?= $job->slug ?>" class="btn btn-primary btn-lg apply-external" style="width:100%;justify-content:center">
                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Log in to apply
              </a>
              <?php if ($requiresAuth): ?>
                <p class="apply-auth-note">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:14px;height:14px"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  Free account required Â·
                  <a href="/register?redirect=/jobs/<?= $job->slug ?>">Create one in 2 minutes</a>
                </p>
              <?php endif; ?>
            <?php endif; ?>

            <button id="saveJobBtn" data-job-id="<?= $job->id ?>" class="save-btn btn btn-outline <?= $isSaved ? 'saved' : '' ?>" aria-label="Save job" style="width:100%;justify-content:center;gap:8px">
              <svg viewBox="0 0 24 24" fill="<?= $isSaved ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" aria-hidden="true" style="width:16px;height:16px"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
              <?= $isSaved ? 'Saved' : 'Save job' ?>
            </button>
            <button type="button" class="btn-report btn btn-outline" data-bs-toggle="modal" data-bs-target="#reportJobModal" style="width:100%;justify-content:center;display:inline-flex;align-items:center;gap:8px;margin-top:8px">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
              Report this job
            </button>
          </div>

          <!-- What candidates need to submit -->
          <div class="apply-reqs">
            <?php if (!isset($job->require_cv) || $job->require_cv): ?>
              <div class="apply-req-item"><svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> CV / Resume required</div>
            <?php endif; ?>
            <?php if (!empty($job->require_cover_letter)): ?>
              <div class="apply-req-item"><svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Cover letter required</div>
            <?php endif; ?>
          </div>
          
          <!-- ATS pre-screening notice -->
          <?php if (!empty($questions)): ?>
            <div class="ats-notice" style="display:flex;align-items:start;gap:8px;background:#f0fdf4;border:1px solid #bbf7d0;padding:10px;border-radius:8px;font-size:.8rem;color:#166534">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-top:2px"><rect x="3" y="11" width="18" height="11" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/></svg>
              <span>This employer uses <strong>pre-screening questions</strong>. You'll be asked a few short questions when you apply â€” this helps your application stand out.</span>
            </div>
          <?php endif; ?>
        </div>

        <!-- JOB OVERVIEW â€” structured facts panel (matches live page) -->
        <div class="overview-card">
          <h2 class="overview-title">Job Overview</h2>
          <ul class="overview-list">
            <li><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span><span class="overview-kv"><span class="overview-k">Posted</span><span class="overview-v"><?= date('d M, Y', strtotime($job->created_at)) ?></span></span></li>
            <li><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span><span class="overview-kv"><span class="overview-k">Status</span><span class="overview-v"><span class="status-open"><?= esc(ucfirst($job->status)) ?></span></span></span></li>
            <li><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></span><span class="overview-kv"><span class="overview-k">Level</span><span class="overview-v"><?= esc(ucfirst($job->experience_level ?: 'Not specified')) ?></span></span></li>
            <li data-key="true"><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span><span class="overview-kv"><span class="overview-k">Salary</span><span class="overview-v"><?= esc($salary) ?></span></span></li>
            <li><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg></span><span class="overview-kv"><span class="overview-k">Education</span><span class="overview-v"><?= esc(ucfirst($job->education_level ?: 'Not specified')) ?></span></span></li>
            <li data-key="true"><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="overview-kv"><span class="overview-k">Location</span><span class="overview-v"><?= esc($job->state_name ?? 'Nigeria') ?></span></span></li>
            <li data-key="true"><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span><span class="overview-kv"><span class="overview-k">Job type</span><span class="overview-v"><?= esc(ucfirst($job->job_type)) ?></span></span></li>
            <li data-key="true"><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/></svg></span><span class="overview-kv"><span class="overview-k">Work style</span><span class="overview-v"><?= esc(ucfirst($job->location_type ?? 'On-site')) ?></span></span></li>
            <li><span class="overview-ic"><svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span><span class="overview-kv"><span class="overview-k">Apply via</span><span class="overview-v"><?= $methodLabel ?></span></span></li>
          </ul>
        </div>

        <!-- Dynamic Company Card -->
        <div class="company-card">
          <div class="company-card-head">
            <div class="company-card-logo" aria-hidden="true" style="width:48px;height:48px;border-radius:8px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:700">
              <?php if ($showImage): ?>
                <img src="<?= esc($coLogo) ?>" alt="<?= esc($coName) ?> logo" style="width:100%;height:100%;object-fit:contain;border-radius:inherit;">
              <?php else: ?>
                <?= esc($initials) ?>
              <?php endif; ?>
            </div>
            <div>
              <div class="company-card-name">
                <?= esc($coName) ?>
                <?php if (empty($job->anonymous) && empty($job->is_anonymous) && !empty($job->is_verified)): ?>
                  <button type="button" class="verified-check" aria-label="Verified employer" style="display:inline-flex;align-items:center;background:none;border:none;padding:0;color:var(--brand);vertical-align:middle">
                    <svg viewBox="0 0 24 24" aria-hidden="true" style="width:14px;height:14px"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  </button>
                <?php endif; ?>
              </div>
              <div class="company-card-meta"><?= esc($job->industry_name ?? 'General Business') ?> Â· <?= $employer_job_count ?> open roles</div>
            </div>
          </div>
          <p><?= esc($coName) ?> is a verified employer on JobberRecruit.</p>
          <?php if (empty($job->anonymous) && empty($job->is_anonymous)): ?>
            <a href="<?= base_url('employer/' . $job->employer_id) ?>" class="btn btn-outline btn-sm">View company profile</a>
          <?php endif; ?>
        </div>
      </aside>

    </div>
  </div>

  <!-- Dynamic Related Jobs -->
  <?php if (!empty($related_jobs)): ?>
  <section class="related-section" aria-labelledby="related-h" style="padding-top:40px">
    <div class="container">
      <div class="related-head" style="margin-bottom:20px">
        <h2 class="section-title" id="related-h">Similar <span>jobs</span></h2>
        <p class="section-sub">Other verified roles you might be interested in.</p>
      </div>
      <div class="related-grid">
        <?php foreach ($related_jobs as $related): ?>
          <?php 
            $relCoName = (!empty($related->anonymous) || !empty($related->is_anonymous)) ? 'Confidential Employer' : esc($related->company_name);
            $relInitials = '';
            foreach (explode(' ', $relCoName) as $p) { $relInitials .= substr($p, 0, 1); }
            $relInitials = strtoupper(substr($relInitials, 0, 2));
            $relShowImage = false;
            if (!empty($related->logo)) {
                $relShowImage = true;
            }
          ?>
          <article class="job-card" aria-label="<?= esc($related->title) ?> at <?= esc($relCoName) ?>">
            <div class="job-card-top" style="display:flex;justify-content:space-between;align-items:start;gap:12px">
              <div>
                <h3 class="job-title" title="<?= esc($related->title) ?>" style="font-size:1rem;margin:0 0 4px">
                  <a href="<?= base_url('jobs/' . $related->slug) ?>" style="color:inherit;text-decoration:none;font-weight:700"><?= esc($related->title) ?></a>
                </h3>
                <div class="job-company" style="font-size:.82rem;color:var(--muted)">
                  <span class="job-company-name"><?= esc($relCoName) ?></span>
                  <?php if (empty($related->anonymous) && empty($related->is_anonymous) && !empty($related->is_verified)): ?>
                    <span style="color:var(--brand);margin-left:4px">
                      <svg viewBox="0 0 24 24" aria-hidden="true" style="width:12px;height:12px;display:inline-block"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="job-logo" style="width:40px;height:40px;border-radius:6px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;overflow:hidden">
                <?php if ($relShowImage): ?>
                  <img src="<?= esc($related->logo) ?>" alt="<?= esc($relCoName) ?> logo" style="width:100%;height:100%;object-fit:contain">
                <?php else: ?>
                  <?= esc($relInitials) ?>
                <?php endif; ?>
              </div>
            </div>
            <div class="job-meta" style="display:flex;gap:10px;font-size:.78rem;color:var(--muted);margin:8px 0">
              <span><?= esc($related->state_name ?? 'Nigeria') ?></span>
              <span>â€¢</span>
              <span><?= esc(ucfirst($related->job_type)) ?></span>
            </div>
            <div class="job-salary-row" style="margin-top:auto">
              <span class="job-salary" style="font-weight:700;color:var(--brand);font-size:.88rem">
                <?php if ($related->salary_type === 'range'): ?>
                  â‚¦<?= number_format($related->salary) ?> - â‚¦<?= number_format($related->salary_max) ?>
                <?php elseif ($related->salary_type === 'fixed'): ?>
                  â‚¦<?= number_format($related->salary) ?>
                <?php else: ?>
                  Negotiable
                <?php endif; ?>
              </span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if (($job->application_method ?? 'form') === 'form'): ?>
  <!-- Apply Job Modal -->
  <div class="modal fade" id="ModalApplyJobForm" tabindex="-1" aria-labelledby="ModalApplyJobFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content" style="border:none;border-radius:12px;box-shadow:0 14px 40px rgba(10,47,87,.16)">
        <div class="modal-header" style="background:var(--brand-light);color:var(--brand);padding:18px 22px">
          <h5 class="modal-title fw-bold" id="ModalApplyJobFormLabel">Apply for this Job</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="inlineApplyForm" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="job_id" value="<?= $job->id ?>">
          <div class="modal-body" style="padding:22px">
            <p style="color:var(--muted);font-size:.87rem;margin-bottom:16px">Complete your application to be considered for this position. All fields marked with * are required.</p>
            <div class="form-row">
              <div class="form-group mb-3" style="flex:1">
                <label class="form-label" style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem" for="apply_name">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="apply_name" name="full_name" required class="form-control" placeholder="John Doe" autocomplete="name" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:.9rem;background:var(--bg);color:var(--text)">
                <div class="form-error"></div>
              </div>
              <div class="form-group mb-3" style="flex:1">
                <label class="form-label" style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem" for="apply_email">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="apply_email" name="email" required class="form-control" placeholder="john@example.com" autocomplete="email" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:.9rem;background:var(--bg);color:var(--text)">
                <div class="form-error"></div>
              </div>
            </div>
            <div class="form-group mb-3">
              <label class="form-label" style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem" for="apply_phone">Phone Number <span class="text-danger">*</span></label>
              <input type="tel" id="apply_phone" name="phone" required class="form-control" placeholder="+234 800 000 0000" autocomplete="tel" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:.9rem;background:var(--bg);color:var(--text)">
              <div class="form-error"></div>
            </div>
            <div class="form-group mb-3">
              <label class="form-label" style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem" for="apply_resume">Resume/CV <span class="text-danger">*</span></label>
              <input type="file" id="apply_resume" name="resume" required accept=".pdf,.doc,.docx,.txt" class="form-control" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:.9rem;background:var(--bg);color:var(--text)">
              <div class="form-text" style="font-size:.8rem;color:var(--muted);margin-top:4px">Upload your CV in PDF, DOC, or DOCX format (max 5MB)</div>
              <div class="form-error"></div>
            </div>
            <div class="form-group mb-3">
              <label class="form-label" style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem" for="apply_cover">Cover Letter (Optional)</label>
              <textarea id="apply_cover" name="cover_letter" rows="5" class="form-control" placeholder="Tell us about yourself..." style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:.9rem;background:var(--bg);color:var(--text);resize:vertical"></textarea>
            </div>
            <div class="form-group mb-3">
              <label class="form-label" style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem" for="apply_referral">How did you hear about this job? <span class="text-danger">*</span></label>
              <select id="apply_referral" name="referral" required class="form-select" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:.9rem;background:var(--bg);color:var(--text)">
                <option value="">Select an option</option>
                <option value="search">Job Search Website</option>
                <option value="social">Social Media</option>
                <option value="friend">Friend/Colleague</option>
                <option value="other">Other</option>
              </select>
              <div class="form-error"></div>
            </div>
          </div>
          <div class="modal-footer" style="padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end">
            <button type="button" class="btn btn-outline" data-bs-dismiss="modal" style="padding:10px 18px;font-size:.85rem">Cancel</button>
            <button type="submit" id="submitApply" class="btn btn-primary" style="padding:10px 22px;font-size:.85rem">Submit Application</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Report Job Modal -->
  <div class="modal fade" id="reportJobModal" tabindex="-1" aria-labelledby="reportJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="border:none;border-radius:12px;overflow:hidden;box-shadow:0 14px 40px rgba(10,47,87,.16)">
        <div class="modal-header" style="background:var(--accent);color:var(--brand-deep);padding:18px 22px">
          <h5 class="modal-title fw-bold" id="reportJobModalLabel">Report this Job</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="report-job-form">
          <?= csrf_field() ?>
          <input type="hidden" name="job_id" value="<?= $job->id ?>">
          <div class="modal-body" style="padding:22px">
            <p style="color:var(--muted);font-size:.87rem;margin-bottom:16px">Is there something wrong with this job post? Let us know — your report helps keep JobberRecruit safe.</p>
            <div class="form-group" style="margin-bottom:16px">
              <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Reason <span style="color:#b91c1c">*</span></label>
              <select name="reason" required style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
                <option value="">Select a reason</option>
                <option value="scam">It's a scam or fraudulent</option>
                <option value="offensive">Offensive or inappropriate content</option>
                <option value="misleading">Misleading or inaccurate information</option>
                <option value="expired">Job is already expired/filled</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Additional Details (Optional)</label>
              <textarea name="details" rows="4" placeholder="Provide more context…" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;resize:vertical"></textarea>
            </div>
          </div>
          <div class="modal-footer" style="padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end">
            <button type="button" class="btn btn-outline" data-bs-dismiss="modal" style="padding:10px 18px;font-size:.85rem">Cancel</button>
            <button type="submit" class="btn btn-accent" id="btn-submit-report" style="padding:10px 22px;font-size:.85rem">Submit Report</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key') ?>"></script>
<script>
function toggleApplyForm() {
  const formSec = document.getElementById('apply-form-section');
  if (formSec) {
    formSec.style.display = 'block';
    formSec.scrollIntoView({ behavior: 'smooth' });
  }
}

function copyLink() {
  var linkInput = document.getElementById('share-link-input');
  linkInput.select();
  linkInput.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(linkInput.value).then(function() {
    var btn = document.querySelector('.share-copy-btn');
    var originalText = btn.innerText;
    btn.innerText = 'Copied!';
    setTimeout(function() { btn.innerText = originalText; }, 2000);
  });
}

$(document).ready(function() {
  // Bookmark toggle
  $("#saveJobBtn").on("click", function() {
    const btn = $(this);
    const jobId = btn.data("job-id");
    btn.prop("disabled", true);
    $.ajax({
      url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
      method: "POST",
      success: function(r) {
        if (r.success) {
          btn.toggleClass("saved", r.saved);
          if (r.saved) {
            btn.addClass("saved");
            btn.html('<svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" aria-hidden="true" style="width:16px;height:16px"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg> Saved');
          } else {
            btn.removeClass("saved");
            btn.html('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="width:16px;height:16px"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg> Save job');
          }
        } else {
          toastr.error(r.message);
        }
      },
      complete: function() { btn.prop("disabled", false); },
      error: function() { toastr.error("Network error. Try again."); btn.prop("disabled", false); }
    });
  });

  // Report Job Submission
  $('#report-job-form').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#btn-submit-report');
    const modal = bootstrap.Modal.getInstance(document.getElementById('reportJobModal'));
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
    $.ajax({
      url: '<?= base_url('jobs/report') ?>',
      method: 'POST',
      data: $(this).serialize(),
      success: function(r) {
        toastr.success(r.message);
        modal.hide();
        $('#report-job-form')[0].reset();
      },
      error: function(x) {
        const r = x.responseJSON;
        toastr.error(r ? r.messages.error : 'An error occurred');
      },
      complete: function() { btn.prop('disabled', false).text('Submit Report'); }
    });
  });

  // Expand description on mobile
  function expandDescription() {
    const body = document.getElementById('detail-body');
    const btn = document.getElementById('desc-expand-btn');

    if (body.classList.contains('desc-collapsed')) {
      body.classList.remove('desc-collapsed');
      btn.innerHTML = '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg> Show less';
    } else {
      body.classList.add('desc-collapsed');
      btn.innerHTML = '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg> Read full description';
    }
  }
});
</script>
<?= $this->endSection() ?>

