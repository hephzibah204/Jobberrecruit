import os

views_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views"

for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            if '#0861A9' in content:
                new_content = content.replace('#0861A9', '#0D609E')
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Fixed color in: {filepath}")
