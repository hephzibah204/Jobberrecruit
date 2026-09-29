<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\JobSeekerModel;
use App\Models\EmployerModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use CodeIgniter\Shield\Entities\User;

class AdminUserController extends BaseController
{
    public function index()
    {
        $userModel = model(UserModel::class);
        $search = $this->request->getGet('search');
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        $db = \Config\Database::connect();

        // 1. Compute Unified Source-of-Truth Metrics
        $totalUsersCount      = (int) $db->table('users')->countAllResults();
        $totalEmployersCount  = (int) $db->table('employers')->countAllResults();
        $totalCandidatesCount = (int) $db->table('job_seekers')->countAllResults();
        $totalAdminsCount     = (int) $db->table('auth_groups_users')->whereIn('group', ['admin', 'superadmin'])->countAllResults();

        // 2. Build Unified Query with Role & Profile Resolution
        $builder = $userModel->select([
            'users.*',
            'MAX(wallets.balance) as balance',
            'MAX(job_seekers.full_name) as seeker_name',
            'MAX(employers.company_name) as employer_name',
            'MAX(auth_identities.secret) as identity_email',
            'COALESCE(MAX(auth_groups_users.group), MAX(users.user_type), CASE WHEN MAX(employers.id) IS NOT NULL THEN "employer" WHEN MAX(job_seekers.id) IS NOT NULL THEN "job_seeker" ELSE "unknown" END) as role'
        ])
        ->join('wallets', 'wallets.user_id = users.id', 'left')
        ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
        ->join('employers', 'employers.user_id = users.id', 'left')
        ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
        ->join('auth_identities', 'auth_identities.user_id = users.id', 'left')
        ->groupBy('users.id');

        if ($search) {
            $builder->groupStart()
                    ->like('users.username', $search)
                    ->orLike('auth_identities.secret', $search)
                    ->orLike('employers.contact_email', $search)
                    ->orLike('job_seekers.full_name', $search)
                    ->orLike('employers.company_name', $search)
                    ->groupEnd();
        }

        if ($role) {
            if ($role === 'employer') {
                $builder->groupStart()
                        ->where('auth_groups_users.group', 'employer')
                        ->orWhere('users.user_type', 'employer')
                        ->orWhere('employers.id IS NOT NULL', null, false)
                        ->groupEnd();
            } elseif ($role === 'job_seeker' || $role === 'candidate') {
                $builder->groupStart()
                        ->where('auth_groups_users.group', 'job_seeker')
                        ->orWhere('auth_groups_users.group', 'candidate')
                        ->orWhere('users.user_type', 'job_seeker')
                        ->orWhere('users.user_type', 'candidate')
                        ->orWhere('job_seekers.id IS NOT NULL', null, false)
                        ->groupEnd();
            } elseif ($role === 'admin') {
                $builder->groupStart()
                        ->where('auth_groups_users.group', 'admin')
                        ->orWhere('auth_groups_users.group', 'superadmin')
                        ->orWhere('users.user_type', 'admin')
                        ->orWhere('users.user_type', 'superadmin')
                        ->groupEnd();
            } else {
                $builder->groupStart()
                        ->where('auth_groups_users.group', $role)
                        ->orWhere('users.user_type', $role)
                        ->groupEnd();
            }
        }

        if ($status !== null && $status !== '') {
            $builder->where('users.active', $status);
        }

        $users = $builder->orderBy('users.created_at', 'DESC')->paginate(20);
        $pager = $userModel->pager;

        return view('admin/users/index', [
            'users'                => $users,
            'pager'                => $pager,
            'search'               => $search,
            'role'                 => $role,
            'status'               => $status,
            'totalUsersCount'      => $totalUsersCount,
            'totalEmployersCount'  => $totalEmployersCount,
            'totalCandidatesCount' => $totalCandidatesCount,
            'totalAdminsCount'     => $totalAdminsCount,
        ]);
    }

    public function fundWallet()
    {
        $userId = $this->request->getPost('user_id');
        $amount = (float) $this->request->getPost('amount');
        
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Amount must be greater than zero.');
        }

        $walletModel = model(WalletModel::class);
        $wallet = $walletModel->where('user_id', $userId)->first();

        if (!$wallet) {
            // Create wallet if it doesn't exist
            $walletModel->insert(['user_id' => $userId, 'balance' => $amount, 'currency' => 'NGN']);
            $walletId = $walletModel->getInsertID();
        } else {
            $walletModel->update($wallet->id, ['balance' => $wallet->balance + $amount]);
            $walletId = $wallet->id;
        }

        $transactionModel = model(WalletTransactionModel::class);
        $transactionModel->insert([
            'wallet_id' => $walletId,
            'type' => 'credit',
            'amount' => $amount,
            'reference' => 'FUND_' . strtoupper(uniqid()),
            'description' => 'Admin funding'
        ]);

