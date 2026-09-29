<div class="card custom-card overflow-hidden border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;" class="text-center">
                            <input class="form-check-input" type="checkbox" id="selectAllCandidates" onchange="toggleSelectAllCandidates(this)">
                        </th>
                        <th>Candidate Profile</th>
                        <th>Role & Location</th>
                        <th>Education & Salary</th>
                        <th>Skills</th>
                        <th>Resume / CV</th>
                        <th class="text-center">Activity</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($candidates) && count($candidates) > 0): ?>
                        <?php foreach ($candidates as $c): ?>
                            <?php
                                $candName = !empty($c->full_name) ? $c->full_name : (!empty($c->username) ? $c->username : 'Candidate #' . $c->id);
                                $rawAvatar = $c->profile_picture ?? '';
                                $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode(trim($candName)) . "&background=0A2F57&color=fff&size=128&bold=true";
                                $avatarUrl = resolve_image_url($rawAvatar, 'candidate', $candName);

                                $skillsArr = !empty($c->skills) ? array_filter(array_map('trim', explode(',', $c->skills))) : [];
                                $hasResume = !empty($c->resume);
                                $isVerified = !empty($c->is_verified);
                                $isVisible = !empty($c->is_visible);
                            ?>
                            <tr id="candidate-row-<?= $c->id ?>">
                                <td class="text-center">
                                    <input class="form-check-input candidate-checkbox" type="checkbox" value="<?= $c->id ?>" onchange="updateBulkDeleteState()">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-md rounded-circle me-2 flex-shrink-0" style="background:#f1f5f9; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; overflow:hidden; width:40px; height:40px;">
                                            <img src="<?= esc($avatarUrl) ?>" alt="<?= esc($candName) ?>" style="width:100%; height:100%; object-fit:cover;" onerror="this.onerror=null; this.src='<?= esc($fallbackAvatar) ?>';">
                                        </span>
                                        <div>
                                            <a href="<?= base_url('admin/candidates/view/' . $c->id) ?>" class="fw-semibold text-primary text-decoration-none hover-underline">
                                                <?= esc($candName) ?>
                                            </a>
                                            <div class="fs-12 text-muted d-flex align-items-center gap-1">
                                                <i class="ti ti-mail fs-11"></i> <?= esc($c->email ?? 'No email') ?>
                                            </div>
                                            <?php if (!empty($c->phone)): ?>
                                                <div class="fs-12 text-muted d-flex align-items-center gap-1">
                                                    <i class="ti ti-phone fs-11"></i> <?= esc($c->phone) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark"><?= esc($c->job_title ?? 'Not specified') ?></div>
                                    <div class="fs-12 text-muted d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-light text-dark border">
                                            <i class="ti ti-briefcase me-1"></i><?= !empty($c->experience_years) ? (int)$c->experience_years . ' yrs exp' : 'Entry level' ?>
                                        </span>
                                        <?php if (!empty($c->state_name) || !empty($c->location)): ?>
                                            <span class="text-secondary">
                                                <i class="ti ti-map-pin fs-12"></i> <?= esc($c->state_name ?? $c->location) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($c->employment_type)): ?>
                                        <span class="badge bg-primary-transparent mt-1"><?= esc($c->employment_type) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fs-13 fw-medium text-dark"><?= esc($c->education_level ?? 'Not specified') ?></div>
                                    <?php if (!empty($c->desired_salary)): ?>
                                        <div class="fs-12 text-success fw-semibold mt-1">
                                            ₦<?= is_numeric($c->desired_salary) ? number_format((float)$c->desired_salary) : esc($c->desired_salary) ?>
                                            <small class="text-muted fw-normal">/ <?= esc($c->salary_type ?? 'mo') ?></small>
                                        </div>
                                    <?php else: ?>
                                        <div class="fs-12 text-muted mt-1">Salary: Negotiable</div>
                                    <?php endif; ?>
                                    <?php if (!empty($c->availability)): ?>
                                        <div class="fs-11 text-muted mt-1">
                                            <i class="ti ti-clock fs-11"></i> Notice: <?= esc($c->availability) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($skillsArr)): ?>
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 220px;">
                                            <?php foreach (array_slice($skillsArr, 0, 3) as $sk): ?>
                                                <span class="badge bg-light text-dark border fs-11"><?= esc($sk) ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($skillsArr) > 3): ?>
                                                <span class="badge bg-secondary-transparent fs-11" title="<?= esc(implode(', ', array_slice($skillsArr, 3))) ?>">
                                                    +<?= count($skillsArr) - 3 ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fs-12">No skills listed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($hasResume): ?>
                                        <a href="<?= base_url('admin/candidates/download-cv/' . $c->id) ?>" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 shadow-none" target="_blank" title="Download Candidate Resume">
                                            <i class="ti ti-file-download fs-14"></i> Download CV
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-warning-transparent d-inline-flex align-items-center gap-1">
                                            <i class="ti ti-file-off fs-12"></i> No CV
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="badge bg-info-transparent" title="Total Job Applications">
                                            <i class="ti ti-send me-1"></i><?= (int)($c->total_applications ?? 0) ?> Apps
                                        </span>
                                        <?php if (!empty($c->total_courses) && (int)$c->total_courses > 0): ?>
                                            <span class="badge bg-purple-transparent" title="Enrolled Courses">
                                                <i class="ti ti-school me-1"></i><?= (int)$c->total_courses ?> Courses
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <button type="button" 
                                                class="badge <?= $isVerified ? 'bg-success' : 'bg-secondary' ?> border-0 cursor-pointer"
                                                onclick="toggleCandidateVerification(<?= $c->id ?>, this)"
                                                title="Click to toggle verification status">
                                            <i class="ti <?= $isVerified ? 'ti-check' : 'ti-shield-off' ?> me-1"></i>
                                            <?= $isVerified ? 'Verified' : 'Unverified' ?>
                                        </button>
                                        <span class="badge <?= $isVisible ? 'bg-primary-transparent' : 'bg-danger-transparent' ?>" title="Search visibility status">
                                            <?= $isVisible ? 'Visible' : 'Hidden' ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('admin/candidates/view/' . $c->id) ?>" class="btn btn-sm btn-light border" title="View Full Profile">
                                            <i class="ti ti-eye text-primary"></i>
                                        </a>
                                        <?php if ($hasResume): ?>
                                            <a href="<?= base_url('admin/candidates/download-cv/' . $c->id) ?>" class="btn btn-sm btn-light border" title="Download CV" target="_blank">
                                                <i class="ti ti-download text-info"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-light border" onclick="deleteCandidate(<?= $c->id ?>)" title="Delete Candidate">
                                            <i class="ti ti-trash text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ti ti-user-x fs-40 text-secondary mb-2 d-block"></i>
                                    <h6 class="fw-semibold">No candidates found</h6>
                                    <p class="fs-13 mb-0">Try clearing or adjusting your search filters to find candidate records.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <div class="text-muted fs-13">
        Showing <?= count($candidates ?? []) ?> candidates
    </div>
    <div>
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
</div>