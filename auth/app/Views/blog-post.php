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
<?php if (!empty($blog->tags)): $tags = array_map('trim', explode(',', $blog->tags)); ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => [],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
.art-hero {
  background: linear-gradient(160deg, var(--brand-deep), var(--brand-dark) 60%, var(--brand));
  color: #fff; padding: 30px 0 0; position: relative; overflow: hidden;
}
.art-hero .gridbg {
  position: absolute; inset: 0; opacity: .35; pointer-events: none;
  background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 46px 46px;
  -webkit-mask-image: radial-gradient(ellipse 90% 90% at 50% 0%, #000 30%, transparent 85%);
  mask-image: radial-gradient(ellipse 90% 90% at 50% 0%, #000 30%, transparent 85%);
}
.art-bc {
  position: relative; z-index: 1; display: flex; gap: 7px; align-items: center;
  font-size: .76rem; color: rgba(255,255,255,.6); margin-bottom: 20px; flex-wrap: wrap;
}
.art-bc a { color: rgba(255,255,255,.6); }
.art-bc a:hover { color: #fff; text-decoration: none; }
.art-bc svg { width: 12px; height: 12px; opacity: .5; }
.art-bc [aria-current] { color: rgba(255,255,255,.85); }
.art-hero-inner { position: relative; z-index: 1; max-width: 760px; padding-bottom: 32px; }
.art-cat {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--accent); color: var(--brand-deep);
  font-size: .68rem; font-weight: 800; letter-spacing: .05em;
  text-transform: uppercase; padding: 5px 12px; border-radius: 20px; margin-bottom: 16px;
}
.art-hero h1 {
  font-size: clamp(1.8rem, 3.6vw, 2.7rem);
  font-weight: 800; line-height: 1.15; letter-spacing: -.02em; margin-bottom: 16px;
}
.art-hero .standfirst {
  font-size: 1.08rem; color: rgba(255,255,255,.78); line-height: 1.6; margin-bottom: 24px;
}
.art-byline { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.art-byline-av {
  width: 46px; height: 46px; border-radius: 50%;
  background: var(--accent); color: var(--brand-deep);
  font-weight: 800; font-size: .95rem;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.art-byline-meta { font-size: .84rem; line-height: 1.5; }
.art-byline-name { font-weight: 700; display: flex; align-items: center; gap: 6px; }
.art-byline-name svg { width: 15px; height: 15px; color: #5ee9a0; }
.art-byline-sub { color: rgba(255,255,255,.6); font-size: .78rem; }
.art-byline-dot { color: rgba(255,255,255,.3); }
.art-cover {
  position: relative; z-index: 1; max-width: 1000px; margin: 0 auto; transform: translateY(28px);
}
.art-cover img {
  width: 100%; height: auto; aspect-ratio: 16/7; object-fit: cover;
  border-radius: 16px; box-shadow: 0 24px 60px rgba(0,0,0,.3); display: block;
}
.art-cover .ph {
  width: 100%; aspect-ratio: 16/7; border-radius: 16px;
  background: linear-gradient(135deg, var(--brand-deep), var(--brand));
  display: flex; align-items: center; justify-content: center;
}
.art-cover .ph svg { width: 64px; height: 64px; color: rgba(255,255,255,.25); }
.art-cover-cap {
  position: absolute; bottom: 0; left: 0; right: 0;
  background: linear-gradient(transparent, rgba(10,18,30,.78));
  color: rgba(255,255,255,.92); font-size: .78rem;
  padding: 32px 18px 14px; border-radius: 0 0 16px 16px; text-align: center;
}
#progress {
  position: fixed; top: 0; left: 0; height: 3px; width: 0;
  background: linear-gradient(90deg, var(--brand), var(--accent));
  z-index: 1200; transition: width .1s;
}
.art-wrap { padding: 64px 0 0; }
.art-layout { display: grid; grid-template-columns: minmax(0, 1fr) 280px; gap: 48px; align-items: start; max-width: 1100px; margin: 0 auto; }
.art-body { font-family: 'Lora', Georgia, serif; font-size: 1.075rem; line-height: 1.85; color: #2a3344; max-width: 720px; }
.art-body > p { margin-bottom: 1.5rem; }
.art-body h2 { font-size: 1.55rem; font-weight: 800; color: var(--brand-deep); line-height: 1.25; letter-spacing: -.01em; margin: 2.6rem 0 1rem; padding-top: .6rem; scroll-margin-top: 90px; }
.art-body h3 { font-size: 1.18rem; font-weight: 700; color: var(--brand); margin: 1.9rem 0 .7rem; scroll-margin-top: 90px; }
.art-body ul, .art-body ol { margin: 0 0 1.5rem 1.3rem; }
.art-body li { margin-bottom: .55rem; padding-left: .3rem; }
.art-body ul li::marker { color: var(--accent); }
.art-body ol li::marker { color: var(--brand); font-weight: 700; }
.art-body a { color: var(--brand); font-weight: 500; text-decoration: underline; text-underline-offset: 2px; text-decoration-color: rgba(8,97,169,.35); }
.art-body a:hover { text-decoration-color: var(--brand); }
.art-body strong { color: var(--brand-deep); font-weight: 600; }
.art-body blockquote {
  border-left: 4px solid var(--accent); background: var(--brand-light);
  border-radius: 0 10px 10px 0; padding: 18px 22px; margin: 2rem 0;
  font-style: italic; color: var(--brand-deep); font-size: 1.05rem;
}
.art-body blockquote p { margin: 0; }
.art-aside { position: sticky; top: 90px; display: flex; flex-direction: column; gap: 20px; }
.toc-card { background: var(--white); border: 1px solid var(--border); border-radius: 13px; padding: 18px 20px; }
.toc-card h4 { font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); margin-bottom: 12px; }
.toc-list { list-style: none; display: flex; flex-direction: column; gap: 1px; max-height: 50vh; overflow-y: auto; }
.toc-list a {
  display: block; font-size: .82rem; color: var(--muted);
  padding: 6px 10px; border-radius: 7px; border-left: 2px solid transparent;
  line-height: 1.4; transition: var(--transition);
}
.toc-list a:hover { background: var(--bg); color: var(--brand); text-decoration: none; }
.toc-list a.active { color: var(--brand); background: var(--brand-light); border-left-color: var(--brand); font-weight: 600; }
.share-card { background: var(--white); border: 1px solid var(--border); border-radius: 13px; padding: 18px 20px; }
.share-card h4 { font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); margin-bottom: 12px; }
.share-btns { display: flex; gap: 8px; flex-wrap: wrap; }
.share-btns button, .share-btns a {
  width: 38px; height: 38px; border-radius: 9px;
  border: 1px solid var(--border); background: var(--white);
  color: var(--muted); display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: var(--transition); text-decoration: none;
}
.share-btns button:hover, .share-btns a:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
.share-btns svg { width: 16px; height: 16px; }
.aside-newsletter { background: linear-gradient(150deg, var(--brand-deep), var(--brand)); border-radius: 13px; padding: 20px; color: #fff; }
.aside-newsletter h4 { font-size: 1rem; font-weight: 700; margin-bottom: 6px; }
.aside-newsletter p { font-size: .8rem; opacity: .82; line-height: 1.5; margin-bottom: 12px; }
.aside-newsletter input {
  width: 100%; border: none; border-radius: 8px; padding: 10px 12px;
  font-size: .84rem; margin-bottom: 8px; outline: none;
}
.aside-newsletter .btn { width: 100%; justify-content: center; }
.aside-newsletter .btn:hover { background: var(--accent); border-color: var(--accent); color: var(--brand-deep); }
.art-tags { display: flex; gap: 8px; flex-wrap: wrap; margin: 2.4rem 0 0; max-width: 720px; }
.art-tag {
  font-size: .76rem; font-weight: 600; color: var(--brand);
  background: var(--brand-light); padding: 6px 13px; border-radius: 20px; text-decoration: none;
}
.art-tag:hover { background: var(--brand); color: #fff; text-decoration: none; }
.author-box {
  display: flex; gap: 18px;
  background: var(--bg); border: 1px solid var(--border);
  border-radius: 14px; padding: 24px; margin: 2.4rem 0 0; max-width: 720px; align-items: flex-start;
}
.author-box-av {
  width: 64px; height: 64px; border-radius: 50%;
  background: var(--brand); color: #fff;
  font-weight: 800; font-size: 1.3rem;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.author-box h4 { font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 7px; margin-bottom: 2px; }
.author-box h4 svg { width: 16px; height: 16px; color: var(--brand); }
.author-box .role { font-size: .82rem; color: var(--brand); font-weight: 600; margin-bottom: 8px; }
.author-box p { font-size: .86rem; color: var(--muted); line-height: 1.65; }
.related-sec { background: var(--bg); border-top: 1px solid var(--border); padding: 56px 0; margin-top: 56px; }
.related-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 28px; }
.rel-card {
  background: var(--white); border: 1px solid var(--border); border-radius: 13px;
  overflow: hidden; text-decoration: none; transition: var(--transition);
  display: flex; flex-direction: column;
}
.rel-card:hover { border-color: var(--brand); box-shadow: var(--shadow-lg); transform: translateY(-3px); text-decoration: none; }
.rel-thumb { height: 120px; display: flex; align-items: center; justify-content: center; }
.rel-thumb svg { width: 38px; height: 38px; color: rgba(255,255,255,.3); }
.rel-body { padding: 16px; }
.rel-cat { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--brand); margin-bottom: 6px; }
.rel-body h3 { font-size: .92rem; font-weight: 700; line-height: 1.3; color: var(--text); margin-bottom: 7px; }
.rel-meta { font-size: .73rem; color: var(--muted); }
.toc-mobile { display: none; }
.ca-label {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 5px 13px;
  border-radius: 20px; margin-bottom: 12px;
}
.ca-label svg { width: 13px; height: 13px; }
.ad-wrap { padding: 8px 0 0; text-align: center; }
.ad-label { font-size: .6rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: #9aa6b6; margin-bottom: 6px; }

@media (max-width: 900px) {
  .art-layout { grid-template-columns: 1fr; gap: 32px; }
  .art-layout > * { min-width: 0; max-width: 100%; }
  .art-aside { position: static; display: flex; flex-direction: column; gap: 16px; width: 100%; }
  .toc-card { display: none; }
  .share-card, .aside-newsletter { flex: none; width: 100%; min-width: 0; }
  .related-grid { grid-template-columns: repeat(2, 1fr); }
  .art-hero-inner { padding-bottom: 24px; }
  .toc-mobile {
    display: block; margin: 0 0 24px; max-width: 100%; width: 100%; min-width: 0; box-sizing: border-box;
    background: var(--white); border: 1px solid var(--border); border-radius: 13px; overflow: hidden;
  }
  .toc-mobile-btn {
    display: flex; align-items: center; justify-content: space-between; width: 100%;
    padding: 15px 18px; background: var(--white); border: none;
    font-size: .88rem; font-weight: 700; color: var(--brand-deep); cursor: pointer; letter-spacing: .01em;
  }
  .toc-mobile-btn .tm-left { display: flex; align-items: center; gap: 10px; }
  .toc-mobile-btn svg.tm-ic { width: 17px; height: 17px; color: var(--brand); }
  .toc-mobile-btn svg.tm-chev { width: 19px; height: 19px; color: var(--muted); transition: transform .22s; }
  .toc-mobile.open .toc-mobile-btn svg.tm-chev { transform: rotate(180deg); }
  .toc-mobile-list { list-style: none; max-height: 0; overflow: hidden; transition: max-height .3s ease; padding: 0 12px; margin: 0; }
  .toc-mobile.open .toc-mobile-list { max-height: 70vh; overflow-y: auto; padding: 2px 12px 14px; }
  .toc-mobile-list a {
    display: block; font-size: .86rem; color: var(--muted);
    padding: 9px 10px; border-radius: 7px; border-left: 2px solid transparent;
    line-height: 1.4; text-decoration: none;
  }
  .toc-mobile-list a:hover, .toc-mobile-list a.active { background: var(--brand-light); color: var(--brand); border-left-color: var(--brand); font-weight: 600; }
}
@media (max-width: 580px) {
  .art-body { font-size: 1.02rem; max-width: 100%; }
  .art-body h2 { font-size: 1.32rem; margin: 2rem 0 .8rem; }
  .art-body h3 { font-size: 1.08rem; }
  .related-grid { grid-template-columns: 1fr; }
  .art-hero { padding-top: 18px; }
  .art-hero h1 { font-size: 1.6rem; line-height: 1.2; }
  .art-hero .standfirst { font-size: .98rem; }
  .art-hero-inner { padding-bottom: 18px; }
  .art-bc { margin-bottom: 14px; font-size: .72rem; }
  .art-cover { transform: translateY(14px); }
  .art-cover img { aspect-ratio: 16/10; border-radius: 12px; }
  .art-wrap { padding: 34px 0 0; }
  .art-layout { gap: 28px; }
  .art-byline { gap: 10px; }
  .art-byline-dot { display: none; }
  .author-box { padding: 20px; flex-direction: column; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="progress" aria-hidden="true"></div>

<section class="art-hero">
  <span class="gridbg" aria-hidden="true"></span>
  <div class="container">
    <nav class="art-bc" aria-label="Breadcrumb">
      <a href="<?= base_url() ?>">Home</a>
      <svg aria-hidden="true"><use href="#i-chevron-right"/></svg>
      <a href="<?= base_url('blog') ?>">Blog</a>
      <svg aria-hidden="true"><use href="#i-chevron-right"/></svg>
      <span aria-current="page"><?= esc($blog->title) ?></span>
    </nav>
    <div class="art-hero-inner">
      <?php if (!empty($blog->category)): ?>
        <span class="art-cat">
          <svg aria-hidden="true"><use href="#i-chip"/></svg>
          <?= esc($blog->category) ?>
        </span>
      <?php endif; ?>
      <h1><?= esc($blog->title) ?></h1>
      <?php if (!empty($blog->excerpt)): ?>
        <p class="standfirst"><?= esc($blog->excerpt) ?></p>
      <?php endif; ?>
      <div class="art-byline">
        <div class="art-byline-av"><?= strtoupper(substr($blog->author ?? 'JR', 0, 2)) ?></div>
        <div class="art-byline-meta">
          <div class="art-byline-name"><?= esc($blog->author ?? 'JobberRecruit Team') ?></div>
          <div class="art-byline-sub"><?= $readingTime ?? 5 ?> min read &middot; <?= number_format($blog->views ?? 0) ?> views</div>
        </div>
        <span class="art-byline-dot">&bull;</span>
        <div class="art-byline-meta art-byline-sub">
          <?= date('F j, Y', strtotime($blog->created_at ?? date('Y-m-d'))) ?>
          <?php if (!empty($blog->updated_at) && $blog->updated_at !== $blog->created_at): ?>
            &nbsp;&middot;&nbsp; Updated: <?= date('M j, Y', strtotime($blog->updated_at)) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <figure class="art-cover">
      <?php if (!empty($blog->thumbnail)): ?>
        <img src="<?= $blog->thumbnail ?>"
             alt="<?= esc($blog->title) ?>"
             width="1000" height="438" fetchpriority="high" decoding="async">
      <?php else: ?>
        <div class="ph"><svg aria-hidden="true"><use href="#i-chip"/></svg></div>
      <?php endif; ?>
      <?php if (!empty($blog->image_caption)): ?>
        <figcaption class="art-cover-cap"><?= esc($blog->image_caption) ?></figcaption>
      <?php endif; ?>
    </figure>
  </div>
</section>

<div class="art-wrap">
  <div class="container">
    <div class="art-layout">

      <nav class="toc-mobile" id="toc-mobile" aria-label="Table of contents">
        <button type="button" class="toc-mobile-btn" aria-expanded="false" aria-controls="toc-mobile-list" onclick="toggleMobToc(this)">
          <span class="tm-left">
            <svg class="tm-ic" aria-hidden="true"><use href="#i-doc"/></svg>
            Table of contents
          </span>
          <svg class="tm-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <ul class="toc-mobile-list" id="toc-mobile-list"></ul>
      </nav>

      <article class="art-body">
        <?= $blog->content ?? '<p class="text-muted">Blog content goes here...</p>' ?>

        <div class="ad-wrap" style="margin: 2rem 0">
          <p class="ad-label">Advertisement</p>
          <ins class="adsbygoogle"
               style="display:block;width:100%;height:auto"
               data-ad-client="ca-pub-3464186884176173"
               data-ad-slot="6229476516"
               data-ad-format="horizontal,rectangle"
               data-full-width-responsive="true"></ins>
        </div>

        <?php if (!empty($blog->tags)): $tags = array_map('trim', explode(',', $blog->tags)); ?>
          <div class="art-tags">
            <?php foreach ($tags as $tag): if (empty($tag)) continue; ?>
              <a href="<?= base_url('blog/tag/' . url_title($tag)) ?>" class="art-tag"><?= esc($tag) ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="author-box">
          <div class="author-box-av"><?= strtoupper(substr($blog->author ?? 'JR', 0, 2)) ?></div>
          <div>
            <h4><?= esc($blog->author ?? 'JobberRecruit Team') ?></h4>
            <div class="role">Career &amp; Recruitment Expert</div>
            <p>Providing valuable insights and tips for job seekers and employers across Nigeria. Follow for more career advice and recruitment strategies.</p>
            <div style="display:flex;align-items:center;gap:7px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);font-size:.78rem;color:var(--muted)">
              <svg aria-hidden="true" style="width:15px;height:15px;color:var(--success)"><use href="#i-shield"/></svg>
              <span>Fact-checked by the JobberRecruit editorial team</span>
            </div>
          </div>
        </div>
      </article>

      <aside class="art-aside" aria-label="Article tools">
        <div class="toc-card" id="toc-card">
          <h4>Table of contents</h4>
          <ul class="toc-list" id="toc"></ul>
        </div>

        <div class="share-card">
          <h4>Share</h4>
          <div class="share-btns">
            <?php $encodedUrl = urlencode(current_url()); $encodedTitle = urlencode($blog->title); ?>
            <button type="button" aria-label="Copy link" onclick="copyLink()">
              <svg aria-hidden="true"><use href="#i-bookmark"/></svg>
            </button>
            <a aria-label="Share on X" href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedTitle ?>" onclick="return shareTo('x',event)">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.41l-5.8-7.58-6.64 7.58H.47l8.6-9.83L0 1.15h7.59l5.24 6.93 6.07-6.93Z"/></svg>
            </a>
            <a aria-label="Share on LinkedIn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $encodedUrl ?>" target="_blank" rel="noopener" onclick="return shareTo('in',event)">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45z"/></svg>
            </a>
            <a aria-label="Share on WhatsApp" href="https://wa.me/?text=<?= $encodedTitle ?>%20<?= $encodedUrl ?>" target="_blank" rel="noopener" onclick="return shareTo('wa',event)">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.05 0C5.5 0 .15 5.34.15 11.9c0 2.1.55 4.14 1.59 5.95L0 24l6.3-1.65a11.9 11.9 0 0 0 5.68 1.45h.01c6.55 0 11.9-5.34 11.9-11.9C23.9 5.34 18.6 0 12.05 0z"/></svg>
            </a>
          </div>
        </div>

        <div class="aside-newsletter">
          <h4>Weekly career tips</h4>
          <p>Join 4,200+ Nigerian professionals getting our best advice every Monday.</p>
          <form action="<?= base_url('newsletter/subscribe') ?>" method="POST">
            <input type="email" name="email" placeholder="Your email address" required aria-label="Email address">
            <button class="btn btn-primary" type="submit">Subscribe</button>
          </form>
        </div>
      </aside>
    </div>
  </div>
</div>

<?php if (!empty($related_posts)): ?>
<section class="related-sec" aria-labelledby="rel-h">
  <div class="container">
    <div class="ca-label">
      <svg aria-hidden="true"><use href="#i-doc"/></svg>
      Keep reading
    </div>
    <h2 id="rel-h" style="font-size:1.6rem;font-weight:800;margin-bottom:0">Related <span style="color:var(--brand)">career guides</span></h2>
    <div class="related-grid">
      <?php
      $gradients = [
        'linear-gradient(135deg,var(--brand-deep),#0891b2)',
        'linear-gradient(135deg,var(--brand-deep),var(--success))',
        'linear-gradient(135deg,var(--brand-dark),var(--accent))',
      ];
      $icons = ['i-chip', 'i-doc', 'i-coins'];
      $i = 0;
      ?>
      <?php foreach ($related_posts as $related): $gi = $i % 3; ?>
      <a class="rel-card" href="<?= base_url('blog/' . $related->slug) ?>">
        <div class="rel-thumb" style="background:<?= $gradients[$gi] ?>">
          <svg aria-hidden="true"><use href="#<?= $icons[$gi] ?>"/></svg>
        </div>
        <div class="rel-body">
          <?php if (!empty($related->category)): ?>
            <div class="rel-cat"><?= esc($related->category) ?></div>
          <?php endif; ?>
          <h3><?= esc($related->title) ?></h3>
          <div class="rel-meta">
            <?= $readingTime ?? 5 ?> min &middot; <?= esc($related->author ?? 'JobberRecruit') ?>
          </div>
        </div>
      </a>
      <?php $i++; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var pb = document.getElementById('progress');
window.addEventListener('scroll', function() {
  var h = document.documentElement;
  var sc = h.scrollTop / (h.scrollHeight - h.clientHeight);
  if (pb) pb.style.width = (sc * 100) + '%';
}, {passive: true});

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(function() {
    toastr.success('Link copied to clipboard!');
  }, function(err) {
    console.error(err);
  });
}

var heads = [];
var links = [];
(function buildTOC() {
  var body = document.querySelector('.art-body');
  var tocD = document.getElementById('toc');
  var tocM = document.getElementById('toc-mobile-list');
  if (!body) return;
  var nodes = [];
  Array.prototype.forEach.call(body.querySelectorAll('h2, h3'), function(h) {
    nodes.push({el: h, text: (h.textContent || '').trim()});
  });
  if (nodes.length < 2) {
    var card = document.getElementById('toc-card'); if (card) card.style.display = 'none';
    var mob = document.getElementById('toc-mobile'); if (mob) mob.style.display = 'none';
    return;
  }
  var used = {};
  function slugify(t) {
    var s = t.toLowerCase().replace(/&[a-z]+;/g, ' ').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 60) || 'section';
    var base = s, n = 2; while (used[s]) { s = base + '-' + n; n++; } used[s] = 1; return s;
  }
  nodes.forEach(function(n) {
    if (!n.el.id) n.el.id = slugify(n.text);
    heads.push(n.el);
    var level = n.el.tagName === 'H3' ? 'style="padding-left:12px"' : '';
    var dl = '<li><a href="#' + n.el.id + '" ' + level + '>' + n.text + '</a></li>';
    if (tocD) tocD.insertAdjacentHTML('beforeend', dl);
    if (tocM) tocM.insertAdjacentHTML('beforeend', dl);
  });
  links = Array.prototype.slice.call(document.querySelectorAll('#toc a, #toc-mobile-list a'));
  links.forEach(function(a) {
    a.addEventListener('click', function(e) {
      var t = document.getElementById(a.getAttribute('href').slice(1));
      if (t) { e.preventDefault(); window.scrollTo({top: t.offsetTop - 80, behavior: 'smooth'}); }
      var m = document.getElementById('toc-mobile');
      if (m && m.classList.contains('open')) { m.classList.remove('open'); var tg = m.querySelector('.toc-mobile-btn'); if (tg) tg.setAttribute('aria-expanded', 'false'); }
    });
  });
})();

function spy() {
  if (!heads.length) return;
  var y = window.scrollY + 120;
  var cur = heads[0].id;
  for (var i = 0; i < heads.length; i++) { if (heads[i].offsetTop <= y) cur = heads[i].id; }
  links.forEach(function(a) { a.classList.toggle('active', a.getAttribute('href') === '#' + cur); });
}
window.addEventListener('scroll', spy, {passive: true}); spy();

function toggleMobToc(btn) {
  var m = document.getElementById('toc-mobile');
  if (!m) return;
  var o = m.classList.toggle('open');
  btn.setAttribute('aria-expanded', String(o));
}

function shareTo(net, e) {
  e.preventDefault();
  var u = encodeURIComponent(location.href);
  var t = encodeURIComponent(document.title);
  var url = '';
  if (net === 'x') url = 'https://twitter.com/intent/tweet?url=' + u + '&text=' + t;
  if (net === 'in') url = 'https://www.linkedin.com/sharing/share-offsite/?url=' + u;
  if (net === 'wa') url = 'https://wa.me/?text=' + t + '%20' + u;
  window.open(url, '_blank', 'noopener,width=600,height=500');
  return false;
}

function copyLink() {
  if (navigator.clipboard) navigator.clipboard.writeText(location.href);
}

document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
  anchor.addEventListener('click', function(e) {
    var targetId = this.getAttribute('href');
    if (targetId === '#') return;
    var targetElement = document.querySelector(targetId);
    if (targetElement) { e.preventDefault(); window.scrollTo({top: targetElement.offsetTop - 80, behavior: 'smooth'}); }
  });
});

// Reading progress bar
window.addEventListener('scroll', function() {
  var progress = document.getElementById('progress');
  if (progress) {
    var totalHeight = document.documentElement.scrollHeight - window.innerHeight;
    if (totalHeight > 0) {
      var progressWidth = (window.scrollY / totalHeight) * 100;
      progress.style.width = progressWidth + '%';
    }
  }
}, {passive: true});

(function() { try { document.querySelectorAll('ins.adsbygoogle').forEach(function() { (window.adsbygoogle = window.adsbygoogle || []).push({}); }); } catch(e) {} })();
</script>
<?= $this->endSection() ?>
