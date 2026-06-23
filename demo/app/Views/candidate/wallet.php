<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .premium-wallet-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(2, 6, 23, 0.95) 100%) !important;
        border: 1px solid rgba(245, 166, 35, 0.2) !important; /* Brand border */
        border-radius: 24px !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        z-index: 1;
    }

    .premium-wallet-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(245, 166, 35, 0.15) 0%, rgba(245, 166, 35, 0) 70%);
        z-index: -1;
        pointer-events: none;
    }

    .wallet-glow-balance {
        font-size: 3rem;
        font-weight: 800;
        color: var(--secondary-color) !important; /* Brand Color Primary */
        text-shadow: 0 0 20px rgba(245, 166, 35, 0.4);
        font-family: 'Outfit', sans-serif;
    }

    .stat-card-premium {
        background: rgba(15, 23, 42, 0.6) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 20px !important;
        transition: all 0.3s ease !important;
    }

    .text-gradient {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--text-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .action-btn-pill {
        border-radius: 50px !important;
        padding: 0.75rem 1.75rem !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        transition: all 0.25s ease !important;
    }

    .action-btn-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 166, 35, 0.3);
    }

    .btn-brand {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-hover) 100%) !important;
        border: none !important;
        color: #fff !important;
    }

    .list-item-glass {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        transition: all 0.25s ease;
    }

    .badge-soft-success {
        background: rgba(16, 185, 129, 0.1) !important;
        color: #10b981 !important;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-soft-danger {
        background: rgba(239, 68, 68, 0.1) !important;
        color: var(--danger-color) !important;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .text-muted-light {
        color: var(--text-muted) !important;
    }

    .preset-amount-btn {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: var(--text-light) !important;
        transition: all 0.2s ease !important;
    }

    .preset-amount-btn:hover {
        background: rgba(245, 166, 35, 0.1) !important;
        border-color: rgba(245, 166, 35, 0.3) !important;
        color: var(--secondary-color) !important;
    }

    .preset-amount-btn.active, .preset-amount-btn.btn-warning {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-hover) 100%) !important;
        border-color: var(--secondary-color) !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
 
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="page-title">
            <h4 class="fw-bold" style="color: #000 !important;"><i class="ti ti-wallet me-2 text-warning"></i>My Wallet</h4>
            <h6 class="text-muted-light" style="color: #000 !important; opacity: 0.75;">Fund your account and make instant payments for platform services.</h6>
        </div>
    </div>
 
    <!-- Main Wallet Card -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="premium-wallet-card p-5">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-4 mb-md-0">
                        <span class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill fw-bold mb-3">
                            <i class="ti ti-wallet me-1"></i> Active Wallet Balance
                        </span>
                        <h1 class="wallet-glow-balance mb-2">₦<?= number_format((float) ($wallet->balance ?? 0), 2) ?></h1>
                        <p class="text-muted-light mb-0 fs-13">Unified wallet for fast checkout, resumes, and training modules.</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <button class="btn btn-brand action-btn-pill px-4 py-3" data-bs-toggle="modal" data-bs-target="#fundWalletModal">
                            <i class="ti ti-plus me-1"></i> Fund My Wallet
                        </button>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="stat-card-premium p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold text-white mb-2"><i class="ti ti-shield-check me-2 text-warning"></i>Strict Non-Refundable Policy</h5>
                    <p class="text-muted-light fs-13 lh-base">All funded wallet balances are kept safely in escrow. They strictly cannot be withdrawn or refunded. Wallet funds can be used at any time to purchase platform services.</p>
                </div>
                <div class="d-flex align-items-center gap-2 text-warning fs-12 mt-3 fw-bold">
                    <i class="ti ti-info-circle"></i> Withdrawals Disabled
                </div>
            </div>
        </div>
    </div>
 
    <!-- Transaction History -->
    <div class="row">
        <div class="col-12">
            <div class="stat-card-premium p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0"><i class="ti ti-history me-2 text-primary"></i>Wallet Transaction History</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" style="color: var(--text-light);">
                            <thead>
                                <tr class="text-muted-light" style="border-bottom: 1px solid rgba(255,255,255,0.08); color: var(--text-muted);">
                                    <th>Reference</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted-light py-4">No wallet transactions found. Fund your wallet to get started!</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($transactions as $tx): ?>
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                        <td class="fw-semibold text-white"><?= esc($tx->reference) ?></td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-2 <?= ($tx->type === 'credit') ? 'badge-soft-success' : 'badge-soft-danger' ?>">
                                                <?= ucfirst($tx->type) ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold text-white">
                                            <?= ($tx->type === 'credit') ? '+' : '-' ?>₦<?= number_format((float)$tx->amount, 2) ?>
                                        </td>
                                        <td class="text-muted-light fs-13"><?= esc($tx->description) ?></td>
                                        <td><?= date('M d, Y H:i A', strtotime($tx->created_at)) ?></td>
                                        <td>
                                            <span class="badge badge-soft-success rounded-pill px-3 py-1">Completed</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
</div>
 
<!-- Fund Wallet Modal -->
<div class="modal fade" id="fundWalletModal" tabindex="-1" aria-labelledby="fundWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #151d30; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
            <div class="modal-header border-bottom border-secondary border-opacity-10 p-4">
                <h5 class="modal-title fw-bold text-white" id="fundWalletModalLabel"><i class="ti ti-wallet me-2 text-warning"></i>Fund Wallet Balance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('wallet/initialize') ?>" method="POST" id="fundWalletForm">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p class="text-muted-light fs-13 mb-4">Select or enter the amount you wish to deposit. You will be redirected to Paystack to complete your payment.</p>
                    
                    <!-- Predefined values -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <button type="button" class="btn preset-amount-btn w-100 rounded-pill py-2" data-val="2000">₦2,000</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn preset-amount-btn w-100 rounded-pill py-2" data-val="5000">₦5,000</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn preset-amount-btn w-100 rounded-pill py-2" data-val="10000">₦10,000</button>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-white-50 fs-12 fw-bold mb-2">CUSTOM DEPOSIT AMOUNT (NGN)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff;">₦</span>
                            <input type="number" name="amount" id="depositAmount" class="form-control" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.1); color: #fff;" min="100" step="100" placeholder="Minimum 100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-10 p-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand rounded-pill px-4"><i class="ti ti-credit-card me-1"></i> Proceed to Pay</button>
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
                presetBtns.forEach(b => b.classList.remove("active", "btn-warning"));
                presetBtns.forEach(b => b.classList.add("btn-outline-secondary"));

                this.classList.remove("btn-outline-secondary");
                this.classList.add("active", "btn-warning");
                
                amountInput.value = this.getAttribute("data-val");
            });
        });

        amountInput.addEventListener("input", function() {
            presetBtns.forEach(b => b.classList.remove("active", "btn-warning"));
            presetBtns.forEach(b => b.classList.add("btn-outline-secondary"));
        });
    });
</script>
<?= $this->endSection() ?>
