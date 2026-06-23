import re

job_html = open(r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\Doc\UI and UX Doc\jobberrecruit HTML Files\job-detail.html', 'r', encoding='utf-8').read()

# 1. Extract styles
styles_match = re.search(r'(/\* ══════════════════════════════════════════════════════════════════════\s*/jobs/{slug} JOB DETAIL PAGE.*?</style>)', job_html, re.DOTALL | re.IGNORECASE)

styles = ''
if styles_match:
    styles = styles_match.group(1).replace('</style>', '')

mobile_dark = re.search(r'(/\* ════════════════════════════════════════════════════════════════════\s*iOS / mobile AUTO-DARK DEFEAT.*?)</style>', job_html, re.DOTALL | re.IGNORECASE)
if mobile_dark and mobile_dark.group(1) not in styles:
    styles += '\n' + mobile_dark.group(1)

# Ensure brand colors are correct
styles = styles.replace('--brand:        #0861A9;', '--brand:        #0D609E;')
styles = styles.replace('--brand-dark:   #064A85;', '--brand-dark:   #0A4D7E;')
styles = styles.replace('--brand-deep:   #0A2F57;', '--brand-deep:   #07304F;')
styles = styles.replace('--brand-light:  #E6F0F8;', '--brand-light:  #E6F0F9;')
styles = styles.replace('--accent:       #ED9020;', '--accent:       #F08F1A;')
styles = styles.replace('--accent-dark:  #C8770E;', '--accent-dark:  #C8750E;')
styles = styles.replace('#0A2F57', 'var(--brand-deep)')

open('temp_styles.css', 'w', encoding='utf-8').write(styles)

# 2. Extract main content
main_match = re.search(r'(<main id="main">.*?</main>)', job_html, re.DOTALL | re.IGNORECASE)
if main_match:
    main_content = main_match.group(1)
    open('temp_main.html', 'w', encoding='utf-8').write(main_content)
else:
    print('main content not found')
