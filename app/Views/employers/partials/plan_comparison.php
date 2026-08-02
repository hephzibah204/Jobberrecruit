<div class="card" style="margin-top: 24px;">
    <div class="card-head">
        <h2 class="card-title">
            <svg aria-hidden="true"><use href="#i-zap"/></svg>
            Compare Subscription Plans
        </h2>
    </div>

    <div class="data-table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="padding: 12px 16px;">Features</th>
                    <?php foreach ($plans as $plan): ?>
                        <th style="padding: 12px 16px; text-align: center; font-weight: 700; color: var(--brand-deep);"><?= esc($plan->name) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <!-- Monthly Credits -->
                <tr>
                    <td style="padding: 12px 16px;"><strong>Monthly Job Credits</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td style="padding: 12px 16px; text-align: center; font-weight: 600; color: var(--brand-deep);">
                            <?= $plan->monthly_job_credits ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <!-- Credit Reset -->
                <tr>
                    <td style="padding: 12px 16px;"><strong>Credit Reset</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td style="padding: 12px 16px; text-align: center; color: var(--muted);">Monthly</td>
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
                        <td style="padding: 12px 16px;"><strong><?= $label ?></strong></td>
                        <?php foreach ($plans as $plan): ?>
                            <?php
                            $features = $plan->features;
                            $enabled  = $features->$key ?? false;
                            ?>
                            <td style="padding: 12px 16px; text-align: center;">
                                <?php if ($enabled): ?>
                                    <svg aria-hidden="true" style="width: 16px; height: 16px; color: var(--success); margin: 0 auto;"><use href="#i-check-c"/></svg>
                                <?php else: ?>
                                    <svg aria-hidden="true" style="width: 14px; height: 14px; color: var(--muted); margin: 0 auto; opacity: 0.4;"><use href="#i-x"/></svg>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>

                <!-- Billing Type -->
                <tr>
                    <td style="padding: 12px 16px;"><strong>Billing Type</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td style="padding: 12px 16px; text-align: center; text-transform: capitalize; color: var(--muted);">
                            <?= esc($plan->billing_type) ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <!-- Price -->
                <tr>
                    <td style="padding: 12px 16px;"><strong>Price</strong></td>
                    <?php foreach ($plans as $plan): ?>
                        <td style="padding: 12px 16px; text-align: center; font-weight: 700; color: var(--brand-deep);">
                            <?php if ((float)$plan->price === 0.0): ?>
                                <span class="pill pill--success" style="font-size: 0.64rem; padding: 3px 8px;">Free</span>
                            <?php else: ?>
                                ₦<?= number_format($plan->price) ?>/mo
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>