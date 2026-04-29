<?php

namespace App\Services;

use App\Models\ReferralModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class ReferralService
{
    protected $referralModel;
    protected $userModel;

    public function __construct()
    {
        $this->referralModel = model(ReferralModel::class);
        $this->userModel = model(UserModel::class);
    }

    /**
     * Generate a unique referral code for a user
     */
    public function generateCode($userId)
    {
        $user = $this->userModel->find($userId);
        if ($user->referral_code) {
            return $user->referral_code;
        }

        $code = strtoupper(substr(md5($userId . time()), 0, 8));
        
        // Ensure uniqueness
        while ($this->userModel->where('referral_code', $code)->countAllResults() > 0) {
            $code = strtoupper(substr(md5($userId . time() . rand()), 0, 8));
        }

        $this->userModel->update($userId, ['referral_code' => $code]);
        return $code;
    }

    /**
     * Attribute a referral to a new user
     */
    public function attributeReferral($newUserId, $code)
    {
        $referrer = $this->userModel->where('referral_code', $code)->first();
        if (!$referrer) {
            return false;
        }

        // Avoid self-referral
        if ($referrer->id == $newUserId) {
            return false;
        }

        $this->userModel->update($newUserId, ['referred_by' => $referrer->id]);

        return $this->referralModel->insert([
            'referrer_id' => $referrer->id,
            'referred_id' => $newUserId,
            'status'      => 'pending',
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Process reward for a completed action (e.g. first payment)
     */
    public function rewardReferrer($referredId, $actionType = 'payment')
    {
        $referral = $this->referralModel->where('referred_id', $referredId)->where('status', 'pending')->first();
        if (!$referral) {
            return false;
        }

        $db = \Config\Database::connect();
        $settingKey = $actionType === 'payment' ? 'referral_reward_employer' : 'referral_reward_candidate';
        $rewardAmount = $db->table('affiliate_settings')->where('key', $settingKey)->get()->getRow()->value ?? 0;

        if ($rewardAmount <= 0) {
            return false;
        }

        // Credit the referrer's wallet
        $walletService = new WalletService();
        $walletService->credit(
            $referral->referrer_id, 
            $rewardAmount, 
            'referral_reward', 
            'REF-' . $referral->id, 
            null, 
            'Reward for referring a new user'
        );

        return $this->referralModel->update($referral->id, [
            'status' => 'rewarded',
            'reward_amount' => $rewardAmount
        ]);
    }
}
