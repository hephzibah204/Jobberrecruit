<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.progress-bar { position: sticky; top: 70px; z-index: 900; background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 0; box-shadow: 0 2px 8px rgba(10,47,87,.06); }
.progress-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.progress-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
.progress-track { flex: 1; max-width: 360px; height: 8px; background: var(--border); border-radius: 20px; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--brand), #1a7fd4); border-radius: 20px; transition: width .6s ease; }
.progress-text { font-size: .82rem; font-weight: 700; color: var(--text); white-space: nowrap; }
.progress-tip { font-size: .78rem; color: var(--accent-dark); font-weight: 600; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px; }
.progress-tip svg { width: 13px; height: 13px; }
.progress-actions { display: flex; gap: 8px; flex-shrink: 0; }

.profile-page { padding: 16px 0 72px; }
.profile-wrap { max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }

.cv-card { background: var(--white); border: 1px solid var(--border); border-left: 3px solid var(--accent); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
.cv-card-header {
  list-style: none; cursor: pointer;
  display: flex; align-items: center; gap: 10px;
  padding: 20px 24px 16px; flex-wrap: wrap;
  -webkit-tap-highlight-color: transparent; user-select: none;
}
.cv-card-header::-webkit-details-marker { display: none; }
.cv-card-header::marker { display: none; }
.cv-card-header:hover { background: #fafbfd; }

.cv-card-title { flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; font-family: 'Sora',sans-serif; font-size: 1.05rem; font-weight: 700; color: var(--text); }
.cv-card-title svg { width: 18px; height: 18px; color: var(--accent); flex-shrink: 0; }

.cv-card-done { display: inline-flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 3px 9px; border-radius: 20px; flex-shrink: 0; white-space: nowrap; margin-left: auto; }
.cv-card-done.complete  { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }
.cv-card-done.incomplete { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.cv-card-done.optional  { background: var(--brand-light); color: var(--brand); }

.cv-chev { width: 18px; height: 18px; flex-shrink: 0; color: var(--muted); transition: transform .22s ease; }
.cv-card:not([open]) .cv-chev { transform: rotate(-90deg); }
.cv-card .cv-card-body { padding: 0 24px 24px; animation: cv-open .18s ease; }

@keyframes cv-open { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }

.cv-card-hint { font-size: .84rem; color: var(--muted); background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 12px 14px; margin-bottom: 18px; line-height: 1.6; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-grid.cols-1 { grid-template-columns: 1fr; }
.form-field { display: flex; flex-direction: column; gap: 5px; }
.form-field.full { grid-column: 1/-1; }

label { font-size: .82rem; font-weight: 600; color: var(--text); }
label .opt { font-weight: 400; color: var(--muted); font-size: .78rem; }

.char-count { font-size: .74rem; color: var(--muted); text-align: right; margin-top: 3px; }

.logo-upload { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
.logo-preview {
  width: 96px; height: 96px; border-radius: 12px;
  border: 2px dashed var(--border); background: var(--bg);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; overflow: hidden; transition: var(--transition);
  cursor: pointer; position: relative;
}
.logo-preview:hover { border-color: var(--brand); background: var(--brand-light); }
.logo-preview svg { width: 36px; height: 36px; color: var(--muted); }
.logo-preview img { width: 100%; height: 100%; object-fit: contain; }
.logo-overlay {
  position: absolute; inset: 0; background: rgba(8,97,169,.7);
  display: flex; align-items: center; justify-content: center;
  opacity: 0; transition: var(--transition); border-radius: 10px;
}
.logo-preview:hover .logo-overlay { opacity: 1; }
.logo-overlay svg { width: 22px; height: 22px; color: var(--white); }
.logo-upload-body { display: flex; flex-direction: column; gap: 5px; }
.logo-upload-body p { font-size: .8rem; color: var(--muted); }

.pref-pill-group { display: flex; flex-wrap: wrap; gap: 8px; }
.pref-pill { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 20px; font-size: .84rem; font-weight: 500; cursor: pointer; transition: var(--transition); user-select: none; background: var(--white); }
.pref-pill input { width: 14px; height: 14px; min-height: 14px; accent-color: var(--brand); cursor: pointer; }
.pref-pill:has(input:checked) { background: var(--brand-light); border-color: var(--brand); color: var(--brand); font-weight: 600; }

.form-actions { display: flex; align-items: center; gap: 10px; padding-top: 18px; border-top: 1px solid var(--border); margin-top: 20px; flex-wrap: wrap; }
.form-actions .autosave-note { font-size: .76rem; color: var(--muted); margin-left: auto; display: inline-flex; align-items: center; gap: 5px; }
.form-actions .autosave-note svg { width: 13px; height: 13px; color: var(--success); }

.verified-banner {
  display: flex; align-items: center; gap: 14px;
  background: var(--brand-light); border: 1px solid #c8dff2;
  border-radius: var(--radius); padding: 16px 20px; margin-bottom: 20px;
}
.verified-banner svg { width: 28px; height: 28px; color: var(--brand); flex-shrink: 0; }
.verified-banner-body { flex: 1; }
.verified-banner-body strong { display: block; font-size: .9rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 2px; }
.verified-banner-body p { font-size: .8rem; color: var(--muted); margin: 0; }
.verified-tag { display: inline-flex; align-items: center; gap: 5px; font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: var(--brand); color: var(--white); padding: 4px 11px; border-radius: 20px; flex-shrink: 0; }
.verified-tag svg { width: 12px; height: 12px; }

.doc-upload-zone {
  border: 2px dashed var(--border); border-radius: var(--radius);
  padding: 28px 24px; text-align: center; cursor: pointer;
  transition: var(--transition); background: var(--bg);
  position: relative;
}
.doc-upload-zone:hover, .doc-upload-zone.drag-over { border-color: var(--brand); background: var(--brand-light); }
.doc-upload-zone.drag-over { transform: scale(1.01); }
.doc-upload-idle { display: flex; flex-direction: column; align-items: center; }
.doc-upload-done { display: flex; align-items: center; gap: 10px; justify-content: center; flex-wrap: wrap; }
.doc-file-name { font-size: .88rem; font-weight: 600; color: var(--text); }
.doc-file-size { font-size: .78rem; color: var(--muted); }
.doc-remove-btn {
  background: #fef2f2; border: 1px solid #fecaca; color: var(--danger);
  border-radius: 6px; padding: 4px 8px; cursor: pointer;
  display: inline-flex; align-items: center; line-height: 0;
  transition: var(--transition); margin-left: 4px;
}
.doc-remove-btn:hover { background: #fee2e2; }

.bottom-actions { display: flex; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-top: 1px solid var(--border); background: var(--white); border-radius: 12px; }

@keyframes bar-pulse { 0%,100% { opacity:1; } 50% { opacity:.65; } }
.progress-fill.pulse { animation: bar-pulse .5s ease 2; }

.cv-gain-tip {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: .68rem; font-weight: 700; letter-spacing: .02em;
  color: var(--accent-dark); background: #fff3e0;
  border: 1px solid #fde3bf; padding: 3px 9px; border-radius: 20px;
  flex-shrink: 0; white-space: nowrap; margin-left: auto; margin-right: 8px;
}
.cv-gain-tip svg { width: 11px; height: 11px; }

@keyframes next-glow { 0% { box-shadow: 0 0 0 0 rgba(8,97,169,.35); } 60% { box-shadow: 0 0 0 6px rgba(8,97,169,0); } 100% { box-shadow: none; } }
.cv-card.next-up { animation: next-glow .7s ease forwards; border-color: var(--brand) !important; }

@media (max-width: 860px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-grid.cols-3 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
  .progress-inner { flex-direction: column; align-items: flex-start; gap: 10px; }
  .progress-tip { display: none; }
  .photo-upload { flex-direction: column; align-items: flex-start; }
  .form-grid.cols-3 { grid-template-columns: 1fr; }
  .bottom-actions { flex-direction: column; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-hd">
  <div class="page-hd-left">
    <h1><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Company Profile</h1>
    <p>This information appears on all your job listings and your employer profile page.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Profile
    </a>
  </div>
</div>

<!-- STICKY PROGRESS BAR -->
<div class="progress-bar">
  <div class="progress-inner">
    <div class="progress-left">
      <div class="progress-track">
        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
      </div>
      <span class="progress-text" id="progress-pct">0% Completed</span>
    </div>
    <div class="progress-tip">
      <svg aria-hidden="true"><use href="#i-bulb"/></svg>
      <span id="progress-tip-text"></span>
    </div>
  </div>
</div>

<div class="profile-page">
  <form id="editEmployerForm" action="<?= base_url('employer/profile/edit') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="profile-wrap">

      <!-- ══ 1. COMPANY IDENTITY ══ -->
      <details class="cv-card" aria-labelledby="h-identity" open>
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-identity">
            <svg aria-hidden="true"><use href="#i-building"/></svg> Company Identity
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Your company name and logo appear on every job listing and search result. Candidates make instant decisions based on these.</div>

          <!-- Company Logo Upload -->
          <div class="logo-upload">
            <?php
            $hasLogo = false;
            $logoSrc = '';
            if (!empty($employer->logo)) {
                if (filter_var($employer->logo, FILTER_VALIDATE_URL) || str_starts_with($employer->logo, 'http')) {
                    $hasLogo = true;
                    $logoSrc = $employer->logo;
                } elseif (file_exists(FCPATH . $employer->logo)) {
                    $hasLogo = true;
                    $logoSrc = base_url($employer->logo);
                }
            }
            ?>
            <label for="logo-input" class="logo-preview" id="logo-preview" title="Click to upload company logo">
              <?php if ($hasLogo): ?>
                <img src="<?= esc($logoSrc) ?>" alt="Company logo preview">
              <?php else: ?>
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <?php endif; ?>
              <div class="logo-overlay"><svg aria-hidden="true"><use href="#i-download"/></svg></div>
            </label>
            <input type="file" id="logo-input" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp" class="sr-only" onchange="previewLogo(this)">
            <div class="logo-upload-body">
              <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" onclick="document.getElementById('logo-input').click()">
                <svg aria-hidden="true"><use href="#i-download"/></svg> Upload logo
              </button>
              <p>PNG, JPG, or SVG · Square recommended · Max 2MB</p>
              <p>Shown on job listings, search results, and your profile page</p>
            </div>
          </div>

          <div class="form-grid">
            <div class="form-field full">
              <label for="company-name">Company name <span class="opt">(contact us to change after setting)</span></label>
              <input type="text" id="company-name" name="company_name" autocomplete="organization" placeholder="e.g. Dangote Group Plc" value="<?= esc($employer->company_name ?? '') ?>">
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">
                🔒 Once set, <a href="<?= base_url('contact-us') ?>">contact us</a> to request a name change
              </span>
            </div>
            <div class="form-field full">
              <label for="company-tagline">Company tagline <span class="opt">(optional — shown below your name on listings)</span></label>
              <input type="text" id="company-tagline" name="tagline" autocomplete="off" placeholder="e.g. Africa's leading technology platform" maxlength="120" value="<?= esc($employer->tagline ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="industry">Industry</label>
              <select id="industry" name="industry">
                <option value="">Select industry</option>
                <?php if (!empty($industries)): ?>
                  <?php foreach ($industries as $parentInd): ?>
                    <optgroup label="<?= esc($parentInd->name) ?>">
                      <?php if (!empty($parentInd->children)): ?>
                        <?php foreach ($parentInd->children as $childInd): ?>
                          <option value="<?= $childInd->id ?>" <?= (isset($employer->industry) && $employer->industry == $childInd->id) ? 'selected' : '' ?>>
                            <?= esc($childInd->name) ?>
                          </option>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <option value="<?= $parentInd->id ?>" <?= (isset($employer->industry) && $employer->industry == $parentInd->id) ? 'selected' : '' ?>>
                          <?= esc($parentInd->name) ?>
                        </option>
                      <?php endif; ?>
                    </optgroup>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-field">
              <label for="company-type">Company type</label>
              <select id="company-type" name="company_type">
                <option value="">Select type</option>
                <?php
                $types = ['Startup', 'SME (Small & Medium Enterprise)', 'Large Corporation', 'Multinational', 'Government Agency / Parastatal', 'NGO / Non-profit', 'Recruiting / Staffing Firm', 'Cooperative', 'Other'];
                foreach ($types as $type):
                  $sel = (isset($employer->company_type) && $employer->company_type == $type) ? 'selected' : '';
                ?>
                  <option value="<?= esc($type) ?>" <?= $sel ?>><?= esc($type) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-field">
              <label for="founded-year">Year founded <span class="opt">(optional)</span></label>
              <select id="founded-year" name="founded_year">
                <option value="">Select year</option>
                <?php
                $currYear = (int) date('Y');
                for ($y = $currYear; $y >= 1950; $y--):
                  $sel = (isset($employer->founded_year) && $employer->founded_year == $y) ? 'selected' : '';
                ?>
                  <option value="<?= $y ?>" <?= $sel ?>><?= $y ?></option>
                <?php endfor; ?>
                <option value="Before 1950" <?= (isset($employer->founded_year) && $employer->founded_year == 'Before 1950') ? 'selected' : '' ?>>Before 1950</option>
              </select>
            </div>
            <div class="form-field">
              <label for="num-employees">Number of employees</label>
              <select id="num-employees" name="num_employees">
                <option value="">Select range</option>
                <?php
                $ranges = ['1–5', '6–10', '11–50', '51–200', '201–500', '501–1,000', '1,001–5,000', '5,000+'];
                foreach ($ranges as $range):
                  $sel = (isset($employer->num_employees) && $employer->num_employees == $range) ? 'selected' : '';
                ?>
                  <option value="<?= esc($range) ?>" <?= $sel ?>><?= esc($range) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-field full">
              <label>Company-wide remote work policy</label>
              <p style="font-size:.8rem;color:var(--muted);margin-bottom:10px">This appears on your employer profile and gives candidates an immediate sense of your working culture.</p>
              <div class="pref-pill-group">
                <?php
                $policies = [
                  'fully_remote' => 'Fully remote',
                  'hybrid' => 'Hybrid',
                  'fully_onsite' => 'Fully on-site',
                  'flexible_by_role' => 'Flexible by role',
                  'not_specified' => 'Prefer not to specify'
                ];
                $currentPolicy = $employer->remote_policy ?? 'not_specified';
                foreach ($policies as $val => $lbl):
                ?>
                  <label class="pref-pill">
                    <input type="radio" name="remote_policy" value="<?= esc($val) ?>" <?= ($currentPolicy == $val) ? 'checked' : '' ?>>
                    <?= esc($lbl) ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('identity', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save identity
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Changes save automatically</span>
          </div>
        </div>
      </details>

      <!-- ══ 2. CONTACT & LOCATION ══ -->
      <details class="cv-card" aria-labelledby="h-contact">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-contact">
            <svg aria-hidden="true"><use href="#i-phone"/></svg> Contact &amp; Location
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Candidates verify companies are real and legitimate using this information. A complete contact section increases trust.</div>
          <div class="form-grid">
            <div class="form-field">
              <label for="company-phone">Phone number <span class="opt">(not published publicly)</span></label>
              <input type="tel" id="company-phone" name="phone" placeholder="e.g. 07038399120" value="<?= esc($employer->phone ?? '') ?>">
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">🔒 Used for internal verification only — not shown to candidates</span>
            </div>
            <div class="form-field">
              <label for="company-whatsapp">WhatsApp Business <span class="opt">(optional)</span></label>
              <input type="tel" id="company-whatsapp" name="whatsapp" autocomplete="tel" placeholder="e.g. 08012345678" value="<?= esc($employer->whatsapp ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="company-email">Company email <span class="opt">(for correspondence)</span></label>
              <input type="email" id="company-email" name="company_email" placeholder="e.g. hr@company.com" value="<?= esc($employer->company_email ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="company-website">Website</label>
              <input type="url" id="company-website" name="website" placeholder="https://yourcompany.com" value="<?= esc($employer->website ?? '') ?>">
            </div>
            <div class="form-field full">
              <label for="company-address">Office address</label>
              <input type="text" id="company-address" name="address" placeholder="e.g. 3rd Floor, 123 Broad Street, Lagos Island" value="<?= esc($employer->address ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="company-state">State</label>
              <select id="company-state" name="state">
                <option value="">Select state</option>
                <?php if (isset($states) && is_array($states)): ?>
                  <?php foreach ($states as $s): ?>
                    <option value="<?= esc($s->name) ?>" <?= (isset($employer->state) && $employer->state == $s->name) ? 'selected' : '' ?>><?= esc($s->name) ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <?php
                  $nigerianStates = ['Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT Abuja', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'];
                  foreach ($nigerianStates as $stateName):
                    $sel = (isset($employer->state) && $employer->state == $stateName) ? 'selected' : '';
                  ?>
                    <option value="<?= esc($stateName) ?>" <?= $sel ?>><?= esc($stateName) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('contact', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save contact info
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Changes save automatically</span>
          </div>
        </div>
      </details>

      <!-- ══ 3. ABOUT THE COMPANY ══ -->
      <details class="cv-card" aria-labelledby="h-about">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-about">
            <svg aria-hidden="true"><use href="#i-briefcase"/></svg> About the Company
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Candidates read your company description before deciding whether to apply. A compelling description significantly improves application quality.</div>
          <div class="form-grid">
            <div class="form-field full">
              <label for="company-desc">Company description</label>
              <textarea id="company-desc" name="description" rows="6" maxlength="1200"
                placeholder="Describe what your company does, your mission, values, and what makes it a great place to work."
                oninput="updateCount('company-desc','desc-count',1200)"><?= esc($employer->description ?? '') ?></textarea>
              <div class="char-count"><span id="desc-count"><?= strlen($employer->description ?? '') ?></span> / 1,200</div>
            </div>
            <div class="form-field full">
              <label>Benefits &amp; perks</label>
              <p style="font-size:.8rem;color:var(--muted);margin-bottom:12px">Select what your company offers. Candidates filter and compare employers by these.</p>

              <?php
              $selectedBenefits = [];
              if (isset($employer->benefits)) {
                  if (is_array($employer->benefits)) {
                      $selectedBenefits = $employer->benefits;
                  } elseif (is_string($employer->benefits)) {
                      $decoded = json_decode($employer->benefits, true);
                      if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                          $selectedBenefits = $decoded;
                      } else {
                          $selectedBenefits = explode(',', $employer->benefits);
                      }
                  }
              }
              $selectedBenefits = array_map('trim', $selectedBenefits);
              ?>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Health &amp; Insurance</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="hmo" <?= in_array('hmo', $selectedBenefits) ? 'checked' : '' ?>> HMO / Health Insurance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="hmo_dependants" <?= in_array('hmo_dependants', $selectedBenefits) ? 'checked' : '' ?>> HMO covers dependants</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="life_insurance" <?= in_array('life_insurance', $selectedBenefits) ? 'checked' : '' ?>> Life Insurance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="dental_vision" <?= in_array('dental_vision', $selectedBenefits) ? 'checked' : '' ?>> Dental &amp; Vision</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Financial</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="pension" <?= in_array('pension', $selectedBenefits) ? 'checked' : '' ?>> Contributory Pension (CPS)</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="13th_month" <?= in_array('13th_month', $selectedBenefits) ? 'checked' : '' ?>> 13th Month Salary</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="performance_bonus" <?= in_array('performance_bonus', $selectedBenefits) ? 'checked' : '' ?>> Performance Bonus</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="profit_sharing" <?= in_array('profit_sharing', $selectedBenefits) ? 'checked' : '' ?>> Profit Sharing</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="stock_options" <?= in_array('stock_options', $selectedBenefits) ? 'checked' : '' ?>> Stock Options / Equity</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Allowances</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="transport" <?= in_array('transport', $selectedBenefits) ? 'checked' : '' ?>> Transport Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="housing" <?= in_array('housing', $selectedBenefits) ? 'checked' : '' ?>> Housing Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="meal" <?= in_array('meal', $selectedBenefits) ? 'checked' : '' ?>> Meal Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="airtime" <?= in_array('airtime', $selectedBenefits) ? 'checked' : '' ?>> Airtime / Data Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="company_car" <?= in_array('company_car', $selectedBenefits) ? 'checked' : '' ?>> Company Car / Vehicle</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="relocation" <?= in_array('relocation', $selectedBenefits) ? 'checked' : '' ?>> Relocation Support</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Leave &amp; Time</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="annual_leave_15" <?= in_array('annual_leave_15', $selectedBenefits) ? 'checked' : '' ?>> Annual Leave 15+ days</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="annual_leave_21" <?= in_array('annual_leave_21', $selectedBenefits) ? 'checked' : '' ?>> Annual Leave 21+ days</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="flex_hours" <?= in_array('flex_hours', $selectedBenefits) ? 'checked' : '' ?>> Flexible Working Hours</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="parental_leave" <?= in_array('parental_leave', $selectedBenefits) ? 'checked' : '' ?>> Paid Parental Leave</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="sabbatical" <?= in_array('sabbatical', $selectedBenefits) ? 'checked' : '' ?>> Sabbatical Leave</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Growth &amp; Wellbeing</p>
              <div class="pref-pill-group">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="training_budget" <?= in_array('training_budget', $selectedBenefits) ? 'checked' : '' ?>> Training &amp; Development Budget</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="mentorship" <?= in_array('mentorship', $selectedBenefits) ? 'checked' : '' ?>> Mentorship Programme</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="gym" <?= in_array('gym', $selectedBenefits) ? 'checked' : '' ?>> Gym / Wellness Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="team_retreats" <?= in_array('team_retreats', $selectedBenefits) ? 'checked' : '' ?>> Team Retreats / Offsites</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="paid_certifications" <?= in_array('paid_certifications', $selectedBenefits) ? 'checked' : '' ?>> Paid Certifications</label>
              </div>
            </div>

            <div class="form-field full">
              <label for="hiring-process">Our hiring process <span class="opt">(optional — shown on your public profile)</span></label>
              <textarea id="hiring-process" name="hiring_process" rows="3"
                placeholder="e.g. Stage 1: CV review (5 days). Stage 2: 30-min video interview. Stage 3: Technical assessment."
                oninput="updateCount('hiring-process','hiring-count',500)"><?= esc($employer->hiring_process ?? '') ?></textarea>
              <div class="char-count"><span id="hiring-count"><?= strlen($employer->hiring_process ?? '') ?></span> / 500</div>
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">Transparency here improves your application completion rate.</span>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('about', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save description
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Changes save automatically</span>
          </div>
        </div>
      </details>

      <!-- ══ 4. VERIFICATION — VERIFIED EMPLOYER BADGE ══ -->
      <details class="cv-card" aria-labelledby="h-verify">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-verify">
            <svg aria-hidden="true"><use href="#i-shield"/></svg> Verification
            <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(unlocks Verified Employer badge)</span>
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">

          <!-- Verified Employer Badge Preview -->
          <div class="verified-banner">
            <svg aria-hidden="true"><use href="#i-shield"/></svg>
            <div class="verified-banner-body">
              <strong>Verified Employer Badge</strong>
              <p>Once verified, a ✓ Verified badge appears on all your job listings. Candidates trust verified employers and are 3× more likely to apply.</p>
            </div>
            <span class="verified-tag">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Verified
            </span>
          </div>

          <div class="form-grid">
            <div class="form-field">
              <label for="rc-number">RC Number / CAC Registration number</label>
              <input type="text" id="rc-number" name="rc_number" autocomplete="off" placeholder="e.g. RC123456" value="<?= esc($employer->rc_number ?? '') ?>">
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">Your Corporate Affairs Commission (CAC) registration number. We cross-reference this with the CAC public register.</span>
            </div>

            <!-- CAC Document Upload -->
            <div class="form-field full">
              <label>CAC Certificate / Incorporation document</label>
              <?php
              $hasCac = !empty($employer->cac_document);
              $cacName = $hasCac ? basename($employer->cac_document) : 'document.pdf';
              ?>
              <div class="doc-upload-zone" id="cac-drop-zone"
                   onclick="document.getElementById('cac-file-input').click()"
                   ondragover="handleDragOver(event)"
                   ondragleave="handleDragLeave(event)"
                   ondrop="handleDrop(event,'cac-file-input','cac-file-info')"
                   role="button" tabindex="0" aria-label="Upload CAC document"
                   onkeydown="if(event.key==='Enter'||event.key===' ')document.getElementById('cac-file-input').click()">
                <input type="file" id="cac-file-input" name="cac_document"
                       accept=".pdf,.jpg,.jpeg,.png"
                       class="sr-only"
                       onchange="showFileInfo(this,'cac-file-info','cac-drop-zone')">
                <div class="doc-upload-idle" id="cac-idle" style="<?= $hasCac ? 'display:none;' : '' ?>">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--muted);margin-bottom:10px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M12 18v-6M9 15l3-3 3 3"/></svg>
                  <p style="font-size:.88rem;font-weight:600;color:var(--text);margin-bottom:4px">Click to upload or drag and drop</p>
                  <p style="font-size:.78rem;color:var(--muted)">CAC Certificate of Incorporation, Business Name Certificate, or Status Report</p>
                  <p style="font-size:.75rem;color:var(--muted);margin-top:4px">PDF, JPG, or PNG · Max 5MB · Must be a clear, unaltered official document</p>
                </div>
                <div class="doc-upload-done" id="cac-file-info" style="<?= $hasCac ? 'display:flex;' : 'display:none;' ?>">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--success)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><polyline points="14 2 14 8 20 8"/></svg>
                  <span class="doc-file-name" id="cac-file-name"><?= esc($cacName) ?></span>
                  <span class="doc-file-size" id="cac-file-size">Uploaded</span>
                  <button type="button" class="doc-remove-btn" onclick="removeFile('cac-file-input','cac-file-info','cac-idle',event)" aria-label="Remove uploaded document">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </div>
              <span style="font-size:.76rem;color:var(--muted);margin-top:6px;display:block">
                🔒 Uploaded documents are reviewed only by the JobberRecruit verification team — never shared with candidates.
              </span>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('verify', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Submit for verification
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Verification takes up to 24 hours</span>
          </div>
        </div>
      </details>

      <!-- ══ 5. SOCIAL PROFILES ══ -->
      <details class="cv-card" aria-labelledby="h-social">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-social">
            <svg aria-hidden="true"><use href="#i-globe"/></svg> Social Profiles
            <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(optional)</span>
          </h2>
          <span class="cv-card-done optional">Optional</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Candidates research your company on social media before applying. Adding your social links builds credibility.</div>
          <div class="form-grid cols-1">
            <div class="form-field">
              <label for="social-linkedin">
                LinkedIn company page
              </label>
              <input type="url" id="social-linkedin" name="linkedin" placeholder="https://linkedin.com/company/yourcompany" value="<?= esc($employer->linkedin ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="social-twitter">
                Twitter / X
              </label>
              <input type="url" id="social-twitter" name="twitter" placeholder="https://x.com/yourcompany" value="<?= esc($employer->twitter ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="social-facebook">
                Facebook page
              </label>
              <input type="url" id="social-facebook" name="facebook" placeholder="https://facebook.com/yourcompany" value="<?= esc($employer->facebook ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="social-instagram">
                Instagram
              </label>
              <input type="url" id="social-instagram" name="instagram" placeholder="https://instagram.com/yourcompany" value="<?= esc($employer->instagram ?? '') ?>">
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('social', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save social profiles
            </button>
          </div>
        </div>
      </details>

      <!-- BOTTOM ACTIONS -->
      <div class="bottom-actions">
        <a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline emp-btn-lg">
          <svg aria-hidden="true"><use href="#i-eye"/></svg> Preview public profile
        </a>
        <button type="button" class="emp-btn emp-btn-primary emp-btn-lg" onclick="saveAllSections(event)">
          <svg aria-hidden="true"><use href="#i-check"/></svg> Save all changes
        </button>
      </div>

    </div>
  </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline">
  <svg aria-hidden="true"><use href="#i-eye"/></svg> Preview
</a>
<button type="button" class="emp-btn emp-btn-primary" onclick="saveAllSections(event)">
  <svg aria-hidden="true"><use href="#i-check"/></svg> Save All
</button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* ── Logo preview ── */
function previewLogo(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview = document.getElementById('logo-preview');
    preview.innerHTML = '<img src="' + e.target.result + '" alt="Company logo preview"><div class="logo-overlay"><svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>';
  };
  reader.readAsDataURL(input.files[0]);
}

/* ── Character counter ── */
function updateCount(fieldId, countId, max) {
  var el = document.getElementById(fieldId);
  var counter = document.getElementById(countId);
  if (el && counter) counter.textContent = el.value.length;
}

/* ── Section ordering, weights and tips ── */
var SECTION_ORDER = ['identity','contact','about','verify','social'];
var sectionWeights = { identity:25, contact:20, about:20, verify:20, social:15 };
var SECTION_TIPS = {
  identity: 'Add your logo & company name for +25% — shown on every listing',
  contact:  'Add contact details for +20% — candidates verify you are legitimate',
  about:    'Add a company description for +20% — candidates read this before applying',
  verify:   'Add RC number for +20% — unlocks the Verified Employer badge',
  social:   'Add social profiles for +15% — builds trust with senior candidates'
};

var completedSections = {
    identity: <?= (!empty($employer->company_name) && !empty($employer->industry) && !empty($employer->company_type) && !empty($employer->num_employees)) ? 'true' : 'false' ?>,
    contact: <?= (!empty($employer->phone) && !empty($employer->company_email) && !empty($employer->website) && !empty($employer->address) && !empty($employer->state)) ? 'true' : 'false' ?>,
    about: <?= (!empty($employer->description)) ? 'true' : 'false' ?>,
    verify: <?= (!empty($employer->rc_number) || !empty($employer->cac_document)) ? 'true' : 'false' ?>,
    social: <?= (!empty($employer->linkedin) || !empty($employer->twitter) || !empty($employer->facebook) || !empty($employer->instagram)) ? 'true' : 'false' ?>
};

/* ── Inject gain-tip badges on load ── */
function injectGainTips() {
  SECTION_ORDER.forEach(function(key) {
    var card = document.querySelector('[aria-labelledby="h-' + key + '"]');
    if (!card) return;
    var summary = card.querySelector('.cv-card-header');
    var chev = summary.querySelector('.cv-chev');
    var gain = sectionWeights[key] || 0;
    var tip = document.createElement('span');
    tip.className = 'cv-gain-tip';
    tip.id = 'gain-tip-' + key;
    tip.title = SECTION_TIPS[key];
    tip.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor" width="11" height="11" aria-hidden="true"><path d="M13 2 4.1 13H11l-2 9 10.9-11H13l2-9z"/></svg> +' + gain + '%';
    summary.insertBefore(tip, chev);
  });
}

/* ── Update sticky bar tip ── */
function updateBarTip() {
  var el = document.getElementById('progress-tip-text');
  if (!el) return;
  for (var i = 0; i < SECTION_ORDER.length; i++) {
    var k = SECTION_ORDER[i];
    if (!completedSections[k]) { el.textContent = SECTION_TIPS[k]; return; }
  }
  el.textContent = 'Profile complete — ready to attract top candidates!';
}

/* ── Auto-advance to next incomplete section ── */
function advanceToNext(currentKey) {
  var idx = SECTION_ORDER.indexOf(currentKey);
  for (var i = idx + 1; i < SECTION_ORDER.length; i++) {
    var nextKey = SECTION_ORDER[i];
    if (completedSections[nextKey]) continue;
    var nextCard = document.querySelector('[aria-labelledby="h-' + nextKey + '"]');
    if (!nextCard) continue;
    nextCard.setAttribute('open', '');
    nextCard.classList.add('next-up');
    setTimeout(function(c) { return function() { c.classList.remove('next-up'); }; }(nextCard), 900);
    setTimeout(function(c) {
      return function() {
        var top = c.getBoundingClientRect().top + window.scrollY - 140;
        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
      };
    }(nextCard), 120);
    return;
  }
  var bottom = document.querySelector('.bottom-actions');
  if (bottom) setTimeout(function() { bottom.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 120);
}

/* ── Progress pulse ── */
function pulseBar() {
  var fill = document.getElementById('progress-fill');
  if (!fill) return;
  fill.classList.remove('pulse');
  void fill.offsetWidth;
  fill.classList.add('pulse');
  setTimeout(function() { fill.classList.remove('pulse'); }, 1100);
}

/* ── Main save handler ── */
function saveSection(key, event) {
  var e = event || window.event;
  var btn = e ? e.target.closest('button') : null;
  if (!btn) return;
  var orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

  var form = document.getElementById('editEmployerForm');
  var formData = new FormData(form);
  formData.append('section', key);

  $.ajax({
      url: form.getAttribute('action'),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
          'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(response) {
          btn.disabled = false;
          completedSections[key] = true;
          var card = btn.closest('.cv-card');
          if (card) card.classList.add('is-complete');
          if (card) {
              var badge = card.querySelector('.cv-card-done');
              if (badge) {
                  badge.className = 'cv-card-done complete';
                  badge.innerHTML = '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Done';
              }
          }
          var gainTip = document.getElementById('gain-tip-' + key);
          if (gainTip) gainTip.style.display = 'none';

          updateProgress(); pulseBar(); updateBarTip(); advanceToNext(key);

          btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Saved';
          btn.style.background = 'var(--brand-dark)'; btn.style.borderColor = 'var(--brand-dark)';
          setTimeout(function() { btn.innerHTML = orig; btn.style.background = ''; btn.style.borderColor = ''; }, 2200);
          
          if (typeof toastr !== 'undefined') {
              toastr.success(response.message || 'Section saved successfully.');
          }
      },
      error: function(xhr) {
          btn.disabled = false;
          btn.innerHTML = orig;
          var message = 'An error occurred while saving.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
          }
          if (typeof toastr !== 'undefined') {
              toastr.error(message);
          } else {
              alert(message);
          }
      }
  });
}

/* ── Save all ── */
function saveAllSections(event) {
  var e = event || window.event;
  var btn = e ? e.target.closest('button') : null;
  if (!btn) return;
  var orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving all...';

  var form = document.getElementById('editEmployerForm');
  var formData = new FormData(form);

  $.ajax({
      url: form.getAttribute('action'),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
          'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(response) {
          btn.disabled = false;
          btn.innerHTML = orig;
          SECTION_ORDER.forEach(function(k) {
              completedSections[k] = true;
              var card = document.querySelector('[aria-labelledby="h-' + k + '"]');
              if (card) {
                  card.classList.add('is-complete');
                  var badge = card.querySelector('.cv-card-done');
                  if (badge) { badge.className = 'cv-card-done complete'; badge.innerHTML = '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Done'; }
                  var tip = document.getElementById('gain-tip-' + k);
                  if (tip) tip.style.display = 'none';
              }
          });
          updateProgress(); pulseBar(); updateBarTip();
          if (typeof toastr !== 'undefined') {
              toastr.success(response.message || 'All sections saved.');
              setTimeout(function() {
                  window.location.href = '<?= base_url('employer/profile') ?>';
              }, 1000);
          } else {
              window.location.href = '<?= base_url('employer/profile') ?>';
          }
      },
      error: function(xhr) {
          btn.disabled = false;
          btn.innerHTML = orig;
          var message = 'An error occurred while saving.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
          }
          if (typeof toastr !== 'undefined') {
              toastr.error(message);
          } else {
              alert(message);
          }
      }
  });
}

/* ── Update progress bar ── */
function updateProgress() {
  var total = 0;
  Object.keys(completedSections).forEach(function(k) { 
      if (completedSections[k] === true) {
          total += sectionWeights[k] || 0; 
      }
  });
  total = Math.min(total, 100);
  var fill = document.getElementById('progress-fill');
  var pct = document.getElementById('progress-pct');
  if (fill) fill.style.width = total + '%';
  if (pct) pct.textContent = total + '% Completed';
}

/* ── CAC document upload handlers ── */
function showFileInfo(input, infoId, zoneId) {
  if (!input.files || !input.files[0]) return;
  var file = input.files[0];

  /* Validate size (5MB max) */
  if (file.size > 5 * 1024 * 1024) {
    if (typeof toastr !== 'undefined') toastr.error('File is too large. Please upload a document under 5MB.');
    else alert('File is too large. Please upload a document under 5MB.');
    input.value = '';
    return;
  }
  /* Validate type */
  var allowed = ['application/pdf','image/jpeg','image/png'];
  if (!allowed.includes(file.type)) {
    if (typeof toastr !== 'undefined') toastr.error('Please upload a PDF, JPG, or PNG file.');
    else alert('Please upload a PDF, JPG, or PNG file.');
    input.value = '';
    return;
  }

  var nameEl = document.getElementById('cac-file-name');
  var sizeEl = document.getElementById('cac-file-size');
  var info   = document.getElementById(infoId);
  var idle   = document.getElementById('cac-idle');

  if (nameEl) nameEl.textContent = file.name;
  if (sizeEl) sizeEl.textContent = file.size > 1024*1024
    ? (file.size / (1024*1024)).toFixed(1) + ' MB'
    : Math.round(file.size / 1024) + ' KB';
  if (info) info.style.display = 'flex';
  if (idle) idle.style.display = 'none';
}

function removeFile(inputId, infoId, idleId, event) {
  event.stopPropagation();
  var input = document.getElementById(inputId);
  var info  = document.getElementById(infoId);
  var idle  = document.getElementById(idleId);
  if (input) input.value = '';
  if (info)  info.style.display = 'none';
  if (idle)  idle.style.display = 'flex';
}

function handleDragOver(event) {
  event.preventDefault();
  event.currentTarget.classList.add('drag-over');
}
function handleDragLeave(event) {
  event.currentTarget.classList.remove('drag-over');
}
function handleDrop(event, inputId, infoId) {
  event.preventDefault();
  event.currentTarget.classList.remove('drag-over');
  var input = document.getElementById(inputId);
  if (!input || !event.dataTransfer.files.length) return;
  try {
    var dt = new DataTransfer();
    dt.items.add(event.dataTransfer.files[0]);
    input.files = dt.files;
  } catch(e) { }
  showFileInfo(input, infoId, event.currentTarget.id);
}

document.addEventListener('DOMContentLoaded', function() {
  injectGainTips();
  
  // Set initial complete states in UI
  SECTION_ORDER.forEach(function(k) {
      if (completedSections[k]) {
          const card = document.querySelector('[aria-labelledby="h-' + k + '"]');
          if (card) {
              card.classList.add('is-complete');
              const badge = card.querySelector('.cv-card-done');
              if (badge) {
                  badge.className = 'cv-card-done complete';
                  badge.innerHTML = '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Done';
              }
              const tip = document.getElementById('gain-tip-' + k);
              if (tip) tip.style.display = 'none';
          }
      }
  });

  updateProgress();
  updateBarTip();
});
</script>
<?= $this->endSection() ?>