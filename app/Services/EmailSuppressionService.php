<?php
namespace App\Services;

final class EmailSuppressionService
{
    public function __construct(private ?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $this->db ??= db_connect();
    }
    public function isSuppressed(string $email): bool
    {
        return $this->db->table('email_suppressions')->where('email_hash', hash('sha256', strtolower(trim($email))))->countAllResults() > 0;
    }
    public function suppress(string $email, string $reason, ?string $eventId = null): void
    {
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) return;
        if (!$this->db->table('email_suppressions')->replace([
            'email_hash' => hash('sha256', $email), 'email' => $email, 'reason' => $reason,
            'event_id' => $eventId ? substr($eventId, 0, 255) : null, 'created_at' => date('Y-m-d H:i:s'),
        ])) throw new \RuntimeException('Could not save email suppression.');
    }
    public function record(array $event): void
    {
        // Mailtrap marks unsubscribes as opted out for the sending domain too.
        // Soft bounces remain retryable and must not suppress the recipient.
        if (in_array($event['event'] ?? '', ['bounce', 'spam', 'unsubscribe'], true) && is_string($event['email'] ?? null)) {
            $this->suppress($event['email'], $event['event'], is_string($event['event_id'] ?? null) ? $event['event_id'] : null);
            if (($event['event'] ?? '') === 'unsubscribe' && $this->db->tableExists('newsletter_subscribers')) {
                $email = strtolower(trim($event['email']));
                $condition = 'LOWER(email) = ' . $this->db->escape($email);
                $this->db->table('newsletter_subscribers')->where($condition, null, false)
                    ->update(['is_active' => 0]);
            }
        }
    }
    public static function authentic(string $body, string $signature, string $secret): bool
    {
        return $secret !== '' && preg_match('/\A[a-fA-F0-9]{64}\z/', $signature)
            && hash_equals(hash_hmac('sha256', $body, $secret), strtolower($signature));
    }
}
