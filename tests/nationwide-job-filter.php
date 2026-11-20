<?php
require dirname(__DIR__) . '/app/Services/NationwideJobFilter.php';

class NationwideQuerySpy
{
    public array $calls = [];
    public function __call($method, $arguments)
    {
        $this->calls[] = [$method, $arguments];
        return $this;
    }
}
$query = new NationwideQuerySpy();
App\Services\NationwideJobFilter::apply($query);
$methods = array_column($query->calls, 0);
if ($methods !== ['groupStart', 'whereIn', 'orLike', 'groupEnd']) {
    throw new RuntimeException('Nationwide predicates must remain grouped.');
}
if ($query->calls[1][1][0] !== 'jobs.state_id' || !is_callable($query->calls[1][1][1])) {
    throw new RuntimeException('Nationwide matching must use a state ID subquery.');
}
if ($query->calls[2][1] !== ['jobs.title', 'nationwide']) {
    throw new RuntimeException('Nationwide title fallback is missing.');
}
$source = file_get_contents(dirname(__DIR__) . '/app/Controllers/Home.php');
if (str_contains($source, "'jobs.location'") || substr_count($source, 'NationwideJobFilter::apply(') !== 5) {
    throw new RuntimeException('All five nationwide queries must use the shared filter.');
}
echo "PASS: all five nationwide queries use grouped state ID and title matching without jobs.location.\n";