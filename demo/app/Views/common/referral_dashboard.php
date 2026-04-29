<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Referral & Affiliate Program</h4>
            <h6>Invite friends and earn rewards when they join and use JobberRecruit</h6>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <div class="col-xl-4 col-md-4 d-flex">
            <div class="card flex-fill bg-primary-transparent border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">Total Referrals</h6>
                            <h3 class="fw-bold mb-0"><?= $stats['total_referrals'] ?></h3>
                        </div>
                        <div class="avatar avatar-lg bg-primary">
                            <i class="ti ti-users fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 d-flex">
            <div class="card flex-fill bg-success-transparent border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">Successful Referrals</h6>
                            <h3 class="fw-bold mb-0"><?= $stats['completed_referrals'] ?></h3>
                        </div>
                        <div class="avatar avatar-lg bg-success">
                            <i class="ti ti-check fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 d-flex">
            <div class="card flex-fill bg-warning-transparent border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">Total Earned</h6>
                            <h3 class="fw-bold mb-0">₦<?= number_format($stats['total_earned'], 2) ?></h3>
                        </div>
                        <div class="avatar avatar-lg bg-warning text-white">
                            <i class="ti ti-wallet fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Invitation Link -->
        <div class="col-xl-6 col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Invite Your Friends</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Share your unique referral link with your network. When they sign up using your link, you'll earn rewards.</p>
                    
                    <div class="mb-4">
                        <label class="form-label">Your Referral Link</label>
                        <div class="input-group">
                            <input type="text" id="referral-link" class="form-control" value="<?= site_url('register?ref=' . $referralCode) ?>" readonly>
                            <button class="btn btn-primary" type="button" onclick="copyReferralLink()">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                        </div>
                    </div>

                    <h6>Share on Social Media</h6>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/?text=Check out JobberRecruit! Use my referral link to sign up: <?= urlencode(site_url('register?ref=' . $referralCode)) ?>" target="_blank" class="btn btn-success">
                            <i class="ti ti-brand-whatsapp me-1"></i>WhatsApp
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=Join JobberRecruit today and find your dream job! <?= urlencode(site_url('register?ref=' . $referralCode)) ?>" target="_blank" class="btn btn-info text-white">
                            <i class="ti ti-brand-twitter me-1"></i>Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(site_url('register?ref=' . $referralCode)) ?>" target="_blank" class="btn btn-primary">
                            <i class="ti ti-brand-linkedin me-1"></i>LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- How it works -->
        <div class="col-xl-6 col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">How It Works</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="avatar avatar-sm bg-light text-primary rounded-circle">1</span>
                        </div>
                        <div>
                            <h6>Share your link</h6>
                            <p class="text-muted small">Invite friends to join JobberRecruit using your unique referral code or link.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="avatar avatar-sm bg-light text-primary rounded-circle">2</span>
                        </div>
                        <div>
                            <h6>They sign up</h6>
                            <p class="text-muted small">Your friend creates an account and starts using the platform.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-0">
                        <div class="me-3">
                            <span class="avatar avatar-sm bg-light text-primary rounded-circle">3</span>
                        </div>
                        <div>
                            <h6>Earn Rewards</h6>
                            <p class="text-muted small">Once they complete a qualifying action (like making a payment or filling their profile), you receive your reward instantly in your wallet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral List -->
    <div class="card custom-card">
        <div class="card-header">
            <h5 class="card-title mb-0">My Referrals</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>User</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                            <th>Reward</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($referrals)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">You haven't referred anyone yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($referrals as $ref): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-light me-2">
                                                <i class="ti ti-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fs-13"><?= esc($ref->referee_name) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($ref->joined_at)) ?></td>
                                    <td>
                                        <?php if ($ref->status === 'rewarded'): ?>
                                            <span class="badge bg-success-transparent text-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-transparent text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>₦<?= number_format($ref->reward_amount, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function copyReferralLink() {
        var copyText = document.getElementById("referral-link");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        toastr.success('Referral link copied to clipboard!');
    }
</script>
<?= $this->endSection() ?>
