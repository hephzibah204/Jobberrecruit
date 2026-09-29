<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "@id": "https://www.jobberrecruit.com/employers#webpage",
  "name": "For Employers — Post a Job & Hire Talent",
  "url": "https://www.jobberrecruit.com/employers",
  "inLanguage": "en-NG",
  "isPartOf": {
    "@type": "WebSite",
    "name": "JobberRecruit",
    "url": "https://www.jobberrecruit.com"
  },
  "publisher": {
    "@type": "Organization",
    "name": "JobberRecruit",
    "url": "https://www.jobberrecruit.com",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.jobberrecruit.com/assets/logo.png"
    }
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://www.jobberrecruit.com/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "For employers",
      "item": "https://www.jobberrecruit.com/employers"
    }
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How long does it take for my job to go live?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "On weekdays, premium job posts are reviewed and go live within 60 minutes of payment. Standard listings are typically approved the same business day."
      }
    },
    {
      "@type": "Question",
      "name": "Are candidates on JobberRecruit verified?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Candidates go through profile verification, and our screening tools let you rank applicants against your own assessment questions so you only spend time on the best fits."
      }
    },
    {
      "@type": "Question",
      "name": "Can I post a job for free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can create an employer account and post your first job free. Premium plans add featured placement, social distribution, advanced screening and a dedicated account officer."
      }
    },
    {
      "@type": "Question",
      "name": "Can I hide my company name when hiring?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Our anonymity feature lets you hide your company name and details — ideal when replacing a current employee or hiring confidentially."
      }
    },
    {
      "@type": "Question",
      "name": "What does the unlimited subscription include?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Subscriptions let you post unlimited premium jobs for one flat, prepaid fee — with 60-minute approval, featured placement, social distribution, advanced screening and a dedicated account officer. A fair-use limit of 30 active posts keeps quality high, and longer terms lower your effective monthly cost."
      }
    },
    {
      "@type": "Question",
      "name": "How do I pay, and are there contracts?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You fund your employer wallet through secure payment, then apply the balance to any plan, subscription or add-on. There are no long-term contracts — you only pay for what you post."
      }
    }
  ]
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- SVG Sprite -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></symbol>
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></symbol>
    <symbol id="i-bookmark-fill" viewBox="0 0 24 24" fill="currentColor"><path d="M17 3H7a2 2 0 0 0-2-2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-verified-disc" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-x-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6m0-6 6 6"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
    <symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M5 12H1M23 12h-4M6.3 6.3 3.5 3.5M20.5 20.5l-2.8-2.8M17.7 6.3l2.8-2.8M3.5 20.5l2.8-2.8"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-bot" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="16" height="11" rx="3"/><path d="M12 3v5M9 13h.01M15 13h.01M2 14h2M20 14h2"/></symbol>
    <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M9 14a5 5 0 1 1 6 0c-.7.5-1 1.2-1 2H10c0-.8-.3-1.5-1-2Z"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></symbol>
    <symbol id="i-gift" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M5 12v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-7"/><path d="M12 8S11 2 8 2a2.5 2.5 0 0 0 0 5M12 8s1-6 4-6a2.5 2.5 0 0 1 0 5"/></symbol>
    <symbol id="i-chip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/></symbol>
    <symbol id="i-coins" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="9" cy="6" rx="6" ry="3"/><path d="M3 6v6c0 1.7 2.7 3 6 3s6-1.3 6-3V6"/><path d="M15 11c2.5.2 6 1.2 6 3 0 1.7-2.7 3-6 3-1 0-2-.1-3-.3"/><path d="M3 12c0 1.7 2.7 3 6 3"/></symbol>
    <symbol id="i-mega" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"/><path d="M15 8a4 4 0 0 1 0 8M18 5a8 8 0 0 1 0 14"/></symbol>
    <symbol id="i-gear" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
    <symbol id="i-drop" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.7S5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-11.3-7-11.3Z"/></symbol>
    <symbol id="i-heart-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.5-1.5 3-3.3 3-5.5A4.5 4.5 0 0 0 12 5.5 4.5 4.5 0 0 0 2 8.5c0 2.2 1.5 4 3 5.5l7 7Z"/><path d="M3.2 12h4l1.5-3 2.5 5 1.5-2h4.5"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="i-handshake" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17 2 2a1 1 0 0 0 1.4 0l3.6-3.6"/><path d="m2 12 3-3 5 5 2-2 5 5 3-3"/><path d="m21 12-3-3-3 3"/><path d="M3 9 6 6l4 4"/></symbol>
    <symbol id="i-bank" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6M4 10v8M20 10v8M8 10v8M16 10v8M3 21h18"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
    <symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/></symbol>
    <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01M10 21v-3h4v3"/></symbol>
    <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="i-chev-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-sliders" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></symbol>
    <symbol id="i-headset" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14v-2a9 9 0 0 1 18 0v2"/><path d="M21 16a2 2 0 0 1-2 2h-1v-6h1a2 2 0 0 1 2 2zM3 16a2 2 0 0 0 2 2h1v-6H5a2 2 0 0 0-2 2zM21 18a3 3 0 0 1-3 3h-6"/></symbol>
    <symbol id="i-share-nodes" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></symbol>
    <symbol id="i-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3H2l8 9.5V19l4 2v-8.5L22 3Z"/></symbol>
  </defs>
</svg>

