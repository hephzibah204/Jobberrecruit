<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.plans {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: clamp(14px, 1.8vw, 20px);
    align-items: start;
}
@media (max-width: 960px) {
    .plans {
        grid-template-columns: 1fr;
    }
}
.bundle {
    display: flex;
    align-items: center;
    gap: 14px;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 15px 16px;
    transition: var(--transition);
    background: #fff;
}
.bundle:hover {
    border-color: var(--brand);
    box-shadow: var(--shadow);
}
.bundle + .bundle {
    margin-top: 12px;
}
.bundle-ic {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #fff;
}
.bundle-ic svg {
    width: 20px;
    height: 20px;
}
.bundle-info {
    flex: 1;
    min-width: 0;
}
.bundle-name {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    color: var(--brand-deep);
}
.bundle-posts {
    font-size: .74rem;
    color: var(--muted);
}
.bundle-price {
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--brand-deep);
    text-align: right;
}
.bundle-price i {
    display: block;
    font-style: normal;
    font-size: .62rem;
    font-weight: 600;
    color: var(--muted);
}
.bundle .btn {
    flex-shrink: 0;
}
@media (max-width: 560px) {
    .bundle {
        flex-wrap: wrap;
    }
    .bundle .btn {
        width: 100%;
    }
}
.pro-card {
    border: 1.5px solid var(--brand);
    border-radius: var(--radius-lg);
    overflow: hidden;
    position: relative;
    background: #fff;
}
.pro-head {
    background: radial-gradient(ellipse 60% 90% at 88% 10%, rgba(237, 144, 32, .2) 0%, transparent 55%), linear-gradient(150deg, #0A2F57 0%, #064A85 55%, #0861A9 100%);
    color: #fff;
    padding: 22px 24px;
    position: relative;
}
.pro-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    background: var(--accent);
    color: var(--brand-deep);
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
}
.pro-head h2 {
    font-size: 1.25rem;
    font-weight: 800;
}
.pro-head p {
    font-size: .8rem;
    color: rgba(255, 255, 255, .85);
    margin-top: 3px;
}
.pro-body {
    padding: 22px 24px;
}
.pro-price {
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: clamp(1.9rem, 4vw, 2.5rem);
    color: var(--brand-deep);
    text-align: center;
    line-height: 1.1;
}
.pro-price i {
    display: block;
    font-style: normal;
    font-size: .76rem;
    font-weight: 500;
    color: var(--muted);
    margin-top: 2px;
}
.feat {
    list-style: none;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 9px 16px;
    margin: 20px 0 22px;
}
@media (max-width: 560px) {
    .feat {
        grid-template-columns: 1fr;
    }
}
.feat li {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    font-size: .8rem;
    color: var(--text);
}
.feat li svg {
    width: 15px;
    height: 15px;
    color: var(--success);
    flex-shrink: 0;
    margin-top: 2px;
}
.feat li b {
    font-weight: 700;
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
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-8px);
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-head">
    <div class="page-head-left">
        <h1><svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans</h1>
        <p>Choose the hiring plan that fits how you recruit.</p>
    </div>
</div>

<div class="notice notice--info" role="status">
    <svg aria-hidden="true"><use href="#i-zap"/></svg>
    <span>You have <b><?= esc($creditBalance ?? 0) ?> job credits</b> available. Credits are used automatically when you post a job.</span>
</div>

