<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Subscription Plans</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Plans & Subscriptions</li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-<?= $isFreeMode ? 'danger' : 'outline-warning' ?>" onclick="toggleFreeMode()">
                    <i class="ti ti-<?= $isFreeMode ? 'lock-open' : 'lock' ?>"></i> <?= $isFreeMode ? 'Disable Free Mode' : 'Enable Free Mode' ?>
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="openBundleModal()"><i class="ti ti-package me-1"></i> Add Growth Bundle</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#planModal" onclick="openCreateModal()"><i class="ti ti-plus me-1"></i> Add Plan</button>
            </div>
        </div>
    </div>

    <?php if ($isFreeMode): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4"><i class="ti ti-alert-triangle me-2"></i><div><strong>FREE MODE ACTIVE:</strong> All features are free for all users. No payments will be collected.</div></div>
    <?php endif; ?>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="planTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" id="employer-tab-btn" data-bs-toggle="tab" data-bs-target="#employerTab">Employer Plans (<?= count($employerPlans) ?>)</button></li>
        <li class="nav-item"><button class="nav-link" id="candidate-tab-btn" data-bs-toggle="tab" data-bs-target="#candidateTab">Candidate Plans (<?= count($candidatePlans) ?>)</button></li>
        <li class="nav-item"><button class="nav-link" id="bundle-tab-btn" data-bs-toggle="tab" data-bs-target="#bundleTab">Growth Bundles (<?= count($bundles) ?>)</button></li>
        <li class="nav-item"><button class="nav-link" id="subs-tab-btn" data-bs-toggle="tab" data-bs-target="#subsTab">Active Subscriptions</button></li>
        <li class="nav-item"><button class="nav-link" id="unlimited-tab-btn" data-bs-toggle="tab" data-bs-target="#unlimitedTab">Unlimited Access</button></li>
    </ul>

    <div class="tab-content">
        <!-- Employer Plans -->
        <div class="tab-pane fade show active" id="employerTab">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0">
                            <thead><tr><th>Plan</th><th>Code</th><th>Price</th><th>Credits</th><th>Features</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($employerPlans as $plan): 
                                    $pObj = (object) $plan;
                                    $features = is_string($pObj->features ?? null) ? (json_decode($pObj->features, true) ?? []) : (array)($pObj->features ?? []); 
                                ?>
                                    <tr>
                                        <td><strong><?= esc($pObj->name ?? '') ?></strong></td>
                                        <td><code><?= esc($pObj->code ?? '') ?></code></td>
                                        <td>₦<?= number_format((float)($pObj->base_price ?? 0)) ?></td>
                                        <td><?= $pObj->monthly_job_credits ?? '—' ?></td>
                                        <td><?= count(array_filter($features)) ?> enabled</td>
                                        <td><span class="badge bg-<?= !empty($pObj->is_active) ? 'success' : 'secondary' ?>"><?= !empty($pObj->is_active) ? 'Active' : 'Inactive' ?></span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary-light" onclick='editPlan(<?= json_encode($pObj) ?>)'><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deletePlan(<?= $pObj->id ?? 0 ?>)"><i class="ti ti-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($employerPlans)): ?><tr><td colspan="7" class="text-center p-4 text-muted">No employer plans. Click "Add Plan" to create one.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidate Plans -->
        <div class="tab-pane fade" id="candidateTab">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0">
                            <thead><tr><th>Plan</th><th>Code</th><th>Price</th><th>Features</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($candidatePlans as $plan): 
                                    $pObj = (object) $plan;
                                    $features = is_string($pObj->features ?? null) ? (json_decode($pObj->features, true) ?? []) : (array)($pObj->features ?? []); 
                                ?>
                                    <tr>
                                        <td><strong><?= esc($pObj->name ?? '') ?></strong></td>
                                        <td><code><?= esc($pObj->code ?? '') ?></code></td>
                                        <td>₦<?= number_format((float)($pObj->base_price ?? 0)) ?></td>
                                        <td><?= count(array_filter($features)) ?> enabled</td>
                                        <td><span class="badge bg-<?= !empty($pObj->is_active) ? 'success' : 'secondary' ?>"><?= !empty($pObj->is_active) ? 'Active' : 'Inactive' ?></span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary-light" onclick='editPlan(<?= json_encode($pObj) ?>)'><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deletePlan(<?= $pObj->id ?? 0 ?>)"><i class="ti ti-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($candidatePlans)): ?><tr><td colspan="6" class="text-center p-4 text-muted">No candidate plans. Click "Add Plan" to create one.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth Bundles -->
        <div class="tab-pane fade" id="bundleTab">
            <div class="card custom-card">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fs-16"><i class="ti ti-packages me-1 text-primary"></i> Growth Bundles (Pay-as-you-go Credits)</h5>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openBundleModal()"><i class="ti ti-plus me-1"></i> New Bundle</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Bundle Name</th>
                                    <th>Code</th>
                                    <th>Credits</th>
                                    <th>Price</th>
                                    <th>Cost per Credit</th>
                                    <th>Best Value</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bundles as $bundle): $bObj = (object)$bundle; ?>
                                    <tr>
                                        <td class="fw-semibold"><?= esc($bObj->name ?? '') ?></td>
                                        <td><code><?= esc($bObj->slug ?? '') ?></code></td>
                                        <td><?= (int)($bObj->job_credits ?? 0) ?> Posts</td>
                                        <td>₦<?= number_format((float) ($bObj->price ?? 0)) ?></td>
                                        <td>₦<?= number_format((float) ($bObj->price_per_credit ?? 0)) ?></td>
                                        <td>
                                            <?php if (!empty($bObj->is_best_value)): ?>
                                                <span class="badge bg-success">Best Value</span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= !empty($bObj->is_active) ? 'success' : 'secondary' ?>">
                                                <?= !empty($bObj->is_active) ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary-light"
                                                onclick='openBundleModal(<?= json_encode($bObj) ?>)'>
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($bundles)): ?><tr><td colspan="8" class="text-center p-4 text-muted">No growth bundles configured. Click "New Bundle" to add one.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="tab-pane fade" id="subsTab">
            <div class="card mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0 fs-16"><i class="ti ti-user-plus me-1 text-primary"></i> Assign Subscription</h5>
                </div>
                <div class="card-body">
                    <form id="assignSubscriptionForm" class="row g-3">
                        <?= csrf_field() ?>
                        <div class="col-md-3">
                            <label class="form-label">Select Plan *</label>
                            <select name="plan_id" id="assign_plan_id" class="form-select" required onchange="onPlanSelected()">
                                <option value="">Choose plan...</option>
                                <?php foreach ($employerPlans as $p): ?>
                                    <?php if (($p->base_price ?? 0) == 0): ?>
                                        <option value="<?= $p->id ?>" data-type="employer"><?= esc($p->name) ?> (Employer - Free)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php foreach ($candidatePlans as $p): ?>
                                    <?php if (($p->base_price ?? 0) == 0): ?>
                                        <option value="<?= $p->id ?>" data-type="candidate"><?= esc($p->name) ?> (Candidate - Free)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3" id="assign_employer_group" style="display: none;">
                            <label class="form-label">Select Employer *</label>
                            <select name="employer_id" id="assign_employer_id" class="form-select">
                                <option value="">Choose employer...</option>
                                <?php foreach ($allEmployers as $e): ?>
                                    <option value="<?= $e->user_id ?>"><?= esc($e->company_name ?: 'N/A') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3" id="assign_candidate_group" style="display: none;">
                            <label class="form-label">Select Candidate *</label>
                            <select name="candidate_id" id="assign_candidate_id" class="form-select">
                                <option value="">Choose candidate...</option>
                                <?php foreach ($allCandidates as $c): ?>
                                    <option value="<?= $c->user_id ?>"><?= esc($c->full_name ?: 'N/A') ?> (<?= esc($c->phone ?: 'N/A') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Starts At *</label>
                            <input type="date" name="starts_at" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ends At (Optional)</label>
                            <input type="date" name="ends_at" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-plus me-1"></i> Assign</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0">
                            <thead><tr><th>Subscriber</th><th>Plan</th><th>Type</th><th>End Date</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($subscriptions as $sub): 
                                    $subObj = (object) $sub;
                                    $subscriberName = '';
                                    $profileUrl = '';
                                    $userTypeBadge = 'Candidate';
                                    $badgeClass = 'info';

                                    if (($subObj->plan_type ?? '') === 'employer' || ($subObj->user_type ?? '') === 'employer') {
                                        $userTypeBadge = 'Employer';
                                        $badgeClass = 'primary';
                                        $subscriberName = !empty($subObj->company_name) ? $subObj->company_name : (!empty($subObj->first_name) ? trim($subObj->first_name . ' ' . $subObj->last_name) : ($subObj->username ?? $subObj->user_email ?? 'Employer #' . $subObj->user_id));
                                        $profileUrl = !empty($subObj->employer_id) ? base_url('admin/employers/view/' . $subObj->employer_id) : '#';
                                    } else {
                                        $userTypeBadge = 'Candidate';
                                        $badgeClass = 'info';
                                        $subscriberName = !empty($subObj->candidate_name) ? $subObj->candidate_name : (!empty($subObj->first_name) ? trim($subObj->first_name . ' ' . $subObj->last_name) : ($subObj->username ?? $subObj->user_email ?? 'Candidate #' . $subObj->user_id));
                                        $profileUrl = !empty($subObj->candidate_id) ? base_url('admin/candidates/view/' . $subObj->candidate_id) : '#';
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ($profileUrl !== '#'): ?>
                                                <a href="<?= $profileUrl ?>" class="fw-semibold text-primary text-decoration-underline" title="View Subscriber Profile">
                                                    <?= esc($subscriberName) ?>
                                                </a>
                                            <?php else: ?>
                                                <strong><?= esc($subscriberName) ?></strong>
                                            <?php endif; ?>
                                            <?php if (!empty($subObj->user_email)): ?>
                                                <div class="fs-12 text-muted"><?= esc($subObj->user_email) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($subObj->plan_name ?? '—') ?></td>
                                        <td><span class="badge bg-<?= $badgeClass ?>"><?= $userTypeBadge ?></span></td>
                                        <td><?= !empty($subObj->ends_at) ? date('M d, Y', strtotime($subObj->ends_at)) : '—' ?></td>
                                        <td><span class="badge bg-<?= !empty($subObj->is_active) ? 'success' : 'secondary' ?>"><?= !empty($subObj->is_active) ? 'Active' : 'Expired' ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($subscriptions)): ?><tr><td colspan="5" class="text-center p-4 text-muted">No subscriptions assigned.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unlimited Access -->
        <div class="tab-pane fade" id="unlimitedTab">
            <div class="card">
                <div class="card-body">
                    <form id="unlimitedAccessForm" class="mb-4">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-5"><label class="form-label">Select Employer</label><select name="employer_id" class="form-select" required><option value="">Choose...</option><?php foreach ($allEmployers as $e): $eObj = (object)$e; ?><option value="<?= $eObj->id ?? $eObj->user_id ?>"><?= esc($eObj->company_name ?? 'N/A') ?></option><?php endforeach; ?></select></div>
                            <div class="col-md-4"><label class="form-label">Unlimited Until</label><input type="datetime-local" name="unlimited_until" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">&nbsp;</label><button type="submit" class="btn btn-primary d-block w-100"><i class="ti ti-infinity"></i> Grant</button></div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead><tr><th>Company</th><th>Unlimited Until</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($employersWithUnlimited as $employer): 
                                $empObj = (object)$employer;
                            ?>
                                <tr><td><?= esc($empObj->company_name ?? 'N/A') ?></td><td><?= !empty($empObj->unlimited_until) ? date('M d, Y', strtotime($empObj->unlimited_until)) : 'Forever' ?></td><td><button class="btn btn-sm btn-danger" onclick="revokeUnlimitedAccess(<?= $empObj->id ?? 0 ?>)">Revoke</button></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plan Modal -->
<div class="modal fade" id="planModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="planForm" action="<?= base_url('admin/plans') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="plan_id">
            <div class="modal-header"><h5 class="modal-title" id="modalTitle">Add Plan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6"><label class="form-label">Plan Name *</label><input type="text" name="name" id="plan_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Plan Code *</label><input type="text" name="code" id="plan_code" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Plan Type *</label><select name="plan_type" id="plan_type" class="form-select" required><option value="employer">Employer</option><option value="candidate">Candidate</option></select></div>
                <div class="col-md-6"><label class="form-label">Price (₦) *</label><input type="number" name="base_price" id="plan_base_price" class="form-control" min="0" step="100" value="0"></div>
                <div class="col-md-6"><label class="form-label">Candidate Search / Job Credits</label><input type="number" name="monthly_job_credits" id="plan_monthly_job_credits" class="form-control" min="0" value="0"></div>
                <div class="col-md-6"><label class="form-label">Duration (days)</label><input type="number" name="duration" id="plan_duration" class="form-control" min="1" value="30"></div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" id="plan_is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                <div class="col-12" id="employer_features_wrapper"><label class="form-label fw-semibold">Employer Features</label><div class="row g-2">
                    <?php $featList = ['unlimited_job_postings'=>'Unlimited Job Postings','featured'=>'Featured Jobs','network_blast'=>'Network Blast','anonymous'=>'Anonymous Posting','trust_badge'=>'Trust Badge','priority_support'=>'Priority Support','url_redirect'=>'URL Redirect','ai_resume'=>'AI Resume Builder','ai_cover_letter'=>'AI Cover Letter','ai_career_tools'=>'AI Career Tools','unlimited_applications'=>'Unlimited Applications','candidate_messaging'=>'Candidate Messaging','profile_highlight'=>'Profile Highlight']; ?>
                    <?php foreach ($featList as $key => $label): ?><div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="feat_<?= $key ?>" id="feat_<?= $key ?>" value="1"><label class="form-check-label" for="feat_<?= $key ?>"><?= $label ?></label></div></div><?php endforeach; ?>
                </div></div>
                <div class="col-12" id="candidate_features_wrapper" style="display:none;"><label class="form-label fw-semibold">Candidate Features (Comma separated)</label>
                    <textarea class="form-control" name="candidate_features" id="candidate_features" rows="3" placeholder="e.g. AI Resume Builder, Priority Support, 3 Mock Interviews"></textarea>
                    <small class="text-muted">Enter features separated by commas. These will be displayed on the candidate pricing page.</small>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Plan</button></div>
        </form>
    </div>
</div>

<!-- Growth Bundle Modal -->
<div class="modal fade" id="bundleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="bundleForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="bundle_id">

            <div class="modal-header">
                <h5 class="modal-title" id="bundleModalTitle">Create / Edit Growth Bundle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">
                <div class="col-md-8">
                    <label class="form-label">Bundle Name *</label>
                    <input type="text" name="name" id="bundle_name" class="form-control" placeholder="e.g. 5 Job Posts Pack" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bundle Code / Slug *</label>
                    <input type="text" name="slug" id="bundle_code" class="form-control" placeholder="e.g. 5-job-pack" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Number of Job Credits *</label>
                    <input type="number" name="job_credits" id="bundle_credits" class="form-control" min="1" placeholder="e.g. 5" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price (₦) *</label>
                    <input type="number" name="price" id="bundle_price" class="form-control" min="0" step="0.01" placeholder="e.g. 35000" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cost Per Job Credit (₦)</label>
                    <input type="number" name="price_per_credit" id="price_per_credit" class="form-control" min="0" step="0.01" placeholder="Auto-calculated if blank">
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_best_value" id="is_best_value">
                        <label class="form-check-label fw-semibold" for="is_best_value">
                            Mark as Best Value Package
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="alert alert-info small mb-0">
                        <strong>Growth Bundles Benefits:</strong><br>
                        Jobs posted using Growth Bundle credits automatically include Instant Approval, Featured Position, Network Blast, and Anonymous Posting options.
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="bundleSubmitBtn">Save Bundle</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    // Tab switching based on URL hash (e.g. #bundleTab)
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash) {
            const triggerEl = document.querySelector('button[data-bs-target="' + window.location.hash + '"]');
            if (triggerEl) {
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }
    });

    let bundleBsModal = null;
    function getBundleModalInstance() {
        if (!bundleBsModal) {
            bundleBsModal = new bootstrap.Modal('#bundleModal');
        }
        return bundleBsModal;
    }

    function openBundleModal(bundle = null) {
        document.getElementById('bundleForm').reset();
        document.getElementById('bundle_id').value = '';
        document.getElementById('bundleModalTitle').textContent = bundle ? 'Edit Growth Bundle: ' + (bundle.name || '') : 'Create Growth Bundle';

        if (bundle) {
            document.getElementById('bundle_id').value = bundle.id || '';
            document.getElementById('bundle_name').value = bundle.name || '';
            document.getElementById('bundle_code').value = bundle.slug || '';
            document.getElementById('bundle_credits').value = bundle.job_credits || '';
            document.getElementById('bundle_price').value = bundle.price || '';
            document.getElementById('price_per_credit').value = bundle.price_per_credit || '';
            document.getElementById('is_best_value').checked = !!bundle.is_best_value;
        }

        getBundleModalInstance().show();
    }

    // Bundle Form Submit
    document.getElementById('bundleForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('bundleSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/bundles') ?>", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new FormData(this)
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof toastr !== 'undefined') toastr.success(res.message);
                setTimeout(() => {
                    window.location.hash = '#bundleTab';
                    location.reload();
                }, 600);
            } else {
                if (typeof toastr !== 'undefined') toastr.error(res.message || 'Failed to save bundle');
                else alert(res.message || 'Failed to save bundle');
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Save Bundle';
        });
    });
