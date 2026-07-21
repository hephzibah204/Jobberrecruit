<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
:root {
  --brand: #0D609E;
  --accent: #F08F1A;
  --brand-deep: #07304F;
  --brand-dark: #0A4D7E;
  --brand-light: #E6F0F9;
}

.cvr-hero {
  background: radial-gradient(1200px 600px at 80% -10%, rgba(240,143,26,.18) 0%, transparent 55%),
              radial-gradient(900px 500px at -10% 110%, rgba(13,96,158,.35) 0%, transparent 55%),
              linear-gradient(155deg, var(--brand-deep) 0%, var(--brand-deep) 40%, var(--brand-dark) 100%);
  color: #fff;
  padding: 64px 0 72px;
  position: relative;
  overflow: hidden;
}

.cvr-hero-grid {
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .45;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 44px 44px;
  -webkit-mask-image: radial-gradient(circle at 70% 30%, #000, transparent 75%);
  mask-image: radial-gradient(circle at 70% 30%, #000, transparent 75%);
}

.cvr-hero-inner {
  position: relative;
  z-index: 1;
  display: grid;
  grid-template-columns: 1.1fr .9fr;
  gap: 48px;
  align-items: center;
}

.cvr-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: .74rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 20px;
  padding: 7px 15px;
  color: rgba(255,255,255,.92);
  margin-bottom: 20px;
}

.cvr-eyebrow .ti {
  font-size: .85rem;
  color: var(--accent);
}

.cvr-hero h1 {
  font-size: 2.5rem;
  font-weight: 800;
  line-height: 1.12;
  letter-spacing: -.02em;
  margin-bottom: 16px;
}

.cvr-hero h1 span { color: var(--accent); }

.cvr-hero-lede {
  font-size: 1.05rem;
  color: rgba(255,255,255,.85);
  line-height: 1.6;
  margin-bottom: 28px;
  max-width: 520px;
}

.cvr-hero-cta { display: flex; gap: 12px; flex-wrap: wrap; }
.cvr-hero-cta .btn-lg { padding: 14px 26px; font-size: .96rem; }

.btn-accent {
  background: var(--accent);
  color: var(--brand-deep);
  border: none;
}
.btn-accent:hover {
  background: #C8770E;
  color: var(--brand-deep);
}
.btn-ghost-light {
  background: rgba(255,255,255,.1);
  color: #fff;
  border: 1.5px solid rgba(255,255,255,.3);
}
.btn-ghost-light:hover {
  background: rgba(255,255,255,.18);
  color: #fff;
}

.cvr-stats {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.16);
  border-radius: 18px;
  padding: 30px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  backdrop-filter: blur(6px);
}

.cvr-stat .n {
  font-size: 2rem;
  font-weight: 800;
  color: #fff;
  line-height: 1;
}

.cvr-stat .n span { color: var(--accent); }
.cvr-stat .l { font-size: .82rem; color: rgba(255,255,255,.7); margin-top: 5px; }

.cvr-sec { padding: 64px 0; }
.cvr-sec.tint { background: var(--brand-light); }
.cvr-sec.white { background: #fff; }

.cvr-sec-head {
  text-align: center;
  max-width: 660px;
  margin: 0 auto 44px;
}

.cvr-label {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--brand);
  background: var(--brand-light);
  padding: 5px 13px;
  border-radius: 20px;
  margin-bottom: 14px;
}

