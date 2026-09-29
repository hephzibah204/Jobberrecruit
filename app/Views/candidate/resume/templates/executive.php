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
  --brand: #0A2F57; --brand-dark: #061d36; --brand-deep: #05182d;
  --accent: #ED9020; --text: #1a202c; --muted: #4a5568; --bg: #ffffff;
  --sidebar-bg: #f4f7fb; --border: #e2e8f0;
}
@page { size: A4; margin: 0; }
body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #fff; color: var(--text); font-size: 13.5px; line-height: 1.6; -webkit-font-smoothing: antialiased; }
h1,h2,h3 { font-family: 'Sora', sans-serif; }
a { color: var(--brand); text-decoration: none; }

.cv-doc {
  display: grid;
  grid-template-columns: 32% 68%;
  background: #fff;
  min-height: 100vh;
  box-sizing: border-box;
}

.cv-header {
  grid-column: 1 / -1;
  padding: 34px 38px 20px;
  background: #fff;
  border-bottom: 3.5px solid var(--brand);
}
.cv-name {
  font-size: 30px;
  font-weight: 800;
  color: var(--brand-deep);
  letter-spacing: -.03em;
  margin-bottom: 4px;
}
.cv-headline {
  font-size: 14px;
  font-weight: 700;
  color: var(--accent);
  text-transform: uppercase;
  letter-spacing: .1em;
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

.exec-sidebar {
  background: var(--sidebar-bg);
  padding: 24px 20px;
  border-right: 1px solid var(--border);
}
.exec-main {
  padding: 24px 28px;
}

.cv-sec { margin-bottom: 22px; }
.cv-sec:last-child { margin-bottom: 0; }
.cv-sec-title {
  font-family: 'Sora', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--brand);
  border-left: 3px solid var(--brand);
  padding-left: 8px;
  margin-bottom: 12px;
}

.exec-sidebar .cv-sec-title {
  color: var(--brand-deep);
  border-left-color: var(--accent);
}

.cv-entry { margin-bottom: 14px; }
.cv-entry:last-child { margin-bottom: 0; }
.cv-entry-header { display: flex; justify-content: space-between; align-items: baseline; gap: 8px; margin-bottom: 2px; }
.cv-entry-title { font-size: 13.5px; font-weight: 700; color: #111; }
.cv-entry-dates { font-size: 11.5px; color: var(--muted); font-weight: 600; white-space: nowrap; }
.cv-entry-sub { font-size: 12.5px; font-weight: 600; color: var(--brand); margin-bottom: 4px; }
.cv-entry-body { font-size: 12.8px; color: #2d3748; line-height: 1.6; }
.cv-entry-body ul { margin-left: 17px; margin-top: 4px; }
.cv-entry-body li { margin-bottom: 3px; }

.cv-summary { font-size: 13px; line-height: 1.7; color: #2d3748; }

.exec-skills { display: flex; flex-direction: column; gap: 6px; }
.exec-skill-item {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 5px 10px;
  font-size: 12px;
  font-weight: 600;
  color: var(--brand-deep);
}

.side-item { margin-bottom: 10px; font-size: 12.5px; }
.side-item strong { display: block; color: var(--text); }
.side-item span { color: var(--muted); font-size: 11.5px; }

.cv-footer {
  grid-column: 1 / -1;
  background: var(--brand-deep);
  color: rgba(255,255,255,0.85);
  padding: 10px 38px;
  font-size: 11px;
  display: flex;
  justify-content: space-between;
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

    <!-- Sidebar Column -->
    <aside class="exec-sidebar">
      <?php if (!empty($skills)): ?>
        <div class="cv-sec">
          <h2 class="cv-sec-title">Core Skills</h2>
          <div class="exec-skills">
            <?php foreach ($skills as $skill): ?>
              <?php
                $sName = is_object($skill) ? ($skill->skill_name ?? $skill->name ?? '') : (is_array($skill) ? ($skill['skill_name'] ?? $skill['name'] ?? '') : (string)$skill);
                if (empty($sName)) continue;
              ?>
              <div class="exec-skill-item"><?= esc($sName) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!empty($resume->languages)): ?>
        <div class="cv-sec">
          <h2 class="cv-sec-title">Languages</h2>
          <p style="font-size:12.5px;color:var(--text);line-height:1.6;">
            <?= esc(is_array($resume->languages) ? implode(', ', $resume->languages) : $resume->languages) ?>
          </p>
        </div>
      <?php endif; ?>

      <?php if (!empty($resume->certs)): ?>
        <div class="cv-sec">
          <h2 class="cv-sec-title">Certifications</h2>
          <?php 
              $certsArr = is_array($resume->certs) ? $resume->certs : explode("\n", $resume->certs);
              foreach ($certsArr as $c): 
                  $c = trim($c);
                  if (empty($c)) continue;
          ?>
            <div class="side-item">
              <strong><?= esc($c) ?></strong>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </aside>

    <!-- Main Content Column -->
    <main class="exec-main">
      <?php if (!empty($resume->summary)): ?>
        <section class="cv-sec">
          <h2 class="cv-sec-title">Executive Summary</h2>
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
        <section class="cv-sec">
          <h2 class="cv-sec-title">Experience &amp; Leadership</h2>
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
        <section class="cv-sec">
          <h2 class="cv-sec-title">Education</h2>
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
    </main>

    <footer class="cv-footer">
      <span><strong>JobberRecruit</strong> &bull; Executive Portfolio Resume</span>
      <span>jobberrecruit.com</span>
    </footer>
  </article>
</body>
</html>