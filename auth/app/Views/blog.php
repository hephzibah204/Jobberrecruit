<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<?php
$listItems = [];
foreach ($blogs as $index => $blog) {
    $listItems[] = [
        '@type'     => 'ListItem',
        'position'  => $index + 1,
        'item'      => [
            '@type'         => 'BlogPosting',
            'headline'      => $blog->title,
            'description'   => $blog->excerpt ?? '',
            'url'           => base_url('blog/' . $blog->slug),
            'image'         => $blog->thumbnail ?: base_url('images/blog-default.jpg'),
            'datePublished' => date('c', strtotime($blog->created_at)),
            'author'        => [
                '@type' => 'Organization',
                'name'  => 'JobberRecruit',
            ],
        ],
    ];
}
?>
<script type="application/ld+json">
<?= json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'CollectionPage',
    'name'       => 'JobberRecruit Blog',
    'description'=> 'Insights, tips, and updates to help job seekers and employers succeed.',
    'url'        => current_url(),
    'mainEntity' => [
        '@type'           => 'ItemList',
        'numberOfItems'   => count($blogs),
        'itemListElement' => $listItems,
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ── Blog Hero ── */
.blog-hero{background:radial-gradient(ellipse 60% 80% at 90% 10%,rgba(240,143,26,.18) 0%,transparent 55%),radial-gradient(ellipse 70% 60% at 5% 90%,rgba(13,96,158,.3) 0%,transparent 55%),linear-gradient(155deg,var(--brand-deep) 0%,var(--brand-dark) 55%,var(--brand) 100%);color:var(--white);padding:64px 0 56px;padding-top:max(64px,calc(64px + env(safe-area-inset-top,0px)));position:relative;overflow:hidden}
.blog-hero-grid{position:absolute;inset:0;pointer-events:none;opacity:.45;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:46px 46px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 50% 30%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 50% 30%,#000 30%,transparent 80%)}
.blog-hero-inner{position:relative;z-index:1}
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:.78rem;color:var(--muted);padding:14px 0;flex-wrap:wrap}
.breadcrumb a{color:var(--brand)}
.breadcrumb a:hover{text-decoration:underline}
.breadcrumb-sep{color:var(--muted);opacity:.5}
.blog-hero-tag{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);margin-bottom:18px}
.blog-hero-tag svg{width:14px;height:14px}
.blog-hero h1{font-size:clamp(2rem,5vw,3.2rem);font-weight:800;line-height:1.1;margin-bottom:16px;color:var(--white)}
.blog-hero h1 em{font-style:normal;color:var(--accent)}
.blog-hero-sub{font-size:1.05rem;opacity:.9;max-width:580px;margin-bottom:32px;color:var(--white)}
.blog-stats{display:flex;flex-wrap:wrap;gap:24px;margin-bottom:36px}
.blog-stat{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);border-radius:10px;padding:16px 24px;text-align:center;min-width:130px}
.blog-stat-num{font-family:'Sora',sans-serif;font-size:1.9rem;font-weight:800;color:var(--accent);line-height:1}
.blog-stat-label{font-size:.78rem;opacity:.8;margin-top:4px;color:var(--white)}
.blog-trust{display:flex;align-items:center;gap:8px;margin-top:18px;font-size:.8rem;opacity:.85;flex-wrap:wrap;color:var(--white)}
.blog-trust svg{width:15px;height:15px;color:var(--accent)}
.blog-trust-dot{opacity:.4;color:var(--white)}
.blog-search-bar{display:flex;gap:0;max-width:560px;background:var(--white);border-radius:10px;box-shadow:0 14px 40px rgba(7,48,79,.16);overflow:hidden}
.blog-search-bar svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:17px;height:17px;color:var(--muted);pointer-events:none}
.blog-search-wrap{position:relative;flex:1}
.blog-search-bar input{width:100%;border:none;outline:none;padding:13px 14px 13px 42px;font-family:'Inter',sans-serif;font-size:.95rem;color:var(--text);background:transparent;min-height:50px}
.blog-search-bar button{flex-shrink:0;padding:0 22px;background:var(--accent);color:var(--brand-deep);border:none;font-family:'Inter',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;transition:var(--transition);min-height:50px;display:inline-flex;align-items:center;gap:7px}
.blog-search-bar button:hover{background:var(--accent-dark)}
.blog-search-bar button svg{position:static;transform:none;width:16px;height:16px;color:inherit}
.search-results-info{font-size:.84rem;color:var(--muted);margin-top:12px}
.search-results-info a{font-weight:600}

