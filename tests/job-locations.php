<?php
require_once dirname(__DIR__) . '/app/Services/NigerianJobLocations.php';
require_once dirname(__DIR__) . '/app/Services/RemoteJobFilter.php';
use App\Services\NigerianJobLocations;
use App\Services\RemoteJobFilter;

$names = ['Remote', 'Nigerian', 'Niger', 'Lagos', ' Lagos State ', 'Abuja', 'FCT', 'Federal Capital Territory', 'Akwa Ibom', 'Cross River'];
$records = array_map(static fn ($id, $name) => (object) ['id' => $id, 'name' => $name, 'url' => 'http://homepage.test/jobs?state_id=' . $id, 'job_count' => 2], range(1, count($names)), $names);
$filtered = NigerianJobLocations::filter($records);
if (array_column($filtered, 'name') !== ['Niger', 'Lagos', 'Abuja', 'Akwa Ibom', 'Cross River']) throw new RuntimeException('Invalid location or duplicate geographic option.');
if ($records[0]->name !== 'Remote' || $records[1]->name !== 'Nigerian') throw new RuntimeException('Filtering must not mutate records.');
if (NigerianJobLocations::key('Nigerian') !== null || NigerianJobLocations::key('Niger') !== 'niger') throw new RuntimeException('Nigerian must never be mapped to Niger.');

class LocationQuerySpy
{
    public array $calls = [];
    public function __call($method, $arguments) { $this->calls[] = [$method, $arguments]; return $this; }
}
foreach ([['location_type'], ['work_arrangement'], ['location_type', 'work_arrangement'], []] as $columns) {
    $db = new class($columns) {
        public function __construct(private array $columns) {}
        public function fieldExists($column, $table) { return $table === 'jobs' && in_array($column, $this->columns, true); }
    };
    $query = new LocationQuerySpy();
    RemoteJobFilter::apply($query, $db);
    $calls = json_encode($query->calls);
    foreach ($columns as $column) if (!str_contains($calls, 'jobs.' . $column)) throw new RuntimeException('Remote filter ignored available structured field.');
    if ($columns && str_contains($calls, 'jobs.title')) throw new RuntimeException('Keyword matches must not override structured remote fields.');
    if (!$columns && !str_contains($calls, 'jobs.title')) throw new RuntimeException('Legacy schema fallback missing.');
}
$GLOBALS['homepageTestLocations'] = $records;
ob_start();
require __DIR__ . '/homepage-render.php';
$html = ob_get_clean();
preg_match_all('/class="loc-name">([^<]+)</', $html, $cards);
if (count(array_keys($cards[1], 'Remote', true)) !== 1) throw new RuntimeException('Homepage must show exactly one Remote card.');
if (in_array('Nigerian', $cards[1], true)) throw new RuntimeException('Invalid location displayed.');
if (!str_contains($html, 'jobs/remote')) throw new RuntimeException('Remote card must open the remote landing page.');
echo "PASS: geographic validation/deduplication, Niger preservation, one Remote card, no record mutations, and all remote-schema variants.\n";
