<?php

use App\Services\CourseAssessmentService;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

final class CourseAssessmentRegressionTest extends CIUnitTestCase
{
    private $connection;
    private CourseAssessmentService $service;
    private array $bank;
    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = Database::connect(config('Database')->tests, false);
        foreach ([
            'CREATE TABLE users (id INTEGER PRIMARY KEY)',
            'CREATE TABLE courses (id INTEGER PRIMARY KEY, test_questions TEXT, updated_at TEXT)',
            'CREATE TABLE course_enrollments (id INTEGER PRIMARY KEY, user_id INTEGER, course_id INTEGER, status TEXT, test_attempts INTEGER DEFAULT 0, test_score REAL, completed_at TEXT, progress INTEGER)',
            'CREATE TABLE course_assessment_submissions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, course_id INTEGER, enrollment_id INTEGER, request_key TEXT, questions TEXT, answers TEXT, score REAL, created_at TEXT, UNIQUE(user_id,course_id,request_key))',
            'CREATE TABLE course_certificates (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, course_id INTEGER, enrollment_id INTEGER, certificate_code TEXT, issued_at TEXT, created_at TEXT, updated_at TEXT)',
        ] as $sql) $this->connection->query($sql);
        $this->bank = [
            ['question' => 'Question one', 'options' => [['text' => 'A', 'is_correct' => 1], ['text' => 'B', 'is_correct' => 0]]],
            ['question' => 'Question two', 'options' => [['text' => 'A', 'is_correct' => 0], ['text' => 'B', 'is_correct' => 1]]],
        ];
        $this->connection->table('users')->insert(['id' => 7]);
        $this->connection->table('courses')->insert(['id' => 3, 'test_questions' => json_encode($this->bank)]);
        $this->connection->table('course_enrollments')->insert(['id' => 1, 'user_id' => 7, 'course_id' => 3, 'status' => 'enrolled']);
        $this->service = new CourseAssessmentService($this->connection);
    }
    protected function tearDown(): void { $this->connection->close(); parent::tearDown(); }
    private function submit(array $answers, string $key): array
    {
        return $this->service->complete(7, 3, $answers, CourseAssessmentService::version($this->bank), $key);
    }
    public function testPassingAnswersIssueOneCertificateAndReplayDoesNotConsumeAnotherAttempt(): void
    {
        $first = $this->submit([0, 1], 'same_submission_1234');
        $second = $this->submit([0, 1], 'same_submission_1234');
        $this->assertTrue($first['success']);
        $this->assertSame(100.0, $first['score']);
        $this->assertSame($first, $second);
        $this->assertSame(1, $this->connection->table('course_certificates')->countAllResults());
        $this->assertSame(1, $this->connection->table('course_assessment_submissions')->countAllResults());
    }
    public function testAdministratorQuestionsPersistAndCanBePassed(): void
    {
        $this->connection->table('courses')->where('id',3)->update(['test_questions'=>null]);
        $this->service->saveQuestions(3,$this->bank);
        $stored=CourseAssessmentService::questions((new \App\Models\CourseModel($this->connection))->find(3));
        $this->assertSame($this->bank,$stored);
        $result=$this->service->complete(7,3,[0,1],CourseAssessmentService::version($stored),'published_bank_12345');
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['certificate_id']);
    }
    public function testMalformedBankCannotReplacePublishedQuestions(): void
    {
        $invalid=$this->bank;
        $invalid[0]['options'][1]['is_correct']=1;
        try {
            $this->service->saveQuestions(3,$invalid);
            $this->fail('Invalid bank accepted');
        } catch (InvalidArgumentException $e) {
            $stored=CourseAssessmentService::questions((new \App\Models\CourseModel($this->connection))->find(3));
            $this->assertSame($this->bank,$stored);
        }
    }
    public function testUnpublishedAssessmentCannotIssueCertificateOrConsumeAttempts(): void
    {
        $this->connection->table('courses')->where('id',3)->update(['test_questions'=>null]);
        try {
            $this->service->complete(7,3,[],CourseAssessmentService::version([]),'unpublished_1234567');
            $this->fail('Unpublished assessment accepted');
        } catch (InvalidArgumentException $e) {
            $this->assertSame(0,$this->connection->table('course_certificates')->countAllResults());
            $this->assertSame(0,(int)$this->connection->table('course_enrollments')->get()->getRow()->test_attempts);
        }
    }
    public function testPostedScoreWithoutAnswersCannotIssueCertificate(): void
    {
        try {
            $this->submit([], 'forged_score_12345');
            $this->fail('Expected invalid assessment.');
        } catch (InvalidArgumentException $e) {
            $this->assertSame(0, $this->connection->table('course_certificates')->countAllResults());
            $this->assertSame(0, $this->connection->table('course_assessment_submissions')->countAllResults());
        }
    }
    public function testThreeFailedAttemptsCannotBeBypassedWithAFourthPass(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $result = $this->submit([1, 0], 'failed_submission_' . $i);
            $this->assertFalse($result['success']);
        }
        $this->expectException(InvalidArgumentException::class);
        $this->submit([0, 1], 'fourth_submission_1');
    }
    public function testQuestionBankVersionMustMatch(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->complete(7, 3, [0, 1], 'wrong-version', 'stale_submission_1');
    }
}
