<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    /* Premium Welcome Wrap - Light Theme */
    .premium-wallet-card {
        background: linear-gradient(135deg, #fff 0%, var(--bg-light) 100%) !important;
        border: 1px solid rgba(245, 166, 35, 0.25) !important; /* Brand border */
        border-radius: 20px !important;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 30px -10px rgba(245, 166, 35, 0.15), 0 1px 3px rgba(0, 0, 0, 0.05);
        z-index: 1;
    }

    .premium-wallet-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(245, 166, 35, 0.08) 0%, rgba(245, 166, 35, 0) 70%);
        z-index: -1;
        pointer-events: none;
    }

    /* Light Theme Stats Cards */
    .stat-card-premium {
        background: #fff !important;
        border: 1px solid var(--border-light) !important;
        border-radius: 16px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .stat-card-premium:hover {
        border-color: rgba(245, 166, 35, 0.3) !important;
        box-shadow: 0 8px 25px -5px rgba(245, 166, 35, 0.1) !important;
        transform: translateY(-2px);
    }

    .wallet-glow-balance {
        font-size: 3rem;
        font-weight: 800;
        color: var(--secondary-color) !important; /* Brand Color Primary */
        font-family: 'Outfit', sans-serif;
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
        box-shadow: 0 8px 20px rgba(245, 166, 35, 0.25);
    }

    .btn-brand {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-hover) 100%) !important;
        border: none !important;
        color: #fff !important;
    }

    /* Text gradient for headings */
    .text-gradient {
        background: linear-gradient(90deg, var(--primary-color) 0%, #0891b2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .list-item-glass {
        background: var(--bg-light);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        transition: all 0.25s ease;
    }

    .badge-soft-success {
        background: rgba(5, 150, 105, 0.1) !important;
        color: #059669 !important;
        border: 1px solid rgba(5, 150, 105, 0.15);
    }

    .badge-soft-danger {
        background: rgba(220, 38, 38, 0.1) !important;
        color: #dc2626 !important;
        border: 1px solid rgba(220, 38, 38, 0.15);
    }

    .text-muted-light {
        color: var(--text-muted) !important;
    }

    .preset-amount-btn {
        background: var(--bg-light) !important;
        border: 1px solid var(--border-light) !important;
        color: var(--text-muted) !important;
        transition: all 0.2s ease !important;
        font-weight: 600 !important;
    }

    .preset-amount-btn:hover {
        background: rgba(245, 166, 35, 0.08) !important;
        border-color: rgba(245, 166, 35, 0.4) !important;
        color: var(--secondary-hover) !important;
    }

    .preset-amount-btn.active, .preset-amount-btn.btn-warning {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-hover) 100%) !important;
        border-color: var(--secondary-color) !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(245, 166, 35, 0.25) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="page-title">
            <h4 class="fw-bold" style="color: #000 !important;"><i class="ti ti-wallet me-2 text-primary"></i>My Wallet</h4>
            <h6 class="text-muted-light" style="color: #000 !important; opacity: 0.75;">Fund your balance and execute fast checkouts for subscriptions and credit bundles.</h6>
        </div>
    </div>

    <!-- Main Wallet Card -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="premium-wallet-card p-5">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-4 mb-md-0">
                        <span class="badge bg-primary-transparent text-primary px-3 py-2 rounded-pill fw-bold mb-3" style="border: 1px solid rgba(3,100,179,0.15);">
                            <i class="ti ti-wallet me-1"></i> Active Wallet Balance
                        </span>
                        <h1 class="wallet-glow-balance mb-2">₦<?= number_format((float) ($wallet->balance ?? 0), 2) ?></h1>
                        <p class="text-muted-light mb-0 fs-13">Avoid recurrent gateways. Load your balance and deploy hires in a single click.</p>
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
                    <h5 class="fw-bold mb-2 text-main" ><i class="ti ti-shield-check me-2 text-warning"></i>Escrow Deposit Policy</h5>
                    <p class="text-muted-light fs-13 lh-base">Funds deposited are kept in safe platform escrow. Please note that wallet funds **cannot be withdrawn or refunded**. You can use these funds to purchase any hiring subscription or credit package at any time.</p>
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
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-light) !important;">
                    <h5 class="fw-bold mb-0 text-main" ><i class="ti ti-history me-2 text-primary"></i>Wallet Transaction History</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" style="color: var(--text-muted);">
                            <thead>
                                <tr class="text-muted" style="border-bottom: 1px solid var(--border-light);">
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
                                    <tr style="border-bottom: 1px solid rgba(226,232,240,0.4);">
                                        <td class="fw-semibold text-main" ><?= esc($tx->reference) ?></td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-2 <?= ($tx->type === 'credit') ? 'badge-soft-success' : 'badge-soft-danger' ?>">
                                                <?= ucfirst($tx->type) ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold text-main" >
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
        <div class="modal-content" style="background: #fff; border: 1px solid var(--border-light); border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-main" id="fundWalletModalLabel"><i class="ti ti-wallet me-2 text-warning"></i>Fund Wallet Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('wallet/initialize') ?>" method="POST" id="fundWalletForm">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p class="text-muted-light fs-13 mb-4">Select or enter the amount you wish to deposit. You will be redirected to Paystack to complete your payment.</p>
                    
                    <!-- Predefined values -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <button type="button" class="btn preset-amount-btn w-100 rounded-pill py-2" data-val="10000">₦10,000</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn preset-amount-btn w-100 rounded-pill py-2" data-val="25000">₦25,000</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn preset-amount-btn w-100 rounded-pill py-2" data-val="50000">₦50,000</button>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-muted fs-12 fw-bold mb-2">CUSTOM DEPOSIT AMOUNT (NGN)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--bg-light); border-color: var(--border-light); color: var(--text-muted);">₦</span>
                            <input type="number" name="amount" id="depositAmount" class="form-control text-main" min="100" step="100" placeholder="Minimum 100" required style="background: #fff; border-color: var(--border-light)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
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
