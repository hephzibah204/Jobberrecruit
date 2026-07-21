import re
path = r'C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\Doc\UI and UX Doc\jobberrecruit HTML Files\job-detail.html'
with open(path, 'r', encoding='utf-8') as f:
    html = f.read()

# Extract styles
style_match = re.search(r'<style>(.*?)</style>', html, re.DOTALL)
styles = style_match.group(1) if style_match else ''

# Extract body
match = re.search(r'(<div class="job-detail-layout">.*?</main>)', html, re.DOTALL)
if match:
    layout = match.group(1)
    print('Found layout length:', len(layout))
    with open('demo/extracted_layout.html', 'w', encoding='utf-8') as f:
        f.write(layout)
    with open('demo/extracted_styles.css', 'w', encoding='utf-8') as f:
        f.write(styles)
