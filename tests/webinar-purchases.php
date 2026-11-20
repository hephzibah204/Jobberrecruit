<?php
require __DIR__ . '/_support/isolated-bootstrap.php';
require APPPATH . 'Helpers/webinar_helper.php';
set_exception_handler(static function (Throwable $e) { fwrite(STDERR, $e . "\n"); exit(1); });
function verifyWebinar($value, $message) { if (!$value) throw new RuntimeException($message); }
$db = \Config\Database::connect(config('Database')->tests, false);
$connections = new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances');
$connections->setValue(null, array_replace(\Config\Database::getConnections(), ['tests'=>$db, config('Database')->defaultGroup=>$db]));
foreach ([
    'CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, deleted_at TEXT)',
    'CREATE TABLE auth_identities (user_id INTEGER, type TEXT, secret TEXT)',
    'CREATE TABLE payments (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, plan_id INTEGER, reference TEXT UNIQUE, amount REAL, currency TEXT, status TEXT, metadata TEXT, paid_at TEXT, payment_method TEXT, amount_paid REAL, channel TEXT, gateway_response TEXT, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER UNIQUE, balance REAL, is_locked INTEGER DEFAULT 0, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE wallet_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, wallet_id INTEGER, type TEXT, amount REAL, reference TEXT UNIQUE, description TEXT)',
    'CREATE TABLE webinars (id INTEGER PRIMARY KEY, title TEXT, status TEXT, scheduled_at TEXT, access_type TEXT, price REAL, registrants_count INTEGER DEFAULT 0, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE webinar_registrations (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, webinar_id INTEGER, reminder_sent_24h INTEGER DEFAULT 0, reminder_sent_1h INTEGER DEFAULT 0, created_at TEXT)',
] as $sql) $db->query($sql);
$db->table('users')->insert(['id'=>7,'username'=>'Candidate']);
$db->table('users')->insert(['id'=>8,'username'=>'Other']);
$db->table('auth_identities')->insert(['user_id'=>7,'type'=>'email_password','secret'=>'owner@example.com']);
$db->table('wallets')->insert(['user_id'=>7,'balance'=>5000]);
for ($i=1;$i<=5;$i++) $db->table('webinars')->insert(['id'=>$i,'title'=>'Webinar '.$i,'status'=>'upcoming',
    'scheduled_at'=>date('Y-m-d H:i:s',time()+86400),'access_type'=>$i===5?'free':'paid','price'=>$i===5?0:1000]);
$service = new \App\Services\WebinarPurchaseService($db);
$service->register(7,1,true); $service->register(7,1,true);
verifyWebinar((float)$db->table('wallets')->where('user_id',7)->get()->getRow()->balance===4000.0,'Wallet charged more than once');
verifyWebinar($db->table('webinar_registrations')->where('webinar_id',1)->countAllResults()===1,'Duplicate registration');
verifyWebinar((int)$db->table('webinars')->where('id',1)->get()->getRow()->registrants_count===1,'Attendee count duplicated');
$db->query("CREATE TRIGGER reject_registration BEFORE INSERT ON webinar_registrations WHEN NEW.webinar_id=2 BEGIN SELECT RAISE(ABORT, 'injected registration failure'); END");
$failed=false; try {$service->register(7,2,true);} catch(Throwable $e) {$failed=true;}
verifyWebinar($failed,'Registration failure not reported');
verifyWebinar((float)$db->table('wallets')->where('user_id',7)->get()->getRow()->balance===4000.0,'Failed registration lost wallet money');
verifyWebinar($db->table('payments')->countAllResults()===1,'Failed registration left a payment');
$order=$service->createOrder(7,3);
$data=['reference'=>$order['reference'],'status'=>'success','currency'=>'NGN','amount'=>100000,
    'customer'=>['email'=>'owner@example.com'],'metadata'=>$order['metadata']];
$badPayloads=[];
foreach (['currency'=>'USD','amount'=>1,'status'=>'failed'] as $field=>$value) {$bad=$data;$bad[$field]=$value;$badPayloads[]=$bad;}
$bad=$data;$bad['customer']['email']='other@example.com';$badPayloads[]=$bad;
$bad=$data;$bad['metadata']['user_id']=8;$badPayloads[]=$bad;
$bad=$data;$bad['metadata']['webinar_id']=4;$badPayloads[]=$bad;
$bad=$data;$bad['metadata']['type']='course';$badPayloads[]=$bad;
$bad=$data;$bad['reference']='not-an-existing-order';$badPayloads[]=$bad;
foreach($badPayloads as $bad){$failed=false;try{$service->fulfill($bad,7,3);}catch(InvalidArgumentException $e){$failed=true;}verifyWebinar($failed,'Mismatched payment accepted');}
foreach([[8,3],[7,4]] as [$owner,$item]){$failed=false;try{$service->fulfill($data,$owner,$item);}catch(InvalidArgumentException $e){$failed=true;}verifyWebinar($failed,'Payment claimed for wrong account/item');}
$service->fulfill($data,7,3); $service->fulfill($data);
(new \App\Services\PaymentFulfillmentService($db))->fulfill($data,7,'webinar',3);
verifyWebinar($db->table('webinar_registrations')->where('webinar_id',3)->countAllResults()===1,'Callback/webhook replay duplicated seat');
verifyWebinar($db->table('payments')->where('reference',$order['reference'])->get()->getRow()->status==='paid','Payment not completed');
$shared=new \App\Services\PaymentFulfillmentService($db);
$fundReference=$shared->createPending(7,'wallet_funding',100);
$fundData=['reference'=>$fundReference,'status'=>'success','currency'=>'NGN','amount'=>10000,
    'customer'=>['email'=>'owner@example.com'],'metadata'=>['type'=>'wallet_funding','user_id'=>7]];
