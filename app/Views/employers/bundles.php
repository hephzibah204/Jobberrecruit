<?php $page_title = 'Job Bundles'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<div class="page-head">
    <div>
        <h1><svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans</h1>
        <p>Choose the hiring plan that fits how you recruit.</p>
    </div>
</div>

<?php if (session()->has('success')): ?>
    <div class="notice notice--info" role="status">
        <svg aria-hidden="true"><use href="#i-check-c"/></svg>
        <span><?= esc(session('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="notice notice--warn" role="status">
        <svg aria-hidden="true"><use href="#i-shield"/></svg>
        <span><?= esc(session('error')) ?></span>
    </div>
<?php endif; ?>

<div class="notice notice--info" role="status">
    <svg aria-hidden="true"><use href="#i-zap"/></svg>
    <span>You have <b><?= number_format($creditBalance ?? 0) ?> job credits</b> available. Credits are used automatically when you post a job.</span>
</div>

<div class="plans">
    <!-- pay-as-you-go bundles -->
    <section class="card" aria-label="Growth bundles">
        <div class="card-head">
            <span class="card-title">
                <svg aria-hidden="true"><use href="#i-briefcase"/></svg>
                Growth Bundles <span style="font-weight:500;color:var(--muted);font-size:.76rem">· Pay as you go</span>
            </span>
        </div>
        <div class="card-body">
            <?php if (empty($bundles)): ?>
                <div class="empty-state">
                    <div class="empty-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></div>
                    <h3>No bundles available</h3>
                    <p>There are no pay-as-you-go bundles configured at the moment.</p>
                </div>
            <?php else: ?>                <?php foreach ($bundles as $bundle): ?>
                    <?php
                    $bg_grad = 'linear-gradient(135deg,#8d99ab,#5b6577)';
                    if (stripos($bundle->name, 'diamond') !== false) {
                        $bg_grad = 'linear-gradient(135deg,#1d6fb8,#0861A9)';
                    } elseif (stripos($bundle->name, 'gold') !== false) {
                        $bg_grad = 'linear-gradient(135deg,#ED9020,#C8770E)';
                    }
                    ?>
                    <div class="bundle">
                        <span class="bundle-ic" style="background:<?= $bg_grad ?>" aria-hidden="true">
                            <svg><use href="#i-briefcase"/></svg>
                        </span>
                        <div class="bundle-info">
                            <div class="bundle-name">
                                <?= esc($bundle->name) ?>
                                <?php if (isset($recommendedBundle) && $recommendedBundle && $recommendedBundle->id === $bundle->id): ?>
                                    <span class="pill pill--reviewed" style="font-size: 0.6rem; padding: 2px 6px; margin-left: 4px;">Recommended</span>
                                <?php endif; ?>
                            </div>
                            <div class="bundle-posts"><?= esc($bundle->job_credits) ?> job posts</div>
                        </div>
                        <div class="bundle-price">
                            &#8358;<?= number_format($bundle->price) ?>
                            <i>&#8358;<?= number_format($bundle->price_per_credit ?? ($bundle->price / ($bundle->job_credits ?: 1))) ?> / post</i>
                        </div>
                        <button class="emp-btn emp-btn-outline emp-btn-sm buy-bundle-btn" data-code="<?= esc($bundle->slug) ?>">
                            Buy Bundle
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <p style="font-size:.74rem;color:var(--muted);margin-top:14px">Bundle credits never expire and are used automatically each time you post a job.</p>
        </div>
    </section>

    <!-- Business Pro -->
    <section class="pro-card" aria-label="Business Pro plan">
        <div class="pro-head">
            <span class="pro-badge">Best value</span>
            <h2><?= esc($subscriptionPlan->name ?? 'Business Pro') ?></h2>
            <p>Unlimited job postings + premium features</p>
        </div>
        <div class="pro-body">
            <div class="form-group">
                <label class="form-label" for="duration">Select duration</label>
                <select class="select" id="duration" aria-label="Subscription duration">
                    <?php
                    $tiers = is_string($pricingTiers) ? json_decode($pricingTiers, true) : ($pricingTiers ?? []);
                    $durations = [
                        1 => ['label' => '1 Month', 'per' => 'billed monthly'],
                        3 => ['label' => '3 Months', 'per' => '&#8358;' . number_format(($tiers[3] ?? 48000) / 3) . ' / month · billed quarterly'],
                        6 => ['label' => '6 Months', 'per' => '&#8358;' . number_format(($tiers[6] ?? 84000) / 6) . ' / month · billed bi-annually'],
                        12 => ['label' => '12 Months (Best Value)', 'per' => '&#8358;' . number_format(($tiers[12] ?? 150000) / 12) . ' / month · billed yearly']
                    ];
                    foreach ($durations as $months => $info):
                        $price = $tiers[$months] ?? ($months * 18000);
                    ?>
                        <option value="<?= $price ?>" data-per="<?= esc($info['per']) ?>" <?= $months === 1 ? 'selected' : '' ?>>
                            <?= esc($info['label']) ?> — &#8358;<?= number_format($price) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="pro-price" style="margin-top:18px">
                <span id="pro-amt">&#8358;<?= number_format($tiers[1] ?? 18000) ?></span>
                <i id="pro-per">billed monthly</i>
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
            <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-accent emp-btn-block">
                <svg aria-hidden="true"><use href="#i-zap"/></svg> Subscribe Now
            </a>
            <p style="font-size:.72rem;color:var(--muted);text-align:center;margin-top:10px">Renews automatically. Cancel anytime from this page.</p>
        </div>
    </section>  </section>
</div>

<!-- Purchase History -->
<div class="card" style="margin-top: 24px;">
    <div class="card-head">
        <span class="card-title">
            <svg aria-hidden="true"><use href="#i-receipt"/></svg> Bundle Purchase History
        </span>
    </div>
    <div class="card-body">
        <?php if (empty($bundleHistory)): ?>
            <div class="empty-state">
                <div class="empty-ic"><svg aria-hidden="true"><use href="#i-receipt"/></svg></div>
                <h3>No purchase history</h3>
                <p>No bundle purchases recorded yet.</p>
            </div>
        <?php else: ?>
            <div class="tbl-wrap">
                <table class="data-table">
                    <thead>
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
                                <td><?= esc($row['credits']) ?></td>
                                <td><code><?= esc($row['reference']) ?></code></td>
                                <td><?= esc($row['description']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
</div>

<div class="notice notice--warn" role="note" style="margin-top: 24px;">
    <svg aria-hidden="true"><use href="#i-shield"/></svg>
    <span><b>Note:</b> We do not publish scam jobs. All postings are reviewed. No refunds after successful payment.</span>
</div>

<div id="payment-loader" class="payment-loader">
    <div class="spinner-border text-primary mb-3" role="status"></div>
    <img src="<?= base_url('auth/img/logo.png'); ?>" alt="Processing" class="loader-logo img-fluid">
    <p class="mt-3 text-muted fw-semibold">Processing payment… please wait</p>
</div>

<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button class="emp-btn emp-btn-outline" onclick="document.querySelector('.plans').scrollIntoView({behavior:'smooth'})">View Bundles</button>
<button class="emp-btn emp-btn-accent" onclick="document.querySelector('.pro-card').scrollIntoView({behavior:'smooth'})"><svg aria-hidden="true"><use href="#i-zap"/></svg> Subscribe Now</button>
<?= $this->endSection() ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
                        $('#payment-loader').fadeIn(150).css('display', 'flex');
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
            data: { reference: reference },
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

    // Duration selector logic
    const d = document.getElementById('duration');
    const a = document.getElementById('pro-amt');
    const p = document.getElementById('pro-per');
    if (d && a && p) {
        d.addEventListener('change', function() {
            const o = d.options[d.selectedIndex];
            a.textContent = '₦' + Number(o.value).toLocaleString('en-NG');
            p.innerHTML = o.getAttribute('data-per');
        });
    }
});
</script>
<?= $this->endSection() ?>