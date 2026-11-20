<?php
namespace CodeIgniter {
    class Model {
        public array $calls = [];
        public function __call($name, $arguments) {
            $this->calls[] = [$name, $arguments];
            return $this;
        }
    }
}
namespace {
require dirname(__DIR__) . '/app/Models/EmployerModel.php';
function check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
foreach (['', '   ', null, ['invalid']] as $term) {
    $query = new App\Models\EmployerModel();
    check($query->applyAdminSearch($term) === $query && !$query->calls, 'Empty/invalid searches must leave filters unchanged');
}
foreach ([' Acme ', 'owner@example.test', '08012345678', "O'Reilly_%", '42'] as $term) {
    $query = new App\Models\EmployerModel();
    $query->applyAdminSearch($term);
    check($query->calls[0][0] === 'groupStart' && end($query->calls)[0] === 'groupEnd', 'Search must group OR matches to preserve verification/industry restrictions');
    $likes = array_values(array_filter($query->calls, fn ($call) => in_array($call[0], ['like', 'orLike'], true)));
    check(array_column(array_column($likes, 1), 0) === ['employers.company_name', 'employers.contact_name', 'employers.contact_email', 'employers.contact_phone', 'users.username', 'auth_identities.secret'], 'Missing searchable employer field');
    foreach ($likes as $call) check($call[1][1] === trim($term) && count($call[1]) === 2, 'Search must pass literal input to default escaped LIKE matching');
    $ids = array_values(array_filter($query->calls, fn ($call) => $call[0] === 'orWhere'));
    check(count($ids) === (ctype_digit(trim($term)) ? 1 : 0), 'Only numeric searches should match employer IDs');
}
$query = new App\Models\EmployerModel();
$query->applyAdminSearch(str_repeat('a', 300));
check(strlen($query->calls[1][1][1]) === 200, 'Search input must be bounded');
$source = file_get_contents(dirname(__DIR__) . '/app/Controllers/AdminController.php');
$start = strpos($source, 'public function employers()');
$end = strpos($source, '// Add verification action methods', $start);
$listings = substr($source, $start, $end - $start);
check(substr_count($listings, '->applyAdminSearch(') === 2, 'Both initial and AJAX listings must search before pagination');
echo "PASS: search fields, literal input, grouped filters, numeric IDs, invalid/empty input, and both listing endpoints.\n";
}