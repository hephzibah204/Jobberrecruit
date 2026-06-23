import re
html = open(r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\Doc\UI and UX Doc\jobberrecruit HTML Files\jobberrecruit-job-apply.html', 'r', encoding='utf-8').read()
match = re.search(r'(<main id="main">.*?</main>)', html, re.DOTALL | re.IGNORECASE)
if match:
    open('temp_apply_main.html', 'w', encoding='utf-8').write(match.group(1))
