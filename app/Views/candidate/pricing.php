<?php $page_title = 'Premium'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ═══ PREMIUM PLANS SIDE-BY-SIDE REDESIGN ═══ */
.prem-hero {
    background: linear-gradient(135deg, var(--brand-deep), var(--brand));
    border-radius: 20px;
    padding: clamp(28px, 5vw, 54px);
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px -10px rgba(10, 47, 87, 0.25);
}
.prem-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% -20%, rgba(237, 144, 32, 0.45), transparent 60%);
}
.prem-hero > * {
    position: relative;
    z-index: 1;
}
.prem-hero .ph-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 30px;
    padding: 6px 14px;
    font-size: .74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 18px;
}
.prem-hero .ph-badge svg {
    width: 13px;
    height: 13px;
    color: #ffd27a;
}
.prem-hero h1 {
    font-family: 'Sora', sans-serif;
    font-size: clamp(1.8rem, 3.5vw, 2.5rem);
    font-weight: 800;
    margin-bottom: 12px;
    color: #fff;
    letter-spacing: -0.02em;
}
.prem-hero p {
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.95);
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.6;
}

.notice--info {
    max-width: 1080px;
    margin: -10px auto 24px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    border-radius: 12px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .88rem;
}

/* Side by Side 3 Column grid */
.prem-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    max-width: 1080px;
    margin: 0 auto;
    align-items: stretch;
}
@media (max-width: 991px) {
    .prem-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 680px) {
    .prem-grid {
        grid-template-columns: 1fr;
    }
}

/* Plan Card layout */
.plan {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 30px 24px;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 4px 20px rgba(10, 47, 87, 0.03);
    transition: all 0.25s cubic-bezier(.2, .8, .2, 1);
    height: 100%;
}
.plan:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(10, 47, 87, 0.08);
    border-color: #cbd5e1;
}

/* Recommended/Featured Badge styling */
.plan.featured {
    border: 2px solid var(--brand);
    box-shadow: 0 8px 32px rgba(10, 47, 87, 0.08);
}
.plan.featured .recommended-badge {
    position: absolute;
    top: -11px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent);
    color: var(--brand-deep);
    font-size: .66rem;
    font-weight: 800;
    letter-spacing: .08em;
    padding: 4px 14px;
    border-radius: 20px;
    text-transform: uppercase;
    white-space: nowrap;
}

/* Card Section Division */
.plan-header-sec {
    margin-bottom: 20px;
}
.plan h3 {
    font-family: 'Sora', sans-serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--brand-deep);
    margin: 0 0 6px;
}
.plan .plan-sub {
    font-size: .84rem;
    color: var(--muted);
    margin: 0;
    line-height: 1.4;
    min-height: 40px;
}

/* Pricing strictly on one line */
.plan .price-sec {
    margin-bottom: 24px;
}
.plan .price {
    display: flex;
    align-items: baseline;
    white-space: nowrap;
    line-height: 1;
}
.plan .price .amt {
    font-family: 'Sora', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--brand-deep);
}
.plan .price .per {
    font-size: 1rem;
    font-weight: 600;
    color: var(--muted);
    margin-left: 3px;
}
.plan .price-note {
    font-size: .74rem;
    color: var(--muted);
    margin: 6px 0 0;
}

/* Features checklist */
.plan-features {
    list-style: none;
    padding: 0;
    margin: 0 0 30px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    flex-grow: 1;
}
.plan-features li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: .86rem;
    color: var(--text);
    line-height: 1.45;
}
.plan-features li svg {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    margin-top: 1px;
}
.plan-features li.yes svg {
    color: var(--success);
}
.plan-features li.no {
    color: var(--muted);
    opacity: 0.65;
}
.plan-features li.no svg {
    color: var(--border);
}

/* Active Badge */
.active-badge-wrapper {
    margin-top: 8px;
}
.active-badge {
    display: inline-block;
    background: #e2fbe8;
    color: #1e7e34;
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .03em;
}

/* Action button at bottom */
.plan-action-sec {
    margin-top: auto;
    width: 100%;
}
.plan-action-sec form,
.plan-action-sec button,
.plan-action-sec .btn {
    width: 100%;
}

