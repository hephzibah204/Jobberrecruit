
<script type="application/ld+json">

<?= json_encode([

    '@context' => 'https://schema.org',

    '@type'    => 'WebPage',

    'name'     => 'Terms of Service',

    'description' => 'Terms and conditions for using JobberRecruit.com - Understand your rights and responsibilities when using our recruitment platform.',

    'url'      => current_url(),

    'datePublished' => '2025-02-12',

    'dateModified'  => '2025-02-12',

    'publisher' => [

        '@type' => 'Organization',

        'name'  => 'JobberRecruit',

        'logo'  => [

            '@type' => 'ImageObject',

            'url'   => base_url('images/logo.png'),

        ],

    ],

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>

</script>


    // ScrollSpy Implementation
    function initScrollSpy() {
        const sections = document.querySelectorAll('section[id], div[id^="section-"]');
        const navLinks = document.querySelectorAll('.nav-link.scroll-to');
        
        if (sections.length === 0 || navLinks.length === 0) return;

        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active', 'text-primary', 'fw-bold');
                        if (link.getAttribute('href') === '#' + id) {
                            link.classList.add('active', 'text-primary', 'fw-bold');
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(sec => observer.observe(sec));
    }
    
    document.addEventListener('DOMContentLoaded', initScrollSpy);
