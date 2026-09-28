<?php
$skillLevels = [
    1 => ['name' => 'Beginner', 'pct' => '20%'],
    2 => ['name' => 'Elementary', 'pct' => '40%'],
    3 => ['name' => 'Intermediate', 'pct' => '60%'],
    4 => ['name' => 'Advanced', 'pct' => '80%'],
    5 => ['name' => 'Expert', 'pct' => '100%']
];
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --brand: #0861A9; --brand-dark: #064A85; --brand-deep: #0A2F57;
  --accent: #ED9020; --text: #1e293b; --muted: #64748b; --bg: #ffffff;
  --white: #ffffff; --border: #e2e8f0;
}
@page { size: A4; margin: 12mm 15mm; }
body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #fff; color: var(--text); font-size: 13.5px; line-height: 1.6; -webkit-font-smoothing: antialiased; }
h1,h2,h3 { font-family: 'Sora', sans-serif; }
a { color: var(--brand); text-decoration: none; }

.cv-doc {
  background: var(--white);
  padding: 34px 40px;
  color: #1e293b;
  max-width: 800px;
  margin: 0 auto;
}

.cv-header {
  padding-bottom: 16px;
  border-bottom: 2px solid var(--border);
  margin-bottom: 20px;
}
.cv-name {
  font-size: 29px;
  font-weight: 800;
  color: var(--brand-deep);
  letter-spacing: -.03em;
  margin-bottom: 4px;
}
.cv-headline {
  font-size: 14px;
  font-weight: 600;
  color: var(--brand);
  margin-bottom: 10px;
}
.cv-contact-row {
  display: flex;
  flex-wrap: wrap;
  gap: 6px 16px;
  font-size: 12.5px;
  color: var(--muted);
}
.cv-contact-item { display: inline-flex; align-items: center; }

.cv-section { margin-bottom: 20px; }
.cv-section:last-child { margin-bottom: 0; }
.cv-section-title {
  font-size: 11.5px;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--muted);
  border-bottom: 1.5px solid var(--border);
  padding-bottom: 5px;
  margin-bottom: 12px;
}

