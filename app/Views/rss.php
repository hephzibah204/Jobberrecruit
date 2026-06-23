<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>

<rss version="2.0">
    <channel>
        <title><?= esc(config('App')->appName ?? 'JobberRecruit Blog') ?></title>
        <link><?= base_url('blog') ?></link>
        <description>Latest insights from JobberRecruit</description>
        <language>en-us</language>
        <lastBuildDate><?= date(DATE_RSS) ?></lastBuildDate>

        <?php foreach ($posts as $post): ?>
            <item>
                <title><?= esc($post->title) ?></title>
                <link><?= base_url('blog/' . $post->slug) ?></link>
                <guid><?= base_url('blog/' . $post->slug) ?></guid>
                <pubDate><?= date(DATE_RSS, strtotime($post->created_at)) ?></pubDate>
                <description>
                    <![CDATA[
                <?= esc($post->excerpt ?: word_limiter(strip_tags($post->content), 40)) ?>
            ]]>
                </description>
            </item>
        <?php endforeach; ?>

    </channel>
</rss>