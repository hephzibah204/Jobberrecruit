import os
import re

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"

style_to_class = {
    'display:none': 'd-none',
    'display:block': 'd-block',
    'text-align:center': 'text-center',
    'text-align:right': 'text-end',
    'text-align:left': 'text-start',
    'width:100%': 'w-100',
    'font-weight:bold': 'fw-bold',
    'cursor:pointer': 'cursor-pointer',
    'object-fit:cover': 'object-fit-cover',
    'border-radius:6px': 'rounded-sm',
    'border-radius:8px': 'rounded',
    'border-radius:10px': 'rounded-md',
    'border-radius:12px': 'rounded-lg',
    'border-radius:16px': 'rounded-xl',
    'float:left': 'float-start',
    'float:right': 'float-end',
    'margin-top:0': 'mt-0',
    'margin-bottom:0': 'mb-0',
    'color:var(--text-main)': 'text-main',
    'color:var(--primary-color)': 'text-primary',
    'color:var(--secondary-color)': 'text-secondary',
}

tag_pattern = re.compile(r'<([a-zA-Z0-9]+)([^>]*)>')

def process_tag(match):
    tag_name = match.group(1)
    attrs = match.group(2)
    
    # Check if there is a style attribute
    style_match = re.search(r'style="([^"]*)"', attrs, re.IGNORECASE)
    if not style_match:
        return match.group(0)
        
    original_style = style_match.group(1)
    # Split styles by semicolon
    styles = [s.strip() for s in original_style.split(';') if s.strip()]
    
    remaining_styles = []
    classes_to_add = []
    
    for s in styles:
        # Normalize
        normalized = s.replace(' ', '').lower()
        if normalized in style_to_class:
            classes_to_add.append(style_to_class[normalized])
        else:
            # Keep it
            remaining_styles.append(s)
            
    if not classes_to_add:
        # No replacements made
        return match.group(0)
        
    # Reconstruct style attribute
    new_style_str = ""
    if remaining_styles:
        new_style_str = f'style="{"; ".join(remaining_styles)}"'
        
    # Remove old style attribute
    new_attrs = attrs[:style_match.start()] + attrs[style_match.end():]
    
    # Add to existing class or create new one
    class_match = re.search(r'class="([^"]*)"', new_attrs, re.IGNORECASE)
    if class_match:
        old_class = class_match.group(1)
        # add only if not already present
        new_classes = old_class.split()
        for c in classes_to_add:
            if c not in new_classes:
                new_classes.append(c)
        new_class_str = 'class="' + " ".join(new_classes) + '"'
        new_attrs = new_attrs[:class_match.start()] + new_class_str + new_attrs[class_match.end():]
    else:
        # insert class
        new_class_str = ' class="' + " ".join(classes_to_add) + '"'
        new_attrs += new_class_str
        
    # Re-insert new style if needed
    if new_style_str:
        new_attrs += ' ' + new_style_str
        
    # Clean up multiple spaces
    new_attrs = re.sub(r'\s+', ' ', new_attrs)
    
    return f"<{tag_name}{new_attrs}>"

total_updated_files = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            new_content = tag_pattern.sub(process_tag, content)
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                total_updated_files += 1

print(f"Updated {total_updated_files} files.")
