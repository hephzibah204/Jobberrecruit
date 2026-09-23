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
                    <?php if (empty($employers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="ti ti-building-off fs-24 d-block mb-1"></i>
                                No employers found for this filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employers as $employer): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php
                                            $coName = $employer->company_name ?? 'Company';
                                            $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode(trim($coName)) . "&background=0A2F57&color=fff&size=128&bold=true";
                                            if (function_exists('resolve_image_url')) {
                                                $logoUrl = resolve_image_url($employer->logo ?? '', 'company', $coName);
                                            } else {
                                                $rawLogo = $employer->logo ?? '';
                                                if (empty($rawLogo)) {
                                                    $logoUrl = $fallbackAvatar;
                                                } elseif (str_starts_with($rawLogo, 'http://') || str_starts_with($rawLogo, 'https://')) {
                                                    $logoUrl = $rawLogo;
                                                } else {
                                                    $logoUrl = base_url(ltrim($rawLogo, '/'));
                                                }
                                            }
                                        ?>
                                        <span class="avatar avatar-md rounded me-2 flex-shrink-0" style="background:#f1f5f9; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; overflow:hidden; width:38px; height:38px;">
                                            <img src="<?= esc($logoUrl) ?>" alt="<?= esc($coName) ?>" style="width:100%; height:100%; object-fit:contain;" onerror="this.onerror=null; this.src='<?= esc($fallbackAvatar) ?>';">
                                        </span>
                                        <div>
                                            <div class="fw-semibold"><?= esc($employer->company_name) ?></div>
                                            <div class="fs-12 text-muted">ID: #<?= $employer->id ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><?= esc(!empty($employer->contact_email) ? $employer->contact_email : (!empty($employer->email) ? $employer->email : 'N/A')) ?></div>
                                    <div class="fs-12 text-muted"><?= esc(!empty($employer->contact_phone) ? $employer->contact_phone : (!empty($employer->phone) ? $employer->phone : '—')) ?></div>
                                </td>
                                <td><?= esc($employer->state_name ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-primary-transparent">
                                        <?= $employer->total_jobs ?? 0 ?> Jobs
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $hasDocs = !empty($employer->submitted_docs_count) && (int)$employer->submitted_docs_count > 0;
                                    if ($employer->verification_status === 'verified') {
                                        $displayStatus = 'Verified';
                                        $color = 'success';
                                    } elseif ($employer->verification_status === 'rejected') {
                                        $displayStatus = 'Rejected';
                                        $color = 'danger';
                                    } elseif ($employer->verification_status === 'document_required') {
                                        $displayStatus = 'Docs Required';
                                        $color = 'info';
                                    } elseif ($hasDocs) {
                                        $displayStatus = 'Pending Verification';
                                        $color = 'warning';
                                    } else {
                                        $displayStatus = 'Unverified (No Docs)';
                                        $color = 'secondary';
                                    }
                                    ?>
                                    <span class="badge bg-<?= $color ?>-transparent">
                                        <?= $displayStatus ?>
                                    </span>
                                    <?php if ($hasDocs && $employer->verification_status === 'pending'): ?>
                                        <div class="fs-11 text-success mt-1">
                                            <i class="ti ti-file-check me-1"></i><?= (int)$employer->submitted_docs_count ?> Doc(s) Submitted
                                        </div>
                                    <?php elseif ($employer->verification_status === 'pending'): ?>
                                        <div class="fs-11 text-muted mt-1">
                                            No Docs Uploaded
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

                                        <?php if ($employer->verification_status === 'pending' || $hasDocs): ?>
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
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pager)): ?>
            <div class="card-footer">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>