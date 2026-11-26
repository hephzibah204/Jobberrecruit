<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Two-Factor Authentication</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Two-Factor Authentication</li>
                </ol>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success py-2"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card custom-card">
        <div class="card-body">

            <?php if ($twoFactor && $twoFactor->enabled): ?>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-success-transparent text-success fs-13">
                        <i class="ti ti-shield-check me-1"></i> Enabled
                    </span>
                    <?php if (!empty($twoFactor->confirmed_at)): ?>
                        <span class="text-muted small">since <?= esc(date('M j, Y g:ia', strtotime($twoFactor->confirmed_at))) ?></span>
                    <?php endif; ?>
                </div>

                <p class="text-muted">
                    Two-factor authentication is active on your account. Each time you sign in you will be
                    asked for a 6-digit code from your authenticator app after your password.
                </p>

                <hr>

                <h6 class="fw-semibold">Disable two-factor authentication</h6>
                <p class="text-muted small">
                    Enter a current code from your authenticator app to confirm. Disabling removes the
                    extra sign-in step from your account immediately.
                </p>
                <form method="POST" action="<?= base_url('admin/two-factor/disable') ?>" class="row g-2 align-items-end">
                    <?= csrf_field() ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="disable-code">Authentication Code</label>
                        <input type="text" class="form-control" id="disable-code" name="code" required
                               autocomplete="one-time-code" inputmode="numeric" pattern="[0-9 ]*" maxlength="7"
                               placeholder="123456">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-shield-off me-1"></i> Disable
                        </button>
                    </div>
                </form>

            <?php elseif ($twoFactor): ?>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-warning-transparent text-warning fs-13">
                        <i class="ti ti-shield me-1"></i> Setup in progress
                    </span>
                </div>

                <p class="text-muted">
                    Add the setup key below to your authenticator app (Google Authenticator, Microsoft
                    Authenticator, Authy, or any TOTP app), then confirm with the 6-digit code it shows.
                    Two-factor authentication is <strong>not</strong> enforced on your account until you confirm.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Setup key</label>
                    <div class="bg-light border rounded p-3">
                        <code class="fs-15 user-select-all"><?= esc($setupKey) ?></code>
                    </div>
                    <div class="form-text">Account: <?= esc($user->email ?? 'admin') ?> &middot; Issuer: JobberRecruit Admin</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Setup URI (for apps that accept one)</label>
                    <div class="bg-light border rounded p-3">
                        <code class="small user-select-all" style="word-break: break-all;"><?= esc($provisioningUri) ?></code>
                    </div>
                </div>

                <form method="POST" action="<?= base_url('admin/two-factor/confirm') ?>" class="row g-2 align-items-end mb-3">
                    <?= csrf_field() ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="confirm-code">Authentication Code</label>
                        <input type="text" class="form-control" id="confirm-code" name="code" required
                               autocomplete="one-time-code" inputmode="numeric" pattern="[0-9 ]*" maxlength="7"
                               placeholder="123456">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-shield-check me-1"></i> Confirm &amp; Enable
                        </button>
                    </div>
                </form>

                <form method="POST" action="<?= base_url('admin/two-factor/start') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="ti ti-refresh me-1"></i> Generate a new key instead
                    </button>
                    <span class="text-muted small ms-2">This replaces the key above; a key you already added to your app will stop working.</span>
                </form>

            <?php else: ?>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-secondary-transparent text-secondary fs-13">
                        <i class="ti ti-shield me-1"></i> Not enabled
                    </span>
                </div>

                <p class="text-muted">
                    Protect your administrator account with an authenticator app. After setup, signing in
                    will require your password plus a 6-digit code that refreshes every 30 seconds.
                    Two-factor authentication applies only to your own account and only once you confirm setup.
                </p>

                <form method="POST" action="<?= base_url('admin/two-factor/start') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-shield-plus me-1"></i> Set up two-factor authentication
                    </button>
                </form>

            <?php endif; ?>

            <hr>

            <h6 class="fw-semibold">Lost your authenticator?</h6>
            <p class="text-muted small mb-0">
                If you lose access to your authenticator app, two-factor authentication can be disabled
                for your account by deleting your row from the <code>admin_2fa</code> table in the
                database. Ask whoever manages the server/database to do this, then sign in with your
                password and set up two-factor authentication again.
            </p>

        </div>
    </div>

</div>

<?= $this->endSection() ?>
