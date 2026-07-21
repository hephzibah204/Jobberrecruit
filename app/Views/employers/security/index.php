<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<div class="page-hd">
    <div class="page-hd-left">
        <h1>Security Settings</h1>
        <p>Manage your account password and security configuration</p>
    </div>
</div>

<div class="duo" style="grid-template-columns: 1.2fr 1fr;">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">
                <svg aria-hidden="true"><use href="#i-shield"/></svg>
                Change Password
            </h2>
        </div>
        <div class="card-body">
            <form id="changePasswordForm" class="form-section-body" style="padding:0;">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Current Password <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input type="password" name="current_password" id="current_password" class="form-control" style="padding-right: 44px;" required>
                        <button class="ic-btn toggle-password" data-pwd-target="current_password" type="button" aria-label="Toggle password" style="position:absolute; right:4px; top:50%; transform:translateY(-50%); border:none; background:transparent; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                            <svg aria-hidden="true" style="width:16px; height:16px; color:var(--muted);"><use href="#i-eye"/></svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">New Password <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input type="password" name="new_password" id="new_password" class="form-control" style="padding-right: 44px;" required>
                        <button class="ic-btn toggle-password" data-pwd-target="new_password" type="button" aria-label="Toggle password" style="position:absolute; right:4px; top:50%; transform:translateY(-50%); border:none; background:transparent; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                            <svg aria-hidden="true" style="width:16px; height:16px; color:var(--muted);"><use href="#i-eye"/></svg>
                        </button>
                    </div>
                    <small class="form-hint">
                        Must be at least 8 characters with uppercase, lowercase, number, and symbol.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password <span class="req">*</span></label>
                    <div style="position:relative;">
                        <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" style="padding-right: 44px;" required>
                        <button class="ic-btn toggle-password" data-pwd-target="confirm_new_password" type="button" aria-label="Toggle password" style="position:absolute; right:4px; top:50%; transform:translateY(-50%); border:none; background:transparent; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                            <svg aria-hidden="true" style="width:16px; height:16px; color:var(--muted);"><use href="#i-eye"/></svg>
                        </button>
                    </div>
                </div>

                <div class="text-end" style="margin-top: 8px;">
                    <button type="submit" class="emp-btn emp-btn-primary" id="changePasswordBtn" style="min-height: 40px; padding: 8px 24px;">
                        <span class="btn-text">Update Password</span>
                        <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body empty-state" style="padding: 32px 20px;">
            <div class="empty-ic" style="background: var(--success-light); color: var(--success);">
                <svg aria-hidden="true" style="width:24px; height:24px;"><use href="#i-shield"/></svg>
            </div>
            <h3>Your password is protected</h3>
            <p>We use strong encryption and follow security best practices to keep your account safe and secure.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetId = btn.getAttribute('data-pwd-target');
            var input = document.getElementById(targetId);
            if (input.getAttribute('type') === 'password') {
                input.setAttribute('type', 'text');
            } else {
                input.setAttribute('type', 'password');
            }
        });
    });

    // Change password form submission
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var btn = document.getElementById('changePasswordBtn');
        var btnText = btn.querySelector('.btn-text');
        var spinner = btn.querySelector('.spinner-border');

        btn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        var formData = new FormData(this);

        fetch('<?= base_url('employer/security/change-password') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                alert(res.message);
                document.getElementById('changePasswordForm').reset();
            } else {
                alert(res.message || 'Something went wrong');
            }
        })
        .catch(err => {
            alert('Network error. Please try again.');
        })
        .finally(() => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        });
    });
</script>
<?= $this->endSection() ?>