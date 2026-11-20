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

if (!isset($savedIds) && $employer) {
    try {
        $savedIds = array_column(
            $db->table('candidate_shortlists')
               ->select('job_seeker_id')
               ->where('employer_id', $employer->id)
               ->get()
               ->getResultArray(),
            'job_seeker_id'
        );
    } catch (\Throwable $e) {
        $savedIds = [];
    }
}

$creditService = new \App\Services\CreditService();
$hasUnlimitedAccess = $user ? $creditService->hasUnlimitedAccess($user->id) : false;
?>

<?php if (!empty($candidates)): ?>
    <?php foreach ($candidates as $c): ?>
        <?php
        $initials = '';
        $words = explode(' ', preg_replace('/\s+/', ' ', trim($c->full_name ?? '')));
        $initials = count($words) >= 2
            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
            : strtoupper(substr($c->full_name ?? 'C', 0, 2));
        
        $isUnlocked = in_array($c->id, $unlockedIds ?? []) || ($hasUnlimitedAccess ?? false);
        $isSaved = in_array($c->id, $savedIds ?? []);

        // 1. MyJobMag Activity Badge calculation
        $activityLabel = 'Active recently';
        $activityClass = 'active-recent';
        $lastAct = !empty($c->last_activity_at) ? strtotime($c->last_activity_at) : (!empty($c->updated_at) ? strtotime($c->updated_at) : 0);
        if ($lastAct > 0) {
            $diffDays = (time() - $lastAct) / 86400;
            if ($diffDays <= 2) {
                $activityLabel = 'Active today';
                $activityClass = 'active-today';
            } elseif ($diffDays <= 7) {
                $activityLabel = 'Active this week';
                $activityClass = 'active-week';
            } elseif ($diffDays <= 14) {
                $activityLabel = 'Active in last 2 weeks';
                $activityClass = 'active-fortnight';
            } elseif ($diffDays <= 30) {
                $activityLabel = 'Active this month';
                $activityClass = 'active-month';
            } else {
                $activityLabel = 'Open to work';
                $activityClass = 'active-open';
            }
        } else {
            $activityLabel = 'Active this week';
            $activityClass = 'active-week';
        }

        // 2. Location string
        $locParts = [];
        if (!empty($c->city)) $locParts[] = trim($c->city);
        if (!empty($c->state_name)) $locParts[] = trim($c->state_name);
        elseif (!empty($c->location)) $locParts[] = trim($c->location);
        $locationText = !empty($locParts) ? implode(', ', $locParts) : 'Nigeria';

        // 3. CV Freshness calculation
        $cvFreshness = 'CV on file';
        $cvRef = !empty($c->updated_at) ? strtotime($c->updated_at) : (!empty($c->created_at) ? strtotime($c->created_at) : 0);
        if ($cvRef > 0) {
            $days = floor((time() - $cvRef) / 86400);
            if ($days <= 1) {
                $cvFreshness = 'CV updated today';
            } elseif ($days < 30) {
                $cvFreshness = 'CV updated ' . $days . ' days ago';
            } elseif ($days < 365) {
                $months = max(1, floor($days / 30));
                $cvFreshness = 'CV updated ' . $months . ' mo ago';
            } else {
                $cvFreshness = 'CV updated recently';
            }
        }

        // 4. Profile completeness
        $pct = (int)($c->profile_completion ?? 0);
        if ($pct <= 0) {
            $checks = [!empty($c->full_name), !empty($c->job_title), !empty($c->skills), !empty($c->education_level), !empty($c->experience_years), !empty($c->resume)];
            $pct = round((count(array_filter($checks)) / count($checks)) * 100);
        }
        $isComplete = $pct >= 80;
        $completenessText = $isComplete ? 'Profile complete' : $pct . '% complete';

        // Avatar check
        $rawPic = $c->profile_picture ?? '';
        $hasPic = false;
        $picUrl = '';
        if (!empty($rawPic)) {
            if (filter_var($rawPic, FILTER_VALIDATE_URL) || str_starts_with($rawPic, 'http://') || str_starts_with($rawPic, 'https://')) {
                $hasPic = true;
                $picUrl = $rawPic;
            } else {
                $cleanPath = ltrim($rawPic, '/\\');
                if (file_exists(FCPATH . $cleanPath)) {
                    $hasPic = true;
                    $picUrl = base_url($cleanPath);
                } elseif (file_exists(FCPATH . 'uploads/' . $cleanPath)) {
                    $hasPic = true;
                    $picUrl = base_url('uploads/' . $cleanPath);
                }
            }
        }
        $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode(trim($c->full_name ?? 'Candidate')) . "&background=0A2F57&color=fff&size=128&bold=true";

        // Skills slice (up to 6 readable chips)
        $skillsList = array_filter(array_map('trim', explode(',', $c->skills ?? '')));
        $skillsSlice = array_slice($skillsList, 0, 6);
        ?>

        <article class="cand-card" id="cand-item-<?= $c->id ?>">
            <div class="cand-card-head">
                <?php if ($hasPic): ?>
                    <img src="<?= esc($picUrl) ?>" alt="<?= esc($c->full_name) ?>" class="cc-ava" onerror="this.onerror=null; this.src='<?= esc($fallbackAvatar) ?>';">
                <?php else: ?>
                    <span class="cc-ava" aria-hidden="true"><?= esc($initials) ?></span>
                <?php endif; ?>

                <div class="cand-card-info">
                    <div class="cand-name-row">
                        <h3 class="cc-name">
                            <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>"><?= esc($c->full_name) ?></a>
                        </h3>
                        <span class="cand-activity-badge <?= esc($activityClass) ?>">
                            <span class="pulse-dot"></span> <?= esc($activityLabel) ?>
                        </span>
                        <?php if ($isUnlocked): ?>
                            <span class="cand-unlocked-badge"><svg aria-hidden="true"><use href="#i-check"/></svg> Unlocked</span>
                        <?php endif; ?>
                    </div>

                    <div class="cc-role"><?= esc($c->job_title ?? 'Professional') ?></div>

                    <!-- MyJobMag 3-Part Trust Line -->
                    <div class="cand-trust-line">
                        <span class="cand-trust-item"><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($locationText) ?></span>
                        <span class="cand-trust-sep">|</span>
                        <span class="cand-trust-item"><svg aria-hidden="true"><use href="#i-file-text"/></svg> <?= esc($cvFreshness) ?></span>
                        <span class="cand-trust-sep">|</span>
                        <span class="cand-trust-item cand-complete-tag"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> <?= esc($completenessText) ?></span>
                    </div>
                </div>
            </div>

            <!-- JobberRecruit Key Criteria Data Bar -->
            <div class="cand-data-row">
                <span class="cand-data-item"><svg aria-hidden="true"><use href="#i-grad"/></svg> <?= esc($c->education_level ?? 'Graduate') ?> · <?= esc($c->experience_years ?? 0) ?> yrs exp</span>
                <span class="cand-data-item"><svg aria-hidden="true"><use href="#i-clock"/></svg> Available: <b><?= esc($c->availability ?? 'Immediate') ?></b></span>
                <?php if (!empty($c->desired_salary) && (float)$c->desired_salary > 0): ?>
                    <span class="cand-data-item cand-salary-item"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Expects: <b>₦<?= number_format((float)$c->desired_salary) ?> / mo</b></span>
                <?php endif; ?>
            </div>

            <!-- Skills List (up to 6 readable chips) -->
            <?php if (!empty($skillsSlice)): ?>
                <div class="cand-skills-row">
                    <?php foreach ($skillsSlice as $s): ?>
                        <span class="cand-skill-chip"><?= esc($s) ?></span>
                    <?php endforeach; ?>
                    <?php if (count($skillsList) > 6): ?>
                        <span class="cand-skill-more">+<?= count($skillsList) - 6 ?> more</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Action Footer (View/Unlock + Save + Message) -->
            <div class="cand-card-foot">
                <?php if ($isUnlocked): ?>
                    <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>" class="emp-btn emp-btn-primary cand-btn-view">
                        View Profile
                    </a>
                <?php else: ?>
                    <button type="button" class="emp-btn emp-btn-primary cand-btn-view" data-unlock-id="<?= $c->id ?>" data-unlock-name="<?= esc($c->full_name) ?>">
                        Unlock <span class="unlock-price">₦5,000</span>
                    </button>
                <?php endif; ?>

                <button type="button" class="cand-btn-save <?= $isSaved ? 'is-saved' : '' ?>" data-candidate-id="<?= $c->id ?>" onclick="toggleSaveCandidate(<?= $c->id ?>, this)" title="<?= $isSaved ? 'Remove from shortlist' : 'Save to shortlist' ?>">
                    <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-check' : '#i-bookmark' ?>"/></svg>
                    <span class="save-txt"><?= $isSaved ? 'Saved' : 'Save' ?></span>
                </button>

                <button type="button" class="cand-btn-msg" onclick="startMessage(<?= $c->id ?>)" aria-label="Message <?= esc($c->full_name) ?> (free)" title="Message candidate (free)">
                    <svg aria-hidden="true"><use href="#i-chat"/></svg>
                </button>
            </div>
        </article>
    <?php endforeach; ?>
    
    <div class="pager" style="margin-top:10px;">
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
                var confirmBtn = document.getElementById('unlock-confirm');
                if (confirmBtn) confirmBtn.setAttribute('data-current-id', b.getAttribute('data-unlock-id'));
                nameEl.textContent = b.getAttribute('data-unlock-name');
                scrim.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }
    });
});
</script>
