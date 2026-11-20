<?php
$accountUrl = base_url('admin/employers/account/' . (int) $employer->id);
$subscription = $account['subscription'];
?>
<section class="card custom-card" aria-labelledby="employer-account-heading">
    <div class="card-header"><h2 id="employer-account-heading" class="card-title mb-0">Account management</h2></div>
    <div class="card-body">
        <?php foreach (['success' => 'success', 'error' => 'danger'] as $flash => $style): ?>
            <?php if ($message = session()->getFlashdata($flash)): ?>
                <div class="alert alert-<?= $style ?>" role="status"><?= esc($message) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><span class="text-muted">Wallet balance</span><div class="fw-semibold fs-18">₦<?= number_format($account['balance'], 2) ?></div></div>
            <div class="col-md-3"><span class="text-muted">Available job credits</span><div class="fw-semibold fs-18"><?= number_format($account['credits']) ?></div></div>
            <div class="col-md-5"><span class="text-muted">Current subscription</span><div class="fw-semibold"><?= esc($subscription->plan_name ?? 'No active subscription') ?></div>
                <?php if ($subscription): ?><small>Expires <?= esc(date('d M Y', strtotime($subscription->ends_at))) ?></small><?php endif; ?>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <form method="post" action="<?= esc($accountUrl, 'attr') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="request_key" value="<?= bin2hex(random_bytes(16)) ?>">
                    <input type="hidden" name="action" value="subscription">
                    <h3 class="fs-15">Assign subscription</h3>
                    <p class="text-muted small">Starts immediately and replaces the current subscription and its unused subscription credits. Bundle and manual credits are kept. No wallet charge or automatic renewal.</p>
                    <label for="account-plan" class="form-label">Employer package</label>
                    <select id="account-plan" name="item_id" class="form-select mb-2" required>
                        <option value="">Select a package</option>
                        <?php foreach ($account['plans'] as $plan): ?><option value="<?= (int) $plan->id ?>"><?= esc($plan->name) ?> · <?= (int) $plan->monthly_job_credits ?> job credits</option><?php endforeach; ?>
                    </select>
                    <label for="account-expiry" class="form-label">Expiry date</label>
                    <input id="account-expiry" type="date" name="ends_on" class="form-control mb-2" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+10 years')) ?>" value="<?= date('Y-m-d', strtotime('+1 month')) ?>" required>
                    <label for="account-plan-reason" class="form-label">Reason</label>
                    <input id="account-plan-reason" name="reason" class="form-control mb-3" maxlength="200" required>
                    <button type="submit" class="btn btn-primary">Assign subscription</button>
                </form>
            </div>
            <div class="col-lg-6">
                <form method="post" action="<?= esc($accountUrl, 'attr') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="request_key" value="<?= bin2hex(random_bytes(16)) ?>">
                    <input type="hidden" name="action" value="bundle">
                    <h3 class="fs-15">Grant bundle</h3>
                    <p class="text-muted small">Adds the selected bundle’s job credits without charging the employer’s wallet or changing their subscription.</p>
                    <label for="account-bundle" class="form-label">Job credit bundle</label>
                    <select id="account-bundle" name="item_id" class="form-select mb-2" required>
                        <option value="">Select a bundle</option>
                        <?php foreach ($account['bundles'] as $bundle): ?><option value="<?= (int) $bundle->id ?>"><?= esc($bundle->name) ?> · <?= (int) $bundle->job_credits ?> credits</option><?php endforeach; ?>
                    </select>
                    <label for="account-bundle-reason" class="form-label">Reason</label>
                    <input id="account-bundle-reason" name="reason" class="form-control mb-3" maxlength="200" required>
                    <button type="submit" class="btn btn-primary">Grant bundle</button>
                </form>
            </div>
            <div class="col-lg-6">
                <form method="post" action="<?= esc($accountUrl, 'attr') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="request_key" value="<?= bin2hex(random_bytes(16)) ?>">
                    <input type="hidden" name="action" value="credits">
                    <h3 class="fs-15">Set job credits</h3>
                    <p class="text-muted small">Sets the total available balance to this number, including zero. Added manual credits do not expire. Existing credits keep their expiry dates.</p>
                    <label for="account-credits" class="form-label">New available balance</label>
                    <input id="account-credits" type="number" name="credits" class="form-control mb-2" min="0" max="1000000" step="1" value="<?= (int) $account['credits'] ?>" required>
                    <label for="account-credit-reason" class="form-label">Reason</label>
                    <input id="account-credit-reason" name="reason" class="form-control mb-3" maxlength="200" required>
                    <button type="submit" class="btn btn-primary">Set job credits</button>
                </form>
            </div>
            <div class="col-lg-6">
                <form method="post" action="<?= esc($accountUrl, 'attr') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="request_key" value="<?= bin2hex(random_bytes(16)) ?>">
                    <input type="hidden" name="action" value="fund">
                    <h3 class="fs-15">Fund wallet</h3>
                    <p class="text-muted small">Adds NGN funds to the employer’s existing wallet and records the deposit in their transaction history.</p>
                    <label for="account-amount" class="form-label">Amount to add (NGN)</label>
                    <input id="account-amount" type="number" name="amount" class="form-control mb-2" min="0.01" max="999999999.99" step="0.01" required>
                    <label for="account-fund-reason" class="form-label">Reason</label>
                    <input id="account-fund-reason" name="reason" class="form-control mb-3" maxlength="200" required>
                    <button type="submit" class="btn btn-primary">Add wallet funds</button>
                </form>
            </div>
        </div>
        <?php if ($account['history']): ?>
            <h3 class="fs-15 mt-4">Recent manual changes</h3>
            <div class="table-responsive"><table class="table table-sm mb-0">
                <thead><tr><th>Date</th><th>Change</th><th>Admin</th></tr></thead>
                <tbody><?php foreach ($account['history'] as $entry): $meta = json_decode($entry->meta, true) ?? []; ?>
                    <tr><td><?= esc($entry->created_at) ?></td><td><?= esc($entry->description) ?>
                        <?php if (isset($meta['amount'])): ?><div>₦<?= number_format((float) $meta['amount'], 2) ?> added</div>
                        <?php else: ?><div>Credits: <?= (int) ($meta['credits_before'] ?? 0) ?> → <?= (int) ($meta['credits_after'] ?? 0) ?></div><?php endif; ?>
                    </td><td><?= (int) ($meta['admin_id'] ?? 0) ?></td></tr>
                <?php endforeach; ?></tbody>
            </table></div>
        <?php endif; ?>
    </div>
</section>
