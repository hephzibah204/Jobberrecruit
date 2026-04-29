<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Subscription Plans</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Subscription Plans</li>
                </ol>
            </div>

            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/bundles') ?>" class="btn btn-outline-primary">
                    <i class="ti ti-settings"></i> Growth Bundles
                </a>
                <button type="button" class="btn btn-primary" onclick='openPlanModal(<?= json_encode($subscriptionPlan) ?>)'>
                    <i class="ti ti-edit"></i> Edit Subscription Plan
                </button>
            </div>
        </div>
    </div>

    <!-- SINGLE SUBSCRIPTION PLAN CARD -->
    <div class="card custom-card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Main Subscription Plan</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($subscriptionPlan) && $subscriptionPlan->id): ?>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Plan Name:</strong><br>
                        <h4 class="fw-bold"><?= esc($subscriptionPlan->name) ?></h4>
                        <small class="text-muted"><?= esc($subscriptionPlan->code) ?></small>
                    </div>
                    <div class="col-md-4">
                        <strong>Base Monthly Price:</strong><br>
                        <h4 class="text-primary">₦<?= number_format($subscriptionPlan->base_price ?? 0) ?></h4>
                    </div>
                    <div class="col-md-4">
                        <strong>Paystack Status:</strong><br>
                        <span class="badge bg-<?= !empty($subscriptionPlan->paystack_plan_code) ? 'success' : 'warning' ?>-transparent">
                            <?= !empty($subscriptionPlan->paystack_plan_code) ? 'Linked' : 'Not Linked' ?>
                        </span>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold mb-3">Pricing Tiers (Duration-based)</h6>
                <div class="row g-3">
                    <?php
                    $tiers = is_string($subscriptionPlan->pricing_tiers)
                        ? json_decode($subscriptionPlan->pricing_tiers, true)
                        : ($subscriptionPlan->pricing_tiers ?? []);
                    $durations = [1 => '1 Month', 3 => '3 Months', 6 => '6 Months', 12 => '12 Months'];
                    ?>
                    <?php foreach ($durations as $months => $label): ?>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <div class="fw-medium"><?= $label ?></div>
                                <h5 class="text-success mb-0">
                                    ₦<?= number_format($tiers[$months] ?? 0) ?>
                                </h5>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No subscription plan created yet. Click "Edit Subscription Plan" to create one.</p>
            <?php endif ?>
        </div>
    </div>

    <!-- PLANS TABLE (Simplified - Mostly for Bundles Reference or Future Plans) -->
    <div class="card custom-card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Plans Overview</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Base Price</th>
                            <th>Type</th>
                            <th>Features</th>
                            <th>Paystack</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($subscriptionPlan)): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= esc($subscriptionPlan->name) ?></div>
                                    <div class="fs-12 text-muted"><?= esc($subscriptionPlan->code) ?></div>
                                </td>
                                <td>₦<?= number_format($subscriptionPlan->base_price ?? 0) ?> <small>(monthly base)</small></td>
                                <td>
                                    <span class="badge bg-primary-transparent">Subscription</span>
                                </td>
                                <td>
                                    <?php
                                    $features = is_string($subscriptionPlan->features)
                                        ? json_decode($subscriptionPlan->features, true)
                                        : [];
                                    ?>
                                    <?php foreach ($features as $key => $enabled): ?>
                                        <?php if ($enabled): ?>
                                            <span class="badge bg-primary-transparent me-1 mb-1">
                                                <?= ucwords(str_replace('_', ' ', $key)) ?>
                                            </span>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= !empty($subscriptionPlan->paystack_plan_code) ? 'success' : 'warning' ?>-transparent">
                                        <?= !empty($subscriptionPlan->paystack_plan_code) ? 'Linked' : 'Not Linked' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light border"
                                        onclick='openPlanModal(<?= json_encode($subscriptionPlan) ?>)'>
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No subscription plan found</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SUBSCRIPTIONS OVERVIEW (Keep your existing charts and table) -->
    <div class="card custom-card mt-4">
        <div class="card-body p-4">
            <h1 class="page-title fw-medium fs-18 mb-4">Subscriptions Overview</h1>

            <!-- Charts Row (unchanged) -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Plans Distribution</div>
                        </div>
                        <div class="card-body">
                            <canvas id="plansChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Monthly Revenue</div>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Subscription Status</div>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions Table (Keep as-is or minor updates) -->
            <!-- ... your existing subscriptions table code ... -->
            <!-- (I left it out here for brevity, but you can keep your original code) -->

        </div>
    </div>

    <!-- Add this section to your admin/plans.php view -->
    <div class="card custom-card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Enterprise/Unlimited Access Management</h5>
        </div>
        <div class="card-body">
            <form id="unlimitedAccessForm" class="mb-4">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Select Employer</label>
                        <select name="employer_id" class="form-select" required>
                            <option value="">Choose employer...</option>
                            <?php foreach ($employers as $employer): ?>
                                <option value="<?= $employer->id ?>">
                                    <?= esc($employer->company_name) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unlimited Until (optional)</label>
                        <input type="datetime-local" name="unlimited_until" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block w-100">
                            <i class="ti ti-infinity"></i> Grant Unlimited Access
                        </button>
                    </div>
                </div>
            </form>

            <hr>

            <h6 class="fw-semibold mb-3">Employers with Unlimited Access</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Unlimited Until</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employersWithUnlimited as $employer): ?>
                            <tr>
                                <td><?= esc($employer->company_name) ?></td>
                                <td>
                                    <?= $employer->unlimited_until ? date('M d, Y', strtotime($employer->unlimited_until)) : 'Never' ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="revokeUnlimitedAccess(<?= $employer->id ?>)">
                                        Revoke
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- UPDATED PLAN MODAL (Only for Subscription Plan) -->
<div class="modal fade" id="planModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="planForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="plan_id">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Business Pro Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Plan Name *</label>
                    <input type="text" name="name" id="plan_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Plan Code *</label>
                    <input type="text" name="code" id="plan_code" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Base Monthly Price (₦) *</label>
                    <input type="number" name="base_price" id="plan_base_price"
                        class="form-control" min="0" step="100" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Pricing Tiers (Duration-based)</label>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">1 Month</label>
                            <input type="number" name="price_1" id="price_1" class="form-control" min="0" step="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">3 Months</label>
                            <input type="number" name="price_3" id="price_3" class="form-control" min="0" step="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">6 Months</label>
                            <input type="number" name="price_6" id="price_6" class="form-control" min="0" step="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">12 Months</label>
                            <input type="number" name="price_12" id="price_12" class="form-control" min="0" step="100">
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Features</label>
                    <div class="row g-2">
                        <?php
                        $featureList = [
                            'featured'          => 'Featured Position (Top)',
                            'network_blast'     => 'Network Blast (115k+ Reach)',
                            'anonymous'         => 'Anonymous Posting',
                            'url_redirect'      => 'Applicant URL Redirection',
                            'trust_badge'       => 'Verified Hirer Badge',
                            'priority_support'  => 'Priority WhatsApp Support'
                        ];
                        ?>
                        <?php foreach ($featureList as $key => $label): ?>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="<?= $key ?>" id="plan_<?= $key ?>" value="<?php if (isset($subscriptionPlan->features) && is_string($subscriptionPlan->features)) {
                                                                                            $features = json_decode($subscriptionPlan->features, true);
                                                                                            echo isset($features[$key]) && $features[$key] ? '1' : '0';
                                                                                        } else {
                                                                                            echo '0';
                                                                                        } ?>">
                                    <label class="form-check-label" for="plan_<?= $key ?>">
                                        <?= $label ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="planSubmitBtn">Save Plan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const planModal = new bootstrap.Modal('#planModal');

    function openPlanModal(plan = null) {
        document.getElementById('planForm').reset();
        document.getElementById('plan_id').value = '';

        if (plan) {
            document.getElementById('plan_id').value = plan.id || '';
            document.getElementById('plan_name').value = plan.name || 'Business Pro';
            document.getElementById('plan_code').value = plan.code || 'business_pro';
            document.getElementById('plan_base_price').value = plan.base_price || 18000;
            document.getElementById('plan_id').value = plan.id || '';

            // Populate pricing tiers
            let tiers = {};
            if (plan.pricing_tiers) {
                tiers = typeof plan.pricing_tiers === 'string' ?
                    JSON.parse(plan.pricing_tiers) :
                    plan.pricing_tiers;
            }
            document.getElementById('price_1').value = tiers[1] || 18000;
            document.getElementById('price_3').value = tiers[3] || '';
            document.getElementById('price_6').value = tiers[6] || '';
            document.getElementById('price_12').value = tiers[12] || '';

            // Populate features
            let features = {};
            if (plan.features) {
                features = typeof plan.features === 'string' ? JSON.parse(plan.features) : plan.features;
            }
            Object.keys(features).forEach(key => {
                const el = document.getElementById('plan_' + key);
                if (el) el.checked = !!features[key];
            });
        }

        document.getElementById('modalTitle').textContent = 'Edit Business Pro Subscription Plan';
        planModal.show();
    }

    // Form Submit
    document.getElementById('planForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('planSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/plans') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message || 'Plan saved successfully');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(res.message || 'Failed to save plan');
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save Plan';
            });
    });

    // Charts (keep your existing chart code)
    const plansData = <?= json_encode($planStats ?? []) ?>;

    new Chart(document.getElementById('plansChart'), {
        type: 'doughnut',
        data: {
            labels: plansData.map(p => p.name),
            datasets: [{
                data: plansData.map(p => p.total)
            }]
        }
    });

    const revenueData = <?= json_encode(array_values($monthlyRevenue ?? [])) ?>;

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue (₦)',
                data: revenueData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => '₦' + v.toLocaleString()
                    }
                }
            }
        }
    });

    // Handle unlimited access form submission
    document.getElementById('unlimitedAccessForm').addEventListener('submit', function(e) {
        e.preventDefault();

        fetch('<?= base_url("admin/plans/grant-unlimited-access") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success('Unlimited access granted successfully');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(res.message);
                }
            });
    });

    function revokeUnlimitedAccess(employerId) {
        if (confirm('Revoke unlimited access for this employer?')) {
            fetch('<?= base_url("admin/plans/revoke-unlimited-access") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        employer_id: employerId
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        toastr.success('Unlimited access revoked');
                        location.reload();
                    } else {
                        toastr.error(res.message);
                    }
                });
        }
    }
</script>
<?= $this->endSection() ?>