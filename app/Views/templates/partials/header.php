<header class="navbar" role="banner">
      <div class="container">
        <nav class="nav-inner" role="navigation" aria-label="Main navigation">
          <a href="<?= base_url('/') ?>" class="nav-logo" aria-label="JobberRecruit Home">
            <img src="<?= base_url('images/logo.png') ?>" alt="JobberRecruit" class="img-fluid" style="height:60px;width:auto">
          </a>
          <ul class="nav-links" role="list">
            <li><a href="<?= base_url('jobs') ?>">Find jobs</a></li>
            <li class="nav-dropdown">
              <button type="button" class="nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true">Training <svg class="nav-caret" aria-hidden="true"><use href="#i-chev-down"/></svg></button>
              <div class="nav-dropdown-menu" role="menu">
                <a href="<?= base_url('training') ?>" role="menuitem">Courses</a>
                <a href="<?= base_url('webinars') ?>" role="menuitem">Webinars</a>
              </div>
            </li>
            <li class="nav-dropdown">
              <button type="button" class="nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true">Recruitment <svg class="nav-caret" aria-hidden="true"><use href="#i-chev-down"/></svg></button>
              <div class="nav-dropdown-menu" role="menu">
                <a href="<?= base_url('recruitment') ?>" role="menuitem">Recruitment services</a>
                <a href="<?= base_url('job-ads') ?>" role="menuitem">Job ad pricing</a>
              </div>
            </li>
            <li><a href="<?= base_url('employer/post-job') ?>">Post a job</a></li>
          </ul>
          <div class="nav-actions">
            <button class="theme-toggle" aria-label="Toggle dark mode" style="background:none;border:none;cursor:pointer;color:var(--text);padding:8px;line-height:0">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
            <?php if (!auth()->user()) : ?>
              <a href="<?= base_url('login') ?>" class="btn btn-outline">Log in</a>
              <a href="<?= base_url('register') ?>" class="btn btn-primary">Get started free</a>
            <?php else : ?>
              <?php if (auth()->user()->user_type == 'employer'): ?>
                <a href="<?= base_url('employer/dashboard') ?>" class="btn btn-primary">Dashboard</a>
              <?php elseif (auth()->user()->user_type == 'job_seeker'): ?>
                <a href="<?= base_url('candidate/dashboard') ?>" class="btn btn-primary">Dashboard</a>
              <?php else: ?>
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary">Dashboard</a>
              <?php endif; ?>
              <a href="<?= base_url('logout') ?>" class="btn btn-outline">Logout</a>
            <?php endif; ?>
            <button class="hamburger" aria-label="Open navigation menu" aria-expanded="false" aria-controls="mob-nav" onclick="toggleMenu(this)">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
          </div>
        </nav>
        <nav id="mob-nav" class="mobile-nav" role="navigation" aria-label="Mobile navigation">
          <a href="<?= base_url('jobs') ?>">Find jobs</a>
          <div class="mob-group"><p class="mob-group-label">Training</p><a href="<?= base_url('training') ?>">Courses</a><a href="<?= base_url('webinars') ?>">Webinars</a></div>
          <div class="mob-group"><p class="mob-group-label">Recruitment</p><a href="<?= base_url('recruitment') ?>">Recruitment services</a><a href="<?= base_url('job-ads') ?>">Job ad pricing</a></div>
          <a href="<?= base_url('employer/post-job') ?>">Post a job</a>
          <a href="<?= base_url('login') ?>">Log in</a>
          <a href="<?= base_url('register') ?>" class="mobile-nav-cta">Get started free →</a>
        </nav>
      </div>
    </header>