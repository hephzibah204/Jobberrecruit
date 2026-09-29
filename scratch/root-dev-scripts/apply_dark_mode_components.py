import os

style_css_path = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\public\css\style.css"

dark_mode_css = """
/* ==========================================================================
   Dark Mode Component Overrides
   ========================================================================== */

[data-theme="dark"] body {
    background-color: var(--bg-white);
    color: var(--text-main);
}

[data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3, 
[data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 {
    color: var(--text-main);
}

[data-theme="dark"] .card-glass {
    background: rgba(15, 23, 42, 0.45); /* Dark translucent */
    border: 1px solid rgba(255, 255, 255, 0.05);
}

[data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
    background-color: var(--bg-light);
    color: var(--text-main);
    border-color: var(--border-light);
}

[data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
    background-color: var(--bg-light);
    color: var(--text-main);
    border-color: var(--primary-color);
}

[data-theme="dark"] .modal-content {
    background-color: var(--bg-white);
    color: var(--text-main);
}

[data-theme="dark"] .table-striped>tbody>tr:nth-of-type(odd)>* {
    color: var(--text-main);
    box-shadow: inset 0 0 0 9999px rgba(255, 255, 255, 0.05);
}

[data-theme="dark"] .skeleton {
    background: linear-gradient(90deg, #1e293b 25%, #334155 37%, #1e293b 63%);
    background-size: 400% 100%;
}

[data-theme="dark"] .toast-success, [data-theme="dark"] .toast-error, [data-theme="dark"] .toast-info, [data-theme="dark"] .toast-warning {
    color: #fff; /* Ensure toast text is readable */
}

/* Base link colors on dark bg */
[data-theme="dark"] a:not(.btn) {
    color: var(--info-color);
}
[data-theme="dark"] a:not(.btn):hover {
    color: var(--info-light);
}
"""

if os.path.exists(style_css_path):
    with open(style_css_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    if "Dark Mode Component Overrides" not in content:
        with open(style_css_path, 'a', encoding='utf-8') as f:
            f.write(dark_mode_css)
        print("Dark mode CSS appended to style.css")
else:
    print("style.css not found")
