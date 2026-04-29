<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<style>
    .pricing-card {
        transition: all .3s ease-in-out;
    }

    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }

    .price-display {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1;
    }

    .payment-loader {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.96);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .loader-logo {
        width: 120px;
        animation: floatUp 2.2s ease-in-out infinite;
    }

    @keyframes floatUp {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }
</style>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Choose Your Hiring Plan</h4>
            <h6 class="text-muted">Unlimited postings with <?= esc($subscriptionPlan->name ?? 'Business Pro') ?> • Flexible durations</h6>

            <p class="text-dark mt-3">
                <span class="text-danger fw-bold">Note:</span> We do not publish scam jobs. No refunds after successful payment.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body pb-5">

            <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
                <div>
                    <strong>Available Job Credits:</strong>
                    <span class="fw-bold"><?= number_format($creditBalance ?? 0) ?></span>
                </div>
            </div>

            <div class="row justify-content-center g-4">

                <!-- Bundles -->
                <div class="col-xl-6">
                    <div class="card h-100 pricing-card shadow-sm border-warning">
                        <div class="card-header bg-warning text-white text-center py-4">
                            <h4>Growth Bundles (Pay-As-You-Go)</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <?php foreach ($bundles as $bundle): ?>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary py-3 text-start js-bundle-purchase"
                                        data-bundle='<?= esc(json_encode($bundle, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), 'attr') ?>'>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?= esc($bundle->name) ?></strong><br>
                                                <small><?= (int)$bundle->job_credits ?> Job Posts</small>
                                            </div>
                                            <strong>₦<?= number_format($bundle->price) ?></strong>
                                        </div>
                                    </button>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription -->
                <div class="col-xl-6">
                    <div class="card h-100 pricing-card shadow <?= ($currentPlan && $currentPlan->plan_type === 'subscription') ? 'current-plan-card' : '' ?>">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h4><?= esc($subscriptionPlan->name ?? 'Business Pro') ?></h4>
                            <p>Unlimited Job Postings + Premium Features</p>
                        </div>
                        <div class="card-body">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Select Duration</label>
                                <select id="durationSelect" class="form-select form-select-lg" onchange="updatePrice()">
                                    <?php
                                    $tiers = is_string($pricingTiers) ? json_decode($pricingTiers, true) : ($pricingTiers ?? []);
                                    $durations = [1 => '1 Month', 3 => '3 Months', 6 => '6 Months', 12 => '12 Months (Best Value)'];
                                    foreach ($durations as $months => $label):
                                        $price = $tiers[$months] ?? ($months * 18000);
                                    ?>
                                        <option value="<?= $months ?>" data-price="<?= $price ?>">
                                            <?= $label ?> — ₦<?= number_format($price) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="text-center mb-4">
                                <div id="priceDisplay" class="price-display text-primary">₦18,000</div>
                                <p id="priceSubtitle" class="text-muted">billed monthly</p>
                            </div>

                            <div id="savingsInfo" class="text-center mb-4 d-none">
                                <span class="badge bg-success">Save up to 25% with annual plan</span>
                            </div>

                            <ul class="list-unstyled row g-2">
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> <strong>Unlimited</strong> job postings</li>
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> Featured at the top</li>
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> Network Blast (115k+)</li>
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> Anonymous posting</li>
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> URL Redirection</li>
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> Verified Hirer Badge</li>
                                <li class="col-12"><i class="ti ti-check text-success me-2"></i> Priority Support</li>
                            </ul>

                            <div class="mt-4">
                                <?php if ($currentPlan && $currentPlan->plan_type === 'subscription'): ?>
                                    <button class="btn btn-success w-100 py-3" disabled>Active Subscription</button>
                                <?php else: ?>
                                    <button onclick="showPurchaseModal('subscription')" class="btn btn-primary w-100 py-3 btn-lg">
                                        Subscribe Now
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- PURCHASE DETAILS MODAL -->
<div class="modal fade" id="purchaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Complete Your Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form id="purchaseForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="type" id="purchase_type">
                    <input type="hidden" name="bundle_id" id="bundle_id">
                    <input type="hidden" name="bundle_data" id="bundle_data">
                    <input type="hidden" name="duration_months" id="duration_months">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" id="invoice_number" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="card">Card Payment (Instant)</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="ussd">USSD</option>
                        </select>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                <button onclick="generateInvoice()" class="btn btn-primary">Generate Invoice & Continue</button>
            </div>
        </div>
    </div>
</div>

