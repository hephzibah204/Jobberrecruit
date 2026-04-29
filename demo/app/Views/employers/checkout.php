<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Checkout</h4>
            <h6>Confirm your subscription details</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <?php if (!empty($message)): ?>
                <div class="alert alert-info mb-4"><?= esc($message) ?></div>
            <?php endif; ?>

            <div class="row g-5">

                <!-- LEFT: Main Subscription Info & Payment -->
                <div class="col-lg-7">

                    <?php if ($plan->slug === 'enterprise'): ?>
                        <div class="text-center py-5">
                            <h3 class="mb-3"><?= esc($plan->name) ?></h3>
                            <p class="text-muted lead"><?= esc($plan->description) ?></p>

                            <div class="mt-4">
                                <p class="mb-4">
                                    Our Enterprise plan is customized to fit your organization's unique hiring needs.
                                </p>
                                <a href="<?= base_url('employer/contact/sales') ?>" class="btn btn-primary btn-lg">
                                    Contact Sales Team
                                </a>
                            </div>
                        </div>

                    <?php else: ?>

                        <?php
                        $amount = isset($upgradeCost) ? $upgradeCost : $plan->price;
                        ?>

                        <div class="bg-light rounded-3 p-4 mb-4">
                            <h4 class="mb-3"><?= esc($plan->name) ?></h4>
                            <p class="text-muted mb-0"><?= esc($plan->description) ?></p>
                        </div>

                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <strong class="fs-4">Amount Due Now</strong>
                                <?php if (!empty($explain)): ?>
                                    <p class="text-muted small mb-0"><?= esc($explain) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <?php if ($amount <= 0): ?>
                                    <h3 class="text-success mb-0">₦0.00</h3>
                                <?php else: ?>
                                    <h3 class="text-primary mb-0">₦<?= number_format($amount, 2) ?></h3>
                                <?php endif; ?>
                            </div>
                        </div>

                        <form method="POST" id="checkoutForm" action="<?= base_url('employer/pricing/process') ?>">
                            <?= csrf_field() ?>

                            <input type="hidden" name="plan_id" value="<?= esc($plan->id) ?>">
                            <input type="hidden" name="amount" value="<?= esc($amount) ?>">
                            <input type="hidden" name="billing_cycle" value="<?= esc($billingCycle) ?>">

                            <div class="d-grid gap-3 mt-4">
                                <button type="button" id="payBtn" class="btn btn-primary btn-lg">
                                    Pay & Subscribe
                                </button>

                                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-outline-secondary">
                                    Cancel and Return
                                </a>
                            </div>
                        </form>

                    <?php endif; ?>

                </div>

                <!-- RIGHT: Plan Features Summary -->
                <div class="col-lg-5">

                    <div class="bg-light rounded-3 p-4 h-100">
                        <h5 class="fw-bold mb-4">Plan Summary</h5>

                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between py-3 border-bottom">
                                <span>Job Posting Limit</span>
                                <strong><?= $plan->job_limit == -1 ? 'Unlimited' : esc($plan->job_limit) ?></strong>
                            </li>
                            <li class="d-flex justify-content-between py-3 border-bottom">
                                <span>Featured Job Limit</span>
                                <strong><?= $plan->featured_limit == -1 ? 'Unlimited' : esc($plan->featured_limit) ?></strong>
                            </li>
                            <li class="d-flex justify-content-between py-3 border-bottom">
                                <span>Job Post Duration</span>
                                <strong><?= esc($plan->duration) ?> days</strong>
                            </li>
                            <li class="d-flex justify-content-between py-3">
                                <span>Billing Cycle</span>
                                <strong>Monthly</strong>
                            </li>
                        </ul>

                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                Secure payment powered by Paystack.<br>
                                Your subscription will auto-renew monthly unless cancelled.
                            </small>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    document.querySelector('#payBtn').addEventListener('click', function(e) {
        e.preventDefault();

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';

        const formData = new FormData(document.querySelector('#checkoutForm'));

        fetch('<?= base_url('employer/pricing/process-ajax') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Something went wrong');
                }

                PaystackPop.setup({
                    key: data.publicKey,
                    email: data.email,
                    amount: data.amount,
                    plan: data.plan,
                    ref: data.reference,
                    callback: function(response) {
                        verifyPayment(response.reference);
                    },
                    onClose: function() {
                        btn.disabled = false;
                        btn.innerHTML = 'Pay & Subscribe';
                    }
                }).openIframe();
            })
            .catch(err => {
                toastr.error(err.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Pay & Subscribe';
            });
    });

    function verifyPayment(reference) {
        console.log(reference);
        fetch('<?= base_url('employer/pricing/verify-ajax') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'reference=' + encodeURIComponent(reference)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= base_url('employer/pricing') ?>'; // or your success page
                } else {
                    toastr.error(data.message || 'Payment verification failed');
                }
            });
    }
</script>
<?= $this->endSection() ?>