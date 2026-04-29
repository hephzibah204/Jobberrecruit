<div class="card custom-card overflow-hidden border-bottom-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th>Details</th>
                        <th>Education</th>
                        <th>Skills</th>
                        <th>Job Type</th>
                        <th>Salary</th>
                        <th>Notice Period</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($candidates): foreach ($candidates as $c): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($c->full_name) ?></strong><br>
                                    <small class="text-muted"><?= esc($c->job_title ?? 'Not Set') ?></small>
                                </td>
                                <td><?= esc($c->education_level ?? 'Not Set') ?></td>
                                <td>
                                    <?php foreach (array_slice(explode(',', $c->skills ?? ''), 0, 3) as $s): ?>
                                        <span class="badge bg-light text-dark"><?= esc(trim($s)) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td><?= esc($c->employment_type ?? 'Not Set') ?></td>
                                <td><?= esc($c->desired_salary ?? 'Not Set') ?></td>
                                <td><?= esc($c->availability ?? 'Not Set') ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('admin/candidates/view/' . $c->id) ?>"
                                            class="btn btn-sm btn-light border"
                                            title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-light border"
                                            onclick="deleteCandidate(<?= $c->id ?>)"
                                            title="Delete">
                                            <i class="ti ti-trash text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No candidates found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $pager->links('default', 'admin_pagination') ?>