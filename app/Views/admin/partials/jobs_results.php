<div class="table-responsive">
    <table class="table text-nowrap mb-0">
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="select-all-jobs" style="width:16px;height:16px;cursor:pointer;"></th>
                <th>ID</th>
                <th>Title / Company</th>
                <th>Location</th>
                <th>Applications</th>
                <th>Posted</th>
                <th>Admin Status</th>
                <th>Job Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($jobs)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        No jobs found
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><input type="checkbox" class="job-checkbox" value="<?= $job->id ?>" data-status="<?= $job->admin_status ?>" style="width:16px;height:16px;cursor:pointer;"></td>
                        <td>#<?= $job->id ?></td>
                        <td>
                            <div class="fw-semibold">
                                <a href="<?= base_url('jobs/' . (!empty($job->slug) ? $job->slug : $job->id)) ?>" target="_blank" class="text-dark text-decoration-none hover-primary" title="View Job Post (Candidate View)">
                                    <?= esc($job->title) ?>
                                    <i class="ti ti-external-link text-primary fs-12 ms-1"></i>
                                </a>
                                <?php if (isset($job->is_verified) && $job->is_verified): ?>
                                    <span class="text-primary ms-1" title="Verified Job"><i class="ti ti-discount-check-filled"></i></span>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted"><?= esc($job->company_name) ?></div>
                        </td>
                        <td><?= esc($job->state_name ?? '—') ?></td>
                        <td>
                            <span class="badge bg-primary-transparent">
                                <?= number_format($job->applications_count ?? 0) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($job->created_at)) ?></td>
                        <td>
                            <?php
                            $adminStatusColors = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            $adminColor = $adminStatusColors[$job->admin_status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $adminColor ?>-transparent cursor-pointer"
                                onclick="toggleStatus(<?= $job->id ?>, '<?= $job->status ?>', '<?= $job->admin_status ?>')">
                                <?php if ($job->admin_status === 'pending'): ?>
                                    <i class="ti ti-hourglass me-1"></i>
                                <?php elseif ($job->admin_status === 'approved'): ?>
                                    <i class="ti ti-check me-1"></i>
                                <?php elseif ($job->admin_status === 'rejected'): ?>
                                    <i class="ti ti-x me-1"></i>
                                <?php endif; ?>
                                <?= ucfirst(str_replace('_', ' ', $job->admin_status)) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $job->status === 'open' ? 'success' : 'secondary' ?>-transparent">
                                <i class="ti ti-<?= $job->status === 'open' ? 'circle-check' : 'circle-x' ?> me-1"></i>
                                <?= ucfirst($job->status) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="<?= base_url('jobs/' . (!empty($job->slug) ? $job->slug : $job->id)) ?>" target="_blank"
                                    class="btn btn-sm btn-primary" title="View Job Post (Candidate Page)">
                                    <i class="ti ti-external-link me-1"></i>View Job
                                </a>

                                <?php if ($job->admin_status === 'pending'): ?>
                                    <button type="button" class="btn btn-sm btn-success quick-approve-job" data-id="<?= $job->id ?>" data-title="<?= esc($job->title) ?>" title="1-Click Approve Job">
                                        <i class="ti ti-check me-1"></i>Approve
                                    </button>
                                <?php elseif ($job->admin_status === 'approved'): ?>
                                    <button type="button" class="btn btn-sm btn-warning quick-unapprove-job" data-id="<?= $job->id ?>" data-title="<?= esc($job->title) ?>" title="Unapprove Job (Move back to Pending)">
                                        <i class="ti ti-rotate-clockwise me-1"></i>Unapprove
                                    </button>
                                    <?php if ($job->status === 'open'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary close-job-btn" data-id="<?= $job->id ?>" data-title="<?= esc($job->title) ?>" title="Close Job (Stop applications and remove from active)">
                                            <i class="ti ti-circle-x me-1"></i>Close
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/jobs/view/' . $job->id) ?>"
                                    class="btn btn-sm btn-light" title="Admin Analytics & Applications">
                                    <i class="ti ti-chart-bar"></i>
                                </a>
                                <a href="<?= base_url('admin/jobs/edit/' . $job->id) ?>"
                                    class="btn btn-sm btn-light" title="Edit Job">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <button type="button"
                                    class="btn btn-sm btn-light <?= (isset($job->is_verified) && $job->is_verified) ? 'text-primary' : 'text-muted' ?>"
                                    onclick="toggleVerification(<?= $job->id ?>, <?= (isset($job->is_verified) && $job->is_verified) ? 1 : 0 ?>)"
                                    title="<?= (isset($job->is_verified) && $job->is_verified) ? 'Unverify Job' : 'Verify Job' ?>">
                                    <i class="ti ti-discount-check<?= (isset($job->is_verified) && $job->is_verified) ? '-filled' : '' ?>"></i>
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-light text-danger delete-job"
                                    data-id="<?= $job->id ?>"
                                    data-title="<?= esc($job->title) ?>"
                                    title="Delete Job">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($pager): ?>
    <div class="card-footer">
        <?= $pager->links() ?>
    </div>
<?php endif; ?>