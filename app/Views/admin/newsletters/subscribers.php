<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h4 class="mb-1"><i class="ti ti-users-group me-2 text-primary"></i>Newsletter Audience &amp; Subscribers</h4>
            <p class="text-muted mb-0">Manage registered audiences, guest subscribers, recent signups, and event registrants.</p>
        </div>
        <div class="page-btn d-flex gap-2">
            <a href="<?= base_url('admin/newsletters/create') ?>" class="btn btn-primary">
                <i class="ti ti-mail-fast me-1"></i>Create Campaign
            </a>
            <a href="<?= base_url('admin/newsletters/subscribers/export') ?>" class="btn btn-success">
                <i class="ti ti-download me-1"></i>Export Emails
            </a>
            <a href="<?= base_url('admin/newsletters') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Audience Navigation Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-2">
            <ul class="nav nav-pills nav-justified" id="audienceTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active fw-semibold" id="candidates-tab" data-bs-toggle="pill" href="#candidatesTab" role="tab">
                        <i class="ti ti-user me-1"></i>Candidates (<?= count($candidates ?? []) ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" id="employers-tab" data-bs-toggle="pill" href="#employersTab" role="tab">
                        <i class="ti ti-building me-1"></i>Employers (<?= count($employers ?? []) ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" id="guest-tab" data-bs-toggle="pill" href="#guestTab" role="tab">
                        <i class="ti ti-mail me-1"></i>Guest Subscribers (<?= count($guestSubscribers ?? []) ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" id="registered-sub-tab" data-bs-toggle="pill" href="#registeredSubTab" role="tab">
                        <i class="ti ti-user-check me-1"></i>Registered Subscribers (<?= count($registeredSubscribers ?? []) ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-success" id="new-users-tab" data-bs-toggle="pill" href="#newUsersTab" role="tab">
                        <i class="ti ti-sparkles me-1"></i>New Signups (<?= count($newCandidates ?? []) + count($newEmployers ?? []) ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-info" id="events-tab" data-bs-toggle="pill" href="#eventsTab" role="tab">
                        <i class="ti ti-certificate me-1"></i>Webinars &amp; Courses (<?= count($webinarRegistrants ?? []) + count($trainingRegistrants ?? []) ?>)
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content" id="audienceTabsContent">
        
        <!-- 1. Registered Candidates Tab -->
        <div class="tab-pane fade show active" id="candidatesTab" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Registered Candidates (<?= count($candidates ?? []) ?>)</h5>
                    <a href="<?= base_url('admin/newsletters/create') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-send me-1"></i>Message Candidates
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>Candidate Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Joined Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($candidates)): ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No registered candidates found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($candidates as $cand): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-primary-transparent rounded-circle me-2 d-flex align-items-center justify-content-center text-primary fw-bold">
                                                        <?= strtoupper(substr($cand['full_name'] ?? $cand['username'] ?? 'C', 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <?php if (!empty($cand['candidate_id'])): ?>
                                                            <a href="<?= base_url('admin/candidates/view/' . $cand['candidate_id']) ?>" class="fw-semibold text-primary text-decoration-underline" title="View Profile">
                                                                <?= esc($cand['full_name'] ?: ($cand['username'] ?? 'Candidate #' . $cand['user_id'])) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="fw-semibold"><?= esc($cand['full_name'] ?: ($cand['username'] ?? 'Candidate #' . $cand['user_id'])) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($cand['email'] ?? '—') ?></td>
                                            <td><?= esc($cand['phone'] ?? '—') ?></td>
                                            <td><?= !empty($cand['created_at']) ? date('M d, Y', strtotime($cand['created_at'])) : '—' ?></td>
                                            <td>
                                                <span class="badge bg-<?= !empty($cand['active']) ? 'success-transparent text-success' : 'secondary-transparent text-secondary' ?>">
                                                    <?= !empty($cand['active']) ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($cand['candidate_id'])): ?>
                                                    <a href="<?= base_url('admin/candidates/view/' . $cand['candidate_id']) ?>" class="btn btn-sm btn-light" title="View Profile">
                                                        <i class="ti ti-eye"></i> View Profile
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Registered Employers Tab -->
        <div class="tab-pane fade" id="employersTab" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Registered Employers (<?= count($employers ?? []) ?>)</h5>
                    <a href="<?= base_url('admin/newsletters/create') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-send me-1"></i>Message Employers
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>Company / Employer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Verification</th>
                                    <th>Joined Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($employers)): ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No registered employers found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($employers as $emp): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-warning-transparent rounded-circle me-2 d-flex align-items-center justify-content-center text-warning fw-bold">
                                                        <?= strtoupper(substr($emp['company_name'] ?? $emp['username'] ?? 'E', 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <?php if (!empty($emp['employer_id'])): ?>
                                                            <a href="<?= base_url('admin/employers/view/' . $emp['employer_id']) ?>" class="fw-semibold text-primary text-decoration-underline" title="View Company Profile">
                                                                <?= esc($emp['company_name'] ?: ($emp['username'] ?? 'Employer #' . $emp['user_id'])) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="fw-semibold"><?= esc($emp['company_name'] ?: ($emp['username'] ?? 'Employer #' . $emp['user_id'])) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($emp['email'] ?? '—') ?></td>
                                            <td><?= esc($emp['phone'] ?? '—') ?></td>
                                            <td>
                                                <?php
                                                $vStatus = $emp['verification_status'] ?? 'pending';
                                                $vClass = $vStatus === 'verified' ? 'success' : ($vStatus === 'rejected' ? 'danger' : 'warning');
                                                ?>
                                                <span class="badge bg-<?= $vClass ?>-transparent text-<?= $vClass ?>">
                                                    <?= ucfirst($vStatus) ?>
                                                </span>
                                            </td>
                                            <td><?= !empty($emp['created_at']) ? date('M d, Y', strtotime($emp['created_at'])) : '—' ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($emp['employer_id'])): ?>
                                                    <a href="<?= base_url('admin/employers/view/' . $emp['employer_id']) ?>" class="btn btn-sm btn-light" title="View Profile">
                                                        <i class="ti ti-eye"></i> View Profile
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Guest Subscribers Tab -->
        <div class="tab-pane fade" id="guestTab" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Guest Newsletter Subscribers (<?= count($guestSubscribers ?? []) ?>)</h5>
                    <span class="badge bg-secondary">Unregistered Public Visitors</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>Subscriber Email</th>
                                    <th>Subscribed At</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($guestSubscribers)): ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No guest subscribers found.</td></tr>
                                <?php else: ?>
                                    <?php $i = 1; foreach ($guestSubscribers as $gSub): ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td class="fw-semibold"><i class="ti ti-mail me-2 text-muted"></i><?= esc($gSub->email) ?></td>
                                            <td><?= !empty($gSub->created_at) ? date('M d, Y H:i', strtotime($gSub->created_at)) : '—' ?></td>
                                            <td>
                                                <span class="badge bg-<?= !empty($gSub->is_active) ? 'success' : 'danger' ?>-transparent text-<?= !empty($gSub->is_active) ? 'success' : 'danger' ?>">
                                                    <?= !empty($gSub->is_active) ? 'Active' : 'Unsubscribed' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <form method="POST" action="<?= base_url('admin/newsletters/subscribers/delete/' . $gSub->id) ?>" class="d-inline" onsubmit="return confirm('Delete this guest subscriber?')">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Registered Subscribers Tab -->
        <div class="tab-pane fade" id="registeredSubTab" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Registered Subscribers (<?= count($registeredSubscribers ?? []) ?>)</h5>
                    <span class="badge bg-primary">Registered Accounts Opted In</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Account Name</th>
                                    <th>User Role</th>
                                    <th>Subscribed At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($registeredSubscribers)): ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No registered account subscribers found.</td></tr>
                                <?php else: ?>
                                    <?php $i = 1; foreach ($registeredSubscribers as $rSub): 
                                        $rName = !empty($rSub['company_name']) ? $rSub['company_name'] : (!empty($rSub['full_name']) ? $rSub['full_name'] : ($rSub['username'] ?? 'User #' . $rSub['user_id']));
                                    ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td class="fw-semibold"><?= esc($rSub['email']) ?></td>
                                            <td><?= esc($rName) ?></td>
                                            <td>
                                                <span class="badge bg-<?= ($rSub['user_type'] ?? '') === 'employer' ? 'primary' : 'info' ?>-transparent text-<?= ($rSub['user_type'] ?? '') === 'employer' ? 'primary' : 'info' ?>">
                                                    <?= ucfirst($rSub['user_type'] ?? 'User') ?>
                                                </span>
                                            </td>
                                            <td><?= !empty($rSub['created_at']) ? date('M d, Y', strtotime($rSub['created_at'])) : '—' ?></td>
                                            <td>
                                                <span class="badge bg-<?= !empty($rSub['is_active']) ? 'success' : 'secondary' ?>-transparent text-<?= !empty($rSub['is_active']) ? 'success' : 'secondary' ?>">
                                                    <?= !empty($rSub['is_active']) ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. New Signups Tab -->
        <div class="tab-pane fade" id="newUsersTab" role="tabpanel">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><i class="ti ti-user-plus me-1 text-info"></i>New Candidates (Last 30 Days: <?= count($newCandidates ?? []) ?>)</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-nowrap">
                                    <thead><tr><th>Name</th><th>Email</th><th>Date Joined</th></tr></thead>
                                    <tbody>
                                        <?php if (empty($newCandidates)): ?>
                                            <tr><td colspan="3" class="text-center py-3 text-muted">No new candidates in last 30 days.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($newCandidates as $nc): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($nc['candidate_id'])): ?>
                                                            <a href="<?= base_url('admin/candidates/view/' . $nc['candidate_id']) ?>" class="fw-semibold text-primary">
                                                                <?= esc($nc['full_name'] ?: ($nc['username'] ?? 'Candidate #' . $nc['user_id'])) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <?= esc($nc['full_name'] ?: ($nc['username'] ?? 'Candidate #' . $nc['user_id'])) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= esc($nc['email']) ?></td>
                                                    <td><?= date('M d, Y', strtotime($nc['created_at'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><i class="ti ti-building-community me-1 text-warning"></i>New Employers (Last 30 Days: <?= count($newEmployers ?? []) ?>)</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-nowrap">
                                    <thead><tr><th>Company</th><th>Email</th><th>Date Joined</th></tr></thead>
                                    <tbody>
                                        <?php if (empty($newEmployers)): ?>
                                            <tr><td colspan="3" class="text-center py-3 text-muted">No new employers in last 30 days.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($newEmployers as $ne): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($ne['employer_id'])): ?>
                                                            <a href="<?= base_url('admin/employers/view/' . $ne['employer_id']) ?>" class="fw-semibold text-primary">
                                                                <?= esc($ne['company_name'] ?: ($ne['username'] ?? 'Employer #' . $ne['user_id'])) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <?= esc($ne['company_name'] ?: ($ne['username'] ?? 'Employer #' . $ne['user_id'])) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= esc($ne['email']) ?></td>
                                                    <td><?= date('M d, Y', strtotime($ne['created_at'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Webinars & Training Registrations Tab -->
        <div class="tab-pane fade" id="eventsTab" role="tabpanel">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><i class="ti ti-video me-1 text-success"></i>Webinar Registrants (<?= count($webinarRegistrants ?? []) ?>)</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-nowrap">
                                    <thead><tr><th>Attendee</th><th>Webinar</th><th>Date</th></tr></thead>
                                    <tbody>
                                        <?php if (empty($webinarRegistrants)): ?>
                                            <tr><td colspan="3" class="text-center py-3 text-muted">No webinar registrations found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($webinarRegistrants as $wr): 
                                                $attName = !empty($wr['company_name']) ? $wr['company_name'] : (!empty($wr['full_name']) ? $wr['full_name'] : ($wr['email'] ?? 'User #' . $wr['user_id']));
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold"><?= esc($attName) ?></div>
                                                        <div class="fs-12 text-muted"><?= esc($wr['email']) ?></div>
                                                    </td>
                                                    <td><strong><?= esc($wr['webinar_title'] ?? 'Webinar') ?></strong></td>
                                                    <td><?= !empty($wr['registered_at']) ? date('M d, Y', strtotime($wr['registered_at'])) : '—' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><i class="ti ti-book me-1 text-purple"></i>Training / Course Registrants (<?= count($trainingRegistrants ?? []) ?>)</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-nowrap">
                                    <thead><tr><th>Candidate</th><th>Course Title</th><th>Date Enrolled</th></tr></thead>
                                    <tbody>
                                        <?php if (empty($trainingRegistrants)): ?>
                                            <tr><td colspan="3" class="text-center py-3 text-muted">No course registrations found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($trainingRegistrants as $tr): 
                                                $candName = !empty($tr['full_name']) ? $tr['full_name'] : ($tr['email'] ?? 'User #' . $tr['user_id']);
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($tr['candidate_id'])): ?>
                                                            <a href="<?= base_url('admin/candidates/view/' . $tr['candidate_id']) ?>" class="fw-semibold text-primary">
                                                                <?= esc($candName) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <div class="fw-semibold"><?= esc($candName) ?></div>
                                                        <?php endif; ?>
                                                        <div class="fs-12 text-muted"><?= esc($tr['email']) ?></div>
                                                    </td>
                                                    <td><strong><?= esc($tr['course_title'] ?? 'Course') ?></strong></td>
                                                    <td><?= !empty($tr['enrolled_at']) ? date('M d, Y', strtotime($tr['enrolled_at'])) : '—' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