$shared->fulfill($fundData,7,'wallet_funding');$shared->fulfill($fundData,7,'wallet_funding');
verifyWebinar((float)$db->table('wallets')->where('user_id',7)->get()->getRow()->balance===4100.0,'Shared wallet funding changed or replayed');
$free=$service->register(7,5); verifyWebinar($free['created'],'Free registration failed');

// A failed payment write must also undo the newly inserted registration.
$order4=$service->createOrder(7,4);$data4=$data;$data4['reference']=$order4['reference'];$data4['metadata']=$order4['metadata'];
$db->query("CREATE TRIGGER reject_payment BEFORE UPDATE ON payments WHEN NEW.reference='".$order4['reference']."' BEGIN SELECT RAISE(ABORT, 'injected payment write failure'); END");
$failed=false;try{$service->fulfill($data4,7,4);}catch(Throwable $e){$failed=true;}
verifyWebinar($failed && $db->table('webinar_registrations')->where('webinar_id',4)->countAllResults()===0,'Failed payment write left confirmed access');

$now=new DateTimeImmutable('now',new DateTimeZone(config('App')->appTimezone));
$db->table('webinars')->where('id <',5)->update(['status'=>'completed']);
$db->table('webinars')->where('id',5)->update(['scheduled_at'=>$now->modify('+1 hour')->format('Y-m-d H:i:s')]);
class WebinarReminderStub extends \App\Services\EmailNotificationService {
    public bool $succeeds=false; public int $calls=0;
    public function __construct() {}
    public function sendWebinarReminderNotification($user,$webinar,string $timeFrame='24h'):bool {$this->calls++;return $this->succeeds;}
}
$stub=new WebinarReminderStub();$reminders=new \App\Services\WebinarReminderService($db,$stub);
verifyWebinar($reminders->run($now)===0,'Failed email counted as sent');
verifyWebinar((int)$db->table('webinar_registrations')->where('webinar_id',5)->get()->getRow()->reminder_sent_1h===0,'Failed reminder cannot retry');
$stub->succeeds=true;verifyWebinar($reminders->run($now)===1,'Reminder retry did not succeed');
$calls=$stub->calls;verifyWebinar($reminders->run($now)===0 && $calls===$stub->calls,'Successful reminder resent');

$event=(object)['title'=>'Interview Q&A','scheduled_at'=>'2026-11-03 12:00:00'];
parse_str(parse_url(webinar_calendar_url($event),PHP_URL_QUERY),$calendar);
verifyWebinar($calendar['dates']==='20261103T110000Z/20261103T123000Z','Calendar UTC conversion or duration wrong');
verifyWebinar(in_array('cv',webinar_topics('Build your CV for remote software jobs'),true),'Topic classification ignores content');
verifyWebinar(\App\Models\WebinarModel::displayStatus((object)['status'=>'upcoming','scheduled_at'=>'2020-01-01 12:00:00'])==='completed','Admin shows expired event upcoming');
class WebinarAccessAuth extends \CodeIgniter\Shield\Auth {
    public bool $signedIn=false;
    public function __construct() {}
    public function loggedIn():bool {return $this->signedIn;}
    public function user():?\CodeIgniter\Shield\Entities\User {return new \CodeIgniter\Shield\Entities\User(['id'=>8,'username'=>'Other']);}
}
$auth=new WebinarAccessAuth();\Config\Services::injectMock('auth',$auth);
$controller=(new ReflectionClass(\App\Controllers\NewsletterController::class))->newInstanceWithoutConstructor();
$request=new \CodeIgniter\HTTP\IncomingRequest(config('App'),new \CodeIgniter\HTTP\URI('http://example.com/training/webinars/registered?id=5'),'',new \CodeIgniter\HTTP\UserAgent());
$request->setGlobal('get',['id'=>5]);
foreach(['request'=>$request,'response'=>service('response'),'webinarModel'=>new \App\Models\WebinarModel($db),'registrationModel'=>new \App\Models\WebinarRegistrationModel($db)] as $property=>$value) (new ReflectionProperty($controller,$property))->setValue($controller,$value);
verifyWebinar(str_contains($controller->registered(5)->getHeaderLine('Location'),'/login'),'Anonymous visitor can access ticket');
$auth->signedIn=true;
verifyWebinar(str_contains($controller->registered(5)->getHeaderLine('Location'),'/training/webinars'),'Unregistered user can access ticket');
echo "PASS: wallet/registration rollback, duplicate protection, order ownership/item/amount/currency/payer checks, webhook replay, reminder retry, calendar UTC and admin expiry.\n";
