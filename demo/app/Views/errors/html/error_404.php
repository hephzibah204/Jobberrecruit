<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | JobberRecruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #0f172a; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-container { text-align: center; padding: 2rem; }
        .error-code { font-size: 8rem; font-weight: 700; background: linear-gradient(135deg, #0ea5e9, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
        .error-message { font-size: 1.25rem; color: #94a3b8; margin: 1rem 0 2rem; }
        .btn-primary { background: #0ea5e9; border: none; padding: 0.75rem 2rem; border-radius: 50px; font-weight: 600; }
        .btn-primary:hover { background: #0284c7; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="h3 mb-2">Page Not Found</h1>
        <p class="error-message">The page you are looking for does not exist or has been moved.</p>
        <a href="<?= base_url() ?>" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
