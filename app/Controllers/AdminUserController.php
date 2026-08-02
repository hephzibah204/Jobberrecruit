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

        $userModel->select('users.*, wallets.balance, job_seekers.full_name as seeker_name, employers.company_name as employer_name, auth_groups_users.group as role')
                  ->join('wallets', 'wallets.user_id = users.id', 'left')
                  ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
                  ->join('employers', 'employers.user_id = users.id', 'left')
                  ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');

        if ($search) {
            $userModel->groupStart()
                      ->like('users.username', $search)
                      ->orLike('users.email', $search)
                      ->orLike('job_seekers.full_name', $search)
                      ->orLike('employers.company_name', $search)
                      ->groupEnd();
        }

        if ($role) {
            $userModel->groupStart()
                      ->where('auth_groups_users.group', $role)
                      ->orWhere('users.user_type', $role)
                      ->groupEnd();
        }

        if ($status !== null && $status !== '') {
            $userModel->where('users.active', $status);
        }

        $users = $userModel->orderBy('users.created_at', 'DESC')->paginate(20);
        $pager = $userModel->pager;

        return view('admin/users/index', [
            'users' => $users,
            'pager' => $pager,
            'search' => $search,
            'role' => $role,
            'status' => $status
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
