<?php

namespace App\Models;

use CodeIgniter\Model;

class AptitudeTestInvitationModel extends Model
{
    protected $table            = 'aptitude_test_invitations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employer_id',
        'candidate_id',
        'job_id',
        'application_id',
        'test_id',
        'attempt_id',
        'email',
        'code',
        'invitation_code',
        'message',
        'status',
        'due_date',
        'reminder_count',
        'last_reminder_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active invitation by token/code
     */
    public function getByCode(string $code)
    {
        $builder = $this->select('aptitude_test_invitations.*, tests.title as test_title, tests.slug as test_slug, tests.duration_mins, tests.num_questions, jobs.title as job_title, employers.company_name')
            ->join('tests', 'tests.id = aptitude_test_invitations.test_id', 'left')
            ->join('jobs', 'jobs.id = aptitude_test_invitations.job_id', 'left')
            ->join('employers', 'employers.id = aptitude_test_invitations.employer_id', 'left')
            ->groupStart()
                ->where('aptitude_test_invitations.invitation_code', $code);
        if ($this->db->fieldExists('code', $this->table)) {
            $builder->orWhere('aptitude_test_invitations.code', $code);
        }
        return $builder->groupEnd()->first();
    }

    /**
     * Get test invitations sent for a specific application/job
     */
    public function getForApplication(int $jobId, ?int $candidateId = null)
    {
        $builder = $this->select('aptitude_test_invitations.*, tests.title as test_title, tests.pass_threshold, test_attempts.score_pct, test_attempts.passed, test_attempts.status as attempt_status')
            ->join('tests', 'tests.id = aptitude_test_invitations.test_id', 'left')
            ->join('test_attempts', 'test_attempts.test_id = aptitude_test_invitations.test_id AND test_attempts.candidate_id = aptitude_test_invitations.candidate_id', 'left')
            ->where('aptitude_test_invitations.job_id', $jobId);

        if ($candidateId !== null) {
            $builder->where('aptitude_test_invitations.candidate_id', $candidateId);
        }

        return $builder->orderBy('aptitude_test_invitations.id', 'DESC')->findAll();
    }

    /**
     * Get all test invitations for a candidate with test, job, employer, and attempt details
     */
    public function getForCandidate(int $candidateUserId)
    {
        $db = \Config\Database::connect();
        $identity = $db->table('auth_identities')
            ->select('secret as email')
            ->where('user_id', $candidateUserId)
            ->where('type', 'email_password')
            ->get()->getRowArray();
        $userEmail = $identity['email'] ?? '';
        $hasRecipientEmail = $db->fieldExists('email', $this->table);

        $builder = $this->select('aptitude_test_invitations.*, tests.title as test_title, tests.slug as test_slug, tests.duration_mins, tests.num_questions, tests.pass_threshold, tests.difficulty, jobs.title as job_title, jobs.slug as job_slug, employers.company_name, employers.logo as company_logo, test_attempts.status as attempt_status, test_attempts.score_pct, test_attempts.passed, test_attempts.id as attempt_attempt_id, test_attempts.started_at as attempt_started_at, test_attempts.submitted_at as attempt_submitted_at, test_attempts.expires_at as attempt_expires_at')
            ->join('tests', 'tests.id = aptitude_test_invitations.test_id', 'left')
            ->join('jobs', 'jobs.id = aptitude_test_invitations.job_id', 'left')
            ->join('employers', 'employers.id = aptitude_test_invitations.employer_id', 'left')
            ->join('test_attempts', 'test_attempts.id = aptitude_test_invitations.attempt_id AND test_attempts.candidate_id = ' . (int)$candidateUserId, 'left')
            ->join('job_applications invitation_app', 'invitation_app.id = aptitude_test_invitations.application_id', 'left')
            ->join('job_seekers invitation_profile', 'invitation_profile.id = invitation_app.job_seeker_id', 'left')
            ->groupStart()
                ->where('aptitude_test_invitations.candidate_id', $candidateUserId)
                ->orGroupStart()
                    ->groupStart()->where('aptitude_test_invitations.candidate_id', 0)->orWhere('aptitude_test_invitations.candidate_id', null)->groupEnd()
                    ->groupStart()->where('invitation_profile.user_id', $candidateUserId);
        if ($userEmail !== '' && $hasRecipientEmail) {
            $builder->orWhere('LOWER(aptitude_test_invitations.email)', strtolower($userEmail));
        }
        $builder->groupEnd()->groupEnd()->groupEnd();
        // A conflicting recipient email must never be overridden by a numeric ID.
        if ($userEmail !== '' && $hasRecipientEmail) {
            $builder->groupStart()->where('aptitude_test_invitations.email', null)
                ->orWhere('aptitude_test_invitations.email', '')
                ->orWhere('LOWER(aptitude_test_invitations.email)', strtolower($userEmail))->groupEnd();
        }

        return $builder->orderBy('aptitude_test_invitations.id', 'DESC')->findAll();
    }
    public static function isPending(object $invitation, ?int $now = null): bool
    {
        if (in_array(strtolower((string) ($invitation->status ?? '')), ['completed', 'expired', 'cancelled', 'declined', 'revoked'], true)
            || ($invitation->attempt_status ?? '') === 'submitted') return false;
        $due = !empty($invitation->due_date) ? strtotime($invitation->due_date) : false;
        return $due === false || $due >= ($now ?? time());
    }

    public function countPendingForCandidate(int $candidateUserId): int
    {
        return count(array_filter($this->getForCandidate($candidateUserId), [self::class, 'isPending']));
    }

    public function belongsToUser(object $invitation, int $userId, string $email): bool
    {
        $recipient = strtolower(trim((string) ($invitation->email ?? '')));
        $email = strtolower(trim($email));
        if ($recipient !== '' && ($email === '' || $recipient !== $email)) return false;
        if ((int) $invitation->candidate_id > 0) {
            return (int) $invitation->candidate_id === $userId;
        }
        if ($recipient !== '' && $recipient === $email) return true;
        if (empty($invitation->application_id)) return false;
        return $this->db->table('job_applications a')->join('job_seekers js', 'js.id = a.job_seeker_id')
            ->where('a.id', $invitation->application_id)->where('js.user_id', $userId)->countAllResults() === 1;
    }

}
