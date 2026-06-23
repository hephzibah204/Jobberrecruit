import sys

with open('demo/app/Views/home/apply.php', 'r', encoding='utf-8') as f:
    apply_lines = f.readlines()

form_start = -1
form_end = -1
for i, line in enumerate(apply_lines):
    if "'id' => 'jobApplicationForm'" in line:
        form_start = i - 6
    if "ORIGINAL SIDEBAR (Job Overview / Company / Share)" in line:
        form_end = i - 3
        break

if form_start != -1 and form_end != -1:
    form_html = ''.join(apply_lines[form_start:form_end])
else:
    print("Could not find form!", form_start, form_end)
    sys.exit(1)

with open('demo/app/Views/home/view_job.php', 'r', encoding='utf-8') as f:
    vj_lines = f.readlines()

for i, line in enumerate(vj_lines):
    if '<div class="detail-card" id="apply-form-section">' in line:
        # inject here
        vj_lines.insert(i+1, form_html)
        break

with open('demo/app/Views/home/view_job.php', 'w', encoding='utf-8') as f:
    f.writelines(vj_lines)

print('Patched view_job.php successfully with form html')
