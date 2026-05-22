<?= $this->extend('layouts/app') ?>

<?= $this->section('section') ?>
<div class="content">
    <div class="page-header mb-4">
        <div class="page-title">
            <h4 class="fw-bold">System Feature Gates & Optimization</h4>
            <p class="text-muted">Control user access to portal modules dynamically and optimize transactional email performance.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-x me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('admin/features/save') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="row">
            <!-- Left Panel: Module Toggles -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                            <i class="ti ti-toggle-right text-primary me-2"></i> Portal Feature Modules
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- E-Learning Toggle -->
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">E-Learning & Course Portal</h6>
                                <p class="text-muted fs-13 mb-0">Toggle the candidate course modules, certifications, and certificates download.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="feature_elearning" value="1" <?= $feature_elearning ? 'checked' : '' ?>>
                            </div>
                        </div>

                        <!-- Webinars Toggle -->
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Career Webinars</h6>
                                <p class="text-muted fs-13 mb-0">Allow candidates to view and register for upcoming and scheduled career webinars.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="feature_webinars" value="1" <?= $feature_webinars ? 'checked' : '' ?>>
                            </div>
                        </div>

                        <!-- AI Resume Builder Toggle -->
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">AI Resume Builder & Improver</h6>
                                <p class="text-muted fs-13 mb-0">Allow candidates to build and optimize their CV records using the automated AI generator.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="feature_ai_resume" value="1" <?= $feature_ai_resume ? 'checked' : '' ?>>
                            </div>
                        </div>

                        <!-- AI Career Tools Toggle -->
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">AI Career Tools Suite</h6>
                                <p class="text-muted fs-13 mb-0">Enable AI Mock Interviews, Salary Negotiation practice, and AI Career Advice modules.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="feature_ai_career_tools" value="1" <?= $feature_ai_career_tools ? 'checked' : '' ?>>
                            </div>
                        </div>

                        <!-- Direct Messaging Toggle -->
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Candidate & Employer Direct Messaging</h6>
                                <p class="text-muted fs-13 mb-0">Allow direct messages between candidates and employers during the recruitment lifecycle.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="feature_messaging" value="1" <?= $feature_messaging ? 'checked' : '' ?>>
                            </div>
                        </div>

                        <!-- Referrals Toggle -->
                        <div class="d-flex justify-content-between align-items-start pb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Affiliate & Referral System</h6>
                                <p class="text-muted fs-13 mb-0">Allow users to access the affiliate dash and refer other users for wallet credits.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="feature_referrals" value="1" <?= $feature_referrals ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                            <i class="ti ti-mail-forwarded text-primary me-2"></i> Email & Performance Tuning
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Email Queue Toggle -->
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Asynchronous Email Queueing</h6>
                                <p class="text-muted fs-13 mb-0">
                                    When enabled, system emails are processed in a background queue instead of synchronously waiting during requests.
                                </p>
                                <span class="badge bg-success-transparent mt-2"><i class="ti ti-bolt me-1"></i> Highly Recommended for Page Load Speed</span>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="email_use_queue" value="1" <?= $email_use_queue ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Paywall Settings & Action -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <!-- AI monetization Settings -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                            <i class="ti ti-premium-badge text-warning me-2"></i> AI Monetization
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">AI Paid Plan Gate (Premium Mode)</h6>
                                <p class="text-muted fs-12 mb-0">Restrict AI Resume Builder and Career Tools to users with active premium subscriptions.</p>
                            </div>
                            <div class="form-check form-switch fs-20">
                                <input class="form-check-input" type="checkbox" name="ai_tools_paid_mode" value="1" <?= $ai_tools_paid_mode ? 'checked' : '' ?>>
                            </div>
                        </div>

                        <?php if ($site_free_mode): ?>
                            <div class="alert alert-warning border-0 rounded-3 mb-0 p-3 fs-12 d-flex align-items-start" style="background: rgba(255, 193, 7, 0.08);">
                                <i class="ti ti-alert-triangle text-warning fs-18 me-2 mt-0.5"></i>
                                <div>
                                    <strong>Site Free Mode is Active!</strong>
                                    <span class="text-muted d-block mt-1">Free Mode overrides plan checks globally. To enforce the AI premium gate, first disable Free Mode in Plan configuration settings.</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info Help Card -->
                <div class="card bg-gradient-info text-white border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-white mb-3 d-flex align-items-center">
                            <i class="ti ti-info-circle me-1.5 fs-18"></i> Dynamically Gated Access
                        </h6>
                        <p class="fs-13 mb-3 text-white-50">
                            Disabling a portal module automatically redirects unauthorized access to the candidate dashboard, showing a friendly disabled feature banner.
                        </p>
                        <p class="fs-13 mb-0 text-white-50">
                            Menu entries in sidebars and headers will hide automatically to maintain interface integrity.
                        </p>
                    </div>
                </div>

                <!-- Action Button Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3">
                            <i class="ti ti-device-floppy me-2"></i> Save Configurations
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
