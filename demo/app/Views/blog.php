<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "JobberRecruit Blog",
        "description": "Insights, tips, and updates to help job seekers and employers succeed.",
        "url": "<?= current_url() ?>",
        "mainEntity": {
            "@type": "ItemList",
            "numberOfItems": "<?= count($blogs) ?>",
            "itemListElement": [
                <?php foreach ($blogs as $index => $blog): ?> {
                        "@type": "ListItem",
                        "position": <?= $index + 1 ?>,
                        "item": {
                            "@type": "BlogPosting",
                            "headline": "<?= addslashes($blog->title) ?>",
                            "description": "<?= addslashes($blog->excerpt) ?>",
                            "url": "<?= base_url('blog/' . $blog->slug) ?>",
                            "image": "<?= esc($blog->thumbnail ?: base_url('images/blog-default.jpg')) ?>",
                            "datePublished": "<?= date('c', strtotime($blog->created_at)) ?>",
                            "author": {
                                "@type": "Organization",
                                "name": "JobberRecruit"
                            }
                        }
                    }
                    <?= $index < count($blogs) - 1 ? ',' : '' ?>
                <?php endforeach; ?>
            ]
        }
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BLOG HERO -->
<section class="blog-hero-section py-5 bg-gradient-primary text-white">
    <div class="container text-center">
        <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
            <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Blog</li>
            </ol>
        </nav>

        <h1 class="display-4 fw-bold mb-3">JobberRecruit Blog</h1>
        <p class="lead col-lg-8 mx-auto">
            Expert insights, actionable tips, and latest updates to help job seekers land their dream jobs
            and employers find top talent.
        </p>

        <!-- Stats -->
        <div class="row justify-content-center mt-4 pt-3">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <h3 class="fw-bold"><?= number_format($totalPosts ?? 0) ?>+</h3>
                    <p class="small mb-0">Articles</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <h3 class="fw-bold"><?= number_format($totalViews ?? 0) ?>+</h3>
                    <p class="small mb-0">Total Reads</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH BAR -->
