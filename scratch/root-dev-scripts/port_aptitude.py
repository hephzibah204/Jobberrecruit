import os
import re

src_dir = r"C:\Users\hephz\Downloads\aptitude-test"
dest_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views\candidate\aptitude"
os.makedirs(dest_dir, exist_ok=True)

files = {
    "aptitude-hub.html": "hub.php",
    "aptitude-test.html": "test_engine.php",
    "aptitude-result-practice.html": "result_practice.php",
    "aptitude-result-official.html": "result_official.php"
}

def extract_content(html):
    # Extract style block if any
    style_match = re.search(r'<style>(.*?)</style>', html, re.DOTALL)
    style = style_match.group(1) if style_match else ""
    
    # Replace brand color
    style = style.replace('#0861A9', '#0D609E')

    # Extract body content (between <body> and </body>, excluding nav and footer)
    # The templates usually have <nav class="nav"> and <footer class="footer">
    body_match = re.search(r'<body[^>]*>(.*?)</body>', html, re.DOTALL)
    body = body_match.group(1) if body_match else html
    
    # Remove nav if exists
    body = re.sub(r'<nav class="nav.*?</nav>', '', body, flags=re.DOTALL)
    body = re.sub(r'<footer class="footer.*?</header>', '', body, flags=re.DOTALL) # wait, header?
    body = re.sub(r'<header class="nav.*?</header>', '', body, flags=re.DOTALL)
    body = re.sub(r'<footer class="footer.*?</footer>', '', body, flags=re.DOTALL)
    
    # Remove script tags that load jquery/bootstrap (handled by base)
    body = re.sub(r'<script src="https://code.jquery.com.*?</script>', '', body, flags=re.DOTALL)

    return f"""<?= $this->extend('templates/base') ?>

<?= $this->section('page_title') ?>Aptitude Test<?= $this->endSection() ?>

<?= $this->section('custom_css') ?>
<style>
{style}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
{body}
<?= $this->endSection() ?>
"""

for src_name, dest_name in files.items():
    src_path = os.path.join(src_dir, src_name)
    dest_path = os.path.join(dest_dir, dest_name)
    
    if os.path.exists(src_path):
        with open(src_path, 'r', encoding='utf-8') as f:
            content = f.read()
            
        new_content = extract_content(content)
        
        with open(dest_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Ported {src_name} to {dest_name}")
    else:
        print(f"File {src_path} not found")
