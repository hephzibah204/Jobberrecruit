<?php
require __DIR__ . '/_support/isolated-bootstrap.php';
set_exception_handler(static function (Throwable $e) { fwrite(STDERR, $e . "\n"); exit(1); });
function checkWebinar($condition, $message) { if (!$condition) throw new RuntimeException($message); }

$db = \Config\Database::connect(config('Database')->tests, false);
$db->query('CREATE TABLE webinars (id INTEGER PRIMARY KEY, title TEXT, scheduled_at TEXT, status TEXT)');
$now = new DateTimeImmutable('2026-11-03 12:00:00', new DateTimeZone('Africa/Lagos'));
$rows = [
    ['id'=>1,'title'=>'Expired but still marked upcoming','scheduled_at'=>'2026-11-02 12:00:00','status'=>'upcoming'],
    ['id'=>2,'title'=>'Starting now','scheduled_at'=>'2026-11-03 12:00:00','status'=>'upcoming'],
    ['id'=>3,'title'=>'Next session','scheduled_at'=>'2026-11-03 12:00:01','status'=>'upcoming'],
    ['id'=>4,'title'=>'Cancelled future','scheduled_at'=>'2026-11-04 12:00:00','status'=>'cancelled'],
    ['id'=>5,'title'=>'Completed future','scheduled_at'=>'2026-11-04 12:00:00','status'=>'completed'],
    ['id'=>6,'title'=>'Later session','scheduled_at'=>'2026-11-05 12:00:00','status'=>'upcoming'],
];
foreach ($rows as $row) $db->table('webinars')->insert($row);
$model = new \App\Models\WebinarModel($db);
checkWebinar(array_column($model->upcoming($now)->findAll(), 'id') === [3,6], 'Upcoming list includes expired/closed events or is out of order');
checkWebinar((int) $model->upcoming($now)->first()->id === 3, 'Featured event is expired');
foreach ($rows as $row) checkWebinar($model::registrationOpen((object) $row, $now) === in_array($row['id'], [3,6], true), 'Registration boundary incorrect for ' . $row['id']);
checkWebinar(!$model::registrationOpen((object) ['status'=>'upcoming','scheduled_at'=>'2026-02-30 12:00:00'], $now), 'Invalid schedule accepted');
$utcNow = $now->setTimezone(new DateTimeZone('UTC'));
checkWebinar($model::inProgress((object) $rows[1], $now), 'Registered attendee cannot join at start');
checkWebinar($model::inProgress((object) $rows[1], $now->modify('+89 minutes')), 'Ticket expires during session');
checkWebinar(!$model::inProgress((object) $rows[1], $now->modify('+90 minutes')), 'Finished ticket still current');
checkWebinar(!$model::inProgress((object) $rows[0], $now), 'Expired event still in progress');
checkWebinar(array_column($model->upcoming($utcNow)->findAll(), 'id') === [3,6], 'Timezone conversion changed eligibility');

class WebinarTestAuth extends \CodeIgniter\Shield\Auth {
    public function __construct() {}
    public function loggedIn(): bool { return true; }
    public function user(): ?\CodeIgniter\Shield\Entities\User {return new \CodeIgniter\Shield\Entities\User(['id'=>7]);}
}
\Config\Services::injectMock('auth', new WebinarTestAuth());
$controller = (new ReflectionClass(\App\Controllers\NewsletterController::class))->newInstanceWithoutConstructor();
(new ReflectionProperty($controller, 'webinarModel'))->setValue($controller, $model);
(new ReflectionProperty($controller, 'response'))->setValue($controller, service('response'));
(new ReflectionProperty($controller, 'registrationModel'))->setValue($controller, new class { public function where(...$args) {return $this;} public function first() {return null;} });
$request = new \CodeIgniter\HTTP\IncomingRequest(config('App'), new \CodeIgniter\HTTP\URI('http://example.com/webinars/register/1'), '', new \CodeIgniter\HTTP\UserAgent());
$request->setGlobal('post', []);
(new ReflectionProperty($controller, 'request'))->setValue($controller, $request);
// This must stop before looking up registrations or doing any payment work.
$response = $controller->registerWebinar(1);
checkWebinar($response->getStatusCode() === 409, 'Expired event registration was not rejected');
echo "PASS: expired/current/future dates, cancelled/completed events, featured order, timezone conversion, invalid dates and direct registration rejection.\n";
