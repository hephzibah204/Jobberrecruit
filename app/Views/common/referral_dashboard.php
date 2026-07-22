<?= $this->extend(auth()->loggedIn() && auth()->user()->user_type === 'employer' ? 'layouts/employer' : 'layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ── Referrals Layout styling ── */
.ref-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 20px;
    margin-top: 20px;
    margin-bottom: 20px;
}
@media (max-width: 992px) {
    .ref-grid { grid-template-columns: 1fr; }
}
.ref-link-row {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 6px;
}
.ref-link-row .input {
    flex: 1;
}
.social-row {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    flex-wrap: wrap;
}
.btn-social {
    color: #fff !important;
}
.btn-wa { background: #25d366; border-color: #25d366; }
.btn-wa:hover { background: #128c7e; border-color: #128c7e; }
.btn-xs { background: #0f1419; border-color: #0f1419; }
.btn-xs:hover { background: #2c3640; border-color: #2c3640; }
.btn-li { background: #0077b5; border-color: #0077b5; }
.btn-li:hover { background: #004b73; border-color: #004b73; }

ol.how {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
ol.how li {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}
ol.how li::before {
    content: none;
}
.how-n {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--brand-light);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    font-weight: 700;
    flex-shrink: 0;
}
ol.how li b {
    display: block;
    font-size: .88rem;
    color: var(--brand-deep);
}
ol.how li p {
    font-size: .78rem;
    color: var(--muted);
    margin: 2px 0 0;
    line-height: 1.4;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-gift"/></svg> Referral Program</h1>
            <p>Invite friends and earn wallet rewards to spend on courses and premium features.</p>
        </div>
    </div>

    <!-- Referral Stats Grid -->
    <section class="stats" aria-label="Referral statistics" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="stat">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-users"/></svg></span>
            </div>
            <div class="stat-num"><?= (int) $stats['total_referrals'] ?></div>
            <div class="stat-lbl">Total Referrals</div>
        </div>
        <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check-c"/></svg></span>
            </div>
            <div class="stat-num"><?= (int) $stats['completed_referrals'] ?></div>
            <div class="stat-lbl">Successful Referrals</div>
        </div>
        <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg></span>
            </div>
            <div class="stat-num">₦<?= number_format($stats['total_earned'], 2) ?></div>
            <div class="stat-lbl">Total Earned</div>
        </div>
    </section>

    <!-- Main Content Panels -->
    <div class="ref-grid">
        
        <!-- Invite Card -->
        <section class="card" aria-label="Invite friends">
            <div class="card-head">
                <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-link"/></svg> Invite Your Friends</span>
            </div>
            <div class="card-body">
                <p style="font-size:.84rem;color:var(--muted);margin-bottom:16px">Share your unique link. When a friend signs up and completes a qualifying action, your reward lands in your wallet — spend it on training courses or premium features.</p>
                
                <label class="lbl" for="ref-link">Your referral link</label>
                <div class="ref-link-row">
                    <input class="input" id="ref-link" type="text" readonly value="<?= site_url('register?ref=' . $referralCode) ?>" aria-label="Your referral link">
                    <button class="btn btn-accent" id="copy-btn">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-copy"/></svg> Copy Link
                    </button>
                </div>

                <div class="lbl" style="margin-top:18px">Share on social media</div>
                <div class="social-row">
                    <a class="btn btn-sm btn-social btn-wa" href="https://wa.me/?text=Join%20me%20on%20JobberRecruit%20%E2%80%94%20find%20verified%20jobs%20in%20Nigeria%3A%20<?= urlencode(site_url('register?ref=' . $referralCode)) ?>" target="_blank" rel="noopener">
                        <svg aria-hidden="true" style="width:13px;height:13px;fill:currentColor;stroke:none;margin-right:4px;"><use href="#i-whatsapp"/></svg> WhatsApp
                    </a>
                    <a class="btn btn-sm btn-social btn-xs" href="https://x.com/intent/post?text=Join%20me%20on%20JobberRecruit&url=<?= urlencode(site_url('register?ref=' . $referralCode)) ?>" target="_blank" rel="noopener">
                        <svg aria-hidden="true" style="width:13px;height:13px;fill:currentColor;stroke:none;margin-right:4px;"><use href="#i-x-social"/></svg> X
                    </a>
                    <a class="btn btn-sm btn-social btn-li" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(site_url('register?ref=' . $referralCode)) ?>" target="_blank" rel="noopener">
                        <svg aria-hidden="true" style="width:13px;height:13px;fill:currentColor;stroke:none;margin-right:4px;"><use href="#i-linkedin"/></svg> LinkedIn
                    </a>
                </div>

                <div class="notice notice--info" style="margin-top:18px;">
                    <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
                    <span>Rewards are credited to your JobberRecruit wallet. Wallet funds can be used for courses and services on the platform; they are not withdrawable as cash and are non-refundable.</span>
                </div>
            </div>
        </section>

        <!-- How It Works Card -->
        <section class="card" aria-label="How it works">
            <div class="card-head">
                <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg> How It Works</span>
            </div>
            <div class="card-body">
                <ol class="how">
                    <li>
                        <span class="how-n" aria-hidden="true">1</span>
                        <div>
                            <b>Share your link</b>
                            <p>Invite friends to join JobberRecruit with your unique referral link.</p>
                        </div>
                    </li>
                    <li>
                        <span class="how-n" aria-hidden="true">2</span>
                        <div>
                            <b>They sign up</b>
                            <p>Your friend creates a free account and starts exploring jobs.</p>
                        </div>
                    </li>
                    <li>
                        <span class="how-n" aria-hidden="true">3</span>
                        <div>
                            <b>Earn rewards</b>
                            <p>When they complete a qualifying action, your wallet is credited instantly — enough referrals can cover a full training course.</p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

    </div>

    <!-- Referrals Table Section -->
    <section class="card" aria-label="My referrals">
        <div class="card-head">
            <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-users"/></svg> My Referrals</span>
        </div>
        
        <?php if (empty($referrals)): ?>
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-gift"/></svg></span>
                <h3>You haven't referred anyone yet</h3>
                <p>Share your link above — every friend who joins and takes a qualifying action earns you a wallet reward.</p>
                <button class="btn btn-primary btn-sm" onclick="document.getElementById('copy-btn').click();window.scrollTo({top:0,behavior:'smooth'})">
                    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-copy"/></svg> Copy My Link
                </button>
            </div>
        <?php else: ?>
            <div class="tbl-wrap">
                <table class="tbl" style="width:100%;">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                            <th>Reward</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($referrals as $ref): ?>
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="ava--round" style="width:28px; height:28px; font-size:.65rem; background:var(--bg); border:1px solid var(--border); color:var(--text); display:flex; align-items:center; justify-content:center;">
                                            U
                                        </span>
                                        <b style="color:var(--brand-deep);"><?= esc($ref->referee_name) ?></b>
                                    </div>
                                </td>
                                <td style="font-size:.8rem; color:var(--muted);"><?= date('d M Y', strtotime($ref->joined_at)) ?></td>
                                <td>
                                    <?php if ($ref->status === 'rewarded'): ?>
                                        <span class="pill pill--success">Completed</span>
                                    <?php else: ?>
                                        <span class="pill pill--pending">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><b style="color:var(--brand-deep)">₦<?= number_format($ref->reward_amount, 2) ?></b></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#copy-btn').on('click', function() {
        var copyText = document.getElementById("ref-link");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        toastr.success('Referral link copied to clipboard!');
    });
});
</script>
<?= $this->endSection() ?>