/* ── Topic Pills ── */
.topic-pills-wrap{background:var(--white);border-bottom:1px solid var(--border)}
.topic-pills{display:flex;flex-wrap:wrap;gap:8px;padding:18px 0}
.topic-pill{padding:7px 16px;border-radius:20px;font-size:.78rem;font-weight:600;border:1.5px solid var(--border);color:var(--text);background:var(--white);cursor:pointer;transition:var(--transition);text-decoration:none;min-height:36px;display:inline-flex;align-items:center;gap:6px}
.topic-pill:hover,.topic-pill.active{background:var(--brand);color:var(--white);border-color:var(--brand);text-decoration:none}
.pill-count{font-size:.68rem;font-weight:700;background:rgba(13,96,158,.12);color:var(--brand);border-radius:20px;padding:1px 7px;margin-left:2px}
.topic-pill.active .pill-count,.topic-pill:hover .pill-count{background:rgba(255,255,255,.25);color:#fff}

/* ── Blog Layout ── */
.blog-section{background:var(--bg);padding:48px 0 76px}
.blog-layout{display:grid;grid-template-columns:1fr 320px;gap:36px;align-items:start}
.section-label{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brand);background:var(--brand-light);padding:5px 13px;border-radius:20px;margin-bottom:14px}
.section-label svg{width:13px;height:13px}
.section-title{font-size:clamp(1.6rem,2.9vw,2.25rem);font-weight:800;line-height:1.15;margin-bottom:12px;color:var(--text)}
.section-title span{color:var(--brand)}
.section-sub{color:var(--muted);font-size:.95rem;max-width:560px}
.blog-header-bar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:28px}
.blog-sort{display:flex;align-items:center;gap:10px}
.blog-sort label{font-size:.8rem;color:var(--muted)}
.blog-sort select{padding:8px 14px;border-radius:8px;border:1.5px solid var(--border);font-family:'Inter',sans-serif;font-size:.82rem;color:var(--text);background:var(--white);cursor:pointer;outline:none}
.blog-sort select:focus{border-color:var(--brand)}

