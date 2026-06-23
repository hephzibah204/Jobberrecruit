import re

html_path = r'C:\Users\hephz\Downloads\File -Updated-Extracted\post-a-job.html'
php_path = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\employers\post-job.php'

html_content = open(html_path, 'r', encoding='utf-8').read()
php_content = open(php_path, 'r', encoding='utf-8').read()

# Extract components from HTML
styles = re.search(r'<style>(.*?)</style>', html_content, re.DOTALL).group(1)
post_wrap = re.search(r'(<div class="post-wrap">.*?</div><!-- /post-wrap -->)', html_content, re.DOTALL).group(1)
scripts = re.search(r'<script>(.*?)</script>', html_content, re.DOTALL).group(1)

# Modify styles: The CSS from the template uses default variables, let's just make sure they match brand if needed. But the template ALREADY has `--brand: #0861A9;` etc. I will leave it as is or replace with the brand.
styles = styles.replace('--brand: #0861A9;', '--brand: #0D609E;')
styles = styles.replace('--accent: #ED9020;', '--accent: #F08F1A;')

# Extract the top section of the original PHP
top_php_match = re.search(r'(<\?php if \(session\(\)->has\(\'errors\'\)\): \?>.*?)<form', php_content, re.DOTALL)
top_php = top_php_match.group(1)

# Make replacements in the `post_wrap` HTML to add PHP constructs.

# 1. CSRF
post_wrap = post_wrap.replace('<form id="post-job-form"', '<form method="POST" class="add-product-form" id="post-job-form">\n    <?= csrf_field() ?>')

# 2. Text inputs
post_wrap = re.sub(r'(<input type="text" id="job-title" name="title"[^>]*)>', r'\1 value="<?= old(\'title\') ?>">', post_wrap)

# 3. Job Type (has specific values)
for typ in ['full-time', 'part-time', 'contract', 'freelance', 'internship']:
    post_wrap = post_wrap.replace(f'<option value="{typ}">', f'<option value="{typ}" <?= old(\'job_type\') == \'{typ}\' ? \'selected\' : \'\' ?>>')

# 4. Location loop
location_html = """
          <option value="">Select state / region</option>
          <?php foreach ($states as $state): ?>
              <option value="<?= $state->id ?>" <?= old('state_id') == $state->id ? 'selected' : '' ?>>
                  <?= $state->name ?>
              </option>
          <?php endforeach; ?>
"""
post_wrap = re.sub(r'<select id="job-location" name="state_id" required>.*?</select>', f'<select id="job-location" name="state_id" class="select2" required>{location_html}</select>', post_wrap, flags=re.DOTALL)

# 5. Location Type
for l_type in ['on-site', 'hybrid', 'remote']:
    post_wrap = post_wrap.replace(f'<option value="{l_type}">', f'<option value="{l_type}" <?= old(\'location_type\') == \'{l_type}\' ? \'selected\' : \'\' ?>>')

# 6. Industry loop
industry_html = """
          <option value="">Select industry</option>
          <?php foreach ($industries as $industry): ?>
              <option value="<?= $industry->id ?>" <?= old('industry_id') == $industry->id ? 'selected' : '' ?>>
                  <?= $industry->name ?>
              </option>
          <?php endforeach; ?>
"""
post_wrap = re.sub(r'<select id="industry" name="industry_id" required>.*?</select>', f'<select id="industry" name="industry_id" class="select2" required>{industry_html}</select>', post_wrap, flags=re.DOTALL)

# 7. Job Category loop
category_html = """
          <option value="">Select function</option>
          <?php foreach ($categories as $category): ?>
              <option value="<?= $category->id ?>" <?= old('category_id') == $category->id ? 'selected' : '' ?>>
                  <?= $category->name ?>
              </option>
          <?php endforeach; ?>
"""
post_wrap = re.sub(r'<select id="job-function" name="category_id" required>.*?</select>', f'<select id="job-function" name="category_id" class="select2" required>{category_html}</select>', post_wrap, flags=re.DOTALL)

# 8. Salary fields
post_wrap = re.sub(r'(<input type="number" id="salary-min"[^>]*)>', r'\1 value="<?= old(\'salary\') ?>">', post_wrap)
post_wrap = re.sub(r'(<input type="number" id="salary-max"[^>]*)>', r'\1 value="<?= old(\'salary_max\') ?>">', post_wrap)
post_wrap = re.sub(r'(<input type="number" id="salary-fixed"[^>]*)>', r'\1 value="<?= old(\'salary\') ?>">', post_wrap)
# Salary period
for per in ['monthly', 'annually', 'daily', 'hourly', 'yearly']:
    post_wrap = post_wrap.replace(f'<option value="{per}">', f'<option value="{per}" <?= old(\'salary_period\') == \'{per}\' ? \'selected\' : \'\' ?>>')

