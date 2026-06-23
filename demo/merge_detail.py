import re
import os

php_path = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\home\view_job.php'
layout_path = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\extracted_layout.html'
styles_path = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\extracted_styles.css'

with open(php_path, 'r', encoding='utf-8') as f:
    php_content = f.read()

with open(layout_path, 'r', encoding='utf-8') as f:
    layout = f.read()

with open(styles_path, 'r', encoding='utf-8') as f:
    styles = f.read()

# Extract top PHP logic (Schema, Breadcrumb, and the top PHP block ending with ?>)
# Look for <main id="main-content"> to split the file.
top_php_match = re.search(r'(.*?)(?:<main id="main-content">|<div class="container">)', php_content, re.DOTALL)
top_php = top_php_match.group(1).strip() if top_php_match else ''

# Clean up CSS slightly if needed
styles = styles.replace('--brand: #0861A9;', '--brand: #0D609E;')
styles = styles.replace('--accent: #ED9020;', '--accent: #F08F1A;')

# Replace static values in the layout with PHP vars.

# Company Logo
layout = re.sub(
    r'<img src="/assets/paystack\.png".*?>',
    r'''<?php if ($showImage): ?>
                    <img src="<?= esc($coLogo) ?>" alt="<?= esc($coName) ?> logo" width="80" height="80" class="job-logo-img">
                  <?php else: ?>
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f8f9fa;color:#6c757d;font-weight:bold;font-size:1.5rem;"><?= esc($initials) ?></div>
                  <?php endif; ?>''',
    layout, flags=re.DOTALL
)

# Replace Title
layout = layout.replace('Product Designer', '<?= esc($job->title) ?>')

# Replace Employer Name
layout = re.sub(
    r'<a href="/companies/paystack" class="job-emp-link">Paystack</a>',
    r'''<?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                      <strong>Confidential Employer</strong>
                    <?php else: ?>
                      <a href="<?= base_url('employer/' . $job->employer_id) ?>" class="job-emp-link"><?= esc($job->employer_name) ?></a>
                    <?php endif; ?>''',
    layout
)

# Trust badge
layout = re.sub(
    r'<span class="job-emp-badge" aria-label="Verified Employer".*?</span>',
    r'''<?php if (empty($job->anonymous) && empty($job->is_anonymous) && !empty($job->is_verified)): ?>
                      <span class="job-emp-badge" aria-label="Verified Employer" data-tooltip="Verified Employer">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      </span>
                    <?php endif; ?>''',
    layout
)

# Badges (FULL-TIME, Urgent, etc)
layout = re.sub(
    r'<div class="job-badges">.*?</div>',
    r'''<div class="job-badges">
                <span class="badge badge-blue"><?= strtoupper(esc($job->job_type)) ?></span>
                <?php if ($job->featured): ?>
                  <span class="badge badge-featured"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></svg> Featured</span>
                <?php endif; ?>
              </div>''',
    layout, flags=re.DOTALL
)

# Action Buttons
layout = re.sub(
    r'<div class="job-actions">.*?</div>',
    r'''<div class="job-actions">
              <button id="saveJobBtn" data-job-id="<?= $job->id ?>" class="btn btn-outline <?= $isSaved ? 'saved' : '' ?>">
                <svg viewBox="0 0 24 24" fill="<?= $isSaved ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                <?= $isSaved ? 'Saved' : 'Save' ?>
              </button>
              
              <?php if ($job->application_method === 'form'): ?>
                <button id="toggleApplyForm" class="btn btn-primary" onclick="document.getElementById('apply-form-section').scrollIntoView({behavior: 'smooth'})">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                  <?= $label ?>
                </button>
              <?php else: ?>
                <a href="<?= $url ?>" class="btn btn-primary" <?= $targetAttr ?>>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                  <?= $label ?>
                </a>
              <?php endif; ?>
            </div>''',
    layout, flags=re.DOTALL
)


# Main Content Area
layout = re.sub(
    r'<div class="detail-block">.*?<h3>Responsibilities</h3>.*?</ul>',
    r'''<div class="detail-block">
              <?= $job->description ?: '<p>No job description provided.</p>' ?>''',
    layout, flags=re.DOTALL
)

layout = re.sub(
    r'<h3>Requirements</h3>.*?</ul>',
    r'''<?php if (!empty($job->requirements)): ?>
                <h3>Requirements</h3>
                <?= $job->requirements ?>
              <?php endif; ?>
              
              <?php if (!empty($job->application)): ?>
                <h3>Application Guidelines</h3>
                <?= $job->application ?>
              <?php endif; ?>''',
    layout, flags=re.DOTALL
)

# Remove Similar Jobs completely for now
layout = re.sub(r'<section class="similar-jobs.*?</section>', '', layout, flags=re.DOTALL)

