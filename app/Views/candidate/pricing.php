<?php $page_title = 'Premium'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ═══ PREMIUM PLANS ═══ */
.prem-hero{background:linear-gradient(135deg,var(--brand-deep),var(--brand));border-radius:18px;padding:clamp(24px,4vw,44px);color:#fff;text-align:center;position:relative;overflow:hidden;margin-bottom:24px}
.prem-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 85% -10%,rgba(237,144,32,.4),transparent 55%)}
.prem-hero > *{position:relative}
.prem-hero .ph-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);border-radius:30px;padding:7px 16px;font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:16px}
.prem-hero .ph-badge svg{width:14px;height:14px;color:#ffd27a}
.prem-hero h1{font-family:'Sora',sans-serif;font-size:clamp(1.6rem,3vw,2.3rem);margin-bottom:10px;color:#fff}
.prem-hero p{font-size:1rem;opacity:.92;max-width:520px;margin:0 auto}
.prem-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;max-width:820px;margin:0 auto}
@media(max-width:720px){.prem-grid{grid-template-columns:1fr}}
.plan{background:var(--bg);border:1px solid var(--border);border-radius:16px;padding:clamp(20px,3vw,30px);display:flex;flex-direction:column}
.plan.featured{border:2px solid var(--brand);box-shadow:var(--shadow-lg);position:relative}
.plan.featured::before{content:'RECOMMENDED';position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:var(--accent);color:var(--brand-deep);font-size:.66rem;font-weight:800;letter-spacing:.08em;padding:4px 14px;border-radius:20px}
.plan h3{font-family:'Sora',sans-serif;font-size:1.2rem;color:var(--brand-deep);margin-bottom:4px}
.plan .plan-sub{font-size:.82rem;color:var(--muted);margin-bottom:16px}
.plan .price{display:flex;align-items:baseline;gap:4px;margin-bottom:4px}
.plan .price .amt{font-family:'Sora',sans-serif;font-size:2.4rem;font-weight:800;color:var(--brand-deep)}
.plan .price .per{font-size:.9rem;color:var(--muted)}
.plan .price-note{font-size:.76rem;color:var(--muted);margin-bottom:20px}
.plan-features{list-style:none;padding:0;margin:0 0 22px;flex:1}
.plan-features li{display:flex;align-items:flex-start;gap:10px;font-size:.88rem;color:var(--text);margin-bottom:12px;line-height:1.45}
.plan-features li svg{width:17px;height:17px;flex-shrink:0;margin-top:1px}
.plan-features li.yes svg{color:var(--success)}
.plan-features li.no{color:var(--muted)}
.plan-features li.no svg{color:var(--border)}
.plan .btn{width:100%;justify-content:center}
.prem-faq{max-width:820px;margin:30px auto 0}
.prem-faq h3{font-family:'Sora',sans-serif;font-size:1.15rem;color:var(--brand-deep);margin-bottom:14px;text-align:center}
.faq-item{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 18px;margin-bottom:11px}
.faq-item b{font-size:.9rem;color:var(--brand-deep);display:block;margin-bottom:5px}
.faq-item p{font-size:.84rem;color:var(--muted);line-height:1.55}
.prem-trust{display:flex;align-items:center;justify-content:center;gap:20px;flex-wrap:wrap;margin-top:24px;padding-top:20px;border-top:1px solid var(--border)}
.prem-trust span{display:inline-flex;align-items:center;gap:8px;font-size:.8rem;color:var(--muted)}
.prem-trust svg{width:15px;height:15px;color:var(--success)}

/* Premium Polish Layer */
:root{
  --shadow-xs:0 1px 3px rgba(10,47,87,.06);
  --shadow-sm:0 2px 10px rgba(10,47,87,.07);
  --shadow-md:0 6px 24px rgba(10,47,87,.10);
  --shadow-lg-p:0 16px 44px rgba(10,47,87,.16);
  --border-c:#e2e8f2;
}
.card,.dash-card,.set-card,.plan,.info-card,.job-card,.les-detail,.cur-card,
.at-card,.faq-item,.modal,.q-block{
  box-shadow:var(--shadow-xs);
  border-color:var(--border-c);
}
.card:hover,.dash-card:hover,.job-card:hover,.cs-tool:hover,.res-item:hover{
  box-shadow:var(--shadow-sm);
}
.modal{box-shadow:var(--shadow-lg-p)}
.btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.tpl-swatch,
.les,.res-item,.faq-item,.plan .btn,.icon-btn{
  transition:transform .12s cubic-bezier(.2,.8,.2,1),
             box-shadow .18s ease,
             background-color .18s ease,
             border-color .18s ease,
             opacity .18s ease;
}
.btn:active,.at-pal:active,.at-opt:active,.q-opt:active,.cs-tool:active,
.les:active,.res-item:active{
  transform:scale(.97);
}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){
  .btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.les,.res-item{
    transition:background-color .12s ease,border-color .12s ease!important;
  }
  .btn:active,.btn:hover{transform:none!important}
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

    <?php if ($isFreeMode): ?>
        <div class="notice notice--info" style="margin-top: 20px;">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg>
            <span><strong>All features are currently free!</strong> Enjoy full access to JobberRecruit at no cost.</span>
        </div>
    <?php endif; ?>

    <div class="prem-grid">
        <?php if (empty($plans)): ?>
            <!-- Fallback Static Free Plan matching candidate-premium.html -->
            <div class="plan">
                <h3>Free</h3>
                <p class="plan-sub">Everything you need to start your job search.</p>
                <div class="price"><span class="amt">₦0</span><span class="per">/month</span></div>
                <p class="price-note">Always free</p>
                <ul class="plan-features">
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Browse &amp; apply to jobs</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Build your candidate profile</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>Employer-invited aptitude tests</span></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <span>2 free practice tests / month</span></li>
                    <li class="no"><svg aria-hidden="true"><use href="#i-x"/></svg> <span>AI Resume Builder</span></li>
                    <li class="no"><svg aria-hidden="true"><use href="#i-x"/></svg> <span>AI Career Tools</span></li>
                    <li class="no"><svg aria-hidden="true"><use href="#i-x"/></svg> <span>Unlimited practice tests</span></li>
                </ul>
                <button class="btn btn-outline" disabled>Your current plan</button>
            </div>

            <!-- Fallback Static Premium Plan matching candidate-premium.html -->
            <div class="plan featured">
                <h3>Premium</h3>
                <p class="plan-sub">The complete AI-powered career advantage.</p>
                <div class="price"><span class="amt">₦5,000</span><span class="per">/month</span></div>
                <p class="price-note">Cancel anytime · billed monthly</p>
                <ul class="plan-features">
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>Everything in Free</b></li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>AI Resume Builder</b> — 7 templates, ATS score, recruiter review</li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>AI Career Tools</b> — interview prep, salary guidance</li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> <b>Unlimited practice tests</b> across all skills</li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> Priority profile in employer searches</li>
                    <li class="yes"><svg aria-hidden="true"><use href="#i-check"/></svg> Detailed skill analytics</li>
                </ul>
                <a href="<?= base_url('candidate/subscription/checkout') ?>" class="btn btn-primary" id="subscribe-btn"><svg aria-hidden="true"><use href="#i-crown"/></svg> Upgrade to Premium</a>
            </div>
        <?php else: ?>
            <?php foreach ($plans as $plan): ?>
                <?php
                    $featuresRaw = $plan->features ?? [];
                    $features = is_string($featuresRaw) ? json_decode($featuresRaw, true) : json_decode(json_encode($featuresRaw), true);
                    $features = is_array($features) ? $features : [];
                    $price = (float) ($plan->base_price ?? 0);
                    $isCurrent = $currentPlan && $currentPlan->plan_id == $plan->id;
                    $isPopular = in_array(strtolower($plan->name), ['pro', 'professional', 'gold', 'premium']);
                ?>
                <div class="plan <?= $isPopular ? 'featured' : '' ?> <?= $isCurrent ? 'current' : '' ?>">
                    <div>
                        <h3><?= esc($plan->name) ?></h3>
                        <p class="plan-sub"><?= esc($plan->code ?? 'Standard Access') ?></p>
                    </div>

                    <div class="price">
                        <?php if ($price <= 0): ?>
                            <span class="amt">FREE</span>
                        <?php else: ?>
                            <span class="amt">₦<?= number_format($price) ?></span>
                            <span class="per">/month</span>
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

                    <div style="margin-top:auto; padding-top:10px;">
                        <?php if ($isCurrent): ?>
                            <button class="btn btn-outline btn-block" disabled style="cursor:not-allowed;">Active Plan</button>
                        <?php elseif ($isFreeMode || $price <= 0): ?>
                            <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <?= $price <= 0 ? 'Activate Free Plan' : 'Get Started' ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <?php 
                                $walletBal = isset($walletBalance) ? (float)$walletBalance : 0.0;
                                $canPayWithWallet = ($walletBal >= $price);
                            ?>
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Pay with Card (Paystack)
                                    </button>
                                </form>

                                <?php if ($canPayWithWallet): ?>
                                    <button type="button" class="btn pay-wallet-btn btn-block" data-plan-id="<?= $plan->id ?>" style="background:var(--accent-light); color:var(--accent-dark); border:1.5px solid var(--accent); font-weight:700;">
                                        Pay with Wallet (₦<?= number_format($price, 2) ?>)
                                    </button>
                                <?php else: ?>
                                    <div style="font-size:0.75rem; color:var(--muted); text-align:center; background:#f8fafc; border:1px dashed #e2e8f0; padding:6px; border-radius:var(--radius);">
                                        Wallet balance: ₦<?= number_format($walletBal, 2) ?> <a href="<?= base_url('candidate/wallet') ?>" style="color:var(--brand); text-decoration:underline; font-weight:700; margin-left:4px;">Fund wallet</a>
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
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 22px; border-bottom:1px solid #e2e8f0;">
      <span style="font-family:'Sora',sans-serif; font-weight:800; font-size:1.05rem; color:var(--brand-deep); display:inline-flex; align-items:center; gap:9px;">
        <svg aria-hidden="true" style="width:18px;height:18px;color:var(--accent);"><use href="#i-wallet"/></svg> Confirm Payment
      </span>
      <button id="wallet-confirm-close" style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; cursor:pointer; flex-shrink:0;" aria-label="Close dialog">
        <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg>
      </button>
    </div>
    <div style="padding:18px 22px;">
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; padding:12px 16px; background:#fefce8; border:1px solid #fde68a; border-radius:10px;">
        <svg aria-hidden="true" style="width:20px;height:20px;color:#d97706;flex-shrink:0;"><use href="#i-wallet"/></svg>
        <div>
          <p id="wallet-modal-desc" style="font-size:0.9rem; font-weight:600; color:#92400e; margin:0;">Pay with your wallet balance?</p>
          <p id="wallet-modal-amount" style="font-size:0.78rem; color:#b45309; margin:4px 0 0;">Amount: ₦<span id="wallet-amount-display">0.00</span></p>
        </div>
      </div>
      <p style="font-size:0.82rem; color:var(--muted); line-height:1.5; margin:0;">This will deduct the plan amount from your wallet balance. Wallet funds cannot be withdrawn or refunded.</p>
    </div>
    <div style="display:flex; justify-content:flex-end; gap:10px; padding:14px 22px; border-top:1px solid #e2e8f0;">
      <button class="btn btn-outline" id="wallet-confirm-cancel">Cancel</button>
      <button class="btn btn-primary" id="wallet-confirm-pay"><svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-check"/></svg> Confirm &amp; Pay</button>
    </div>
  </div>  </div>
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

    let pendingPlanId = null;
    let pendingBtn = null;
    let pendingOriginalText = '';

    function openWalletModal(planId, btn, amount) {
        pendingPlanId = planId;
        pendingBtn = btn;
        pendingOriginalText = btn.innerText;
        amountDisplay.textContent = Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
        modalDesc.textContent = 'Pay for premium subscription with your wallet balance?';
        scrim.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeWalletModal() {
        scrim.style.display = 'none';
        document.body.style.overflow = '';
        pendingPlanId = null;
        pendingBtn = null;
    }

    cancelBtn.addEventListener('click', closeWalletModal);
    closeBtn.addEventListener('click', closeWalletModal);
    scrim.addEventListener('click', function(e) { if (e.target === scrim) closeWalletModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && scrim.style.display === 'flex') closeWalletModal(); });

    confirmPayBtn.addEventListener('click', function() {
        if (!pendingPlanId || !pendingBtn) return;

        confirmPayBtn.disabled = true;
        confirmPayBtn.innerHTML = 'Processing...';

        const planId = pendingPlanId;
        const btn = pendingBtn;
        const originalText = pendingOriginalText;

        fetch('<?= base_url('employer/wallet/pay-with-wallet') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                type: 'candidate_plan',
                plan_id: planId
            })
        })
        .then(res => res.json())
        .then(data => {
            confirmPayBtn.disabled = false;
            confirmPayBtn.innerHTML = '<svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-check"/></svg> Confirm &amp; Pay';

            if (data.success) {
                closeWalletModal();
                toastr.success(data.message || 'Subscription activated successfully!');
                window.location.href = '<?= base_url('candidate/dashboard') ?>';
            } else {
                closeWalletModal();
                toastr.error(data.message || 'Payment failed.');
                btn.disabled = false;
                btn.innerText = originalText;
            }
        })
        .catch(err => {
            confirmPayBtn.disabled = false;
            confirmPayBtn.innerHTML = '<svg aria-hidden="true" style="width:14px;height:14px;"><use href="#i-check"/></svg> Confirm &amp; Pay';
            closeWalletModal();
            toastr.error('An error occurred during wallet transaction process.');
            btn.disabled = false;
            btn.innerText = originalText;
        });
    });

    // Hook wallet buttons to open modal instead of confirm()
    document.querySelectorAll('.pay-wallet-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const planId = this.dataset.planId;
            if (!planId) return;

            // Extract price from button text: "Pay with Wallet (₦1,500.00)"
            const match = this.innerText.match(/₦?([\d,]+(?:\.\d{1,2})?)/);
            const amount = match ? match[1].replace(/,/g, '') : '0';

            openWalletModal(planId, this, amount);
        });
    });
});
</script>
<?= $this->endSection() ?>
