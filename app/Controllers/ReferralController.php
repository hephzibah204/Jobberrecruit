<?php

namespace App\Controllers;

use App\Models\ReferralModel;
use App\Models\UserModel;
use App\Services\ReferralService;
use CodeIgniter\API\ResponseTrait;

class ReferralController extends BaseController
{
    use ResponseTrait;
    protected $referralModel;
    protected $userModel;
    protected $referralService;

    public function __construct()
    {
        $this->referralModel = model(ReferralModel::class);
        $this->userModel = model(UserModel::class);
        $this->referralService = new ReferralService();
    }

    /**
     * User Referral Dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Generate code if missing
        $referralCode = $this->referralService->generateCode($user->id);
        
        $referrals = $this->referralModel
            ->select('referrals.*, users.username as referee_name, users.created_at as joined_at')
            ->join('users', 'users.id = referrals.referee_id')
            ->where('referrer_id', $user->id)
            ->orderBy('referrals.created_at', 'DESC')
            ->findAll();

        $stats = [
            'total_referrals' => count($referrals),
            'completed_referrals' => $this->referralModel->where('referrer_id', $user->id)->where('status', 'rewarded')->countAllResults(),
            'total_earned' => $this->referralModel->where('referrer_id', $user->id)->selectSum('reward_amount')->get()->getRow()->reward_amount ?? 0
        ];

        $data = [
            'title' => 'Referral & Affiliate Program',
            'referralCode' => $referralCode,
            'referrals' => $referrals,
            'stats' => $stats,
            'user' => $user,
        ];

        // When an employer views this page it renders inside the employer
        // layout, which shows the company name/logo and a pending-apps badge.
        if ($user->user_type === 'employer') {
            $employer = model(\App\Models\EmployerModel::class)
                ->where('user_id', $user->id)->first();
            $data['employer'] = $employer;
            if ($employer) {
                $data['pendingApps'] = model(\App\Models\JobApplicationModel::class)
                    ->where('status', 'pending')
                    ->whereIn('job_id', function ($builder) use ($employer) {
                        return $builder->select('id')->from('jobs')->where('employer_id', $employer->id);
                    })
                    ->countAllResults();
            }
        }

        return view('common/referral_dashboard', $data);
    }

    /**
     * Admin: Manage Affiliate Settings
     */
    public function adminSettings()
    {
        // Admin only check
        if (auth()->user()->user_type !== 'admin') {
            return redirect()->to('/')->with('error', 'Unauthorized access');
        }

        $db = \Config\Database::connect();
        $settings = $db->table('affiliate_settings')->get()->getResult();

        return view('admin/affiliate_settings', [
            'title' => 'Affiliate Settings',
            'settings' => $settings
        ]);
    }

    /**
     * Admin: Update Settings
     */
    public function updateSettings()
    {
        if (auth()->user()->user_type !== 'admin') {
            return $this->fail('Unauthorized');
        }

        $settings = $this->request->getPost('settings');
        $db = \Config\Database::connect();

        foreach ($settings as $key => $value) {
            $db->table('affiliate_settings')
               ->where('key', $key)
               ->update(['value' => $value, 'updated_at' => date('Y-m-d H:i:s')]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
