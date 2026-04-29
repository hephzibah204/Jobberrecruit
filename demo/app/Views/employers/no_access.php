<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="text-center mb-5">
                    <i class="ti ti-credit-card-off" style="font-size: 5.5rem; color: #e74c3c; opacity: 0.9;"></i>
                    <h1 class="fw-bold mt-4">Can't Post Job Yet</h1>
                    <p class="lead text-muted">You need either an active subscription or job credits to create a new job post.</p>
                </div>

                <div class="card shadow">
                    <div class="card-body p-5">

                        <?php if (($creditBalance ?? 0) > 0): ?>
                            <div class="alert alert-info">
                                You still have <strong><?= number_format($creditBalance) ?> job credits</strong>,
                                but you need an active subscription for unlimited posting.
                            </div>
                        <?php endif; ?>

                        <h5 class="fw-semibold mb-4 text-center">Choose an option to continue posting jobs:</h5>

                        <div class="row g-4">

                            <!-- Bundle Option -->
                            <div class="col-md-6">
                                <div onclick="window.location.href='<?= base_url('employer/pricing') ?>'"
                                    class="card h-100 border-warning text-center pricing-card" style="cursor:pointer;">
                                    <div class="card-body py-5">
                                        <i class="ti ti-stack text-warning mb-3" style="font-size: 3rem;"></i>
                                        <h4 class="fw-bold">Buy Job Credits</h4>
                                        <p class="text-muted mb-4">Flexible one-time bundles</p>
                                        <button class="btn btn-warning w-100 py-3 text-dark">Buy Bundle →</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Subscription Option -->
                            <div class="col-md-6">
                                <div onclick="window.location.href='<?= base_url('employer/pricing') ?>'"
                                    class="card h-100 border-primary text-center pricing-card" style="cursor:pointer;">
                                    <div class="card-body py-5">
                                        <i class="ti ti-rocket text-primary mb-3" style="font-size: 3rem;"></i>
                                        <h4 class="fw-bold"><?= $subscriptionPlan->name ?> Subscription</h4>
                                        <p class="text-muted mb-4">Unlimited job posts + premium features</p>
                                        <button class="btn btn-primary w-100 py-3">Choose Plan →</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-5">
                            <a href="<?= base_url('employer/pricing') ?>" class="btn btn-lg btn-primary px-5 py-3">
                                Go to Pricing Page
                            </a>
                        </div>

                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        Questions? Contact support via WhatsApp or email
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>