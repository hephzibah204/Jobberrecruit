<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 0; }
    body {
        font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        font-size: 10pt;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }
    .page { width: 210mm; min-height: 297mm; }
    .banner {
        background: linear-gradient(135deg, #4c1d95, #6d28d9, #7c3aed);
        padding: 32px 30px 24px 30px;
        color: #ffffff;
    }
    .banner .name {
        font-size: 26pt;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .banner .title-line {
        font-size: 11pt;
        color: #c4b5fd;
        font-weight: 500;
        margin-bottom: 12px;
    }
    .banner .contact {
        font-size: 9pt;
        color: #ddd6fe;
    }
    .banner .contact span.sep {
        margin: 0 8px;
        color: rgba(255,255,255,0.3);
    }
    .body-content {
        padding: 22px 30px 24px 30px;
    }
    .section-title {
        font-size: 11pt;
        font-weight: 700;
        color: #4c1d95;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        padding-bottom: 3px;
        border-bottom: 2.5px solid #7c3aed;
    }
    .summary-text {
        font-size: 9.5pt;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 14px;
    }
    .premium-summary {
        background: #ffffff;
        background: rgba(255,255,255,0.05);
        border-left: 4px solid #6d28d9;
        border-left: 4px solid var(--primary-color, #6d28d9);
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    .exp-item {
        margin-bottom: 12px;
    }
    .exp-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .exp-position {
        font-size: 10.5pt;
        font-weight: 700;
        color: #0f172a;
    }
    .exp-date {
        font-size: 8.5pt;
        color: #64748b;
        font-weight: 500;
    }
    .exp-company {
        font-size: 10pt;
        color: #6d28d9;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .exp-desc {
        font-size: 9.5pt;
        color: #334155;
        line-height: 1.5;
    }
    .exp-desc ul { margin: 3px 0; padding-left: 16px; }
    .exp-desc li { margin-bottom: 1px; }
    .edu-item {
        margin-bottom: 8px;
    }
    .edu-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .edu-school {
        font-size: 10.5pt;
        font-weight: 700;
        color: #0f172a;
    }
    .edu-year { font-size: 8.5pt; color: #64748b; }
    .edu-degree {
        font-size: 9.5pt;
        color: #475569;
        font-style: italic;
    }
    .skill-items {
        font-size: 9.5pt;
        color: #334155;
        line-height: 1.8;
    }
    .skill-tag {
        display: inline-block;
        background: #f5f3ff;
        color: #5b21b6;
        border: 1px solid #ddd6fe;
        padding: 2px 10px;
        margin: 2px 2px;
        font-size: 9pt;
        font-weight: 500;
    }
</style>
<?= $this->include('candidate/resume/ai_replies_css') ?>
</head>
<body>
<div class="page">

<div class="banner">
    <div class="name"><?= esc($resume->full_name ?? 'CANDIDATE NAME') ?></div>
    <?php if (!empty($resume->title) && $resume->title !== 'My Professional Resume'): ?>
    <div class="title-line"><?= esc($resume->title) ?></div>
    <?php endif; ?>
    <div class="contact">
        <?php if (!empty($resume->email)): ?><?= esc($resume->email) ?><?php endif; ?>
        <?php if (!empty($resume->email) && (!empty($resume->phone) || !empty($resume->location))): ?><span class="sep">|</span><?php endif; ?>
        <?php if (!empty($resume->phone)): ?><?= esc($resume->phone) ?><?php endif; ?>
        <?php if (!empty($resume->phone) && !empty($resume->location)): ?><span class="sep">|</span><?php endif; ?>
        <?php if (!empty($resume->location)): ?><?= esc($resume->location) ?><?php endif; ?>
    </div>
</div>

<div class="body-content">

<?php if (!empty($resume->summary)): ?>
<div class="section-title">Professional Summary</div>
<?php $summary = $resume->summary; $plain = strip_tags($summary) === $summary; ?>
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
<div class="exp-item">
    <div class="exp-header">
        <span class="exp-position"><?= esc($exp->position ?? 'Position') ?></span>
        <span class="exp-date"><?= date('M Y', strtotime($exp->start_date ?? 'now')) ?> &ndash; <?= !empty($exp->is_current) ? 'Present' : (!empty($exp->end_date) ? date('M Y', strtotime($exp->end_date)) : '') ?></span>
    </div>
    <div class="exp-company"><?= esc($exp->company ?? 'Company') ?></div>
    <?php if (!empty($exp->description)): ?>
    <div class="exp-desc"><?= nl2br(esc($exp->description)) ?></div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($education)): ?>
<div class="section-title">Education</div>
<?php foreach ($education as $edu): ?>
<div class="edu-item">
    <div class="edu-header">
        <span class="edu-school"><?= esc($edu->institution ?? 'Institution') ?></span>
        <span class="edu-year"><?= !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '' ?></span>
    </div>
    <div class="edu-degree"><?= esc($edu->degree ?? 'Degree') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?></div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($skills)): ?>
<div class="section-title">Skills &amp; Competencies</div>
<div class="skill-items">
    <?php foreach ($skills as $skill): ?>
        <?php if (!empty($skill->skill_name)): ?>
        <span class="skill-tag"><?= esc($skill->skill_name) ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</div>
</div>
</body>
</html>