# Remove the fake Apply Card, replace it with PHP application form inclusion
apply_card_html = r'''
            <?php if ($job->application_method === 'form'): ?>
              <div class="apply-card" id="apply-form-section">
                <div class="apply-card-header">
                  <h3 class="apply-card-title"><svg aria-hidden="true"><use href="#i-send"/></svg> Apply for this position</h3>
                  <p class="apply-card-sub">Submit your application directly to the employer through JobberRecruit.</p>
                </div>
                <div class="apply-card-body">
                  <?= $this->include('home/partials/apply_form') ?>
                </div>
              </div>
            <?php endif; ?>
'''
layout = re.sub(r'<div class="apply-card".*?</div>\s*</div>\s*</div>', apply_card_html, layout, flags=re.DOTALL)


# QUICK FACTS Sidebar
layout = re.sub(r'<li class="qf-item">\s*<div class="qf-icon">.*?<use href="#i-clock"/>.*?<strong>Job Type</strong>\s*<span>Full-Time</span>', r'''<li class="qf-item">
                <div class="qf-icon"><svg aria-hidden="true" width="16" height="16"><use href="#i-clock"/></svg></div>
                <div class="qf-body">
                  <strong>Job Type</strong>
                  <span><?= esc(ucfirst($job->job_type)) ?></span>''', layout, flags=re.DOTALL)

layout = re.sub(r'<li class="qf-item">\s*<div class="qf-icon">.*?<use href="#i-briefcase"/>.*?<strong>Experience</strong>\s*<span>2–5 Years</span>', r'''<li class="qf-item">
                <div class="qf-icon"><svg aria-hidden="true" width="16" height="16"><use href="#i-briefcase"/></svg></div>
                <div class="qf-body">
                  <strong>Experience</strong>
                  <span><?= esc($job->experience_level ?? 'Not specified') ?></span>''', layout, flags=re.DOTALL)
                  
layout = re.sub(r'<li class="qf-item">\s*<div class="qf-icon">.*?<use href="#i-map-pin"/>.*?<strong>Location</strong>\s*<span>Lagos, Nigeria \(Hybrid\)</span>', r'''<li class="qf-item">
                <div class="qf-icon"><svg aria-hidden="true" width="16" height="16"><use href="#i-map-pin"/></svg></div>
                <div class="qf-body">
                  <strong>Location</strong>
                  <span><?= esc($job->state_name ?? 'Nigeria') ?> (<?= esc(ucfirst($job->location_type ?? 'On-site')) ?>)</span>''', layout, flags=re.DOTALL)

layout = re.sub(r'<li class="qf-item">\s*<div class="qf-icon">.*?<use href="#i-naira"/>.*?<strong>Salary</strong>\s*<span>₦400,000 – ₦650,000 / month</span>', r'''<li class="qf-item">
                <div class="qf-icon"><svg aria-hidden="true" width="16" height="16"><use href="#i-naira"/></svg></div>
                <div class="qf-body">
                  <strong>Salary</strong>
                  <span><?= esc($salary) ?> <?= (!empty($job->salary_period) && $job->salary_period != 'Negotiable') ? ' / ' . $job->salary_period : '' ?></span>''', layout, flags=re.DOTALL)

# Required Skills
layout = re.sub(r'<ul class="skill-tags">.*?</ul>', r'''<ul class="skill-tags">
                <?php if (!empty($job->skills)): ?>
                    <?php foreach(explode(',', $job->skills) as $skill): ?>
                        <li><?= esc(trim($skill)) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Not specified</li>
                <?php endif; ?>
              </ul>''', layout, flags=re.DOTALL)

# Remove the MAP for now
layout = re.sub(r'<div class="sidebar-card">.*?<h3>Location</h3>.*?<div class="map-placeholder">.*?</div>.*?</div>', '', layout, flags=re.DOTALL)

# Share URL
layout = layout.replace('value="https://www.jobberrecruit.com/jobs/product-designer-paystack-lagos"', 'value="<?= current_url() ?>"')

final_php = f'''{top_php}

<?= $this->section('styles') ?>
<style>
{styles}

/* Custom adjustments */
.job-logo-img {{
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 8px;
}}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">
{layout}
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function copyLink() {{
  var linkInput = document.getElementById('share-link-input');
  linkInput.select();
  linkInput.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(linkInput.value).then(function() {{
    var btn = document.querySelector('.share-copy-btn');
    var originalText = btn.innerText;
    btn.innerText = 'Copied!';
    setTimeout(function() {{ btn.innerText = originalText; }}, 2000);
  }});
}}
</script>
<?= $this->endSection() ?>
'''

with open(php_path, 'w', encoding='utf-8') as f:
    f.write(final_php)

print("Merge Complete!")
