import re

filepath = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views\employers\post-job.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Title input name
content = content.replace('name="job_title"', 'name="title"')

# 2. Update Location select name & dynamic options
state_pattern = r'<select id="job-location" name="location" required >(.*?)</select>'
state_replacement = """<select id="job-location" name="state_id" required >
          <option value="">Select state / Remote</option>
          <?php foreach ($states as $state): ?>
            <option value="<?= $state['id'] ?>" <?= old('state_id') == $state['id'] ? 'selected' : '' ?>><?= esc($state['name']) ?></option>
          <?php endforeach; ?>
        </select>"""
content = re.sub(state_pattern, state_replacement, content, flags=re.DOTALL)

# 3. Update Work Style/Location Type select name and options
style_pattern = r'<select id="work-style" name="work_style" required onchange="toggleHybridDays\(this.value\)">\s*<option value="">Select work style</option>\s*<option>On-site</option>\s*<option>Remote</option>\s*<option>Hybrid</option>\s*<option>Flexible</option>\s*</select>'
style_replacement = """<select id="work-style" name="location_type" required onchange="toggleHybridDays(this.value)">
          <option value="">Select work style</option>
          <option value="on-site">On-site</option>
          <option value="remote">Remote</option>
          <option value="hybrid">Hybrid</option>
        </select>"""
content = re.sub(style_pattern, style_replacement, content, flags=re.DOTALL)

# 4. Update Industry select name & options
ind_pattern = r'<select id="industry" name="industry" required onchange="updateJobCategories\(this.value\)">.*?</select>'
ind_replacement = """<select id="industry" name="industry_id" required onchange="updateJobCategories(this.value)">
          <option value="">Select industry</option>
          <?php foreach ($industries as $ind): ?>
            <option value="<?= $ind['id'] ?>" <?= old('industry_id') == $ind['id'] ? 'selected' : '' ?>><?= esc($ind['name']) ?></option>
          <?php endforeach; ?>
        </select>"""
content = re.sub(ind_pattern, ind_replacement, content, flags=re.DOTALL)

# 5. Update Job Category select name & options
cat_pattern = r'<select id="job-category" name="job_category" required>.*?</select>'
cat_replacement = """<select id="job-category" name="category_id" required>
          <option value="">Select category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>"""
content = re.sub(cat_pattern, cat_replacement, content, flags=re.DOTALL)

# 6. Update Job Type options to lowercase values
content = content.replace('<option>Full-time</option>', '<option value="full-time">Full-time</option>')
content = content.replace('<option>Part-time</option>', '<option value="part-time">Part-time</option>')
content = content.replace('<option>Contract</option>', '<option value="contract">Contract</option>')
content = content.replace('<option>Internship</option>', '<option value="internship">Internship</option>')
content = content.replace('<option>Freelance</option>', '<option value="freelance">Freelance</option>')

# 7. Update publishJob javascript to use AJAX submit
js_pattern = r'function publishJob\(e\) \{.*?toastr\.success\(\'Job posted! \(backend integration pending\)\'\);.*?\}'
js_replacement = """function publishJob(e) {
  e.preventDefault();
  
  var title = document.getElementById('job-title');
  if (!title || !title.value.trim()) { 
    title.focus(); 
    toastr.error('Please enter a job title.'); 
    return; 
  }

  const form = document.getElementById('post-job-form');
  const formData = new FormData(form);

  const submitBtn = document.querySelector('.publish-bar button[type="submit"]') || document.querySelector('button[type="submit"]');
  if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Publishing...';
  }

  fetch('<?= base_url("employer/post-job") ?>', {
      method: 'POST',
      body: formData,
      headers: {
          'X-Requested-With': 'XMLHttpRequest'
      }
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          toastr.success(data.message);
          setTimeout(() => {
              window.location.href = '<?= base_url("employer/dashboard") ?>';
          }, 2000);
      } else {
          toastr.error(data.message || 'An error occurred.');
          if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = 'Publish Job';
          }
      }
  })
  .catch(error => {
      console.error('Error:', error);
      toastr.error('A network error occurred. Please try again.');
      if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Publish Job';
      }
  });
}"""
content = re.sub(js_pattern, js_replacement, content, flags=re.DOTALL)

# 8. Update Javascript ALL_CATEGORIES to be dynamic based on database categories
content = content.replace('var ALL_CATEGORIES = [\'Accounting & Audit\',\'Administration\',\'Agriculture\',\'Architecture\',\'Aviation\',\'Banking Operations\',\'Brand Management\',\'Business Consulting\',\'Business Development\',\'Chemical Engineering\',\'Civil Engineering\',\'Clinical Research\',\'Cloud & DevOps\',\'Community Development\',\'Content Creation\',\'Content Marketing\',\'Customer Service\',\'Cybersecurity\',\'Data / Analytics\',\'Digital Marketing\',\'Drilling & Production\',\'Education & Training\',\'Electrical Engineering\',\'Energy Management\',\'Estate Management\',\'Executive Assistant\',\'Finance & Investment\',\'Fleet Management\',\'Food Science\',\'Government Affairs\',\'Graduate Trainee\',\'Health & Safety\',\'Hotel Management\',\'HSE\',\'Human Resources\',\'ICT / Software\',\'Insurance Operations\',\'Internal Audit\',\'Investment Banking\',\'Journalism\',\'Law / Legal\',\'Lecturing / Teaching\',\'Logistics & Supply Chain\',\'Management Consulting\',\'Manufacturing\',\'Market Research\',\'Marketing & Communications\',\'Mechanical Engineering\',\'Medical / Healthcare\',\'Mobile Development\',\'Networking\',\'NGO Program Management\',\'Nursing\',\'Office Management\',\'Petroleum Engineering\',\'Pharmacy\',\'Product Management\',\'Procurement\',\'Production\',\'Project Management\',\'Public Health\',\'Public Relations\',\'Quality Assurance\',\'Quantity Surveying\',\'Research & Development\',\'Restaurant Operations\',\'Retail Management\',\'Risk Management\',\'Sales\',\'School Administration\',\'Security Management\',\'Social Media\',\'Social Work\',\'Strategy\',\'Supply Chain\',\'Technical Support\',\'Treasury\',\'UI/UX Design\',\'Video Production\',\'Warehousing\',\'Other\'];',
                          'var ALL_CATEGORIES = <?= json_encode($categories) ?>;')

# 9. Rewrite Javascript updateJobCategories to dynamically filter categories
js_cat_func = r'function updateJobCategories\(industryValue\) \{.*?sel\.appendChild\(opt\);\s*\}\s*;\s*\}'
js_cat_func_replacement = """function updateJobCategories(industryId) {
  var sel = document.getElementById('job-category');
  if (!sel) return;
  sel.innerHTML = '<option value="">Select category</option>';
  var filtered = ALL_CATEGORIES.filter(c => c.parent_id == industryId);
  filtered.forEach(function(c) {
    var opt = document.createElement('option');
    opt.value = c.id; opt.textContent = c.name;
    sel.appendChild(opt);
  });
}"""
content = re.sub(r'function updateJobCategories\(industryValue\) \{.*?cats\.forEach\(function\(cat\) \{.*?\}\);\s*\}', js_cat_func_replacement, content, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Aligned post-job.php frontend with backend")