<div class="plans">
    <!-- pay-as-you-go bundles -->
    <section class="card" aria-label="Growth bundles">
        <div class="card-head">
            <span class="card-title">
                <svg aria-hidden="true"><use href="#i-briefcase"/></svg> Growth Bundles 
                <span style="font-weight:500;color:var(--muted);font-size:.76rem">· Pay as you go</span>
            </span>
        </div>
        <div class="card-body">
            <?php if (!empty($bundles)): ?>
                <?php foreach ($bundles as $bundle): 
                    $icBg = 'linear-gradient(135deg,#8d99ab,#5b6577)';
                    if (stripos($bundle->name, 'gold') !== false) {
                        $icBg = 'linear-gradient(135deg,#ED9020,#C8770E)';
                    } elseif (stripos($bundle->name, 'diamond') !== false || stripos($bundle->name, 'blue') !== false || (int)$bundle->job_credits >= 5) {
                        $icBg = 'linear-gradient(135deg,#1d6fb8,#0861A9)';
                    }
                ?>
                    <div class="bundle">
                        <span class="bundle-ic" style="background: <?= $icBg ?>;" aria-hidden="true">
                            <svg><use href="#i-briefcase"/></svg>
                        </span>
                        <div class="bundle-info">
                            <div class="bundle-name"><?= esc($bundle->name) ?></div>
                            <div class="bundle-posts"><?= (int)$bundle->job_credits ?> job post<?= (int)$bundle->job_credits > 1 ? 's' : '' ?></div>
                        </div>
                        <div class="bundle-price">
                            &#8358;<?= number_format($bundle->price) ?>
                            <i>&#8358;<?= number_format($bundle->price_per_credit ?? ($bundle->price / $bundle->job_credits)) ?> / post</i>
                        </div>
                        <button type="button" class="btn btn-outline btn-sm js-bundle-purchase" data-bundle='<?= esc(json_encode($bundle, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), 'attr') ?>'>Buy Bundle</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty">
                    <div class="empty-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></div>
                    <h3>No Bundles Available</h3>
                    <p>There are no pay-as-you-go bundles available at the moment.</p>
                </div>
            <?php endif; ?>
            <p style="font-size:.74rem;color:var(--muted);margin-top:14px">Bundle credits never expire and are used automatically each time you post a job.</p>
        </div>
    </section>

    <!-- Subscription Plan -->
    <section class="pro-card" aria-label="Subscription plan">
        <div class="pro-head">
            <span class="pro-badge">Best value</span>
            <h2><?= esc($subscriptionPlan->name ?? 'Business Pro') ?></h2>
            <p>Unlimited job postings + premium features</p>
        </div>
        <div class="pro-body">
            <label class="lbl" for="duration">Select duration</label>
            <select class="select" id="duration" aria-label="Subscription duration" onchange="updatePrice()">
                <?php
                $tiers = is_string($pricingTiers) ? json_decode($pricingTiers, true) : ($pricingTiers ?? []);
                $price1 = $tiers[1] ?? 18000;
                $price3 = $tiers[3] ?? 48000;
                $price6 = $tiers[6] ?? 84000;
                $price12 = $tiers[12] ?? 150000;

                $durations = [
                    1 => ['label' => '1 Month', 'price' => $price1, 'per' => 'billed monthly'],
                    3 => ['label' => '3 Months', 'price' => $price3, 'per' => '&#8358;' . number_format($price3 / 3) . ' / month · billed quarterly'],
                    6 => ['label' => '6 Months', 'price' => $price6, 'per' => '&#8358;' . number_format($price6 / 6) . ' / month · billed bi-annually'],
                    12 => ['label' => '12 Months (Best Value)', 'price' => $price12, 'per' => '&#8358;' . number_format($price12 / 12) . ' / month · billed yearly']
                ];
                foreach ($durations as $months => $info):
                    $price = $info['price'];
                ?>
                    <option value="<?= $months ?>" data-price="<?= $price ?>" data-per="<?= esc($info['per']) ?>" <?= $months === 1 ? 'selected' : '' ?>>
                        <?= esc($info['label']) ?> — &#8358;<?= number_format($price) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <div class="pro-price" style="margin-top:18px">
                <span id="pro-amt">&#8358;18,000</span>
                <i id="pro-per">billed monthly</i>
            </div>
            
            <div id="savingsInfo" class="text-center mb-3 d-none" style="margin-top: 10px;">
                <span class="badge bg-success" style="background: var(--success); color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.72rem;">Save up to 25% with annual plan</span>
            </div>

            <ul class="feat">
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span><b>Unlimited</b> job postings</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>Featured at the top</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>Network Blast (115k+)</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>Anonymous posting</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>URL redirection</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>Verified Hirer badge</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>Priority support</span></li>
                <li><svg aria-hidden="true"><use href="#i-check"/></svg><span>Advanced candidate search</span></li>
            </ul>

            <?php 
            $activePlan = $myPlan ?? $currentPlan ?? null;
            if ($activePlan && $activePlan->plan_type === 'subscription'): 
            ?>
                <button class="btn btn-accent btn-block" disabled>Active Subscription</button>
            <?php else: ?>
                <button onclick="showPurchaseModal('subscription')" class="btn btn-accent btn-block">
                    <svg aria-hidden="true"><use href="#i-zap"/></svg> Subscribe Now
                </button>
            <?php endif; ?>
            <p style="font-size:.72rem;color:var(--muted);text-align:center;margin-top:10px">Renews automatically. Cancel anytime from this page.</p>
        </div>
    </section>