.cv-entry { margin-bottom: 14px; }
.cv-entry:last-child { margin-bottom: 0; }
.cv-entry-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2px; }
.cv-entry-title { font-size: 14px; font-weight: 700; color: #0f172a; }
.cv-entry-dates { font-size: 12px; color: var(--muted); font-weight: 500; white-space: nowrap; }
.cv-entry-sub { font-size: 13px; font-weight: 600; color: var(--brand-deep); margin-bottom: 4px; }
.cv-entry-body { font-size: 13px; color: #334155; line-height: 1.6; }
.cv-entry-body ul { margin-left: 17px; margin-top: 4px; }
.cv-entry-body li { margin-bottom: 3px; }
.cv-entry-body ul li::marker { color: var(--brand); }

.cv-summary { font-size: 13px; line-height: 1.7; color: #334155; }

.cv-skills-grid { display: flex; flex-wrap: wrap; gap: 6px 8px; }
.cv-skill-tag {
  font-size: 12px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 4px;
  background: #f1f5f9;
  border: 1px solid var(--border);
  color: #1e293b;
}

.cv-lang-list { font-size: 13px; color: #334155; line-height: 1.8; }
.cv-cert-item { margin-bottom: 6px; font-size: 13px; }
.cv-footer {
  text-align: center;
  padding-top: 20px;
  margin-top: 24px;
  border-top: 1px solid var(--border);
  font-size: 11px;
  color: var(--muted);
}
</style>
</head>
<body>
  <article class="cv-doc" role="main">
    <header class="cv-header">
      <h1 class="cv-name"><?= esc($resume->full_name ?? 'Candidate Name') ?></h1>
      <?php if (!empty($resume->title) && $resume->title !== 'My Professional Resume'): ?>
        <p class="cv-headline"><?= esc($resume->title) ?></p>
      <?php endif; ?>

      <div class="cv-contact-row">
        <?php if (!empty($resume->phone)): ?>
          <span class="cv-contact-item"><?= esc($resume->phone) ?></span>
        <?php endif; ?>
        <?php if (!empty($resume->email)): ?>
          <span class="cv-contact-item"><?= esc($resume->email) ?></span>
        <?php endif; ?>
        <?php if (!empty($resume->location)): ?>
          <span class="cv-contact-item"><?= esc($resume->location) ?></span>
        <?php endif; ?>
        <?php if (!empty($resume->linkedin)): ?>
          <span class="cv-contact-item"><?= esc(preg_replace('/^https?:\/\/(www\.)?/', '', $resume->linkedin)) ?></span>
        <?php endif; ?>
      </div>
    </header>

    <div class="cv-body">
      <?php if (!empty($resume->summary)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Professional Summary</h2>
          <div class="cv-summary">
            <?php 
              $summary = $resume->summary;
              if (strip_tags($summary) === $summary) {
                  echo nl2br(esc($summary));
              } else {
                  echo strip_tags($summary, '<p><br><strong><em><ul><ol><li>');
              }
            ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($experiences)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Work Experience</h2>
          <?php foreach ($experiences as $exp): ?>
            <?php
              $pos = is_object($exp) ? ($exp->position ?? $exp->job_title ?? '') : ($exp['position'] ?? $exp['job_title'] ?? '');
              $comp = is_object($exp) ? ($exp->company ?? '') : ($exp['company'] ?? '');
              $start = is_object($exp) ? ($exp->start_date ?? '') : ($exp['start_date'] ?? '');
              $end = is_object($exp) ? ($exp->end_date ?? '') : ($exp['end_date'] ?? '');
              $isCur = is_object($exp) ? (!empty($exp->is_current)) : (!empty($exp['is_current']));
              $desc = is_object($exp) ? ($exp->description ?? '') : ($exp['description'] ?? '');

              $dateStr = '';
              if ($start) $dateStr .= date('M Y', strtotime($start));
              if ($isCur) {
                $dateStr .= ' – Present';
              } elseif ($end) {
                $dateStr .= ' – ' . date('M Y', strtotime($end));
              }
            ?>
            <div class="cv-entry">
              <div class="cv-entry-header">
                <span class="cv-entry-title"><?= esc($pos ?: 'Position') ?></span>
                <span class="cv-entry-dates"><?= esc($dateStr) ?></span>
              </div>
              <?php if ($comp): ?>
                <p class="cv-entry-sub"><?= esc($comp) ?></p>
              <?php endif; ?>
              <?php if (!empty($desc)): ?>
                <div class="cv-entry-body">
                  <?php 
                    if (strip_tags($desc) === $desc) {
                        $lines = array_filter(array_map('trim', explode("\n", $desc)));
                        if (count($lines) > 0) {
                            echo "<ul>";
                            foreach ($lines as $line) echo "<li>" . esc($line) . "</li>";
                            echo "</ul>";
                        }
                    } else {
                        echo strip_tags($desc, '<p><br><strong><em><ul><ol><li>');
                    }
                  ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </section>
      <?php endif; ?>

      <?php if (!empty($education)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Education</h2>
          <?php foreach ($education as $edu): ?>
            <?php
              $deg = is_object($edu) ? ($edu->degree ?? '') : ($edu['degree'] ?? '');
              $field = is_object($edu) ? ($edu->field_of_study ?? $edu->field ?? '') : ($edu['field_of_study'] ?? $edu['field'] ?? '');
              $inst = is_object($edu) ? ($edu->institution ?? $edu->school ?? '') : ($edu['institution'] ?? $edu['school'] ?? '');
              $grad = is_object($edu) ? ($edu->graduation_year ?? $edu->graduation_date ?? $edu->year ?? '') : ($edu['graduation_year'] ?? $edu['graduation_date'] ?? $edu['year'] ?? '');
            ?>
            <div class="cv-entry">
              <div class="cv-entry-header">
                <span class="cv-entry-title"><?= esc($deg ?: 'Degree') ?><?= !empty($field) ? ' in ' . esc($field) : '' ?></span>
                <span class="cv-entry-dates"><?= esc($grad) ?></span>
              </div>
              <?php if ($inst): ?>
                <p class="cv-entry-sub"><?= esc($inst) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </section>
      <?php endif; ?>

      <?php if (!empty($skills)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Skills &amp; Qualifications</h2>
          <div class="cv-skills-grid">
            <?php foreach ($skills as $skill): ?>
              <?php
                $sName = is_object($skill) ? ($skill->skill_name ?? $skill->name ?? '') : (is_array($skill) ? ($skill['skill_name'] ?? $skill['name'] ?? '') : (string)$skill);
                if (empty($sName)) continue;
              ?>
              <span class="cv-skill-tag"><?= esc($sName) ?></span>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($resume->languages)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Languages</h2>
          <div class="cv-lang-list">
            <?= esc(is_array($resume->languages) ? implode(', ', $resume->languages) : $resume->languages) ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($resume->certs)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Certifications</h2>
          <?php 
              $certsArr = is_array($resume->certs) ? $resume->certs : explode("\n", $resume->certs);
              foreach ($certsArr as $c): 
                  $c = trim($c);
                  if (empty($c)) continue;
          ?>
            <div class="cv-cert-item">
              <strong><?= esc($c) ?></strong>
            </div>
          <?php endforeach; ?>
        </section>
      <?php endif; ?>
    </div>

    <div class="cv-footer">
      JobberRecruit &bull; Professional Resume &bull; jobberrecruit.com
    </div>
  </article>
</body>
</html>