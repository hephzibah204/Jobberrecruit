<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 24mm 25mm 20mm 25mm; }
    body {
        font-family: 'Georgia', 'Times New Roman', 'Palatino Linotype', serif;
        color: #1e293b;
        font-size: 10.5pt;
        line-height: 1.55;
        margin: 0;
        padding: 0;
    }
    .header {
        text-align: center;
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid #94a3b8;
    }
    .header .name {
        font-size: 26pt;
        font-weight: 700;
        color: #0c1a2e;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 0 0 6px 0;
    }
    .header .subtitle {
        font-size: 11pt;
        color: #7c3aed;
        font-weight: 500;
        font-style: italic;
        margin: 0 0 10px 0;
    }
    .header .contact {
        font-size: 9.5pt;
        color: #475569;
        margin: 0;
    }
    .header .contact span.sep {
        margin: 0 8px;
        color: #cbd5e1;
    }
    .section-title {
        font-size: 11pt;
        font-weight: 700;
        color: #0c1a2e;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-align: center;
        margin-top: 18px;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid #cbd5e1;
    }
    .summary-text {
        font-size: 10pt;
        color: #334155;
        line-height: 1.65;
        font-style: italic;
        text-align: center;
        margin-bottom: 4px;
    }
    .premium-summary {
        font-style: normal;
        margin: 8px auto 12px auto;
        max-width: 86%;
        background: #faf5ff;
        background: rgba(124,58,237,0.04);
        border-left: 4px solid #7c3aed;
        border-left: 4px solid var(--primary-color, #7c3aed);
        padding: 10px 14px;
        border-radius: 6px;
        color: #0c1a2e;
        font-weight: 600;
    }
    .exp-item {
        margin-bottom: 14px;
    }
    .exp-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        border-bottom: 1px dotted #e2e8f0;
        padding-bottom: 2px;
        margin-bottom: 3px;
    }
    .exp-position {
        font-size: 11pt;
        font-weight: 700;
        color: #0c1a2e;
    }
    .exp-date {
        font-size: 9pt;
        color: #64748b;
        font-weight: 500;
        font-style: italic;
    }
    .exp-company {
        font-size: 10pt;
        color: #7c3aed;
        font-weight: 600;
        margin-bottom: 3px;
    }
    .exp-desc {
        font-size: 10pt;
        color: #334155;
        line-height: 1.55;
    }
    .exp-desc ul { margin: 3px 0; padding-left: 18px; }
    .exp-desc li { margin-bottom: 2px; }
    .edu-item {
        margin-bottom: 10px;
        text-align: center;
    }
    .edu-school {
        font-size: 11pt;
        font-weight: 700;
        color: #0c1a2e;
    }
    .edu-year {
        font-size: 9pt;
        color: #64748b;
        font-style: italic;
    }
    .edu-degree {
        font-size: 10pt;
        color: #475569;
        font-style: italic;
    }
    .skill-items {
        text-align: center;
        font-size: 10pt;
        color: #334155;
        line-height: 1.7;
    }
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
        <?php if (!empty($resume->email) && (!empty($resume->phone) || !empty($resume->location))): ?><span class="sep">|</span><?php endif; ?>
        <?php if (!empty($resume->phone)): ?><?= esc($resume->phone) ?><?php endif; ?>
        <?php if (!empty($resume->phone) && !empty($resume->location)): ?><span class="sep">|</span><?php endif; ?>
        <?php if (!empty($resume->location)): ?><?= esc($resume->location) ?><?php endif; ?>
    </div>
</div>

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
<div class="section-title">Professional Experience</div>
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
    <div class="edu-school"><?= esc($edu->institution ?? 'Institution') ?></div>
    <div class="edu-degree"><?= esc($edu->degree ?? 'Degree') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?></div>
    <div class="edu-year"><?= !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '' ?></div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($skills)): ?>
<div class="section-title">Core Competencies</div>
<div class="skill-items">
    <?php
    $skillNames = array_map(function($s) { return $s->skill_name ?? ''; }, $skills);
    $skillNames = array_filter($skillNames);
    echo esc(implode('  &nbsp;·&nbsp;  ', $skillNames));
    ?>
</div>
<?php endif; ?>

</body>
</html>