</div>

<div class="notice notice--warn" role="note" style="margin-top: 24px;">
    <svg aria-hidden="true"><use href="#i-shield"/></svg>
    <span><b>Note:</b> We do not publish scam jobs. All postings are reviewed. No refunds after successful payment.</span>
</div>

<!-- PURCHASE DETAILS MODAL -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border); padding: 16px 20px;">
                <h5 class="modal-title fw-bold" id="modalTitle" style="font-family: 'Sora', sans-serif; color: var(--brand-deep);">Complete Your Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; line-height: 1; cursor: pointer; color: var(--muted);">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <form id="purchaseForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="type" id="purchase_type">
                    <input type="hidden" name="bundle_id" id="bundle_id">
                    <input type="hidden" name="bundle_data" id="bundle_data">
                    <input type="hidden" name="duration_months" id="duration_months">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="lbl">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="input" required style="font-size: 14px;">
                        </div>
                        <div>
                            <label class="lbl">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="input" required style="font-size: 14px;">
                        </div>
                        <div>
                            <label class="lbl">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="phone" class="input" required style="font-size: 14px;">
                        </div>
                        <div>
                            <label class="lbl">Invoice Number</label>
                            <input type="text" id="invoice_number" class="input" readonly style="background: var(--bg); font-size: 14px;">
                        </div>
                    </div>

                    <div style="margin-top: 16px;">
                        <label class="lbl">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="select" required style="font-size: 14px;">
                            <option value="card">Card Payment (Instant)</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="ussd">USSD</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                <button onclick="generateInvoice()" class="btn btn-primary">Generate Invoice & Continue</button>
            </div>
        </div>
    </div>
</div>

<!-- INVOICE PREVIEW MODAL -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border);">
            <div class="modal-header bg-primary text-white" style="background: var(--brand); color: #white; padding: 16px 20px;">
                <h5 class="modal-title fw-bold" style="font-family: 'Sora', sans-serif; color: #fff;">
                    INVOICE
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; line-height: 1; cursor: pointer; color: #fff;">&times;</button>
            </div>

            <div class="modal-body p-0" id="invoiceContent" style="padding: 0;">
                <!-- Filled dynamically by JavaScript -->
            </div>

            <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                <button onclick="proceedToPayment()" class="btn btn-primary" style="background: var(--success); border-color: var(--success);">
                    Pay Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Loader -->
<div id="payment-loader" class="payment-loader">
    <div style="border: 4px solid var(--border); border-top: 4px solid var(--brand); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom: 20px;"></div>
    <img src="<?= base_url('auth/img/logo.png') ?>" class="loader-logo" alt="JobberRecruit">
    <p class="mt-3 fw-semibold" style="margin-top: 15px; font-weight: 600; color: var(--brand-deep);">Processing payment…</p>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button class="btn btn-outline" onclick="document.querySelector('.plans').scrollIntoView({behavior:'smooth'})">View Bundles</button>
<?php if ($activePlan && $activePlan->plan_type === 'subscription'): ?>
    <button class="btn btn-accent" disabled>Active Subscription</button>
