import os

html_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\Doc\UI and UX Doc\jobberrecruit HTML Files"
views_dir = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views"

html_files = [f for f in os.listdir(html_dir) if f.endswith('.html')]

# Counterpart map heuristics
def find_counterparts(html_name):
    # Strip suffixes and clean up
    base = html_name.lower().replace(' (1)', '').replace(' (2)', '').replace(' (3)', '').replace('.html', '')
    
    # Heuristics mapping base name to likely view files
    potential_matches = []
    
    for root, dirs, files in os.walk(views_dir):
        for f in files:
            if f.endswith('.php'):
                f_base = f.lower().replace('.php', '')
                # check similarity
                if f_base == base or base in f_base or f_base in base:
                    potential_matches.append(os.path.join(root, f))
    
    # Specific maps
    custom_maps = {
        'homepage.html': ['home/index.php'],
        'blog (1).html': ['blog/index.php', 'home/blogs.php'],
        'blog-article (1).html': ['blog/view.php', 'home/blog_detail.php'],
        'candidate-profile.html': ['candidate/profile.php', 'jobseeker/profile.php'],
        'employer-profile.html': ['employer/profile.php', 'employers/profile.php'],
        'job-apply.html': ['home/apply.php', 'job/apply.php'],
        'job-detail.html': ['home/view_job.php', 'job/view.php'],
        'training (1).html': ['training/index.php', 'home/elearning.php'],
        'recruitment-services.html': ['home/recruitment_services.php']
    }
    
    if html_name in custom_maps:
        custom_matches = []
        for path in custom_maps[html_name]:
            full_path = os.path.join(views_dir, path.replace('/', os.sep))
            if os.path.exists(full_path):
                custom_matches.append(full_path)
        if custom_matches:
            return custom_matches

    return list(set(potential_matches))

print(f"{'HTML Template':<45} | {'PHP Counterpart(s)':<55}")
print("-" * 110)
for hf in sorted(html_files):
    matches = find_counterparts(hf)
    rel_matches = [os.path.relpath(m, views_dir) for m in matches]
    matches_str = ", ".join(rel_matches) if rel_matches else "NOT FOUND"
    print(f"{hf:<45} | {matches_str:<55}")
