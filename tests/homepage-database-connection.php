<?php
// Source regression guard for the live undefined Home::$db crash.
// Does not require a database or a bootable local application.
$source = file_get_contents(dirname(__DIR__) . '/app/Controllers/Home.php');
if (str_contains($source, '$this->db')) {
    throw new RuntimeException('Home must not access an undeclared database property.');
}
$remoteFilter = file_get_contents(dirname(__DIR__) . '/app/Services/RemoteJobFilter.php');
if (!str_contains($source, 'RemoteJobFilter::apply($remoteJobsQuery, db_connect())') || !str_contains($source, 'RemoteJobFilter::apply($query, db_connect())')) {
    throw new RuntimeException('Homepage and job-search must share the remote filter using the initialized database service.');
}
if (!str_contains($remoteFilter, "->like('jobs.title', 'remote')")) {
    throw new RuntimeException('Legacy-schema remote-search fallback must remain available.');
}
echo "PASS: homepage and job-search checks use the database service, with the legacy-schema fallback preserved.\n";
