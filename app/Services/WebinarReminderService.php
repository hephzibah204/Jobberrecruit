<?php
namespace App\Services;

class WebinarReminderService
{
    public function __construct(
        private ?\CodeIgniter\Database\BaseConnection $db = null,
        private ?EmailNotificationService $notifications = null
    ) {
        $this->db ??= db_connect();
        $this->notifications ??= new EmailNotificationService();
    }

    public function run(?\DateTimeInterface $now = null): int
    {
        $now ??= new \DateTimeImmutable('now', new \DateTimeZone(config('App')->appTimezone));
        $webinars = (new \App\Models\WebinarModel($this->db))->upcoming($now)->findAll();
        $registrations = new \App\Models\WebinarRegistrationModel($this->db);
        $users = new \App\Models\UserModel($this->db);
        $sent = 0;
        foreach ($webinars as $webinar) {
            $start = new \DateTimeImmutable($webinar->scheduled_at, new \DateTimeZone(config('App')->appTimezone));
            $hours = ($start->getTimestamp() - $now->getTimestamp()) / 3600;
            $frame = $hours >= 23 && $hours <= 25 ? '24h' : ($hours >= 0.1 && $hours <= 1.5 ? '1h' : null);
            if (!$frame) continue;
            $flag = 'reminder_sent_' . $frame;
            foreach ($registrations->where('webinar_id', $webinar->id)->where($flag, 0)->findAll() as $registration) {
                $user = $users->find($registration->user_id);
                if ($user && $this->notifications->sendWebinarReminderNotification($user, $webinar, $frame)) {
                    if (!$registrations->update($registration->id, [$flag=>1])) throw new \RuntimeException('Reminder status could not be saved.');
                    $sent++;
                }
            }
        }
        return $sent;
    }
}