<!-- INVOICE PREVIEW MODAL -->
<div class="modal fade" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="ti ti-file-invoice me-2"></i> INVOICE
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0" id="invoiceContent">
                <!-- Filled dynamically by JavaScript -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Close</button>
                <button onclick="proceedToPayment()" class="btn btn-success px-5">
                    <i class="ti ti-credit-card"></i> Pay Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Loader -->
<div id="payment-loader" class="payment-loader">
    <div class="spinner-border text-primary mb-3"></div>
    <img src="<?= base_url('assets/imgs/template/logo.png') ?>" class="loader-logo">
    <p class="mt-3 fw-semibold">Processing payment…</p>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    let currentPurchase = {};

    document.querySelectorAll('.js-bundle-purchase').forEach((button) => {
        button.addEventListener('click', function() {
            const bundleJson = this.dataset.bundle;

            if (!bundleJson) {
                return;
            }

            try {
                showPurchaseModal('bundle', JSON.parse(bundleJson));
            } catch (error) {
                console.error('Unable to parse bundle payload', error);
                toastr.error('Unable to load bundle details. Please refresh and try again.');
            }
        });
    });

    function showPurchaseModal(type, bundle = null) {
        currentPurchase = {
            type
        };

        // Handle bundle properly
        if (type === 'bundle' && bundle) {
            currentPurchase.bundle = bundle;
            document.getElementById('bundle_id').value = bundle.id;
            document.getElementById('bundle_data').value = JSON.stringify(bundle);
        } else {
            document.getElementById('bundle_id').value = '';
            document.getElementById('bundle_data').value = '';
        }

        document.getElementById('purchase_type').value = type;

        // Handle subscription
        if (type === 'subscription') {
            const months = parseInt(document.getElementById('durationSelect').value);
            currentPurchase.duration_months = months;
            document.getElementById('duration_months').value = months;
            document.getElementById('modalTitle').textContent = 'Subscribe to Business Pro';
        } else {
            document.getElementById('modalTitle').textContent = 'Purchase Bundle';
        }

        // Auto-fill user info
        document.getElementById('full_name').value = "<?= esc($user->fullname ?? '') ?>";
        document.getElementById('email').value = "<?= esc($user->email ?? '') ?>";
        document.getElementById('phone').value = "";

        // Generate invoice number
        const invoiceNo = 'INV-' + Date.now().toString().slice(-8);
        document.getElementById('invoice_number').value = invoiceNo;

        new bootstrap.Modal(document.getElementById('purchaseModal')).show();
    }

    function generateInvoice() {
        const form = document.getElementById('purchaseForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);

        currentPurchase = {
            type: formData.get('type'),
            bundle_id: formData.get('bundle_id'),
            bundle_data: formData.get('bundle_data') ? JSON.parse(formData.get('bundle_data')) : null,
            duration_months: parseInt(formData.get('duration_months') || 0),
            full_name: formData.get('full_name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            payment_method: formData.get('payment_method'),
            invoice_number: document.getElementById('invoice_number').value,
            date: new Date().toLocaleDateString('en-GB', {
                weekday: 'short',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            })
        };

        let amount = 0;
        let itemDescription = '';
        let itemDetails = '';

        if (currentPurchase.type === 'subscription') {
            const tiers = <?= json_encode($pricingTiers ?? []) ?>;
            const basePrice = <?= (int)($subscriptionPlan->base_price ?? 18000) ?>;

            amount = tiers[currentPurchase.duration_months] || (basePrice * currentPurchase.duration_months);

            currentPurchase.duration_months = parseInt(document.getElementById('durationSelect').value);

            itemDescription = `<?= esc($subscriptionPlan->name ?? 'Business Pro') ?> Subscription (${currentPurchase.duration_months} Month${currentPurchase.duration_months > 1 ? 's' : ''})`;

            itemDetails = `Unlimited job postings + premium features for ${currentPurchase.duration_months} month${currentPurchase.duration_months > 1 ? 's' : ''}`;
        } else if (currentPurchase.type === 'bundle') {
            const bundle = currentPurchase.bundle_data;

            console.log(bundle);

            amount = parseFloat(bundle.price);
            itemDescription = bundle.name;
            itemDetails = `${bundle.job_credits} Job Posting Credits`;
        }

        currentPurchase.amount = amount;

        // Full Professional Invoice HTML
        let html = `
        <div style="max-width: 800px; margin: 20px auto; background: white; font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6;">
            
            <!-- Company Header -->
            <div style="background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; padding: 25px 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 style="margin: 0; font-size: 28px;">${'<?= esc($companyName ?? "Jobber Recruit") ?>'}</h2>
                        <p style="margin: 8px 0 0; opacity: 0.9; font-size: 15px;">
                            The new face of job hunting • Lagos, Nigeria
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <h1 style="margin: 0; font-size: 32px; font-weight: 700;">INVOICE</h1>
                        <p style="margin: 5px 0 0; font-size: 16px;">#${currentPurchase.invoice_number}</p>
                    </div>
                </div>
            </div>

            <div style="padding: 30px;">

                <!-- Bill To & Invoice Info -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 35px;">
                    <div>
                        <strong style="color: #555; font-size: 13px;">BILL TO</strong><br>
                        <strong style="font-size: 17px;">${currentPurchase.full_name}</strong><br>
                        ${currentPurchase.email}<br>
                        ${currentPurchase.phone}
                    </div>
                    <div style="text-align: right;">
                        <strong style="color: #555; font-size: 13px;">INVOICE DATE</strong><br>
                        ${currentPurchase.date}<br><br>
                        <strong style="color: #555; font-size: 13px;">PAYMENT METHOD</strong><br>
                        ${currentPurchase.payment_method.replace('_', ' ').toUpperCase()}
                    </div>
                </div>

                <!-- Item Table -->
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Description</th>
                            <th style="padding: 14px 12px; text-align: right; border-bottom: 2px solid #dee2e6;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 18px 12px; border-bottom: 1px solid #eee;">
                                <strong>${itemDescription}</strong><br>
                                <small style="color: #666;">${itemDetails}</small>
                            </td>
                            <td style="padding: 18px 12px; text-align: right; border-bottom: 1px solid #eee; font-size: 18px; font-weight: 600;">
                                ₦${amount.toLocaleString()}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Total -->
                <div style="text-align: right; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 15px; color: #555;">Total Amount Due</div>
                    <div style="font-size: 28px; font-weight: 700; color: #0d6efd;">
                        ₦${amount.toLocaleString()}
                    </div>
                </div>

                <!-- Footer Note -->
                <div style="margin-top: 40px; padding-top: 20px; border-top: 1px dashed #ccc; font-size: 13.5px; color: #666;">
                    <strong>Payment Terms:</strong> Full payment is required upon receipt of invoice.<br>
                    Card / Bank Transfer / USSD payments are processed securely via Paystack and will be confirmed ASAP.
                </div>

            </div>
        </div>
    `;

        document.getElementById('invoiceContent').innerHTML = html;

        // Close details modal and show invoice
        bootstrap.Modal.getInstance(document.getElementById('purchaseModal')).hide();
        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
    }

    function proceedToPayment() {
        bootstrap.Modal.getInstance(document.getElementById('invoiceModal')).hide();
        document.getElementById('payment-loader').style.display = 'flex';

        const payload = {
            type: currentPurchase.type,
            duration_months: currentPurchase.duration_months,
            bundle_id: currentPurchase.bundle_id,
            full_name: currentPurchase.full_name,
            email: currentPurchase.email,
            phone: currentPurchase.phone,
            invoice_number: currentPurchase.invoice_number,
            payment_method: currentPurchase.payment_method
        };

        console.log(payload);

        fetch("<?= base_url('employer/initiate-payment') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(res => {
                document.getElementById('payment-loader').style.display = 'none';

                if (res.success && res.paystack) {
                    let handler = PaystackPop.setup({
                        key: res.paystack,
                        email: res.email,
                        amount: res.amount,
                        ref: res.reference,
                        channel: [res.method],
                        metadata: res.metadata,
                        callback: function(response) {
                            window.location.href = "<?= base_url('employer/verify-payment') ?>?reference=" + response.reference;
                        },
                        onClose: function() {
                            toastr.warning('Payment cancelled');
                        }
                    });
                    handler.openIframe();
                } else {
                    toastr.error(res.message || 'Payment initiation failed');
                }
            })
            .catch(() => {
                document.getElementById('payment-loader').style.display = 'none';
                toastr.error('Network error');
            });
    }

    function updatePrice() {
        const months = parseInt(document.getElementById('durationSelect').value);
        const tiers = <?= json_encode($pricingTiers) ?>;
        const price = tiers[months] || (18000 * months);

        document.getElementById('priceDisplay').textContent = '₦' + price.toLocaleString();
        document.getElementById('priceSubtitle').textContent = months === 1 ? 'billed monthly' : `billed once for ${months} months`;
        document.getElementById('savingsInfo').classList.toggle('d-none', months < 6);
    }

    window.onload = updatePrice;
</script>
<?= $this->endSection() ?>