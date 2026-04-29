<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <?php foreach ($pages as $page): ?>
        <url>
            <loc><?= esc($page['loc']) ?></loc>
            <?php if (!empty($page['lastmod'])): ?>
                <lastmod><?= esc($page['lastmod']) ?></lastmod>
            <?php endif; ?>
            <changefreq><?= esc($page['changefreq'] ?? 'monthly') ?></changefreq>
            <priority><?= esc($page['priority'] ?? '0.5') ?></priority>
        </url>
    <?php endforeach; ?>
</urlset>