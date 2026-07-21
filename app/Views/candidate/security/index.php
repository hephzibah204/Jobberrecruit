<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.switch{position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0}
.switch input{opacity:0;width:0;height:0}
.switch .sl{position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.2s}
.switch .sl::before{content:"";position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.25)}
.switch input:checked + .sl{background:var(--brand)}
.switch input:checked + .sl::before{transform:translateX(20px)}
.pref-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid var(--border)}
.pref-row:last-child{border-bottom:none}
.pref-row b{font-size:.86rem;color:var(--brand-deep);font-family:'Sora',sans-serif}
.pref-row p{font-size:.76rem;color:var(--muted);margin:2px 0 0}
.danger-card{border:1px solid #f3c2c2 !important}
.btn-danger{background:#dc2626;color:#fff;border:1.5px solid #dc2626}
.btn-danger:hover{background:#b91c1c;border-color:#b91c1c}
.modal-scrim{position:fixed;inset:0;background:rgba(10,25,40,.55);display:none;align-items:center;justify-content:center;z-index:1000;padding:16px}
.modal-scrim.show{display:flex}
.modal-card{background:#fff;border-radius:14px;max-width:440px;width:100%;padding:26px;box-shadow:0 20px 60px rgba(0,0,0,.3)}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-cog"/></svg> General Settings</h1>
            <p>Manage your password, notification preferences, and account.</p>
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

        <!-- Notification preferences card -->
        <section class="card" aria-label="Notification preferences" style="padding: 24px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:var(--brand-deep); margin-bottom:4px; display:flex; align-items:center; gap:8px;">
                <svg aria-hidden="true" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Notification preferences
            </h3>
            <p style="font-size:0.8rem; color:var(--muted); margin-bottom:16px;">Choose which emails and alerts you'd like to receive.</p>
            <form id="notifPrefsForm">
                <?php
                $prefs = [
                    'notify_job_alerts'          => ['New job matches & alerts', 'Jobs matching your saved alerts and profile.', 1],
                    'notify_application_updates' => ['Application updates', 'Status changes on jobs you\'ve applied to.', 1],
                    'notify_messages'            => ['Messages', 'When an employer sends you a message.', 1],
                    'notify_marketing'           => ['Tips & product updates', 'Occasional career tips and platform news.', 0],
                ];
                foreach ($prefs as $name => $meta):
                    $checked = isset($candidate->$name) ? (int) $candidate->$name : $meta[2];
                ?>
                <label class="pref-row">
                    <span><b><?= esc($meta[0]) ?></b><p><?= esc($meta[1]) ?></p></span>
                    <span class="switch">
                        <input type="checkbox" name="<?= $name ?>" <?= $checked ? 'checked' : '' ?>>
                        <span class="sl"></span>
                    </span>
                </label>
                <?php endforeach; ?>
                <div style="display:flex; justify-content:flex-end; margin-top:16px;">
                    <button type="submit" class="btn btn-primary" id="savePrefsBtn">Save preferences</button>
                </div>
            </form>
        </section>

        <!-- Data & privacy card -->
        <section class="card" aria-label="Your data" style="padding: 24px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:var(--brand-deep); margin-bottom:4px; display:flex; align-items:center; gap:8px;">
                <svg aria-hidden="true" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-download"/></svg> Your data
            </h3>
            <p style="font-size:0.8rem; color:var(--muted); margin-bottom:16px;">Download a copy of your JobberRecruit data — profile, work history, education, applications, saved jobs, alerts and certificates — as a JSON file.</p>
            <a href="<?= base_url('candidate/settings/export-data') ?>" class="btn btn-outline">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-download"/></svg> Export my data
            </a>
        </section>

        <!-- Delete account (danger) card -->
        <section class="card danger-card" aria-label="Delete account" style="padding: 24px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:#b91c1c; margin-bottom:4px; display:flex; align-items:center; gap:8px;">
                <svg aria-hidden="true" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg> Delete account
            </h3>
            <p style="font-size:0.8rem; color:var(--muted); margin-bottom:16px;">Permanently delete your account and all associated data — applications, saved jobs, alerts, resumes, certificates and wallet. <b style="color:#b91c1c;">This cannot be undone.</b></p>
            <button type="button" class="btn btn-danger" id="openDeleteModal">
                <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg> Delete my account
            </button>
        </section>
    </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal-scrim" id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="dm-title">
    <div class="modal-card">
        <h3 id="dm-title" style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:#b91c1c; margin-bottom:8px;">Delete your account?</h3>
        <p style="font-size:0.82rem; color:var(--muted); line-height:1.6; margin-bottom:12px;">This permanently erases your account and all associated data. Enter your password and type <b>DELETE</b> to confirm.</p>
        <a href="<?= base_url('candidate/settings/export-data') ?>" class="btn btn-outline btn-sm" style="margin-bottom:16px;">
            <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-download"/></svg> Download my data first
        </a>
        <form id="deleteAccountForm" style="display:flex; flex-direction:column; gap:12px;">
            <?= csrf_field() ?>
            <div class="form-field">
                <label class="lbl">Password</label>
                <input type="password" name="password" class="input" required placeholder="Your current password">
            </div>
            <div class="form-field">
                <label class="lbl">Type DELETE to confirm</label>
                <input type="text" id="deleteConfirmText" class="input" autocomplete="off" placeholder="DELETE">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:6px;">
                <button type="button" class="btn btn-outline" id="cancelDelete">Cancel</button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete permanently</button>
            </div>
        </form>
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

    // Notification preferences — send unchecked boxes explicitly as 0
    $('#notifPrefsForm').on('submit', function(e) {
        e.preventDefault();
        var btn = $('#savePrefsBtn').prop('disabled', true);
        var data = { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' };
        $(this).find('input[type="checkbox"]').each(function() {
            data[this.name] = this.checked ? 1 : 0;
        });
        $.ajax({
            url: '<?= base_url('candidate/settings/notifications') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: data,
            success: function(res) {
                if (res && res.success) { toastr.success(res.message); }
                else { toastr.error((res && res.message) || 'Could not save preferences.'); }
            },
            error: function() { toastr.error('Network error. Please try again.'); },
            complete: function() { btn.prop('disabled', false); }
        });
    });

    // Delete account flow
    var deleteModal = $('#deleteModal');
    $('#openDeleteModal').on('click', function() { deleteModal.addClass('show'); });
    $('#cancelDelete').on('click', function() { deleteModal.removeClass('show'); });
    deleteModal.on('click', function(e) { if (e.target === this) deleteModal.removeClass('show'); });

    // Enable the confirm button only when the user types DELETE
    $('#deleteConfirmText').on('input', function() {
        $('#confirmDeleteBtn').prop('disabled', $(this).val().trim().toUpperCase() !== 'DELETE');
    });

    $('#deleteAccountForm').on('submit', function(e) {
        e.preventDefault();
        var btn = $('#confirmDeleteBtn').prop('disabled', true).text('Deleting…');
        $.ajax({
            url: '<?= base_url('candidate/settings/delete-account') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: $(this).serialize(),
            success: function(res) {
                if (res && res.success) {
                    toastr.success(res.message);
                    setTimeout(function() { window.location.href = res.redirect || '<?= base_url('/') ?>'; }, 1200);
                } else {
                    toastr.error((res && res.message) || 'Could not delete account.');
                    btn.prop('disabled', false).text('Delete permanently');
                }
            },
            error: function() {
                toastr.error('Network error. Please try again.');
                btn.prop('disabled', false).text('Delete permanently');
            }
        });
    });
</script>
<?= $this->endSection() ?>