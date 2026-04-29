<div class="card mt-4">
    <div class="card-header">
        <h5 class="fw-bold">Compare Subscription Plans</h5>
        <small class="text-muted">
            Plans give monthly credits & features. Credits reset every month.
        </small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Features</th>
                    <?php foreach ($plans as $plan): ?>
                        <th><?= esc($plan->name) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>

                <!-- Monthly Credits -->
                <tr>
                    <td><strong>Monthly Job Credits</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td>
                            <?= $plan->monthly_job_credits ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <!-- Credit Reset -->
                <tr>
                    <td><strong>Credit Reset</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td>Monthly</td>
                    <?php endforeach; ?>
                </tr>

                <!-- Feature Rows -->
                <?php
                $featureLabels = [
                    'featured'        => 'Featured Jobs',
                    'network_blast'   => 'Network Blast',
                    'anonymous'       => 'Anonymous Posting',
                    'url_redirect'    => 'External Application Link',
                    'trust_badge'     => 'Verified Hirer Badge',
                    'priority_support' => 'Priority Support',
                ];
                ?>

                <?php foreach ($featureLabels as $key => $label): ?>
                    <tr>
                        <td><strong><?= $label ?></strong></td>
                        <?php foreach ($plans as $plan): ?>
                            <?php
                            $features = $plan->features;
                            $enabled  = $features->$key ?? false;
                            ?>
                            <td>
                                <i class="ti ti-<?= $enabled ? 'check text-success' : 'x text-muted' ?>"></i>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>

                <!-- Billing Type -->
                <tr>
                    <td><strong>Billing Type</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td class="text-capitalize">
                            <?= esc($plan->billing_type) ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <!-- Price -->
                <tr>
                    <td><strong>Price</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td>
                            <?php if ((float)$plan->price === 0.0): ?>
                                <span class="badge bg-success-transparent">Free</span>
                            <?php else: ?>
                                ₦<?= number_format($plan->price) ?>/month
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

            </tbody>
        </table>
    </div>
</div>