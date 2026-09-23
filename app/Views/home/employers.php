<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
.emp-hero {
  background: radial-gradient(ellipse 70% 60% at 82% 20%, rgba(240,143,26,.2) 0%, transparent 55%),
              radial-gradient(ellipse 80% 70% at 10% 90%, rgba(13,96,158,.35) 0%, transparent 55%),
              linear-gradient(160deg, #07304F 0%, #0A4D7E 55%, #0D609E 100%);
  color: #fff;
  padding: 64px 0 54px;
  position: relative;
  overflow: hidden;
}
.emp-hero-grid {
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .4;
  background-image: linear-gradient(rgba(255,255,255,.06) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.06) 1px, transparent 1px);
  background-size: 44px 44px;
}
.emp-hero h1 {
  font-family: 'Sora', sans-serif;
  font-size: clamp(2rem, 3.8vw, 2.8rem);
  font-weight: 800;
  color: #ffffff;
  line-height: 1.15;
  margin-bottom: 12px;
}
.emp-hero h1 span {
  color: var(--accent, #F08F1A);
}
.emp-hero-sub {
  font-size: 1.02rem;
  color: rgba(255, 255, 255, 0.85);
  max-width: 600px;
  margin-bottom: 28px;
  line-height: 1.6;
}
.emp-search-box {
  background: #ffffff;
  padding: 8px;
  border-radius: 14px;
  box-shadow: 0 14px 34px rgba(7, 48, 79, 0.28);
}
.emp-search-btn {
  background: var(--accent, #F08F1A);
  color: #07304F;
  border: none;
  font-weight: 700;
  font-family: 'Sora', sans-serif;
  border-radius: 10px;
  padding: 12px 24px;
  transition: all 0.2s ease;
}
.emp-search-btn:hover {
  background: #d07d10;
  color: #07304F;
}
.emp-card {
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px;
  transition: all 0.25s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.emp-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 32px rgba(10, 47, 87, 0.1);
  border-color: var(--brand, #0D609E);
}
.emp-logo {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  object-fit: cover;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #0D609E;
  font-size: 1.25rem;
  font-family: 'Sora', sans-serif;
  flex-shrink: 0;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="emp-hero">
  <div class="emp-hero-grid"></div>
  <div class="container position-relative" style="z-index: 1;">
    <div class="row align-items-center">
      <div class="col-lg-9">
        <h1>Top Employers in <span>Nigeria</span></h1>
        <p class="emp-hero-sub">Discover verified companies hiring top talent in Lagos, Abuja, Port Harcourt, and across Nigeria.</p>
        <div class="emp-search-box">
          <form action="<?= base_url('employers') ?>" method="get" class="row g-2 align-items-center">
            <div class="col-md-6">
              <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" class="form-control form-control-lg border-0 shadow-none" placeholder="Search company name...">
            </div>
            <div class="col-md-4">
              <select name="state_id" class="form-select form-select-lg border-0 border-start shadow-none">
                <option value="">All Locations</option>
                <?php foreach ($states as $s): ?>
                  <option value="<?= $s->id ?>" <?= ($selectedState == $s->id) ? 'selected' : '' ?>><?= esc($s->name) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="emp-search-btn w-100">Search</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="py-5" style="background: #f8fafc;">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h3 class="fw-bold mb-1" style="font-family:'Sora',sans-serif; color:#07304F;">Browse Verified Companies</h3>
        <p class="text-muted small mb-0">Direct access to hiring companies and verified job openings</p>
      </div>
      <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill fw-semibold shadow-sm"><?= count($employers) ?> companies found</span>
    </div>

    <?php if (empty($employers)): ?>
      <div class="text-center py-5 bg-white rounded-4 border shadow-sm p-4">
        <i class="ti ti-building-store fs-1 text-muted d-block mb-3"></i>
        <h4 class="fw-bold text-dark">No employers found</h4>
        <p class="text-muted">Try adjusting your search keywords or location filter.</p>
        <a href="<?= base_url('employers') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-2">Reset Filters</a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($employers as $emp): ?>
          <?php
            $initials = strtoupper(substr($emp->company_name ?? 'C', 0, 2));
            $logoUrl = (!empty($emp->logo) && file_exists(FCPATH . $emp->logo)) ? base_url($emp->logo) : null;
          ?>
          <div class="col-md-6 col-lg-4">
            <div class="emp-card shadow-sm">
              <div>
                <div class="d-flex align-items-center gap-3 mb-3">
                  <?php if ($logoUrl): ?>
                    <img src="<?= $logoUrl ?>" alt="<?= esc($emp->company_name) ?>" class="emp-logo">
                  <?php else: ?>
                    <div class="emp-logo"><?= $initials ?></div>
                  <?php endif; ?>
                  <div style="min-width: 0;">
                    <h5 class="fw-bold mb-1 text-truncate">
                      <a href="<?= base_url('employer/' . url_title($emp->company_name, '-', true)) ?>" class="text-dark text-decoration-none"><?= esc($emp->company_name) ?></a>
                      <?php if ($emp->is_verified): ?>
                        <i class="ti ti-circle-check-filled text-primary ms-1" title="Verified Employer"></i>
                      <?php endif; ?>
                    </h5>
                    <span class="text-muted small"><i class="ti ti-map-pin me-1"></i><?= esc($emp->location ?? 'Nigeria') ?></span>
                  </div>
                </div>
              </div>
              <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                <span class="badge bg-light text-primary border rounded-pill px-3 py-2 fw-semibold">
                  <i class="ti ti-briefcase me-1"></i><?= $emp->job_count ?> Open <?= $emp->job_count == 1 ? 'Job' : 'Jobs' ?>
                </span>
                <a href="<?= base_url('jobs') ?>?keyword=<?= urlencode($emp->company_name) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                  View Jobs <i class="ti ti-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
