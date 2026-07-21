<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
@media (min-width: 768px) { .prem-faq-grid { grid-template-columns: 1fr 1fr; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    
    <div class="prem-hero" style="background: radial-gradient(ellipse 90% 40% at 100% 0%,rgba(237,144,32,.14) 0%,transparent 55%), linear-gradient(175deg,#0A2F57 0%,#083a6b 60%,#064A85 100%); color: #fff; padding: 40px; border-radius: var(--radius-lg); text-align: center;">
        <span class="ph-badge" style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); padding:6px 14px; border-radius:20px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:15px; color:#ffd27a;">
            <svg aria-hidden="true" style="width:13px;height:13px;fill:currentColor;"><use href="#i-crown"/></svg> JobberRecruit Premium
        </span>
        <h1 style="font-family:'Sora',sans-serif; font-weight:800; font-size:clamp(1.5rem, 3vw, 2.2rem); margin-bottom:10px; color:#fff;">Unlock your full career toolkit</h1>
        <p style="font-size:0.95rem; color:#dbe6f2; max-width:600px; margin:0 auto; line-height:1.6;">Get the AI tools that help you stand out, apply faster, and keep your skills sharp — all for one simple monthly price.</p>
    </div>

    <?php if ($isFreeMode): ?>
        <div class="notice notice--info" style="margin-top: 20px;">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg>
            <span><strong>All features are currently free!</strong> Enjoy full access to JobberRecruit at no cost.</span>
        </div>
    <?php endif; ?>

    <div class="prem-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; margin-top:30px; align-items: stretch;">
        <?php if (empty($plans)): ?>
            <div class="card" style="grid-column: 1 / -1; text-align:center; padding:48px;">
                <div class="empty">
                    <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-x"/></svg></span>
                    <h3>No plans available</h3>
                    <p>Check back later for premium plans.</p>
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
                    $isPopular = in_array(strtolower($plan->name), ['pro', 'professional', 'gold', 'premium']);
                ?>
                <div class="plan <?= $isPopular ? 'featured' : '' ?> <?= $isCurrent ? 'current' : '' ?>" style="background:#fff; border:1.5px solid <?= $isPopular ? 'var(--brand)' : 'var(--border)' ?>; border-radius:var(--radius-lg); padding:28px; display:flex; flex-direction:column; gap:20px; transition:var(--transition); position:relative; overflow:hidden;">
                    <?php if ($isPopular): ?>
                        <div style="position:absolute; top:12px; right:12px; background:var(--accent-light); color:var(--accent-dark); font-size:0.65rem; font-weight:800; padding:4px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:0.04em;">Most Popular</div>
                    <?php endif; ?>

                    <div>
                        <h3 style="font-family:'Sora',sans-serif; font-size:1.24rem; font-weight:800; color:var(--brand-deep); margin-bottom:4px;"><?= esc($plan->name) ?></h3>
                        <p class="plan-sub" style="font-size:0.8rem; color:var(--muted);"><?= esc($plan->code ?? 'Standard Access') ?></p>
                    </div>

                    <div class="price" style="display:flex; align-items:baseline; gap:4px;">
                        <?php if ($price <= 0): ?>
                            <span class="amt" style="font-family:'Sora',sans-serif; font-size:2rem; font-weight:800; color:var(--brand-deep);">FREE</span>
                        <?php else: ?>
                            <span class="amt" style="font-family:'Sora',sans-serif; font-size:2rem; font-weight:800; color:var(--brand-deep);">₦<?= number_format($price) ?></span>
                            <span class="per" style="font-size:0.82rem; color:var(--muted);">/month</span>
                        <?php endif; ?>
                    </div>

                    <ul class="plan-features" style="list-style:none; display:flex; flex-direction:column; gap:10px; font-size:0.84rem; color:var(--text); margin-bottom:10px;">
                        <?php foreach ($features as $feature => $enabled): ?>
                            <li style="display:flex; align-items:flex-start; gap:8px;">
                                <?php if ($enabled): ?>
                                    <svg aria-hidden="true" style="width:14px;height:14px;color:var(--success);margin-top:4px;fill:none;stroke:currentColor;stroke-width:2.5;"><use href="#i-check"/></svg>
                                    <span><?= esc(ucwords(str_replace('_', ' ', $feature))) ?></span>
                                <?php else: ?>
                                    <svg aria-hidden="true" style="width:14px;height:14px;color:var(--muted);opacity:0.5;margin-top:4px;fill:none;stroke:currentColor;stroke-width:2.5;"><use href="#i-x"/></svg>
                                    <span style="color:var(--muted); text-decoration:line-through; opacity:0.6;"><?= esc(ucwords(str_replace('_', ' ', $feature))) ?></span>
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
                            <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Upgrade to Premium
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Trust banner -->
    <div class="prem-trust" style="display:flex; justify-content:center; gap:24px; flex-wrap:wrap; margin-top:30px; font-size:0.8rem; color:var(--muted);">
        <span style="display:inline-flex; align-items:center; gap:6px;"><svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg> Secure payment</span>
        <span style="display:inline-flex; align-items:center; gap:6px;"><svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-refresh"/></svg> Cancel anytime</span>
        <span style="display:inline-flex; align-items:center; gap:6px;"><svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check-c"/></svg> Instant access</span>
    </div>

    <!-- Common FAQ Questions -->
    <section class="prem-faq card" style="margin-top:40px; padding:24px;">
        <h3 style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:var(--brand-deep); margin-bottom:20px;">Common questions</h3>
        <div class="prem-faq-grid" style="display:grid; grid-template-columns:1fr; gap:20px;">
            <div class="faq-item" style="display:flex; flex-direction:column; gap:6px;">
                <b style="font-size:0.88rem; color:var(--brand-deep);">What happens when I subscribe?</b>
                <p style="font-size:0.8rem; color:var(--muted); line-height:1.6;">You get instant access to the AI Resume Builder, AI Career Tools, and unlimited practice tests. Your subscription renews monthly until cancelled.</p>
            </div>
            <div class="faq-item" style="display:flex; flex-direction:column; gap:6px;">
                <b style="font-size:0.88rem; color:var(--brand-deep);">Do aptitude test certificates come with Premium?</b>
                <p style="font-size:0.8rem; color:var(--muted); line-height:1.6;">No. Certificates are only issued for paid courses you complete and pass. Practice aptitude tests help you learn, but don't carry a certificate.</p>
            </div>
            <div class="faq-item" style="display:flex; flex-direction:column; gap:6px;">
                <b style="font-size:0.88rem; color:var(--brand-deep);">Can I cancel anytime?</b>
                <p style="font-size:0.8rem; color:var(--muted); line-height:1.6;">Yes. Cancel from this page whenever you like — you'll keep Premium access until the end of your current billing month.</p>
            </div>
            <div class="faq-item" style="display:flex; flex-direction:column; gap:6px;">
                <b style="font-size:0.88rem; color:var(--brand-deep);">Are employer-invited tests free?</b>
                <p style="font-size:0.8rem; color:var(--muted); line-height:1.6;">Always. When an employer invites you to take an aptitude test as part of hiring, it's completely free — that never counts against your practice-test limit.</p>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
