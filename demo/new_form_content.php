            <div class="detail-card" id="apply-form-section">
                <!-- Apply Form styled as apply-form-card -->
                <?= form_open_multipart(base_url("job/application/{$job->id}"), [
                    'id' => 'apply-form',
                    'class' => 'apply-form-card',
                    'novalidate' => true
                ], ['job_id' => (string)$job->id]) ?>
                
                <h2 class="apply-form-title">
                    <svg aria-hidden="true" width="19" height="19"><use href="#i-send"/></svg> Apply for this position
                </h2>

                <!-- Display Flash Error -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="form-error mb-4" style="margin-bottom:14px; color:#b91c1c; font-size:.85rem; font-weight:600;">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <!-- Display Validation Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="form-error mb-4" style="margin-bottom:14px; color:#b91c1c; font-size:.85rem; font-weight:600;">
                        <ul style="margin:0; padding-left:20px;">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!$user): ?>
                    <div class="guest-notice" id="guest-notice" style="background:#fffbeb; border:1px solid #fde68a; padding:12px; border-radius:8px; display:flex; gap:10px; font-size:.85rem; margin-bottom:18px;">
                        <svg aria-hidden="true" width="18" height="18" fill="var(--accent)"><use href="#i-flag"/></svg>
                        <span>You're applying as a guest. <a href="<?= base_url('login') ?>">Log in</a> or <a href="<?= base_url('register') ?>">create an account</a> to save your CV and track applications.</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="req">*</span></label>
                            <input class="form-input" type="text" id="first_name" name="first_name" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="req">*</span></label>
                            <input class="form-input" type="text" id="last_name" name="last_name" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="req">*</span></label>
                        <input class="form-input" type="email" id="email" name="email" placeholder="john.doe@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number <span class="req">*</span></label>
                        <input class="form-input" type="tel" id="phone" name="phone" placeholder="+234 800 000 0000" required>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="cover_letter">Cover Letter <span class="opt">(optional)</span></label>
                    <textarea class="form-textarea" id="cover_letter" name="cover_letter" maxlength="2000" placeholder="Why are you a great fit for this role? Highlight your relevant experience, skills, and enthusiasm for <?= esc($job->title) ?> at <?= esc($job->employer_name) ?>."></textarea>
                    <p class="form-hint">Tailor your message to the job description. Be concise and professional.</p>
                </div>

                <!-- PRE-SCREENING QUESTIONS -->
                <?php if (!empty($questions)): ?>
                    <h3 style="font-size:1rem; font-weight:700; margin-top:24px; margin-bottom:12px;">Pre-screening Questions</h3>
                    <?php foreach ($questions as $q): ?>
                        <div class="form-group">
                            <label><?= esc($q->question_text) ?> <?= $q->is_required ? '<span class="req">*</span>' : '' ?></label>
                            
                            <?php if ($q->question_type === 'text'): ?>
                                <textarea name="answers[<?= $q->id ?>]" class="form-textarea" style="min-height:80px;" <?= $q->is_required ? 'required' : '' ?>></textarea>
                            <?php elseif ($q->question_type === 'yes_no'): ?>
                                <div style="display:flex; gap:16px; margin-top:8px;">
                                    <label><input type="radio" name="answers[<?= $q->id ?>]" value="Yes" <?= $q->is_required ? 'required' : '' ?>> Yes</label>
                                    <label><input type="radio" name="answers[<?= $q->id ?>]" value="No"> No</label>
                                </div>
                            <?php elseif (in_array($q->question_type, ['select', 'multiple_choice'])): ?>
                                <select name="answers[<?= $q->id ?>]" class="form-select" <?= $q->is_required ? 'required' : '' ?>>
                                    <option value="">Select an option</option>
                                    <?php 
                                        $opts = !empty($q->options) ? $q->options : ($q->options ?? '');
                                        foreach (explode(',', $opts) as $option): 
                                    ?>
                                        <option value="<?= trim(esc($option)) ?>"><?= trim(esc($option)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($q->question_type === 'checkbox'): ?>
                                <?php 
                                    $opts = !empty($q->options) ? $q->options : ($q->options ?? '');
                                    foreach (explode(',', $opts) as $option): 
                                ?>
                                    <label style="display:block; margin-top:6px;">
                                        <input type="checkbox" name="answers[<?= $q->id ?>][]" value="<?= trim(esc($option)) ?>"> <?= trim(esc($option)) ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="form-group" style="margin-top:24px;">
                    <label for="cv_file">Attach Your CV <span class="req">*</span></label>
                    
                    <?php if ($user && !empty($candidate->resume)): ?>
                        <div style="margin-bottom:12px;">
                            <label style="display:block; margin-bottom:8px;"><input type="radio" name="cv_source" value="saved" checked onchange="document.getElementById('upload_cv_div').style.display='none'"> Use saved CV (<?= esc(basename($candidate->resume)) ?>)</label>
                            <label style="display:block;"><input type="radio" name="cv_source" value="upload" onchange="document.getElementById('upload_cv_div').style.display='block'"> Upload new CV</label>
                        </div>
                        <div id="upload_cv_div" style="display:none;">
                            <input type="file" id="cv_file" name="cv_file" class="form-input" accept=".pdf,.doc,.docx" style="padding:10px;">
                        </div>
                    <?php else: ?>
                        <input type="file" id="cv_file" name="cv_file" class="form-input" accept=".pdf,.doc,.docx" required style="padding:10px;">
                        <input type="hidden" name="cv_source" value="upload">
                    <?php endif; ?>
                    <p class="form-hint">Max 10MB — PDF, DOC, DOCX</p>
                </div>

                <div class="form-group" style="margin-top:24px;">
                    <label>References <span class="opt">(optional)</span></label>
                    <p class="form-hint mb-2">Most employers request references later in the process.</p>
                    <div id="ref-rows">
                        <div class="ref-row">
                            <input class="form-input" type="text" placeholder="Full Name" name="ref_name[]">
                            <input class="form-input" type="text" placeholder="Job Title" name="ref_title[]">
                            <input class="form-input" type="email" placeholder="Email" name="ref_email[]">
                        </div>
                    </div>
                </div>

                <div class="form-row" style="margin-top:24px;">
                    <div class="form-group">
                        <label for="availability">When can you start? <span class="req">*</span></label>
                        <select class="form-select" id="availability" name="availability" required>
                            <option value="">Select availability</option>
                            <option value="Immediately">Immediately</option>
                            <option value="Within 2 weeks">Within 2 weeks</option>
                            <option value="Within 1 month">Within 1 month</option>
                            <option value="Negotiable">Negotiable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="salary_expectation">Expected Salary (NGN) <span class="opt">(optional)</span></label>
                        <input class="form-input" type="text" id="salary_expectation" name="salary_expectation" placeholder="e.g., 250,000 – 300,000">
                    </div>
                </div>

                <div class="form-group">
                    <label>Eligibility to Work in Nigeria <span class="req">*</span></label>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:8px;">
                        <label><input type="radio" name="work_eligibility" value="Yes" required> Yes, I am legally authorized to work in Nigeria</label>
                        <label><input type="radio" name="work_eligibility" value="No" required> No, I would require sponsorship</label>
                    </div>
                </div>

                <div class="form-group" style="margin-top:20px;">
                    <label style="display:flex; gap:10px; align-items:flex-start; font-weight:normal; font-size:.85rem;">
                        <input type="checkbox" name="consent" required style="margin-top:4px;">
                        <span>I consent to the processing of my personal data in accordance with the <a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>. I understand my application will be retained for future opportunities unless I opt out.</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px; padding:12px; font-size:1rem; border-radius:8px;">
                    <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg> 
                    Submit Application
                </button>
                <?= form_close() ?>
            </div>
