<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;

$userModel = new UserModel();
$employerModel = new EmployerModel();
$seekerModel = new JobSeekerModel();
$db = \Config\Database::connect();

try {
    $db->transStart();

    // 1. Create an Employer User
    $empUserEmail = 'test_employer_' . time() . '@example.com';
    $userIdEmp = $userModel->insert([
        'first_name' => 'Test',
        'last_name' => 'Employer',
        'email' => $empUserEmail,
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'user_type' => 'employer',
        'status' => 'active'
    ]);

    // Create the employer profile
    $employerId = $employerModel->insert([
        'user_id' => $userIdEmp,
        'company_name' => 'Test Company LLC',
        'company_email' => $empUserEmail,
        'is_active' => 1
    ]);

    echo "Created Employer User ID: $userIdEmp, Employer ID: $employerId\n";

    // 2. Create a Job Seeker User
    $seekUserEmail = 'test_seeker_' . time() . '@example.com';
    $userIdSeek = $userModel->insert([
        'first_name' => 'Test',
        'last_name' => 'Seeker',
        'email' => $seekUserEmail,
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'user_type' => 'job_seeker',
        'status' => 'active'
    ]);

    // Create the seeker profile
    $seekerId = $seekerModel->insert([
        'user_id' => $userIdSeek,
        'full_name' => 'Test Seeker',
        'is_active' => 1
    ]);

    echo "Created Job Seeker User ID: $userIdSeek, Seeker ID: $seekerId\n";

    $db->transCommit();
} catch (\Exception $e) {
    $db->transRollback();
    echo "Error setting up users: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Simulate MessageController::startConversation logic
use App\Models\ConversationModel;
$conversationModel = new ConversationModel();

echo "Simulating startConversation...\n";

// Validate existence
$validEmployer = $employerModel->find($employerId);
$validSeeker = $seekerModel->find($seekerId);

if (!$validEmployer || !$validSeeker) {
    echo "Validation Failed: Invalid recipient or conversation party not found. Emp: $employerId, Seek: $seekerId\n";
} else {
    try {
        $conversationId = $conversationModel->insert([
            'employer_id' => $employerId,
            'job_seeker_id' => $seekerId,
            'job_id' => null,
            'is_active' => 1,
        ]);
        
        if ($conversationId) {
            echo "Successfully created conversation! ID: $conversationId\n";
        } else {
            echo "Failed to create conversation: " . json_encode($conversationModel->errors()) . "\n";
        }
    } catch (\Exception $e) {
        echo "Exception during insert: " . $e->getMessage() . "\n";
    }
}
