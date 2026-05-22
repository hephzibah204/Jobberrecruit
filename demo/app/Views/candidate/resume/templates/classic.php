<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 25mm 20mm;
        }
        body { 
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; 
            color: #2d3748; 
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb; 
        }
        .name { 
            font-size: 26pt; 
            font-weight: 700; 
            color: #1e40af; 
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0 0 8px 0;
        }
        .contact { 
            font-size: 10pt; 
            color: #64748b;
            margin: 0;
        }
        .section-title { 
            font-size: 13pt; 
            font-weight: 700; 
            color: #1e40af; 
            border-bottom: 2px solid #e2e8f0; 
            margin-top: 22px; 
            margin-bottom: 12px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 4px;
        }
        .item-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: baseline;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .item-sub { 
            color: #64748b; 
            font-style: italic; 
            margin-bottom: 6px;
            font-size: 10.5pt;
        }
        .description { 
            font-size: 10.5pt; 
            text-align: left;
            margin-bottom: 12px;
        }
        .skills-list { 
            display: flex; 
            flex-wrap: wrap;
            gap: 6px;
        }
        .skill-item { 
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            padding: 4px 10px; 
            border-radius: 4px; 
            font-size: 10pt;
            font-weight: 500;
            border: 1px solid #93c5fd;
        }
        .date-range {
            font-size: 10pt;
            color: #64748b;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name"><?= esc($resume->full_name ?? 'CANDIDATE NAME') ?></div>
        <div class="contact">
            <?php if (!empty($resume->email)): ?><?= esc($resume->email) ?><?php endif; ?>
            <?php if (!empty($resume->phone)): ?> | <?= esc($resume->phone) ?><?php endif; ?>
            <?php if (!empty($resume->location)): ?> | <?= esc($resume->location) ?><?php endif; ?>
        </div>
    </div>

    <?php if (!empty($resume->summary)): ?>
    <div class="section-title">Professional Summary</div>
    <div class="description"><?= nl2br(esc($resume->summary)) ?></div>
    <?php endif; ?>

    <?php if (!empty($experiences)): ?>
    <div class="section-title">Work Experience</div>
    <?php foreach ($experiences as $exp): ?>
    <div style="margin-bottom: 16px;">
        <div class="item-header">
            <span><?= esc($exp->position ?? 'Position') ?></span>
            <span class="date-range"><?= date('M Y', strtotime($exp->start_date ?? 'now')) ?> - <?= !empty($exp->is_current) ? 'Present' : (!empty($exp->end_date) ? date('M Y', strtotime($exp->end_date)) : '') ?></span>
        </div>
        <div class="item-sub"><?= esc($exp->company ?? 'Company') ?></div>
        <?php if (!empty($exp->description)): ?>
        <div class="description"><?= nl2br(esc($exp->description)) ?></div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($education)): ?>
    <div class="section-title">Education</div>
    <?php foreach ($education as $edu): ?>
    <div style="margin-bottom: 12px;">
        <div class="item-header">
            <span><?= esc($edu->institution ?? 'Institution') ?></span>
            <span class="date-range"><?= !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '' ?></span>
        </div>
        <div class="item-sub"><?= esc($edu->degree ?? 'Degree') ?> in <?= esc($edu->field_of_study ?? 'Field') ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($skills)): ?>
    <div class="section-title">Skills</div>
    <div class="skills-list">
        <?php foreach ($skills as $skill): ?>
            <?php if (!empty($skill->skill_name)): ?>
            <span class="skill-item"><?= esc($skill->skill_name) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</body>
</html>
