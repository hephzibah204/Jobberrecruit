<?php
use App\Controllers\JobSeekerController;
use App\Models\JobSeekerModel;
use CodeIgniter\Shield\Auth;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

final class ProfileHistoryRegressionTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    protected $namespace = null;
    protected $refresh = true;
    protected $migrateOnce = false;
    private $connection;
    private array $previousConnections;
    private int $candidateId;
    protected function setUp(): void
    {
        $this->previousConnections = \Config\Database::getConnections();
        $this->connection = \Config\Database::connect(config('Database')->tests, false);
        (new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances'))->setValue(null, array_replace($this->previousConnections, ['tests'=>$this->connection]));
        Services::resetSingle('migrations');
        parent::setUp();
        db_connect()->table('users')->insert(['id'=>91,'username'=>'profile-audit','active'=>1,'user_type'=>'job_seeker']);
        $user = new \CodeIgniter\Shield\Entities\User(['id'=>91,'username'=>'profile-audit','user_type'=>'job_seeker']);
        $auth = $this->getMockBuilder(Auth::class)->disableOriginalConstructor()->onlyMethods(['user','__call'])->getMock();
        $auth->method('user')->willReturn($user);
        $auth->method('__call')->willReturnCallback(static fn($method,$args)=>match($method){'id'=>91,'loggedIn'=>true,default=>null});
        Services::injectMock('auth',$auth);
        db_connect()->table('job_categories')->insert(['id'=>910,'name'=>'Audit category','slug'=>'audit-category']);
        $this->candidateId = (int)(new JobSeekerModel())->insert(['user_id'=>91,'full_name'=>'Profile Tester']);
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        Services::resetSingle('migrations');
        $this->connection->close();
        (new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances'))->setValue(null,$this->previousConnections);
    }
    private function save(array $data)
    {
        Services::resetSingle('response');
        $request = service('request');
        $request->setMethod('POST');
        $request->setGlobal('post',array_merge(['full_name'=>'Profile Tester'],$data));
        $request->setGlobal('request',array_merge(['full_name'=>'Profile Tester'],$data));
        $controller = new JobSeekerController();
        $controller->initController($request,service('response'),service('logger'));
        return $controller->edit_profile();
    }
    public function testEducationAndNamedMonthCertificatesPersistTogether(): void
    {
        $response = $this->save([
            'edu_degree'=>['B.Sc.'],'edu_field'=>['QA Test Study'],'edu_school'=>['QA Test University'],
            'edu_start_year'=>['2020'],'edu_end_year'=>['2024'],
            'cert_name'=>['QA Test Certification'],'cert_org'=>['QA Test Institute'],'cert_id'=>['QATEST-001'],
            'cert_issue_month'=>['June'],'cert_issue_year'=>['2025'],'cert_no_expire'=>['1'],
        ]);
        $this->assertSame('success', json_decode($response->getBody(),true)['status'], $response->getBody());
        $education = (new \App\Models\JobSeekerEducationModel())->forSeeker($this->candidateId);
        $certs = (new \App\Models\JobSeekerCertificationModel())->forSeeker($this->candidateId);
        $this->assertCount(1,$education);
        $this->assertSame('QA Test University',$education[0]->school);
        $this->assertCount(1,$certs);
        $this->assertSame('June',$certs[0]->issue_month);
        $this->assertSame(1,(int)$certs[0]->does_not_expire);
        $profile=(new JobSeekerModel())->find($this->candidateId);
        $this->assertSame($profile->getProfileCompletion(),(int)$profile->profile_completion);
    }
    public function testInvalidCertificateDoesNotDeletePreviouslySavedEducation(): void
    {
        (new \App\Models\JobSeekerEducationModel())->insert(['job_seeker_id'=>$this->candidateId,'degree'=>'Original degree','school'=>'Original school']);
        $response=$this->save(['edu_degree'=>['Replacement'],'cert_name'=>['Invalid certificate'],'cert_issue_month'=>['WrongMonth'],'cert_issue_year'=>['2025']]);
        $this->assertSame(422,$response->getStatusCode());
        $rows=(new \App\Models\JobSeekerEducationModel())->forSeeker($this->candidateId);
        $this->assertCount(1,$rows);
        $this->assertSame('Original degree',$rows[0]->degree);
    }
    private function alert(array $data)
    {
        Services::resetSingle('response');
        $request=service('request');
        $request->setMethod('POST');
        $request->setGlobal('post',$data);
        $request->setGlobal('request',$data);
        $controller=new JobSeekerController();
        $controller->initController($request,service('response'),service('logger'));
        return $controller->saveAlert();
    }
    public function testExistingAlertCanChangeFrequencyWithoutCreatingDuplicate(): void
    {
        $model=new \App\Models\JobAlertModel();
        $id=$model->insert(['job_seeker_id'=>$this->candidateId,'keyword'=>'Sales Manager','frequency'=>'daily','delivery_time'=>'08:00','channel'=>'email']);
        $response=$this->alert(['id'=>$id,'keyword'=>'Sales Manager','frequency'=>'weekly','delivery_time'=>'09:30','channel'=>'both']);
        $this->assertTrue(json_decode($response->getBody(),true)['success'],$response->getBody());
        $this->assertSame('weekly',$model->find($id)->frequency);
        $response=$this->alert(['id'=>$id,'keyword'=>'Sales Manager','frequency'=>'instant','delivery_time'=>'09:30','channel'=>'email']);
        $this->assertTrue(json_decode($response->getBody(),true)['success']);
        $this->assertSame('instant',$model->find($id)->frequency);
        $this->assertSame(1,$model->countAllResults());
    }
    public function testAlertUpdateRejectsAnotherCandidatesIdAndInvalidFields(): void
    {
        db_connect()->table('users')->insert(['id'=>92,'username'=>'other-profile','active'=>1,'user_type'=>'job_seeker']);
        $other=(new JobSeekerModel())->insert(['user_id'=>92,'full_name'=>'Other User']);
        $model=new \App\Models\JobAlertModel();
        $id=$model->insert(['job_seeker_id'=>$other,'keyword'=>'Original','frequency'=>'daily','delivery_time'=>'08:00','channel'=>'email']);
        $response=$this->alert(['id'=>$id,'keyword'=>'Tampered','frequency'=>'weekly','delivery_time'=>'08:00','channel'=>'email']);
        $this->assertSame(404,$response->getStatusCode());
        $this->assertSame('Original',$model->find($id)->keyword);
        $response=$this->alert(['keyword'=>'Bad','frequency'=>'every-minute','delivery_time'=>'99:99','channel'=>'sms']);
        $this->assertSame(422,$response->getStatusCode());
        $this->assertSame(1,$model->countAllResults());
    }
    public function testNewAccountCanOpenDashboardAndOnlyLiveJobsAreRecommended(): void
    {
        $db=db_connect();
        $db->connID->createFunction('DAYOFWEEK',static fn($date)=>(int)date('w',strtotime($date))+1);
        (new JobSeekerModel())->delete($this->candidateId);
        $db->table('countries')->insert(['id'=>910,'name'=>'Audit country','iso_code'=>'ZZ']);
        $db->table('states')->insert(['country_id'=>910,'name'=>'Audit Location']);$state=(int)$db->insertID();
        $db->table('users')->insert(['id'=>92,'username'=>'audit-employer','active'=>1,'user_type'=>'employer']);
        $db->table('employers')->insert(['user_id'=>92,'company_name'=>'Audit Employer']);$employer=(int)$db->insertID();
        foreach (['live','expired','closed','unapproved'] as $type) {
            $db->table('jobs')->insert(['category_id'=>910,'description'=>'Audit job description','employer_id'=>$employer,'state_id'=>$state,'title'=>'Job '.$type,'slug'=>'job-'.$type,
                'status'=>$type==='closed'?'closed':'open','admin_status'=>$type==='unapproved'?'pending':'approved',
                'application_deadline'=>date('Y-m-d',time()+($type==='expired'?-86400:86400)), 'created_at'=>date('Y-m-d H:i:s')]);
        }
        $captured=[];
        $renderer=$this->getMockBuilder(\CodeIgniter\View\View::class)->disableOriginalConstructor()->onlyMethods(['setData','render'])->getMock();
        $renderer->method('setData')->willReturnCallback(function($data)use(&$captured,$renderer){$captured=$data;return $renderer;});
        $renderer->method('render')->willReturn('dashboard-rendered');
        Services::injectMock('renderer',$renderer);
        try {
            $request=service('request');$request->setMethod('GET');
            $controller=new JobSeekerController();$controller->initController($request,service('response'),service('logger'));
            $this->assertSame('dashboard-rendered',$controller->dashboard());
            $this->assertSame(0,(int)$captured['candidate']->id);
            $this->assertSame(0,$captured['profileCompletion']);
            $this->assertNotEmpty($captured['profileChecklist']);
            $this->assertCount(1,$captured['recommendedJobs']);
            $this->assertSame('Job live',$captured['recommendedJobs'][0]->title);
            $this->assertSame('Audit Location',$captured['recommendedJobs'][0]->location);
        } finally {Services::resetSingle('renderer');}
    }
    public function testCandidateNotificationsUseProfileIdAndPendingInvitationsIncludeApplicationRecipients(): void
    {
        $db=db_connect();
        $db->table('auth_identities')->insert(['user_id'=>91,'type'=>'email_password','secret'=>'audit@example.test']);
        $notifications=new \App\Models\CandidateNotificationModel();
        $notifications->createNotification($this->candidateId,'application_viewed','Audit title','Audit message');
        $this->assertSame(1,$notifications->getUnreadCount($this->candidateId));
        $this->assertSame('Audit title',$notifications->getNotifications($this->candidateId,4)[0]['title']);
        $db->table('users')->insert(['id'=>92,'username'=>'audit-employer','active'=>1,'user_type'=>'employer']);
        $db->table('employers')->insert(['user_id'=>92,'company_name'=>'Audit Employer']);$employer=(int)$db->insertID();
        $db->table('jobs')->insert(['category_id'=>910,'description'=>'Audit description','employer_id'=>$employer,'title'=>'Audit role','slug'=>'audit-role','status'=>'open','admin_status'=>'approved']);$job=(int)$db->insertID();
        $db->table('job_applications')->insert(['job_seeker_id'=>$this->candidateId,'job_id'=>$job,'status'=>'pending']);$application=(int)$db->insertID();
        $test=(new \App\Models\TestModel())->insert(['category_id'=>910,'title'=>'Audit test','slug'=>'audit-test','duration_mins'=>10,'num_questions'=>1,'pass_threshold'=>70,'is_active'=>1]);
        $invites=new \App\Models\AptitudeTestInvitationModel();
        foreach (['pending','completed','expired'] as $status) $invites->insert(['test_id'=>$test,'employer_id'=>$employer,'job_id'=>$job,'application_id'=>$application,'candidate_id'=>0,'status'=>$status,'invitation_code'=>'audit-'.$status]);
        $this->assertSame(1,$invites->countPendingForCandidate(91));
        $this->assertSame('pending',$invites->getByCode('audit-pending')->status);
        $this->assertFalse($invites::isPending((object)['status'=>'pending','due_date'=>date('Y-m-d H:i:s',time()-60)]));
        $this->assertFalse($invites::isPending((object)['status'=>'pending','attempt_status'=>'submitted']));
    }
}
