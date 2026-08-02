<div class="tri" style="grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));">
    <?php if (!empty($candidates)): ?>
        <?php foreach ($candidates as $c): ?>
            <?php
                // Resolve initials
                $initials = '';
                $words = explode(' ', preg_replace('/\s+/', ' ', trim($c->full_name)));
                if (count($words) >= 2) {
                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                } else {
                    $initials = strtoupper(substr($c->full_name, 0, 2));
                }
            ?>
            <div class="card">
                <div class="card-body" style="padding: 20px; display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="app-ava" style="width: 44px; height: 44px; font-size: 0.86rem; border-radius: 12px; flex-shrink: 0;">
                            <?= esc($initials) ?>
                        </span>
                        <div style="min-width: 0; flex: 1;">
                            <h3 style="font-size: 0.92rem; font-weight: 700; color: var(--brand-deep); margin: 0; display: flex; align-items: center; gap: 6px;">
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc($c->full_name) ?></span>
                                <?php if (isset($c->is_verified) && $c->is_verified): ?>
                                    <svg aria-hidden="true" style="width: 14px; height: 14px; color: var(--brand); flex-shrink: 0;"><use href="#i-check-c"/></svg>
                                <?php endif; ?>
                            </h3>
                            <small style="font-size: 0.74rem; color: var(--muted); display: block; margin-top: 2px;"><?= esc($c->job_title ?? 'Professional') ?></small>
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 6px; font-size: 0.72rem; color: var(--muted);">
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: var(--bg); padding: 4px 8px; border-radius: 6px;">
                            <svg aria-hidden="true" style="width: 12px; height: 12px;"><use href="#i-briefcase"/></svg>
                            <?= esc($c->experience_years ?? 0) ?> Yrs Exp
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: var(--bg); padding: 4px 8px; border-radius: 6px;">
                            <svg aria-hidden="true" style="width: 12px; height: 12px;"><use href="#i-clock"/></svg>
                            <?= esc($c->availability ?? 'Immediate') ?>
                        </span>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 2px;">
                        <?php 
                        $skillsList = array_filter(explode(',', $c->skills ?? ''));
                        $skillsDisplay = array_slice($skillsList, 0, 3);
                        foreach ($skillsDisplay as $s): if(!empty(trim($s))): ?>
                            <span class="pill pill--reviewed" style="font-size: 0.6rem; padding: 2px 8px;"><?= esc(trim($s)) ?></span>
                        <?php endif; endforeach; ?>
                        <?php if (count($skillsList) > 3): ?>
                            <span class="pill pill--closed" style="font-size: 0.6rem; padding: 2px 8px;">+<?= count($skillsList) - 3 ?></span>
                        <?php endif; ?>
                    </div>

                    <div style="margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <div>
                            <small style="font-size: 0.68rem; color: var(--muted); display: block;">Desired Salary</small>
                            <b style="font-family: 'Sora', sans-serif; font-size: 0.84rem; color: var(--brand-deep);">₦<?= number_format((float)($c->desired_salary ?? 0)) ?></b>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>" class="emp-btn emp-btn-outline emp-btn-sm" style="padding: 6px 12px; min-height: 32px;">
                                View
                            </a>
                            <button class="ic-btn" onclick="startMessage(<?= $c->id ?>)" style="width: 32px; height: 32px; border-radius: 6px;" title="Send Message">
                                <svg aria-hidden="true" style="width: 13px; height: 13px;"><use href="#i-chat"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-body empty-state" style="padding: 48px 24px;">
                <div class="empty-ic">
                    <svg aria-hidden="true" style="width: 24px; height: 24px;"><use href="#i-search-user"/></svg>
                </div>
                <h3>No candidates found matching criteria</h3>
                <p>Try adjusting your search query or filters.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<div style="margin-top: 20px;">
    <?= $pager->links('default', 'admin_pagination') ?>
</div>