<main id="main">

  <!-- HERO -->
  <section class="emp-hero">
    <span class="gridbg" aria-hidden="true"></span>
    <div class="container">
      <div class="emp-hero-inner">
        <div>
          <nav class="pg-bc" aria-label="Breadcrumb">
            <a href="<?= base_url() ?>">Home</a><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg><span aria-current="page">For employers</span>
          </nav>
          <div class="emp-eyebrow"><svg aria-hidden="true"><use href="#i-building"/></svg>For employers &amp; recruiters</div>
          <h1>Post a Job.<br><em>Find Talent.</em> Done.</h1>
          <p class="lede">Stop sifting through clutter. Get instant access to a pool of pre-verified candidates and go from &ldquo;post&rdquo; to &ldquo;interview&rdquo; in record time.</p>
          <div class="emp-usps">
            <div class="emp-usp"><span class="emp-usp-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></span><span class="emp-usp-tx"><strong>Slash hiring time</strong><span>Go from posted to shortlisted in record time</span></span></div>
            <div class="emp-usp"><span class="emp-usp-ic"><svg aria-hidden="true"><use href="#i-chart"/></svg></span><span class="emp-usp-tx"><strong>Boost quality</strong><span>Target the top 10% of talent, not just whoever is available</span></span></div>
            <div class="emp-usp"><span class="emp-usp-ic"><svg aria-hidden="true"><use href="#i-coins"/></svg></span><span class="emp-usp-tx"><strong>Cut costs</strong><span>Premium recruitment tools at a fraction of agency fees</span></span></div>
          </div>
          <div class="emp-hero-actions">
            <a href="<?= base_url('register?type=employer') ?>" class="btn btn-accent btn-lg"><svg aria-hidden="true"><use href="#i-rocket"/></svg>Post your first job</a>
            <a href="#pricing" class="btn btn-white btn-lg">See pricing</a>
          </div>
          <div class="emp-trust-line">
            <div class="emp-trust-avatars" aria-hidden="true">
              <span style="background:#0861A9">MT</span><span style="background:#16a34a">UB</span><span style="background:#C8770E">TE</span><span style="background:#7c3aed">+</span>
            </div>
            <span>Trusted by <strong style="color:#fff">160+ companies</strong> hiring across Nigeria</span>
          </div>
        </div>

        <div class="emp-hero-visual">
          <div class="compare-card">
            <span class="cmp-tag">The difference</span>
            <div class="compare-row compare-bad">
              <span class="cmp-ic bad"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span>
              <span class="cmp-tx"><h4>Traditional hiring</h4><p>Sifting through 100+ unscreened resumes by hand</p></span>
            </div>
            <div class="compare-row compare-good">
              <span class="cmp-ic good"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>
              <span class="cmp-tx"><h4>JobberRecruit hiring</h4><p>Pre-screened, ranked candidates in your dashboard</p></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- stat strip -->
    <div class="container">
      <div class="emp-statstrip">
        <div class="emp-stat"><div class="emp-stat-n">160<span>+</span></div><div class="emp-stat-l">Companies hiring</div></div>
        <div class="emp-stat"><div class="emp-stat-n">60<span>min</span></div><div class="emp-stat-l">Avg. approval time</div></div>
        <div class="emp-stat"><div class="emp-stat-n">95<span>%</span></div><div class="emp-stat-l">Satisfaction rate</div></div>
        <div class="emp-stat"><div class="emp-stat-n">10<span>min</span></div><div class="emp-stat-l">To post a job</div></div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section class="sec tint" aria-labelledby="feat-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-spark"/></svg>More than just a job board</div>
        <h2 class="section-title" id="feat-h">A full suite of <span>premium hiring tools</span></h2>
        <p>When you post on JobberRecruit, you unlock everything you need to attract, screen and hire the right people faster.</p>
      </div>
      <div class="feat-grid">
        <div class="feat-card"><div class="feat-ic fi-1"><svg aria-hidden="true"><use href="#i-eye"/></svg></div><h3>3&times; visibility boost</h3><p>Your ad doesn&rsquo;t just sit there. We feature it on our homepage and push it through premium channels so the right people see it.</p></div>
        <div class="feat-card"><div class="feat-ic fi-2"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div><h3>60-minute approval</h3><p>Time is money. On weekdays, your job goes live within an hour of posting &mdash; no waiting days for review.</p></div>
        <div class="feat-card"><div class="feat-ic fi-3"><svg aria-hidden="true"><use href="#i-share-nodes"/></svg></div><h3>Smart distribution</h3><p>We actively distribute your ad to targeted professional communities and networks unavailable to standard users.</p></div>
        <div class="feat-card"><div class="feat-ic fi-4"><svg aria-hidden="true"><use href="#i-lock"/></svg></div><h3>Complete privacy</h3><p>Hiring to replace a current employee? Our anonymity feature lets you hide your company name and details completely.</p></div>
        <div class="feat-card"><div class="feat-ic fi-5"><svg aria-hidden="true"><use href="#i-filter"/></svg></div><h3>Advanced screening</h3><p>Stop reading irrelevant CVs. Use custom assessment questions to automatically rank candidates against your needs.</p></div>
        <div class="feat-card"><div class="feat-ic fi-6"><svg aria-hidden="true"><use href="#i-sliders"/></svg></div><h3>Dashboard control</h3><p>Say goodbye to cluttered email. Manage, sort, filter and shortlist every application from one employer dashboard.</p></div>
        <div class="feat-card"><div class="feat-ic fi-7"><svg aria-hidden="true"><use href="#i-headset"/></svg></div><h3>Dedicated support</h3><p>Every premium poster gets a dedicated account officer to optimise your ad for the best possible results.</p></div>
        <div class="feat-card"><div class="feat-ic fi-8"><svg aria-hidden="true"><use href="#i-mega"/></svg></div><h3>Social dominance</h3><p>Your vacancy is showcased on our high-traffic Instagram and X pages, putting your brand in front of thousands.</p></div>
        <div class="feat-card"><div class="feat-ic fi-9"><svg aria-hidden="true"><use href="#i-users"/></svg></div><h3>Direct community blasts</h3><p>We push your job into our exclusive WhatsApp and Telegram groups for instant visibility on candidates&rsquo; phones.</p></div>
      </div>
    </div>
  </section>

  <!-- CANDIDATE REACH -->
  <section class="sec white" aria-labelledby="reach-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-users"/></svg>Your talent pool</div>
        <h2 class="section-title" id="reach-h">Reach the candidates <span>your roles need</span></h2>
        <p>A deep, active pool of pre-verified professionals across every major industry in Nigeria.</p>
      </div>
      <div class="reach">
        <div class="reach-n">12,000<span>+</span></div>
        <div class="reach-sub">active, verified candidates ready to be matched to your roles &mdash; growing every week.</div>
        <div class="reach-chips">
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-chip"/></svg>IT &amp; Software</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-bank"/></svg>Banking &amp; Finance</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-heart-pulse"/></svg>Healthcare</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-mega"/></svg>Marketing &amp; Sales</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-building"/></svg>Engineering</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-cap"/></svg>Education</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-bag"/></svg>Operations &amp; Admin</span>
          <span class="reach-chip"><svg aria-hidden="true"><use href="#i-globe"/></svg>Remote roles</span>
        </div>
      </div>
    </div>
  </section>

  <!-- COLLABORATOR BAND -->
  <section class="sec white">
    <div class="container">
      <div class="collab">
        <div class="collab-ic"><svg aria-hidden="true"><use href="#i-handshake"/></svg></div>
        <h2>The Collaborator Ecosystem</h2>
        <p>We&rsquo;ve partnered with influencers and career platforms across Nigeria. When we post your role, they repost &mdash; amplifying your reach exponentially beyond our own audience.</p>
      </div>
    </div>
  </section>

  <!-- SOCIAL PROOF -->
  <section class="sec tint" aria-labelledby="proof-h">
    <div class="container">
      <div class="proof-grid">
        <div class="proof-left">
          <div class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg>Trusted at scale</div>
          <h2 id="proof-h">Join the smartest hiring teams in Nigeria</h2>
          <p>Over 160 companies &mdash; from agile SMEs to major multinationals &mdash; trust JobberRecruit every month to build their teams.</p>
          <div class="quote-card">
            <div class="quote-mark" aria-hidden="true">&ldquo;</div>
            <p>The process was seamless. I created an account, funded my wallet, and posted my ad in under 10 minutes. The quality of candidates was exactly what we needed.</p>
            <div class="quote-author">
              <div class="quote-av">HR</div>
              <div><strong>HR Director</strong><span>Leading FMCG Company</span></div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:12px;margin-top:16px;padding:16px 18px;background:#fff;border:1px solid var(--border);border-radius:12px">
            <div class="quote-av" style="background:var(--brand);color:#fff">TO</div>
            <div><p style="font-size:.85rem;color:var(--text);line-height:1.5;margin-bottom:4px;font-style:italic">&ldquo;We filled two senior engineering roles in under three weeks. The screening tools saved my team days of CV reading.&rdquo;</p><span style="font-size:.76rem;color:var(--muted)"><strong style="color:var(--text)">Tobi Ade</strong> &middot; Head of People, Tech Startup</span></div>
          </div>
        </div>
        <div class="proof-card">
          <h3>Trusted by leading companies</h3>
          <div class="logo-grid">
            <div class="logo-box">MTN</div>
            <div class="logo-box">Union Bank</div>
            <div class="logo-box">TotalEnergies</div>
            <div class="logo-box">Access</div>
            <div class="logo-box">Dangote</div>
            <div class="logo-box">Paystack</div>
          </div>
          <div class="proof-stats">
            <div class="proof-stat"><div class="proof-stat-n">160+</div><div class="proof-stat-l">Companies</div></div>
            <div class="proof-stat"><div class="proof-stat-n">95%</div><div class="proof-stat-l">Satisfaction rate</div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="sec white" aria-labelledby="works-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-clock"/></svg>How it works</div>
        <h2 class="section-title" id="works-h">Ready to find your <span>next great hire?</span></h2>
        <p>It takes less than 10 minutes to get started.</p>
      </div>
      <div class="steps-grid">
        <div class="step-card"><div class="step-num">1</div><h3>Create account</h3><p>Sign up as an employer and complete your company profile in minutes.</p></div>
        <div class="step-card"><div class="step-num">2</div><h3>Make payment</h3><p>Choose your plan and pay securely through our platform &mdash; no hidden fees.</p></div>
        <div class="step-card"><div class="step-num">3</div><h3>Post your job</h3><p>Publish your role and watch qualified applications roll in from verified candidates.</p></div>
      </div>
      <div class="steps-cta">
        <a href="<?= base_url('register?type=employer') ?>" class="btn btn-primary btn-lg"><svg aria-hidden="true"><use href="#i-rocket"/></svg>Get started now</a>
        <p class="muted">Have questions first? Email our support team at <a href="mailto:support@jobberrecruit.com">support@jobberrecruit.com</a></p>
      </div>
    </div>
  </section>

  <!-- PLANS & PRICING -->
  <section class="sec tint" id="pricing" aria-labelledby="price-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-coins"/></svg>Plans &amp; pricing</div>
        <h2 class="section-title" id="price-h">Simple, transparent <span>job ad pricing</span></h2>
        <p>Post your first job free, upgrade when you need more reach, or go unlimited with a subscription. No contracts.</p>
      </div>

      <div class="pr-grid">
        <div class="pcard">
          <div class="pname">Free</div>
          <div class="ptag">Try the platform with your first role.</div>
          <div class="pamt free"><span class="num">&#8358;0</span></div>
          <div class="psub">First standard job post</div>
          <a href="<?= base_url('register?type=employer') ?>" class="btn btn-outline">Start free</a>
          <ul class="pfeats">
            <li class="pfeats-h">Includes</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>1 standard job post (30 days)</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Employer dashboard</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Application management</li>
            <li class="off"><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span>Featured placement</li>
            <li class="off"><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span>Social distribution</li>
          </ul>
        </div>

        <div class="pcard">
          <div class="pname">Standard</div>
          <div class="ptag">For steady, everyday hiring needs.</div>
          <div class="pamt"><span class="cur">&#8358;</span><span class="num">5k</span><span class="per">/ job</span></div>
          <div class="psub">Billed per job post</div>
          <a href="<?= base_url('post-a-job?plan=standard') ?>" class="btn btn-outline">Choose Standard</a>
          <ul class="pfeats">
            <li class="pfeats-h">Everything in Free, plus</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Priority same-day approval</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Standard search visibility</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Email application alerts</li>
            <li class="off"><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span>Homepage feature</li>
          </ul>
        </div>

        <div class="pcard featured">
          <span class="pflag">Most popular</span>
          <div class="pname">Premium</div>
          <div class="ptag">Maximum reach and faster hires.</div>
          <div class="pamt"><span class="cur">&#8358;</span><span class="num">15k</span><span class="per">/ job</span></div>
          <div class="psub">Billed per job post</div>
          <a href="<?= base_url('post-a-job?plan=premium') ?>" class="btn btn-primary">Choose Premium</a>
          <ul class="pfeats">
            <li class="pfeats-h">Everything in Standard, plus</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>60-minute approval</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Homepage &amp; featured placement</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Social &amp; community distribution</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Advanced screening &amp; ranking</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Dedicated account officer</li>
          </ul>
        </div>

        <div class="pcard">
          <div class="pname">Business</div>
          <div class="ptag">For teams hiring at volume.</div>
          <div class="pamt"><span class="num">Custom</span></div>
          <div class="psub">Tailored to your volume</div>
          <a href="<?= base_url('recruitment/services#inquiry') ?>" class="btn btn-outline">Talk to sales</a>
          <ul class="pfeats">
            <li class="pfeats-h">Everything in Premium, plus</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Bulk &amp; unlimited postings</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Multi-seat team access</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Talent-pool search</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Priority support &amp; reporting</li>
            <li><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span>Volume discounts</li>
          </ul>
        </div>
      </div>

      <!-- value props -->
      <div class="vp-strip" style="margin-top:44px">
        <div class="vp"><span class="vp-ic"><svg aria-hidden="true"><use href="#i-lock"/></svg></span><div><strong>Secure payment</strong><span>Fund your wallet safely &mdash; pay only for what you use.</span></div></div>
        <div class="vp"><span class="vp-ic"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span><div><strong>No contracts</strong><span>Post when you need to. Cancel or pause anytime.</span></div></div>
        <div class="vp"><span class="vp-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></span><div><strong>Live in 60 minutes</strong><span>Premium posts are reviewed and live within the hour.</span></div></div>
        <div class="vp"><span class="vp-ic"><svg aria-hidden="true"><use href="#i-headset"/></svg></span><div><strong>Real support</strong><span>A dedicated account officer on every Premium post.</span></div></div>
      </div>
    </div>
  </section>

  <!-- SUBSCRIBE & SAVE -->
  <section class="sub-sec" id="subscribe" aria-labelledby="sub-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-rocket"/></svg>Subscribe &amp; save</div>
        <h2 class="section-title" id="sub-h">Hiring often? <span>Go unlimited</span></h2>
        <p>For teams that hire continuously. Post unlimited premium jobs for one flat fee &mdash; the longer you commit, the more you save. Prepaid, no surprise renewals.</p>
      </div>

      <div class="sub-toggle-wrap">
        <div class="sub-toggle" role="tablist" aria-label="Billing term">
          <button class="sub-tg" role="tab" aria-selected="false" onclick="setTerm('monthly',this)">Monthly</button>
          <button class="sub-tg" role="tab" aria-selected="false" onclick="setTerm('quarterly',this)">Quarterly</button>
          <button class="sub-tg" role="tab" aria-selected="false" onclick="setTerm('biannual',this)">6 months</button>
          <button class="sub-tg active" role="tab" aria-selected="true" onclick="setTerm('annual',this)">Annual</button>
        </div>
      </div>

      <div class="sub-grid">
        <div class="subcard" data-term="monthly">
          <div class="subterm">Monthly</div>
          <div class="subterm-sub">Rolling, prepaid monthly</div>
          <div class="subprice"><span class="cur">&#8358;</span><span class="num">40k</span><span class="per">/ month</span></div>
          <div class="sub-permo"><b>&#8358;40,000</b> per month</div>
          <span class="sub-save none">Save</span>
          <a href="<?= base_url('post-a-job?plan=sub-monthly') ?>" class="btn btn-outline">Choose monthly</a>
          <div class="sub-billed">Billed &#8358;40,000 each month</div>
        </div>

        <div class="subcard" data-term="quarterly">
          <div class="subterm">Quarterly</div>
          <div class="subterm-sub">Every 3 months, prepaid</div>
          <div class="subprice"><span class="cur">&#8358;</span><span class="num">34k</span><span class="per">/ month</span></div>
          <div class="sub-permo"><b>&#8358;102,000</b> billed quarterly</div>
          <span class="sub-save">Save 15%</span>
          <a href="<?= base_url('post-a-job?plan=sub-quarterly') ?>" class="btn btn-outline">Choose quarterly</a>
          <div class="sub-billed">Billed &#8358;102,000 every 3 months</div>
        </div>

        <div class="subcard" data-term="biannual">
          <div class="subterm">6 months</div>
          <div class="subterm-sub">Every 6 months, prepaid</div>
          <div class="subprice"><span class="cur">&#8358;</span><span class="num">30k</span><span class="per">/ month</span></div>
          <div class="sub-permo"><b>&#8358;180,000</b> billed half-yearly</div>
          <span class="sub-save">Save 25%</span>
          <a href="<?= base_url('post-a-job?plan=sub-biannual') ?>" class="btn btn-outline">Choose 6 months</a>
          <div class="sub-billed">Billed &#8358;180,000 every 6 months</div>
        </div>

        <div class="subcard best" data-term="annual">
          <span class="subflag">Best value</span>
          <div class="subterm">Annual</div>
          <div class="subterm-sub">Yearly, prepaid</div>
          <div class="subprice"><span class="cur">&#8358;</span><span class="num">26k</span><span class="per">/ month</span></div>
          <div class="sub-permo"><b>&#8358;312,000</b> billed yearly</div>
          <span class="sub-save">Save 35%</span>
          <a href="<?= base_url('post-a-job?plan=sub-annual') ?>" class="btn btn-primary">Choose annual</a>
          <div class="sub-billed">Billed &#8358;312,000 once a year</div>
        </div>
      </div>

      <div class="sub-includes">
        <h3><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Every subscription includes</h3>
        <ul class="sub-inc-grid">
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Unlimited premium job posts</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>60-minute approval on every post</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Homepage &amp; featured placement</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Social &amp; community distribution</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Advanced screening &amp; ranking</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Dedicated account officer</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Anonymous / confidential posting</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg>Cancel renewal anytime &mdash; no lock-in</li>
        </ul>
        <div class="sub-fairuse">
          <svg aria-hidden="true"><use href="#i-bulb"/></svg>
          <span><strong>Fair use:</strong> &ldquo;Unlimited&rdquo; covers genuine, distinct vacancies for your organisation &mdash; up to 30 active posts at a time. Duplicate or non-genuine listings aren&rsquo;t permitted, which keeps quality high for candidates.</span>
        </div>
      </div>
    </div>
  </section>

  <!-- COMPARISON TABLE -->
  <section class="cmp-sec" aria-labelledby="cmp-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-sliders"/></svg>Full comparison</div>
        <h2 class="section-title" id="cmp-h">Compare every <span>feature</span></h2>
        <p>See exactly what&rsquo;s included in each plan, side by side.</p>
      </div>
      <div class="cmp-wrap">
        <table class="cmp-table">
          <thead><tr><th class="feat-col">Feature</th><th>Free</th><th>Standard</th><th class="hl">Premium</th><th>Business</th></tr></thead>
          <tbody>
            <tr class="cmp-cat"><td colspan="5">Posting</td></tr>
            <tr><td class="feat-col">Job posts</td><td>1</td><td>Per post</td><td>Per post</td><td>Bulk / unlimited</td></tr>
            <tr><td class="feat-col">Listing duration</td><td>30 days</td><td>30 days</td><td>30 days</td><td>Flexible</td></tr>
            <tr><td class="feat-col">Approval speed</td><td>Same day</td><td>Priority</td><td>60 minutes</td><td>60 minutes</td></tr>
            <tr class="cmp-cat"><td colspan="5">Visibility</td></tr>
            <tr><td class="feat-col">Search visibility</td><td>Standard</td><td>Standard</td><td>Boosted</td><td>Boosted</td></tr>
            <tr><td class="feat-col">Homepage feature</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr><td class="feat-col">Social media distribution</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr><td class="feat-col">WhatsApp &amp; Telegram blast</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr class="cmp-cat"><td colspan="5">Screening &amp; management</td></tr>
            <tr><td class="feat-col">Employer dashboard</td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr><td class="feat-col">Advanced screening &amp; ranking</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr><td class="feat-col">Anonymous / confidential posting</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr><td class="feat-col">Talent-pool search</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr class="cmp-cat"><td colspan="5">Support</td></tr>
            <tr><td class="feat-col">Dedicated account officer</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
            <tr><td class="feat-col">Multi-seat team access</td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="no"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></span></td><td><span class="yes"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- BOOSTS & ADD-ONS -->
  <section class="sec white" aria-labelledby="addon-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-spark"/></svg>Boosts &amp; add-ons</div>
        <h2 class="section-title" id="addon-h">Give any post an <span>extra push</span></h2>
        <p>Optional boosts you can add to any plan at checkout or while your post is live.</p>
      </div>
      <div class="addon-grid">
        <div class="addon"><div class="addon-top"><span class="addon-ic ai-1"><svg aria-hidden="true"><use href="#i-star"/></svg></span><span class="addon-price">&#8358;5k<span> / post</span></span></div><h3>Featured listing</h3><p>Pin your job to the top of search results and category pages for maximum visibility.</p></div>
        <div class="addon"><div class="addon-top"><span class="addon-ic ai-2"><svg aria-hidden="true"><use href="#i-bell"/></svg></span><span class="addon-price">&#8358;3k<span> / post</span></span></div><h3>Urgent badge</h3><p>Add a bold &ldquo;Urgent&rdquo; tag so candidates know to apply fast &mdash; great for time-sensitive roles.</p></div>
        <div class="addon"><div class="addon-top"><span class="addon-ic ai-3"><svg aria-hidden="true"><use href="#i-mega"/></svg></span><span class="addon-price">&#8358;7k<span> / post</span></span></div><h3>Social blast</h3><p>A dedicated feature across our Instagram, X, WhatsApp and Telegram channels.</p></div>
      </div>
    </div>
  </section>

  <!-- DEMO / LEAD CAPTURE -->
  <section class="sec white" id="demo" aria-labelledby="demo-h">
    <div class="container">
      <div class="demo-band">
        <div class="demo-left">
          <div class="section-label"><svg aria-hidden="true"><use href="#i-headset"/></svg>Talk to sales</div>
          <h2 id="demo-h">Talk to our team about the right plan</h2>
          <p>Tell us what you&rsquo;re hiring for and a JobberRecruit specialist will recommend the best plan and answer your questions &mdash; no obligation.</p>
          <ul class="demo-perks">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg>A quick 15-minute call, no obligation</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg>Volume &amp; multi-role pricing</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg>Answers to all your questions</li>
          </ul>
        </div>
        <div class="demo-right">
          <form class="demo-form" onsubmit="return submitDemo(event)">
            <div class="demo-field"><label for="d-name">Full name</label><input id="d-name" type="text" placeholder="e.g. Adaeze Okonkwo" required></div>
            <div class="demo-field"><label for="d-email">Work email</label><input id="d-email" type="email" placeholder="you@company.com" required></div>
            <div class="demo-field"><label for="d-company">Company</label><input id="d-company" type="text" placeholder="Your company name" required></div>
            <div class="demo-field"><label for="d-roles">Roles to fill</label>
              <select id="d-roles" required>
                <option value="" disabled selected>Select&hellip;</option>
                <option>1 role</option><option>2&ndash;5 roles</option><option>6&ndash;20 roles</option><option>20+ roles</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Talk to our sales team</button>
            <p class="demo-formnote">We&rsquo;ll reply within one business day. No spam, ever.</p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- TWO WAYS TO HIRE -->
  <section class="sec tint" aria-labelledby="dual-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-handshake"/></svg>Two ways to hire</div>
        <h2 class="section-title" id="dual-h">Hire your way, <span>on your terms</span></h2>
        <p>Post it yourself with our self-serve plans, or let our recruiters headhunt and shortlist candidates for you.</p>
      </div>
      <div class="dual">
        <div class="dual-card accent">
          <span class="dual-deco" aria-hidden="true"></span>
          <h3>Do it yourself</h3>
          <p>Post a job, manage applicants, and hire on your own timeline. Plans from free to premium &mdash; best for active, ongoing hiring.</p>
          <a href="#pricing" class="btn" style="background:#0A2F57;color:#fff;border-color:#0A2F57">See plans &amp; pricing &#x2192;</a>
        </div>
        <div class="dual-card blue">
          <span class="dual-deco" aria-hidden="true"></span>
          <h3>Let us hire for you</h3>
          <p>Our recruiters headhunt, screen and shortlist on your behalf &mdash; including passive talent. Best for senior, urgent or hard-to-fill roles.</p>
          <a href="<?= base_url('recruitment/services') ?>" class="btn btn-accent">Explore recruitment services &#x2192;</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="sec white" aria-labelledby="faq-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true"><use href="#i-bulb"/></svg>Questions &amp; answers</div>
        <h2 class="section-title" id="faq-h">Everything employers <span>ask us</span></h2>
      </div>
      <div class="faq-wrap">
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">How long does it take for my job to go live? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">On weekdays, premium job posts are reviewed and go live within 60 minutes of payment. Standard listings are typically approved the same business day.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Are candidates on JobberRecruit verified? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Yes. Candidates go through profile verification, and our screening tools let you rank applicants against your own assessment questions so you only spend time on the best fits.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Can I post a job for free? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">You can create an employer account and post your first job free. Premium plans add featured placement, social distribution, advanced screening and a dedicated account officer.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Can I hide my company name when hiring? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Yes. Our anonymity feature lets you hide your company name and details &mdash; ideal when replacing a current employee or hiring confidentially.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">What does the unlimited subscription include? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Subscriptions let you post unlimited premium jobs for one flat, prepaid fee &mdash; with 60-minute approval, featured placement, social distribution, advanced screening and a dedicated account officer. A fair-use limit of 30 active posts keeps quality high, and longer terms lower your effective monthly cost.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">How do I pay, and are there contracts? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">You fund your employer wallet through secure payment, then apply the balance to any plan, subscription or add-on. There are no long-term contracts &mdash; you only pay for what you post.</div></div></div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="sec white" style="padding-top:0">
    <div class="container">
      <div class="final-cta">
        <h2>Your next great hire is one post away</h2>
        <p>Join 160+ companies hiring smarter on JobberRecruit. Post your first job in under 10 minutes.</p>
        <div class="final-cta-actions">
          <a href="<?= base_url('post-a-job') ?>" class="btn btn-accent btn-lg"><svg aria-hidden="true"><use href="#i-rocket"/></svg>Post your first job</a>
          <a href="#pricing" class="btn btn-white btn-lg">Compare plans</a>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- sticky mobile CTA -->
