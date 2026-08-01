<?php $page_title = 'Referral Program'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.stats--ref { grid-template-columns: repeat(3, 1fr); }
@media (max-width:800px) { .stats--ref { grid-template-columns: 1fr 1fr; } }
@media (max-width:520px) { .stats--ref { grid-template-columns: 1fr; } }
.ref-grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: clamp(14px, 1.8vw, 20px); align-items: start; }
@media (max-width:900px) { .ref-grid { grid-template-columns: 1fr; } }
.ref-link-row { display: flex; gap: 8px; flex-wrap: wrap; }
.ref-link-row .input { flex: 1; min-width: 220px; font-family: ui-monospace, Menlo, Consolas, monospace; font-size: .82rem; background: var(--bg); }
.copied-toast { font-size: .74rem; font-weight: 600; color: var(--success); display: none; align-items: center; gap: 4px; margin-top: 8px; }
.copied-toast svg { width: 13px; height: 13px; }
.copied-toast.show { display: inline-flex; }
.social-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; }
.btn-social { color: #fff; border: none; }
.btn-social svg { width: 16px; height: 16px; }
.btn-wa { background: #25D366; } .btn-wa:hover { background: #1ebe5b; color: #fff; }
.btn-xs { background: #141926; } .btn-xs:hover { background: #333a49; color: #fff; }
.btn-li { background: #0A66C2; } .btn-li:hover { background: #0857a5; color: #fff; }
.how { list-style: none; display: flex; flex-direction: column; gap: 16px; counter-reset: step; }
.how li { display: flex; gap: 12px; align-items: flex-start; }
.how-n { counter-increment: step; width: 30px; height: 30px; border-radius: 50%; background: var(--brand-light); color: var(--brand); font-family: 'Sora', sans-serif; font-weight: 800; font-size: .8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.how-n::before { content: counter(step); }
.how b { display: block; font-size: .85rem; color: var(--brand-deep); }
.how p { font-size: .78rem; color: var(--muted); line-height: 1.6; }
.notice { display: flex; gap: 8px; align-items: flex-start; font-size: .78rem; border-radius: 10px; padding: 12px 14px; border: 1px solid; }
.notice svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 2px; }
.notice--info { background: var(--brand-light); border-color: #cfe2f2; color: var(--brand-dark); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <div class="page-head">
        <div>
            <h1>
                <svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-gift"/></svg>
                Referral Program
            </h1>
            <p>Invite friends and earn wallet rewards to spend on courses and premium features.</p>
        </div>
    </div>

    <?php 
        $refLink = base_url('register?ref=' . esc($candidate->referral_code ?? 'FE7A21C4'));
        $waLink  = "https://wa.me/?text=" . urlencode("Join me on JobberRecruit — find verified jobs in Nigeria: $refLink");
        $xLink   = "https://x.com/intent/post?text=" . urlencode("Join me on JobberRecruit") . "&url=" . urlencode($refLink);
        $liLink  = "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($refLink);
    ?>

    <section class="stats stats--ref" aria-label="Referral statistics">
        <div class="stat">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span>
            </div>
            <div class="stat-num"><?= isset($referrals) ? count($referrals) : 0 ?></div>
            <div class="stat-lbl">Total Referrals</div>
        </div>
        <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true"><use href="#i-check-c"/></svg></span>
            </div>
            <div class="stat-num"><?= $successful_count ?? 0 ?></div>
            <div class="stat-lbl">Successful Referrals</div>
        </div>
        <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true"><use href="#i-wallet"/></svg></span>
            </div>
            <div class="stat-num">&#8358;<?= number_format($total_earned ?? 0, 2) ?></div>
            <div class="stat-lbl">Total Earned</div>
        </div>
    </section>

    <div class="ref-grid">
        <section class="card" aria-label="Invite friends">
            <div class="card-head">
                <span class="card-title"><svg aria-hidden="true"><use href="#i-link"/></svg> Invite Your Friends</span>
            </div>
            <div class="card-body">
                <p style="font-size:.84rem;color:var(--muted);margin-bottom:16px">
                    Share your unique link. When a friend signs up and completes a qualifying action, your reward lands in your wallet — spend it on training courses or premium features.
                </p>
                
                <label class="lbl" for="ref-link">Your referral link</label>
                <div class="ref-link-row">
                    <input class="input" id="ref-link" type="text" readonly value="<?= $refLink ?>" aria-label="Your referral link">
                    <button class="btn btn-accent" id="copy-btn">
                        <svg aria-hidden="true"><use href="#i-copy"/></svg> Copy Link
                    </button>
                </div>
                <span class="copied-toast" id="copied" role="status">
                    <svg aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2"><use href="#i-check"/></svg> Link copied to clipboard
                </span>
                
                <div class="lbl" style="margin-top:18px">Share on social media</div>
                <div class="social-row">
                    <a class="btn btn-sm btn-social btn-wa" href="<?= $waLink ?>" target="_blank" rel="noopener">
                        <svg aria-hidden="true"><use href="#i-whatsapp"/></svg> WhatsApp
                    </a>
                    <a class="btn btn-sm btn-social btn-xs" href="<?= $xLink ?>" target="_blank" rel="noopener">
                        <svg aria-hidden="true"><use href="#i-x-social"/></svg> X
                    </a>
                    <a class="btn btn-sm btn-social btn-li" href="<?= $liLink ?>" target="_blank" rel="noopener">
                        <svg aria-hidden="true"><use href="#i-linkedin"/></svg> LinkedIn
                    </a>
                </div>
                
                <div class="notice notice--info" style="margin-top:18px">
                    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
                    <span>Rewards are credited to your JobberRecruit wallet. Wallet funds can be used for courses and services on the platform; they are not withdrawable as cash and are non-refundable.</span>
                </div>
            </div>
        </section>

        <section class="card" aria-label="How it works">
            <div class="card-head">
                <span class="card-title"><svg aria-hidden="true"><use href="#i-bulb"/></svg> How It Works</span>
            </div>
            <div class="card-body">
                <ol class="how">
                    <li>
                        <span class="how-n" aria-hidden="true"></span>
                        <div><b>Share your link</b><p>Invite friends to join JobberRecruit with your unique referral link.</p></div>
                    </li>
                    <li>
                        <span class="how-n" aria-hidden="true"></span>
                        <div><b>They sign up</b><p>Your friend creates a free account and starts exploring jobs.</p></div>
                    </li>
                    <li>
                        <span class="how-n" aria-hidden="true"></span>
                        <div><b>Earn rewards</b><p>When they complete a qualifying action, your wallet is credited instantly — enough referrals can cover a full training course.</p></div>
                    </li>
                </ol>
            </div>
        </section>
    </div>

    <section class="card" aria-label="My referrals">
        <div class="card-head">
            <span class="card-title"><svg aria-hidden="true"><use href="#i-users"/></svg> My Referrals</span>
        </div>
        <?php if (empty($referrals)): ?>
        <div class="empty">
            <span class="empty-ic"><svg aria-hidden="true" style="width:28px;height:28px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-gift"/></svg></span>
            <h3>You haven't referred anyone yet</h3>
            <p>Share your link above — every friend who joins and takes a qualifying action earns you a wallet reward.</p>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('copy-btn').click();window.scrollTo({top:0,behavior:'smooth'})">
                <svg aria-hidden="true"><use href="#i-copy"/></svg> Copy My Link
            </button>
        </div>
        <?php else: ?>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Referred User</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                        <th>Reward</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($referrals as $ref): ?>
                    <tr>
                        <td><b><?= esc($ref->referred_name) ?></b></td>
                        <td>
                            <?php if ($ref->status === 'successful'): ?>
                            <span class="pill pill--success">Successful</span>
                            <?php else: ?>
                            <span class="pill pill--pending">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M j, Y', strtotime($ref->created_at)) ?></td>
                        <td><?= $ref->status === 'successful' ? '&#8358;' . number_format($ref->reward_amount, 2) : '-' ?></td>
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
(function() {
    'use strict';
    var b = document.getElementById('copy-btn'),
        i = document.getElementById('ref-link'),
        t = document.getElementById('copied');
    
    if (b && i && t) {
        b.addEventListener('click', function() {
            var done = function() {
                t.classList.add('show');
                setTimeout(function() { t.classList.remove('show') }, 2200);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(i.value).then(done);
            } else {
                i.select();
                try {
                    document.execCommand('copy');
                    done();
                } catch(e) {}
            }
        });
    }
})();
</script>
<?= $this->endSection() ?>
