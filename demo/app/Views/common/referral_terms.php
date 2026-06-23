<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">JobberRecruit Referral Rewards Program</h4>
            <h6>Terms & Conditions</h6>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-body">
            <h5 class="mb-3">Earn 10% Wallet Credit for Referring New Employers or Candidates</h5>
            <p>Know an employer looking to hire or a candidate looking to advance their career?</p>
            <p>Invite them to JobberRecruit and earn 10% Wallet Credit when they successfully purchase an eligible paid service on our platform.</p>
            <p>Your rewards are credited directly to your JobberRecruit Wallet and can be used to purchase eligible services on JobberRecruit.</p>
            <p>Wallet Credits are not redeemable for cash and cannot be withdrawn or transferred.</p>

            <h5 class="mt-4 mb-3">How It Works</h5>
            <ol>
                <li class="mb-2"><strong>Share Your Referral Link:</strong> Share your unique referral link with employers and candidates in your network.</li>
                <li class="mb-2"><strong>User Registers:</strong> The employer or candidate creates an account on JobberRecruit using your referral link.</li>
                <li class="mb-2"><strong>User Makes a Qualifying Purchase:</strong> The referred employer or candidate purchases an eligible paid service on JobberRecruit.</li>
                <li class="mb-2"><strong>Earn 10% Wallet Credit:</strong> Once the payment has been successfully received and verified, 10% of the purchase value will be credited to your JobberRecruit Wallet.</li>
            </ol>

            <h5 class="mt-4 mb-3">Terms and Conditions</h5>
            <ul>
                <li class="mb-2">Referral rewards apply only to new employers and new candidates referred to JobberRecruit.</li>
                <li class="mb-2">Only the first time purchase associated with an employer or candidate will qualify for rewards.</li>
            </ul>

            <?php
                $userType = auth()->user()->user_type ?? 'candidate';
                $prefix = ($userType === 'employer') ? 'employer' : 'candidate';
            ?>
            <form action="<?= site_url($prefix . '/referrals/accept-terms') ?>" method="POST" class="mt-4">
                <?= csrf_field() ?>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="acceptTerms" name="accept_terms" required>
                    <label class="form-check-label" for="acceptTerms">
                        I have read and agree to the Referral Rewards Program Terms and Conditions.
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Accept and Continue</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