<section class="search-section py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form method="get" action="<?= base_url('blog') ?>" class="search-form">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-dark"></i>
                        </span>
                        <input type="search"
                            name="q"
                            value="<?= esc($q ?? '') ?>"
                            class="form-control border-start-0"
                            placeholder="Search articles, tips, guides..."
                            aria-label="Search blog articles">
                        <button type="submit" class="btn btn-secondary px-4">
                            Search
                        </button>
                    </div>
                    <?php if ($q): ?>
                        <div class="mt-3">
                            <span class="text-muted small">
                                Found <?= $pager->getTotal() ?? 0 ?> result(s) for "<strong><?= esc($q) ?></strong>"
                            </span>
                            <a href="<?= base_url('blog') ?>" class="text-primary small ms-3 text-decoration-none">
                                <i class="bi bi-x-circle me-1"></i>Clear search
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- BLOG CONTENT -->
<section class="blog-section py-5">
    <div class="container">
        <div class="row g-2">

            <!-- MAIN CONTENT -->
            <div class="col-lg-8">
                <?php if (!empty($blogs)): ?>

                    <!-- Filters/Sorting (Optional) -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 fw-bold mb-0">
                            <?php if ($q): ?>
                                Search Results
                            <?php else: ?>
                                Latest Articles
                            <?php endif; ?>
                        </h2>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-sort-down me-1"></i>Sort By
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= current_url() ?>?sort=newest">Newest First</a></li>
                                <li><a class="dropdown-item" href="<?= current_url() ?>?sort=popular">Most Popular</a></li>
                                <li><a class="dropdown-item" href="<?= current_url() ?>?sort=oldest">Oldest First</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Ad-Res -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-3464186884176173"
                        data-ad-slot="6229476516"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>

                    <!-- Blog Grid -->
                    <div class="row g-4">
                        <?php foreach ($blogs as $blog): ?>
                            <div class="col-12">
                                <article class="blog-post-card card shadow-sm border-0 h-100 hover-lift">
                                    <div class="row g-0">
                                        <?php if (!empty($blog->thumbnail)): ?>
                                            <div class="col-md-4">
                                                <div class="position-relative overflow-hidden" style="height: 100%; min-height: 200px;">
                                                    <img src="<?= esc($blog->thumbnail) ?>"
                                                        alt="<?= esc($blog->title) ?>"
                                                        loading="lazy"
                                                        class="img-fluid h-100 w-100 object-fit-cover">
                                                    <div class="position-absolute top-0 start-0 m-3">
                                                        <span class="badge bg-primary bg-opacity-75">
                                                            <i class="bi bi-clock me-1"></i>
                                                            <?= max(1, ceil(str_word_count(strip_tags($blog->content)) / 200)) ?> min
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                            <?php else: ?>
                                                <div class="col-12">
                                                <?php endif; ?>
                                                <div class="card-body p-4">
                                                    <!-- Meta Info -->
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="d-flex align-items-center me-3">
                                                            <i class="bi bi-calendar2 text-muted me-1"></i>
                                                            <time datetime="<?= date('Y-m-d', strtotime($blog->created_at)) ?>"
                                                                class="text-muted small">
                                                                <?= date('F j, Y', strtotime($blog->created_at)) ?>
                                                            </time>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-eye text-muted me-1"></i>
                                                            <span class="text-muted small"><?= number_format($blog->views) ?> reads</span>
                                                        </div>
                                                    </div>

                                                    <!-- Title -->
                                                    <h2 class="h4 fw-bold mb-3">
                                                        <a href="<?= base_url('blog/' . $blog->slug) ?>"
                                                            class="text-white text-decoration-none stretched-link">
                                                            <?= esc($blog->title) ?>
                                                        </a>
                                                    </h2>

                                                    <!-- Excerpt -->
                                                    <p class="text-muted mb-4">
                                                        <?= esc($blog->excerpt) ?>
                                                    </p>

                                                    <!-- Footer -->
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-3">
                                                                <span class="text-muted small">By</span>
                                                                <span class="fw-medium small"><?= esc($blog->author ?? 'JobberRecruit Team') ?></span>
                                                            </div>
                                                        </div>
                                                        <span class="text-primary small fw-medium">
                                                            Read More <i class="bi bi-arrow-right ms-1"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <!-- No Results -->
                    <div class="text-center py-5 my-5">
                        <div class="mb-4">
                            <i class="bi bi-search display-1 text-muted"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">No articles found</h3>
                        <p class="text-muted mb-4">
                            <?php if ($q): ?>
                                No results found for "<strong><?= esc($q) ?></strong>". Try different keywords or browse all articles.
                            <?php else: ?>
                                No blog posts published yet. Check back soon!
                            <?php endif; ?>
                        </p>
                        <?php if ($q): ?>
                            <a href="<?= base_url('blog') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>View All Articles
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?= $pager->links('default', 'blog_pager') ?>

            </div>

            <!-- SIDEBAR -->
            <aside class="col-lg-4">

                <!-- Popular Posts -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary border-0 pt-4 pb-3">
                        <h5 class="fw-bold mb-0 text-white">
                            <i class="bi bi-fire text-danger me-2"></i>Trending Articles
                        </h5>
                    </div>
                    <div class="card-body pt-2">
                        <?php if (!empty($popularPosts)): ?>
                            <?php foreach ($popularPosts as $index => $post): ?>
                                <div class="d-flex align-items-start mb-3 pb-3 <?= $index < count($popularPosts) - 1 ? 'border-bottom' : '' ?>">
                                    <div class="flex-shrink-0 me-3">
                                        <span class="badge bg-light rounded-circle d-flex align-items-center justify-content-center text-dark"
                                            style="width: 32px; height: 32px;">
                                            <?= $index + 1 ?>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">
                                            <a href="<?= base_url('blog/' . $post->slug) ?>"
                                                class="text-white text-decoration-none">
                                                <?= esc($post->title) ?>
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center text-muted small">
                                            <span>
                                                <i class="bi bi-eye me-1"></i><?= number_format($post->views) ?>
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span><?= date('M d', strtotime($post->created_at)) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small mb-0">No popular posts yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ad-Res -->
                <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-3464186884176173"
                    data-ad-slot="6229476516"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>

                <!-- Categories/Topics -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary border-0 pt-4 pb-3">
                        <h5 class="fw-bold mb-0 text-white">
                            <i class="bi bi-bookmark me-2"></i>Explore Topics
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $topics = [
                                'Job Search' => 'primary',
                                'Interview Tips' => 'success',
                                'CV Writing' => 'info',
                                'Career Growth' => 'warning',
                                'Salary Negotiation' => 'danger',
                                'Remote Work' => 'dark',
                                'Recruitment' => 'secondary',
                                'Industry Trends' => 'purple'
                            ];

                            foreach ($topics as $topic => $color):
                                $url = base_url('blog') . '?q=' . urlencode($topic);
                            ?>
                                <a href="<?= $url ?>"
                                    class="badge bg-<?= $color ?> bg-opacity-10 text-white border border-white border-opacity-25 text-decoration-none py-2 px-3">
                                    <?= $topic ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Social Follow -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary border-0 pt-4 pb-3">
                        <h5 class="fw-bold mb-0 text-white">
                            <i class="bi bi-people me-2"></i>Follow Us
                        </h5>
                    </div>
                    <div class="card-body pt-0">
                        <p class="text-muted small mb-3">
                            Join our community for daily career tips and job opportunities.
                        </p>
                        <div class="d-flex gap-2 text-white">
                            <a href="#" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-facebook me-1 text-white"></i>Facebook
                            </a>
                            <a href="#" class="btn btn-outline-dark btn-sm flex-fill">
                                <i class="bi bi-twitter-x me-1"></i>Twitter
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm flex-fill">
                                <i class="bi bi-linkedin me-1"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Custom Blog Styles */
    .blog-hero-section {
        background: linear-gradient(135deg, #F0890E 0%, #bb6804ff 100%);
        position: relative;
        overflow: hidden;
    }

    .blog-hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
    }

    .stat-item {
        padding: 1rem;
        background: #0D609E;
        border-radius: 0.75rem;
        backdrop-filter: blur(10px);
    }

    .stat-item p {
        color: #f8f9fa !important;
    }

    .stat-item h3 {
        font-size: 2.5rem;
        margin-bottom: 0.25rem;
        color: #fff;
    }

    .breadcrumb-light .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }

    .breadcrumb-light .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-light .breadcrumb-item a:hover {
        color: white;
    }

    .search-section {
        background-color: #0D609E;
    }

    .search-section .btn-secondary {
        background-color: #F0890E !important;
        border-color: #F0890E !important;
    }

    .search-form .form-control {
        border: none !important;
        outline: none !important;
    }

    .search-form .form-control::placeholder {
        font-size: 14px;
    }

    .search-form .input-group-text {
        background-color: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.7);
    }

    .search-form .input-group {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .search-form.form-control:active {
        border: none !important;
        outline: none !important
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .bg-primary {
        background-color: #0D609E !important;
        color: #fff !important;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .btn-outline-primary {
        color: #0D609E !important;
        border-color: #fff !important;
    }

    a.btn-outline-primary {
        background-color: #0D609E !important;
        color: #fff !important;
    }

    /* Pagination */
    .pagination .page-item.active .page-link {
        background-color: #0D609E;
        border-color: #0D609E;
    }

    .pagination .page-link {
        color: #0D609E;
        border-radius: 0.375rem;
        margin: 0 0.25rem;
    }

    .pagination .page-link:hover {
        background-color: #f8f9fa;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .blog-hero-section h1 {
            font-size: 2.5rem;
        }

        .stat-item h3 {
            font-size: 2rem;
        }

        .search-form .input-group {
            flex-direction: column;
        }

        .search-form .input-group>* {
            width: 100%;
            border-radius: 0.5rem !important;
            margin-bottom: 0.5rem;
        }

        .search-form button {
            margin-left: 0 !important;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .card {
            background-color: #0D609E;
            border-color: #0D609E;
        }

        .text-dark {
            color: #0D609E !important;
        }

        .text-muted {
            color: #fff !important;
        }

        .bg-light {
            background-color: #fff !important;
        }

        .border-bottom {
            border-color: #0D609E !important;
        }

        .bg-secondary {
            background: #F0890E !important;
            color: #fff !important;
        }
    }

    /* Blog Listing Specific Styles */
    .blog-post-card {
        border-radius: 0.75rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .blog-post-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }

    .blog-post-card .stretched-link::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        content: "";
    }

    .blog-post-card img {
        transition: transform 0.5s ease;
    }

    .blog-post-card:hover img {
        transform: scale(1.05);
    }

    /* Reading time badge */
    .badge.bg-primary.bg-opacity-75 {
        backdrop-filter: blur(4px);
    }

    /* Topic badges */
    .badge.bg-opacity-10 {
        transition: all 0.2s ease;
    }

    .badge.bg-opacity-10:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Pagination animation */
    .page-link {
        transition: all 0.2s ease;
    }

    .page-item.active .page-link {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        color: #fff !important;
    }

    .page-link:hover {
        transform: translateY(-2px);
    }

    /* Loading animation for cards */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .blog-post-card {
        animation: fadeInUp 0.5s ease forwards;
    }

    /* Stagger animation for multiple cards */
    .blog-post-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .blog-post-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .blog-post-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .blog-post-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .blog-post-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .blog-post-card:nth-child(6) {
        animation-delay: 0.6s;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Newsletter form validation
    document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        // Simulate submission
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Subscribing...';
        submitBtn.disabled = true;

        setTimeout(() => {
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Subscribed!';
            submitBtn.classList.remove('btn-light');
            submitBtn.classList.add('btn-success');

            // Reset form
            this.reset();
            this.classList.remove('was-validated');

            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show mt-3';
            alert.innerHTML = `
            <strong>Success!</strong> You've been subscribed to our newsletter.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
            this.appendChild(alert);

            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-light');
            }, 3000);
        }, 1500);
    });

    // Search input focus effect
    const searchInput = document.querySelector('input[name="q"]');
    searchInput?.addEventListener('focus', function() {
        this.parentElement.classList.add('focus-ring');
    });

    searchInput?.addEventListener('blur', function() {
        this.parentElement.classList.remove('focus-ring');
    });

    // Smooth scroll for pagination links
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');

            // Smooth scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Navigate after scroll
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 300);
        });
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>