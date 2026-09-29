<!-- Mobile Bottom Navigation App Bar (Hidden on Desktop) -->
<style>
/* Hide on tablet and desktop (>=768px) */
@media (min-width: 768px) {
  html body .mobile-bottom-nav { display: none !important; }
}

.mobile-bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: #ffffff;
  display: flex !important;
  justify-content: space-around;
  align-items: center;
  height: 66px;
  min-height: 66px;
  padding-top: 6px;
  padding-bottom: calc(6px + env(safe-area-inset-bottom, 0px));
  box-shadow: 0 -3px 18px rgba(10, 47, 87, 0.08);
  z-index: 1040;
  border-top: 1px solid #e2e8f0;
  -webkit-font-smoothing: antialiased;
}

.mobile-bottom-nav .nav-item {
  flex: 1 !important;
  min-width: 0 !important;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  text-align: center !important;
  text-decoration: none !important;
  color: #64748b !important;
  padding: 4px 2px !important;
  min-height: 48px !important;
  transition: all 0.15s ease !important;
}

.mobile-bottom-nav .nav-item svg {
  width: 22px !important;
  height: 22px !important;
  margin-bottom: 3px !important;
  stroke: currentColor !important;
  stroke-width: 2 !important;
  fill: none !important;
  transition: transform 0.15s ease, stroke 0.15s ease !important;
}

.mobile-bottom-nav .nav-item span {
  font-size: 11.5px !important;
  font-weight: 600 !important;
  letter-spacing: 0.01em !important;
  white-space: nowrap !important;
  word-break: normal !important;
  overflow-wrap: normal !important;
  text-overflow: ellipsis !important;
  overflow: hidden !important;
  max-width: 100% !important;
  display: block !important;
  line-height: 1.2 !important;
}

.mobile-bottom-nav .nav-item.active {
  color: #0861A9 !important;
}

.mobile-bottom-nav .nav-item.active svg {
  stroke: #0861A9 !important;
  transform: translateY(-1px) scale(1.06) !important;
}

.mobile-bottom-nav .nav-item.active span {
  color: #0861A9 !important;
  font-weight: 700 !important;
}

[data-theme="dark"] .mobile-bottom-nav {
  background: #0f172a;
  border-top-color: #1e293b;
  box-shadow: 0 -3px 18px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .mobile-bottom-nav .nav-item {
  color: #94a3b8 !important;
}

[data-theme="dark"] .mobile-bottom-nav .nav-item.active,
[data-theme="dark"] .mobile-bottom-nav .nav-item.active span {
  color: #38bdf8 !important;
}

[data-theme="dark"] .mobile-bottom-nav .nav-item.active svg {
  stroke: #38bdf8 !important;
}
</style>

<div class="mobile-bottom-nav d-flex d-md-none" role="navigation" aria-label="Mobile Navigation">
    <a href="<?= base_url('/') ?>" class="nav-item <?= current_url() == base_url() || current_url() == base_url('/') ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Home</span>
    </a>
    <a href="<?= base_url('jobs') ?>" class="nav-item <?= strpos(current_url(), 'jobs') !== false ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <span>Jobs</span>
    </a>
    
    <?php if (auth()->user()): ?>
        <?php 
            $dashLink = base_url('login'); // fallback
            if (auth()->user()->user_type == 'employer') {
                $dashLink = base_url('employer/dashboard');
            } elseif (auth()->user()->user_type == 'job_seeker') {
                $dashLink = base_url('candidate/dashboard');
            } elseif (auth()->user()->user_type == 'admin') {
                $dashLink = base_url('admin/dashboard');
            }
        ?>
        <a href="<?= $dashLink ?>" class="nav-item <?= strpos(current_url(), 'dashboard') !== false || strpos(current_url(), 'employer') !== false || strpos(current_url(), 'candidate') !== false ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
            <span>Dashboard</span>
        </a>
    <?php else: ?>
        <a href="<?= base_url('login') ?>" class="nav-item <?= strpos(current_url(), 'login') !== false ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a8.5 8.5 0 0 1 13 0"/></svg>
            <span>Get Started</span>
        </a>
    <?php endif; ?>

    <a href="javascript:void(0);" class="nav-item" onclick="if(window.toggleMobileMenu){window.toggleMobileMenu();}else if(window.toggleEmployerSidebar){window.toggleEmployerSidebar();}else if(window.togglePublicMenu){window.togglePublicMenu(document.querySelector('.hamburger'));}else{var h=document.querySelector('#emp-hamburger')||document.querySelector('.hamburger')||document.querySelector('.burger-icon');if(h)h.click();}">
        <svg viewBox="0 0 24 24" aria-hidden="true"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
        <span>Menu</span>
    </a>
</div>