.cvr-label .ti { font-size: .75rem; }
.cvr-sec.tint .cvr-label { background: #fff; }

.cvr-sec-head h2 {
  font-size: 1.9rem;
  font-weight: 800;
  color: var(--brand-deep);
  letter-spacing: -.02em;
  margin-bottom: 10px;
}

.cvr-sec-head h2 span { color: var(--brand); }
.cvr-sec-head p { font-size: 1rem; color: #5b6577; line-height: 1.55; }

.cvr-steps {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.cvr-step {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 14px;
  padding: 26px 22px;
  position: relative;
  transition: .18s ease;
}

.cvr-step:hover {
  transform: translateY(-3px);
  box-shadow: 0 14px 40px rgba(10,47,87,.16);
}

.cvr-step-n {
  position: absolute;
  top: -14px;
  left: 22px;
  width: 30px;
  height: 30px;
  border-radius: 9px;
  background: var(--brand);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: .9rem;
}

.cvr-step-ic {
  width: 44px;
  height: 44px;
  border-radius: 11px;
  background: var(--brand-light);
  color: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 8px 0 14px;
}

.cvr-step-ic .ti { font-size: 1.3rem; }
.cvr-step h3 { font-size: 1rem; font-weight: 700; color: #141926; margin-bottom: 7px; }
.cvr-step p { font-size: .86rem; color: #5b6577; line-height: 1.55; }

.cvr-pricing {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  align-items: stretch;
  max-width: 1000px;
  margin: 0 auto;
}

.price-card {
  background: #fff;
  border: 1.5px solid #e2e8f2;
  border-radius: 16px;
  padding: 30px 26px;
  display: flex;
  flex-direction: column;
  position: relative;
  transition: .18s ease;
}

.price-card:hover {
  box-shadow: 0 14px 40px rgba(10,47,87,.16);
  transform: translateY(-3px);
}

.price-card.featured {
  border: 2px solid var(--brand);
  box-shadow: 0 10px 36px rgba(13,96,158,.16);
}

.price-badge {
  position: absolute;
  top: -13px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--accent);
  color: #fff;
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .04em;
  text-transform: uppercase;
  padding: 5px 15px;
  border-radius: 20px;
  white-space: nowrap;
}

.price-name { font-size: 1.1rem; font-weight: 700; color: var(--brand-deep); }
.price-tag-line { font-size: .82rem; color: #5b6577; margin: 4px 0 18px; }

.price-amount {
  display: flex;
  align-items: baseline;
  gap: 4px;
  margin-bottom: 4px;
}

.price-amount .cur { font-size: 1.2rem; font-weight: 700; color: #141926; }
.price-amount .val { font-size: 2.4rem; font-weight: 800; color: #141926; line-height: 1; }
.price-amount .per { font-size: .82rem; color: #5b6577; }
.price-sub { font-size: .78rem; color: #5b6577; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f2; }

.price-feats {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 11px;
  margin-bottom: 24px;
  flex: 1;
  padding: 0;
}

.price-feats li {
  display: flex;
  align-items: flex-start;
  gap: 9px;
  font-size: .86rem;
  color: #141926;
  line-height: 1.45;
}

.price-feats .ti { font-size: 1rem; color: #16a34a; flex-shrink: 0; margin-top: 2px; }
.price-feats li.off { color: #9aa6b6; }
.price-feats li.off .ti { color: #cbd5e1; }
.price-card .btn { width: 100%; justify-content: center; padding: 13px; }

.cvr-proof {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: center;
}

.cvr-proof-text h2 {
  font-size: 1.7rem;
  font-weight: 800;
  color: var(--brand-deep);
  margin-bottom: 14px;
  letter-spacing: -.02em;
}

.cvr-proof-text h2 span { color: var(--brand); }
.cvr-proof-text p { font-size: .95rem; color: #5b6577; line-height: 1.6; margin-bottom: 18px; }

.cvr-proof-list {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 12px;
  padding: 0;
}

.cvr-proof-list li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: .9rem;
  color: #141926;
}

.cvr-proof-list .ti { font-size: 1.1rem; color: #16a34a; flex-shrink: 0; margin-top: 2px; }

.cvr-report-img {
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #e2e8f2;
  box-shadow: 0 14px 40px rgba(10,47,87,.16);
  background: #fff;
  position: relative;
  aspect-ratio: 4/3;
}

.cvr-report-img img { width: 100%; height: 100%; object-fit: cover; }

.cvr-report-ph {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f0f6fc, #e6f0f8);
  color: var(--brand);
  gap: 10px;
}

.cvr-report-ph .ti { font-size: 3rem; opacity: .5; }
.cvr-report-ph span { font-size: .82rem; color: #5b6577; font-weight: 600; }

.cvr-review-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.cvr-rev-item {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 13px;
  padding: 24px;
}

.cvr-rev-ic {
  width: 42px;
  height: 42px;
  border-radius: 11px;
  background: var(--brand-light);
  color: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}

.cvr-rev-ic .ti { font-size: 1.2rem; }
.cvr-rev-item h3 { font-size: .96rem; font-weight: 700; color: #141926; margin-bottom: 7px; }
.cvr-rev-item p { font-size: .85rem; color: #5b6577; line-height: 1.55; }

.expert-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 22px;
}

.expert-card {
  text-align: center;
  padding: 32px 20px;
  border-radius: 14px;
  background: #fff;
  border: 1px solid #e2e8f2;
  transition: .18s ease;
}

.expert-card:hover {
  box-shadow: 0 14px 40px rgba(10,47,87,.16);
  transform: translateY(-3px);
}

.expert-avatar {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--brand-light), #e0e7ff);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 14px;
  font-size: 1.5rem;
  color: var(--brand);
  font-weight: 700;
}

.expert-card h5 { font-weight: 600; margin-bottom: 4px; font-size: .96rem; }
.expert-card .role { color: var(--brand); font-size: .82rem; font-weight: 500; margin-bottom: 10px; }
.expert-card p { color: #5b6577; font-size: .85rem; margin: 0; line-height: 1.55; }

.cvr-tm-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
}

.cvr-tm {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 14px;
  padding: 26px;
  display: flex;
  flex-direction: column;
}

.cvr-tm-stars { display: flex; gap: 2px; margin-bottom: 13px; }
.cvr-tm-stars .ti { font-size: .9rem; color: var(--accent); }
.cvr-tm p { font-size: .88rem; color: #141926; line-height: 1.6; margin-bottom: 18px; flex: 1; }

.cvr-tm-who { display: flex; align-items: center; gap: 11px; }
.cvr-tm-av {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: .85rem;
  color: #fff;
  flex-shrink: 0;
}
.cvr-tm-who strong { display: block; font-size: .85rem; color: #141926; }
.cvr-tm-who span { font-size: .76rem; color: #5b6577; }

.cvr-signin {
  max-width: 560px;
  margin: 0 auto;
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 18px;
  padding: 40px;
  text-align: center;
  box-shadow: 0 2px 14px rgba(10,47,87,.08);
}

.cvr-signin-ic {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  background: var(--brand-light);
  color: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 18px;
}

.cvr-signin-ic .ti { font-size: 1.7rem; }
.cvr-signin h2 { font-size: 1.4rem; font-weight: 800; color: var(--brand-deep); margin-bottom: 8px; }
.cvr-signin p { font-size: .92rem; color: #5b6577; margin-bottom: 8px; line-height: 1.55; }
.cvr-signin-actions { display: flex; gap: 12px; justify-content: center; margin-top: 22px; flex-wrap: wrap; }
.cvr-signin-actions .btn { padding: 12px 28px; }

.upload-card {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 2px 14px rgba(10,47,87,.08);
}

.upload-card-header {
  padding: 24px 28px 0;
}

.upload-card-header h3 {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--brand-deep);
  margin-bottom: 6px;
}

.upload-card-header p { color: #5b6577; font-size: .92rem; margin: 0; }

.upload-card-body { padding: 24px 28px 28px; }

.upload-area {
  border: 2px dashed #cbd5e1;
  border-radius: 14px;
  padding: 48px 24px;
  text-align: center;
  cursor: pointer;
  transition: all .2s;
  background: #f8fafc;
}

.upload-area:hover, .upload-area.dragover {
  border-color: var(--brand);
  background: var(--brand-light);
}

.upload-area .ti-cloud-upload { font-size: 2.5rem; color: var(--brand); margin-bottom: 12px; display: block; }
.upload-area h5 { font-weight: 600; font-size: 1rem; margin-bottom: 6px; }
.upload-area p { color: #5b6577; font-size: .88rem; margin: 0; }
.upload-area .formats { color: #5b6577; font-size: .78rem; margin-top: 6px; }

.file-preview {
  display: none;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 12px;
  padding: 16px 20px;
  margin-top: 16px;
}

.file-preview.show {
  display: flex;
  align-items: center;
  gap: 14px;
}

.file-preview .ti { font-size: 1.5rem; color: #16a34a; }
.file-preview .file-name { font-weight: 600; font-size: .9rem; color: #16a34a; }
.file-preview .file-size { font-size: .78rem; color: #334155; }

.benefits-card {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 18px;
  box-shadow: 0 2px 14px rgba(10,47,87,.08);
}

.benefits-card-body { padding: 24px 28px; }

.benefits-card h5 {
  font-weight: 700;
  font-size: 1.05rem;
  margin-bottom: 16px;
  color: #141926;
}

.benefits-card h5 .ti { color: var(--brand); }

.benefits-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.benefits-list li {
  display: flex;
  gap: 12px;
  margin-bottom: 14px;
}

.benefits-list li:last-child { margin-bottom: 0; }

.benefits-list .ti-circle-check {
  font-size: 1.1rem;
  color: #16a34a;
  flex-shrink: 0;
  margin-top: 2px;
}

.benefits-list strong { display: block; font-size: .88rem; color: #141926; }
.benefits-list p { font-size: .82rem; color: #5b6577; margin: 0; line-height: 1.5; }

.help-card {
  border-radius: 18px;
  overflow: hidden;
}

.help-card-body {
  padding: 28px 24px;
  text-align: center;
  background: linear-gradient(135deg, #0f172a, #1e293b);
}

.help-card-body .ti { font-size: 2rem; color: #60a5fa; margin-bottom: 12px; display: block; }
.help-card-body h5 { color: #fff; font-weight: 700; }
.help-card-body p { color: #94a3b8; font-size: .88rem; margin-bottom: 16px; }

.cvr-faq {
  max-width: 760px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.faq-item {
  background: #fff;
  border: 1px solid #e2e8f2;
  border-radius: 12px;
  overflow: hidden;
}

.faq-q {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  padding: 18px 22px;
  background: none;
  border: none;
  font-size: .95rem;
  font-weight: 600;
  color: #141926;
  cursor: pointer;
  text-align: left;
  transition: background .2s;
}

.faq-q:hover { background: #f8fafc; }
.faq-q .ti { font-size: 1.2rem; color: var(--brand); flex-shrink: 0; transition: transform .2s; }
.faq-q[aria-expanded="true"] .ti { transform: rotate(45deg); }
.faq-a { max-height: 0; overflow: hidden; transition: max-height .25s ease; }
.faq-a-in { padding: 0 22px 20px; font-size: .88rem; color: #5b6577; line-height: 1.6; }

.cvr-final {
  background: radial-gradient(900px 500px at 50% -20%, rgba(240,143,26,.16), transparent 60%),
              linear-gradient(155deg, var(--brand-deep), var(--brand-dark));
  color: #fff;
  padding: 60px 0;
  text-align: center;
  border-radius: 24px;
}

.cvr-final h2 {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 12px;
  letter-spacing: -.02em;
}

.cvr-final p {
  font-size: 1.02rem;
  color: rgba(255,255,255,.85);
  max-width: 560px;
  margin: 0 auto 26px;
  line-height: 1.55;
}

@media (max-width: 960px) {
  .cvr-hero-inner { grid-template-columns: 1fr; gap: 32px; }
  .cvr-steps { grid-template-columns: 1fr 1fr; }
  .cvr-pricing { grid-template-columns: 1fr; max-width: 440px; }
  .price-card.featured { order: -1; }
  .cvr-review-grid { grid-template-columns: 1fr 1fr; }
  .cvr-tm-grid { grid-template-columns: 1fr; }
  .cvr-proof { grid-template-columns: 1fr; gap: 28px; }
  .expert-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
  .cvr-hero h1 { font-size: 1.85rem; }
  .cvr-hero { padding: 44px 0 52px; }
  .cvr-stats { padding: 22px; gap: 18px; }
  .cvr-sec-head h2 { font-size: 1.5rem; }
  .cvr-final h2 { font-size: 1.5rem; }
  .cvr-final { padding: 40px 24px; }
}

@media (max-width: 580px) {
  .cvr-steps { grid-template-columns: 1fr; }
  .cvr-review-grid { grid-template-columns: 1fr; }
  .expert-grid { grid-template-columns: 1fr; }
  .cvr-sec { padding: 48px 0; }
  input, select, textarea { font-size: 16px !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Professional CV Review Service",
  "serviceType": "CV / Resume Review",
  "provider": { "@type": "Organization", "name": "JobberRecruit", "url": "<?= base_url() ?>" },
  "areaServed": { "@type": "Country", "name": "Nigeria" },
  "description": "Professional CV review by certified HR professionals and recruitment experts, including ATS compatibility scanning, structure and formatting checks, keyword analysis, and line-by-line feedback.",
  "aggregateRating": { "@type": "AggregateRating", "ratingValue": "4.9", "reviewCount": "1024", "bestRating": "5" },
  "offers": [
    { "@type": "Offer", "name": "Basic Review", "price": "<?= $planPrices['basic'] ?? '0' ?>", "priceCurrency": "NGN", "description": "AI-powered ATS compatibility and formatting analysis." },
    { "@type": "Offer", "name": "Professional Review", "price": "<?= $planPrices['professional'] ?? '15000' ?>", "priceCurrency": "NGN", "description": "Full human expert analysis with line-by-line feedback and rewritten sections." },
    { "@type": "Offer", "name": "Premium Review", "price": "<?= $planPrices['premium'] ?? '30000' ?>", "priceCurrency": "NGN", "description": "Everything in Professional plus a 1-on-1 consultation call and cover letter." }
  ]
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HERO -->
<section class="cvr-hero" aria-labelledby="cvr-h1">
  <div class="cvr-hero-grid" aria-hidden="true"></div>
  <div class="container">
    <div class="cvr-hero-inner">
      <div>
        <div class="cvr-eyebrow"><i class="ti ti-star"></i> Trusted by 10,000+ Professionals</div>
        <h1 id="cvr-h1">Professional <span>CV Review</span> Service</h1>
        <p class="cvr-hero-lede">Get your CV reviewed by certified HR professionals and recruitment experts. Receive actionable, line-by-line feedback to land more interviews at top companies.</p>
        <div class="cvr-hero-cta">
          <a href="<?= base_url($isLoggedIn ? 'cv-review/submit' : 'login?redirect=cv-review/submit') ?>" class="btn btn-accent btn-lg"><i class="ti ti-upload me-2"></i>Get Your CV Reviewed</a>
          <a href="#how-it-works" class="btn btn-ghost-light btn-lg"><i class="ti ti-info-circle me-2"></i>How It Works</a>
        </div>
      </div>
      <div class="cvr-stats">
        <div class="cvr-stat"><div class="n">10K<span>+</span></div><div class="l">CVs Reviewed</div></div>
        <div class="cvr-stat"><div class="n">4.9<span>/5</span></div><div class="l">Client Rating</div></div>
        <div class="cvr-stat"><div class="n">48<span>hr</span></div><div class="l">Avg. Turnaround</div></div>
        <div class="cvr-stat"><div class="n">78<span>%</span></div><div class="l">Interview Success</div></div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="cvr-sec white" id="how-it-works" aria-labelledby="how-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><i class="ti ti-adjustments-horizontal"></i> How It Works</div>
      <h2 id="how-h">Your CV Review <span>Journey</span></h2>
      <p>Four simple steps to a standout CV that gets you noticed by recruiters and hiring managers.</p>
    </div>
    <div class="cvr-steps">
      <div class="cvr-step">
        <div class="cvr-step-n">1</div>
        <div class="cvr-step-ic"><i class="ti ti-upload"></i></div>
        <h3>Upload Your CV</h3>
        <p>Submit your CV in PDF, DOC, or DOCX format. No sign-up required for a quick review.</p>
      </div>
      <div class="cvr-step">
        <div class="cvr-step-n">2</div>
        <div class="cvr-step-ic"><i class="ti ti-search"></i></div>
        <h3>Expert Analysis</h3>
        <p>A certified reviewer evaluates your CV against industry standards and ATS systems.</p>
      </div>
      <div class="cvr-step">
        <div class="cvr-step-n">3</div>
        <div class="cvr-step-ic"><i class="ti ti-file-text"></i></div>
        <h3>Detailed Report</h3>
        <p>Receive a comprehensive report with actionable recommendations and before/after examples.</p>
      </div>
      <div class="cvr-step">
        <div class="cvr-step-n">4</div>
        <div class="cvr-step-ic"><i class="ti ti-trending-up"></i></div>
        <h3>Land Interviews</h3>
        <p>Apply with confidence using your optimized CV that stands out to recruiters.</p>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="cvr-sec tint" id="pricing" aria-labelledby="price-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><i class="ti ti-coin"></i> Pricing</div>
      <h2 id="price-h">Choose Your <span>Review Package</span></h2>
      <p>Select the level of feedback that matches your career goals and budget. All prices in Nigerian Naira.</p>
    </div>
    <div class="cvr-pricing">
      <div class="price-card">
        <div class="price-name">Basic Review</div>
        <div class="price-tag-line">AI-powered instant feedback</div>
        <div class="price-amount"><span class="cur">&#8358;</span><span class="val"><?= number_format($planPrices['basic'] ?? 0, 0) ?></span><span class="per">/review</span></div>
        <div class="price-sub">Fast automated feedback to fix the essentials.</div>
        <ul class="price-feats">
          <li><i class="ti ti-check"></i> ATS Compatibility Scan</li>
          <li><i class="ti ti-check"></i> Structure &amp; Formatting Check</li>
          <li><i class="ti ti-check"></i> Keyword Analysis</li>
          <li><i class="ti ti-check"></i> 48-hour turnaround</li>
          <li><i class="ti ti-check"></i> Email summary report</li>
        </ul>
        <a href="<?= base_url($isLoggedIn ? 'cv-review/submit' : 'login?redirect=cv-review/submit') ?>" class="btn btn-outline-primary">Get Free Review</a>
      </div>
      <div class="price-card featured">
        <div class="price-badge">Most Popular</div>
        <div class="price-name">Professional Review</div>
        <div class="price-tag-line">Expert human + AI analysis</div>
        <div class="price-amount"><span class="cur">&#8358;</span><span class="val"><?= number_format($planPrices['professional'], 0) ?></span><span class="per">/review</span></div>
        <div class="price-sub">Full certified-expert review with rewrites.</div>
        <ul class="price-feats">
          <li><i class="ti ti-check"></i> Everything in Basic</li>
          <li><i class="ti ti-check"></i> HR Expert Review</li>
          <li><i class="ti ti-check"></i> Line-by-line Feedback</li>
          <li><i class="ti ti-check"></i> Rewritten Sections</li>
          <li><i class="ti ti-check"></i> Cover Letter Tips</li>
          <li><i class="ti ti-check"></i> 24-hour turnaround</li>
        </ul>
        <a href="#" class="btn btn-accent choose-plan" data-plan="professional" data-price="<?= $planPrices['professional'] ?>">Get Reviewed Now</a>
      </div>
      <div class="price-card">
        <div class="price-name">Premium Review</div>
        <div class="price-tag-line">Full career document overhaul</div>
        <div class="price-amount"><span class="cur">&#8358;</span><span class="val"><?= number_format($planPrices['premium'], 0) ?></span><span class="per">/review</span></div>
        <div class="price-sub">The complete package with 1-on-1 support.</div>
        <ul class="price-feats">
          <li><i class="ti ti-check"></i> Everything in Professional</li>
          <li><i class="ti ti-check"></i> Full CV Rewrite</li>
          <li><i class="ti ti-check"></i> LinkedIn Profile Review</li>
          <li><i class="ti ti-check"></i> Cover Letter Writing</li>
          <li><i class="ti ti-check"></i> 1-on-1 Consultation Call</li>
          <li><i class="ti ti-check"></i> 12-hour turnaround</li>
        </ul>
        <a href="#" class="btn btn-outline-primary choose-plan" data-plan="premium" data-price="<?= $planPrices['premium'] ?>">Choose Premium</a>
      </div>
    </div>
  </div>
</section>

<!-- WHAT YOU GET / VISUAL PROOF -->
<section class="cvr-sec white" aria-labelledby="proof-h">
  <div class="container">
    <div class="cvr-proof">
      <div class="cvr-proof-text">
        <div class="cvr-label"><i class="ti ti-eye"></i> What You Get</div>
        <h2 id="proof-h">A Clear, <span>Actionable Report</span> &mdash; Not Vague Advice</h2>
        <p>Every review delivers a structured report you can act on immediately, with specific examples drawn from your own CV.</p>
        <ul class="cvr-proof-list">
          <li><i class="ti ti-check"></i> Before-and-after rewrites of your weakest sections</li>
          <li><i class="ti ti-check"></i> Your ATS compatibility score and exactly what to fix</li>
          <li><i class="ti ti-check"></i> Prioritized checklist, ranked by impact</li>
          <li><i class="ti ti-check"></i> Industry-specific keyword recommendations</li>
        </ul>
      </div>
      <div class="cvr-report-img">
        <div class="cvr-report-ph"><i class="ti ti-file-text"></i><span>Sample Report Preview</span></div>
      </div>
    </div>
  </div>
</section>

<!-- WHAT WE REVIEW -->
<section class="cvr-sec tint" aria-labelledby="rev-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><i class="ti ti-adjustments-horizontal"></i> What We Review</div>
      <h2 id="rev-h">Comprehensive <span>CV Analysis</span></h2>
      <p>Every CV undergoes a thorough multi-point inspection across these key areas.</p>
    </div>
    <div class="cvr-review-grid">
      <div class="cvr-rev-item">
        <div class="cvr-rev-ic"><i class="ti ti-layout"></i></div>
        <h3>Structure &amp; Formatting</h3>
        <p>Layout, section ordering, spacing, font consistency, and page balance.</p>
      </div>
      <div class="cvr-rev-item">
        <div class="cvr-rev-ic"><i class="ti ti-user"></i></div>
        <h3>Professional Summary</h3>
        <p>Impact, clarity, keyword optimization, and alignment with target roles.</p>
      </div>
      <div class="cvr-rev-item">
        <div class="cvr-rev-ic"><i class="ti ti-briefcase"></i></div>
        <h3>Work Experience</h3>
        <p>Action verbs, quantified achievements, relevance, and career progression.</p>
      </div>
      <div class="cvr-rev-item">
        <div class="cvr-rev-ic"><i class="ti ti-tools"></i></div>
        <h3>Skills Presentation</h3>
        <p>Relevance, categorization, proficiency indicators, and keyword density.</p>
      </div>
      <div class="cvr-rev-item">
        <div class="cvr-rev-ic"><i class="ti ti-device-analytics"></i></div>
        <h3>ATS Compatibility</h3>
        <p>Parseability, keyword match rate, file format, and section header recognition.</p>
      </div>
      <div class="cvr-rev-item">
        <div class="cvr-rev-ic"><i class="ti ti-rocket"></i></div>
        <h3>Overall Impact</h3>
        <p>First impression, uniqueness, value proposition, and call to action.</p>
      </div>
    </div>
  </div>
</section>

<!-- OUR EXPERTS -->
<section class="cvr-sec white" aria-labelledby="exp-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><i class="ti ti-users"></i> Our Experts</div>
      <h2 id="exp-h">Meet Your <span>Reviewers</span></h2>
      <p>Certified HR professionals and recruitment specialists with years of industry experience.</p>
    </div>
    <div class="expert-grid">
      <div class="expert-card">
        <div class="expert-avatar">SA</div>
        <h5>Sarah Adeyemi</h5>
        <div class="role">Senior HR Specialist</div>
        <p>12+ years in talent acquisition across banking, tech, and FMCG sectors.</p>
      </div>
      <div class="expert-card">
        <div class="expert-avatar">TO</div>
        <h5>Tunde Okafor</h5>
        <div class="role">Recruitment Lead</div>
        <p>Former agency recruiter with 5000+ CVs reviewed for top-tier firms.</p>
      </div>
      <div class="expert-card">
        <div class="expert-avatar">CO</div>
        <h5>Chioma Obi</h5>
        <div class="role">Career Coach</div>
        <p>Certified career development professional and ATS optimization expert.</p>
      </div>
      <div class="expert-card">
        <div class="expert-avatar">KM</div>
        <h5>Kunle Martins</h5>
        <div class="role">Talent Director</div>
        <p>10+ years leading recruitment for multinational corporations across Africa.</p>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="cvr-sec tint" aria-labelledby="tm-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><i class="ti ti-star"></i> Testimonials</div>
      <h2 id="tm-h">What Our <span>Clients Say</span></h2>
      <p>Hear from professionals who transformed their job search with our CV review service.</p>
    </div>
    <div class="cvr-tm-grid">
      <div class="cvr-tm">
        <div class="cvr-tm-stars" aria-label="5 out of 5 stars">
          <i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i>
        </div>
        <p>&ldquo;The CV review was eye-opening. I had no idea my CV wasn't ATS-friendly. After implementing the changes, I got 3 interview invites in the first week!&rdquo;</p>
        <div class="cvr-tm-who">
          <span class="cvr-tm-av" style="background:var(--brand)">AM</span>
          <div><strong>Adebayo Martins</strong><span>Software Engineer, Access Bank</span></div>
        </div>
      </div>
      <div class="cvr-tm">
        <div class="cvr-tm-stars" aria-label="5 out of 5 stars">
          <i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i>
        </div>
        <p>&ldquo;Worth every penny! The detailed line-by-line feedback helped me completely rewrite my CV. Landed my dream job within 3 weeks of the premium review.&rdquo;</p>
        <div class="cvr-tm-who">
          <span class="cvr-tm-av" style="background:#16a34a">FO</span>
          <div><strong>Folake Ogunlesi</strong><span>Marketing Manager, MTN Nigeria</span></div>
        </div>
      </div>
      <div class="cvr-tm">
        <div class="cvr-tm-stars" aria-label="5 out of 5 stars">
          <i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i>
        </div>
        <p>&ldquo;The free basic review already gave me so much value. I upgraded to professional and the before/after comparison was incredible. Highly recommended!&rdquo;</p>
        <div class="cvr-tm-who">
          <span class="cvr-tm-av" style="background:var(--accent)">CE</span>
          <div><strong>Chidi Eze</strong><span>Data Analyst, PwC</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- UPLOAD SECTION -->
<section class="cvr-sec white" id="upload-section" aria-labelledby="up-h">
  <div class="container">
    <?php if (!$isLoggedIn): ?>
    <div class="cvr-signin">
      <div class="cvr-signin-ic"><i class="ti ti-lock"></i></div>
      <h2 id="up-h">Sign In Required</h2>
      <p>You need to be signed in to submit your CV for review. Already have an account? Sign in below.</p>
      <div class="cvr-signin-actions">
        <a href="<?= base_url('login?redirect=cv-review') ?>" class="btn btn-primary"><i class="ti ti-login me-2"></i>Sign In</a>
        <a href="<?= base_url('register?redirect=cv-review') ?>" class="btn btn-outline-primary"><i class="ti ti-user-plus me-2"></i>Register</a>
      </div>
    </div>
    <?php else: ?>
    <div class="row g-5 align-items-start">
      <div class="col-lg-7">
        <div class="upload-card">
          <div class="upload-card-header">
            <h3 id="up-h">Upload Your CV for Review</h3>
            <p>Fill in the details and our experts will get back to you within 48 hours.</p>
          </div>
          <div class="upload-card-body">
            <form id="cv-review-form" enctype="multipart/form-data">
              <input type="hidden" name="review_id" id="review_id" value="">
              <div class="mb-4">
                <label class="form-label fw-semibold">Select Review Package <span class="text-danger">*</span></label>
                <select name="plan" id="plan-select" class="form-select">
                  <option value="basic">Basic Review (Free)</option>
                  <option value="professional">Professional Review (&#8358;<?= number_format($planPrices['professional'], 0) ?>)</option>
                  <option value="premium">Premium Review (&#8358;<?= number_format($planPrices['premium'], 0) ?>)</option>
                </select>
                <div id="payment-status" class="mt-2 small" style="display:none;"></div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                  <input type="text" name="full_name" class="form-control form-control-lg" placeholder="e.g. John Doe" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="e.g. john@example.com" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Phone Number</label>
                  <input type="tel" name="phone" class="form-control form-control-lg" placeholder="e.g. +234 800 000 0000">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Target Industry <span class="text-danger">*</span></label>
                  <select name="industry" class="form-select form-select-lg">
                    <option value="">Select Industry</option>
                    <option value="technology">Technology</option>
                    <option value="finance">Finance &amp; Banking</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="education">Education</option>
                    <option value="marketing">Marketing &amp; Media</option>
                    <option value="sales">Sales &amp; Retail</option>
                    <option value="government">Government &amp; Public Sector</option>
                    <option value="consulting">Consulting</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              </div>
              <div class="mb-4">
                <label class="form-label fw-semibold">Upload CV File <span class="text-danger">*</span></label>
                <div class="upload-area" id="upload-area">
                  <i class="ti ti-cloud-upload"></i>
                  <h5>Drag &amp; drop your CV here</h5>
                  <p>or click to browse files</p>
                  <div class="formats">Supported formats: PDF, DOC, DOCX (Max 5MB)</div>
                  <input type="file" name="cv_file" id="cv_file" class="d-none" accept=".pdf,.doc,.docx" required>
                </div>
                <div id="file-error" class="text-danger mt-2 small" style="display: none;"></div>
                <div class="file-preview" id="file-preview">
                  <i class="ti ti-file-text"></i>
                  <div>
                    <div class="file-name" id="file-name"></div>
                    <div class="file-size" id="file-size"></div>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="remove-file" aria-label="Close">
                    <i class="ti ti-x"></i>
                  </button>
                </div>
              </div>
              <div class="mb-4">
                <label class="form-label fw-semibold">What specific feedback are you looking for?</label>
                <textarea name="feedback_request" class="form-control" rows="4" placeholder="e.g. I'm applying for senior product manager roles in fintech. Please focus on my achievements section, ATS optimization, and overall impact..."></textarea>
              </div>
              <div class="mb-4">
                <label class="form-label fw-semibold">Target Role / Job Title</label>
                <input type="text" name="target_role" class="form-control form-control-lg" placeholder="e.g. Senior Product Manager">
              </div>
              <button type="submit" class="btn btn-accent btn-lg w-100 py-3" id="btn-submit-cv">
                <i class="ti ti-send me-2"></i>Submit for Review
              </button>
            </form>
            <div id="cv-upload-msg" class="mt-4" style="display: none;"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="benefits-card mb-4">
          <div class="benefits-card-body">
            <h5><i class="ti ti-shield-check me-2"></i>Why Choose Our Service?</h5>
            <ul class="benefits-list">
              <li>
                <i class="ti ti-circle-check"></i>
                <div>
                  <strong>Certified Reviewers</strong>
                  <p>All reviewers are HR-certified professionals with real recruitment experience.</p>
                </div>
              </li>
              <li>
                <i class="ti ti-circle-check"></i>
                <div>
                  <strong>ATS-Optimized</strong>
                  <p>We ensure your CV passes Applicant Tracking Systems used by 95% of large employers.</p>
                </div>
              </li>
              <li>
                <i class="ti ti-circle-check"></i>
                <div>
                  <strong>48hr Guarantee</strong>
                  <p>Receive your detailed review report within 48 hours, often sooner.</p>
                </div>
              </li>
              <li>
                <i class="ti ti-circle-check"></i>
                <div>
                  <strong>Confidential &amp; Secure</strong>
                  <p>Your documents are encrypted and never shared with third parties.</p>
                </div>
              </li>
              <li>
                <i class="ti ti-circle-check"></i>
                <div>
                  <strong>Satisfaction Guaranteed</strong>
                  <p>Not happy? We'll re-review your CV for free until you're satisfied.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="help-card">
          <div class="help-card-body">
            <i class="ti ti-message-chat"></i>
            <h5>Have Questions?</h5>
            <p>Our team is ready to help you choose the right review package.</p>
            <a href="<?= base_url('contact-us') ?>" class="btn btn-outline-light px-4">Contact Us</a>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- FAQ -->
<section class="cvr-sec tint" aria-labelledby="faq-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><i class="ti ti-lamp"></i> FAQ</div>
      <h2 id="faq-h">Frequently Asked <span>Questions</span></h2>
      <p>Everything you need to know about our CV review service.</p>
    </div>
    <div class="cvr-faq">
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">
          How long does a CV review take?
          <i class="ti ti-plus"></i>
        </button>
        <div class="faq-a">
          <div class="faq-a-in">Basic reviews are processed within 48 hours. Professional reviews are completed within 24 hours, and Premium reviews have a 12-hour turnaround. We also offer express options for urgent requests.</div>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">
          What file formats do you accept?
          <i class="ti ti-plus"></i>
        </button>
        <div class="faq-a">
          <div class="faq-a-in">We accept PDF, DOC, and DOCX file formats. Maximum file size is 5MB. For best results, we recommend uploading a PDF version of your CV.</div>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">
          What's the difference between Basic and Professional review?
          <i class="ti ti-plus"></i>
        </button>
        <div class="faq-a">
          <div class="faq-a-in">The Basic review provides an AI-powered analysis covering ATS compatibility, structure, and keywords. The Professional review includes a full human expert analysis with line-by-line feedback, rewritten sections, and personalized recommendations tailored to your target role.</div>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">
          Will my CV be kept confidential?
          <i class="ti ti-plus"></i>
        </button>
        <div class="faq-a">
          <div class="faq-a-in">Absolutely. We take your privacy seriously. All uploaded documents are encrypted during transmission and storage. Your CV is only accessible to your assigned reviewer and is never shared with third parties. You can request deletion of your documents at any time.</div>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">
          What if I'm not satisfied with the review?
          <i class="ti ti-plus"></i>
        </button>
        <div class="faq-a">
          <div class="faq-a-in">Your satisfaction is our priority. If you're not happy with your review, we'll re-review your CV for free and provide additional recommendations. Our premium package includes a 1-on-1 consultation call to address all your concerns.</div>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">
          Do you review CVs for specific industries?
          <i class="ti ti-plus"></i>
        </button>
        <div class="faq-a">
          <div class="faq-a-in">Yes! Our team of experts covers a wide range of industries including Technology, Finance, Healthcare, Education, Marketing, Sales, Government, and Consulting. When you upload your CV, simply select your target industry so we can match you with the most relevant reviewer.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="cvr-sec" aria-labelledby="final-h">
  <div class="container">
    <div class="cvr-final">
      <h2 id="final-h">Ready to Transform Your CV?</h2>
      <p>Join thousands of professionals who landed their dream jobs after a professional CV review. Start your journey today.</p>
      <a href="<?= base_url($isLoggedIn ? 'cv-review/submit' : 'login?redirect=cv-review/submit') ?>" class="btn btn-accent btn-lg px-5 py-3">
        <i class="ti ti-upload me-2"></i>Get Your CV Reviewed
      </a>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFaq(btn) {
  var open = btn.getAttribute('aria-expanded') === 'true';
  btn.setAttribute('aria-expanded', !open);
  var ans = btn.nextElementSibling;
  ans.style.maxHeight = open ? '0' : ans.scrollHeight + 'px';
}

document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('cv-review-form');
  if (!form) return;

  var fileInput = document.getElementById('cv_file');
  var uploadArea = document.getElementById('upload-area');
  var fileError = document.getElementById('file-error');
  var submitBtn = document.getElementById('btn-submit-cv');
  var msgDiv = document.getElementById('cv-upload-msg');
  var filePreview = document.getElementById('file-preview');
  var fileName = document.getElementById('file-name');
  var fileSize = document.getElementById('file-size');
  var removeFileBtn = document.getElementById('remove-file');

  uploadArea.addEventListener('click', function() { fileInput.click(); });

  uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.classList.add('dragover');
  });

  uploadArea.addEventListener('dragleave', function() {
    uploadArea.classList.remove('dragover');
  });

  uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    if (e.dataTransfer.files.length) {
      fileInput.files = e.dataTransfer.files;
      handleFileSelect();
    }
  });

  fileInput.addEventListener('change', handleFileSelect);

  function handleFileSelect() {
    var file = fileInput.files[0];
    if (!file) return;
    var allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (allowedTypes.indexOf(file.type) === -1) {
      fileError.textContent = 'Only PDF, DOC, and DOCX files are allowed';
      fileError.style.display = 'block';
      fileInput.value = '';
      return;
    }
    if (file.size > 5 * 1024 * 1024) {
      fileError.textContent = 'File size must be less than 5MB';
      fileError.style.display = 'block';
      fileInput.value = '';
      return;
    }
    fileError.style.display = 'none';
    fileName.textContent = file.name;
    fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    filePreview.classList.add('show');
  }

  if (removeFileBtn) {
    removeFileBtn.addEventListener('click', function() {
      fileInput.value = '';
      filePreview.classList.remove('show');
    });
  }

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    fileError.style.display = 'none';
    msgDiv.style.display = 'none';

    var plan = document.getElementById('plan-select').value;

    if (plan !== 'basic' && !document.getElementById('review_id').value) {
      fileError.textContent = 'Please complete payment for the ' + plan + ' plan first by clicking the pricing button above.';
      fileError.style.display = 'block';
      return;
    }

    var file = fileInput.files[0];
    if (!file) {
      fileError.textContent = 'Please select a CV file to upload';
      fileError.style.display = 'block';
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

    var formData = new FormData(form);

    fetch('<?= base_url('cv-review/upload') ?>', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
      }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      if (data.success || data.status === 200) {
        msgDiv.className = 'alert alert-success mt-4';
        msgDiv.innerHTML = '<i class="ti ti-check-circle me-2"></i>' + (data.message || 'CV uploaded successfully!');
        msgDiv.style.display = 'block';
        form.reset();
        filePreview.classList.remove('show');
        document.getElementById('review_id').value = '';
        document.getElementById('payment-status').style.display = 'none';
      } else {
        fileError.textContent = data.message || 'Upload failed. Please try again.';
        fileError.style.display = 'block';
      }
    })
    .catch(function() {
      fileError.textContent = 'An error occurred. Please try again.';
      fileError.style.display = 'block';
    })
    .finally(function() {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="ti ti-send me-2"></i>Submit for Review';
    });
  });

  var currentReviewId = <?= isset($reviewId) && $reviewId ? (int)$reviewId : 'null' ?>;
  if (currentReviewId) {
    document.getElementById('review_id').value = currentReviewId;
    var ps = document.getElementById('payment-status');
    ps.style.display = 'block';
    ps.className = 'mt-2 small text-success';
    ps.innerHTML = '<i class="ti ti-check-circle me-1"></i>Payment completed! Please upload your CV.';
  }

  var planPrices = <?= json_encode($planPrices) ?>;

  document.querySelectorAll('.choose-plan').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var plan = this.dataset.plan;
      var price = planPrices[plan] || 0;
      document.getElementById('plan-select').value = plan;

      if (plan === 'basic') {
        document.getElementById('upload-section').scrollIntoView({ behavior: 'smooth' });
        return;
      }

      <?php if (!$isLoggedIn): ?>
      window.location.href = '<?= base_url('login?redirect=cv-review&plan=') ?>' + plan;
      return;
      <?php else: ?>
      var btnEl = this;
      btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Redirecting...';
      btnEl.style.pointerEvents = 'none';

      fetch('<?= base_url('cv-review/pay') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest',
          '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
        },
        body: 'plan=' + plan + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
      })
      .then(function(res) { return res.json(); })
      .then(function(data) {
        if (data.authorization_url) {
          window.location.href = data.authorization_url;
        } else {
          toastr.error(data.message || 'Payment initiation failed');
          btnEl.innerHTML = '<i class="ti ti-crown me-1"></i>Pay &#8358;' + parseInt(price).toLocaleString();
          btnEl.style.pointerEvents = 'auto';
        }
      })
      .catch(function() {
        toastr.error('Connection error. Please try again.');
        btnEl.innerHTML = '<i class="ti ti-crown me-1"></i>Pay &#8358;' + parseInt(price).toLocaleString();
        btnEl.style.pointerEvents = 'auto';
      });
      <?php endif; ?>
    });
  });
});
</script>
<?= $this->endSection() ?>
