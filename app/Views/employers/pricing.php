<?php $page_title = 'Billing & Plans'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
/* Custom lightweight modal overlay */
.custom-modal {
    position: fixed;
    inset: 0;
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(10, 47, 87, 0.45);
    backdrop-filter: blur(4px);
    padding: 16px;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.custom-modal.show {
    display: flex;
    opacity: 1;
}
.custom-modal-dialog {
    background: #fff;
    border-radius: var(--radius-lg, 12px);
    box-shadow: 0 10px 30px rgba(10, 47, 87, 0.18);
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(20px);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.custom-modal.show .custom-modal-dialog {
    transform: translateY(0);
}
.custom-modal-dialog.modal-lg {
    max-width: 700px;
}
.custom-modal-dialog.modal-xl {
    max-width: 900px;
}
.custom-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border, #e2e8f2);
}
.custom-modal-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--brand-deep, #0A2F57);
    margin: 0;
}
.custom-close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--muted, #5b6577);
    line-height: 1;
    padding: 4px;
    transition: color 0.15s ease;
}
.custom-close-btn:hover {
    color: var(--brand, #0861A9);
}
.custom-modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}
.custom-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 20px;
    border-top: 1px solid var(--border, #e2e8f2);
    background: var(--bg, #f5f7fb);
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
                        <button type="button" class="emp-btn emp-btn-outline emp-btn-sm js-bundle-purchase" data-bundle='<?= esc(json_encode($bundle, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), 'attr') ?>'>Buy Bundle</button>
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
                <button class="emp-btn emp-btn-accent emp-btn-block" disabled>Active Subscription</button>
            <?php else: ?>
                <button onclick="showPurchaseModal('subscription')" class="emp-btn emp-btn-accent emp-btn-block">
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
<div class="custom-modal" id="purchaseModal">
    <div class="custom-modal-dialog modal-lg">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title" id="modalTitle">Complete Your Purchase</h5>
            <button type="button" class="custom-close-btn" onclick="closeModal('purchaseModal')">&times;</button>
        </div>
        <div class="custom-modal-body">
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
                        <option value="wallet">Wallet Balance (₦<?= number_format($walletBalance ?? 0, 2) ?>)</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="ussd">USSD</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="emp-btn emp-btn-outline" onclick="closeModal('purchaseModal')">Cancel</button>
            <button onclick="generateInvoice()" class="emp-btn emp-btn-primary">Generate Invoice & Continue</button>
        </div>
    </div>
</div>

<!-- INVOICE PREVIEW MODAL -->
<div class="custom-modal" id="invoiceModal">
    <div class="custom-modal-dialog modal-xl">
        <div class="custom-modal-header" style="background: var(--brand); color: #fff;">
            <h5 class="custom-modal-title" style="color: #fff;">INVOICE</h5>
            <button type="button" class="custom-close-btn" onclick="closeModal('invoiceModal')" style="color: #fff;">&times;</button>
        </div>

        <div class="custom-modal-body" id="invoiceContent" style="padding: 0;">
            <!-- Filled dynamically by JavaScript -->
        </div>

        <div class="custom-modal-footer">
            <button type="button" class="emp-btn emp-btn-outline" onclick="closeModal('invoiceModal')">Close</button>
            <button onclick="proceedToPayment()" class="emp-btn emp-btn-primary" style="background: var(--success); border-color: var(--success);">
                Pay Now
            </button>
        </div>
    </div>
</div>

<!-- Payment Loader -->
<div id="payment-loader" class="payment-loader">
    <div style="border: 4px solid var(--border); border-top: 4px solid var(--brand); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom: 20px;"></div>
    <img src="<?= base_url('auth/img/logo.png') ?>" class="loader-logo" alt="JobberRecruit">
    <p class="mt-3 fw-semibold" style="margin-top: 15px; font-weight: 600; color: var(--brand-deep);">Processing payment…</p>
</div>

<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button class="emp-btn emp-btn-outline" onclick="document.querySelector('.plans').scrollIntoView({behavior:'smooth'})">View Bundles</button>
<?php if ($activePlan && $activePlan->plan_type === 'subscription'): ?>
    <button class="emp-btn emp-btn-accent" disabled>Active Subscription</button>
<?php else: ?>
    <button class="emp-btn emp-btn-accent" onclick="showPurchaseModal('subscription')">
        <svg aria-hidden="true"><use href="#i-zap"/></svg> Subscribe Now
    </button>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 200);
        }
    }

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
            document.getElementById('modalTitle').textContent = 'Subscribe to ' + <?= json_encode($subscriptionPlan->name ?? "Business Pro") ?>;
        } else {
            document.getElementById('modalTitle').textContent = 'Purchase Bundle';
        }

        document.getElementById('full_name').value = <?= json_encode($user->fullname ?? $user->username ?? '') ?>;
        document.getElementById('email').value = <?= json_encode($user->email ?? '') ?>;
        document.getElementById('phone').value = "";

        const invoiceNo = 'INV-' + Date.now().toString().slice(-8);
        document.getElementById('invoice_number').value = invoiceNo;

        openModal('purchaseModal');
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
                        <h2 style="margin: 0; font-size: 28px;">${<?= json_encode($companyName ?? "Jobber Recruit") ?>}</h2>
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

        closeModal('purchaseModal');
        openModal('invoiceModal');
    }

    function proceedToPayment() {
        closeModal('invoiceModal');

        if (currentPurchase.payment_method === 'wallet') {
            const walletBalance = <?= (float)($walletBalance ?? 0.0) ?>;
            if (walletBalance < currentPurchase.amount) {
                toastr.warning('Insufficient wallet balance. Please select another payment method.');
                return;
            }

            document.getElementById('payment-loader').style.display = 'flex';
            
            // Build key value body parameters to align with WalletController API expectation
            const walletPayload = {
                type: currentPurchase.type === 'subscription' ? 'subscription' : 'bundle',
                plan_id: currentPurchase.type === 'subscription' ? <?= (int)($subscriptionPlan->id ?? 0) ?> : currentPurchase.bundle_id,
                bundle_id: currentPurchase.bundle_id,
                duration_months: currentPurchase.duration_months
            };

            fetch('<?= base_url('employer/wallet/pay-with-wallet') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(walletPayload)
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('payment-loader').style.display = 'none';
                if (data.success) {
                    toastr.success(data.message || 'Payment successful!');
                    window.location.reload();
                } else {
                    toastr.error(data.message || 'Wallet payment failed.');
                }
            })
            .catch(() => {
                document.getElementById('payment-loader').style.display = 'none';
                toastr.error('An error occurred processing payment.');
            });
            return;
        }

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
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="<?= csrf_token() ?>"]').value
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