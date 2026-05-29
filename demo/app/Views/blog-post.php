<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<link rel="canonical" href="<?= base_url('blog/' . $blog->slug) ?>">

<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BlogPosting',
    'headline' => $blog->title,
    'description' => $meta_description ?? '',
    'image'    => $og_image ?? '',
    'author'   => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'url'   => base_url(),
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'logo'  => [
            '@type'  => 'ImageObject',
            'url'    => base_url('images/logo.png'),
            'width'  => 600,
            'height' => 60,
        ],
    ],
    'datePublished'  => date(DATE_ATOM, strtotime($blog->created_at)),
    'dateModified'   => date(DATE_ATOM, strtotime($blog->updated_at ?? $blog->created_at)),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id'   => current_url(),
    ],
    'wordCount'    => str_word_count(strip_tags($blog->content)),
    'timeRequired' => 'PT' . ($readingTime ?? 5) . 'M',
    'keywords'     => $blog->tags ?? '',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => base_url('blog')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $blog->title, 'item' => current_url()],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Reading Progress Bar -->
<div class="reading-progress">
    <div class="reading-progress-bar" id="readingProgress"></div>
</div>

<!-- BREADCRUMB -->
<nav aria-label="breadcrumb" class="container py-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('blog') ?>">Blog</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($blog->title) ?></li>
    </ol>
</nav>

