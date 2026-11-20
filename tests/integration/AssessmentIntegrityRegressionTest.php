<?php

use App\Controllers\AptitudeController;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use App\Models\TestAttemptModel;
use App\Models\TestModel;
use CodeIgniter\Shield\Auth;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

/** Security regressions for candidate assessment access and grading. */
final class AssessmentIntegrityRegressionTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    protected $namespace = null;
    protected $refresh = true;
    protected $migrateOnce = false;
    private int $userId;
    private array $previousConnections;
    private $isolatedConnection;

    protected function setUp(): void
    {
        // Controller methods use db_connect(), so register a fresh in-memory
        // connection for this fixture and restore the previous one afterwards.
        $this->previousConnections = \Config\Database::getConnections();
        $this->isolatedConnection = \Config\Database::connect(config('Database')->tests, false);
        $connections = new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances');
        $connections->setValue(null, array_replace($this->previousConnections, ['tests' => $this->isolatedConnection]));
        Services::resetSingle('migrations');
        parent::setUp();
        $db = db_connect();
        $db->table('users')->insert(['username' => 'audit-probe', 'active' => 1, 'user_type' => 'job_seeker']);
        $this->userId = (int) $db->insertID();
        $user = new \CodeIgniter\Shield\Entities\User(['id' => $this->userId, 'user_type' => 'job_seeker']);
        $id = $this->userId;
        $auth = $this->getMockBuilder(Auth::class)->disableOriginalConstructor()->onlyMethods(['user', '__call'])->getMock();
        $auth->method('user')->willReturn($user);
        $auth->method('__call')->willReturnCallback(static fn ($method, $args) => match ($method) {
            'id' => $id, 'loggedIn' => true, default => null,
        });
        Services::injectMock('auth', $auth);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Services::resetSingle('migrations');
        $this->isolatedConnection->close();
        (new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances'))->setValue(null, $this->previousConnections);
    }

    private function controller(array $post): AptitudeController
    {
        Services::resetSingle('response');
        $request = service('request');
        $request->setGlobal('post', $post);
        $request->setMethod('POST');
        $controller = new AptitudeController();
        $controller->initController($request, service('response'), service('logger'));
        return $controller;
    }

    private function fixture(bool $active = true): array
    {
        db_connect()->table('job_categories')->insert(['name' => 'Audit Category', 'slug' => 'audit-category']);
        $categoryId = (int) db_connect()->insertID();
        $testId = (new TestModel())->insert(['title' => 'Audit probe', 'slug' => 'audit-probe',
            'category_id' => $categoryId, 'duration_mins' => 10, 'num_questions' => 2, 'pass_threshold' => 60, 'is_active' => $active ? 1 : 0]);
        $ids = [];
        for ($i = 0; $i < 3; $i++) {
            $ids[] = (new QuestionModel())->insert(['test_id' => $testId, 'type' => 'mcq',
                'body' => 'Probe question ' . $i, 'is_active' => 1]);
        }
        $optionId = (new QuestionOptionModel())->insert(['question_id' => $ids[2], 'body' => 'Correct only for question 3', 'is_correct' => 1]);
        return [$testId, $ids, $optionId];
    }

    public function testForeignOptionsAndUnassignedQuestionsAreRejectedAndCannotInflateScores(): void
    {
        [$testId, $questions, $optionId] = $this->fixture();
        $attemptId = (new TestAttemptModel())->insert(['candidate_id' => $this->userId, 'test_id' => $testId,
            'mode' => 'practice', 'status' => 'in_progress', 'started_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', time() + 600), 'question_ids' => json_encode(array_slice($questions, 0, 2))]);
        foreach ($questions as $questionId) {
            $result = $this->controller(['question_id' => $questionId, 'option_ids' => [$optionId]])->saveAnswer($attemptId);
            $this->assertSame(422, $result->getStatusCode());
            // Also simulate a malformed record saved before the fix.
            (new \App\Models\AttemptAnswerModel())->insert(['attempt_id' => $attemptId, 'question_id' => $questionId, 'selected_option_ids' => json_encode([$optionId])]);
        }
        $this->controller(['tab_switches' => 0])->submitTest($attemptId);
        $row = (new TestAttemptModel())->find($attemptId);
        $this->assertSame(0.0, (float) $row['score_pct']);
        $this->assertSame(0, (int) $row['num_correct']);
        $this->assertSame(2, (int) $row['num_total']);
    }

    public function testSpoofedEmployerRequiredFlagIsNowRejected(): void
    {
        [$testId] = $this->fixture(false);
        $post = ['test_id' => $testId, 'mode' => 'official', 'employer_required' => 1];
        $first = json_decode($this->controller($post)->startAttempt()->getBody(), true);
        $second = json_decode($this->controller($post)->startAttempt()->getBody(), true);
        $this->assertSame('error', $first['status']);
        $this->assertSame('error', $second['status']);
    }

    public function testPracticeStartsWhenOptionalReferralColumnIsMissing(): void
    {
        [$testId] = $this->fixture();
        $db = db_connect();
        if ($db->fieldExists('referrer_id', 'test_attempts')) {
            \Config\Database::forge($db)->dropColumn('test_attempts', 'referrer_id');
            $db->resetDataCache();
        }
        $response = $this->controller([])->practice('audit-probe');
        $this->assertSame(302, $response->getStatusCode());
        $attempt = (new TestAttemptModel())->where('test_id', $testId)->first();
        $this->assertNotNull($attempt);
        $this->assertSame($this->userId, (int) $attempt['candidate_id']);
        $this->assertStringContainsString('/aptitude/test/' . $attempt['id'], $response->getHeaderLine('Location'));
    }

    public function testValidAssignedAnswersReceiveTheCorrectScoreAndCannotBeSubmittedTwice(): void
    {
        [$testId, $questions] = $this->fixture();
        $attemptId = (new TestAttemptModel())->insert(['candidate_id' => $this->userId, 'test_id' => $testId,
            'mode' => 'practice', 'status' => 'in_progress', 'started_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', time() + 600), 'question_ids' => json_encode(array_slice($questions, 0, 2))]);
        foreach (array_slice($questions, 0, 2) as $questionId) {
            $optionId = (new QuestionOptionModel())->insert(['question_id' => $questionId, 'body' => 'Correct', 'is_correct' => 1]);
            $response = $this->controller(['question_id' => $questionId, 'option_ids' => [$optionId]])->saveAnswer($attemptId);
            $this->assertSame('success', json_decode($response->getBody(), true)['status']);
        }
        $this->controller([])->submitTest($attemptId);
        $this->assertSame(100.0, (float) (new TestAttemptModel())->find($attemptId)['score_pct']);
        $this->assertSame(403, $this->controller([])->submitTest($attemptId)->getStatusCode());
    }
    public function testInvitationUserIdCannotMatchAnotherUsersProfileId(): void
    {
        $model = new \App\Models\AptitudeTestInvitationModel();
        $invitation = (object) ['candidate_id' => 42, 'email' => 'owner@example.com', 'application_id' => null];
        $this->assertTrue($model->belongsToUser($invitation, 42, 'owner@example.com'));
        $this->assertFalse($model->belongsToUser($invitation, 99, 'other@example.com'));
        $this->assertFalse($model->belongsToUser($invitation, 42, 'other@example.com'));
    }

    public function testPrivateTestCannotBeStartedThroughGeneralApi(): void
    {
        [$testId] = $this->fixture();
        db_connect()->table('employers')->insert(['user_id' => $this->userId, 'company_name' => 'Assessment Owner']);
        $employerId = (int) db_connect()->insertID();
        (new TestModel())->update($testId, ['employer_id' => $employerId]);
        $result = $this->controller(['test_id' => $testId, 'mode' => 'practice'])->startAttempt();
        $this->assertSame(404, $result->getStatusCode());
    }

    public function testGatewayLifecycleFieldsSurviveCreateCancelAndReactivate(): void
    {
        $planId = (new \App\Models\PlanModel())->insert(['code' => 'audit_plan', 'name' => 'Audit subscription',
            'base_price' => 1000, 'plan_type' => 'subscription', 'billing_type' => 'subscription', 'monthly_job_credits' => 0, 'is_active' => 1]);
        $subscriptions = new \App\Models\UserSubscriptionModel();
        $id = $subscriptions->insert(['user_id' => $this->userId, 'plan_id' => $planId,
            'starts_at' => date('Y-m-d H:i:s'), 'ends_at' => date('Y-m-d H:i:s', time() + 86400),
            'is_active' => 1, 'subscription_code' => 'SUB_audit', 'email_token' => 'token_audit',
            'authorization' => '{"authorization_code":"AUTH_test"}', 'will_not_renew' => 0]);
        $this->assertSame('SUB_audit', $subscriptions->find($id)['subscription_code']);
        $this->assertSame('token_audit', $subscriptions->find($id)['email_token']);
        $subscriptions->update($id, ['will_not_renew' => 1]);
        $this->assertSame(1, (int) $subscriptions->find($id)['will_not_renew']);
        $subscriptions->update($id, ['will_not_renew' => 0]);
        $this->assertSame(0, (int) $subscriptions->find($id)['will_not_renew']);
    }
}
