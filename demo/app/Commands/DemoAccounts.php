<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use App\Models\EmailVerificationModel;

/**
 * DemoAccounts - Creates a demo employer and candidate account for testing.
 *
 * Run with: php spark demo:accounts
 * This command is intended for development only.
 */
class DemoAccounts extends BaseCommand
{
    protected $group = 'Demo';
    protected $name = 'demo:accounts';
    protected $description = 'Create demo employer and candidate accounts for testing.';
    protected $usage = 'demo:accounts';
    protected $arguments = [];

    public function run(array $params)
    {
        if (ENVIRONMENT !== 'development') {
            CLI::error('This command can only be run in development environment.');
            return;
        }

        $users = model(UserModel::class);
        $employerModel = model(EmployerModel::class);
        $jobSeekerModel = model(JobSeekerModel::class);
        $emailVerification = model(EmailVerificationModel::class);

        // ---------- Employer ----------
        $employerEmail = 'demo.employer@example.com';
        $employerPassword = 'Password123';
        $employerUsername = 'demoemployer';

        // Ensure any previous demo employer account is removed before creation
        $db = db_connect();
        // Delete any existing identity for this email
        $db->table('auth_identities')
            ->where('secret', $employerEmail)
            ->where('type', 'email_password')
            ->delete();
        // Delete any existing user row with this username
        $db->table('users')->where('username', $employerUsername)->delete();
        CLI::write('Existing demo employer account removed if it existed.');
        // Proceed to create new employer account

            // Create Shield user (using userProvider via AuthController logic)
            $userProvider = model(setting('Auth.userProvider'));
            $newUser = $userProvider->createNewUser([
                'username' => $employerUsername,
                'email'    => $employerEmail,
                'user_type'=> 'employer',
                'active'   => 1,
                'status'   => 'active',
                'status_message' => 'Demo employer account',
            ]);
            $userId = $userProvider->insert($newUser);
            // Set password via Shield identity
            // Set password via Shield entity (auto‑hashes)
            $user = $userProvider->findById($userId);
            $user->setPassword($employerPassword);
            $userProvider->save($user);
            // Email identity creation removed to prevent duplicate entries
            // Employer profile
            $employerModel->save([
                'user_id'       => $userId,
                'company_name'  => 'Demo Employer Inc.',
                'contact_name'  => 'Demo Employer',
                'contact_phone' => '1234567890',
            ]);
            // Mark email verified
            $users->update($userId, ['email_verified_at' => date('Y-m-d H:i:s')]);
            CLI::write('Created demo employer: ' . $employerEmail);


        // ---------- Candidate ----------
        $candidateEmail = 'demo.candidate@example.com';
        $candidatePassword = 'Password123';
        $candidateUsername = 'democandidate';

        // Ensure any previous demo candidate account is removed before creation
        // Delete any existing identity for this email
        $db->table('auth_identities')
            ->where('secret', $candidateEmail)
            ->where('type', 'email_password')
            ->delete();
        // Delete any existing user row with this username
        $db->table('users')->where('username', $candidateUsername)->delete();
        CLI::write('Existing demo candidate account removed if it existed.');
        // Proceed to create new candidate account


            $userProvider = model(setting('Auth.userProvider'));
            $newUser = $userProvider->createNewUser([
                'username' => $candidateUsername,
                'email'    => $candidateEmail,
                'user_type'=> 'job_seeker',
                'active'   => 1,
                'status'   => 'active',
                'status_message' => 'Demo candidate account',
            ]);
            $userId = $userProvider->insert($newUser);
            // Set password via Shield entity (auto‑hashes)
            $user = $userProvider->findById($userId);
            $user->setPassword($candidatePassword);
            $userProvider->save($user);
            // Email identity creation removed to prevent duplicate entries
            // Candidate profile
            $jobSeekerModel->save([
                'user_id'   => $userId,
                'full_name' => 'Demo Candidate',
                'phone'     => '0987654321',
                'location'  => 'Demo City',
                'state_id'  => 1,
                'job_title' => 'Software Engineer',
                'employment_type' => 'full_time',
                'skills' => 'PHP, JavaScript, HTML, CSS',
                'experience_years' => 2,
                'education_level' => "Bachelor's",
                'resume' => null,
            ]);
            // Mark email verified
            $users->update($userId, ['email_verified_at' => date('Y-m-d H:i:s')]);
            CLI::write('Created demo candidate: ' . $candidateEmail);
        CLI::write('Demo accounts setup complete.');


    }
}
?>
