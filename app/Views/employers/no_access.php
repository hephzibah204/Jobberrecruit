<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<div class="page-hd">
    <div class="page-hd-left">
        <h1>Post Job Authorization</h1>
        <p>Manage your active plan subscriptions and job posting credits</p>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-body empty-state" style="padding: 48px 24px; max-width: 680px; margin: 0 auto;">
        <div class="empty-ic" style="background: var(--danger-light); color: var(--danger);">
            <svg aria-hidden="true" style="width: 28px; height: 28px;"><use href="#i-shield"/></svg>
        </div>
        <h3 style="font-size: 1.4rem;">Can't Post Job Yet</h3>
        <p style="max-width: 520px; font-size: 0.92rem; margin-bottom: 20px;">You need either an active subscription or job credits to create a new job post.</p>

        <?php if (isset($creditBalance) && $creditBalance > 0): ?>
            <div class="info-banner warning mb-4" style="text-align: left; width: 100%;">
                <svg aria-hidden="true"><use href="#i-bulb"/></svg>
                <div class="info-banner-body">
                    You still have <strong><?= number_format($creditBalance) ?> job credits</strong>, but you need an active subscription for unlimited posting.
                </div>
            </div>
        <?php endif; ?>

        <h4 style="font-family: 'Sora', sans-serif; font-size: 1rem; color: var(--brand-deep); margin-bottom: 16px; font-weight: 700;">Choose an option to continue:</h4>

        <div class="duo" style="width: 100%; gap: 16px;">
            <!-- Bundle Option -->
            <div class="card" onclick="window.location.href='<?= base_url('employer/pricing') ?>'" style="cursor: pointer; transition: var(--transition);" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <div class="card-body" style="padding: 32px 20px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--accent-light); color: var(--accent-dark); display: flex; align-items: center; justify-content: center;">
                        <svg aria-hidden="true" style="width: 20px; height: 20px;"><use href="#i-card"/></svg>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 700; color: var(--brand-deep);">Buy Job Credits</h3>
                    <p style="font-size: 0.8rem; color: var(--muted); margin: 0;">Flexible one-time bundles</p>
                    <button class="emp-btn emp-btn-accent emp-btn-sm emp-btn-block" style="margin-top: 10px;">Buy Bundle &rarr;</button>
                </div>
            </div>

            <!-- Subscription Option -->
            <div class="card" onclick="window.location.href='<?= base_url('employer/pricing') ?>'" style="cursor: pointer; transition: var(--transition);" onmouseover="this.style.borderColor='var(--brand)'" onmouseout="this.style.borderColor='var(--border)'">
                <div class="card-body" style="padding: 32px 20px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center;">
                        <svg aria-hidden="true" style="width: 20px; height: 20px;"><use href="#i-zap"/></svg>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 700; color: var(--brand-deep);"><?= isset($subscriptionPlan->name) ? esc($subscriptionPlan->name) : 'Business Pro' ?></h3>
                    <p style="font-size: 0.8rem; color: var(--muted); margin: 0;">Unlimited job posts + premium features</p>
                    <button class="emp-btn emp-btn-primary emp-btn-sm emp-btn-block" style="margin-top: 10px;">Choose Plan &rarr;</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 24px;">
            <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-primary" style="padding: 10px 32px;">
                Go to Pricing Page
            </a>
        </div>
    </div>
</div>

<div class="text-center" style="margin-bottom: 24px;">
    <small class="text-muted">Questions? Contact support via WhatsApp or email</small>
</div>
<?= $this->endSection() ?>