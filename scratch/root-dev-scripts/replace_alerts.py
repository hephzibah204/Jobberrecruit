import os
import glob
import re

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"

def replace_alert_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # We will use regex to find alert(something)
    # Be careful not to replace showAlert
    
    # Let's replace simple string alerts
    # If it contains success, we use toastr.success
    # If it contains error, failed, or we use toastr.error
    # Otherwise toastr.info
    
    def replacer(match):
        full_match = match.group(0)
        inner = match.group(1)
        
        lower_inner = inner.lower()
        if 'error' in lower_inner or 'fail' in lower_inner or 'wrong' in lower_inner:
            return f"toastr.error({inner})"
        elif 'success' in lower_inner or 'thank you' in lower_inner:
            return f"toastr.success({inner})"
        else:
            return f"toastr.info({inner})"
            
    # Regex: match alert( ... ) that are not part of showAlert or other words
    # (?<!\w)alert\((.*?)\);?
    new_content = re.sub(r'(?<!\w)alert\((.*?)\)', replacer, content)
    
    if new_content != content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath}")

for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php') or file.endswith('.js'):
            replace_alert_in_file(os.path.join(root, file))

print("Done replacing alerts!")
