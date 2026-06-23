<?php
// Initialize CodeIgniter framework
define('FCPATH', __DIR__ . '/../public/');
require __DIR__ . '/../app/Config/Paths.php';
$paths = new Config\Paths();
require __DIR__ . '/../vendor/codeigniter4/framework/system/bootstrap.php';

$employerModel = model(\App\Models\EmployerModel::class);
$verificationStatus = 'all';

$builder = $employerModel
    ->select([
        'employers.*',
        'MAX(states.name) AS state_name',
        'MAX(COALESCE(identities.secret, users.email)) AS email',
        'MAX(users.username) AS username',
        'GROUP_CONCAT(DISTINCT industries.name) AS industries',
        'COUNT(DISTINCT jobs.id) AS total_jobs'
    ])
    ->join('users', 'users.id = employers.user_id', 'left')
    ->join('auth_identities identities', 'identities.user_id = employers.user_id', 'left')
    ->join('states', 'states.id = employers.state_id', 'left')
    ->join('employer_industries', 'employer_industries.employer_id = employers.id', 'left')
    ->join('industries', 'industries.id = employer_industries.industry_id', 'left')
    ->join('jobs', 'jobs.employer_id = employers.id', 'left')
    ->groupBy('employers.id')
    ->orderBy('employers.created_at', 'DESC');

try {
    $results = $builder->findAll();
    echo "QUERY SUCCESSFUL!\n";
    echo "Results count: " . count($results) . "\n";
    print_r($results);
} catch (\Exception $e) {
    echo "QUERY FAILED!\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
