import os
import re
from collections import Counter

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"
style_pattern = re.compile(r'style="([^"]+)"', re.IGNORECASE)

styles_counter = Counter()
total_styles = 0

for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
                matches = style_pattern.findall(content)
                for m in matches:
                    s = m.strip().replace(' ', '').lower()
                    styles_counter[s] += 1
                    total_styles += 1

print(f"Total inline styles found: {total_styles}")
print("Top 20 most common inline styles:")
for k, v in styles_counter.most_common(20):
    print(f"{v} times: {k}")
