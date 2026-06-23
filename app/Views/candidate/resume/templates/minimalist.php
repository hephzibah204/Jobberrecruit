<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 28mm 24mm 24mm 24mm; }
    body {
        font-family: 'Inter', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        font-size: 10pt;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }
    .header {
        margin-bottom: 28px;
    }
    .header .name {
        font-size: 28pt;
        font-weight: 300;
        color: #0f172a;
        letter-spacing: 4px;
        text-transform: uppercase;
        margin: 0 0 2px 0;
    }
    .header .subtitle {
        font-size: 10pt;
        color: #94a3b8;
        font-weight: 400;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 0 0 12px 0;
    }
    .header .contact {
        font-size: 8.5pt;
        color: #64748b;
        letter-spacing: 0.5px;
        margin: 0;
    }
    .header .contact span.sep {
        margin: 0 6px;
        color: #cbd5e1;
    }
    .header-rule {
        height: 1px;
        background: #e2e8f0;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 8.5pt;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        margin-bottom: 6px;
    }
    .section-content {
        margin-bottom: 18px;
        padding-left: 0;
    }
    .summary-text {
        font-size: 10pt;
        color: #334155;
        line-height: 1.7;
    }
    .premium-summary {
        background: #f4f6f8;
        background: linear-gradient(90deg, rgba(14,30,66,0.04), rgba(14,30,66,0.02));
        border-left: 4px solid #0f172a;
        border-left: 4px solid var(--primary-color, #0f172a);
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    .exp-item {
        margin-bottom: 14px;
    }
    .exp-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .exp-position {
        font-size: 10.5pt;
        font-weight: 600;
        color: #0f172a;
    }
    .exp-date {
        font-size: 8.5pt;
        color: #94a3b8;
        font-weight: 400;
    }
    .exp-company {
        font-size: 9.5pt;
        color: #475569;
        font-weight: 500;
        margin-bottom: 2px;
    }
    .exp-desc {
        font-size: 9.5pt;
        color: #475569;
        line-height: 1.6;
    }
    .exp-desc ul { margin: 3px 0; padding-left: 16px; }
    .exp-desc li { margin-bottom: 2px; }
    .edu-item {
        margin-bottom: 10px;
    }
    .edu-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .edu-school {
        font-size: 10.5pt;
        font-weight: 600;
        color: #0f172a;
    }
    .edu-year {
        font-size: 8.5pt;
        color: #94a3b8;
    }
    .edu-degree {
        font-size: 9.5pt;
        color: #475569;
    }
    .skill-items {
        font-size: 9.5pt;
        color: #475569;
        line-height: 1.8;
    }
    .skill-items .dot {
        color: #cbd5e1;
        margin: 0 4px;
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
        <?php if (!empty($resume->email) && (!empty($resume->phone) || !empty($resume->location))): ?><span class="sep">/</span><?php endif; ?>
        <?php if (!empty($resume->phone)): ?><?= esc($resume->phone) ?><?php endif; ?>
        <?php if (!empty($resume->phone) && !empty($resume->location)): ?><span class="sep">/</span><?php endif; ?>
        <?php if (!empty($resume->location)): ?><?= esc($resume->location) ?><?php endif; ?>
    </div>
</div>
<div class="header-rule"></div>

<?php if (!empty($resume->summary)): ?>
<div class="section-title">Profile</div>
<div class="section-content">
    <?php $summary = $resume->summary; $plain = strip_tags($summary) === $summary; ?>
    <?php if ($plain): ?>
        <div class="summary-text"><?= nl2br(esc($summary)) ?></div>
    <?php else: ?>
        <?php $allowed = '<p><br><strong><em><ul><ol><li><h3><h4><div><span><table><thead><tbody><tr><th><td><img>'; ?>
        <div class="summary-text"><?php echo strip_tags($summary, $allowed); ?></div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (!empty($experiences)): ?>
<div class="section-title">Experience</div>
<div class="section-content">
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
</div>
<?php endif; ?>

<?php if (!empty($education)): ?>
<div class="section-title">Education</div>
<div class="section-content">
<?php foreach ($education as $edu): ?>
<div class="edu-item">
    <div class="edu-header">
        <span class="edu-school"><?= esc($edu->institution ?? 'Institution') ?></span>
        <span class="edu-year"><?= !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '' ?></span>
    </div>
    <div class="edu-degree"><?= esc($edu->degree ?? 'Degree') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?></div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($skills)): ?>
<div class="section-title">Skills</div>
<div class="section-content">
    <div class="skill-items">
        <?php
        $skillNames = array_map(function($s) { return $s->skill_name ?? ''; }, $skills);
        $skillNames = array_filter($skillNames);
        echo esc(implode(' <span class="dot">·</span> ', $skillNames));
        ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>
