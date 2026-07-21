<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-cog"/></svg> Security Settings</h1>
            <p>Manage your account security and authentication preferences.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 24px; max-width: 680px; margin-top: 20px;">
        <!-- Change password card -->
        <section class="card" aria-label="Change Password" style="padding: 24px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:var(--brand-deep); margin-bottom:4px; display:flex; align-items:center; gap:8px;">
                <svg aria-hidden="true" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg> Change password
            </h3>
            <p style="font-size:0.8rem; color:var(--muted); margin-bottom:20px;">Use a strong password you don't use anywhere else.</p>

            <form id="changePasswordForm" style="display:flex; flex-direction:column; gap:16px;">
                <?= csrf_field() ?>

                <div class="form-field">
                    <label class="lbl">Current password <span class="text-danger">*</span></label>
                    <div style="position:relative;">
                        <input type="password" name="current_password" class="input" required placeholder="Enter current password">
                        <button class="toggle-password" type="button" aria-label="Show/hide password">
                            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-eye"/></svg>
                        </button>
                    </div>
                </div>

                <div class="form-field">
                    <label class="lbl">New password <span class="text-danger">*</span></label>
                    <div style="position:relative;">
                        <input type="password" name="new_password" class="input" required placeholder="At least 8 characters">
                        <button class="toggle-password" type="button" aria-label="Show/hide password">
                            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-eye"/></svg>
                        </button>
                    </div>
                    <div style="font-size:0.74rem; color:var(--muted); margin-top:4px;">
                        Must be at least 8 characters with a mix of letters, numbers &amp; symbols.
                    </div>
                </div>

                <div class="form-field">
                    <label class="lbl">Confirm new password <span class="text-danger">*</span></label>
                    <div style="position:relative;">
                        <input type="password" name="confirm_new_password" class="input" required placeholder="Re-enter new password">
                        <button class="toggle-password" type="button" aria-label="Show/hide password">
                            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-eye"/></svg>
                        </button>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:8px;">
                    <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                        <span class="btn-text">Update Password</span>
                        <span class="spinner d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </section>

        <!-- Information card -->
        <section class="card" aria-label="Security Overview" style="padding: 24px; text-align: center;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--success-light); color: var(--success); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                <svg aria-hidden="true" style="width:24px;height:24px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg>
            </div>
            <h4 style="font-family:'Sora',sans-serif; font-size:0.94rem; font-weight:700; color:var(--brand-deep); margin-bottom:4px;">Your password is protected</h4>
            <p style="font-size:0.8rem; color:var(--muted); line-height:1.6; max-width:400px; margin:0 auto;">We use strong encryption and follow security best practices to keep your account safe.</p>
        </section>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const btn = $(this);
        const input = btn.closest('.form-field').find('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });

    // Change password form
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#changePasswordBtn');
        const btnText = btn.find('.btn-text');
        const spinner = btn.find('.spinner');

        // Loading state
        btn.prop('disabled', true);
        btnText.addClass('d-none');
        spinner.removeClass('d-none');

        $.ajax({
            url: '<?= base_url('candidate/settings/security/change-password') ?>',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    $('#changePasswordForm')[0].reset();
                } else {
                    toastr.error(res.message || 'Something went wrong');
                    if (res.errors) {
                        Object.values(res.errors).forEach(msg => toastr.warning(msg));
                    }
                }
            },
            error: function() {
                toastr.error('Network error. Please try again.');
            },
            complete: function() {
                btn.prop('disabled', false);
                btnText.removeClass('d-none');
                spinner.addClass('d-none');
            }
        });
    });
</script>
<?= $this->endSection() ?>