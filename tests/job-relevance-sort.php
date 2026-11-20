<?php
require dirname(__DIR__) . '/app/Services/JobRelevanceSort.php';
class RelevanceQuerySpy
{
    public array $orders = [];
    public function orderBy($expression, $direction, $escape = true) { $this->orders[] = [$expression, $direction]; return $this; }
}
$db = new class {
    public function escape($value) { return "'" . str_replace("'", "''", $value) . "'"; }
    public function escapeLikeString($value) { return strtr($value, ['!' => '!!', '%' => '!%', '_' => '!_']); }
};
$query = new RelevanceQuerySpy();
\App\Services\JobRelevanceSort::apply($query, $db, 'Engineer');
if (count($query->orders) !== 3 || !str_contains($query->orders[0][0], 'jobs.skills')) throw new RuntimeException('Relevance ranking is missing.');
$empty = new RelevanceQuerySpy();
\App\Services\JobRelevanceSort::apply($empty, $db, '');
if ($empty->orders !== [['jobs.created_at', 'DESC'], ['jobs.id', 'DESC']]) throw new RuntimeException('No-keyword fallback must be deterministic newest first.');
if (!in_array('sqlite', PDO::getAvailableDrivers(), true)) throw new RuntimeException('SQLite driver required for relevance SQL verification.');
$sql = new PDO('sqlite::memory:');
$sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql->exec('CREATE TABLE jobs (id INTEGER, title TEXT, skills TEXT, description TEXT, created_at TEXT)');
$insert = $sql->prepare('INSERT INTO jobs VALUES (?, ?, ?, ?, ?)');
foreach ([[1, 'Engineer', '', ''], [2, 'Engineer II', '', ''], [3, 'Senior Engineer', '', ''], [4, 'Developer', 'Engineer', ''], [5, 'Manager', '', 'Engineer']] as $row) $insert->execute([...$row, '2026-10-19']);
$order = implode(', ', array_map(static fn($item) => $item[0] . ' ' . $item[1], $query->orders));
$ids = $sql->query('SELECT id FROM jobs ORDER BY ' . $order)->fetchAll(PDO::FETCH_COLUMN);
if (array_map('intval', $ids) !== [1, 2, 3, 4, 5]) throw new RuntimeException('Incorrect relevance ranking.');
$unsafe = new RelevanceQuerySpy();
\App\Services\JobRelevanceSort::apply($unsafe, $db, "' OR 1=1 -- %_");
$order = implode(', ', array_map(static fn($item) => $item[0] . ' ' . $item[1], $unsafe->orders));
if (count($sql->query('SELECT id FROM jobs ORDER BY ' . $order)->fetchAll()) !== 5) throw new RuntimeException('Search text altered SQL structure.');
echo "PASS: relevance ranking, deterministic empty-search fallback and SQL quoting/wildcard safety.\n";
