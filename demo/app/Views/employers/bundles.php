<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<style>
    body.loading {
        overflow: hidden;
    }

    .pricing-card {
        transition: all .25s ease-in-out;
    }

    .pricing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    /* Fullscreen overlay */
    .payment-loader {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.96);
        z-index: 99999;

        display: none;
        /* show via JS */
        align-items: center;
        justify-content: center;
        flex-direction: column;

        text-align: center;
    }

    /* Logo animation */
    .loader-logo {
        width: 120px;
        max-width: 60%;
        margin-top: 10px;
        will-change: transform;

        animation: floatUp 2.2s ease-in-out infinite;
    }

    /* Optional: smoother spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Keyframes */
    @keyframes pulseScale {
        0% {
            transform: scale(1);
            opacity: 0.85;
        }

        50% {
            transform: scale(1.08);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 0.85;
        }
    }

    @keyframes floatUp {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }

        100% {
            transform: translateY(0);
        }
    }

    @keyframes slowRotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* .loader-logo {
        animation: slowRotate 6s linear infinite;
    } */
</style>

<div class="content">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Job Bundles</h4>
            <h6>Buy job credits for urgent or bulk hiring</h6>

            <p class="text-dark mt-3">
                <span class="text-danger fw-bold">Note: </span> We will not publish scam jobs or non-existent "company names" and we will not offer refunds.
            </p>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session('success') ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session('error') ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body pb-5">

            <!-- CREDIT BALANCE -->
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <strong>Available Job Credits:</strong>
                    <?= number_format($creditBalance, 0) ?>
                </div>
                <a href="<?= base_url('employer/post-job') ?>" class="btn btn-sm btn-primary">
                    Post a Job
                </a>
            </div>

            <!-- BUNDLES GRID -->
            <div class="row justify-content-center g-4 mt-3">

                <?php foreach ($bundles as $bundle): ?>

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="card pricing-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center d-flex flex-column">
                                <?php if ($recommendedBundle && $recommendedBundle['id'] === $bundle['id']): ?>
                                    <span class="badge bg-primary mb-2">Recommended</span>
                                <?php endif; ?>

                                <h5 class="fw-bold mb-1">
                                    <?= esc($bundle['name']) ?>
                                </h5>

                                <p class="text-muted mb-3">
                                    <?= $bundle['credits'] ?> Job Credits
                                </p>

                                <h2 class="fw-bold text-primary mb-0">
                                    ₦<?= number_format($bundle['price']) ?>
                                </h2>

                                <small class="text-muted mb-4 d-block">
                                    ₦<?= number_format($bundle['cost_per_credit']) ?> per credit
                                </small>

                                <div class="mt-auto">
                                    <button
                                        class="btn btn-outline-primary w-100 buy-bundle-btn"
                                        data-code="<?= esc($bundle['code']) ?>">
                                        Buy Bundle
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

            <!-- EXPLANATION -->
            <div class="mt-5 text-center">
                <p class="text-muted">
                    Job bundles give you extra credits that can be used anytime.
                    Perfect for urgent hiring or when your monthly plan credits run out.
                </p>
            </div>

            <div class="mt-5">
                <h5 class="fw-bold mb-3">Bundle Purchase History</h5>

                <?php if (empty($bundleHistory)): ?>
                    <p class="text-muted">No bundle purchases yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Credits</th>
                                    <th>Reference</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bundleHistory as $row): ?>
                                    <tr>
                                        <td><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                        <td><?= $row['credits'] ?></td>
                                        <td>
                                            <code><?= esc($row['reference']) ?></code>
                                        </td>
                                        <td><?= esc($row['description']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif ?>
            </div>

        </div>
    </div>
</div>

<div id="payment-loader" class="payment-loader">
    <div class="spinner-border text-primary mb-3" role="status"></div>

    <img
        src="<?= base_url('assets/imgs/template/logo.png'); ?>"
        alt="Processing"
        class="loader-logo img-fluid">

    <p class="mt-3 text-muted fw-semibold">
        Processing payment… please wait
    </p>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    $(function() {

        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 5000
        };

        $('.buy-bundle-btn').on('click', function() {
            const btn = $(this);
            const bundleCode = btn.data('code');

            btn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: "<?= base_url('employer/bundles/buy') ?>/" + bundleCode,
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },

                success: function(res) {

                    if (!res.success) {
                        toastr.error(res.message || 'Unable to proceed');
                        btn.prop('disabled', false).text('Buy Bundle');
                        return;
                    }

                    // WALLET ONLY
                    if (!res.paystack) {
                        toastr.success(res.message || 'Bundle purchased');
                        window.location.reload();
                        return;
                    }

                    // PAYSTACK POPUP
                    let handler = PaystackPop.setup({
                        key: res.public_key,
                        email: res.email,
                        amount: res.amount,
                        ref: res.reference,
                        metadata: res.metadata,

                        callback: function(response) {
                            $('body').addClass('loading');
                            $('#payment-loader').fadeIn(150);

                            verifyPayment(response.reference);
                        },

                        onClose: function() {
                            toastr.warning('Payment cancelled');
                            btn.prop('disabled', false).text('Buy Bundle');
                        }
                    });

                    handler.openIframe();
                },

                error: function() {
                    toastr.error('Network error');
                    btn.prop('disabled', false).text('Buy Bundle');
                }
            });
        });

        function verifyPayment(reference) {

            $.ajax({
                url: "<?= base_url('employer/bundles/payments/verify') ?>",
                type: 'POST',
                data: {
                    reference
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(res) {

                    if (res.success && res.verified) {
                        toastr.success('Payment confirmed');
                        window.location.reload();
                        return;
                    }

                    setTimeout(() => verifyPayment(reference), 2500);
                },

                error: function() {
                    setTimeout(() => verifyPayment(reference), 3000);
                }
            });
        }

    });
</script>
<?= $this->endSection() ?>