.pay-wallet-btn {
    transition: all 0.2s ease;
}
.pay-wallet-btn:hover {
    background: var(--accent) !important;
    color: var(--brand-deep) !important;
    border-color: var(--accent) !important;
}

/* Trust layer & FAQ */
.prem-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    margin-top: 35px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}
.prem-trust span {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .82rem;
    color: var(--muted);
}
.prem-trust svg {
    width: 15px;
    height: 15px;
    color: var(--success);
}

.prem-faq {
    max-width: 1080px;
    margin: 45px auto 0;
}
.prem-faq h3 {
    font-family: 'Sora', sans-serif;
    font-size: 1.3rem;
    color: var(--brand-deep);
    margin-bottom: 20px;
    text-align: center;
    font-weight: 800;
}
.prem-faq-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 650px) {
    .prem-faq-grid {
        grid-template-columns: 1fr;
    }
}
.faq-item {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px 20px;
}
.faq-item b {
    font-size: .95rem;
    color: var(--brand-deep);
    display: block;
    margin-bottom: 6px;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
}
.faq-item p {
    font-size: .86rem;
    color: var(--muted);
    line-height: 1.55;
    margin: 0;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    
    <div class="prem-hero">
        <span class="ph-badge">
            <svg aria-hidden="true"><use href="#i-crown"/></svg> JobberRecruit Premium
        </span>
        <h1>Unlock your full career toolkit</h1>
        <p>Get the AI tools that help you stand out, apply faster, and keep your skills sharp — all for one simple monthly price.</p>
    </div>

    <?php if ($currentPlan): ?>
        <div class="notice notice--success" style="background:#eefbf3; border:1.5px solid #86efac; border-radius:12px; padding:16px 20px; margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; box-shadow:0 4px 14px rgba(22,163,74,0.08);">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg aria-hidden="true" style="width:22px; height:22px; stroke-width:2.5;"><use href="#i-check-c"/></svg>
                </div>
                <div>
                    <div style="font-weight:700; color:#14532d; font-size:1rem; font-family:'Sora',sans-serif;">You have already subscribed to the <?= esc($currentPlan->plan_name ?? 'Premium') ?> package</div>
                    <div style="font-size:0.83rem; color:#166534; margin-top:3px;">
                        Your subscription is currently active until <b><?= !empty($currentPlan->ends_at) ? date('M d, Y', strtotime($currentPlan->ends_at)) : 'Ongoing' ?></b>.
                    </div>
                </div>
            </div>
            <a href="<?= base_url('candidate/dashboard') ?>" class="btn btn-sm btn-primary" style="background:#16a34a; border-color:#16a34a; font-weight:700; white-space:nowrap; padding:9px 18px; border-radius:8px;">Go to Dashboard →</a>
        </div>
    <?php endif; ?>

    <?php if ($isFreeMode): ?>
        <div class="notice notice--info">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg>
            <span><strong>All features are currently free!</strong> Enjoy full access to JobberRecruit at no cost.</span>
        </div>
    <?php endif; ?>

    <div class="prem-grid">
        <?php if (empty($plans)): ?>
            <!-- Fallback Static Free Plan matching candidate-premium.html -->
            <div class="plan">
                <div class="plan-header-sec">
                    <h3>Free</h3>
                    <p class="plan-sub">Standard Access</p>
                </div>
                <div class="price-sec">
                    <div class="price">
                        <span class="amt">FREE</span>
                    </div>
                    <p class="price-note">Always free</p>
                </div>
                <ul class="plan-features">
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Browse &amp; apply to jobs</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Build your candidate profile</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Employer-invited aptitude tests</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>2 free practice tests / month</span></li>
                    <li class="no"><svg aria-hidden="true"><use href="#i-x"/></svg> <span>AI Resume Builder</span></li>
                    <li class="no"><svg aria-hidden="true"><use href="#i-x"/></svg> <span>AI Career Tools</span></li>
                    <li class="no"><svg aria-hidden="true"><use href="#i-x"/></svg> <span>Unlimited practice tests</span></li>
                </ul>
                <div class="plan-action-sec">
                    <button class="btn btn-outline" disabled>Your current plan</button>
                </div>
            </div>

            <!-- Fallback Static Premium Plan matching candidate-premium.html -->
            <div class="plan featured">
                <span class="recommended-badge">Recommended</span>
                <div class="plan-header-sec">
                    <h3>Premium</h3>
                    <p class="plan-sub">The complete AI-powered career advantage.</p>
                </div>
                <div class="price-sec">
                    <div class="price">
                        <span class="amt">₦5,000</span><span class="per">/month</span>
                    </div>
                    <p class="price-note">Cancel anytime · billed monthly</p>
                </div>
                <ul class="plan-features">
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>Everything in Free</b></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>AI Resume Builder</b></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>AI Career Tools</b></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>Unlimited practice tests</b></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Priority profile in searches</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Detailed skill analytics</span></li>
                </ul>
                <div class="plan-action-sec">
                    <button class="btn btn-primary" id="subscribe-btn" disabled title="No premium plan is currently configured — please check back soon."><svg aria-hidden="true"><use href="#i-crown"/></svg> Upgrade to Premium</button>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($plans as $plan): ?>
                <?php
                    $featuresRaw = $plan->features ?? [];
                    $features = is_string($featuresRaw) ? json_decode($featuresRaw, true) : json_decode(json_encode($featuresRaw), true);
                    $features = is_array($features) ? $features : [];
                    $price = (float) ($plan->base_price ?? 0);
                    $isCurrent = $currentPlan && $currentPlan->plan_id == $plan->id;
                    $isPopular = in_array(strtolower($plan->name), ['pro', 'professional', 'gold', 'premium', 'ai monthly access']);
                ?>
                <div class="plan <?= $isPopular ? 'featured' : '' ?>">
                    <?php if ($isPopular): ?>
                        <span class="recommended-badge">Recommended</span>
                    <?php endif; ?>
                    
                    <div class="plan-header-sec">
                        <h3><?= esc($plan->name) ?></h3>
                        <?php
                            $planSub = !empty($plan->description) ? $plan->description : (
                                ($plan->code ?? '') === 'basic_monthly' ? 'Essential career starter toolkit' : (
                                    ($plan->code ?? '') === 'ai_monthly_access' ? 'Full AI tools & smart matching access' : (
                                        ($plan->code ?? '') === 'pro_monthly' ? 'Complete career & advanced AI suite' : ucwords(str_replace('_', ' ', $plan->code ?? 'Standard Access'))
                                    )
                                )
                            );
                        ?>
                        <p class="plan-sub"><?= esc($planSub) ?></p>
                        <?php if ($isCurrent): ?>
                            <div class="active-badge-wrapper">
                                <span class="active-badge">Active Plan</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="price-sec">
                        <div class="price">
                            <?php if ($price <= 0): ?>
                                <span class="amt">FREE</span>
                            <?php else: ?>
                                <span class="amt">₦<?= number_format($price) ?></span><span class="per">/month</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($price > 0): ?>
                            <p class="price-note">Billed monthly</p>
                        <?php endif; ?>
                    </div>

                    <ul class="plan-features">
                        <?php foreach ($features as $feature => $enabled): ?>
                            <li class="<?= $enabled ? 'yes' : 'no' ?>">
                                <?php if ($enabled): ?>
                                    <svg aria-hidden="true"><use href="#i-check"/></svg>
                                    <span><?= esc(ucwords(str_replace('_', ' ', $feature))) ?></span>
                                <?php else: ?>
                                    <svg aria-hidden="true"><use href="#i-x"/></svg>
                                    <span><?= esc(ucwords(str_replace('_', ' ', $feature))) ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="plan-action-sec">
                        <?php if ($isCurrent): ?>
                            <div style="background:#eefbf3; border:1.5px solid #bbf2d0; border-radius:10px; padding:12px 14px; text-align:center; margin-bottom:10px;">
                                <div style="display:inline-flex; align-items:center; gap:6px; color:#15803d; font-weight:700; font-size:0.88rem;">
                                    <svg aria-hidden="true" style="width:16px; height:16px;"><use href="#i-check-c"/></svg>
                                    Already Subscribed
                                </div>
                                <p style="font-size:0.75rem; color:#166534; margin:4px 0 0;">You have already subscribed to this package (Active until <?= !empty($currentPlan->ends_at) ? date('M d, Y', strtotime($currentPlan->ends_at)) : 'Ongoing' ?>).</p>
                            </div>
                            <button class="btn btn-outline" disabled style="cursor:not-allowed; width:100%; font-weight:700; color:#15803d; border-color:#86efac; background:#f0fdf4;">Current Active Plan</button>
                        <?php elseif ($isFreeMode || $price <= 0): ?>
                            <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                <button type="submit" class="btn btn-primary">
                                    <?= $price <= 0 ? 'Activate Free Plan' : 'Get Started' ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <?php 
                                $walletBal = isset($walletBalance) ? (float)$walletBalance : 0.0;
                                $canPayWithWallet = ($walletBal >= $price);
                            ?>
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST" class="checkout-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                    <button type="submit" class="btn btn-primary submit-btn">
                                        Pay with Card
                                    </button>
                                </form>

                                <?php if ($canPayWithWallet): ?>
                                    <button type="button" class="btn pay-wallet-btn" data-plan-id="<?= $plan->id ?>" data-price="<?= $price ?>" style="background:var(--accent-light); color:var(--accent-dark); border:1.5px solid var(--accent); font-weight:700;">
                                        Pay with Wallet
                                    </button>
                                <?php else: ?>
                                    <div style="font-size:0.75rem; color:var(--muted); text-align:center; background:#f8fafc; border:1px dashed #e2e8f0; padding:6px; border-radius:var(--radius);">
                                        Wallet: ₦<?= number_format($walletBal, 2) ?> · <a href="<?= base_url('candidate/wallet') ?>" style="color:var(--brand); text-decoration:underline; font-weight:700;">Top up</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Trust banner -->
    <div class="prem-trust">
        <span><svg aria-hidden="true"><use href="#i-shield"/></svg> Secure payment</span>
        <span><svg aria-hidden="true"><use href="#i-refresh"/></svg> Cancel anytime</span>
        <span><svg aria-hidden="true"><use href="#i-check-c"/></svg> Instant access</span>
    </div>

    <!-- Common FAQ Questions -->
    <section class="prem-faq">
        <h3>Common questions</h3>
        <div class="prem-faq-grid">
            <div class="faq-item">
                <b>What happens when I subscribe?</b>
                <p>You get instant access to the AI Resume Builder, AI Career Tools, and unlimited practice tests. Your subscription renews monthly until cancelled.</p>
            </div>
            <div class="faq-item">
                <b>Do aptitude test certificates come with Premium?</b>
                <p>No. Certificates are only issued for paid courses you complete and pass. Practice aptitude tests help you learn, but don't carry a certificate.</p>
            </div>
            <div class="faq-item">
                <b>Can I cancel anytime?</b>
                <p>Yes. Cancel from this page whenever you like — you'll keep Premium access until the end of your current billing month.</p>
            </div>
            <div class="faq-item">
                <b>Are employer-invited tests free?</b>
                <p>Always. When an employer invites you to take an aptitude test as part of hiring, it's completely free — that never counts against your practice-test limit.</p>
            </div>
        </div>
    </section>

    <!-- Wallet Payment Confirmation Modal -->
    <div class="modal-scrim" id="wallet-confirm-scrim" style="display:none; position:fixed; inset:0; background:rgba(10,25,45,.55); backdrop-filter:blur(2px); z-index:1400; align-items:center; justify-content:center; padding:24px;">
        <div style="background:#fff; border-radius:16px; width:100%; max-width:420px; overflow:hidden; box-shadow:0 20px 40px -15px rgba(10,47,87,0.15); animation:modal-in .22s ease;" role="dialog" aria-modal="true" aria-labelledby="wallet-confirm-title">
            <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST" id="wallet-checkout-form">
                <?= csrf_field() ?>
                <input type="hidden" name="plan_id" id="wallet-modal-plan-id" value="">
                <input type="hidden" name="payment_method" value="wallet">

                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 22px; border-bottom:1px solid #e2e8f0;">
                    <span id="wallet-confirm-title" style="font-family:'Sora',sans-serif; font-weight:800; font-size:1.05rem; color:var(--brand-deep); display:inline-flex; align-items:center; gap:9px;">
                        <svg aria-hidden="true" style="width:18px;height:18px;color:var(--accent);"><use href="#i-wallet"/></svg> Confirm Wallet Payment
                    </span>
                    <button type="button" id="wallet-confirm-close" style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; cursor:pointer; flex-shrink:0;" aria-label="Close dialog">
                        <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg>
                    </button>
                </div>
                <div style="padding:18px 22px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; padding:12px 16px; background:#fefce8; border:1px solid #fde68a; border-radius:10px;">
                        <svg aria-hidden="true" style="width:20px;height:20px;color:#d97706;flex-shrink:0;"><use href="#i-wallet"/></svg>
                        <div>
                            <p id="wallet-modal-desc" style="font-size:0.9rem; font-weight:700; color:#92400e; margin:0;">Pay with your wallet balance?</p>
                            <p id="wallet-modal-amount" style="font-size:0.82rem; color:#b45309; margin:4px 0 0;">Amount: ₦<span id="wallet-amount-display">0.00</span></p>
                        </div>
                    </div>
                    <p style="font-size:0.82rem; color:var(--muted); line-height:1.5; margin:0;">This will deduct the plan fee from your available wallet balance (Balance: ₦<?= number_format($walletBalance ?? 0, 2) ?>). Your premium plan will be activated immediately.</p>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; padding:14px 22px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn btn-outline" id="wallet-confirm-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="wallet-confirm-pay" style="background:var(--brand); border-color:var(--brand); font-weight:700;">
                        <svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-check"/></svg> Confirm &amp; Activate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrim = document.getElementById('wallet-confirm-scrim');
    const confirmPayBtn = document.getElementById('wallet-confirm-pay');
    const cancelBtn = document.getElementById('wallet-confirm-cancel');
    const closeBtn = document.getElementById('wallet-confirm-close');
    const amountDisplay = document.getElementById('wallet-amount-display');
    const modalDesc = document.getElementById('wallet-modal-desc');
    const modalPlanInput = document.getElementById('wallet-modal-plan-id');
    const walletForm = document.getElementById('wallet-checkout-form');

    let pendingPlanId = null;

    function openWalletModal(planId, amount) {
        pendingPlanId = planId;
        if (modalPlanInput) modalPlanInput.value = planId;
        if (amountDisplay) amountDisplay.textContent = Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
        if (modalDesc) modalDesc.textContent = 'Pay for candidate premium subscription with your wallet balance?';
        if (scrim) {
            scrim.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeWalletModal() {
        if (scrim) scrim.style.display = 'none';
        document.body.style.overflow = '';
        pendingPlanId = null;
    }

    if (cancelBtn) cancelBtn.addEventListener('click', closeWalletModal);
    if (closeBtn) closeBtn.addEventListener('click', closeWalletModal);
    if (scrim) {
        scrim.addEventListener('click', function(e) { if (e.target === scrim) closeWalletModal(); });
    }
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && scrim && scrim.style.display === 'flex') closeWalletModal(); });

    // Handle wallet form submission safely
    if (walletForm) {
        walletForm.addEventListener('submit', function(e) {
            if (!modalPlanInput || !modalPlanInput.value) {
                e.preventDefault();
                alert('Please select a valid plan.');
                return;
            }
            if (confirmPayBtn) {
                confirmPayBtn.disabled = true;
                confirmPayBtn.innerHTML = 'Processing...';
            }
        });
    }

    // Attach click handlers to all Pay with Wallet buttons
    document.querySelectorAll('.pay-wallet-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const planId = this.getAttribute('data-plan-id') || this.dataset.planId;
            const amount = this.getAttribute('data-price') || this.dataset.price || '0';
            if (!planId) return;

            openWalletModal(planId, amount);
        });
    });

    // Prevent double submissions on pay with card forms
    document.querySelectorAll('.checkout-form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 'Processing...';
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
