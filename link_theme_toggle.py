import os

files_to_update = [
    r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\admin\layouts\app.php",
    r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\layouts\app.php",
    r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\app\Views\templates\base.php"
]

script_tag = """    <script src="<?= base_url('js/theme-toggle.js'); ?>" type="text/javascript"></script>\n"""

for filepath in files_to_update:
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if 'theme-toggle.js' not in content:
            # Try to insert after script.js, or before </body>
            if "script.js');" in content:
                new_content = content.replace("script.js'); ?>\" type=\"text/javascript\"></script>", "script.js'); ?>\" type=\"text/javascript\"></script>\n" + script_tag)
            else:
                new_content = content.replace("</body>", script_tag + "</body>")
                
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Injected script tag into {filepath}")
    else:
        print(f"File not found: {filepath}")
