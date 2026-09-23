<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserSubscriptionModel;
use App\Models\UserModel;
use App\Services\SubscriptionService;
use App\Services\EmailNotificationService;

class SubscriptionReminders extends BaseCommand
{
    protected $group       = 'Automations';
    protected $name        = 'subscriptions:send-expiring-reminders';
    protected $description = 'Send email & in-app notifications for subscriptions expiring in 3 days, 1 day, or newly expired';

    public function run(array $params)
    {
        CLI::write("Starting subscription expiration check...", "yellow");

        $subModel          = new UserSubscriptionModel();
        $userModel         = new UserModel();
        $subService        = new SubscriptionService();
        $emailNotifService = new EmailNotificationService();

        $now = time();

        // 1. Process 3-Day & 1-Day Expiring Warnings
        $activeSubs = $subModel
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->findAll();

        $count3d = 0;
        $count1d = 0;
        $countExpired = 0;

        foreach ($activeSubs as $sub) {
            $subObj = (object) $sub;
            $endsAtTimestamp = strtotime($subObj->ends_at);
            $secondsRemaining = $endsAtTimestamp - $now;
            $daysRemaining = (int) ceil($secondsRemaining / 86400);

            $user = $userModel->find($subObj->user_id);
            if (!$user) continue;

            $subDetails = $subService->getActiveSubscriptionDetails((int)$subObj->user_id);

            // 3-Day Warning (remaining days <= 3 and > 1)
            if ($daysRemaining <= 3 && $daysRemaining > 1 && empty($subObj->expiry_reminder_3d_sent)) {
                $sent = $emailNotifService->sendSubscriptionExpiringNotification($user, $subDetails, $daysRemaining);
                if ($sent) {
                    $subModel->update($subObj->id, ['expiry_reminder_3d_sent' => 1]);
                    $count3d++;
                }
            }

            // 1-Day Warning (remaining days <= 1)
            if ($daysRemaining <= 1 && empty($subObj->expiry_reminder_1d_sent)) {
                $sent = $emailNotifService->sendSubscriptionExpiringNotification($user, $subDetails, 1);
                if ($sent) {
                    $subModel->update($subObj->id, ['expiry_reminder_1d_sent' => 1]);
                    $count1d++;
                }
            }
        }

        // 2. Process Expired Subscriptions
        $expiredSubs = $subModel
            ->where('ends_at <=', date('Y-m-d H:i:s'))
            ->where('expired_notice_sent', 0)
            ->findAll();

        foreach ($expiredSubs as $sub) {
            $subObj = (object) $sub;
            $user = $userModel->find($subObj->user_id);

            if ($user) {
                $subDetails = $subService->getActiveSubscriptionDetails((int)$subObj->user_id);
                $emailNotifService->sendSubscriptionExpiredNotification($user, $subDetails);
            }

            $subModel->update($subObj->id, [
                'is_active'           => 0,
                'expired_notice_sent' => 1
            ]);
            $countExpired++;
        }

        CLI::write("Subscription notifications complete. 3-Day warnings: {$count3d}, 1-Day warnings: {$count1d}, Expired notices: {$countExpired}", "green");
    }
}
