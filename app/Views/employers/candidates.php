<?php $page_title = 'Candidates Search'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<?php
// Query database directly to fetch unlocked IDs for the logged-in employer
$db = \Config\Database::connect();
$unlockedIds = [];
if (!empty($employer->id)) {
    $unlockedIds = array_column(
        $db->table('candidate_unlocks')
           ->select('job_seeker_id')
           ->where('employer_id', $employer->id)
           ->get()
           ->getResultArray(),
        'job_seeker_id'
    );
}

// Fetch wallet balance
$walletRow = $db->table('wallets')->where('user_id', $user->id)->get()->getRow();
$walletBalanceValue = $walletRow ? (float)$walletRow->balance : 0;
$walletBalanceFormatted = '₦' . number_format($walletBalanceValue, 2);
?>

<div class="page-head">
    <div class="page-head-left">
        <h1><svg aria-hidden="true"><use href="#i-search-user"/></svg> Find Candidates</h1>
        <p>Search verified candidates ready to work.</p>
    </div>
</div>

<div class="cand-layout">
    <!-- filters sidebar -->
    <aside class="card filters-card" id="filters" aria-label="Candidate filters">
        <form method="GET" action="<?= current_url() ?>" id="filters-form">
            <div class="f-head">
                <b><svg aria-hidden="true"><use href="#i-filter"/></svg> Filters</b>
                <button type="button" class="f-close" id="f-close" aria-label="Close filters"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            
            <!-- Desired Roles Filter -->
            <?php if (!empty($jobTitleCounts)): ?>
                <div class="f-group">
                    <div class="f-label">Desired role</div>
                    <?php foreach ($jobTitleCounts as $row): ?>
                        <?php if (!empty($row->job_title)): ?>
                            <label class="f-opt">
                                <input type="checkbox" name="job_title[]" value="<?= esc($row->job_title) ?>" 
                                    <?= in_array($row->job_title, (array)request()->getGet('job_title')) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <?= esc($row->job_title) ?>
                                <span class="n"><?= $row->total ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Skills Filter (if variable passed) -->
            <?php if (!empty($skills)): ?>
                <div class="f-group">
                    <div class="f-label">Skills</div>
                    <?php foreach ($skills as $skill): ?>
                        <label class="f-opt">
                            <input type="checkbox" name="skill[]" value="<?= esc($skill) ?>"
                                <?= in_array($skill, (array)request()->getGet('skill')) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <?= esc($skill) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Locations Filter (if variable passed) -->
            <?php if (!empty($locations)): ?>
                <div class="f-group">
                    <div class="f-label">Location</div>
                    <?php foreach ($locations as $loc): ?>
                        <label class="f-opt">
                            <input type="checkbox" name="location[]" value="<?= esc($loc) ?>"
                                <?= in_array($loc, (array)request()->getGet('location')) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <?= esc($loc) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Employment type Filter -->
            <?php if (!empty($jobTypeCounts)): ?>
                <div class="f-group">
                    <div class="f-label">Employment type</div>
                    <?php foreach ($jobTypeCounts as $row): ?>
                        <?php if (!empty($row->employment_type)): ?>
                            <label class="f-opt">
                                <input type="checkbox" name="employment_type[]" value="<?= esc($row->employment_type) ?>"
                                    <?= in_array($row->employment_type, (array)request()->getGet('employment_type')) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <span class="text-capitalize"><?= esc(str_replace('-', ' ', $row->employment_type)) ?></span>
                                <span class="n"><?= $row->total ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Education Level Filter -->
            <?php if (!empty($educationCounts)): ?>
                <div class="f-group">
                    <div class="f-label">Education level</div>
                    <?php foreach ($educationCounts as $row): ?>
                        <?php if (!empty($row->education_level)): ?>
                            <label class="f-opt">
                                <input type="checkbox" name="education_level[]" value="<?= esc($row->education_level) ?>"
                                    <?= in_array($row->education_level, (array)request()->getGet('education_level')) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <?= esc($row->education_level) ?>
                                <span class="n"><?= $row->total ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <a href="<?= current_url() ?>" class="emp-btn emp-btn-outline emp-btn-sm f-clear text-center">Clear all filters</a>
        </form>
    </aside>

    <div style="display:flex;flex-direction:column;gap:20px;min-width:0;width:100%">
        <!-- Search Bar Card -->
        <section class="card" aria-label="Candidate search">
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
                <form method="GET" action="<?= current_url() ?>">
                    <div class="toolbar">
                        <button type="button" class="emp-btn emp-btn-outline emp-btn-sm mob-filter-btn" id="f-open" aria-controls="filters" aria-expanded="false">
                            <svg aria-hidden="true"><use href="#i-filter"/></svg> Filters
                        </button>
                        <div class="search-wrap">
                            <svg aria-hidden="true"><use href="#i-search"/></svg>
                            <input class="input" type="search" name="keyword" value="<?= esc(request()->getGet('keyword') ?? '') ?>" placeholder="Search by name, skills, or job title…" aria-label="Search candidates">
                        </div>
                        <select class="select" name="sort" aria-label="Sort candidates">
                            <option value="best_match" <?= request()->getGet('sort') === 'best_match' ? 'selected' : '' ?>>Best match</option>
                            <option value="most_experienced" <?= request()->getGet('sort') === 'most_experienced' ? 'selected' : '' ?>>Most experienced</option>
                            <option value="recently_active" <?= request()->getGet('sort') === 'recently_active' ? 'selected' : '' ?>>Recently active</option>
                        </select>
                        <button type="submit" class="emp-btn emp-btn-primary">Search</button>
                    </div>
                </form>
                <div class="cand-list-head">
                    <span class="result-count"><b><?= number_format($total ?? 0) ?></b> candidates found</span>
                    <span style="font-size:.72rem;color:var(--muted);display:inline-flex;align-items:center;gap:6px">
                        <span class="cc-dot" aria-hidden="true"></span> Open to work
                    </span>
                </div>
                <!-- Pricing Disclosure -->
                <div class="notice notice--info" role="note" style="margin-top:2px">
                    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
                    <span>Searching and <b>messaging candidates is free</b>. Unlocking a candidate's contact details and CV download costs <b>₦5,000 per candidate</b>, charged from your wallet.</span>
                </div>
            </div>
        </section>

        <!-- Candidate Results List -->
        <section class="card" aria-label="Candidate results">
            <?php if (!empty($candidates)): ?>
                <?php foreach ($candidates as $c): ?>
                    <?php
                    $initials = '';
                    $words = explode(' ', preg_replace('/\s+/', ' ', trim($c->full_name)));
                    $initials = count($words) >= 2
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                        : strtoupper(substr($c->full_name, 0, 2));
                    
                    $isUnlocked = in_array($c->id, $unlockedIds) || ($hasUnlimitedAccess ?? false);
                    ?>
                    <div class="cand-card">
                        <?php if (!empty($c->profile_picture)): ?>
                            <img src="<?= base_url($c->profile_picture) ?>" alt="<?= esc($c->full_name) ?>" class="ava ava--round cc-ava" style="object-fit: cover; width: 42px; height: 42px;">
                        <?php else: ?>
                            <span class="ava ava--round cc-ava" aria-hidden="true"><?= esc($initials) ?></span>
                        <?php endif; ?>

                        <div class="cc-id">
                            <div class="cc-name">
                                <?= esc($c->full_name) ?>
                                <span class="cc-dot" title="Open to work" aria-label="Open to work"></span>
                            </div>
                            <div class="cc-role"><?= esc($c->job_title ?? 'Professional') ?></div>
                        </div>

                        <div class="cc-meta">
                            <span><svg aria-hidden="true"><use href="#i-grad"/></svg> <?= esc($c->education_level ?? 'Graduate') ?> · <?= esc($c->experience_years ?? 0) ?> yrs exp</span>
                            <span><svg aria-hidden="true"><use href="#i-clock"/></svg> Available: <b>&nbsp;<?= esc($c->availability ?? 'Immediate') ?></b></span>
                            <span><svg aria-hidden="true"><use href="#i-wallet"/></svg> Expects: <b>&nbsp;₦<?= number_format((float)($c->desired_salary ?? 0)) ?> / mo</b></span>
                        </div>

                        <div class="chips cc-skill">
                            <?php 
                            $skillsList = array_filter(explode(',', $c->skills ?? ''));
                            $skillsSlice = array_slice($skillsList, 0, 3);
                            foreach ($skillsSlice as $s): if(!empty(trim($s))): ?>
                                <span class="chip"><?= esc(trim($s)) ?></span>
                            <?php endif; endforeach; ?>
                            <?php if (count($skillsList) > 3): ?>
                                <span class="chip">+<?= count($skillsList) - 3 ?> more</span>
                            <?php endif; ?>
                            <?php if (empty($skillsList)): ?>
                                <span style="font-size:.72rem;color:var(--muted)">Skills not listed</span>
                            <?php endif; ?>
                        </div>

                        <div class="cc-act">
                            <button type="button" class="ic-btn" onclick="startMessage(<?= $c->id ?>)" aria-label="Message <?= esc($c->full_name) ?> (free)" title="Message — free">
                                <svg aria-hidden="true"><use href="#i-chat"/></svg>
                            </button>
                            <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>" class="emp-btn emp-btn-outline emp-btn-sm">View Profile</a>
                            
                            <?php if ($isUnlocked): ?>
                                <span class="pill pill--success"><svg aria-hidden="true"><use href="#i-check"/></svg> Unlocked</span>
                            <?php else: ?>
                                <button type="button" class="emp-btn emp-btn-primary emp-btn-sm" data-unlock-id="<?= $c->id ?>" data-unlock-name="<?= esc($c->full_name) ?>">
                                    Unlock <span class="unlock-price">₦5,000</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="pager">
                    <span>Showing results</span>
                    <div class="pager-nav">
                        <?= $pager->links('default', 'employer_pagination') ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty">
                    <div class="empty-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
                    <h3>No Candidates Found</h3>
                    <p>We couldn't find any candidates matching your criteria. Try adjusting your filters or search keyword.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- UNLOCK MODAL -->