        return redirect()->back()->with('success', 'Wallet funded successfully.');
    }

    public function resetPassword()
    {
        $userId = $this->request->getPost('user_id');
        $users = auth()->getProvider();
        $user = $users->findById($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Generate a random temporary password
        $tempPassword = bin2hex(random_bytes(4));
        $user->fill(['password' => $tempPassword]);
        $users->save($user);

        return redirect()->back()->with('success', "Password reset successfully. The new temporary password is: <b>$tempPassword</b>");
    }

    public function toggleStatus()
    {
        $userId = $this->request->getPost('user_id');
        $userModel = model(UserModel::class);
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $newStatus = $user->active ? 0 : 1;
        $userModel->update($userId, ['active' => $newStatus]);

        $msg = $newStatus ? 'User unsuspended successfully.' : 'User suspended successfully.';
        return redirect()->back()->with('success', $msg);
    }

    public function deleteUser()
    {
        $userId = $this->request->getPost('user_id');
        $userModel = model(UserModel::class);
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // We will perform a hard delete by deleting from Shield provider
        $users = auth()->getProvider();
        $users->delete($userId, true); 

        return redirect()->back()->with('success', 'User completely deleted.');
    }

    /**
     * Bulk Delete Users
     */
    public function bulkDelete()
    {
        $userIds = $this->request->getPost('user_ids');
        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No users selected for deletion.'
            ]);
        }

        $currentUserId = auth()->id();
        $db = \Config\Database::connect();
        $users = auth()->getProvider();
        $deletedCount = 0;

        $db->transStart();

        foreach ($userIds as $uid) {
            $uid = (int) $uid;
            if ($uid === $currentUserId) {
                continue; // Prevent deleting the currently logged-in admin
            }

            // 1. Delete candidate records
            $seeker = $db->table('job_seekers')->where('user_id', $uid)->get()->getRow();
            if ($seeker) {
                $seekerId = $seeker->id;
                $db->table('job_applications')->where('job_seeker_id', $seekerId)->delete();
                $db->table('saved_jobs')->where('job_seeker_id', $seekerId)->delete();
                $db->table('cv_reviews')->where('job_seeker_id', $seekerId)->delete();
                $db->table('resumes')->where('job_seeker_id', $seekerId)->delete();
                $db->table('resume_education')->where('job_seeker_id', $seekerId)->delete();
                $db->table('resume_experience')->where('job_seeker_id', $seekerId)->delete();
                $db->table('resume_skills')->where('job_seeker_id', $seekerId)->delete();
                $db->table('resume_autosaves')->where('job_seeker_id', $seekerId)->delete();
                $db->table('job_seekers')->where('id', $seekerId)->delete();
            }

            // 2. Delete employer records
            $employer = $db->table('employers')->where('user_id', $uid)->get()->getRow();
            if ($employer) {
                $empId = $employer->id;
                $db->table('job_applications')->whereIn('job_id', static function($builder) use ($empId) {
                    return $builder->select('id')->from('jobs')->where('employer_id', $empId);
                })->delete();
                $db->table('jobs')->where('employer_id', $empId)->delete();
                $db->table('employer_documents')->where('employer_id', $empId)->delete();
                $db->table('employer_industries')->where('employer_id', $empId)->delete();
                $db->table('job_credit_wallets')->where('employer_id', $empId)->delete();
                $db->table('employers')->where('id', $empId)->delete();
            }

            // 3. Delete wallets and subscriptions
            $wallet = $db->table('wallets')->where('user_id', $uid)->get()->getRow();
            if ($wallet) {
                $db->table('wallet_transactions')->where('wallet_id', $wallet->id)->delete();
                $db->table('wallets')->where('id', $wallet->id)->delete();
            }
            $db->table('user_subscriptions')->where('user_id', $uid)->delete();
            $db->table('newsletter_subscribers')->where('user_id', $uid)->delete();

            // 4. Delete user through Shield
            $users->delete($uid, true);
            $deletedCount++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete selected users due to a database error.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} user(s) and their associated data."
        ]);
    }

    public function resetAccount()
    {
        $userId = $this->request->getPost('user_id');
        $userModel = model(UserModel::class);
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Reset Wallet
        $walletModel = model(WalletModel::class);
        $wallet = $walletModel->where('user_id', $userId)->first();
        if ($wallet) {
            $db->table('wallet_transactions')->where('wallet_id', $wallet->id)->delete();
            $walletModel->update($wallet->id, ['balance' => 0]);
        }

        // 2. Identify Role & Clear Data
        $seekerModel = model(JobSeekerModel::class);
        $employerModel = model(EmployerModel::class);

        $seeker = $seekerModel->where('user_id', $userId)->first();
        if ($seeker) {
            $seekerId = $seeker->id;
            // Delete seeker related data
            $db->table('job_applications')->where('job_seeker_id', $seekerId)->delete();
            $db->table('saved_jobs')->where('job_seeker_id', $seekerId)->delete();
            $db->table('cv_reviews')->where('job_seeker_id', $seekerId)->delete();
            $db->table('resumes')->where('job_seeker_id', $seekerId)->delete();
            $db->table('resume_education')->where('job_seeker_id', $seekerId)->delete();
            $db->table('resume_experience')->where('job_seeker_id', $seekerId)->delete();
            $db->table('resume_skills')->where('job_seeker_id', $seekerId)->delete();
            $db->table('resume_autosaves')->where('job_seeker_id', $seekerId)->delete();
            
            // Keep basic details, but clear heavy profile data
            $seekerModel->update($seekerId, [
                'bio' => null,
                'cv_file' => null,
                'video_resume_url' => null,
                'profile_picture' => null,
                'address' => null,
                'linkedin_url' => null,
                'portfolio_url' => null
            ]);
        }

        $employer = $employerModel->where('user_id', $userId)->first();
        if ($employer) {
            $employerId = $employer->id;
            // Delete employer related data
            $db->table('jobs')->where('employer_id', $employerId)->delete();
            $db->table('employer_documents')->where('employer_id', $employerId)->delete();
            $db->table('employer_industries')->where('employer_id', $employerId)->delete();
            $db->table('job_credit_wallets')->where('employer_id', $employerId)->delete();

            // Keep basic details
            $employerModel->update($employerId, [
                'description' => null,
                'logo' => null,
                'website' => null,
                'address' => null,
                'verification_status' => 'unverified'
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to reset account due to a database error.');
        }

        return redirect()->back()->with('success', 'Account reset successfully (Fresh Start).');
    }
}
