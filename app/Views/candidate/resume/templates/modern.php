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
    .page {
        width: 210mm;
        min-height: 297mm;
        position: relative;
    }
    .sidebar {
        position: absolute;
        top: 0;
        left: 0;
        width: 70mm;
        min-height: 297mm;
        background: #1e3a5f;
        color: #e2e8f0;
        padding: 30px 18px 30px 20px;
        box-sizing: border-box;
    }
    .main {
        margin-left: 70mm;
        padding: 30px 28px 30px 24px;
    }
    .sidebar .name {
        font-size: 20pt;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .sidebar .title-line {
        font-size: 9.5pt;
        color: #93c5fd;
        font-weight: 500;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.15);
    }
    .sidebar-section {
        margin-bottom: 16px;
    }
    .sidebar-section h3 {
        font-size: 9pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #93c5fd;
        margin-bottom: 6px;
        padding-bottom: 3px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar .contact-item {
        font-size: 8.5pt;
        color: #cbd5e1;
        margin-bottom: 4px;
        line-height: 1.4;
    }
    .sidebar .contact-item strong {
        color: #e2e8f0;
        font-weight: 600;
    }
    .sidebar .skill-tag {
        display: inline-block;
        font-size: 8pt;
        color: #e2e8f0;
        background: rgba(255,255,255,0.08);
        padding: 2px 8px;
        margin: 2px 1px;
        border-radius: 2px;
    }
    .main-section {
        margin-bottom: 16px;
    }
    .main-section h2 {
        font-size: 11pt;
        font-weight: 700;
        color: #1e3a5f;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid #1e3a5f;
        padding-bottom: 3px;
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
        font-size: 9.5pt;
        color: #1e3a5f;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .exp-desc {
        font-size: 9.5pt;
        color: #334155;
        line-height: 1.5;
    }
    .exp-desc ul {
        margin: 3px 0;
        padding-left: 16px;
    }
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
    .summary-text {
        font-size: 10pt;
        color: #334155;
        line-height: 1.6;
    }
    .premium-summary {
        background: #f4f6f9;
        background: linear-gradient(90deg, rgba(30,58,95,0.04), rgba(30,58,95,0.02));
        border-left: 4px solid #1e3a5f;
        border-left: 4px solid var(--primary-color, #1e3a5f);
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    .skill-tag { background: rgba(255,255,255,0.06); padding:4px 8px; border-radius:4px; display:inline-block; margin:2px 4px; font-size:9pt; }
</style>
<?= $this->include('candidate/resume/ai_replies_css') ?>
</head>
<body>

<div class="page">
    <div class="sidebar">
        <div class="name"><?= esc($resume->full_name ?? 'CANDIDATE NAME') ?></div>
        <?php if (!empty($resume->title) && $resume->title !== 'My Professional Resume'): ?>
        <div class="title-line"><?= esc($resume->title) ?></div>
        <?php else: ?>
        <div class="title-line">&nbsp;</div>
        <?php endif; ?>

        <div class="sidebar-section">
            <h3>Contact</h3>
            <?php if (!empty($resume->email)): ?>
            <div class="contact-item"><strong>Email</strong><br><?= esc($resume->email) ?></div>
            <?php endif; ?>
            <?php if (!empty($resume->phone)): ?>
            <div class="contact-item"><strong>Phone</strong><br><?= esc($resume->phone) ?></div>
            <?php endif; ?>
            <?php if (!empty($resume->location)): ?>
            <div class="contact-item"><strong>Location</strong><br><?= esc($resume->location) ?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($skills)): ?>
        <div class="sidebar-section">
            <h3>Skills</h3>
            <?php foreach ($skills as $skill): ?>
                <?php if (!empty($skill->skill_name)): ?>
                <span class="skill-tag"><?= esc($skill->skill_name) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="main">
        <?php if (!empty($resume->summary)): ?>
        <div class="main-section">
            <h2>Professional Summary</h2>
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
        <div class="main-section">
            <h2>Experience</h2>
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
        <div class="main-section">
            <h2>Education</h2>
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
    </div>
</div>

</body>
</html>