# 9. Job Description
post_wrap = re.sub(r'<textarea id="job-desc" name="description"([^>]*)>.*?</textarea>', r'<textarea id="job-desc" name="description"\1><?= old(\'description\') ?></textarea>', post_wrap, flags=re.DOTALL)

# 10. Education
for edu in ['High School', 'Associate Degree', 'Bachelor\'s Degree', 'Master\'s Degree', 'PhD']:
    val = edu.replace("'", "\\'")
    # We replace any <option> that starts with the value
    post_wrap = re.sub(rf'<option>({edu})</option>', f'<option value="{val}" <?= old(\'education_level\') == \'{val}\' ? \'selected\' : \'\' ?>>\\1</option>', post_wrap)
# Handle edge cases for education string matching in the template
post_wrap = post_wrap.replace('<option>No formal education required</option>', '<option value="No formal education required" <?= old(\'education_level\') == \'No formal education required\' ? \'selected\' : \'\' ?>>No formal education required</option>')
post_wrap = post_wrap.replace('<option>B.Sc / B.A / B.Eng</option>', '<option value="Bachelor\'s Degree" <?= old(\'education_level\') == \'Bachelor\\\'s Degree\' ? \'selected\' : \'\' ?>>B.Sc / B.A / B.Eng</option>')
post_wrap = post_wrap.replace('<option>M.Sc / MBA / M.A</option>', '<option value="Master\'s Degree" <?= old(\'education_level\') == \'Master\\\'s Degree\' ? \'selected\' : \'\' ?>>M.Sc / MBA / M.A</option>')
post_wrap = post_wrap.replace('<option>Ph.D</option>', '<option value="PhD" <?= old(\'education_level\') == \'PhD\' ? \'selected\' : \'\' ?>>Ph.D</option>')

# 11. Experience
# The template has: No experience, Less than 1 year, 1-2 years, 3-5 years, etc.
# Old has: Entry Level (0-2 years), Mid Level (2-5 years), Senior Level (5+ years), Executive Level
# It's better to keep the old values since they're in the DB.
exp_html = """
          <option value="">Select range</option>
          <option value="Entry Level (0-2 years)" <?= old('experience_level') == 'Entry Level (0-2 years)' ? 'selected' : '' ?>>Entry Level (0-2 years)</option>
          <option value="Mid Level (2-5 years)" <?= old('experience_level') == 'Mid Level (2-5 years)' ? 'selected' : '' ?>>Mid Level (2-5 years)</option>
          <option value="Senior Level (5+ years)" <?= old('experience_level') == 'Senior Level (5+ years)' ? 'selected' : '' ?>>Senior Level (5+ years)</option>
          <option value="Executive Level" <?= old('experience_level') == 'Executive Level' ? 'selected' : '' ?>>Executive Level</option>
"""
post_wrap = re.sub(r'<select id="years-exp" name="years_experience" required>.*?</select>', f'<select id="years-exp" name="experience_level" required>{exp_html}</select>', post_wrap, flags=re.DOTALL)

# 12. Skills & Requirements (hidden inputs in the new template)
post_wrap = post_wrap.replace('id="skills-hidden" name="required_skills" value=""', 'id="skills-hidden" name="skills" value="<?= old(\'skills\') ?>"')

# 13. Application deadline / start date
post_wrap = post_wrap.replace('id="app-deadline" name="application_deadline"', 'id="app-deadline" name="application_deadline" value="<?= old(\'application_deadline\') ?>"')
post_wrap = post_wrap.replace('id="start-date" name="start_date"', 'id="start-date" name="start_date" value="<?= old(\'start_date\') ?>"')

# 14. Application methods
post_wrap = post_wrap.replace('<input type="radio" name="app_method" value="jobberrecruit" checked', '<input type="radio" name="application_method" value="form" <?= old(\'application_method\', \'form\') == \'form\' ? \'checked\' : \'\' ?>')
post_wrap = post_wrap.replace('<input type="radio" name="app_method" value="whatsapp"', '<input type="radio" name="application_method" value="whatsapp" <?= old(\'application_method\') == \'whatsapp\' ? \'checked\' : \'\' ?>')
post_wrap = post_wrap.replace('<input type="radio" name="app_method" value="email"', '<input type="radio" name="application_method" value="email" <?= old(\'application_method\') == \'email\' ? \'checked\' : \'\' ?>')
post_wrap = post_wrap.replace('<input type="radio" name="app_method" value="external"', '<input type="radio" name="application_method" value="external" <?= old(\'application_method\') == \'external\' ? \'checked\' : \'\' ?>')

