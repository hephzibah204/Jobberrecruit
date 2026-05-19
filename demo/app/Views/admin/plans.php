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
                <a href="<?= base_url('admin/bundles') ?>" class="btn btn-outline-primary"><i class="ti ti-settings"></i> Growth Bundles</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#planModal" onclick="openCreateModal()"><i class="ti ti-plus"></i> Add Plan</button>
            </div>
        </div>
    </div>

    <?php if ($isFreeMode): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4"><i class="ti ti-alert-triangle me-2"></i><div><strong>FREE MODE ACTIVE:</strong> All features are free for all users. No payments will be collected.</div></div>
    <?php endif; ?>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="planTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#employerTab">Employer Plans (<?= count($employerPlans) ?>)</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#candidateTab">Candidate Plans (<?= count($candidatePlans) ?>)</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#subsTab">Active Subscriptions</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#unlimitedTab">Unlimited Access</button></li>
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
                                <?php foreach ($employerPlans as $plan): ?>
                                    <?php $features = json_decode($plan->features ?? '[]', true) ?? []; ?>
                                    <tr>
                                        <td><strong><?= esc($plan->name) ?></strong></td>
                                        <td><code><?= esc($plan->code) ?></code></td>
                                        <td>₦<?= number_format($plan->base_price) ?></td>
                                        <td><?= $plan->monthly_job_credits ?? '—' ?></td>
                                        <td><?= count(array_filter($features)) ?> enabled</td>
                                        <td><span class="badge bg-<?= $plan->is_active ? 'success' : 'secondary' ?>"><?= $plan->is_active ? 'Active' : 'Inactive' ?></span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary-light" onclick='editPlan(<?= json_encode($plan) ?>)'><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deletePlan(<?= $plan->id ?>)"><i class="ti ti-trash"></i></button>
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
                                <?php foreach ($candidatePlans as $plan): ?>
                                    <?php $features = json_decode($plan->features ?? '[]', true) ?? []; ?>
                                    <tr>
                                        <td><strong><?= esc($plan->name) ?></strong></td>
                                        <td><code><?= esc($plan->code) ?></code></td>
                                        <td>₦<?= number_format($plan->base_price) ?></td>
                                        <td><?= count(array_filter($features)) ?> enabled</td>
                                        <td><span class="badge bg-<?= $plan->is_active ? 'success' : 'secondary' ?>"><?= $plan->is_active ? 'Active' : 'Inactive' ?></span></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary-light" onclick='editPlan(<?= json_encode($plan) ?>)'><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deletePlan(<?= $plan->id ?>)"><i class="ti ti-trash"></i></button>
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

        <!-- Active Subscriptions -->
        <div class="tab-pane fade" id="subsTab">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0">
                            <thead><tr><th>Company</th><th>Plan</th><th>Type</th><th>End Date</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($subscriptions as $sub): ?>
                                    <tr>
                                        <td><?= esc($sub->company_name ?? 'N/A') ?></td>
                                        <td><?= esc($sub->plan_name ?? '—') ?></td>
                                        <td><span class="badge bg-<?= $sub->plan_type === 'employer' ? 'primary' : 'info' ?>"><?= esc($sub->plan_type) ?></span></td>
                                        <td><?= $sub->end_date ? date('M d, Y', strtotime($sub->end_date)) : '—' ?></td>
                                        <td><span class="badge bg-<?= $sub->is_active ? 'success' : 'secondary' ?>"><?= $sub->is_active ? 'Active' : 'Expired' ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
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
                            <div class="col-md-5"><label class="form-label">Select Employer</label><select name="employer_id" class="form-select" required><option value="">Choose...</option><?php foreach ($allEmployers as $e): ?><option value="<?= $e->id ?>"><?= esc($e->company_name) ?></option><?php endforeach; ?></select></div>
                            <div class="col-md-4"><label class="form-label">Unlimited Until</label><input type="datetime-local" name="unlimited_until" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">&nbsp;</label><button type="submit" class="btn btn-primary d-block w-100"><i class="ti ti-infinity"></i> Grant</button></div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead><tr><th>Company</th><th>Unlimited Until</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($employersWithUnlimited as $employer): ?>
                                <tr><td><?= esc($employer->company_name) ?></td><td><?= $employer->unlimited_until ? date('M d, Y', strtotime($employer->unlimited_until)) : 'Forever' ?></td><td><button class="btn btn-sm btn-danger" onclick="revokeUnlimitedAccess(<?= $employer->id ?>)">Revoke</button></td></tr>
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
                <div class="col-md-6"><label class="form-label">Monthly Price (₦)</label><input type="number" name="base_price" id="plan_base_price" class="form-control" min="0" step="100" value="0"></div>
                <div class="col-md-6"><label class="form-label">Monthly Job Credits</label><input type="number" name="monthly_job_credits" id="plan_monthly_job_credits" class="form-control" min="0" value="0"></div>
                <div class="col-md-6"><label class="form-label">Duration (days)</label><input type="number" name="duration" id="plan_duration" class="form-control" min="1" value="30"></div>
                <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" id="plan_is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                <div class="col-12"><label class="form-label fw-semibold">Features</label><div class="row g-2">
                    <?php $featList = ['featured'=>'Featured Jobs','network_blast'=>'Network Blast','anonymous'=>'Anonymous Posting','trust_badge'=>'Trust Badge','priority_support'=>'Priority Support','url_redirect'=>'URL Redirect','ai_resume'=>'AI Resume Builder','ai_cover_letter'=>'AI Cover Letter','ai_career_tools'=>'AI Career Tools','unlimited_applications'=>'Unlimited Applications','candidate_messaging'=>'Candidate Messaging','profile_highlight'=>'Profile Highlight']; ?>
                    <?php foreach ($featList as $key => $label): ?><div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="feat_<?= $key ?>" id="feat_<?= $key ?>" value="1"><label class="form-check-label" for="feat_<?= $key ?>"><?= $label ?></label></div></div><?php endforeach; ?>
                </div></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Plan</button></div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function openCreateModal() {
    document.getElementById('planForm').reset();
    document.getElementById('plan_id').value = '';
    document.getElementById('modalTitle').textContent = 'Add Plan';
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
    document.getElementById('modalTitle').textContent = 'Edit: ' + (plan.name || '');
    const features = typeof plan.features === 'string' ? JSON.parse(plan.features) : (plan.features || {});
    Object.keys(features).forEach(k => { const el = document.getElementById('feat_' + k); if (el) el.checked = !!features[k]; });
    new bootstrap.Modal('#planModal').show();
}
function deletePlan(id) {
    if (!confirm('Delete this plan? Active subscriptions will not be affected.')) return;
    fetch('<?= base_url("admin/plans/delete") ?>/' + id, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { alert(d.message); } });
}
function toggleFreeMode() {
    if (!confirm('Toggle free mode? This affects all users.')) return;
    fetch('<?= base_url("admin/plans/toggle-free-mode") ?>', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { alert(d.message); } });
}
document.getElementById('unlimitedAccessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch('<?= base_url("admin/plans/grant-unlimited-access") ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { alert(d.message); } });
});
function revokeUnlimitedAccess(id) {
    if (!confirm('Revoke unlimited access?')) return;
    const fd = new FormData(); fd.append('employer_id', id);
    fetch('<?= base_url("admin/plans/revoke-unlimited-access") ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) { location.reload(); } else { alert(d.message); } });
}
</script>
<?= $this->endSection() ?>
