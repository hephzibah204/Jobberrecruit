<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\WebinarModel;
use App\Models\WebinarRegistrationModel;
use App\Models\UserModel;
use App\Services\EmailNotificationService;

class WebinarReminders extends BaseCommand
{
    protected $group       = 'Automations';
    protected $name        = 'webinars:send-reminders';
    protected $description = 'Send 24h and 1h email & in-app reminders to registered webinar attendees';

    public function run(array $params)
    {
        CLI::write("Starting webinar reminder processing...", "yellow");

        $webinarModel      = new WebinarModel();
        $registrationModel = new WebinarRegistrationModel();
        $userModel         = new UserModel();
        $emailNotifService = new EmailNotificationService();

        $now               = time();
        $in24Hours         = date('Y-m-d H:i:s', $now + (24 * 3600));
        $in1Hour           = date('Y-m-d H:i:s', $now + 3600);

        // Fetch upcoming active webinars
        $webinars = $webinarModel
            ->where('status !=', 'cancelled')
            ->where('scheduled_at >=', date('Y-m-d H:i:s', $now))
            ->findAll();

        $sentCount24h = 0;
        $sentCount1h  = 0;

        foreach ($webinars as $webinar) {
            $webinarObj = (object) $webinar;
            $scheduledTime = strtotime($webinarObj->scheduled_at);
            $diffHours = ($scheduledTime - $now) / 3600;

            // 1. Check for 24-hour reminder window (between 23h and 25h away)
            if ($diffHours <= 25 && $diffHours >= 23) {
                $registrations = $registrationModel
                    ->where('webinar_id', $webinarObj->id)
                    ->where('reminder_sent_24h', 0)
                    ->findAll();

                foreach ($registrations as $reg) {
                    $user = $userModel->find($reg['user_id']);
                    if ($user) {
                        $sent = $emailNotifService->sendWebinarReminderNotification($user, $webinarObj, '24h');
                        if ($sent) {
                            $registrationModel->update($reg['id'], ['reminder_sent_24h' => 1]);
                            $sentCount24h++;
                        }
                    }
                }
            }

            // 2. Check for 1-hour reminder window (between 0.5h and 1.5h away)
            if ($diffHours <= 1.5 && $diffHours >= 0.2) {
                $registrations = $registrationModel
                    ->where('webinar_id', $webinarObj->id)
                    ->where('reminder_sent_1h', 0)
                    ->findAll();

                foreach ($registrations as $reg) {
                    $user = $userModel->find($reg['user_id']);
                    if ($user) {
                        $sent = $emailNotifService->sendWebinarReminderNotification($user, $webinarObj, '1h');
                        if ($sent) {
                            $registrationModel->update($reg['id'], ['reminder_sent_1h' => 1]);
                            $sentCount1h++;
                        }
                    }
                }
            }
        }

        CLI::write("Webinar reminders completed. Sent 24h: {$sentCount24h}, 1h: {$sentCount1h}", "green");
    }
}