<div class="sticky-cta" aria-label="Quick actions">
  <a href="<?= base_url('post-a-job') ?>" class="btn btn-accent"><svg aria-hidden="true" style="width:16px;height:16px"><use href="#i-rocket"/></svg>Post a job</a>
  <a href="#demo" class="btn btn-outline">Talk to sales</a>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ===== BRAND TOKENS & OVERRIDES ===== */
:root {
  --brand:        #0B5C9C;
  --brand-dark:   #084A80;
  --brand-deep:   #062E52;
  --brand-light:  #F0F7FD;
  --accent:       #F29D38;
  --accent-dark:  #D98223;
  --text:         #1E2530;
  --muted:        #64748B;
  --bg:           #F8FAFC;
  --white:        #ffffff;
  --border:       #E2E8F0;
  --success:      #10B981;
  --radius:       16px;
  --shadow:       0 4px 20px rgba(6,46,82,0.06);
  --shadow-lg:    0 20px 40px rgba(6,46,82,0.12);
  --transition:   .25s cubic-bezier(0.4, 0, 0.2, 1);
}

h1, h2, h3, h4, .display { font-family: 'Sora', 'Inter', sans-serif; letter-spacing: -.02em; }
.section-label {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: .75rem; font-weight: 700; letter-spacing: .08em;
  text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 6px 14px;
  border-radius: 30px; margin-bottom: 16px;
  border: 1px solid rgba(11, 92, 156, 0.1);
}
.section-label svg { width: 13px; height: 13px; }
.section-title {
  font-size: clamp(1.75rem, 3.2vw, 2.5rem);
  font-weight: 800; line-height: 1.15; margin-bottom: 14px; color: var(--brand-deep);
}
.section-title span { color: var(--brand); }

