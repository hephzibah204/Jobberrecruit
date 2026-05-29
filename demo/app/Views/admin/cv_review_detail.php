<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="content">
    <div class="page-header mb-4 d-flex flex-wrap justify-content-between align-items-center">
        <div class="page-title">
            <h4 class="fw-bold">CV Review #<?= $review->id ?></h4>
            <p class="text-muted">Review details, AI analysis, and feedback delivery.</p>
        </div>
        <a href="<?= base_url('admin/cv-reviews') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>Back to List
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="ti ti-user text-primary me-2"></i>Candidate Details</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-sm table-borderless">
                        <tr><td class="text-muted ps-0" style="width:40%;">Full Name</td><td class="fw-semibold"><?= esc($review->full_name ?: 'N/A') ?></td></tr>
                        <tr><td class="text-muted ps-0">Email</td><td><?= esc($review->email) ?></td></tr>
                        <tr><td class="text-muted ps-0">Phone</td><td><?= esc($review->phone ?: 'N/A') ?></td></tr>
                        <tr><td class="text-muted ps-0">Target Role</td><td class="fw-semibold"><?= esc($review->target_role ?: 'N/A') ?></td></tr>
                        <tr><td class="text-muted ps-0">Industry</td><td><?= esc(ucfirst($review->industry ?: 'N/A')) ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="ti ti-receipt text-primary me-2"></i>Order Details</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-sm table-borderless">
                        <tr><td class="text-muted ps-0" style="width:40%;">Plan</td><td><span class="badge bg-<?= $review->plan === 'premium' ? 'warning' : ($review->plan === 'professional' ? 'primary' : 'secondary') ?>-transparent text-<?= $review->plan === 'premium' ? 'warning' : ($review->plan === 'professional' ? 'primary' : 'secondary') ?>"><?= ucfirst($review->plan) ?></span></td></tr>
                        <tr><td class="text-muted ps-0">Amount</td><td class="fw-semibold"><?= $review->amount > 0 ? '&#8358;' . number_format($review->amount, 0) : 'Free' ?></td></tr>
                        <tr><td class="text-muted ps-0">Payment</td><td><?= $review->payment_status === 'paid' ? '<span class="badge bg-success-transparent text-success">Paid</span>' : ($review->payment_status === 'free' ? '<span class="badge bg-secondary-transparent text-secondary">Free</span>' : '<span class="badge bg-danger-transparent text-danger">' . ucfirst($review->payment_status) . '</span>') ?></td></tr>
                        <tr><td class="text-muted ps-0">Reference</td><td><small class="text-muted"><?= esc($review->payment_reference ?: 'N/A') ?></small></td></tr>
                        <tr><td class="text-muted ps-0">Status</td><td>
                            <?php
                            $badges = ['pending'=>'warning','in_review'=>'info','completed'=>'success','rejected'=>'danger'];
                            $s = $badges[$review->status] ?? 'secondary'; ?>
                            <span class="badge bg-<?= $s ?>-transparent text-<?= $s ?> px-3 py-2"><?= ucfirst(str_replace('_',' ',$review->status)) ?></span>
                        </td></tr>
                        <tr><td class="text-muted ps-0">Mode</td><td><?= $review->review_mode === 'auto' ? '<span class="badge bg-info-transparent text-info">Auto</span>' : '<span class="badge bg-secondary-transparent text-secondary">Semi</span>' ?></td></tr>
                        <tr><td class="text-muted ps-0">Submitted</td><td><small class="text-muted"><?= date('d M Y, h:i A', strtotime($review->created_at)) ?></small></td></tr>
                        <?php if ($review->reviewed_at): ?>
                        <tr><td class="text-muted ps-0">Reviewed</td><td><small class="text-muted"><?= date('d M Y, h:i A', strtotime($review->reviewed_at)) ?></small></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php if ($review->file_path): ?>
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="ti ti-file text-primary me-2"></i>Uploaded CV</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <i class="ti ti-file-text" style="font-size:3rem;color:#3b82f6;display:block;margin-bottom:12px;"></i>
                    <p class="mb-2"><?= basename($review->file_path) ?></p>
                    <a href="<?= base_url('admin/cv-reviews/download/' . $review->id) ?>" class="btn btn-primary" target="_blank">
                        <i class="ti ti-download me-1"></i>Download CV
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($review->feedback_request): ?>
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="ti ti-message text-primary me-2"></i>Feedback Request</h5>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0 text-muted"><?= esc($review->feedback_request) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-7">
            <!-- AI Review Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="ti ti-robot text-primary me-2"></i>AI Review</h5>
                    <div class="d-flex gap-2">
                        <?php if (!$review->ai_review): ?>
                        <form method="POST" action="<?= base_url('admin/cv-reviews/ai-review/' . $review->id) ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-primary btn-sm" onclick="return confirm('Generate AI review for this CV?')">
                                <i class="ti ti-sparkles me-1"></i>Generate AI Review
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php if ($review->status === 'pending' || $review->status === 'in_review'): ?>
                        <form method="POST" action="<?= base_url('admin/cv-reviews/mark-in-review/' . $review->id) ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-info btn-sm">
                                <i class="ti ti-eye me-1"></i>Mark In Review
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if ($review->ai_review): ?>
                        <div class="bg-light p-4 rounded-3" style="max-height:500px;overflow-y:auto;">
                            <?= $review->ai_review ?>
                        </div>
                        <div class="mt-3 text-end">
                            <small class="text-muted">Generated on <?= date('d M Y, h:i A', strtotime($review->updated_at)) ?></small>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="ti ti-robot-off" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                            <p>No AI review has been generated yet. Click <strong>"Generate AI Review"</strong> to create one.</p>
                            <?php if (env('cv_review_mode', 'semi') === 'auto'): ?>
                            <span class="badge bg-info-transparent text-info">Auto mode is ON — reviews are generated automatically on submission.</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="ti ti-edit text-primary me-2"></i>Admin Notes</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= base_url('admin/cv-reviews/save-notes/' . $review->id) ?>">
                        <?= csrf_field() ?>
                        <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add internal notes about this review..."><?= esc($review->admin_notes ?? '') ?></textarea>
                        <button type="submit" class="btn btn-primary mt-3 btn-sm">
                            <i class="ti ti-device-floppy me-1"></i>Save Notes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Feedback Delivery -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="ti ti-send text-primary me-2"></i>Feedback Delivery</h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($review->feedback_delivered): ?>
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="ti ti-check-circle fs-4 me-2"></i>
                            <div>
                                <strong>Feedback delivered</strong> on <?= $review->delivered_at ? date('d M Y, h:i A', strtotime($review->delivered_at)) : 'N/A' ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 flex-wrap">
                        <?php if ($review->ai_review && !$review->feedback_delivered): ?>
                        <form method="POST" action="<?= base_url('admin/cv-reviews/deliver/' . $review->id) ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark feedback as delivered and notify the candidate?')">
                                <i class="ti ti-send me-1"></i>Mark as Delivered
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if ($review->status !== 'completed'): ?>
                        <form method="POST" action="<?= base_url('admin/cv-reviews/complete/' . $review->id) ?>" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Mark this review as completed?')">
                                <i class="ti ti-check me-1"></i>Mark Completed
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>

                    <?php if (!$review->ai_review): ?>
                    <div class="alert alert-warning mt-3 mb-0 py-2 small">
                        <i class="ti ti-alert-triangle me-1"></i>Generate an AI review before delivering feedback.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
