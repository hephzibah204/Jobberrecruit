<?php /** admin/partials/candidates_table.php — AJAX partial for filtered candidate list */ ?>
<div class="table-responsive">
    <table class="table table-hover text-nowrap align-middle">
        <thead class="table-light">
            <tr>
                <th>Candidate</th>
                <th>Education</th>
                <th>Skills</th>
                <th>Job Type</th>
                <th>Salary Expectation</th>
                <th>Availability</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($candidates)): foreach ($candidates as $c): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <?php if (!empty($c->profile_photo)): ?>
                                <img src="<?= base_url('uploads/' . $c->profile_photo) ?>" class="rounded-circle" width="36" height="36" style="object-fit:cover" alt="">
                            <?php else: ?>
                                <div class="avatar avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                    <span class="text-primary fw-semibold fs-12"><?= strtoupper(substr($c->full_name ?? 'U', 0, 1)) ?></span>
                                </div>
                            <?php endif; ?>
                            <div>
                                <strong><?= esc($c->full_name) ?></strong><br>
                                <small class="text-muted"><?= esc($c->job_title ?? 'Not Set') ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?= esc($c->education_level ?? '—') ?></td>
                    <td>
                        <?php
                        $skills = array_slice(array_filter(explode(',', $c->skills ?? '')), 0, 3);
                        foreach ($skills as $s): ?>
                            <span class="badge bg-light text-dark border"><?= esc(trim($s)) ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td><?= esc($c->employment_type ?? '—') ?></td>
                    <td><?= !empty($c->desired_salary) ? '₦' . number_format($c->desired_salary) : '—' ?></td>
                    <td><?= esc($c->availability ?? '—') ?></td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="<?= base_url('admin/candidates/view/' . $c->id) ?>"
                               class="btn btn-sm btn-outline-primary" title="View Profile">
                                <i class="ti ti-eye"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="deleteCandidate(<?= $c->id ?>)"
                                    title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="ti ti-users-off fs-24 d-block mb-2 opacity-50"></i>
                        No candidates match your filters.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($pager)): ?>
    <div class="d-flex justify-content-end mt-3">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
<?php endif; ?>
