import os
import re

file_path = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views\webinars_public.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the wrong brand color
content = content.replace('#0861A9', '#0D609E')

# We need to strip out the global resets that bleed into base template.
# The CSS block starts around line 37 and goes to 100 roughly. Let's just use regex to remove the dangerous parts.
# 1. Remove the entire root variables block
content = re.sub(r':root\s*\{[^}]+\}', '', content)

# 2. Remove body, html, h1, h2, h3, a resets
content = re.sub(r'html\s*\{[^}]+\}', '', content)
content = re.sub(r'body\s*\{[^}]+\}', '', content)
content = re.sub(r'h1,h2,h3,\.nav-logo\s*\{[^}]+\}', '', content)
content = re.sub(r'a\s*\{[^}]+\}', '', content)
content = re.sub(r'a:hover\s*\{[^}]+\}', '', content)
content = re.sub(r'\*,\*::before,\*::after\s*\{[^}]+\}', '', content)

# 3. Remove .navbar, .nav-inner, .nav-links, etc
content = re.sub(r'\.navbar\s*\{[^}]+\}', '', content)
content = re.sub(r'\.nav-inner\s*\{[^}]+\}', '', content)
content = re.sub(r'\.nav-links[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\.nav-dropdown[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\.hamburger\s*\{[^}]+\}', '', content)
content = re.sub(r'\.mobile-nav[^\{]*\{[^}]+\}', '', content)

# 4. Remove btn global overrides (it breaks site buttons)
content = re.sub(r'\.btn[^\{]*\{[^}]+\}', '', content)
content = re.sub(r'\:focus-visible\s*\{[^}]+\}', '', content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed webinars_public.php CSS")
