<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
    .wallet-card-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-top: 20px;
    }
    @media (min-width: 992px) {
        .wallet-card-container {
            grid-template-columns: 1.8fr 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
 
    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg> My Wallet</h1>
            <p>Fund your account and make instant payments for platform services.</p>
        </div>
    </div>
 
    <!-- Main Wallet Card Container -->
    <div class="wallet-card-container">
        <!-- Balance Card -->
        <section class="card" aria-label="Wallet balance" style="padding: 32px; display: flex; flex-direction: column; justify-content: center; background: radial-gradient(ellipse 90% 40% at 100% 0%,rgba(8,97,169,.12) 0%,transparent 55%), #ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span class="pill pill--brand" style="font-size: 0.72rem; padding: 4px 12px; margin-bottom: 12px; font-weight: 700;">
                        Active Wallet Balance
                    </span>
                    <h2 style="font-family: 'Sora', sans-serif; font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 800; color: var(--brand-deep); margin: 8px 0 4px;">₦<?= number_format((float) ($wallet ? $wallet->balance : 0), 2) ?></h2>
                    <p style="font-size: 0.8rem; color: var(--muted); margin: 0;">Unified wallet for fast checkout, resumes, and training modules.</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fundWalletModal">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2.5;margin-right:4px;"><use href="#i-check"/></svg> Fund Wallet
                    </button>
                </div>
            </div>
        </section>
 
        <!-- escrow alert card -->
        <section class="card" aria-label="Wallet Policy" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; background: #fff8f0; border-color: #ffd9a8;">
            <div>
                <h4 style="font-family:'Sora',sans-serif; font-size: 0.94rem; font-weight: 800; color: var(--accent-dark); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg> Escrow Policy
                </h4>
                <p style="font-size: 0.8rem; color: var(--accent-dark); line-height: 1.5; margin: 0;">All funded wallet balances are kept safely in escrow and strictly cannot be withdrawn or refunded. Wallet funds can be used at any time to purchase platform services.</p>
            </div>
            <div style="margin-top: 14px; font-size: 0.72rem; color: var(--accent-dark); font-weight: 700;">
                • Withdrawals Disabled
            </div>
        </section>
    </div>
 
    <!-- Transaction History -->
    <div style="margin-top: 24px;">
        <section class="card" aria-label="Transaction Logs" style="padding: 24px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.05rem; font-weight:800; color:var(--brand-deep); margin-bottom:18px;">Transaction History</h3>
            
            <div class="table-responsive">
                <table class="table" style="width:100%; border-collapse:collapse; font-size:0.84rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border); text-align: left; color: var(--muted); font-weight: 700;">
                            <th style="padding:12px 8px;">Reference</th>
                            <th style="padding:12px 8px;">Type</th>
                            <th style="padding:12px 8px;">Amount</th>
                            <th style="padding:12px 8px;">Description</th>
                            <th style="padding:12px 8px;">Date</th>
                            <th style="padding:12px 8px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding:32px; color:var(--muted);">No wallet transactions found. Fund your wallet to get started!</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($transactions as $tx): ?>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding:14px 8px; font-weight:600; color:var(--brand-deep);"><?= esc($tx->reference) ?></td>
                                <td style="padding:14px 8px;">
                                    <span class="pill pill--<?= ($tx->type === 'credit') ? 'success' : 'closed' ?>" style="font-size:0.7rem; padding:2px 8px;">
                                        <?= ucfirst($tx->type) ?>
                                    </span>
                                </td>
                                <td style="padding:14px 8px; font-weight:700; color:var(--brand-deep);">
                                    <?= ($tx->type === 'credit') ? '+' : '-' ?>₦<?= number_format((float)$tx->amount, 2) ?>
                                </td>
                                <td style="padding:14px 8px; color:var(--muted);"><?= esc($tx->description) ?></td>
                                <td style="padding:14px 8px; color:var(--muted); font-size:0.8rem;"><?= date('M d, Y h:i A', strtotime($tx->created_at)) ?></td>
                                <td style="padding:14px 8px;">
                                    <span class="pill pill--<?= ($tx->status ?? 'completed') === 'completed' ? 'success' : 'closed' ?>" style="font-size:0.7rem; padding:2px 8px;">
                                        <?= ucfirst($tx->status ?? 'completed') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
 
</div>
 
<!-- Fund Wallet Modal -->
<div class="modal fade" id="fundWalletModal" tabindex="-1" aria-labelledby="fundWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#fff; border:none; border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid var(--border); padding:20px 24px;">
                <h5 class="modal-title" id="fundWalletModalLabel" style="font-family:'Sora',sans-serif; font-size:1.1rem; font-weight:800; color:var(--brand-deep); margin:0;">Fund Wallet Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none; font-size:1.4rem; color:var(--muted); cursor:pointer;">&times;</button>
            </div>
            <form action="<?= base_url('wallet/initialize') ?>" method="POST" id="fundWalletForm">
                <?= csrf_field() ?>
                <div class="modal-body" style="padding:24px; display:flex; flex-direction:column; gap:16px;">
                    <p style="font-size:0.82rem; color:var(--muted); margin:0;">Select or enter the amount you wish to deposit. You will be redirected to Paystack to complete your payment.</p>
                    
                    <!-- Predefined values -->
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:8px;">
                        <button type="button" class="btn btn-outline preset-amount-btn" data-val="2000" style="padding:8px 0; font-size:0.82rem;">₦2,000</button>
                        <button type="button" class="btn btn-outline preset-amount-btn" data-val="5000" style="padding:8px 0; font-size:0.82rem;">₦5,000</button>
                        <button type="button" class="btn btn-outline preset-amount-btn" data-val="10000" style="padding:8px 0; font-size:0.82rem;">₦10,000</button>
                    </div>

                    <div class="form-field">
                        <label class="lbl">CUSTOM DEPOSIT AMOUNT (NGN)</label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:0.9rem; color:var(--muted); font-weight:700;">₦</span>
                            <input type="number" name="amount" id="depositAmount" class="input" min="100" step="100" placeholder="Minimum 100" required style="padding-left:32px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); padding:20px 24px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Proceed to Pay</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle preset amount button clicks
        const presetBtns = document.querySelectorAll(".preset-amount-btn");
        const amountInput = document.getElementById("depositAmount");

        presetBtns.forEach(btn => {
            btn.addEventListener("click", function () {
                presetBtns.forEach(b => {
                    b.classList.remove("btn-primary");
                    b.classList.add("btn-outline");
                });

                this.classList.remove("btn-outline");
                this.classList.add("btn-primary");
                
                amountInput.value = this.getAttribute("data-val");
            });
        });

        amountInput.addEventListener("input", function() {
            presetBtns.forEach(b => {
                b.classList.remove("btn-primary");
                b.classList.add("btn-outline");
            });
        });
    });
</script>
<?= $this->endSection() ?>

