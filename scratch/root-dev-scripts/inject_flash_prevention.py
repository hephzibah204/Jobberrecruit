import os

files_to_update = [
    r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\admin\layouts\app.php",
    r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\layouts\app.php",
    r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\templates\base.php"
]

script_to_inject = """<script>
        // Dark Mode Flash Prevention
        (function() {
            try {
                var theme = localStorage.getItem('jr-theme');
                if (!theme) {
                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>"""

for filepath in files_to_update:
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if 'jr-theme' not in content:
            new_content = content.replace('<head>', f'<head>\n    {script_to_inject}')
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Injected into {filepath}")
    else:
        print(f"File not found: {filepath}")
