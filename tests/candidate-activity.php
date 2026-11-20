<?php
// Test the production activity expression against isolated in-memory records.
namespace CodeIgniter { class Model {} }
namespace {
    require __DIR__ . '/../app/Models/JobSeekerModel.php';
    $sql = (new ReflectionClass(\App\Models\JobSeekerModel::class))->getConstant('ACTIVITY_SQL');
    $db = new SQLite3(':memory:');
    $db->exec('CREATE TABLE users (id INTEGER, last_active TEXT, created_at TEXT)');
    $db->exec('CREATE TABLE job_seekers (id INTEGER, user_id INTEGER, updated_at TEXT)');
    $db->exec('CREATE TABLE job_applications (job_seeker_id INTEGER, created_at TEXT)');
    $db->exec("INSERT INTO users VALUES (1,'2026-10-15','2025-01-01'),(2,'2026-01-01','2025-01-01'),(3,NULL,'2026-10-10'),(4,NULL,NULL)");
    $db->exec("INSERT INTO job_seekers VALUES (1,1,'2026-01-01'),(2,2,'2026-10-16'),(3,3,'2026-10-16'),(4,4,'2026-10-16')");
    $db->exec("INSERT INTO job_applications VALUES (1,'2026-02-01'),(2,'2026-10-16'),(2,'2026-03-01')");
    $query = "SELECT job_seekers.id, $sql AS last_activity_at FROM job_seekers LEFT JOIN users ON users.id=job_seekers.user_id LEFT JOIN job_applications ON job_applications.job_seeker_id=job_seekers.id GROUP BY job_seekers.id";
    $rows = $db->query($query . ' ORDER BY last_activity_at DESC');
    $actual = [];
    while ($row = $rows->fetchArray(SQLITE3_ASSOC)) $actual[$row['id']] = $row['last_activity_at'];
    if ($actual !== [2 => '2026-10-16', 1 => '2026-10-15', 3 => '2026-10-10', 4 => null]) throw new RuntimeException('Activity ordering failed');
    $rows = $db->query($query . " HAVING last_activity_at >= '2026-10-14' ORDER BY last_activity_at DESC");
    $ids = [];
    while ($row = $rows->fetchArray(SQLITE3_ASSOC)) $ids[] = $row['id'];
    if ($ids !== [2, 1]) throw new RuntimeException('Activity filter failed');
    echo "Passed: recent login, latest application, registration fallback, unknown activity, ordering and date filtering.\n";
}
