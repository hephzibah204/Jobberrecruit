import re
import sys

php_file = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\home\view_job.php'
form_file = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\new_form_content.php'
css_file = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\apply_form_styles.css'

with open(php_file, 'r', encoding='utf-8') as f:
    php_content = f.read()

with open(form_file, 'r', encoding='utf-8') as f:
    new_form = f.read()

with open(css_file, 'r', encoding='utf-8') as f:
    css_content = f.read()

# We look for <?php if ($job->application_method === 'form'): ?>
# and end right before <?php endif; ?> that precedes <!-- ── Share Card ── -->

pattern = r'(<\?php if \(\$job->application_method === \'form\'\): \?>\s*<div class=\"detail-card\" id=\"apply-form-section\">.*?)(?=<\?php endif; \?>\s*<!-- ── Share Card ── -->)'

match = re.search(pattern, php_content, re.DOTALL)
if match:
    # new_form ALREADY contains:
    #             <div class="detail-card" id="apply-form-section">
    #                 ...
    #             </div>
    # We should replace match.group(1) with:
    #           <?php if ($job->application_method === 'form'): ?>
    #           new_form
    #           
    replacement = "          <?php if ($job->application_method === 'form'): ?>\n" + new_form + "\n"
    php_content = php_content.replace(match.group(1), replacement)
    print('SUCCESS: Replaced form.')
else:
    print('FAILURE: Regex failed to find the form section.')
    sys.exit(1)

if 'apply-form-card' not in php_content:
    style_tag = '\n<style>\n' + css_content + '\n</style>\n'
    php_content = php_content.replace('</main>', style_tag + '</main>')
    print('SUCCESS: Injected CSS.')

with open(php_file, 'w', encoding='utf-8') as f:
    f.write(php_content)

print('Done.')
