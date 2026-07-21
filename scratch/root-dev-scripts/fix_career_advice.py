import re

file_path = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views\career_advice.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the wrong brand color just in case
content = content.replace('#0861A9', '#0D609E')

# Strip out the global resets that bleed into base template.
content = re.sub(r':root\s*\{[^}]+\}', '', content)
content = re.sub(r'html\s*\{[^}]+\}', '', content)
content = re.sub(r'body\s*\{[^}]+\}', '', content)
content = re.sub(r'h1, h2, h3, h4, \.nav-logo, \.display\s*\{[^}]+\}', '', content)
content = re.sub(r'a\s*\{[^}]+\}', '', content)
content = re.sub(r'a:hover\s*\{[^}]+\}', '', content)
content = re.sub(r'\*,\s*\*::before,\s*\*::after\s*\{[^}]+\}', '', content)
content = re.sub(r'\.navbar\s*\{[^}]+\}', '', content)
content = re.sub(r'\.nav-inner\s*\{[^}]+\}', '', content)
content = re.sub(r'\.nav-links[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\.nav-dropdown[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\.hamburger\s*\{[^}]+\}', '', content)
content = re.sub(r'\.mobile-nav[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\.btn[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\:focus-visible\s*\{[^}]+\}', '', content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed career_advice.php CSS")
