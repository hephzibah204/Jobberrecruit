<?php $page_title = 'My Profile'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Retrieve Escrow Wallet balance
$walletModel = new \App\Models\WalletModel();
$wallet = $walletModel->where('user_id', $user->id)->first();
$walletBalance = $wallet ? $wallet->balance : 0;

// Dynamic Profile completion calculation
$fields = [
    'full_name',
    'dob',
    'gender',
    'phone',
    'location',
    'job_title',
    'employment_type',
    'skills',
    'experience_years',
    'education_level',
];

$completed = 0;
foreach ($fields as $f) {
    if (!empty($candidate->$f)) $completed++;
}
if (!empty($candidate->resume)) $completed++;
$totalFields = count($fields) + 1;
$completion = round(($completed / $totalFields) * 100);
?>

<main class="content" id="main-content">
    
    <!-- Breadcrumbs / Top header style -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true"><use href="#i-users"/></svg> My Profile</h1>
            <p>View and manage your job seeker profile</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('candidate/profile/employer-view') ?>" class="btn btn-outline btn-sm">
                <svg aria-hidden="true"><use href="#i-eye"/></svg> View as Employer
            </a>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-primary btn-sm">
                <svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile
            </a>
        </div>
    </div>

    <!-- The 2-column Layout Grid -->
    <div class="prof-grid">
      <!-- ══ LEFT COLUMN ══ -->
      <div class="prof-col prof-sticky">
        <section class="card id-card" aria-label="Candidate ID">
          <div class="id-ava">
            <?php if (!empty($candidate->profile_picture)): ?>
                <img src="<?= base_url($candidate->profile_picture) ?>" alt="Profile Photo" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <?= esc(substr($candidate->full_name ?? 'C', 0, 1)) ?>
            <?php endif; ?>
            <span class="dot" aria-hidden="true"></span>
          </div>
          <h2 class="id-name"><?= esc($candidate->full_name ?? 'Not Set') ?></h2>
          <p class="id-mail"><?= esc($user->email) ?></p>
          <div class="id-badges">
            <?php if (!empty($candidate->resume)): ?>
                <span class="pill pill--success"><svg aria-hidden="true"><use href="#i-check"/></svg> CV Uploaded</span>
            <?php else: ?>
                <span class="pill pill--pending"><svg aria-hidden="true"><use href="#i-x"/></svg> No CV</span>
            <?php endif; ?>
            <?php if (!empty($candidate->is_open_to_work)): ?>
                <span class="pill pill--immediate">Open to work</span>
            <?php endif; ?>
          </div>
          <div class="id-actions">
            <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-outline btn-sm btn-block"><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile</a>
          </div>
        </section>

        <!-- Profile completion card -->
        <section class="card" aria-label="Profile completion">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Profile Completion</span></div>
          <div class="card-body">
            <div class="pf">
              <div class="pf-ring" role="img" aria-label="Profile <?= $completion ?> percent complete">
                <svg viewBox="0 0 88 88" aria-hidden="true"><circle class="track" cx="44" cy="44" r="38"/><circle class="prog" cx="44" cy="44" r="38" stroke-dashoffset="<?= 239 * (1 - $completion/100) ?>"/></svg>
                <span class="pct"><?= $completion ?>%</span>
              </div>
              <div class="pf-body">
                <b><?= $completion == 100 ? 'Fully Complete' : 'Almost complete' ?></b>
                <p>
                    <?php if ($completion < 100):
                        // Identify the first missing field so we can give specific advice
                        $missingHints = [];
                        if (empty($candidate->full_name))       $missingHints[] = 'add your full name';
                        if (empty($candidate->dob))             $missingHints[] = 'add your date of birth';
                        if (empty($candidate->gender))          $missingHints[] = 'set your gender';
                        if (empty($candidate->phone))           $missingHints[] = 'add a phone number';
                        if (empty($candidate->location))        $missingHints[] = 'add your location';
                        if (empty($candidate->job_title))       $missingHints[] = 'add your job title';
                        if (empty($candidate->employment_type)) $missingHints[] = 'set your employment type';
                        if (empty($candidate->skills))          $missingHints[] = 'add your skills';
                        if (empty($candidate->experience_years))$missingHints[] = 'set your years of experience';
                        if (empty($candidate->education_level)) $missingHints[] = 'set your education level';
                        if (empty($candidate->resume))          $missingHints[] = '<a href="' . base_url('candidate/profile/edit') . '">upload your CV</a>';
                        $nextStep = !empty($missingHints) ? ucfirst($missingHints[0]) . ' to move to the next milestone.' : 'Almost there!';
                    ?>
                        Next step: <?= $nextStep ?> Complete profiles rank higher in employer searches and unlock &#8358;500 in wallet rewards.
                    <?php else: ?>
                        Excellent! Your profile is complete and optimised for employer searches.
                    <?php endif; ?>
                </p>
                <p style="margin-top:7px;display:flex;gap:6px;flex-wrap:wrap">
                    <span class="pill <?= $completion >= 60 ? 'pill--success' : 'pill--pending' ?>"><svg aria-hidden="true"><use href="#i-check"/></svg> &#8358;200 at 60%</span>
                    <span class="pill <?= $completion >= 100 ? 'pill--success' : 'pill--pending' ?>"><svg aria-hidden="true"><use href="#i-wallet"/></svg> &#8358;500 at 100%</span>
                </p>
              </div>
            </div>
          </div>
        </section>

        <!-- Profile visibility -->
        <section class="card" aria-label="Profile visibility">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-eye"/></svg> Profile Visibility</span></div>
          <div class="card-body">
            <div class="vis-row" style="display:flex;align-items:center;justify-content:space-between;gap:14px;">
              <p id="vis-text" style="font-size:.82rem;color:var(--muted);margin:0;">
                <?php if (!empty($candidate->is_visible)): ?>
                  <b style="color:var(--brand-deep)">Visible to employers.</b> Verified employers can find you in candidate search and invite you to roles.
                <?php else: ?>
                  <b style="color:var(--brand-deep)">Hidden.</b> You won't appear in employer candidate search until you turn this back on.
                <?php endif; ?>
              </p>
              <label class="switch" style="flex-shrink:0;">
                <input type="checkbox" id="visibility-toggle" <?= !empty($candidate->is_visible) ? 'checked' : '' ?> aria-label="Profile visible to employers">
                <span class="sl"></span>
              </label>
            </div>
          </div>
        </section>

        <!-- Wallet card -->
        <section class="card" aria-label="Wallet">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Wallet Balance</span></div>
          <div class="card-body">
            <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:1.4rem;color:var(--brand-deep);margin-bottom:10px;">&#8358;<?= number_format($walletBalance, 2) ?></h3>
            <a href="<?= base_url('candidate/wallet') ?>" class="btn btn-outline btn-sm btn-block"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Go to Wallet</a>
          </div>
        </section>
      </div>

      <!-- ══ RIGHT COLUMN ══ -->
      <div class="prof-col">
        <!-- Personal Info -->
        <section class="card" aria-label="Personal information">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-users"/></svg> Personal Information</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <div class="info-grid">
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Full name</div><div class="info-val"><?= esc($candidate->full_name ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Gender</div><div class="info-val"><?= esc(ucfirst($candidate->gender ?? 'Not Set')) ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-calendar"/></svg> Date of birth</div><div class="info-val"><?= esc(!empty($candidate->dob) ? date('d M Y', strtotime($candidate->dob)) : 'Not Set') ?></div>
                <div class="priv-note"><svg aria-hidden="true"><use href="#i-shield"/></svg> Private — used for age verification, never shown to employers</div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-phone"/></svg> Phone</div><div class="info-val"><?= esc($candidate->phone ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-building"/></svg> Location</div><div class="info-val"><?= esc(!empty($candidate->location) ? $candidate->location . ' State' : 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-doc"/></svg> User ID</div><div class="info-val"><?= esc($candidate->user_id) ?></div></div>
            </div>
          </div>
        </section>

        <!-- Career Info -->
        <section class="card" aria-label="Career information">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Career Information</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <div class="info-grid">
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job title</div><div class="info-val"><?= esc($candidate->job_title ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-clock"/></svg> Employment type</div><div class="info-val"><?= esc($candidate->employment_type ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-chart"/></svg> Experience</div><div class="info-val"><?= esc(!empty($candidate->experience_years) ? $candidate->experience_years . ' years' : 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-grad"/></svg> Education level</div><div class="info-val"><?= esc($candidate->education_level ?? 'Not Set') ?></div></div>
              <div class="info-full"><div class="info-lbl"><svg aria-hidden="true"><use href="#i-zap"/></svg> Skills</div>
                <div class="chips" style="margin-top:4px">
                  <?php if (!empty($candidate->skills)): ?>
                      <?php 
                      $skillsArr = array_map('trim', explode(',', $candidate->skills));
                      foreach ($skillsArr as $skill): 
                      ?>
                          <span class="chip"><?= esc($skill) ?></span>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <span class="text-muted small">No skills added</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Professional Summary -->
        <section class="card" aria-label="Professional summary">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-note"/></svg> Professional Summary</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <p style="font-size:.86rem;line-height:1.75">
                <?= !empty($candidate->description) ? nl2br(esc($candidate->description)) : 'No professional summary added.' ?>
            </p>
          </div>
        </section>

        <!-- Work Experience -->
        <section class="card" aria-label="Work experience">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Work Experience</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <?php if (!empty($experiences)): ?>
                <?php foreach ($experiences as $xp): ?>
                    <?php
                    $start = !empty($xp->start_date) ? date('M Y', strtotime($xp->start_date)) : '';
                    $end   = !empty($xp->is_current) ? 'present' : (!empty($xp->end_date) ? date('M Y', strtotime($xp->end_date)) : '');
                    $range = trim($start . ($start && $end ? ' – ' : '') . $end);
                    ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
                      <div><b><?= esc($xp->job_title) ?></b><i><?= esc(trim(($xp->company ?? '') . (!empty($xp->location) ? ' · ' . $xp->location : '') . ($range ? ' · ' . $range : ''), ' ·')) ?></i>
                        <?php if (!empty($xp->description)): ?><p><?= nl2br(esc($xp->description)) ?></p><?php endif; ?>
                      </div></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="xp" style="border:none;padding:2px 0"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
                  <div><b>No work experience added</b><i><a href="<?= base_url('candidate/profile/edit') ?>">Add your work history</a> so employers can see your background.</i></div></div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Education -->
        <section class="card" aria-label="Education">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-grad"/></svg> Education</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <?php if (!empty($education)): ?>
                <?php foreach ($education as $ed): ?>
                    <?php $yr = trim(($ed->start_year ?? '') . (($ed->start_year && $ed->end_year) ? ' – ' : '') . ($ed->end_year ?? '')); ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-grad"/></svg></span>
                      <div><b><?= esc($ed->degree) ?><?= !empty($ed->field_of_study) ? ' — ' . esc($ed->field_of_study) : '' ?></b><i><?= esc(trim(($ed->school ?? '') . ($yr ? ' · ' . $yr : '') . (!empty($ed->grade) ? ' · ' . $ed->grade : ''), ' ·')) ?></i></div></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="xp" style="border:none;padding:2px 0"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-grad"/></svg></span>
                  <div><b>No education added</b><i><a href="<?= base_url('candidate/profile/edit') ?>">Add your qualifications</a> to strengthen your profile.</i></div></div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Certifications (auto-attached JobberRecruit certificates) -->
        <section class="card" aria-label="Certifications">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-award"/></svg> Certifications</span>
            <a href="<?= base_url('candidate/certificates') ?>" class="card-link">My certificates <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <?php if (!empty($certificates)): ?>
                <?php foreach ($certificates as $cert): ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
                      <div><b><?= esc($cert['course_name'] ?? 'Course Certificate') ?></b><i>JobberRecruit Training · <?= esc(date('M Y', strtotime($cert['issued_at']))) ?> · <?= esc($cert['certificate_code']) ?> · <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" target="_blank">Download</a></i></div></div>
                <?php endforeach; ?>
                <p style="font-size:.72rem;color:var(--muted);margin-top:10px">JobberRecruit certificates attach to your profile automatically and are verifiable by employers.</p>
            <?php else: ?>
                <div class="xp" style="border:none;padding:2px 0"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
                  <div><b>No certificates yet</b><i>Complete a course in <a href="<?= base_url('training') ?>">Training</a> to earn a verifiable certificate.</i></div></div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Languages & Job Preferences -->
        <div class="duo2">
          <section class="card" aria-label="Languages" style="min-width:0">
            <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-chat"/></svg> Languages</span></div>
            <div class="card-body">
              <div class="chips">
                <?php if (!empty($candidate->languages)): ?>
                    <?php 
                    $langs = array_map('trim', explode(',', $candidate->languages));
                    foreach ($langs as $lang):
                    ?>
                        <span class="chip"><?= esc($lang) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted small">Not Set</span>
                <?php endif; ?>
              </div>
            </div>
          </section>
          
          <section class="card" aria-label="Job Preferences" style="min-width:0">
            <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Preferences</span></div>
            <div class="card-body">
              <div style="font-size:.84rem;line-height:1.6;">
                <div><span class="text-muted">Salary:</span> <b><?= !empty($candidate->desired_salary) ? '&#8358;' . number_format($candidate->desired_salary) . ' / ' . esc($candidate->salary_type ?? 'monthly') : 'Negotiable' ?></b></div>
                <div><span class="text-muted">Availability:</span> <b><?= esc($candidate->availability ?? 'Not Set') ?></b></div>
                <?php if (!empty($candidate->preferred_location)): ?>
                <div><span class="text-muted">Preferred location:</span> <b><?= esc($candidate->preferred_location) ?></b></div>
                <?php endif; ?>
                <?php if (!empty($candidate->portfolio)): ?>
                <div><span class="text-muted">Portfolio:</span> <b><a href="<?= esc($candidate->portfolio, 'attr') ?>" target="_blank" rel="noopener noreferrer">View portfolio</a></b></div>
                <?php endif; ?>
              </div>
            </div>
          </section>
        </div>

        <!-- Documents / CV -->
        <section class="card" aria-label="Documents">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> Documents</span>
            <span class="pill <?= !empty($candidate->resume) ? 'pill--success' : 'pill--pending' ?>"><svg aria-hidden="true"><use href="#i-check"/></svg> <?= !empty($candidate->resume) ? 'Uploaded' : 'Pending' ?></span></div>
          <div class="card-body">
            <div class="doc-row">
              <span class="doc-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
              <div class="doc-info">
                <b>Resume / CV</b>
                <i><?= !empty($candidate->resume) ? 'Uploaded CV File' : 'No CV file uploaded yet' ?></i>
              </div>
              <div class="doc-actions">
                <?php if (!empty($candidate->resume)): ?>
                    <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="btn btn-outline btn-sm"><svg aria-hidden="true"><use href="#i-eye"/></svg> View Resume</a>
                <?php endif; ?>
                <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-outline btn-sm"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Upload/Replace</a>
              </div>
            </div>
            <p style="font-size:.74rem;color:var(--muted);margin-top:12px">Your CV is shared with an employer when you apply, or when a verified employer unlocks your profile from candidate search.</p>
          </div>
        </section>
      </div>
    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    $('#visibility-toggle').on('change', function () {
        var input = $(this);
        var makeVisible = input.is(':checked') ? 1 : 0;
        input.prop('disabled', true);

        $.ajax({
            url: '<?= base_url('candidate/profile/visibility') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                is_visible: makeVisible
            },
            success: function (res) {
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message);
                    if (res.is_visible) {
                        $('#vis-text').html('<b style="color:var(--brand-deep)">Visible to employers.</b> Verified employers can find you in candidate search and invite you to roles.');
                    } else {
                        $('#vis-text').html('<b style="color:var(--brand-deep)">Hidden.</b> You won\'t appear in employer candidate search until you turn this back on.');
                    }
                } else {
                    input.prop('checked', !makeVisible);
                    if (typeof toastr !== 'undefined') toastr.error((res && res.message) || 'Could not update visibility.');
                }
            },
            error: function () {
                input.prop('checked', !makeVisible);
                if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
            },
            complete: function () { input.prop('disabled', false); }
        });
    });
});
</script>
<?= $this->endSection() ?>
