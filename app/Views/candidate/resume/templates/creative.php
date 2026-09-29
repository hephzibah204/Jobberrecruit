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
  --brand: #9333ea; --brand-dark: #7e22ce; --brand-deep: #581c87;
  --brand-light: #f5f3ff; --accent: #ec4899; --accent-dark: #db2777;
  --text: #1e1b4b; --muted: #6b7280; --bg: #ffffff;
  --white: #ffffff; --border: #ede9fe;
  --radius: 12px; --shadow-lg: 0 14px 40px rgba(147,51,234,.12); --transition: .18s ease;
}
@page { size: A4; margin: 12mm 15mm; }
body { padding-bottom: 0; font-family: 'Inter', system-ui, -apple-system, sans-serif; background: var(--bg); color: var(--text); font-size: 13.5px; line-height: 1.6; -webkit-font-smoothing: antialiased; }
h1,h2,h3 { font-family: 'Sora', sans-serif; letter-spacing: -.02em; }
a { color: var(--brand); text-decoration: none; }
img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }

.cv-doc {
  position: relative;
  background: var(--white);
  border-radius: 12px;
  box-shadow: var(--shadow-lg);
  overflow: hidden;
  font-family: 'Inter', sans-serif;
  font-size: 13.5px;
  line-height: 1.65;
  color: #1e1b4b;
  border-top: 6px solid var(--brand);
}
.cv-doc::before {
  content: '';
  position: absolute;
  top: -6px;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(90deg, var(--brand), var(--accent));
}

.cv-header {
  background: linear-gradient(135deg, #faf5ff 0%, #ffffff 100%);
  padding: 34px 40px 22px;
  border-bottom: 1px solid var(--border);
}
.cv-name {
  font-family: 'Sora', sans-serif;
  font-size: 30px;
  font-weight: 800;
  background: linear-gradient(135deg, var(--brand-deep), var(--brand));
  -webkit-background-clip: text;
  -webkit-text-fill-color: var(--brand-deep);
  letter-spacing: -.03em;
  line-height: 1.1;
  margin-bottom: 6px;
}
.cv-headline {
  font-size: 14px;
  font-weight: 600;
  color: var(--accent);
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: .06em;
}
.cv-contact-row {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px 16px;
  font-size: 12.5px;
  color: var(--muted);
}
.cv-contact-item {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}
.cv-contact-item a { color: var(--muted); }
.cv-contact-item a:hover { color: var(--brand); }

.cv-body { padding: 26px 40px 28px; }

.cv-section { margin-bottom: 24px; }
.cv-section:last-child { margin-bottom: 0; }
.cv-section-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'Sora', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--brand-deep);
  padding-bottom: 6px;
  border-bottom: 1.5px solid var(--border);
  margin-bottom: 12px;
}
.cv-section-title::before {
  content: '';
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--brand), var(--accent));
  flex-shrink: 0;
}

.cv-entry { margin-bottom: 15px; }
.cv-entry:last-child { margin-bottom: 0; }
.cv-entry-header { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin-bottom: 2px; }
.cv-entry-title { font-size: 14px; font-weight: 700; color: #1e1b4b; }
.cv-entry-dates { font-size: 12px; color: var(--muted); font-weight: 500; white-space: nowrap; }
.cv-entry-sub { font-size: 13px; font-weight: 600; color: var(--brand); margin-bottom: 4px; }
.cv-entry-body { font-size: 13px; color: #374151; line-height: 1.65; }
.cv-entry-body ul { margin-left: 18px; margin-top: 4px; }
.cv-entry-body li { margin-bottom: 3px; }

.cv-summary { font-size: 13px; line-height: 1.75; color: #374151; }

.cv-skills-grid { display: flex; flex-wrap: wrap; gap: 6px 8px; }
.cv-skill-pill {
  font-size: 12px;
  font-weight: 600;
  padding: 4px 11px;
  border-radius: 20px;
  border: 1px solid #ddd6fe;
  background: var(--brand-light);
  color: var(--brand-deep);
}

.cv-lang-list { font-size: 13px; color: var(--text); line-height: 1.8; }
.cv-cert-item { margin-bottom: 8px; }
.cv-cert-item:last-child { margin-bottom: 0; }
.cv-cert-name { font-size: 13px; font-weight: 700; color: #1e1b4b; }
.cv-cert-org { font-size: 12px; color: var(--muted); }

.cv-footer {
  background: linear-gradient(135deg, var(--brand-deep), #3b0764);
  color: rgba(255,255,255,0.85);
  padding: 12px 40px;
  font-size: 11px;
  display: flex;
  justify-content: space-between;
  align-items: center;
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
          <h2 class="cv-section-title">Creative Profile</h2>
          <div class="cv-summary">
            <?php 
              $summary = $resume->summary;
              if (strip_tags($summary) === $summary) {
                  echo nl2br(esc($summary));
              } else {
                  $allowed = '<p><br><strong><em><ul><ol><li><h3><h4><div><span>';
                  echo strip_tags($summary, $allowed);
              }
            ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($experiences)): ?>
        <section class="cv-section">
          <h2 class="cv-section-title">Experience &amp; Projects</h2>
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
          <h2 class="cv-section-title">Core Skills &amp; Tools</h2>
          <div class="cv-skills-grid">
            <?php foreach ($skills as $skill): ?>
              <?php
                $sName = is_object($skill) ? ($skill->skill_name ?? $skill->name ?? '') : (is_array($skill) ? ($skill['skill_name'] ?? $skill['name'] ?? '') : (string)$skill);
                if (empty($sName)) continue;
              ?>
              <span class="cv-skill-pill"><?= esc($sName) ?></span>
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
          <h2 class="cv-section-title">Certifications &amp; Awards</h2>
          <?php 
              $certsArr = is_array($resume->certs) ? $resume->certs : explode("\n", $resume->certs);
              foreach ($certsArr as $c): 
                  $c = trim($c);
                  if (empty($c)) continue;
          ?>
            <div class="cv-cert-item">
              <div class="cv-cert-name"><?= esc($c) ?></div>
            </div>
          <?php endforeach; ?>
        </section>
      <?php endif; ?>
    </div>

    <div class="cv-footer">
      <span><strong>JobberRecruit</strong> &bull; Creative Resume</span>
      <span>jobberrecruit.com</span>
    </div>
  </article>
</body>
</html>