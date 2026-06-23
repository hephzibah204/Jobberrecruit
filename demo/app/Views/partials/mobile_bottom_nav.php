<!-- Mobile Bottom Navigation App Bar -->
<div class="mobile-bottom-nav d-block d-md-none">
    <a href="<?= base_url('/') ?>" class="nav-item <?= current_url() == base_url() || current_url() == base_url('/') ? 'active' : '' ?>">
        <i class="bi bi-house"></i>
        <span>Home</span>
    </a>
    <a href="<?= base_url('jobs') ?>" class="nav-item <?= strpos(current_url(), 'jobs') !== false ? 'active' : '' ?>">
        <i class="bi bi-search"></i>
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
        <a href="<?= $dashLink ?>" class="nav-item <?= strpos(current_url(), 'dashboard') !== false ? 'active' : '' ?>">
            <i class="bi bi-person"></i>
            <span>Dashboard</span>
        </a>
    <?php else: ?>
        <a href="<?= base_url('login') ?>" class="nav-item <?= strpos(current_url(), 'login') !== false ? 'active' : '' ?>">
            <i class="bi bi-person"></i>
            <span>Login</span>
        </a>
    <?php endif; ?>

    <a href="javascript:void(0);" class="nav-item" onclick="document.querySelector('.hamburger').click()">
        <i class="bi bi-grid"></i>
        <span>Menu</span>
    </a>
</div>
