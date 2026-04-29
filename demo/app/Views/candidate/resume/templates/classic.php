<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; }
        .name { font-size: 24px; font-weight: bold; color: #0d6efd; text-transform: uppercase; }
        .contact { font-size: 12px; color: #666; }
        .section-title { font-size: 16px; font-weight: bold; color: #0d6efd; border-bottom: 1px solid #eee; margin-top: 20px; margin-bottom: 10px; text-transform: uppercase; }
        .item-header { display: flex; justify-content: space-between; font-weight: bold; }
        .item-sub { color: #666; font-style: italic; margin-bottom: 5px; }
        .description { font-size: 13px; text-align: justify; }
        .skills-list { display: flex; flex-wrap: wrap; }
        .skill-item { background: #f0f0f0; padding: 3px 8px; border-radius: 4px; margin-right: 5px; margin-bottom: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="name"><?= esc($resume->full_name ?? 'CANDIDATE NAME') ?></div>
        <div class="contact">
            <?= esc($resume->email ?? '') ?> | <?= esc($resume->phone ?? '') ?> | <?= esc($resume->location ?? '') ?>
        </div>
    </div>

    <div class="section-title">Professional Summary</div>
    <div class="description"><?= nl2br(esc($resume->summary)) ?></div>

    <?php if (!empty($experiences)): ?>
    <div class="section-title">Work Experience</div>
    <?php foreach ($experiences as $exp): ?>
    <div style="margin-bottom: 15px;">
        <div class="item-header">
            <span><?= esc($exp->position) ?></span>
            <span style="float: right;"><?= date('M Y', strtotime($exp->start_date)) ?> - <?= $exp->is_current ? 'Present' : date('M Y', strtotime($exp->end_date)) ?></span>
        </div>
        <div class="item-sub"><?= esc($exp->company) ?></div>
        <div class="description"><?= nl2br(esc($exp->description)) ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($education)): ?>
    <div class="section-title">Education</div>
    <?php foreach ($education as $edu): ?>
    <div style="margin-bottom: 10px;">
        <div class="item-header">
            <span><?= esc($edu->institution) ?></span>
            <span style="float: right;"><?= $edu->graduation_date ? date('Y', strtotime($edu->graduation_date)) : '' ?></span>
        </div>
        <div class="item-sub"><?= esc($edu->degree) ?> in <?= esc($edu->field_of_study) ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($skills)): ?>
    <div class="section-title">Skills</div>
    <div class="skills-list">
        <?php foreach ($skills as $skill): ?>
            <span class="skill-item"><?= esc($skill->skill_name) ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</body>
</html>
