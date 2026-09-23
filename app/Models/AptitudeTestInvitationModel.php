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
        'test_id',
        'email',
        'code',
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
        return $this->select('aptitude_test_invitations.*, tests.title as test_title, tests.slug as test_slug, tests.duration_mins, tests.num_questions, jobs.title as job_title, employers.company_name')
            ->join('tests', 'tests.id = aptitude_test_invitations.test_id', 'left')
            ->join('jobs', 'jobs.id = aptitude_test_invitations.job_id', 'left')
            ->join('employers', 'employers.id = aptitude_test_invitations.employer_id', 'left')
            ->where('aptitude_test_invitations.code', $code)
            ->first();
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
        return $this->select('aptitude_test_invitations.*, tests.title as test_title, tests.slug as test_slug, tests.duration_mins, tests.num_questions, tests.pass_threshold, tests.difficulty, jobs.title as job_title, jobs.slug as job_slug, employers.company_name, employers.logo as company_logo, test_attempts.status as attempt_status, test_attempts.score_pct, test_attempts.passed, test_attempts.id as attempt_attempt_id, test_attempts.started_at as attempt_started_at, test_attempts.submitted_at as attempt_submitted_at, test_attempts.expires_at as attempt_expires_at')
            ->join('tests', 'tests.id = aptitude_test_invitations.test_id', 'left')
            ->join('jobs', 'jobs.id = aptitude_test_invitations.job_id', 'left')
            ->join('employers', 'employers.id = aptitude_test_invitations.employer_id', 'left')
            ->join('test_attempts', 'test_attempts.test_id = aptitude_test_invitations.test_id AND test_attempts.candidate_id = aptitude_test_invitations.candidate_id', 'left')
            ->where('aptitude_test_invitations.candidate_id', $candidateUserId)
            ->orderBy('aptitude_test_invitations.id', 'DESC')
            ->findAll();
    }
}
