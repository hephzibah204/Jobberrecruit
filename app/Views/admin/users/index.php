<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">User Management</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">All Registered Users</div>
            <form action="<?= base_url('admin/users') ?>" method="get" class="d-flex gap-2">
                <select name="role" class="form-select form-select-sm" style="width: auto;">
                    <option value="">All Roles</option>
                    <option value="job_seeker" <?= $role === 'job_seeker' ? 'selected' : '' ?>>Job Seeker</option>
                    <option value="employer" <?= $role === 'employer' ? 'selected' : '' ?>>Employer</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <select name="status" class="form-select form-select-sm" style="width: auto;">
                    <option value="">All Statuses</option>
                    <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Suspended</option>
                </select>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search email or name..." value="<?= esc($search) ?>" style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap table-hover border table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">User</th>
                            <th scope="col">Role</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Status</th>
                            <th scope="col">Joined Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): ?>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= $user->id ?></td>
                                <td>
                                    <div class="fw-semibold"><?= esc($user->seeker_name ?: $user->employer_name ?: 'Admin / No Name') ?></div>
                                    <div class="text-muted fs-12"><?= esc($user->email) ?></div>
                                </td>
                                <td>
                                    <?php
                                    $displayRole = !empty($user->role) ? $msgRole = $user->role : (!empty($user->user_type) ? $user->user_type : '');
                                    if($displayRole === 'employer'): ?>
                                        <span class="badge bg-primary-transparent">Employer</span>
                                    <?php elseif($displayRole === 'job_seeker' || $displayRole === 'candidate'): ?>
                                        <span class="badge bg-secondary-transparent">Candidate</span>
                                    <?php elseif($displayRole === 'admin' || $displayRole === 'superadmin'): ?>
                                        <span class="badge bg-dark-transparent">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="fw-semibold text-success">
                                        <?= number_format((float) $user->balance, 2) ?> NGN
                                    </span>
                                </td>
                                <td>
                                    <?php if($user->active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Suspended</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M j, Y', strtotime($user->created_at)) ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Manage
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!-- Fund Wallet -->
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openFundModal(<?= $user->id ?>, '<?= esc($user->email) ?>')"><i class="ri-wallet-3-line me-2 text-primary"></i> Fund Wallet</a></li>
                                            
                                            <!-- Reset Password -->
                                            <li>
                                                <form action="<?= base_url('admin/users/reset-password') ?>" method="post" onsubmit="return confirm('Generate a new random password for this user?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                    <button type="submit" class="dropdown-item"><i class="ri-key-2-line me-2 text-warning"></i> Reset Password</button>
                                                </form>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <!-- Suspend/Unsuspend -->
                                            <li>
                                                <form action="<?= base_url('admin/users/toggle-status') ?>" method="post" onsubmit="return confirm('Are you sure you want to change this user\'s access?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="ri-prohibited-line me-2 text-danger"></i> <?= $user->active ? 'Suspend User' : 'Unsuspend User' ?>
                                                    </button>
                                                </form>
                                            </li>

                                            <!-- Reset Account -->
                                            <li>
                                                <form action="<?= base_url('admin/users/reset-account') ?>" method="post" onsubmit="return confirm('WARNING: This will delete all their data, wallet, applications, and updates (Except login credentials). Proceed?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                    <button type="submit" class="dropdown-item"><i class="ri-refresh-line me-2 text-warning"></i> Fresh Start (Wipe Data)</button>
                                                </form>
                                            </li>

                                            <!-- Delete User -->
                                            <li>
                                                <form action="<?= base_url('admin/users/delete') ?>" method="post" onsubmit="return confirm('CRITICAL: This permanently deletes the user account entirely. This cannot be undone! Proceed?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                    <button type="submit" class="dropdown-item"><i class="ri-delete-bin-line me-2 text-danger"></i> Delete Account</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <?= $pager->links() ?>
            </div>
        </div>
    </div>
</div>

<!-- Fund Wallet Modal -->
<div class="modal fade" id="fundModal" tabindex="-1" aria-labelledby="fundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/users/fund') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="user_id" id="fundUserId">
            <div class="modal-header">
                <h6 class="modal-title" id="fundModalLabel">Fund Wallet</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Funding account: <strong id="fundUserEmail"></strong></p>
                <div class="mb-3">
                    <label class="form-label">Amount (NGN)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter amount..." required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Funds</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openFundModal(userId, userEmail) {
        document.getElementById('fundUserId').value = userId;
        document.getElementById('fundUserEmail').innerText = userEmail;
        var fundModal = new bootstrap.Modal(document.getElementById('fundModal'));
        fundModal.show();
    }
</script>
<?= $this->endSection() ?>
