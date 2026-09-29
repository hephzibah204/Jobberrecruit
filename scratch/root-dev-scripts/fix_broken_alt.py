import os

base_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views"

updated = 0
for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            new_content = content.replace('? alt="">', '?>')
            new_content = new_content.replace('- alt="">', '->')
            new_content = new_content.replace('= alt="">', '=>')
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                updated += 1

print(f"Fixed {updated} files with broken alt injections.")