<?php else: ?>
    <button class="btn btn-accent" onclick="showPurchaseModal('subscription')">
        <svg aria-hidden="true"><use href="#i-zap"/></svg> Subscribe Now
    </button>
<?php endif; ?>
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
            }
        });
    });

    function showPurchaseModal(type, bundle = null) {
        currentPurchase = {
            type
        };

        if (type === 'bundle' && bundle) {
            currentPurchase.bundle = bundle;
            document.getElementById('bundle_id').value = bundle.id;
            document.getElementById('bundle_data').value = JSON.stringify(bundle);
        } else {
            document.getElementById('bundle_id').value = '';
            document.getElementById('bundle_data').value = '';
        }

        document.getElementById('purchase_type').value = type;

        if (type === 'subscription') {
            const months = parseInt(document.getElementById('duration').value);
            currentPurchase.duration_months = months;
            document.getElementById('duration_months').value = months;
            document.getElementById('modalTitle').textContent = 'Subscribe to <?= esc($subscriptionPlan->name ?? "Business Pro") ?>';
        } else {
            document.getElementById('modalTitle').textContent = 'Purchase Bundle';
        }

        document.getElementById('full_name').value = "<?= esc($user->fullname ?? $user->username ?? '') ?>";
        document.getElementById('email').value = "<?= esc($user->email ?? '') ?>";
        document.getElementById('phone').value = "";

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

            currentPurchase.duration_months = parseInt(document.getElementById('duration').value);
            amount = tiers[currentPurchase.duration_months] || (basePrice * currentPurchase.duration_months);

            itemDescription = `<?= esc($subscriptionPlan->name ?? 'Business Pro') ?> Subscription (${currentPurchase.duration_months} Month${currentPurchase.duration_months > 1 ? 's' : ''})`;
            itemDetails = `Unlimited job postings + premium features for ${currentPurchase.duration_months} month${currentPurchase.duration_months > 1 ? 's' : ''}`;
        } else if (currentPurchase.type === 'bundle') {
            const bundle = currentPurchase.bundle_data;
            amount = parseFloat(bundle.price);
            itemDescription = bundle.name;
            itemDetails = `${bundle.job_credits} Job Posting Credits`;
        }

        currentPurchase.amount = amount;

        let html = `
        <div style="max-width: 800px; margin: 20px auto; background: white; font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6;">
            <!-- Company Header -->
            <div style="background: linear-gradient(135deg, #0861A9, #064A85); color: white; padding: 25px 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 style="margin: 0; font-size: 28px;">${'<?= esc($companyName ?? "Jobber Recruit") ?>'}</h2>
                        <p style="margin: 8px 0 0; opacity: 0.9; font-size: 15px;">
                            The new face of job hunting • Lagos, Nigeria
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <h1 style="margin: 0; font-size: 32px; font-weight: 700; color: #fff;">INVOICE</h1>
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
                    <div style="font-size: 28px; font-weight: 700; color: #0861A9;">
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
                        channels: [res.method],
                        metadata: res.metadata,
                        callback: function(response) {
                            window.location.href = "<?= base_url('employer/verify-payment') ?>?reference=" + response.reference;
                        },
                        onClose: function() {
                            alert('Payment cancelled');
                        }
                    });
                    handler.openIframe();
                } else {
                    alert(res.message || 'Payment initiation failed');
                }
            })
            .catch(() => {
                document.getElementById('payment-loader').style.display = 'none';
                alert('Network error');
            });
    }

    function updatePrice() {
        const d = document.getElementById('duration');
        const a = document.getElementById('pro-amt');
        const p = document.getElementById('pro-per');
        const o = d.options[d.selectedIndex];
        const price = Number(o.getAttribute('data-price'));
        const months = Number(o.value);
        
        a.textContent = '₦' + price.toLocaleString('en-NG');
        p.innerHTML = o.getAttribute('data-per');
        document.getElementById('savingsInfo').classList.toggle('d-none', months < 6);
    }

    window.onload = updatePrice;
</script>
<?= $this->endSection() ?>