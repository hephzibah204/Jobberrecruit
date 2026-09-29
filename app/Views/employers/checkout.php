<?php $page_title = 'Checkout'; ?>
<?= $this->extend('layouts/employer') ?>


// Resolve item / plan attributes dynamically
$itemName = '';
$itemDesc = '';
$itemId = '';
$itemType = 'subscription';

if (isset($item)) {
    if (is_object($item)) {
        $itemName = $item->name ?? '';
        $itemDesc = $item->description ?? '';
        $itemId = $item->id ?? '';
        $itemType = $item->type ?? 'subscription';
    } else if (is_array($item)) {
        $itemName = $item['name'] ?? '';
        $itemDesc = $item['description'] ?? '';
        $itemId = $item['id'] ?? '';
        $itemType = $item['type'] ?? 'subscription';
    }
} elseif (isset($plan)) {
    if (is_object($plan)) {
        $itemName = $plan->name ?? '';
        $itemDesc = $plan->description ?? '';
        $itemId = $plan->id ?? '';
        $itemType = 'subscription';
    } else if (is_array($plan)) {
        $itemName = $plan['name'] ?? '';
        $itemDesc = $plan['description'] ?? '';
        $itemId = $plan['id'] ?? '';
        $itemType = 'subscription';
    }
}

$finalAmount = isset($amount) ? (float)$amount : (isset($upgradeCost) ? (float)$upgradeCost : 0.0);
$walletBal = isset($walletBalance) ? (float)$walletBalance : 0.0;
$canPayWithWallet = ($walletBal >= $finalAmount && $finalAmount > 0);
?>

<?= $this->section('content') ?>
<div class="page-head">
    <div class="page-title">
        <h1><svg aria-hidden="true"><use href="#i-card"/></svg> Checkout</h1>
        <p>Confirm your subscription or bundle purchase details</p>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="notice notice--warn mb-4" role="status">
        <svg aria-hidden="true"><use href="#i-x"/></svg>
        <span><?= esc(session()->getFlashdata('error')) ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="notice notice--info mb-4" role="status">
        <svg aria-hidden="true"><use href="#i-check-c"/></svg>
        <span><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
<?php endif; ?>

<?php if (isset($message) && !empty($message)): ?>
    <div class="notice notice--info mb-4" role="status">
        <svg aria-hidden="true"><use href="#i-zap"/></svg>
        <span><?= esc($message) ?></span>
    </div>
<?php endif; ?>

<div class="checkout-grid">
    <!-- Main checkout options -->
    <div class="card">
        <div class="card-head">
            <span class="card-title">
                <svg aria-hidden="true"><use href="#i-shield"/></svg> Payment Options
            </span>
        </div>
        <div class="card-body">
            <!-- Wallet balance information -->
            <div class="checkout-wallet-box">
                <div class="checkout-wallet-icon">
                    <svg aria-hidden="true"><use href="#i-wallet"/></svg>
                </div>
                <div class="wallet-details">
                    <div class="wallet-details-lbl">Your Wallet Balance</div>
                    <div class="wallet-details-val">₦<?= number_format($walletBal, 2) ?></div>
                </div>
                <?php if (!$canPayWithWallet && $finalAmount > 0): ?>
                    <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-outline emp-btn-sm">Fund Wallet</a>
                <?php endif; ?>
            </div>

            <?php if (isset($plan) && is_object($plan) && $plan->slug === 'enterprise'): ?>
                <div style="text-align: center; padding: 32px 16px;">
                    <h3 style="margin-bottom: 12px; font-family: 'Sora', sans-serif;"><?= esc($itemName) ?></h3>
                    <p style="color: var(--muted); margin-bottom: 24px; max-width: 450px; margin-left: auto; margin-right: auto;"><?= esc($itemDesc) ?></p>
                    <a href="<?= base_url('employer/contact/sales') ?>" class="emp-btn emp-btn-primary">
                        Contact Sales Team
                    </a>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                    <h3 style="font-family: 'Sora', sans-serif; margin-bottom: 6px; color: var(--brand-deep);"><?= esc($itemName) ?></h3>
                    <p style="color: var(--muted); font-size: 0.88rem;"><?= esc($itemDesc) ?></p>
                </div>

                <form method="POST" id="checkoutForm" action="<?= base_url('employer/pricing/process') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="plan_id" id="plan_id" value="<?= esc($itemId) ?>">
                    <input type="hidden" name="amount" id="amount" value="<?= esc($finalAmount) ?>">
                    <input type="hidden" name="billing_cycle" id="billing_cycle" value="<?= esc($billingCycle ?? 'monthly') ?>">
                    <input type="hidden" name="type" id="type" value="<?= esc($itemType) ?>">

                    <?php if ($canPayWithWallet): ?>
                        <div style="background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px; margin-bottom: 20px;">
                            <h4 style="font-family: 'Sora', sans-serif; font-size: 0.94rem; margin-bottom: 8px; color: var(--brand-deep);">Pay Instantly from Wallet</h4>
                            <p style="font-size: 0.8rem; color: var(--muted); margin-bottom: 14px;">You have sufficient balance to pay with your wallet.</p>
                            <button type="button" id="payWalletBtn" class="emp-btn emp-btn-accent emp-btn-block">
                                <svg aria-hidden="true"><use href="#i-check"/></svg> Pay with Wallet (₦<?= number_format($finalAmount, 2) ?>)
                            </button>
                        </div>
                    <?php endif; ?>

                    <div style="display: grid; gap: 12px;">
                        <button type="button" id="payBtn" class="emp-btn emp-btn-primary emp-btn-block">
                            Pay with Card (Paystack)
                        </button>
                        <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-outline emp-btn-block" style="text-align: center;">
                            Cancel & Return
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar summary -->
    <div class="checkout-summary-card">
        <h3 style="font-family: 'Sora', sans-serif; font-size: 1.1rem; color: var(--brand-deep); margin-bottom: 16px;">Order Summary</h3>
        <ul class="summary-list">
            <li class="summary-item">
                <span>Item Name</span>
                <strong><?= esc($itemName) ?></strong>
            </li>
            <?php if (isset($plan) && is_object($plan)): ?>
                <li class="summary-item">
                    <span>Job Postings</span>
                    <strong><?= $plan->job_limit == -1 ? 'Unlimited' : esc($plan->job_limit) ?></strong>
                </li>
                <li class="summary-item">
                    <span>Featured Limit</span>
                    <strong><?= $plan->featured_limit == -1 ? 'Unlimited' : esc($plan->featured_limit) ?></strong>
                </li>
                <li class="summary-item">
                    <span>Duration</span>
                    <strong><?= esc($plan->duration) ?> days</strong>
                </li>
            <?php endif; ?>
            <li class="summary-item" style="border-top: 1px solid var(--border); padding-top: 14px; margin-top: 6px;">
                <span style="font-weight: 700; color: var(--brand-deep);">Amount Due</span>
                <strong style="font-family: 'Sora', sans-serif; font-size: 1.1rem; color: var(--brand-deep);">₦<?= number_format($finalAmount, 2) ?></strong>
            </li>
        </ul>

        <?php if (!empty($explain)): ?>
            <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 12px; font-size: 0.78rem; color: var(--muted); margin-top: 12px;">
                <strong>Pricing Note:</strong> <?= esc($explain) ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 20px; font-size: 0.74rem; color: var(--muted); line-height: 1.4;">
            <p>Secure transactions processed securely. Subscription terms and conditions apply.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<?php if (isset($plan) && is_object($plan) && $plan->slug === 'enterprise'): ?>
    <a href="<?= base_url('employer/contact/sales') ?>" class="emp-btn emp-btn-primary emp-btn-block">Contact Sales</a>
