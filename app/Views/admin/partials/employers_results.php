<div class="card custom-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Jobs</th>
                        <th>Verification Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employers as $employer): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($employer->logo): ?>
                                        <img src="<?= base_url($employer->logo) ?>" class="avatar avatar-sm rounded me-2">
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?= esc($employer->company_name) ?></div>
                                        <div class="fs-12 text-muted">ID: #<?= $employer->id ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><?= esc($employer->company_email) ?></div>
                                <div class="fs-12 text-muted"><?= esc($employer->phone) ?></div>
                            </td>
                            <td><?= esc($employer->state_name ?? 'N/A') ?></td>
                            <td>
                                <span class="badge bg-primary-transparent">
                                    <?= $employer->total_jobs ?? 0 ?> Jobs
                                </span>
                            </td>
                            <td>
                                <?php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'verified' => 'success',
                                    'rejected' => 'danger',
                                    'document_required' => 'info'
                                ];
                                $color = $statusColors[$employer->verification_status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $color ?>-transparent">
                                    <?= ucfirst(str_replace('_', ' ', $employer->verification_status)) ?>
                                </span>
                                <?php if ($employer->verification_status === 'pending'): ?>
                                    <div class="fs-11 text-muted mt-1">
                                        Since: <?= date('M d, Y', strtotime($employer->created_at)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info"
                                        onclick="viewDocumentss(<?= $employer->id ?>)"
                                        title="View Documents">
                                        <i class="ti ti-file-text"></i>
                                    </button>

                                    <?php if ($employer->verification_status === 'pending'): ?>
                                        <button class="btn btn-sm btn-success"
                                            onclick="openVerifyModal(<?= $employer->id ?>)"
                                            title="Verify">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="openRejectModal(<?= $employer->id ?>)"
                                            title="Reject">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    <?php endif; ?>

                                    <a href="<?= base_url('admin/employers/view/' . $employer->id) ?>"
                                        class="btn btn-sm btn-light"
                                        title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    <button class="btn btn-sm btn-light"
                                        onclick="deleteEmployer(<?= $employer->id ?>)"
                                        title="Delete">
                                        <i class="ti ti-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager): ?>
            <div class="card-footer">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>