/* Buttons */
.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  padding: 12px 24px; border-radius: 10px;
  font-family: 'Inter', sans-serif; font-size: .9rem; font-weight: 600;
  cursor: pointer; border: 1.5px solid transparent;
  transition: var(--transition); text-decoration: none;
  -webkit-tap-highlight-color: transparent; touch-action: manipulation;
}
.btn svg { width: 18px; height: 18px; }
.btn-primary  { background: var(--brand);  color: var(--white); border-color: var(--brand); }
.btn-primary:hover  { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; transform: translateY(-1px); }
.btn-outline  { background: transparent; color: var(--brand); border-color: var(--brand); }
.btn-outline:hover  { background: var(--brand-light); text-decoration: none; }
.btn-accent   { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
.btn-accent:hover   { background: var(--accent-dark); border-color: var(--accent-dark); color: var(--brand-deep); text-decoration: none; transform: translateY(-1px); }
.btn-white    { background: var(--white); color: var(--brand-deep); border-color: var(--border); box-shadow: var(--shadow); }
.btn-white:hover    { background: var(--bg); text-decoration: none; border-color: #cbd5e1; }
.btn-sm       { padding: 8px 16px; font-size: .8rem; }
.btn-lg       { padding: 15px 36px; font-size: 1rem; }

/* Breadcrumb */
.pg-bc{position:relative;z-index:1;display:flex;gap:7px;align-items:center;justify-content:flex-start;flex-wrap:wrap;font-family:'Inter',sans-serif;font-size:.76rem;color:rgba(255,255,255,.6);margin-bottom:12px}
.pg-bc a{color:rgba(255,255,255,.6);text-decoration:none}
.pg-bc a:hover{color:#fff}
.pg-bc svg{width:12px;height:12px;opacity:.5}
.pg-bc [aria-current]{color:rgba(255,255,255,.9);font-weight:600}

/* ===== EMPLOYER LANDING ===== */
.emp-hero{background:radial-gradient(circle at 80% 20%,rgba(242,157,56,.12) 0%,transparent 45%),radial-gradient(circle at 20% 80%,rgba(11,92,156,.25) 0%,transparent 50%),linear-gradient(145deg,#051E36 0%,#0A2F57 60%,#064A85 100%);color:#fff;position:relative;overflow:hidden;padding-top:30px}
.emp-hero .gridbg{position:absolute;inset:0;opacity:.25;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:48px 48px;-webkit-mask-image:radial-gradient(circle at 60% 30%,#000 20%,transparent 70%);mask-image:radial-gradient(circle at 60% 30%,#000 20%,transparent 70%)}
.emp-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1.05fr .95fr;gap:60px;align-items:center;padding:70px 0 80px}
.emp-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:30px;padding:6px 16px;color:rgba(255,255,255,.9);margin-bottom:24px;backdrop-filter:blur(4px)}
.emp-eyebrow svg{width:14px;height:14px;color:var(--accent)}
.emp-hero h1{font-family:'Sora',sans-serif;font-size:clamp(2.4rem,4.8vw,3.75rem);font-weight:800;line-height:1.1;letter-spacing:-.025em;margin-bottom:20px}
.emp-hero h1 em{font-style:normal;color:var(--accent);background:linear-gradient(to right,var(--accent),#FFF);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.emp-hero .lede{font-size:1.1rem;color:rgba(255,255,255,.8);line-height:1.65;max-width:500px;margin-bottom:30px}
.emp-usps{display:flex;flex-direction:column;gap:16px;margin-bottom:36px}
.emp-usp{display:flex;align-items:flex-start;gap:14px}
.emp-usp-ic{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1)}
.emp-usp-ic svg{width:20px;height:20px;color:var(--accent)}
.emp-usp-tx strong{display:block;font-size:.95rem;font-weight:700;color:#fff;margin-bottom:2px}
.emp-usp-tx span{font-size:.85rem;color:rgba(255,255,255,.65);line-height:1.5}
.emp-hero-actions{display:flex;gap:16px;flex-wrap:wrap;align-items:center}
.emp-trust-line{display:flex;align-items:center;gap:12px;margin-top:32px;font-size:.85rem;color:rgba(255,255,255,.65)}
.emp-trust-avatars{display:flex}
.emp-trust-avatars span{width:32px;height:32px;border-radius:50%;border:2px solid #0A2F57;margin-left:-10px;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#fff;font-family:'Sora',sans-serif}
.emp-trust-avatars span:first-child{margin-left:0}

/* hero comparison card */
.emp-hero-visual{position:relative}
.compare-card{background:#fff;border-radius:20px;box-shadow:0 30px 80px rgba(5,30,54,.45);overflow:hidden;border:1px solid rgba(255,255,255,0.1)}
.compare-row{display:flex;align-items:flex-start;gap:16px;padding:24px 26px;transition:var(--transition)}
.compare-row+.compare-row{border-top:1px solid var(--border)}
.compare-bad{background:#fff}
.compare-good{background:linear-gradient(135deg,#F0F7FD,#fff)}
.cmp-ic{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.cmp-ic.bad{background:#FEE2E2;color:#EF4444}
.cmp-ic.good{background:#D1FAE5;color:#10B981}
.cmp-ic svg{width:22px;height:22px}
.cmp-tx h4{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;color:var(--brand-deep);margin-bottom:4px}
.cmp-tx p{font-size:.88rem;color:var(--muted);line-height:1.5}
.compare-good .cmp-tx h4{color:var(--brand)}
.cmp-tag{position:absolute;top:-12px;right:24px;background:var(--accent);color:var(--brand-deep);font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:6px 14px;border-radius:30px;box-shadow:0 4px 14px rgba(242,157,56,.3)}

/* hero stat strip */
.emp-statstrip{position:relative;z-index:1;display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:rgba(255,255,255,.1);border-top:1px solid rgba(255,255,255,.12);border-radius:12px 12px 0 0;overflow:hidden;margin-top:20px}
.emp-stat{background:#062E52;padding:24px 20px;text-align:center}
.emp-stat-n{font-family:'Sora',sans-serif;font-size:1.85rem;font-weight:800;color:#fff;line-height:1}
.emp-stat-n span{color:var(--accent)}
.emp-stat-l{font-size:.78rem;color:rgba(255,255,255,.55);margin-top:6px;font-weight:500}

/* sections */
.sec{padding:80px 0}
.sec.tint{background:var(--bg)}
.sec.white{background:#fff}
.sec-head{text-align:center;max-width:680px;margin:0 auto 54px}
.sec-head .section-title{font-size:clamp(1.75rem,3.2vw,2.4rem);font-weight:800;line-height:1.15;margin-bottom:14px;color:var(--brand-deep)}
.sec-head p{color:var(--muted);font-size:1.02rem;line-height:1.6}

/* feature grid */
.feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.feat-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:32px 28px;transition:var(--transition);position:relative;overflow:hidden;box-shadow:var(--shadow)}
.feat-card:hover{border-color:var(--brand);box-shadow:var(--shadow-lg);transform:translateY(-5px)}
.feat-ic{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px}
.feat-ic svg{width:26px;height:26px}
.fi-1{background:#F0F7FD;color:var(--brand)}.fi-2{background:#FEF3C7;color:#D97706}.fi-3{background:#E0F2FE;color:#0284C7}
.fi-4{background:#D1FAE5;color:#10B981}.fi-5{background:#FEE2E2;color:#EF4444}.fi-6{background:#F3E8FF;color:#8B5CF6}
.fi-7{background:#E0F2FE;color:var(--brand)}.fi-8{background:#FCE7F3;color:#DB2777}.fi-9{background:#D1FAE5;color:#10B981}
.feat-card h3{font-family:'Sora',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:10px;color:var(--brand-deep)}
.feat-card p{font-size:.9rem;color:var(--muted);line-height:1.65}

/* collaborator highlight band */
.collab{background:linear-gradient(135deg,var(--accent),#FDBA74);border-radius:20px;padding:48px;text-align:center;color:var(--brand-deep);position:relative;overflow:hidden;box-shadow:var(--shadow-lg)}
.collab::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 80% 20%,rgba(255,255,255,.3),transparent 50%);pointer-events:none}
.collab-ic{width:64px;height:64px;border-radius:16px;background:rgba(6,46,82,.1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;position:relative}
.collab-ic svg{width:32px;height:32px;color:var(--brand-deep)}
.collab h2{font-family:'Sora',sans-serif;font-size:1.85rem;font-weight:800;margin-bottom:12px;position:relative}
.collab p{font-size:1.02rem;line-height:1.65;max-width:650px;margin:0 auto;color:rgba(6,46,82,.85);position:relative}

/* social proof split */
.proof-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
.proof-left .section-label{margin-bottom:16px}
.proof-left h2{font-family:'Sora',sans-serif;font-size:clamp(1.6rem,2.8vw,2.25rem);font-weight:800;line-height:1.18;margin-bottom:16px;color:var(--brand-deep)}
.proof-left>p{color:var(--muted);font-size:1rem;line-height:1.65;margin-bottom:28px}
.quote-card{background:linear-gradient(135deg,#062E52,var(--brand));color:#fff;border-radius:20px;padding:36px;box-shadow:var(--shadow-lg);position:relative}
.quote-mark{font-family:'Sora',sans-serif;font-size:3rem;font-weight:800;color:var(--accent);line-height:.4;margin-bottom:12px}
.quote-card p{font-size:1.05rem;line-height:1.65;margin-bottom:24px;font-style:italic}
.quote-author{display:flex;align-items:center;gap:14px}
.quote-av{width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0}
.quote-author strong{display:block;font-size:.95rem;font-weight:700;margin-bottom:2px}
.quote-author span{font-size:.8rem;opacity:.75}
.proof-card{background:#fff;border:1px solid var(--border);border-radius:20px;padding:40px;box-shadow:var(--shadow-lg)}
.proof-card h3{font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:800;color:var(--brand-deep);margin-bottom:24px;text-align:center}
.logo-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:32px}
.logo-box{background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:20px 14px;text-align:center;font-family:'Sora',sans-serif;font-weight:700;color:var(--brand);font-size:.95rem;transition:var(--transition)}
.logo-box:hover{border-color:var(--brand);background:#fff;box-shadow:var(--shadow)}
.proof-stats{display:flex;gap:1px;background:var(--border);border-radius:12px;overflow:hidden}
.proof-stats .proof-stat{flex:1;background:var(--bg);padding:18px;text-align:center}
.proof-stats .proof-stat-n{font-family:'Sora',sans-serif;font-size:1.55rem;font-weight:800;color:var(--brand-deep);line-height:1}
.proof-stats .proof-stat-l{font-size:.8rem;color:var(--muted);margin-top:6px}

/* steps how it works */
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.step-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:36px 28px;text-align:center;position:relative;box-shadow:var(--shadow)}
.step-num{width:48px;height:48px;border-radius:50%;background:var(--brand-light);color:var(--brand);font-family:'Sora',sans-serif;font-weight:800;font-size:1.15rem;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;border:1px solid rgba(11,92,156,0.1)}
.step-card h3{font-family:'Sora',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:10px;color:var(--brand-deep)}
.step-card p{font-size:.9rem;color:var(--muted);line-height:1.65}
.steps-cta{text-align:center;margin-top:44px}
.steps-cta p{margin-top:16px;font-size:.9rem;color:var(--muted)}
.steps-cta a{font-weight:700}
.final-cta{background:radial-gradient(circle at 50% 0%,rgba(242,157,56,.15),transparent 65%),linear-gradient(150deg,#051E36,#0A2F57);color:#fff;border-radius:24px;padding:64px 40px;text-align:center;position:relative;overflow:hidden;box-shadow:var(--shadow-lg)}
.final-cta h2{font-family:'Sora',sans-serif;font-size:clamp(1.85rem,3.2vw,2.6rem);font-weight:800;margin-bottom:14px;position:relative}
.final-cta p{font-size:1.05rem;color:rgba(255,255,255,.78);margin-bottom:32px;max-width:540px;margin-left:auto;margin-right:auto;position:relative}
.final-cta-actions{display:flex;gap:16px;justify-content:center;flex-wrap:wrap;position:relative}

/* ══ CANDIDATE REACH ══ */
.reach{background:#fff;border:1px solid var(--border);border-radius:20px;padding:48px;text-align:center;box-shadow:var(--shadow-lg)}
.reach-n{font-family:'Sora',sans-serif;font-size:clamp(2.6rem,5.5vw,3.75rem);font-weight:800;color:var(--brand);line-height:1}
.reach-n span{color:var(--accent)}
.reach-sub{font-size:1.02rem;color:var(--muted);margin:10px 0 30px;max-width:520px;margin-left:auto;margin-right:auto;line-height:1.6}
.reach-chips{display:flex;flex-wrap:wrap;gap:12px;justify-content:center}
.reach-chip{display:inline-flex;align-items:center;gap:10px;background:var(--bg);border:1px solid var(--border);border-radius:30px;padding:10px 20px;font-size:.9rem;font-weight:600;color:var(--brand-deep);transition:var(--transition)}
.reach-chip:hover{border-color:var(--brand);color:var(--brand);background:#fff;transform:translateY(-2px);box-shadow:var(--shadow)}
.reach-chip svg{width:16px;height:16px;color:var(--brand)}

/* ══ PRICING PREVIEW ══ */
.price-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;align-items:stretch}
.price-card{background:#fff;border:1px solid var(--border);border-radius:20px;padding:36px 30px;display:flex;flex-direction:column;transition:var(--transition);position:relative}
.price-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-5px)}
.price-card.featured{border:2px solid var(--brand);box-shadow:0 20px 50px rgba(11,92,156,.16)}
.price-flag{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:var(--accent);color:var(--brand-deep);font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:6px 16px;border-radius:30px;white-space:nowrap}
.price-name{font-family:'Sora',sans-serif;font-size:1.15rem;font-weight:700;color:var(--brand-deep);margin-bottom:6px}
.price-tagline{font-size:.85rem;color:var(--muted);margin-bottom:20px;min-height:2.4em;line-height:1.4}
.price-amt{font-family:'Sora',sans-serif;font-weight:800;color:var(--brand-deep);line-height:1;margin-bottom:6px}
.price-amt .cur{font-size:1.25rem;vertical-align:.6em;margin-right:1px}
.price-amt .num{font-size:2.5rem}
.price-amt .per{font-size:.88rem;color:var(--muted);font-weight:500;font-family:'Inter',sans-serif}
.price-amt.free .num{color:var(--success)}
.price-divider{height:1px;background:var(--border);margin:20px 0}
.price-feats{list-style:none;display:flex;flex-direction:column;gap:12px;margin-bottom:28px;flex:1}
.price-feats li{display:flex;align-items:flex-start;gap:10px;font-size:.9rem;color:var(--text);line-height:1.5}
.price-feats svg{width:18px;height:18px;color:var(--success);flex-shrink:0;margin-top:2px}
.price-feats li.muted{color:var(--muted)}
.price-feats li.muted svg{color:var(--border)}
.price-note{text-align:center;margin-top:32px;font-size:.9rem;color:var(--muted)}

/* ══ DEMO / LEAD CAPTURE ══ */
.demo-band{display:grid;grid-template-columns:1.1fr .9fr;gap:0;border-radius:20px;overflow:hidden;border:1px solid var(--border);background:#fff;box-shadow:var(--shadow-lg)}
.demo-left{background:linear-gradient(135deg,#062E52,var(--brand));color:#fff;padding:54px 48px;position:relative}
.demo-left .section-label{background:rgba(255,255,255,.12);color:#fff;margin-bottom:16px;border-color:rgba(255,255,255,.1)}
.demo-left h2{font-family:'Sora',sans-serif;font-size:1.75rem;font-weight:800;line-height:1.2;margin-bottom:14px}
.demo-left p{font-size:.98rem;opacity:.85;line-height:1.65;margin-bottom:26px}
.demo-perks{list-style:none;display:flex;flex-direction:column;gap:14px}
.demo-perks li{display:flex;align-items:center;gap:12px;font-size:.92rem}
.demo-perks svg{width:18px;height:18px;color:var(--accent);flex-shrink:0}
.demo-right{padding:48px;display:flex;flex-direction:column;justify-content:center}
.demo-form{display:flex;flex-direction:column;gap:16px}
.demo-field label{display:block;font-size:.8rem;font-weight:600;color:var(--brand-deep);margin-bottom:6px}
.demo-field input,.demo-field select{width:100%;border:1.5px solid var(--border);border-radius:10px;padding:12px 15px;font-family:'Inter',sans-serif;font-size:.9rem;color:var(--text);background:#fff;outline:none;min-height:46px;transition:var(--transition)}
.demo-field input:focus,.demo-field select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(11,92,156,0.1)}
.demo-form .btn{justify-content:center;min-height:48px;margin-top:6px}
.demo-formnote{font-size:.78rem;color:var(--muted);text-align:center;margin-top:2px}

/* ══ STICKY MOBILE CTA ══ */
.sticky-cta{display:none}
@media(max-width:780px){
  .sticky-cta{display:flex;position:fixed;left:0;right:0;bottom:0;z-index:950;gap:12px;padding:14px 18px calc(14px + env(safe-area-inset-bottom,0px));background:rgba(255,255,255,.96);backdrop-filter:blur(10px);border-top:1px solid var(--border);box-shadow:0 -5px 25px rgba(6,46,82,.08)}
  .sticky-cta .btn{flex:1;justify-content:center;min-height:48px}
  body{padding-bottom:76px}
}

@media(max-width:900px){.price-grid{grid-template-columns:1fr}.demo-band{grid-template-columns:1fr}.price-card.featured{order:-1}}
@media(max-width:580px){.reach,.demo-left,.demo-right{padding:30px 24px}}

/* ===== JOB AD PRICING ===== */
.pr-hero{background:radial-gradient(circle at 80% 20%,rgba(242,157,56,.12) 0%,transparent 45%),radial-gradient(circle at 20% 80%,rgba(11,92,156,.25) 0%,transparent 50%),linear-gradient(145deg,#051E36 0%,#0A2F57 60%,#064A85 100%);color:#fff;position:relative;overflow:hidden}
.pr-hero .gridbg{position:absolute;inset:0;opacity:.25;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:48px 48px;-webkit-mask-image:radial-gradient(circle at 50% 20%,#000 20%,transparent 75%);mask-image:radial-gradient(circle at 50% 20%,#000 20%,transparent 75%)}
.pr-hero-inner{position:relative;z-index:1;text-align:center;max-width:700px;margin:0 auto;padding:70px 0 60px}
.pr-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:30px;padding:6px 16px;color:rgba(255,255,255,.9);margin-bottom:24px}
.pr-eyebrow svg{width:14px;height:14px;color:var(--accent)}
.pr-hero h1{font-family:'Sora',sans-serif;font-size:clamp(2.25rem,4.6vw,3.4rem);font-weight:800;line-height:1.1;letter-spacing:-.025em;margin-bottom:18px}
.pr-hero h1 span{color:var(--accent)}
.pr-hero p{font-size:1.1rem;color:rgba(255,255,255,.8);line-height:1.6;max-width:540px;margin:0 auto}

/* billing toggle */
.bill-toggle{display:inline-flex;align-items:center;gap:0;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:30px;padding:4px;margin-top:28px}
.bill-opt{padding:10px 22px;border-radius:24px;border:none;background:transparent;color:rgba(255,255,255,.7);font-family:'Inter',sans-serif;font-size:.86rem;font-weight:600;cursor:pointer;transition:var(--transition)}
.bill-opt.active{background:#fff;color:var(--brand-deep);box-shadow:var(--shadow)}
.bill-save{font-size:.68rem;font-weight:700;color:var(--brand-deep);background:var(--accent);border-radius:12px;padding:2px 8px;margin-left:6px}

/* pricing cards */
.pr-main{position:relative;z-index:5;margin-top:-40px;padding-bottom:80px}
.pr-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;align-items:stretch}
.pcard{background:#fff;border:1px solid var(--border);border-radius:20px;padding:32px 24px;display:flex;flex-direction:column;box-shadow:var(--shadow);transition:var(--transition);position:relative}
.pcard:hover{box-shadow:var(--shadow-lg);transform:translateY(-5px);border-color:#cbd5e1}
.pcard.featured{border:2px solid var(--brand);box-shadow:0 20px 50px rgba(11,92,156,.18)}
.pflag{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:var(--accent);color:var(--brand-deep);font-size:.68rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;padding:6px 15px;border-radius:30px;white-space:nowrap;box-shadow:0 4px 14px rgba(242,157,56,0.3)}
.pname{font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:800;color:var(--brand-deep);margin-bottom:6px}
.ptag{font-size:.82rem;color:var(--muted);margin-bottom:20px;min-height:2.6em;line-height:1.45}
.pamt{font-family:'Sora',sans-serif;font-weight:800;color:var(--brand-deep);line-height:1;margin-bottom:8px;display:flex;align-items:baseline;gap:3px;flex-wrap:wrap}
.pamt .cur{font-size:1.25rem}
.pamt .num{font-size:2.5rem}
.pamt.free .num{color:var(--success)}
.pamt .per{font-size:.85rem;color:var(--muted);font-weight:500;font-family:'Inter',sans-serif}
.psub{font-size:.8rem;color:var(--muted);margin-bottom:24px}
.pcard .btn{width:100%;justify-content:center;margin-bottom:24px}
.pfeats{list-style:none;display:flex;flex-direction:column;gap:12px;margin-top:auto}
.pfeats-h{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:4px}
.pfeats li{display:flex;align-items:flex-start;gap:10px;font-size:.85rem;color:var(--text);line-height:1.45}
.pfeats svg{width:16px;height:16px;color:var(--success);flex-shrink:0;margin-top:2px}
.pfeats li.off{color:var(--muted)}
.pfeats li.off svg{color:var(--border)}

/* comparison table */
.cmp-sec{padding:80px 0;background:var(--bg)}
.cmp-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:16px;background:#fff;box-shadow:var(--shadow)}
.cmp-table{width:100%;border-collapse:collapse;min-width:760px}
.cmp-table th,.cmp-table td{padding:16px 20px;text-align:center;border-bottom:1px solid var(--border)}
.cmp-table thead th{background:var(--brand-deep);color:#fff;font-family:'Sora',sans-serif;font-size:.9rem;font-weight:700;position:sticky;top:0;border:none}
.cmp-table thead th.feat-col{text-align:left}
.cmp-table thead th.hl{background:var(--brand)}
.cmp-table td.feat-col{text-align:left;font-weight:600;color:var(--brand-deep);font-size:.9rem}
.cmp-table td{font-size:.88rem;color:var(--text)}
.cmp-table tbody tr:hover{background:var(--bg)}
.cmp-table .yes{color:var(--success)}.cmp-table .no{color:#cbd5e1}
.cmp-table .yes svg,.cmp-table .no svg{width:18px;height:18px}
.cmp-cat td{background:var(--brand-light)!important;font-family:'Sora',sans-serif;font-weight:700;color:var(--brand-deep);text-align:left;font-size:.82rem;text-transform:uppercase;letter-spacing:.06em;border-top:1px solid var(--border)}

/* add-ons */
.addon-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.addon{background:#fff;border:1px solid var(--border);border-radius:16px;padding:30px 26px;transition:var(--transition);box-shadow:var(--shadow)}
.addon:hover{border-color:var(--brand);box-shadow:var(--shadow-lg);transform:translateY(-4px)}
.addon-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
.addon-ic{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center}
.addon-ic svg{width:24px;height:24px}
.addon-price{font-family:'Sora',sans-serif;font-weight:800;font-size:1.15rem;color:var(--brand-deep)}
.addon-price span{font-size:.8rem;color:var(--muted);font-weight:500}
.addon h3{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:8px;color:var(--brand-deep)}
.addon p{font-size:.88rem;color:var(--muted);line-height:1.6}
.ai-1{background:#FEF3C7;color:#D97706}.ai-2{background:#FEE2E2;color:#EF4444}.ai-3{background:#F0F7FD;color:var(--brand)}

/* value props strip */
.vp-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;margin-top:12px}
.vp{display:flex;gap:14px;align-items:flex-start}
.vp-ic{width:44px;height:44px;border-radius:12px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(11,92,156,0.08)}
.vp-ic svg{width:22px;height:22px}
.vp strong{display:block;font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:var(--brand-deep);margin-bottom:3px}
.vp span{font-size:.85rem;color:var(--muted);line-height:1.55}

/* two ways band */
.dual{display:grid;grid-template-columns:1fr 1fr;gap:24px}
.dual-card{border-radius:20px;padding:40px 36px;border:1px solid var(--border);background:#fff;transition:var(--transition);position:relative;overflow:hidden;box-shadow:var(--shadow)}
.dual-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-4px)}
.dual-card h3{font-family:'Sora',sans-serif;font-size:1.25rem;font-weight:800;margin-bottom:10px;color:var(--brand-deep);position:relative}
.dual-card p{font-size:.92rem;color:var(--muted);line-height:1.65;margin-bottom:24px;position:relative}
.dual-card.accent{background:linear-gradient(135deg,#fffbeb,#FEF3C7);border-color:#FDE68A}
.dual-card.blue{background:linear-gradient(135deg,#F0F7FD,#fff);border-color:#CFE0F1}

/* faq */
.faq-wrap{max-width:780px;margin:0 auto}
.faq-item{background:#fff;border:1px solid var(--border);border-radius:14px;margin-bottom:14px;overflow:hidden;transition:var(--transition);box-shadow:var(--shadow)}
.faq-item:hover{border-color:#CFE0F1;box-shadow:var(--shadow-lg)}
.faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:20px 24px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:var(--brand-deep);text-align:left;line-height:1.45}
.faq-q svg{width:20px;height:20px;color:var(--brand);flex-shrink:0;transition:transform .2s}
.faq-item.open .faq-q svg{transform:rotate(45deg)}
.faq-a{max-height:0;overflow:hidden;transition:max-height .26s ease}
.faq-a-in{padding:0 24px 20px;font-size:.9rem;color:var(--muted);line-height:1.75}
.faq-item.open .faq-a{max-height:260px}

/* ===== SUBSCRIPTION SECTION ===== */
.sub-sec{padding:80px 0;background:linear-gradient(180deg,var(--bg),#fff)}
.sub-toggle-wrap{display:flex;justify-content:center;margin-bottom:40px}
.sub-toggle{display:inline-flex;background:#fff;border:1px solid var(--border);border-radius:14px;padding:5px;gap:4px;box-shadow:var(--shadow)}
.sub-tg{padding:11px 24px;border-radius:10px;border:none;background:transparent;color:var(--muted);font-family:'Inter',sans-serif;font-size:.88rem;font-weight:600;cursor:pointer;transition:var(--transition);white-space:nowrap}
.sub-tg.active{background:var(--brand);color:#fff;box-shadow:var(--shadow)}
.sub-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;align-items:stretch}
.subcard{background:#fff;border:1px solid var(--border);border-radius:20px;padding:30px 24px;display:flex;flex-direction:column;transition:var(--transition);position:relative}
.subcard:hover{box-shadow:var(--shadow-lg);transform:translateY(-5px);border-color:#cbd5e1}
.subcard.best{border:2px solid var(--accent);box-shadow:0 20px 50px rgba(242,157,56,.18)}
.subflag{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:var(--accent);color:var(--brand-deep);font-size:.66rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:6px 15px;border-radius:30px;white-space:nowrap;box-shadow:0 4px 14px rgba(242,157,56,0.3)}
.subterm{font-family:'Sora',sans-serif;font-size:1.1rem;font-weight:800;color:var(--brand-deep);margin-bottom:4px}
.subterm-sub{font-size:.8rem;color:var(--muted);margin-bottom:20px}
.subprice{font-family:'Sora',sans-serif;font-weight:800;color:var(--brand-deep);line-height:1;display:flex;align-items:baseline;gap:2px;margin-bottom:6px}
.subprice .cur{font-size:1.25rem}.subprice .num{font-size:2.3rem}.subprice .per{font-size:.82rem;color:var(--muted);font-weight:500;font-family:'Inter',sans-serif}
.sub-permo{font-size:.82rem;color:var(--muted);margin-bottom:10px}
.sub-permo b{color:var(--text)}
.sub-save{display:inline-block;font-size:.72rem;font-weight:700;color:#047857;background:#D1FAE5;border-radius:30px;padding:4px 12px;margin-bottom:20px;align-self:flex-start}
.sub-save.none{visibility:hidden}
.subcard .btn{width:100%;justify-content:center;margin-top:auto}
.sub-billed{font-size:.76rem;color:var(--muted);text-align:center;margin-top:12px}
.sub-includes{background:#fff;border:1px solid var(--border);border-radius:20px;padding:30px 34px;margin-top:36px;box-shadow:var(--shadow)}
.sub-includes h3{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;color:var(--brand-deep);margin-bottom:18px;display:flex;align-items:center;gap:10px}
.sub-includes h3 svg{width:20px;height:20px;color:var(--accent)}
.sub-inc-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px 30px}
.sub-inc-grid li{display:flex;align-items:flex-start;gap:10px;font-size:.9rem;color:var(--text);line-height:1.5;list-style:none}
.sub-inc-grid svg{width:18px;height:18px;color:var(--success);flex-shrink:0;margin-top:2px}
.sub-fairuse{display:flex;gap:12px;align-items:flex-start;background:var(--brand-light);border-radius:12px;padding:16px 20px;margin-top:24px;font-size:.88rem;color:var(--brand-dark);line-height:1.6;border:1px solid rgba(11,92,156,0.08)}
.sub-fairuse svg{width:18px;height:18px;color:var(--brand);flex-shrink:0;margin-top:2px}

@media(max-width:980px){
  .pr-grid{grid-template-columns:repeat(2,1fr)}
  .vp-strip{grid-template-columns:repeat(2,1fr)}
  .sub-grid{grid-template-columns:repeat(2,1fr)}
  .emp-hero-inner{grid-template-columns:1fr;gap:40px;padding:50px 0 60px}
  .emp-hero-visual{max-width:480px}
  .feat-grid,.steps-grid{grid-template-columns:repeat(2,1fr)}
  .proof-grid,.dual{grid-template-columns:1fr}
  .emp-statstrip{grid-template-columns:repeat(2,1fr);border-radius:0}
  .emp-stat{border-top:1px solid rgba(255,255,255,.1)}
}
@media(max-width:880px){
  .addon-grid{grid-template-columns:1fr}
  .sub-inc-grid{grid-template-columns:1fr}
}
@media(max-width:580px){
  .sec{padding:54px 0}
  .feat-grid,.steps-grid,.logo-grid{grid-template-columns:1fr}
  .logo-grid{grid-template-columns:repeat(3,1fr)}
  .emp-statstrip{grid-template-columns:1fr}
  .collab,.final-cta{padding:36px 24px}
  .proof-card{padding:28px}
  .emp-hero-actions .btn{width:100%;justify-content:center}
  .reach,.demo-left,.demo-right{padding:30px 24px}
  .sub-toggle{width:100%;display:grid;grid-template-columns:1fr 1fr;gap:4px}
  .sub-tg{flex:none;padding:11px 8px;font-size:.82rem}
  .pr-main{margin-top:-15px}
  .dual-card{padding:32px 24px}
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFaq(btn){
  var i=btn.parentElement;
  var o=i.classList.toggle('open');
  btn.setAttribute('aria-expanded',String(o));
}

function setTerm(term,btn){
  document.querySelectorAll('.sub-tg').forEach(function(t){
    t.classList.remove('active');
    t.setAttribute('aria-selected','false');
  });
  btn.classList.add('active');
  btn.setAttribute('aria-selected','true');
  
  document.querySelectorAll('.subcard').forEach(function(c){
    c.classList.toggle('best', c.dataset.term === term);
  });
  
  var card = document.querySelector('.subcard[data-term="'+term+'"]');
  if(card && window.innerWidth <= 580){
    card.scrollIntoView({behavior:'smooth',block:'center'});
  }
}

function submitDemo(e){
  e.preventDefault();
  var b=e.target.querySelector('button[type="submit"]');
  b.textContent='Request sent \u2713';
  b.style.background='var(--success)';
  b.style.borderColor='var(--success)';
  b.disabled=true;
  return false;
}
</script>
<?= $this->endSection() ?>