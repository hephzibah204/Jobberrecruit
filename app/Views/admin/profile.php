<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title fw-medium fs-18">My Profile</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Edit Card -->
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form id="profileForm">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control"
                                value="<?= esc($admin->full_name) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email (cannot be changed)</label>
                            <input type="email" class="form-control" value="<?= esc($user->email) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?= esc(ucfirst($admin->role)) ?>" disabled>
                        </div>

                        <button type="submit" class="btn btn-primary" id="profileBtn">
                            <span class="btn-text">Update Profile</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card custom-card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form id="passwordForm">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                            <small class="text-muted">Minimum 8 characters, include letters, numbers & symbols</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger" id="passwordBtn">
                            <span class="btn-text">Change Password</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card custom-card text-center">
                <div class="card-body">
                    <div class="avatar avatar-xxl bg-primary mb-3 mx-auto">
                        <span class="avatar-initials fs-3 fw-bold text-white">
                            <?= strtoupper(substr($admin->full_name, 0, 2)) ?>
                        </span>
                    </div>
                    <h5 class="mb-1"><?= esc($admin->full_name) ?></h5>
                    <p class="text-muted mb-0"><?= esc($user->email) ?></p>
                    <span class="badge bg-success-transparent mt-2"><?= ucfirst($admin->role) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right'
    };

    // Helper to handle loading state
    function setLoading(btnId, loading) {
        const btn = document.getElementById(btnId);
        const text = btn.querySelector('.btn-text');
        const spinner = btn.querySelector('.spinner-border');

        if (loading) {
            btn.disabled = true;
            text.classList.add('d-none');
            spinner.classList.remove('d-none');
        } else {
            btn.disabled = false;
            text.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    }

    // Update Profile
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        setLoading('profileBtn', true);

        fetch('<?= base_url('admin/profile/update') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    // Optional: update name in avatar
                    document.querySelector('.avatar-initials').textContent =
                        res.message.includes('updated') ?
                        res.full_name?.substring(0, 2).toUpperCase() ||
                        '<?= strtoupper(substr($admin->full_name, 0, 2)) ?>' :
                        '<?= strtoupper(substr($admin->full_name, 0, 2)) ?>';
                } else {
                    toastr.error(res.message || 'Something went wrong');
                    if (res.errors) {
                        Object.values(res.errors).forEach(msg => toastr.warning(msg));
                    }
                }
            })
            .catch(() => toastr.error('Network error'))
            .finally(() => setLoading('profileBtn', false));
    });

    // Change Password
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        setLoading('passwordBtn', true);

        fetch('<?= base_url('admin/profile/change-password') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    this.reset(); // clear form
                } else {
                    toastr.error(res.message || 'Something went wrong');
                    if (res.errors) {
                        Object.values(res.errors).forEach(msg => toastr.warning(msg));
                    }
                }
            })
            .catch(() => toastr.error('Network error'))
            .finally(() => setLoading('passwordBtn', false));
    });
</script>
<?= $this->endSection() ?>