/* ── Article Cards ── */
.article-list{display:flex;flex-direction:column;gap:28px}
.article-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;display:flex;flex-direction:column;transition:var(--transition)}
.article-card:hover{box-shadow:0 14px 40px rgba(7,48,79,.16);border-color:var(--brand);transform:translateY(-3px);text-decoration:none}
.article-card--featured{border-left:4px solid var(--accent);background:linear-gradient(180deg,#fffbf2,#fff)}
.article-card--featured:hover{border-left-color:var(--accent-dark);border-color:var(--accent)}
.article-thumb{width:100%;height:200px;overflow:hidden;position:relative;background:linear-gradient(135deg,var(--brand-deep),var(--brand));display:flex;align-items:center;justify-content:center;flex-shrink:0}
.article-thumb svg{width:56px;height:56px;color:rgba(255,255,255,.28)}
.article-thumb-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:1}
.article-thumb-label,.article-thumb-read{z-index:2}
.article-thumb-label{position:absolute;top:14px;left:14px;background:var(--accent);color:var(--brand-deep);font-size:.68rem;font-weight:800;padding:4px 11px;border-radius:20px;letter-spacing:.04em;display:inline-flex;align-items:center;gap:5px}
.article-thumb-label svg{width:11px;height:11px}
.article-thumb-read{position:absolute;top:14px;right:14px;background:rgba(7,48,79,.7);color:rgba(255,255,255,.9);font-size:.7rem;font-weight:600;padding:4px 11px;border-radius:20px;backdrop-filter:blur(4px)}
.article-body{padding:22px 26px 26px;display:flex;flex-direction:column;gap:10px;flex:1}
.article-meta{display:flex;flex-wrap:wrap;align-items:center;gap:12px;font-size:.75rem;color:var(--muted)}
.article-meta span{display:inline-flex;align-items:center;gap:5px}
.article-meta svg{width:13px;height:13px}
.article-topic{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.7rem;font-weight:700;background:var(--brand-light);color:var(--brand)}
.article-topic svg{width:11px;height:11px}
.article-title{font-family:'Sora',sans-serif;font-size:1.12rem;font-weight:700;line-height:1.3;color:var(--text);margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.article-card:hover .article-title{color:var(--brand)}
.article-excerpt{font-size:.87rem;color:var(--muted);line-height:1.7;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.article-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:4px;flex-wrap:wrap}
.article-author{display:flex;align-items:center;gap:9px}
.article-avatar{width:34px;height:34px;border-radius:50%;background:var(--brand);color:var(--white);font-family:'Sora',sans-serif;font-weight:700;font-size:.78rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.article-avatar--accent{background:var(--accent);color:var(--brand-deep)}
.article-avatar--deep{background:var(--brand-deep)}
.article-author-name{font-weight:600;font-size:.82rem;display:inline-flex;align-items:center;gap:4px}
.article-author-name .verified{width:13px;height:13px;color:var(--brand)}
.article-author-role{font-size:.72rem;color:var(--muted)}
.article-updated{display:inline-flex;align-items:center;gap:4px;font-weight:600;color:var(--success)}
.article-updated svg{width:13px;height:13px}
.article-save{background:none;border:1.5px solid var(--border);border-radius:8px;width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:var(--transition);flex-shrink:0}
.article-save svg{width:16px;height:16px}
.article-save:hover{border-color:var(--brand);color:var(--brand);background:var(--brand-light)}
.article-read-link{display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:700;color:var(--brand);flex-shrink:0;transition:var(--transition)}
.article-read-link svg{width:15px;height:15px;transition:transform .18s}
.article-card:hover .article-read-link{color:var(--accent-dark)}
.article-card:hover .article-read-link svg{transform:translateX(3px)}
.article-footer-actions{display:inline-flex;align-items:center;gap:10px;flex-shrink:0}
.article-sources{font-size:.73rem;color:var(--muted);margin-top:2px;display:flex;align-items:center;gap:5px;flex-wrap:wrap}
.article-sources svg{width:12px;height:12px;flex-shrink:0}
.article-sources strong{color:var(--text);font-weight:600}

/* Horizontal card variant (first/featured article) */
.article-card--hero{flex-direction:row}
.article-card--hero .article-thumb{width:320px;height:auto;min-height:240px;flex-shrink:0}
.article-card--hero .article-body{padding:28px 30px}

/* Helpful strip */
.helpful-strip{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:18px 22px;margin-top:28px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.helpful-strip p{font-size:.9rem;font-weight:600;color:var(--text);display:inline-flex;align-items:center;gap:8px;margin:0}
.helpful-strip p svg{width:18px;height:18px;color:var(--brand)}
.helpful-actions{display:flex;gap:8px}
.helpful-btn{border:1.5px solid var(--border);background:var(--bg);border-radius:8px;padding:8px 16px;font-family:'Inter',sans-serif;font-size:.82rem;font-weight:600;color:var(--text);cursor:pointer;transition:var(--transition);display:inline-flex;align-items:center;gap:6px;min-height:40px}
.helpful-btn:hover{border-color:var(--brand);background:var(--brand-light);color:var(--brand)}
.helpful-btn svg{width:15px;height:15px}

/* ── Pagination ── */
.pagination{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:40px;flex-wrap:wrap}
.pagination a,.pagination span{min-width:42px;height:42px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border);border-radius:9px;font-family:'Sora',sans-serif;font-size:.88rem;font-weight:600;color:var(--text);text-decoration:none;padding:0 12px;transition:all .15s}
.pagination a:hover{border-color:var(--brand);color:var(--brand);background:var(--brand-light);text-decoration:none}
.pagination .current{background:var(--brand);border-color:var(--brand);color:#fff}
.pagination .ellipsis{border:none;min-width:auto;color:var(--muted)}
.pagination .pg-arrow{font-weight:700}
.pagination .pg-arrow.disabled{opacity:.4;pointer-events:none}

/* ── Sidebar ── */
.blog-sidebar{display:flex;flex-direction:column;gap:24px}
.sidebar-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.sidebar-card-header{padding:16px 20px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px}
.sidebar-card-header h3{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:700;color:var(--text);margin:0}
.sidebar-card-header svg{width:16px;height:16px;color:var(--brand)}
.sidebar-card-body{padding:16px 20px}
.trending-list{display:flex;flex-direction:column;gap:0}
.trending-item{display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);text-decoration:none;transition:var(--transition)}
.trending-item:last-child{border-bottom:none;padding-bottom:0}
.trending-item:hover{text-decoration:none}
.trending-item:hover .trending-title{color:var(--brand)}
.trending-num{font-family:'Sora',sans-serif;font-size:1.3rem;font-weight:800;color:var(--brand-light);line-height:1;flex-shrink:0;min-width:24px}
.trending-item:first-child .trending-num{color:var(--accent)}
.trending-title{font-size:.84rem;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:4px}
.trending-meta{font-size:.72rem;color:var(--muted);display:flex;gap:8px}
.topics-cloud{display:flex;flex-wrap:wrap;gap:7px}
.topic-tag{padding:5px 12px;border-radius:20px;font-size:.74rem;font-weight:600;border:1.5px solid var(--border);color:var(--muted);background:var(--bg);text-decoration:none;transition:var(--transition)}
.topic-tag:hover{background:var(--brand);color:var(--white);border-color:var(--brand);text-decoration:none}
.topic-tag.t-blue{background:var(--brand-light);color:var(--brand);border-color:var(--brand-light)}
.topic-tag.t-orange{background:#fef3c7;color:var(--accent-dark);border-color:#fde68a}
.topic-tag.t-green{background:#ecfdf5;color:#15803d;border-color:#a7f3d0}
.tag-count{opacity:.65;font-weight:700;margin-left:3px}

/* Newsletter sidebar */
.sidebar-newsletter{background:linear-gradient(150deg,var(--brand-deep),var(--brand));color:var(--white);border:none}
.sidebar-newsletter .sidebar-card-header{border-color:rgba(255,255,255,.12)}
.sidebar-newsletter .sidebar-card-header h3{color:var(--white)}
.sidebar-newsletter .sidebar-card-header svg{color:var(--accent)}
.sidebar-newsletter p{font-size:.83rem;opacity:.85;margin-bottom:14px;color:var(--white)}
.sidebar-nl-form{display:flex;flex-direction:column;gap:8px}
.sidebar-nl-form input{width:100%;padding:10px 14px;border-radius:8px;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.1);color:var(--white);font-family:'Inter',sans-serif;font-size:.85rem;outline:none}
.sidebar-nl-form input::placeholder{color:rgba(255,255,255,.5)}
.sidebar-nl-form input:focus{border-color:var(--accent);background:rgba(255,255,255,.16)}
.sidebar-nl-form button{width:100%;padding:10px;border-radius:8px;background:var(--accent);color:var(--brand-deep);border:none;font-family:'Inter',sans-serif;font-size:.85rem;font-weight:700;cursor:pointer;transition:var(--transition)}
.sidebar-nl-form button:hover{background:var(--accent-dark)}
.sidebar-nl-disclaimer{font-size:.7rem;opacity:.55;margin-top:6px;color:var(--white)}

/* AI Tools sidebar */
.sidebar-tools-list{display:flex;flex-direction:column;gap:10px}
.sidebar-tool{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:9px;background:var(--bg);border:1px solid var(--border);text-decoration:none;transition:var(--transition)}
.sidebar-tool:hover{border-color:var(--brand);background:var(--brand-light);text-decoration:none}
.sidebar-tool-ic{width:36px;height:36px;border-radius:9px;background:var(--brand);color:var(--white);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sidebar-tool-ic.accent{background:var(--accent);color:var(--brand-deep)}
.sidebar-tool-ic svg{width:18px;height:18px}
.sidebar-tool-name{font-weight:600;font-size:.83rem;color:var(--text)}
.sidebar-tool-desc{font-size:.73rem;color:var(--muted)}

/* Sidebar ad */
.sidebar-ad{background:#fff;border:1px solid var(--border);border-radius:10px;padding:14px;text-align:center;min-height:280px;max-height:300px;overflow:hidden;display:flex;flex-direction:column;align-items:center;justify-content:center}
.sidebar-ad-label{font-size:.6rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;align-self:flex-start}

/* Dual CTA */
.dual-cta{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.cta-panel{border-radius:12px;padding:44px 32px}
.cta-panel.blue{background:linear-gradient(150deg,var(--brand-deep),var(--brand));color:var(--white)}
.cta-panel.light{background:var(--white);color:var(--text);border:1px solid var(--border)}
.cta-ic{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.cta-panel.blue .cta-ic{background:rgba(255,255,255,.14);color:var(--white)}
.cta-panel.light .cta-ic{background:var(--brand-light);color:var(--brand)}
.cta-ic svg{width:25px;height:25px}
.cta-panel h2{font-size:1.35rem;font-weight:700;margin-bottom:10px}
.cta-panel p{font-size:.87rem;margin-bottom:22px;line-height:1.55}
.cta-panel.blue h2, .cta-panel.blue p, .cta-panel.blue li, .cta-panel.blue strong{color:var(--white)}
.cta-panel.blue p{opacity:.86}
.cta-panel.light p{color:var(--muted)}
.cta-list{list-style:none;margin-bottom:26px;display:flex;flex-direction:column;gap:9px;padding:0}
.cta-list li{display:flex;align-items:center;gap:9px;font-size:.85rem}
.cta-list li svg{width:16px;height:16px;flex-shrink:0;color:var(--accent)}
.cta-tag{font-size:.64rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;background:var(--accent);color:var(--brand-deep);padding:1px 7px;border-radius:20px;margin-left:2px}
.cta-panel .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 22px;border-radius:8px;font-family:'Inter',sans-serif;font-size:.88rem;font-weight:600;cursor:pointer;border:1.5px solid transparent;transition:var(--transition);text-decoration:none}
.btn-accent{background:var(--accent);color:var(--brand-deep);border-color:var(--accent)}
.btn-accent:hover{background:var(--accent-dark);border-color:var(--accent-dark);color:var(--brand-deep);text-decoration:none}

/* Responsive */
@media(max-width:960px){
  .blog-layout{grid-template-columns:1fr}
  .blog-sidebar{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(280px,100%),1fr))}
  .article-card--hero{flex-direction:column}
  .article-card--hero .article-thumb{width:100%;height:220px}
}
@media(max-width:580px){
  .blog-hero{padding:56px 0 40px}
  .blog-stats{gap:12px}
  .blog-stat{min-width:110px;padding:12px 16px}
  .blog-stat-num{font-size:1.5rem}
  .article-card--hero .article-thumb{width:100%;height:200px}
  .blog-sidebar{display:flex}
  .dual-cta{grid-template-columns:1fr}
  .cta-panel{padding:30px 22px}
}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main id="main">

  <!-- BLOG HERO -->
  <section class="blog-hero" aria-label="JobberRecruit Blog">
    <span class="blog-hero-grid" aria-hidden="true"></span>
    <div class="container blog-hero-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="<?= base_url() ?>">Home</a>
        <span class="breadcrumb-sep" aria-hidden="true">›</span>
        <span aria-current="page">Blog</span>
      </nav>
      <p class="blog-hero-tag"><svg aria-hidden="true"><use href="#i-book"/></svg> JobberRecruit Blog</p>
      <h1>Expert insights to help you<br><em>land your dream job</em></h1>
      <p class="blog-hero-sub">
        Career advice, job search strategies, salary guides, CV tips, and hiring insights — written for Nigerian professionals at every stage of their career.
      </p>
      <div class="blog-stats" role="list" aria-label="Blog statistics">
        <div class="blog-stat" role="listitem">
          <div class="blog-stat-num"><?= number_format($totalPosts) ?>+</div>
          <div class="blog-stat-label">Articles published</div>
        </div>
        <div class="blog-stat" role="listitem">
          <div class="blog-stat-num"><?= number_format(max(1, round($totalViews / 1000)) * 1000, 0) ?>+</div>
          <div class="blog-stat-label">Monthly readers</div>
        </div>
        <div class="blog-stat" role="listitem">
          <div class="blog-stat-num">8</div>
          <div class="blog-stat-label">Expert topics covered</div>
        </div>
      </div>
      <p class="blog-trust">
        <svg aria-hidden="true"><use href="#i-verified-disc"/></svg> Written by certified HR experts &amp; career coaches
        <span class="blog-trust-dot" aria-hidden="true">•</span>
        <svg aria-hidden="true"><use href="#i-users"/></svg> 5,000+ Nigerian professionals read us monthly
      </p>
      <form class="blog-search-bar" action="<?= base_url('blog') ?>" method="get" role="search" aria-label="Search blog articles">
        <div class="blog-search-wrap">
          <svg aria-hidden="true"><use href="#i-search"/></svg>
          <label for="blog-q" class="sr-only">Search articles, tips and guides</label>
          <input id="blog-q" type="search" name="q" value="<?= esc($q ?? '') ?>" placeholder="Search articles, tips, guides…" autocomplete="off">
        </div>
        <button type="submit">
          <svg aria-hidden="true"><use href="#i-search"/></svg> Search
        </button>
      </form>
      <?php if ($q): ?>
        <p class="search-results-info">
          Found <?= $pager->getTotal() ?? 0 ?> result(s) for "<strong><?= esc($q) ?></strong>"
          <a href="<?= base_url('blog') ?>" class="search-results-info-clear">Clear search</a>
        </p>
      <?php endif; ?>
    </div>
  </section>

  <!-- TOPIC FILTER -->
  <div class="topic-pills-wrap">
    <div class="container">
      <nav class="topic-pills" aria-label="Filter articles by topic">
        <a href="<?= base_url('blog') ?>" class="topic-pill <?= !$q ? 'active' : '' ?>">All articles</a>
        <a href="<?= base_url('blog') ?>?q=Job+Search" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-search"/></svg> Job Search
        </a>
        <a href="<?= base_url('blog') ?>?q=CV+Writing" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-doc"/></svg> CV Writing
        </a>
        <a href="<?= base_url('blog') ?>?q=Interview+Tips" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-mic"/></svg> Interview Tips
        </a>
        <a href="<?= base_url('blog') ?>?q=Salary" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-coins"/></svg> Salary &amp; Negotiation
        </a>
        <a href="<?= base_url('blog') ?>?q=Career+Growth" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-chart"/></svg> Career Growth
        </a>
        <a href="<?= base_url('blog') ?>?q=Remote+Work" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-globe"/></svg> Remote Work
        </a>
        <a href="<?= base_url('blog') ?>?q=Recruitment" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-users"/></svg> Recruitment
        </a>
        <a href="<?= base_url('blog') ?>?q=Industry+Trends" class="topic-pill">
          <svg width="13" height="13" aria-hidden="true"><use href="#i-spark"/></svg> Industry Trends
        </a>
      </nav>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <section class="blog-section" aria-labelledby="blog-articles-h">
    <div class="container">
      <div class="blog-layout">

        <!-- ARTICLES COLUMN -->
        <div>
          <div class="blog-header-bar">
            <div>
              <p class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Latest articles</p>
              <h2 class="section-title" id="blog-articles-h" style="margin-bottom:0;">Recent <span>career guides</span></h2>
            </div>
            <div class="blog-sort">
              <label for="sort-select">Sort by:</label>
              <select id="sort-select" aria-label="Sort articles" onchange="window.location.href=this.value">
                <option value="<?= current_url() ?>?sort=newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest first</option>
                <option value="<?= current_url() ?>?sort=popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Most read</option>
                <option value="<?= current_url() ?>?sort=oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest first</option>
              </select>
            </div>
          </div>

          <?php if (!empty($blogs)): ?>

          <div class="article-list" role="list" aria-label="Blog articles">
            <?php foreach ($blogs as $i => $blog):
              $isFirst = $i === 0;
              $readingTime = max(1, ceil(str_word_count(strip_tags($blog->content ?? '')) / 200));
              $authorInitials = '';
              $authorName = $blog->author ?? 'JobberRecruit Team';
              $parts = explode(' ', $authorName);
              foreach ($parts as $p) $authorInitials .= strtoupper($p[0] ?? '');
              $authorInitials = substr($authorInitials, 0, 2);
              $avatarClass = $i % 3 === 0 ? 'article-avatar--accent' : ($i % 3 === 1 ? 'article-avatar--deep' : '');
            ?>
            <article class="article-card <?= $isFirst ? 'article-card--hero article-card--featured' : '' ?>" role="listitem" aria-label="<?= esc($blog->title) ?>">
              <div class="article-thumb" <?= $isFirst ? '' : 'style="height:180px"' ?>>
                <?php if (!empty($blog->thumbnail)): ?>
                  <img class="article-thumb-img" src="<?= esc($blog->thumbnail) ?>" alt="<?= esc($blog->title) ?>" loading="lazy" decoding="async" onerror="this.remove()">
                <?php endif; ?>
                <?php if ($isFirst): ?>
                  <svg aria-hidden="true"><use href="#i-chip"/></svg>
                  <span class="article-thumb-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
                <?php else: ?>
                  <svg aria-hidden="true"><use href="#i-doc"/></svg>
                <?php endif; ?>
                <span class="article-thumb-read"><?= $readingTime ?> min read</span>
              </div>
              <div class="article-body">
                <div class="article-meta">
                  <span class="article-topic"><svg aria-hidden="true"><use href="#i-book"/></svg> <?= esc($blog->category ?? 'Career Tips') ?></span>
                  <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= date('j M Y', strtotime($blog->created_at)) ?></span>
                  <span><svg aria-hidden="true"><use href="#i-chart"/></svg> <?= number_format($blog->views ?? 0) ?> reads</span>
                </div>
                <h3 class="article-title"><?= esc($blog->title) ?></h3>
                <p class="article-excerpt"><?= esc($blog->excerpt ?? '') ?></p>
                <div class="article-footer">
                  <div class="article-author">
                    <div class="article-avatar <?= $avatarClass ?>"><?= $authorInitials ?></div>
                    <div>
                      <div class="article-author-name"><?= esc($authorName) ?> <svg class="verified" aria-hidden="true"><use href="#i-verified-disc"/></svg></div>
                      <div class="article-author-role"><?= esc($blog->author_role ?? 'JobberRecruit Contributor') ?></div>
                    </div>
                  </div>
                  <div class="article-footer-actions">
                    <a href="<?= base_url('blog/' . $blog->slug) ?>" class="article-read-link" aria-label="Read: <?= esc($blog->title) ?>">
                      Read article
                      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                  </div>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          </div>

          <!-- Helpful strip -->
          <div class="helpful-strip" role="group" aria-label="Was this content helpful">
            <p><svg aria-hidden="true"><use href="#i-heart-pulse"/></svg> Are these guides helping your job search?</p>
            <div class="helpful-actions">
              <button type="button" class="helpful-btn" onclick="alert('Thanks for your feedback!')"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 11v9H3v-9zM7 11l4-8a2 2 0 0 1 3 1.7V8h5a2 2 0 0 1 2 2.3l-1.3 7A2 2 0 0 1 20.7 19H7"/></svg> Yes, helpful</button>
              <button type="button" class="helpful-btn" onclick="alert('Thanks for your feedback!')"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 13V4h4v9zM17 13l-4 8a2 2 0 0 1-3-1.7V16H5a2 2 0 0 1-2-2.3l1.3-7A2 2 0 0 1 6.3 5H17"/></svg> Not quite</button>
            </div>
          </div>

          <?php else: ?>
          <div class="text-center py-5 my-5">
            <svg aria-hidden="true" width="48" height="48" style="color:var(--muted);margin-bottom:16px"><use href="#i-search"/></svg>
            <h3 class="h4 fw-bold mb-3">No articles found</h3>
            <p class="text-muted mb-4">
              <?php if ($q): ?>
                No results found for "<strong><?= esc($q) ?></strong>". Try different keywords or browse all articles.
              <?php else: ?>
                No blog posts published yet. Check back soon!
              <?php endif; ?>
            </p>
            <?php if ($q): ?>
              <a href="<?= base_url('blog') ?>" class="btn btn-accent">View all articles</a>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- Pagination -->
          <nav class="pagination" role="navigation" aria-label="Blog pagination">
            <?= $pager->links('default', 'blog_pager') ?>
          </nav>

        </div><!-- /articles column -->

        <!-- SIDEBAR -->
        <aside class="blog-sidebar" aria-label="Blog sidebar">

          <!-- Trending -->
          <div class="sidebar-card">
            <div class="sidebar-card-header">
              <svg aria-hidden="true"><use href="#i-chart"/></svg>
              <h3>Trending this week</h3>
            </div>
            <div class="sidebar-card-body">
              <nav class="trending-list" aria-label="Trending articles">
                <?php if (!empty($popularPosts)): ?>
                  <?php foreach ($popularPosts as $i => $post): ?>
                    <a href="<?= base_url('blog/' . $post->slug) ?>" class="trending-item">
                      <span class="trending-num" aria-hidden="true"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                      <div>
                        <div class="trending-title"><?= esc($post->title) ?></div>
                        <div class="trending-meta"><span><?= number_format($post->views ?? 0) ?> reads</span><span>·</span><span><?= max(1, ceil(str_word_count(strip_tags($post->content ?? '')) / 200)) ?> min</span></div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p style="font-size:.84rem;color:var(--muted)">No trending posts yet.</p>
                <?php endif; ?>
              </nav>
            </div>
          </div>

          <!-- Explore Topics -->
          <div class="sidebar-card">
            <div class="sidebar-card-header">
              <svg aria-hidden="true"><use href="#i-book"/></svg>
              <h3>Explore topics</h3>
            </div>
            <div class="sidebar-card-body">
              <div class="topics-cloud">
                <a href="<?= base_url('blog') ?>?q=Job+Search" class="topic-tag t-blue">Job Search</a>
                <a href="<?= base_url('blog') ?>?q=Interview+Tips" class="topic-tag t-orange">Interview Tips</a>
                <a href="<?= base_url('blog') ?>?q=CV+Writing" class="topic-tag t-green">CV Writing</a>
                <a href="<?= base_url('blog') ?>?q=Career+Growth" class="topic-tag t-blue">Career Growth</a>
                <a href="<?= base_url('blog') ?>?q=Salary" class="topic-tag t-orange">Salary</a>
                <a href="<?= base_url('blog') ?>?q=Remote+Work" class="topic-tag t-green">Remote Work</a>
                <a href="<?= base_url('blog') ?>?q=Recruitment" class="topic-tag">Recruitment</a>
                <a href="<?= base_url('blog') ?>?q=Industry+Trends" class="topic-tag t-blue">Industry Trends</a>
              </div>
            </div>
          </div>

          <!-- AI Tools -->
          <div class="sidebar-card">
            <div class="sidebar-card-header">
              <svg aria-hidden="true"><use href="#i-bot"/></svg>
              <h3>Boost your job search</h3>
            </div>
            <div class="sidebar-card-body">
              <div class="sidebar-tools-list">
                <a href="<?= base_url('ai-tools/resume-builder') ?>" class="sidebar-tool">
                  <div class="sidebar-tool-ic"><svg aria-hidden="true"><use href="#i-bot"/></svg></div>
                  <div>
                    <div class="sidebar-tool-name">AI Resume Builder</div>
                    <div class="sidebar-tool-desc">ATS-optimised in minutes</div>
                  </div>
                </a>
                <a href="<?= base_url('ai-tools/mock-interview') ?>" class="sidebar-tool">
                  <div class="sidebar-tool-ic accent"><svg aria-hidden="true"><use href="#i-mic"/></svg></div>
                  <div>
                    <div class="sidebar-tool-name">AI Mock Interview</div>
                    <div class="sidebar-tool-desc">Role-specific practice, instant feedback</div>
                  </div>
                </a>
                <a href="<?= base_url('career-advice') ?>" class="sidebar-tool">
                  <div class="sidebar-tool-ic"><svg aria-hidden="true"><use href="#i-bulb"/></svg></div>
                  <div>
                    <div class="sidebar-tool-name">Career Advice</div>
                    <div class="sidebar-tool-desc">Personalised guidance for your goals</div>
                  </div>
                </a>
                <a href="<?= base_url('training/cv-revamp') ?>" class="sidebar-tool">
                  <div class="sidebar-tool-ic accent"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
                  <div>
                    <div class="sidebar-tool-name">Professional CV Revamp</div>
                    <div class="sidebar-tool-desc">Reviewed by certified HR experts</div>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <!-- Newsletter -->
          <div class="sidebar-card sidebar-newsletter">
            <div class="sidebar-card-header">
              <svg aria-hidden="true"><use href="#i-mail"/></svg>
              <h3>Weekly career tips</h3>
            </div>
            <div class="sidebar-card-body">
              <p>Weekly job market insights, salary trends and career advice for Nigerian professionals — straight to your inbox.</p>
              <form class="sidebar-nl-form" action="<?= base_url('newsletter/subscribe') ?>" method="post" aria-label="Newsletter signup">
                <?= csrf_field() ?>
                <label for="nl-email" class="sr-only">Your email address</label>
                <input type="email" id="nl-email" name="email" placeholder="Your email address" required>
                <button type="submit">
                  <svg aria-hidden="true"><use href="#i-bell"/></svg> Subscribe free
                </button>
              </form>
              <p class="sidebar-nl-disclaimer">No spam. Unsubscribe at any time.</p>
            </div>
          </div>

          <!-- Social Follow -->
          <div class="sidebar-card">
            <div class="sidebar-card-header">
              <svg aria-hidden="true"><use href="#i-users"/></svg>
              <h3>Follow us</h3>
            </div>
            <div class="sidebar-card-body">
              <p style="font-size:.83rem;color:var(--muted);margin-bottom:14px">Join our community for daily career tips and fresh job opportunities.</p>
              <div style="display:flex;flex-direction:column;gap:8px">
                <a href="https://facebook.com/jobberrecruit" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="justify-content:flex-start;gap:10px;font-size:.82rem">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54v-2.89h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.991 22 12z"/></svg>
                  Facebook
                </a>
                <a href="https://twitter.com/jobberrecruit" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="justify-content:flex-start;gap:10px;font-size:.82rem">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932L18.901 1.153Zm-1.61 19.56h2.039L6.486 3.24H4.298Z"/></svg>
                  X (Twitter)
                </a>
                <a href="https://linkedin.com/company/jobberrecruit" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="justify-content:flex-start;gap:10px;font-size:.82rem">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.137 1.445-2.137 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 1 1 0-4.124 2.062 2.062 0 0 1 0 4.124zM7.119 20.452H3.555V9h3.564v11.452z"/></svg>
                  LinkedIn
                </a>
              </div>
            </div>
          </div>

          <!-- Sidebar Ad -->
          <div class="sidebar-ad" aria-label="Advertisement">
            <p class="sidebar-ad-label">Advertisement</p>
            <ins class="adsbygoogle"
                 style="display:block;width:100%;height:auto"
                 data-ad-client="ca-pub-3464186884176173"
                 data-ad-slot="6229476516"
                 data-ad-format="rectangle"
                 data-full-width-responsive="true"></ins>
          </div>

        </aside><!-- /sidebar -->
      </div><!-- /blog-layout -->
    </div>
  </section>

  <!-- DUAL CTA -->
  <section class="section white-bg" aria-labelledby="cta-h" style="padding:76px 0;background:var(--white)">
    <div class="container">
      <div class="text-center" style="margin-bottom:36px">
        <p class="section-label"><svg aria-hidden="true"><use href="#i-spark"/></svg> Put the insights to work</p>
        <h2 class="section-title" id="cta-h">Ready to take your next <span>career step?</span></h2>
        <p class="section-sub" style="margin:0 auto">Use everything you've learned — the tools, the guides, the community — to land your next role faster.</p>
      </div>
      <div class="dual-cta">
        <div class="cta-panel blue">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
          <h2>Start your career journey</h2>
          <p>Create a free account, upload your CV, and start applying to verified roles that match your strengths.</p>
          <ul class="cta-list" aria-label="Job seeker benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> AI resume builder</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> AI mock interviews</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Personalised career advice</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Smart job alerts</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Training &amp; certificates</li>
          </ul>
          <a href="<?= base_url('register') ?>" class="btn btn-accent">Create free account →</a>
        </div>
        <div class="cta-panel light">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div>
          <h2>Looking to hire? Post a job free</h2>
          <p>Post your vacancy free and reach verified, pre-screened candidates across every state in Nigeria.</p>
          <ul class="cta-list" aria-label="Employer benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Post your first job free</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Smart recruitment dashboard</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Verified candidate database <span class="cta-tag">Paid</span></li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Application management tools</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Referral rewards</li>
          </ul>
          <a href="<?= base_url('post-a-job') ?>" class="btn btn-primary">Post a job free →</a>
        </div>
      </div>
    </div>
  </section>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('nl-email')?.addEventListener('keydown', function(e) { if(e.key==='Enter') e.preventDefault(); });
</script>
<?= $this->endSection() ?>