</script>
<script>
function openCreateModal() {
    document.getElementById('planForm').reset();
    document.getElementById('plan_id').value = '';
    document.getElementById('modalTitle').textContent = 'Add Employer Plan';
    document.getElementById('candidate_features').value = '';
    toggleFeaturesWrapper();
}
function editPlan(plan) {
    document.getElementById('plan_id').value = plan.id || '';
    document.getElementById('plan_name').value = plan.name || '';
    document.getElementById('plan_code').value = plan.code || '';
    document.getElementById('plan_type').value = plan.plan_type || 'employer';
    document.getElementById('plan_base_price').value = plan.base_price || 0;
    document.getElementById('plan_monthly_job_credits').value = plan.monthly_job_credits || 0;
    document.getElementById('plan_duration').value = 30;
    document.getElementById('plan_is_active').value = plan.is_active ? '1' : '0';
    const typeLabel = plan.plan_type === 'candidate' ? 'Candidate' : 'Employer';
    document.getElementById('modalTitle').textContent = 'Edit ' + typeLabel + ' Plan: ' + (plan.name || '');
    const features = typeof plan.features === 'string' ? JSON.parse(plan.features) : (plan.features || {});
    
    // Reset all checkboxes
    document.querySelectorAll('#employer_features_wrapper .form-check-input').forEach(el => el.checked = false);
    
    if (plan.plan_type === 'candidate') {
        document.getElementById('candidate_features').value = Object.keys(features).join(', ');
    } else {
        Object.keys(features).forEach(k => { const el = document.getElementById('feat_' + k); if (el) el.checked = !!features[k]; });
    }
    
    toggleFeaturesWrapper();
    new bootstrap.Modal('#planModal').show();
}