<div class="modal-scrim" id="unlock-scrim">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="unlock-title">
        <div class="modal-head">
            <span class="modal-title" id="unlock-title"><svg aria-hidden="true"><use href="#i-shield"/></svg> Unlock Candidate Details</span>
            <button class="modal-close" id="unlock-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
        </div>
        <div class="modal-body">
            <p style="font-size:.86rem">Unlock <b id="unlock-name">this candidate</b> to get:</p>
            <ul class="unlock-list">
                <li><svg aria-hidden="true"><use href="#i-phone"/></svg> Direct phone number</li>
                <li><svg aria-hidden="true"><use href="#i-mail"/></svg> Email address</li>
                <li><svg aria-hidden="true"><use href="#i-download"/></svg> Full CV / resume download</li>
            </ul>
            <div class="unlock-total"><span>One-time unlock fee</span><b>₦5,000</b></div>
            
            <?php if ($walletBalanceValue < 5000): ?>
                <div class="notice notice--warn" id="unlock-warn" style="margin-top:12px">
                    <svg aria-hidden="true"><use href="#i-wallet"/></svg>
                    <span>Your wallet balance is <b><?= esc($walletBalanceFormatted) ?></b> — fund your wallet to unlock this candidate.</span>
                </div>
            <?php endif; ?>
            <p style="font-size:.72rem;color:var(--muted);margin-top:12px">The fee is deducted from your wallet. Unlocks are one-time per candidate, permanent for your account, and non-refundable. Messaging this candidate remains free without unlocking.</p>
        </div>
        <div class="modal-foot" style="flex-direction: column; gap: 10px; align-items: stretch; width: 100%;">
            <div style="display: flex; gap: 8px; justify-content: flex-end; width: 100%;">
                <button class="emp-btn emp-btn-outline" id="unlock-cancel" style="flex: 1;">Cancel</button>
                <?php if ($walletBalanceValue >= 5000): ?>
                    <button class="emp-btn emp-btn-primary" id="unlock-confirm" style="flex: 2;"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Use Wallet (₦5,000)</button>
                <?php endif; ?>
            </div>
            <button class="emp-btn emp-btn-accent" id="unlock-paystack-btn" style="width: 100%;"><svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
