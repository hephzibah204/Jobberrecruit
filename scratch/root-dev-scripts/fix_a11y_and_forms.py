import os
import re

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"

def fix_icon_buttons(content):
    # Find <a> tags with title but no aria-label, containing only an <i> tag
    pattern = re.compile(r'<a([^>]*title="([^"]+)"[^>]*)>\s*<i([^>]+)></i>\s*</a>', re.IGNORECASE)
    
    def replacer(match):
        attrs = match.group(1)
        title = match.group(2)
        i_tag = match.group(3)
        if 'aria-label' not in attrs.lower():
            # append aria-label
            new_attrs = f'{attrs} aria-label="{title}"'
            return f'<a{new_attrs}><i{i_tag}></i></a>'
        return match.group(0)
        
    return pattern.sub(replacer, content)

def fix_form_widths(content):
    # Find input, select, textarea with style="width: 300px" or width="300"
    tags = ['input', 'select', 'textarea']
    for tag in tags:
        # Match style="width: XXpx;"
        pattern_style = re.compile(rf'<{tag}([^>]*)style="([^"]*)width:\s*\d+px;?([^"]*)"([^>]*)>', re.IGNORECASE)
        def replacer_style(match):
            before = match.group(1)
            style_before = match.group(2)
            style_after = match.group(3)
            after = match.group(4)
            
            # Remove width from style
            new_style = f'{style_before}{style_after}'.strip()
            style_attr = f'style="{new_style}"' if new_style else ''
            
            # Add class="w-100" if not present
            all_attrs = f'{before} {style_attr} {after}'
            if 'class="' in all_attrs:
                all_attrs = re.sub(r'class="([^"]*)"', lambda m: f'class="{m.group(1)} w-100"' if 'w-100' not in m.group(1) else m.group(0), all_attrs)
            else:
                all_attrs += ' class="w-100"'
                
            return f'<{tag} {all_attrs}>'
            
        content = pattern_style.sub(replacer_style, content)
        
        # Match width="XX"
        pattern_attr = re.compile(rf'<{tag}([^>]*)width="\d+"([^>]*)>', re.IGNORECASE)
        def replacer_attr(match):
            before = match.group(1)
            after = match.group(2)
            all_attrs = f'{before} {after}'
            if 'class="' in all_attrs:
                all_attrs = re.sub(r'class="([^"]*)"', lambda m: f'class="{m.group(1)} w-100"' if 'w-100' not in m.group(1) else m.group(0), all_attrs)
            else:
                all_attrs += ' class="w-100"'
            return f'<{tag} {all_attrs}>'
            
        content = pattern_attr.sub(replacer_attr, content)
        
    return content

updated_files = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            new_content = fix_icon_buttons(content)
            new_content = fix_form_widths(new_content)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                updated_files += 1

print(f"Updated {updated_files} files with aria-labels and form width fixes.")
