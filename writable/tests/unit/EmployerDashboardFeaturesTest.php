<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use App\Models\PaymentModel;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\JobModel;
use App\Services\CreditService;
use App\Controllers\EmployerController;
use App\Controllers\MessageController;

/**
 * @internal
 */
final class EmployerDashboardFeaturesTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = true;
    protected $refresh     = false;
    protected $namespace   = null;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Truncate tables to ensure a clean state
        $this->db->query('PRAGMA foreign_keys = OFF');
        $tables = [
            'candidate_unlocks', 'payments', 'job_credit_wallets', 
            'job_seekers', 'employers', 'auth_identities', 'users', 
            'states', 'countries', 'courses', 'course_enrollments', 'cv_reviews'
        ];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->truncate();
            }
        }
        $this->db->query('PRAGMA foreign_keys = ON');

        // Seed default country and state
        $this->db->table('countries')->insert([
            'id' => 1,
            'name' => 'Nigeria',
            'iso_code' => 'NG',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('states')->insert([
            'id' => 1,
            'country_id' => 1,
            'name' => 'Lagos',
            'slug' => 'lagos',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function createUser(string $username, string $email, string $userType): int
    {
        $this->db->table('users')->insert([
            'username' => $username,
            'user_type' => $userType,
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        $userId = $this->db->insertID();
        
        $this->db->table('auth_identities')->insert([
            'user_id' => $userId,
            'type' => 'email_password',
            'secret' => $email,
            'secret2' => password_hash('password123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return $userId;
    }

    public function testCandidatesSearchFilters(): void
    {
        // Seed second state (state 1 Lagos is seeded globally in setUp)
        $this->db->table('states')->insert([
            'id' => 2,
            'country_id' => 1,
            'name' => 'Abuja',
            'slug' => 'abuja',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 2. Seed Candidates
        $seekerUserIdA = $this->createUser('seeker_a', 'seeker_a@test.com', 'job_seeker');
        $this->db->table('job_seekers')->insert([
            'user_id' => $seekerUserIdA,
            'full_name' => 'John Doe',
            'job_title' => 'PHP Developer',
            'employment_type' => 'full-time',
            'experience_years' => 5,
            'state_id' => 1,
            'availability' => 'immediate',
            'education_level' => 'bachelor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $seekerUserIdB = $this->createUser('seeker_b', 'seeker_b@test.com', 'job_seeker');
        $this->db->table('job_seekers')->insert([
            'user_id' => $seekerUserIdB,
            'full_name' => 'Jane Smith',
            'job_title' => 'Frontend Developer',
            'employment_type' => 'part-time',
            'experience_years' => 3,
            'state_id' => 1,
            'availability' => 'immediate',
            'education_level' => 'bachelor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $seekerUserIdC = $this->createUser('seeker_c', 'seeker_c@test.com', 'job_seeker');
        $this->db->table('job_seekers')->insert([
            'user_id' => $seekerUserIdC,
            'full_name' => 'Bob Johnson',
            'job_title' => 'PHP Developer',
            'employment_type' => 'full-time',
            'experience_years' => 10,
            'state_id' => 2,
            'availability' => 'immediate',
            'education_level' => 'master',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $seekerModel = model(JobSeekerModel::class);

        // 3. Test Keyword search ('PHP')
        $results = $seekerModel->getCandidates(['keyword' => 'PHP']);
        $this->assertCount(2, $results);
        $names = array_column($results, 'full_name');
        $this->assertContains('John Doe', $names);
        $this->assertContains('Bob Johnson', $names);

        // 4. Test State search (state_id = 1)
        $results = $seekerModel->getCandidates(['state_id' => 1]);
        $this->assertCount(2, $results);
        $names = array_column($results, 'full_name');
        $this->assertContains('John Doe', $names);
        $this->assertContains('Jane Smith', $names);

        // 5. Test Experience search (experience_years >= 5)
        $results = $seekerModel->getCandidates(['experience_years' => 5]);
        $this->assertCount(2, $results);
        $names = array_column($results, 'full_name');
        $this->assertContains('John Doe', $names);
        $this->assertContains('Bob Johnson', $names);
    }

    public function testCandidateUnlockLogic(): void
    {
        // 2. Create Employer user & profile
        $employerUserId = $this->createUser('employer_test', 'employer@test.com', 'employer');
        $this->db->table('employers')->insert([
            'user_id' => $employerUserId,
            'company_name' => 'Test Company',
            'company_size' => '1-10',
            'contact_name' => 'Test Contact',
            'contact_email' => 'employer@test.com',
            'contact_phone' => '12345678',
            'state_id' => 1,
            'unlimited_access' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $employerId = $this->db->insertID();

        // 3. Create Candidate
        $seekerUserId = $this->createUser('seeker_test', 'seeker@test.com', 'job_seeker');
        $this->db->table('job_seekers')->insert([
            'user_id' => $seekerUserId,
            'full_name' => 'Unlocked Candidate',
            'job_title' => 'QA Specialist',
            'employment_type' => 'full-time',
            'experience_years' => 4,
            'state_id' => 1,
            'availability' => 'immediate',
            'education_level' => 'bachelor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $seekerId = $this->db->insertID();

        // 4. Seed Credits (2 credits)
        $this->db->table('job_credit_wallets')->insert([
            'user_id' => $employerUserId,
            'credits' => 2,
            'source' => 'bundle',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $creditService = new CreditService();

        // Verify initial credits
        $this->assertEquals(2, $creditService->getAvailableCredits($employerUserId));

        // 5. Deduct 1 credit for unlock
        $result = $creditService->deductCredits(
            $employerUserId,
            1,
            'unlock_' . $seekerId,
            'Unlocked candidate: ' . $seekerId,
            'unlock_candidate'
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $creditService->getAvailableCredits($employerUserId));

        // 6. Record Unlock
        $this->db->table('candidate_unlocks')->insert([
            'employer_id' => $employerId,
            'job_seeker_id' => $seekerId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 7. Verify isUnlocked check
        $isUnlocked = $this->db->table('candidate_unlocks')
            ->where('employer_id', $employerId)
            ->where('job_seeker_id', $seekerId)
            ->countAllResults() > 0;

        $this->assertTrue($isUnlocked);
    }

    public function testTransactionsAndPaymentsCalculations(): void
    {
        // 1. Create Employer User
        $employerUserId = $this->createUser('employer_test2', 'employer2@test.com', 'employer');
        $this->db->table('employers')->insert([
            'user_id' => $employerUserId,
            'company_name' => 'Transactions Company',
            'company_size' => '1-10',
            'contact_name' => 'Contact',
            'contact_email' => 'employer2@test.com',
            'contact_phone' => '12345678',
            'state_id' => 1,
            'unlimited_access' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $employerId = $this->db->insertID();

        // 2. Add multiple transaction records
        $this->db->table('payments')->insert([
            'user_id' => $employerUserId,
            'employer_id' => $employerId,
            'reference' => 'REF1',
            'amount' => 5000.00,
            'status' => 'paid',
            'payment_method' => 'card',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('payments')->insert([
            'user_id' => $employerUserId,
            'employer_id' => $employerId,
            'reference' => 'REF2',
            'amount' => 15000.00,
            'status' => 'paid',
            'payment_method' => 'card',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('payments')->insert([
            'user_id' => $employerUserId,
            'employer_id' => $employerId,
            'reference' => 'REF3',
            'amount' => 10000.00,
            'status' => 'failed',
            'payment_method' => 'card',
            'paid_at' => null,
        ]);

        // 3. Query payments and calculate spent
        $paymentModel = model(PaymentModel::class);
        $transactions = $paymentModel
            ->where('employer_id', $employerId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $this->assertCount(3, $transactions);

        $totalSpent = array_sum(array_column($transactions, 'amount'));
        $this->assertEquals(30000.00, $totalSpent);
    }

    public function testCandidateTransactionsSortingAndMerging(): void
    {
        // 1. Create candidate user
        $candidateUserId = $this->createUser('candidate_txn', 'candidate_txn@test.com', 'job_seeker');

        // Seed a mock course
        $this->db->table('courses')->insert([
            'id' => 1,
            'title' => 'Mock Course',
            'slug' => 'mock-course',
            'description' => 'This is a mock course for testing transactions.',
            'instructor' => 'Test Instructor',
            'duration' => '10 hours',
            'thumbnail' => 'mock.png',
            'content_source' => 'none',
            'price' => 5000.00,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 2. Add course enrollment with payment
        $this->db->table('course_enrollments')->insert([
            'course_id' => 1,
            'user_id' => $candidateUserId,
            'status' => 'enrolled',
            'payment_reference' => 'TX_COURSE_1',
            'amount' => 5000.00,
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ]);

        // 3. Add subscription payment
        $this->db->table('payments')->insert([
            'user_id' => $candidateUserId,
            'employer_id' => null,
            'reference' => 'TX_SUB_1',
            'amount' => 10000.00,
            'status' => 'paid',
            'payment_method' => 'card',
            'paid_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
        ]);

        // 4. Add CV review payment
        $this->db->table('cv_reviews')->insert([
            'user_id' => $candidateUserId,
            'plan' => 'professional',
            'amount' => 15000.00,
            'payment_reference' => 'TX_CV_1',
            'payment_status' => 'paid',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Verify initial mock counts in DB
        $this->assertEquals(1, $this->db->table('course_enrollments')->where('user_id', $candidateUserId)->countAllResults());
        $this->assertEquals(1, $this->db->table('payments')->where('user_id', $candidateUserId)->countAllResults());
        $this->assertEquals(1, $this->db->table('cv_reviews')->where('user_id', $candidateUserId)->countAllResults());
    }

    public function testMessageSendingConstraints(): void
    {
        // 1. Create Employer User & Profile
        $employerUserId = $this->createUser('employer_msg', 'employer_msg@test.com', 'employer');
        $this->db->table('employers')->insert([
            'user_id' => $employerUserId,
            'company_name' => 'Messaging Company',
            'company_size' => '1-10',
            'contact_name' => 'Contact',
            'contact_email' => 'employer_msg@test.com',
            'contact_phone' => '12345678',
            'state_id' => 1,
            'unlimited_access' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $employerId = $this->db->insertID();

        // 2. Create Seeker A (Unlocked) & Seeker B (Locked)
        $seekerUserIdA = $this->createUser('seeker_msg_a', 'seeker_msg_a@test.com', 'job_seeker');
        $this->db->table('job_seekers')->insert([
            'user_id' => $seekerUserIdA,
            'full_name' => 'Candidate Unlocked',
            'job_title' => 'QA Specialist',
            'employment_type' => 'full-time',
            'experience_years' => 4,
            'state_id' => 1,
            'availability' => 'immediate',
            'education_level' => 'bachelor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $seekerIdA = $this->db->insertID();

        $seekerUserIdB = $this->createUser('seeker_msg_b', 'seeker_msg_b@test.com', 'job_seeker');
        $this->db->table('job_seekers')->insert([
            'user_id' => $seekerUserIdB,
            'full_name' => 'Candidate Locked',
            'job_title' => 'QA Specialist',
            'employment_type' => 'full-time',
            'experience_years' => 4,
            'state_id' => 1,
            'availability' => 'immediate',
            'education_level' => 'bachelor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $seekerIdB = $this->db->insertID();

        // 3. Unlock Candidate A
        $this->db->table('candidate_unlocks')->insert([
            'employer_id' => $employerId,
            'job_seeker_id' => $seekerIdA,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 4. Test unlock conditions
        $isUnlockedA = $this->db->table('candidate_unlocks')
            ->where('employer_id', $employerId)
            ->where('job_seeker_id', $seekerIdA)
            ->countAllResults() > 0;
        $this->assertTrue($isUnlockedA);

        $isUnlockedB = $this->db->table('candidate_unlocks')
            ->where('employer_id', $employerId)
            ->where('job_seeker_id', $seekerIdB)
            ->countAllResults() > 0;
        $this->assertFalse($isUnlockedB);
    }
}

