<?php

namespace App\Services;

/**
 * Evaluates audience-segment rule trees against the newsletter
 * subscriber list.
 *
 * The criteria are the JSON trees produced by the jQuery QueryBuilder
 * on the segment builder page (and stored in audience_segments.criteria_json):
 * groups of {condition: AND|OR, rules: [...]} whose leaf rules are
 * {id, operator, value}. Groups may nest.
 *
 * The segment universe is newsletter_subscribers — the same list the
 * preset "Subscribers" campaign audiences draw from. A subscriber is
 * eligible only while is_active = 1, which is exactly how
 * NewsletterController::sendNewsletter() excludes unsubscribes (the
 * Mailtrap webhook flips is_active to 0 on an unsubscribe event).
 *
 * Counts and recipient lists always come from the same evaluation, so
 * an estimate, the stored user_count and an actual send can never
 * disagree with each other.
 */
class SegmentAudienceService
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct(?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * Full recipient list for a criteria tree (array or JSON string).
     * Each entry is ['email' => string, 'name' => string].
     * Deduplicated by lower-cased email; the first matching row wins.
     * Rows with an invalid email are skipped, mirroring the
     * FILTER_VALIDATE_EMAIL check in sendNewsletter().
     *
     * @throws \InvalidArgumentException on unknown fields, operators or values
     */
    public function recipients($criteria): array
    {
        $tree = $this->decodeCriteria($criteria);

        $rows = $this->db->table('newsletter_subscribers ns')
            ->select('ns.id, ns.email, ns.user_id, ns.name, ns.engagement_score, ns.tags, ns.last_opened_at, ns.gdpr_consent, ns.status, ns.created_at, u.user_type, u.username, js.full_name AS seeker_name, emp.company_name')
            ->join('users u', 'u.id = ns.user_id', 'left')
            ->join('job_seekers js', 'js.user_id = ns.user_id', 'left')
            ->join('employers emp', 'emp.user_id = ns.user_id', 'left')
            ->where('ns.is_active', 1)
            ->orderBy('ns.id', 'ASC')
            ->get()
            ->getResultArray();

        $recipients = [];
        $seen = [];
        foreach ($rows as $row) {
            if (!$this->matchesGroup($row, $tree)) {
                continue;
            }
            $email = trim((string) ($row['email'] ?? ''));
            $key = strtolower($email);
            if ($email === '' || isset($seen[$key]) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $seen[$key] = true;
            $recipients[] = [
                'email' => $email,
                'name'  => $this->recipientName($row),
            ];
        }

        return $recipients;
    }

    /**
     * Exact number of recipients a criteria tree matches right now.
     *
     * @throws \InvalidArgumentException on unknown fields, operators or values
     */
    public function count($criteria): int
    {
        return count($this->recipients($criteria));
    }

    /**
     * Count plus up to $limit sample recipients, for admin previews.
     *
     * @throws \InvalidArgumentException on unknown fields, operators or values
     */
    public function preview($criteria, int $limit = 10): array
    {
        $recipients = $this->recipients($criteria);

        return [
            'count'  => count($recipients),
            'sample' => array_slice($recipients, 0, max(0, $limit)),
        ];
    }

    private function decodeCriteria($criteria): array
    {
        if (is_string($criteria)) {
            $decoded = json_decode($criteria, true);
            if (!is_array($decoded)) {
                throw new \InvalidArgumentException('Segment criteria are not valid JSON.');
            }
            $criteria = $decoded;
        }

        if (!is_array($criteria) || empty($criteria['rules']) || !is_array($criteria['rules'])) {
            throw new \InvalidArgumentException('Segment criteria contain no rules.');
        }

        return $criteria;
    }

    private function matchesGroup(array $row, array $node): bool
    {
        $condition = strtoupper((string) ($node['condition'] ?? 'AND'));
        if (!in_array($condition, ['AND', 'OR'], true)) {
            throw new \InvalidArgumentException('Unknown segment condition: ' . $condition);
        }

        $rules = $node['rules'] ?? null;
        if (!is_array($rules) || $rules === []) {
            throw new \InvalidArgumentException('Segment rule group is empty.');
        }

        foreach ($rules as $rule) {
            if (!is_array($rule)) {
                throw new \InvalidArgumentException('Segment rule is malformed.');
            }
            $result = isset($rule['rules'])
                ? $this->matchesGroup($row, $rule)
                : $this->matchesRule($row, $rule);

            if ($condition === 'AND' && !$result) {
                return false;
            }
            if ($condition === 'OR' && $result) {
                return true;
            }
        }

        return $condition === 'AND';
    }

    private function matchesRule(array $row, array $rule): bool
    {
        $field    = (string) ($rule['id'] ?? $rule['field'] ?? '');
        $operator = (string) ($rule['operator'] ?? '');
        $value    = $rule['value'] ?? null;

        switch ($field) {
            case 'type':
                return $this->matchType($row, $operator, $value);
            case 'engagement_score':
                return $this->matchInteger((int) ($row['engagement_score'] ?? 0), $operator, $value, $field);
            case 'tags':
                return $this->matchTags($row['tags'] ?? null, $operator, $value);
            case 'last_opened_at':
                return $this->matchDate($row['last_opened_at'] ?? null, $operator, $value, $field);
            case 'created_at':
                return $this->matchDate($row['created_at'] ?? null, $operator, $value, $field);
            case 'gdpr_consent':
                if ($operator !== 'equal') {
                    throw new \InvalidArgumentException('Unsupported operator for gdpr_consent: ' . $operator);
                }
                return (int) ($row['gdpr_consent'] ?? 0) === $this->intValue($value, $field);
            case 'status':
                return $this->matchStatus($row, $operator, $value);
        }

        throw new \InvalidArgumentException('Unknown segment field: ' . $field);
    }

    private function matchType(array $row, string $operator, $value): bool
    {
        $wanted = strtolower((string) $value);
        if (!in_array($wanted, ['candidate', 'employer', 'guest'], true)) {
            throw new \InvalidArgumentException('Unknown user type in segment rule: ' . $wanted);
        }

        // The builder's "User Type" is the audience of the linked
        // account, classified exactly as the campaign presets and the
        // subscribers page classify it: guests are subscriber rows with
        // no account, candidates and employers come from the linked
        // user's user_type. The newsletter_subscribers.type column
        // cannot be used for this — no code path ever writes
        // 'candidate' or 'employer' into it, and 'guest' is not one of
        // its ENUM values.
        $audience = empty($row['user_id']) ? 'guest' : strtolower((string) ($row['user_type'] ?? ''));

        if ($operator === 'equal') {
            return $audience === $wanted;
        }
        if ($operator === 'not_equal') {
            return $audience !== $wanted;
        }

        throw new \InvalidArgumentException('Unsupported operator for type: ' . $operator);
    }

    private function matchInteger(int $actual, string $operator, $value, string $field): bool
    {
        switch ($operator) {
            case 'equal':
                return $actual === $this->intValue($value, $field);
            case 'less':
                return $actual < $this->intValue($value, $field);
            case 'less_or_equal':
                return $actual <= $this->intValue($value, $field);
            case 'greater':
                return $actual > $this->intValue($value, $field);
            case 'greater_or_equal':
                return $actual >= $this->intValue($value, $field);
            case 'between':
                if (!is_array($value) || count($value) !== 2) {
                    throw new \InvalidArgumentException('Segment rule for ' . $field . ' needs two values for "between".');
                }
                $values = array_values($value);
                return $actual >= $this->intValue($values[0], $field)
                    && $actual <= $this->intValue($values[1], $field);
        }

        throw new \InvalidArgumentException('Unsupported operator for ' . $field . ': ' . $operator);
    }

    private function intValue($value, string $field): int
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('Segment rule for ' . $field . ' needs a numeric value.');
        }

        return (int) $value;
    }

    private function matchTags($rawTags, string $operator, $value): bool
    {
        $tags = $this->tagList($rawTags);

        switch ($operator) {
            case 'is_empty':
                return $tags === [];
            case 'is_not_empty':
                return $tags !== [];
            case 'contains':
                return in_array($this->normaliseTag($value), $tags, true);
            case 'not_contains':
                return !in_array($this->normaliseTag($value), $tags, true);
        }

        throw new \InvalidArgumentException('Unsupported operator for tags: ' . $operator);
    }

    /**
     * The tags column is a JSON array per its migration, but no code in
     * the repo writes it, so a plain comma-separated string is also
     * accepted. Matching is case-insensitive on whole tags.
     */
    private function tagList($rawTags): array
    {
        if ($rawTags === null || trim((string) $rawTags) === '') {
            return [];
        }

        $decoded = json_decode((string) $rawTags, true);
        $items = is_array($decoded) ? $decoded : explode(',', (string) $rawTags);

        $tags = [];
        foreach ($items as $item) {
            if (is_scalar($item)) {
                $tag = $this->normaliseTag($item);
                if ($tag !== '') {
                    $tags[] = $tag;
                }
            }
        }

        return $tags;
    }

    private function normaliseTag($value): string
    {
        return strtolower(trim((string) $value));
    }

    private function matchDate($rawDate, string $operator, $value, string $field): bool
    {
        // A NULL date (e.g. a subscriber who has never opened an email)
        // cannot satisfy any date comparison.
        if ($rawDate === null || trim((string) $rawDate) === '') {
            return false;
        }
        $actual = substr((string) $rawDate, 0, 10);

        switch ($operator) {
            case 'equal':
                return $actual === $this->dateValue($value, $field);
            case 'less':
                return $actual < $this->dateValue($value, $field);
            case 'greater':
                return $actual > $this->dateValue($value, $field);
            case 'between':
                if (!is_array($value) || count($value) !== 2) {
                    throw new \InvalidArgumentException('Segment rule for ' . $field . ' needs two dates for "between".');
                }
                $values = array_values($value);
                return $actual >= $this->dateValue($values[0], $field)
                    && $actual <= $this->dateValue($values[1], $field);
        }

        throw new \InvalidArgumentException('Unsupported operator for ' . $field . ': ' . $operator);
    }

    private function dateValue($value, string $field): string
    {
        $raw = trim((string) $value);
        $date = \DateTime::createFromFormat('!Y-m-d', $raw);
        if ($date === false || $date->format('Y-m-d') !== $raw) {
            throw new \InvalidArgumentException('Segment rule for ' . $field . ' needs a date in Y-m-d format.');
        }

        return $raw;
    }

    private function matchStatus(array $row, string $operator, $value): bool
    {
        $wanted = strtolower((string) $value);
        if (!in_array($wanted, ['active', 'unsubscribed', 'bounced', 'complained', 'inactive'], true)) {
            throw new \InvalidArgumentException('Unknown subscriber status in segment rule: ' . $wanted);
        }

        $actual = strtolower((string) ($row['status'] ?? ''));

        if ($operator === 'equal') {
            return $actual === $wanted;
        }
        if ($operator === 'not_equal') {
            return $actual !== $wanted;
        }

        throw new \InvalidArgumentException('Unsupported operator for status: ' . $operator);
    }

    private function recipientName(array $row): string
    {
        $name = trim((string) ($row['name'] ?? ''));
        if ($name !== '') {
            return $name;
        }

        foreach (['seeker_name', 'company_name', 'username'] as $key) {
            if (!empty($row[$key])) {
                return (string) $row[$key];
            }
        }

        return '';
    }
}
