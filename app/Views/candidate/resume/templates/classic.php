<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 22mm 22mm 20mm 22mm; }
    body {
        font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        font-size: 10.5pt;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }
    .header {
        text-align: left;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 3px solid #1e40af;
    }
    .header .name {
        font-size: 24pt;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: 0.5px;
        margin: 0 0 4px 0;
    }
    .header .subtitle {
        font-size: 11pt;
        color: #1e40af;
        font-weight: 500;
        margin: 0 0 8px 0;
    }
    .header .contact {
        font-size: 9.5pt;
        color: #475569;
        margin: 0;
    }
    .header .contact span {
        margin: 0 6px;
        color: #cbd5e1;
    }
    .section-title {
        font-size: 12pt;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1.5px solid #e2e8f0;
        padding-bottom: 3px;
        margin-top: 18px;
        margin-bottom: 10px;
    }
    .experience-item {
        margin-bottom: 14px;
    }
    .exp-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .exp-position {
        font-size: 11pt;
        font-weight: 700;
        color: #0f172a;
    }
    .exp-date {
        font-size: 9.5pt;
        color: #64748b;
        font-weight: 500;
    }
    .exp-company {
        font-size: 10pt;
        color: #1e40af;
        font-weight: 600;
        margin-bottom: 3px;
    }
    .exp-description {
        font-size: 10pt;
        color: #334155;
        line-height: 1.55;
        margin-bottom: 4px;
    }
    .exp-description ul {
        margin: 4px 0;
        padding-left: 18px;
    }
    .exp-description li {
        margin-bottom: 2px;
    }
    .education-item {
        margin-bottom: 10px;
    }
    .edu-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .edu-institution {
        font-size: 11pt;
        font-weight: 700;
        color: #0f172a;
    }
    .edu-year {
        font-size: 9.5pt;
        color: #64748b;
    }
    .edu-degree {
        font-size: 10pt;
        color: #475569;
        font-style: italic;
    }
    .skills-section {
        margin-top: 4px;
    }
    .skill-pill {
        display:inline-block; margin-right:8px; background: linear-gradient(90deg, rgba(14,30,66,0.03), rgba(14,30,66,0.01)); padding:6px 10px; border-radius:18px; font-size:10pt; color:#0f172a; margin-bottom:6px; border:1px solid rgba(14,30,66,0.04);
    }
    .skill-category {
        margin-bottom: 6px;
    }
    .skill-cat-label {
        font-weight: 600;
        font-size: 10pt;
        color: #0f172a;
    }
    .skill-items {
        font-size: 10pt;
        color: #334155;
        line-height: 1.6;
    }
    .summary-text {
        font-size: 11pt;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 8px;
    }
    .premium-summary {
        background: #f0f4ff;
        background: linear-gradient(90deg, rgba(13,110,253,0.06), rgba(11,94,215,0.02));
        border-left: 4px solid #1e40af;
        border-left: 4px solid var(--primary-color, #1e40af);
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 8px;
        color: #0f172a;
        font-weight: 500;
    }
    a { color: inherit; text-decoration: none; }
</style>
<?= $this->include('candidate/resume/ai_replies_css') ?>
</head>
<body>

<div class="header">
    <div class="name"><?= esc($resume->full_name ?? 'CANDIDATE NAME') ?></div>
    <?php if (!empty($resume->title) && $resume->title !== 'My Professional Resume'): ?>
    <div class="subtitle"><?= esc($resume->title) ?></div>
    <?php endif; ?>
    <div class="contact">
        <?php if (!empty($resume->email)): ?><?= esc($resume->email) ?><?php endif; ?>
        <?php if (!empty($resume->email) && (!empty($resume->phone) || !empty($resume->location))): ?><span>|</span><?php endif; ?>
        <?php if (!empty($resume->phone)): ?><?= esc($resume->phone) ?><?php endif; ?>
        <?php if (!empty($resume->phone) && !empty($resume->location)): ?><span>|</span><?php endif; ?>
        <?php if (!empty($resume->location)): ?><?= esc($resume->location) ?><?php endif; ?>
    </div>
</div>

<?php if (!empty($resume->summary)): ?>
<div class="section-title">Professional Summary</div>
<?php
    // If the summary contains HTML fragments (AI may return a small safe HTML block), render it directly.
    $summary = $resume->summary;
    $plain = strip_tags($summary) === $summary;
?>
<?php if ($plain): ?>
    <div class="summary-text"><?= nl2br(esc($summary)) ?></div>
<?php else: ?>
    <?php $allowed = '<p><br><strong><em><ul><ol><li><h3><h4><div><span><table><thead><tbody><tr><th><td><img>'; ?>
    <div class="summary-text"><?php echo strip_tags($summary, $allowed); ?></div>
<?php endif; ?>
<?php endif; ?>

<?php if (!empty($experiences)): ?>
<div class="section-title">Experience</div>
<?php foreach ($experiences as $exp): ?>
<div class="experience-item">
    <div class="exp-header">
        <span class="exp-position"><?= esc($exp->position ?? 'Position') ?></span>
        <span class="exp-date"><?= date('M Y', strtotime($exp->start_date ?? 'now')) ?> &ndash; <?= !empty($exp->is_current) ? 'Present' : (!empty($exp->end_date) ? date('M Y', strtotime($exp->end_date)) : '') ?></span>
    </div>
    <div class="exp-company"><?= esc($exp->company ?? 'Company') ?></div>
    <?php if (!empty($exp->description)): ?>
    <div class="exp-description"><?= nl2br(esc($exp->description)) ?></div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($education)): ?>
<div class="section-title">Education</div>
<?php foreach ($education as $edu): ?>
<div class="education-item">
    <div class="edu-header">
        <span class="edu-institution"><?= esc($edu->institution ?? 'Institution') ?></span>
        <span class="edu-year"><?= !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '' ?></span>
    </div>
    <div class="edu-degree"><?= esc($edu->degree ?? 'Degree') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?></div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($skills)): ?>
<div class="section-title">Skills</div>
<div class="skills-section">
    <div class="skill-items">
        <?php foreach ($skills as $skill): ?>
            <?php if (!empty($skill->skill_name)): ?>
                <span class="skill-pill"><?= esc($skill->skill_name) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>
