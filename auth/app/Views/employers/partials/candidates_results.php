<?php
$db = \Config\Database::connect();
$user = auth()->user();
$employer = model(\App\Models\EmployerModel::class)->where('user_id', $user->id)->first();

$unlockedIds = [];
if ($employer) {
    $unlockedIds = array_column(
        $db->table('candidate_unlocks')
           ->select('job_seeker_id')
           ->where('employer_id', $employer->id)
           ->get()
           ->getResultArray(),
        'job_seeker_id'
    );
}

$creditService = new \App\Services\CreditService();
$hasUnlimitedAccess = $user ? $creditService->hasUnlimitedAccess($user->id) : false;
?>

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

<script>
// Re-bind click event handlers for the newly loaded unlock buttons
document.querySelectorAll('[data-unlock-id]').forEach(function(b) {
    b.addEventListener('click', function() {
        if (typeof openUnlockModal === 'function') {
            openUnlockModal(b.getAttribute('data-unlock-id'), b.getAttribute('data-unlock-name'));
        } else {
            // Fallback if not scoped globally
            var scrim = document.getElementById('unlock-scrim');
            var nameEl = document.getElementById('unlock-name');
            if (scrim && nameEl) {
                document.getElementById('unlock-confirm').setAttribute('data-current-id', b.getAttribute('data-unlock-id'));
                nameEl.textContent = b.getAttribute('data-unlock-name');
                scrim.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }
    });
});
</script>