<!-- BLOG HERO -->
<section class="blog-hero-section py-5 bg-gradient-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <!-- Category Badge -->
                <?php

                use App\Models\BlogModel;

                if (!empty($blog->category)): ?>
                    <a href="<?= base_url('blog/category/' . url_title($blog->category)) ?>"
                        class="badge bg-primary mb-3 text-decoration-none">
                        <?= esc($blog->category) ?>
                    </a>
                <?php endif; ?>

                <h1 class="display-4 fw-bold mb-3"><?= esc($blog->title ?? 'Blog Title Here') ?></h1>

                <!-- Meta Information -->
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mb-4 text-muted">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <span><?= esc($blog->author ?? 'JobberRecruit Team') ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar2 me-2"></i>
                        <time datetime="<?= date('Y-m-d', strtotime($blog->created_at)) ?>">
                            <?= date('F j, Y', strtotime($blog->created_at ?? date('Y-m-d'))) ?>
                        </time>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-2"></i>
                        <span><?= $readingTime ?? 5 ?> min read</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-eye me-2"></i>
                        <span><?= number_format($blog->views ?? 0) ?> views</span>
                    </div>
                </div>

                <!-- Hero Image -->
                <?php if (!empty($blog->thumbnail)): ?>
                    <div class="hero-image-container mt-4">
                        <img src="<?= $blog->thumbnail ?>"
                            class="img-fluid rounded-3 shadow-lg hero-image"
                            alt="<?= esc($blog->title) ?>"
                            loading="eager">
                        <?php if (!empty($blog->image_caption)): ?>
                            <p class="text-muted small mt-2 text-center">
                                <i><?= esc($blog->image_caption) ?></i>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="hero-image-container mt-4">
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            class="img-fluid rounded-3 shadow-sm hero-image"
                            alt="<?= esc($blog->title) ?>"
                            loading="lazy">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="single-blog-section py-5">
    <div class="container">
        <div class="row g-2">

            <!-- ARTICLE & SIDEBAR -->
            <div class="col-lg-8">
                <article class="blog-article mb-5" id="blog-content">
                    <!-- Table of Contents (Auto-generated if needed) -->
                    <?php if (str_word_count(strip_tags($blog->content)) > 1000): ?>
                        <div class="toc-card card shadow-sm mb-5 d-lg-none">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Table of Contents</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="toc-list" id="mobileToc"></div>
                            </div>
                        </div>
                    <?php endif; ?>
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

                    <!-- Blog Content -->
                    <div class="content-wrapper">
                        <?= $blog->content ?? '<p class="text-muted">Blog content goes here...</p>' ?>
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

                    <!-- Reading Time & Views -->
                    <div class="d-flex justify-content-between align-items-center border-top border-bottom py-3 my-5">
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i> <?= $readingTime ?? 5 ?> min read
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-eye me-1"></i> <?= number_format($blog->views ?? 0) ?> views
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar2 me-1"></i> Updated:
                            <?= date('M d, Y', strtotime($blog->updated_at ?? $blog->created_at)) ?>
                        </div>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($blog->tags)): ?>
                        <div class="mb-5">
                            <h6 class="fw-semibold mb-3">Related Topics</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach (explode(',', $blog->tags) as $tag):
                                    $trimmedTag = trim($tag);
                                    if (!empty($trimmedTag)): ?>
                                        <a href="<?= base_url('blog/tag/' . url_title($trimmedTag)) ?>"
                                            class="badge bg-light text-dark border py-2 px-3 text-decoration-none">
                                            <i class="bi bi-tag me-1"></i><?= esc($trimmedTag) ?>
                                        </a>
                                <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Share Section -->
                    <div class="share-section my-5 p-4 bg-light rounded-3">
                        <h5 class="fw-semibold mb-3">Share This Article</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $currentUrl = current_url();
                            $encodedUrl = urlencode($currentUrl);
                            $encodedTitle = urlencode($blog->title);
                            $encodedDesc = urlencode($meta_description ?? '');
                            ?>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="btn btn-outline-primary share-btn">
                                <i class="bi bi-facebook me-2"></i>Facebook
                                <span class="badge bg-primary ms-2 share-count">0</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedTitle ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="btn btn-outline-info share-btn">
                                <i class="bi bi-twitter me-2"></i>Twitter
                                <span class="badge bg-info ms-2 share-count">0</span>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $encodedUrl ?>&title=<?= $encodedTitle ?>&summary=<?= $encodedDesc ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="btn btn-outline-dark share-btn">
                                <i class="bi bi-linkedin me-2"></i>LinkedIn
                                <span class="badge bg-dark ms-2 share-count">0</span>
                            </a>
                            <button onclick="copyToClipboard('<?= $currentUrl ?>')"
                                class="btn btn-outline-success share-btn">
                                <i class="bi bi-link-45deg me-2"></i>Copy Link
                            </button>
                        </div>
                    </div>

                    <!-- Author Card -->
                    <div class="author-card card shadow-sm mb-5">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($blog->author ?? 'JobberRecruit') ?>&background=667eea&color=fff&size=100"
                                        class="rounded-circle"
                                        width="80" height="80"
                                        alt="<?= esc($blog->author ?? 'JobberRecruit Team') ?>">
                                </div>
                                <div class="col">
                                    <h5 class="fw-bold mb-1"><?= esc($blog->author ?? 'JobberRecruit Team') ?></h5>
                                    <p class="text-light mb-2 small">Career & Recruitment Expert</p>
                                    <p class="text-light-50 mb-0 small">
                                        Providing valuable insights and tips for job seekers and employers.
                                        Follow for more career advice and recruitment strategies.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- RELATED POSTS -->
                <?php if (!empty($related_posts)): ?>
                    <div class="related-posts mt-5 pt-5 border-top">
                        <h3 class="fw-bold mb-4">Related Articles</h3>
                        <div class="row g-4">
                            <?php foreach ($related_posts as $related): ?>
                                <div class="col-md-4">
                                    <div class="related-post-card card shadow-sm h-100">
                                        <?php if (!empty($related->thumbnail)): ?>
                                            <img src="<?= $related->thumbnail ?>"
                                                class="card-img-top"
                                                alt="<?= esc($related->title) ?>"
                                                loading="lazy">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title fw-semibold">
                                                <a href="<?= base_url('blog/' . $related->slug) ?>"
                                                    class="text-decoration-none text-dark">
                                                    <?= esc($related->title) ?>
                                                </a>
                                            </h6>
                                            <p class="card-text small text-muted">
                                                <?= substr(strip_tags($related->excerpt ?? $related->content), 0, 100) ?>...
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar2 me-1"></i>
                                                    <?= date('M d, Y', strtotime($related->created_at)) ?>
                                                </small>
                                                <a href="<?= base_url('blog/' . $related->slug) ?>"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Read <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
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
            </div>

            <!-- SIDEBAR -->
            <aside class="col-lg-4">
                <!-- Table of Contents (Desktop) -->
                <?php if (str_word_count(strip_tags($blog->content)) > 1000): ?>
                    <div class="toc-card card shadow-sm mb-4 sticky-top">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Table of Contents</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="toc-list" id="desktopToc"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Popular Posts -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Popular Posts</h6>
                        <?php
                        // You would fetch these from your model
                        $popularPosts = model(BlogModel::class)
                            ->where('status', 'published')
                            ->orderBy('views', 'DESC')
                            ->limit(3)
                            ->find();
                        ?>
                        <?php foreach ($popularPosts as $popular): ?>
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                <?php if (!empty($popular->thumbnail)): ?>
                                    <img src="<?= $popular->thumbnail ?>"
                                        class="rounded me-3"
                                        width="60" height="60"
                                        alt="<?= esc($popular->title) ?>"
                                        style="object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <h6 class="small fw-semibold mb-1">
                                        <a href="<?= base_url('blog/' . $popular->slug) ?>"
                                            class="text-decoration-none text-dark">
                                            <?= esc($popular->title) ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar2 me-1"></i>
                                        <?= date('M d', strtotime($popular->created_at)) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
            </aside>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Reading Progress Bar
    window.addEventListener('scroll', function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('readingProgress').style.width = scrolled + '%';
    });

    // Copy to Clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Link copied to clipboard!');
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    // Table of Contents Generator
    function generateTOC() {
        const content = document.getElementById('blog-content');
        const headings = content.querySelectorAll('h2, h3');
        const tocContainer = document.getElementById('desktopToc') || document.getElementById('mobileToc');

        if (!headings.length || !tocContainer) return;

        let tocHTML = '<nav class="nav flex-column">';
        let tocMobileHTML = '<nav class="nav flex-column">';

        headings.forEach((heading, index) => {
            if (!heading.id) {
                heading.id = 'heading-' + index;
            }

            const level = heading.tagName === 'H2' ? '' : 'ps-3';
            const text = heading.textContent;

            tocHTML += `
            <a class="nav-link toc-item ${level}" href="#${heading.id}">
                ${text}
            </a>
        `;
        });

        tocHTML += '</nav>';
        tocContainer.innerHTML = tocHTML;

        // Add scrollspy for desktop
        if (document.getElementById('desktopToc')) {
            new bootstrap.ScrollSpy(document.body, {
                target: '#desktopToc',
                offset: 100
            });
        }
    }

    // Share Counts (placeholder - you'd need to implement actual API calls)
    function updateShareCounts() {
        // This is a placeholder. You would need to implement actual API calls
        // to get share counts from social media platforms
        document.querySelectorAll('.share-count').forEach(el => {
            el.textContent = Math.floor(Math.random() * 50); // Remove this in production
        });
    }

    // Lazy load images
    document.addEventListener('DOMContentLoaded', function() {
        // Generate TOC
        generateTOC();

        // Update share counts
        updateShareCounts();

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add reading time estimation
        const content = document.querySelector('.blog-article');
        if (content) {
            const text = content.textContent || content.innerText;
            const wordCount = text.trim().split(/\s+/).length;
            const readingTime = Math.ceil(wordCount / 200);

            const timeElement = document.querySelector('.reading-time');
            if (timeElement) {
                timeElement.textContent = readingTime + ' min';
            }
        }
    });

    // Print functionality
    function printArticle() {
        window.print();
    }
</script>
<?= $this->endSection() ?>