<?php else: ?>
    <?php if ($canPayWithWallet): ?>
        <button type="button" id="mobPayWalletBtn" class="emp-btn emp-btn-accent">Wallet Pay</button>
    <?php endif; ?>
    <button type="button" id="mobPayBtn" class="emp-btn emp-btn-primary">Pay Card</button>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.querySelector('#payBtn');
    const mobPayBtn = document.querySelector('#mobPayBtn');
    const payWalletBtn = document.querySelector('#payWalletBtn');
    const mobPayWalletBtn = document.querySelector('#mobPayWalletBtn');
    const checkoutForm = document.querySelector('#checkoutForm');

    function initPaystackPayment(e) {
        e.preventDefault();

        const btn = payBtn || mobPayBtn;
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = 'Processing...';
        }

        const formData = new FormData(checkoutForm);

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
                    resetButtons();
                }
            }).openIframe();
        })
        .catch(err => {
            alert(err.message || 'Error processing request');
            resetButtons();
        });
    }

    function initWalletPayment(e) {
        e.preventDefault();

        const btn = payWalletBtn || mobPayWalletBtn;
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = 'Processing...';
        }

        const formData = new FormData(checkoutForm);

        fetch('<?= base_url('employer/wallet/pay-with-wallet') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= base_url('employer/pricing') ?>';
            } else {
                alert(data.message || 'Wallet payment failed');
                resetButtons();
            }
        })
        .catch(err => {
            alert('Error processing wallet payment');
            resetButtons();
        });
    }

    function verifyPayment(reference) {
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
                window.location.href = '<?= base_url('employer/pricing') ?>';
            } else {
                alert(data.message || 'Payment verification failed');
                resetButtons();
            }
        })
        .catch(err => {
            alert('Payment verification connection error');
            resetButtons();
        });
    }

    function resetButtons() {
        [payBtn, mobPayBtn, payWalletBtn, mobPayWalletBtn].forEach(btn => {
            if (btn && btn.dataset.originalHtml) {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.originalHtml;
            }
        });
    }

    if (payBtn) payBtn.addEventListener('click', initPaystackPayment);
    if (mobPayBtn) mobPayBtn.addEventListener('click', initPaystackPayment);
    if (payWalletBtn) payWalletBtn.addEventListener('click', initWalletPayment);
    if (mobPayWalletBtn) mobPayWalletBtn.addEventListener('click', initWalletPayment);
});
</script>
<?= $this->endSection() ?>