/* Mobile filters drawer toggle */
(function() {
    var f = document.getElementById('filters'),
        o = document.getElementById('f-open'),
        c = document.getElementById('f-close'),
        sc = document.getElementById('emp-scrim');
        
    function open() {
        f.classList.add('open');
        sc.hidden = false;
        requestAnimationFrame(function() { sc.classList.add('show'); });
        o.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    
    function close() {
        f.classList.remove('open');
        sc.classList.remove('show');
        o.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        setTimeout(function() { if (!sc.classList.contains('show')) sc.hidden = true; }, 240);
    }
    
    if (o) o.addEventListener('click', open);
    if (c) c.addEventListener('click', close);
    if (sc) sc.addEventListener('click', close);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && f.classList.contains('open')) close();
    });
})();

/* Unlock candidate paywall modal & API request handler */
(function() {
    var scrim = document.getElementById('unlock-scrim'),
        nameEl = document.getElementById('unlock-name'),
        closeB = document.getElementById('unlock-close'),
        cancelB = document.getElementById('unlock-cancel'),
        confirmB = document.getElementById('unlock-confirm'),
        lastFocusedElement = null,
        currentCandidateId = null;

    window.openUnlockModal = function(id, name) {
        currentCandidateId = id;
        nameEl.textContent = name;
        lastFocusedElement = document.activeElement;
        scrim.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    function close() {
        scrim.classList.remove('show');
        document.body.style.overflow = '';
        if (lastFocusedElement) lastFocusedElement.focus();
    }

    document.querySelectorAll('[data-unlock-id]').forEach(function(b) {
        b.addEventListener('click', function() {
            window.openUnlockModal(b.getAttribute('data-unlock-id'), b.getAttribute('data-unlock-name'));
        });
    });

    if (closeB) closeB.addEventListener('click', close);
    if (cancelB) cancelB.addEventListener('click', close);
    scrim.addEventListener('click', function(e) {
        if (e.target === scrim) close();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && scrim.classList.contains('show')) close();
    });

    if (confirmB) {
        confirmB.addEventListener('click', function() {
            confirmB.disabled = true;
            confirmB.textContent = 'Unlocking...';
            
            var formData = new FormData();
            formData.append('candidate_id', currentCandidateId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url("employer/candidates/unlock") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    location.reload();
                } else {
                    toastr.error(res.message || 'Failed to unlock candidate.');
                    confirmB.disabled = false;
                    confirmB.innerHTML = '<svg aria-hidden="true"><use href="#i-wallet"/></svg> Confirm Unlock (₦5,000)';
                }
            })
            .catch(function(err) {
                toastr.error('An error occurred. Please try again.');
                confirmB.disabled = false;
                confirmB.innerHTML = '<svg aria-hidden="true"><use href="#i-wallet"/></svg> Confirm Unlock (₦5,000)';
            });
        });
    }

    var paystackB = document.getElementById('unlock-paystack-btn');
    if (paystackB) {
        paystackB.addEventListener('click', function() {
            paystackB.disabled = true;
            paystackB.textContent = 'Processing...';

            fetch("<?= base_url('employer/initiate-payment') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    type: 'unlock',
                    candidate_id: currentCandidateId,
                    email: '<?= esc($user->email ?? '') ?>',
                    full_name: '<?= esc($employer->company_name ?? '') ?>'
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.paystack) {
                    let handler = PaystackPop.setup({
                        key: res.paystack,
                        email: res.email,
                        amount: res.amount,
                        ref: res.reference,
                        channels: [res.method || 'card'],
                        metadata: res.metadata,
                        callback: function(response) {
                            // Verify payment via Ajax
                            var verifyData = new FormData();
                            verifyData.append('reference', response.reference);
                            verifyData.append('candidate_id', currentCandidateId);
                            verifyData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                            fetch("<?= base_url('employer/candidates/unlock-verify') ?>", {
                                method: 'POST',
                                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                body: verifyData
                            })
                            .then(function(vr) { return vr.json(); })
                            .then(function(vres) {
                                if (vres.success) {
                                    location.reload();
                                } else {
                                    toastr.error(vres.message || 'Payment verification failed.');
                                    paystackB.disabled = false;
                                    paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                                }
                            })
                            .catch(function() {
                                toastr.error('Error verifying transaction.');
                                paystackB.disabled = false;
                                paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                            });
                        },
                        onClose: function() {
                            paystackB.disabled = false;
                            paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                        }
                    });
                    handler.openIframe();
                } else {
                    toastr.error(res.message || 'Failed to initialize payment.');
                    paystackB.disabled = false;
                    paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                }
            })
            .catch(function(err) {
                toastr.error('Connection error.');
                paystackB.disabled = false;
                paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
            });
        });
    }
})();

/* Start free conversation with candidate */
function startMessage(candidateId) {
    var formData = new FormData();
    formData.append('seeker_id', candidateId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url("employer/messages/start") ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success && res.redirect) {
            window.location.href = res.redirect;
        } else {
            toastr.error(res.message || 'Failed to start conversation');
        }
    })
    .catch(function(err) {
        toastr.error('Error starting conversation');
        console.error(err);
    });
}
</script>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/candidates') ?>?view=favorites" class="emp-btn emp-btn-outline"><svg aria-hidden="true"><use href="#i-star"/></svg> Favorites</a>
<a href="<?= base_url('employer/candidates') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-search-user"/></svg> Search</a>
<?= $this->endSection() ?>