function toggleFeaturesWrapper() {
    const pType = document.getElementById('plan_type').value;
    const typeLabel = pType === 'candidate' ? 'Candidate' : 'Employer';
    
    // Update modal title dynamically if adding or editing plan
    const planId = document.getElementById('plan_id').value;
    const planName = document.getElementById('plan_name').value;
    if (planId) {
        document.getElementById('modalTitle').textContent = 'Edit ' + typeLabel + ' Plan: ' + planName;
    } else {
        document.getElementById('modalTitle').textContent = 'Add ' + typeLabel + ' Plan';
    }

    if (pType === 'candidate') {
        document.getElementById('employer_features_wrapper').style.display = 'none';
        document.getElementById('candidate_features_wrapper').style.display = 'block';
    } else {
        document.getElementById('employer_features_wrapper').style.display = 'block';
        document.getElementById('candidate_features_wrapper').style.display = 'none';
    }
}

document.getElementById('plan_type').addEventListener('change', toggleFeaturesWrapper);
function deletePlan(id) {
    if (!confirm('Delete this plan? Active subscriptions will not be affected.')) return;
    fetch('<?= base_url("admin/plans/delete") ?>/' + id, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { toastr.error(d.message); } });
}
function toggleFreeMode() {
    if (!confirm('Toggle Site Free Access mode? This affects all users across the platform.')) return;
    fetch('<?= base_url("admin/plans/toggle-free-mode") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            location.reload();
        } else {
            toastr.error(d.message);
        }
    })
    .catch(err => {
        console.error(err);
        toastr.error('Error toggling Free Access mode');
    });
}
document.getElementById('unlimitedAccessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch('<?= base_url("admin/plans/grant-unlimited-access") ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { toastr.error(d.message); } });
});
function revokeUnlimitedAccess(id) {
    if (!confirm('Revoke unlimited access?')) return;
    const fd = new FormData(); fd.append('employer_id', id);
    fetch('<?= base_url("admin/plans/revoke-unlimited-access") ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { toastr.error(d.message); } });
}

function onPlanSelected() {
    const select = document.getElementById('assign_plan_id');
    const selectedOpt = select.options[select.selectedIndex];
    const type = selectedOpt ? selectedOpt.getAttribute('data-type') : '';
    
    const empGroup = document.getElementById('assign_employer_group');
    const candGroup = document.getElementById('assign_candidate_group');
    const empSelect = document.getElementById('assign_employer_id');
    const candSelect = document.getElementById('assign_candidate_id');
    
    if (type === 'employer') {
        empGroup.style.display = 'block';
        candGroup.style.display = 'none';
        empSelect.required = true;
        candSelect.required = false;
        candSelect.value = '';
    } else if (type === 'candidate') {
        empGroup.style.display = 'none';
        candGroup.style.display = 'block';
        empSelect.required = false;
        candSelect.required = true;
        empSelect.value = '';
    } else {
        empGroup.style.display = 'none';
        candGroup.style.display = 'none';
        empSelect.required = false;
        candSelect.required = false;
    }
}

document.getElementById('assignSubscriptionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    
    const select = document.getElementById('assign_plan_id');
    const selectedOpt = select.options[select.selectedIndex];
    const type = selectedOpt ? selectedOpt.getAttribute('data-type') : '';
    
    let userId = '';
    if (type === 'employer') {
        userId = document.getElementById('assign_employer_id').value;
    } else if (type === 'candidate') {
        userId = document.getElementById('assign_candidate_id').value;
    }
    
    fd.append('user_id', userId);
    
    fetch('<?= base_url("admin/plans/assign") ?>', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            location.reload();
        } else {
            toastr.error(d.message);
        }
    });
});
</script>
<?= $this->endSection() ?>
