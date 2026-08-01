<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaryNegotiationSessionModel extends Model
{
    protected $table            = 'salary_negotiation_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'job_title',
        'base_salary_offered',
        'target_salary',
        'final_salary',
        'recruiter_style',
        'difficulty',
        'rounds_completed',
        'confidence_score',
        'persuasion_score',
        'overall_score',
        'outcome',
        'transcript_json',
        'evaluation_json',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = false;

    /**
     * Get recent sessions for a user, ordered by most recent.
     */
    public function getRecentSessions(int $userId, int $limit = 10): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    /**
     * Calculate negotiation streak (consecutive days with at least one session).
     */
    public function calculateStreak(int $userId): int
    {
        $sessions = $this->select('created_at')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if (empty($sessions)) {
            return 0;
        }

        $dates = [];
        foreach ($sessions as $s) {
            $dates[] = date('Y-m-d', strtotime($s->created_at));
        }
        $dates = array_unique($dates);

        $streak  = 0;
        $today   = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        if (! in_array($today, $dates) && ! in_array($yesterday, $dates)) {
            return 0;
        }

        $current = in_array($today, $dates) ? $today : $yesterday;

        while (in_array($current, $dates)) {
            $streak++;
            $current = date('Y-m-d', strtotime($current . ' -1 day'));
        }

        return $streak;
    }

    /**
     * Count sessions completed this week (Mon–Sun).
     */
    public function weeklySessionsCount(int $userId): int
    {
        $weekStart = date('Y-m-d 00:00:00', strtotime('monday this week'));

        return $this->where('user_id', $userId)
            ->where('created_at >=', $weekStart)
            ->countAllResults();
    }

    /**
     * Get the user's best (highest) overall score.
     */
    public function bestScore(int $userId): int
    {
        $row = $this->selectMax('overall_score')
            ->where('user_id', $userId)
            ->first();

        return (int) ($row->overall_score ?? 0);
    }

    /**
     * Get average confidence and persuasion across recent sessions.
     */
    public function averageScores(int $userId, int $limit = 5): array
    {
        $sessions = $this->select('confidence_score, persuasion_score, overall_score')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);

        if (empty($sessions)) {
            return ['confidence' => 0, 'persuasion' => 0, 'overall' => 0];
        }

        $conf   = 0;
        $pers   = 0;
        $overall = 0;
        $count   = count($sessions);

        foreach ($sessions as $s) {
            $conf    += (float) ($s->confidence_score ?? 0);
            $pers    += (float) ($s->persuasion_score ?? 0);
            $overall += (float) ($s->overall_score ?? 0);
        }

        return [
            'confidence' => round($conf / $count),
            'persuasion' => round($pers / $count),
            'overall'    => round($overall / $count),
        ];
    }

    /**
     * Calculate persuasion trend: compare last 3 vs previous 3 sessions.
     */
    public function persuasionTrend(int $userId): int
    {
        $sessions = $this->select('persuasion_score')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(6);

        if (count($sessions) < 2) {
            return 0;
        }

        $recent  = array_slice($sessions, 0, min(3, count($sessions)));
        $older   = array_slice($sessions, min(3, count($sessions)));

        $recentAvg = array_sum(array_map(fn($s) => (float) ($s->persuasion_score ?? 0), $recent)) / count($recent);
        $olderAvg  = empty($older) ? $recentAvg : array_sum(array_map(fn($s) => (float) ($s->persuasion_score ?? 0), $older)) / count($older);

        if ($olderAvg <= 0) {
            return 0;
        }

        return (int) round($recentAvg - $olderAvg);
    }
}
