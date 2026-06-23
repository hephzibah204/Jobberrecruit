<div class="card custom-card overflow-hidden border-bottom-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table text-nowrap table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Candidate Details</th>
                        <th>Education & Experience</th>
                        <th>Core Skills</th>
                        <th>Expectations</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($candidates): foreach ($candidates as $c): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-2 online">
                                            <?php if (!empty($c->profile_picture)): ?>
                                                <img src="<?= base_url($c->profile_picture) ?>" alt="img" class="rounded-circle">
                                            <?php else: ?>
                                                <div class="avatar avatar-md bg-primary-transparent rounded-circle">
                                                    <?= substr($c->full_name, 0, 1) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-14">
                                                <?= esc($c->full_name) ?>
                                                <?php if ($c->is_verified): ?>
                                                    <span class="ms-1 text-primary" title="Verified Candidate">
                                                        <i class="ri-checkbox-circle-fill"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted"><?= esc($c->job_title ?? 'Professional') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fs-12 fw-medium"><?= esc($c->education_level ?? 'Graduate') ?></div>
                                    <small class="text-muted"><?= esc($c->experience_years ?? 0) ?> Years Experience</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php 
                                        $skills = array_slice(explode(',', $c->skills ?? ''), 0, 3);
                                        foreach ($skills as $s): if(!empty(trim($s))): ?>
                                            <span class="badge bg-primary-transparent text-primary fs-10"><?= esc(trim($s)) ?></span>
                                        <?php endif; endforeach; ?>
                                        <?php if (count(explode(',', $c->skills ?? '')) > 3): ?>
                                            <span class="badge bg-light text-muted fs-10">+<?= count(explode(',', $c->skills ?? '')) - 3 ?> more</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fs-12 fw-medium text-capitalize"><?= esc(str_replace('-', ' ', $c->employment_type ?? 'Full-time')) ?></div>
                                    <small class="text-success fw-bold">₦<?= number_format((float)($c->desired_salary ?? 0)) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info-transparent"><?= esc($c->availability ?? 'Immediate') ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>" 
                                           class="btn btn-sm btn-primary-light">
                                            View Profile
                                        </a>
                                        <button class="btn btn-sm btn-outline-primary" onclick="startMessage(<?= $c->id ?>)" title="Send Message">
                                            <i class="ri-message-2-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="6" class="text-center p-5">
                                <div class="mb-2">
                                    <i class="ri-user-search-line fs-40 text-muted"></i>
                                </div>
                                <h6 class="text-muted">No candidates found matching your criteria</h6>
                                <p class="text-muted small">Try adjusting your filters or search keywords</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <?= $pager->links('default', 'admin_pagination') ?>
</div>
