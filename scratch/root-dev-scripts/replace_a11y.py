import os
import re

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"
tag_pattern = re.compile(r'<img([^>]+)>', re.IGNORECASE)

def process_img(match):
    attrs = match.group(1)
    
    # Does it have an alt attribute?
    alt_match = re.search(r'alt="([^"]*)"', attrs, re.IGNORECASE)
    
    if not alt_match:
        # Add an empty alt attribute to the end before the closing slash if it exists
        if attrs.endswith('/'):
            return f'<img{attrs[:-1]} alt="" />'
        else:
            return f'<img{attrs} alt="">'
            
    else:
        # Check if alt is unhelpful
        alt_val = alt_match.group(1)
        if alt_val.lower() == 'logo':
            new_attrs = attrs[:alt_match.start(1)] + "Company Logo" + attrs[alt_match.end(1):]
            return f'<img{new_attrs}>'
        if alt_val.lower() == 'image':
            new_attrs = attrs[:alt_match.start(1)] + "Graphic" + attrs[alt_match.end(1):]
            return f'<img{new_attrs}>'
            
    return match.group(0)

total_updated_files = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            new_content = tag_pattern.sub(process_img, content)
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                total_updated_files += 1

print(f"Updated {total_updated_files} files with missing alt attributes.")
