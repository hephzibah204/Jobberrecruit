<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<style>
.adv-hero {
  background: radial-gradient(1000px 600px at 80% 0%, rgba(240,143,26,0.18), transparent 60%),
              linear-gradient(145deg, #0A2F57 0%, #064A85 100%);
  color: #fff;
  padding: 64px 0 48px;
}
.adv-hero h1 { font-family: 'Sora', sans-serif; font-size: 2.3rem; font-weight: 800; }
.adv-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px;
  transition: all 0.25s ease;
  height: 100%;
}
.adv-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(10,47,87,0.08);
  border-color: #0D609E;
}
.adv-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: #E6F0F8;
  color: #0D609E;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 16px;
}
</style>

<div class="adv-hero">
  <div class="container text-center max-w-700">
    <h1 class="mb-3">Career Advice & <span>Guides</span></h1>
    <p class="lead text-white-50 mb-0">Expert insights, resume building strategies, interview masterclasses, and salary negotiation guides tailored for job seekers in Nigeria.</p>
  </div>
</div>

<div class="py-5 bg-light">
  <div class="container">

    <!-- Pillars -->
    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-3">
        <div class="adv-card">
          <div class="adv-icon"><i class="ti ti-file-text"></i></div>
          <h5 class="fw-bold mb-2">Resume & CV Mastery</h5>
          <p class="text-muted small mb-3">Learn how to write ATS-friendly resumes that get you shortlisted by top Nigerian recruiters.</p>
          <a href="<?= base_url('cv-review') ?>" class="text-primary fw-bold small text-decoration-none">Get CV Review <i class="ti ti-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="adv-card">
          <div class="adv-icon"><i class="ti ti-messages"></i></div>
          <h5 class="fw-bold mb-2">Interview Prep</h5>
          <p class="text-muted small mb-3">Master behavioral interview questions, technical assessments, and confidence techniques.</p>
          <a href="<?= base_url('candidate/career-tools') ?>" class="text-primary fw-bold small text-decoration-none">AI Mock Interview <i class="ti ti-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="adv-card">
          <div class="adv-icon"><i class="ti ti-report-money"></i></div>
          <h5 class="fw-bold mb-2">Salary Benchmarks</h5>
          <p class="text-muted small mb-3">Navigate salary negotiations and discover benchmark compensation trends across tech, finance & management.</p>
          <a href="<?= base_url('candidate/career-tools') ?>" class="text-primary fw-bold small text-decoration-none">Salary Guide <i class="ti ti-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="adv-card">
          <div class="adv-icon"><i class="ti ti-school"></i></div>
          <h5 class="fw-bold mb-2">Upskilling & Certs</h5>
          <p class="text-muted small mb-3">Acquire in-demand skills and earn verified certificates to elevate your professional portfolio.</p>
          <a href="<?= base_url('training') ?>" class="text-primary fw-bold small text-decoration-none">Browse Courses <i class="ti ti-arrow-right"></i></a>
        </div>
      </div>
    </div>

    <!-- Latest Articles -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h3 class="fw-bold mb-0">Latest Articles & Insights</h3>
      <a href="<?= base_url('blog') ?>" class="btn btn-outline-primary btn-sm">View All Blog Posts</a>
    </div>

    <div class="row g-4">
      <?php if (!empty($recentBlogs)): ?>
        <?php foreach ($recentBlogs as $blog): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
              <?php if (!empty($blog->featured_image)): ?>
                <img src="<?= base_url($blog->featured_image) ?>" class="card-img-top" alt="<?= esc($blog->title) ?>" style="height: 180px; object-fit: cover;">
              <?php else: ?>
                <div class="bg-secondary-subtle text-center py-5" style="height: 180px;">
                  <i class="ti ti-news fs-1 text-secondary"></i>
                </div>
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <span class="badge bg-light text-dark align-self-start mb-2"><?= esc($blog->category ?? 'Career Advice') ?></span>
                <h5 class="card-title fw-bold mb-2">
                  <a href="<?= base_url('blog/' . $blog->slug) ?>" class="text-dark text-decoration-none"><?= esc($blog->title) ?></a>
                </h5>
                <p class="card-text text-muted small flex-grow-1"><?= esc(character_limiter(strip_tags($blog->content ?? ''), 120)) ?></p>
                <div class="pt-3 border-top d-flex align-items-center justify-content-between text-muted small">
                  <span><i class="ti ti-calendar me-1"></i><?= date('M d, Y', strtotime($blog->created_at)) ?></span>
                  <a href="<?= base_url('blog/' . $blog->slug) ?>" class="text-primary fw-bold text-decoration-none">Read Article <i class="ti ti-arrow-right"></i></a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-4">
          <p class="text-muted">No recent articles found. Check out our <a href="<?= base_url('blog') ?>">blog page</a>.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
