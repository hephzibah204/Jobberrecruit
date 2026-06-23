<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use App\Models\ConversationModel;

class TestMessageController extends ResourceController
{
    public function index()
    {
        $userModel = new UserModel();
        $employerModel = new EmployerModel();
        $seekerModel = new JobSeekerModel();
        $conversationModel = new ConversationModel();
        
        $db = \Config\Database::connect();
        $db->transStart();

        $empUserEmail = 'test_employer_' . time() . '@example.com';
        $userIdEmp = $userModel->insert([
            'first_name' => 'Test',
            'last_name' => 'Employer',
            'email' => $empUserEmail,
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'user_type' => 'employer',
            'status' => 'active'
        ]);

        $employerId = $employerModel->insert([
            'user_id' => $userIdEmp,
            'company_name' => 'Test Company LLC',
            'company_email' => $empUserEmail,
            'is_active' => 1
        ]);

        $seekUserEmail = 'test_seeker_' . time() . '@example.com';
        $userIdSeek = $userModel->insert([
            'first_name' => 'Test',
            'last_name' => 'Seeker',
            'email' => $seekUserEmail,
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'user_type' => 'job_seeker',
            'status' => 'active'
        ]);

        $seekerId = $seekerModel->insert([
            'user_id' => $userIdSeek,
            'full_name' => 'Test Seeker',
            'is_active' => 1
        ]);

        $db->transCommit();

        $validEmployer = $employerModel->find($employerId);
        $validSeeker = $seekerModel->find($seekerId);

        if (!$validEmployer || !$validSeeker) {
            return $this->response->setJSON(['error' => "Invalid entities. Emp: $employerId, Seek: $seekerId"]);
        }

        try {
            // Note: Testing with ID from entity to mimic exactly what startConversation does
            $conversationId = $conversationModel->insert([
                'employer_id' => $validEmployer->id,
                'job_seeker_id' => $validSeeker->id,
                'job_id' => null,
                'is_active' => 1,
            ]);
            
            if ($conversationId) {
                return $this->response->setJSON(['success' => true, 'conversation_id' => $conversationId]);
            } else {
                return $this->response->setJSON(['error' => 'Validation error', 'messages' => $conversationModel->errors()]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Exception', 'message' => $e->getMessage()]);
        }
    }
}
