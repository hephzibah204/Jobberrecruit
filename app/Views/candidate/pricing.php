<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold"><i class="ti ti-crown me-2 text-warning"></i>Premium Plans</h4>
            <h6>Unlock powerful features to accelerate your job search</h6>
        </div>
    </div>

    <?php if ($isFreeMode): ?>
        <div class="alert alert-success text-center mb-4">
            <i class="ti ti-gift me-2"></i>
            <strong>All features are currently free!</strong> Enjoy full access to JobberRecruit at no cost.
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <?php if (empty($plans)): ?>
            <div class="col-md-8 text-center py-5">
                <i class="ti ti-package text-muted mb-3" style="width: 48px; height: 48px;"></i>
                <h5 class="text-muted">No plans available</h5>
                <p class="text-muted">Check back later for premium plans.</p>
            </div>
        <?php else: ?>
            <?php foreach ($plans as $plan): ?>
                <?php
                    $features = json_decode($plan->features ?? '[]', true) ?? [];
                    $price = (float) ($plan->base_price ?? 0);
                    $isCurrent = $currentPlan && $currentPlan->plan_id == $plan->id;
                    $isPopular = in_array(strtolower($plan->name), ['pro', 'professional', 'gold']);
                ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 <?= $isPopular ? 'border-primary shadow' : '' ?> <?= $isCurrent ? 'border-success' : '' ?>">
                        <?php if ($isPopular): ?>
                            <div class="card-header bg-primary text-white text-center">
                                <span class="badge bg-warning text-dark">Most Popular</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($isCurrent): ?>
                            <div class="card-header bg-success text-white text-center">
                                <span class="badge bg-light text-success">Current Plan</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold mb-1"><?= esc($plan->name) ?></h5>
                            <p class="text-muted small mb-3"><?= esc($plan->code ?? '') ?></p>
                            
                            <div class="mb-4">
                                <?php if ($price <= 0): ?>
                                    <h2 class="fw-bold text-primary mb-0">FREE</h2>
                                <?php else: ?>
                                    <h2 class="fw-bold text-primary mb-0">₦<?= number_format($price) ?></h2>
                                    <small class="text-muted">/month</small>
                                <?php endif; ?>
                            </div>

                            <ul class="list-unstyled text-start mb-4">
                                <?php foreach ($features as $feature => $enabled): ?>
                                    <?php if ($enabled): ?>
                                        <li class="mb-2">
                                            <i class="ti ti-check text-success me-2"></i>
                                            <small><?= esc(ucwords(str_replace('_', ' ', $feature))) ?></small>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>

                            <?php if ($isCurrent): ?>
                                <button class="btn btn-success w-100" disabled>Active Plan</button>
                            <?php elseif ($isFreeMode || $price <= 0): ?>
                                <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <?= $price <= 0 ? 'Activate Free Plan' : 'Get Started' ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="<?= base_url('candidate/subscription/checkout') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Subscribe Now
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Feature Comparison -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Feature Comparison</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Feature</th>
                            <?php foreach ($plans as $plan): ?>
                                <th><?= esc($plan->name) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $allFeatures = [];
                            foreach ($plans as $plan) {
                                $features = json_decode($plan->features ?? '[]', true) ?? [];
                                $allFeatures = array_unique(array_merge($allFeatures, array_keys($features)));
                            }
                            foreach ($allFeatures as $feature):
                        ?>
                            <tr>
                                <td class="text-start"><?= esc(ucwords(str_replace('_', ' ', $feature))) ?></td>
                                <?php foreach ($plans as $plan): ?>
                                    <?php
                                        $features = json_decode($plan->features ?? '[]', true) ?? [];
                                        $enabled = !empty($features[$feature]);
                                    ?>
                                    <td>
                                        <?php if ($enabled): ?>
                                            <i class="ti ti-check text-success"></i>
                                        <?php else: ?>
                                            <i class="ti ti-x text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
