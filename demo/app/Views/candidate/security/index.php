<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Security Settings</h4>
                <h6>Manage your account security</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-shield-lock text-primary me-2"></i>
                        Change Password
                    </h5>
                </div>
                <div class="card-body">
                    <form id="changePasswordForm">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="current_password" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="ti ti-eye-off"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="ti ti-eye-off"></i>
                                </button>
                            </div>
                            <small class="text-muted">
                                Must be at least 8 characters with uppercase, lowercase, number, and symbol.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="confirm_new_password" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="ti ti-eye-off"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                                <span class="btn-text">Update Password</span>
                                <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom-card mt-4">
                <div class="card-body text-center py-5">
                    <i class="ti ti-lock fs-1 text-success mb-3"></i>
                    <h6>Your password is protected</h6>
                    <p class="text-muted">We use strong encryption and follow best practices to keep your account secure.</p>
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
        positionClass: 'toast-top-right',
        timeOut: 6000
    };

    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const btn = $(this);
        const input = btn.closest('.input-group').find('input');
        const icon = btn.find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('ti-eye-off').addClass('ti-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('ti-eye').addClass('ti-eye-off');
        }
    });

    // Change password form
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#changePasswordBtn');
        const btnText = btn.find('.btn-text');
        const spinner = btn.find('.spinner-border');

        // Loading state
        btn.prop('disabled', true);
        btnText.addClass('d-none');
        spinner.removeClass('d-none');

        $.ajax({
            url: '<?= base_url('candidate/security/change-password') ?>',
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