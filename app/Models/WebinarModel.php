<?php

namespace App\Models;

use CodeIgniter\Model;

class WebinarModel extends Model
{
    protected $table            = 'webinars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'speaker_name', 'scheduled_at', 'meeting_link', 'recording_link', 'access_type', 'price', 'flyer_image', 'registrants_count', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function upcoming(?\DateTimeInterface $now = null): self
    {
        $zone = new \DateTimeZone(config('App')->appTimezone);
        $localNow = $now ? \DateTimeImmutable::createFromInterface($now)->setTimezone($zone) : new \DateTimeImmutable('now', $zone);
        return $this->where('status', 'upcoming')
            ->where('scheduled_at >', $localNow->format('Y-m-d H:i:s'))
            ->orderBy('scheduled_at', 'ASC');
    }

    public static function registrationOpen(object $webinar, ?\DateTimeInterface $now = null): bool
    {
        if (($webinar->status ?? '') !== 'upcoming') return false;
        $zone = new \DateTimeZone(config('App')->appTimezone);
        $start = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', (string) ($webinar->scheduled_at ?? ''), $zone);
        $errors = \DateTimeImmutable::getLastErrors();
        if (!$start || ($errors && ($errors['warning_count'] || $errors['error_count']))) return false;
        return $start > ($now ?? new \DateTimeImmutable('now', $zone));
    }

    public static function inProgress(object $webinar, ?\DateTimeInterface $now = null): bool
    {
        if (!in_array($webinar->status ?? '', ['upcoming', 'ongoing'], true)) return false;
        $zone = new \DateTimeZone(config('App')->appTimezone);
        $start = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', (string) ($webinar->scheduled_at ?? ''), $zone);
        $errors = \DateTimeImmutable::getLastErrors();
        if (!$start || ($errors && ($errors['warning_count'] || $errors['error_count']))) return false;
        $now ??= new \DateTimeImmutable('now', $zone);
        // Webinar tickets and calendar invites currently specify a 90-minute session.
        return $start <= $now && $start->modify('+90 minutes') > $now;
    }

    public static function displayStatus(object $webinar, ?\DateTimeInterface $now = null): string
    {
        if (in_array($webinar->status ?? '', ['completed','cancelled'], true)) return $webinar->status;
        if (self::inProgress($webinar, $now)) return 'ongoing';
        if (self::registrationOpen((object) array_merge((array) $webinar, ['status'=>'upcoming']), $now)) return 'upcoming';
        return 'completed';
    }
}
