import os
import re

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"
img_pattern = re.compile(r'<img\s+([^>]+)>', re.IGNORECASE)

missing_alt = 0
empty_alt = 0
total_img = 0

for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
                matches = img_pattern.findall(content)
                for attrs in matches:
                    total_img += 1
                    alt_match = re.search(r'alt="([^"]*)"', attrs, re.IGNORECASE)
                    if not alt_match:
                        missing_alt += 1
                    elif alt_match.group(1).strip() == "" or alt_match.group(1).lower() == "logo" or alt_match.group(1).lower() == "image":
                        empty_alt += 1

print(f"Total images found: {total_img}")
print(f"Missing alt attribute entirely: {missing_alt}")
print(f"Empty or unhelpful 'Logo'/'Image' alt: {empty_alt}")