# Also change the dynamic detail input
# The new template uses a single input 'app_method_detail'. The old backend expects whatsapp_link, application_email, external_url.
# I will replace the single input with three inputs that hide/show, same as the original. Or modify the template to match the old inputs.
# In the new template: <input type="text" id="method-detail-input" name="app_method_detail"...>
old_conditional_inputs = """
        <div id="method-detail" style="margin-top:10px;display:none">
          <input type="url" id="method_whatsapp_input" name="whatsapp_link" class="form-control" style="display:none;" placeholder="https://wa.me/2348000000000" value="<?= old('whatsapp_link') ?>">
          <input type="email" id="method_email_input" name="application_email" class="form-control" style="display:none;" placeholder="jobs@company.com" value="<?= old('application_email') ?>">
          <input type="url" id="method_external_input" name="external_url" class="form-control" style="display:none;" placeholder="https://company.com/apply" value="<?= old('external_url') ?>">
        </div>
"""
post_wrap = re.sub(r'<div id="method-detail".*?</div>', old_conditional_inputs, post_wrap, flags=re.DOTALL)

# 15. Who can apply
post_wrap = post_wrap.replace('<input type="radio" name="who_can_apply" value="all" checked>', '<input type="radio" name="application_access" value="general" <?= old(\'application_access\', \'general\') == \'general\' ? \'checked\' : \'\' ?>>')
post_wrap = post_wrap.replace('<input type="radio" name="who_can_apply" value="registered">', '<input type="radio" name="application_access" value="authenticated" <?= old(\'application_access\') == \'authenticated\' ? \'checked\' : \'\' ?>>')
# Add guest applicant option
guest_html = '<label class="method-pill"><input type="radio" name="application_access" value="guest" <?= old(\'application_access\') == \'guest\' ? \'checked\' : \'\' ?>> Guest Applicants</label>'
post_wrap = post_wrap.replace('</label>\n        </div>\n        <span', f'</label>\n          {guest_html}\n        </div>\n        <span')


# 16. Anonymous posting toggle
post_wrap = post_wrap.replace('name="anonymous" id="post-anon"', 'name="is_anonymous" id="post-anon" value="1" <?= old(\'is_anonymous\') ? \'checked\' : \'\' ?>')

# Fix Notification email toggle
post_wrap = post_wrap.replace('name="notify_inapp" id="notify-inapp" checked', 'name="notification_in_app" id="notify-inapp" value="1" <?= old(\'notification_in_app\', 1) ? \'checked\' : \'\' ?>')
post_wrap = post_wrap.replace('name="notify_email" id="notify-email"', 'name="notification_email_toggle" id="notify-email" value="1" <?= old(\'notification_email_toggle\') ? \'checked\' : \'\' ?>')


# Assemble final PHP string
final_php = f'''<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    {top_php}

    {post_wrap}
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
{styles}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
{scripts}

// Additional JS to bridge the template interactions with our PHP form inputs
function toggleMethodDetail(val) {{
  var detailDiv = document.getElementById('method-detail');
  var w = document.getElementById('method_whatsapp_input');
  var e = document.getElementById('method_email_input');
  var ext = document.getElementById('method_external_input');
  var note = document.getElementById('ats-unavailable-note');
  var atsSection = document.getElementById('ats-section');
  
  if(w) w.style.display = 'none';
  if(e) e.style.display = 'none';
  if(ext) ext.style.display = 'none';
  
  if (val === 'jobberrecruit' || val === 'form') {{
    detailDiv.style.display = 'none';
    note.style.display = 'none';
    atsSection.style.display = 'block';
  }} else {{
    detailDiv.style.display = 'block';
    note.style.display = 'flex';
    atsSection.style.display = 'none';
    
    if(val === 'whatsapp') w.style.display = 'block';
    if(val === 'email') e.style.display = 'block';
    if(val === 'external') ext.style.display = 'block';
  }}
}}

// Initialize Method Toggle on Load
document.addEventListener('DOMContentLoaded', function() {{
    const selectedMethod = document.querySelector('input[name="application_method"]:checked');
    if (selectedMethod) {{
        toggleMethodDetail(selectedMethod.value);
    }}
    
    // Select2 Initialization
    if (typeof $ !== 'undefined' && $.fn.select2) {{
        $('.select2').select2();
    }}
}});
</script>
<?= $this->endSection() ?>
'''

with open(php_path, 'w', encoding='utf-8') as f:
    f.write(final_php)

print("Replacement Complete!")
