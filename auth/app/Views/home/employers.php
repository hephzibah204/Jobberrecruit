<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<style>
.emp-hero {
  background: radial-gradient(900px 500px at 10% 0%, rgba(237,144,32,0.15), transparent 55%),
              linear-gradient(135deg, #0A2F57 0%, #064A85 100%);
  color: #fff;
  padding: 60px 0 45px;
}
.emp-hero h1 { font-family: 'Sora', sans-serif; font-size: 2.2rem; font-weight: 800; }
.emp-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px;
  transition: all 0.25s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.emp-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(10,47,87,0.08);
  border-color: #0D609E;
}
.emp-logo {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  object-fit: cover;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #0D609E;
  font-size: 1.2rem;
}
</style>

<div class="emp-hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="mb-2">Top Employers in <span>Nigeria</span></h1>
        <p class="lead mb-4 text-white-50">Discover verified companies hiring top talent in Lagos, Abuja, and across Nigeria.</p>
        <form action="<?= base_url('employers') ?>" method="get" class="row g-2">
          <div class="col-md-6">
            <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" class="form-control form-control-lg" placeholder="Search company name...">
          </div>
          <div class="col-md-4">
            <select name="state_id" class="form-select form-select-lg">
              <option value="">All Locations</option>
              <?php foreach ($states as $s): ?>
                <option value="<?= $s->id ?>" <?= ($selectedState == $s->id) ? 'selected' : '' ?>><?= esc($s->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold">Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="py-5 bg-light">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h4 class="fw-bold mb-0">Browse Verified Companies</h4>
      <span class="text-muted"><?= count($employers) ?> companies found</span>
    </div>

    <?php if (empty($employers)): ?>
      <div class="text-center py-5 bg-white rounded-3 border">
        <i class="ti ti-building-store fs-1 text-muted d-block mb-2"></i>
        <h5>No employers found</h5>
        <p class="text-muted">Try adjusting your search query or location filter.</p>
        <a href="<?= base_url('employers') ?>" class="btn btn-outline-primary btn-sm">Reset Filters</a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($employers as $emp): ?>
          <?php
            $initials = strtoupper(substr($emp->company_name ?? 'C', 0, 2));
            $logoUrl = (!empty($emp->logo) && file_exists(FCPATH . $emp->logo)) ? base_url($emp->logo) : null;
          ?>
          <div class="col-md-6 col-lg-4">
            <div class="emp-card">
              <div>
                <div class="d-flex align-items-center gap-3 mb-3">
                  <?php if ($logoUrl): ?>
                    <img src="<?= $logoUrl ?>" alt="<?= esc($emp->company_name) ?>" class="emp-logo">
                  <?php else: ?>
                    <div class="emp-logo"><?= $initials ?></div>
                  <?php endif; ?>
                  <div>
                    <h5 class="fw-bold mb-1">
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
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                  <i class="ti ti-briefcase me-1"></i><?= $emp->job_count ?> Open Jobs
                </span>
                <a href="<?= base_url('jobs') ?>?keyword=<?= urlencode($emp->company_name) ?>" class="btn btn-sm btn-outline-secondary">
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
