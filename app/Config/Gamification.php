<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Career Gamification & XP Configuration
 *
 * Configures the point weights, reward amounts, and level progression
 * thresholds across candidate career tools (Mock Interview, Salary Negotiation,
 * Aptitude Tests, and Job Applications).
 */
class Gamification extends BaseConfig
{
    /**
     * XP awarded per completed AI mock interview session.
     */
    public int $xpPerMockInterview = 200;

    /**
     * XP awarded per submitted job application.
     */
    public int $xpPerJobApplication = 100;

    /**
     * XP awarded per completed aptitude test attempt.
     */
    public int $xpPerAptitudeTest = 150;

    /**
     * XP awarded per completed salary negotiation practice session.
     */
    public int $xpPerSalaryNegotiation = 150;

    /**
     * XP threshold required per level (linear leveling curve).
     */
    public int $xpPerLevel = 500;

    /**
     * Calculate candidate level, next level threshold, needed XP, and progress percentage.
     *
     * @param int $xp Total accumulated XP
     * @return array{level: int, next_level: int, next_level_threshold: int, needed_xp: int, progress_pct: int}
     */
    public function getLevelData(int $xp): array
    {
        $step = max(100, $this->xpPerLevel);
        $currentXp = max(0, $xp);

        $level = (int) floor($currentXp / $step) + 1;
        $nextLevelThreshold = $level * $step;
        $neededXp = max(0, $nextLevelThreshold - $currentXp);
        $levelBaseXp = ($level - 1) * $step;
        $progressPct = min(100, (int) round((($currentXp - $levelBaseXp) / $step) * 100));

        return [
            'level'                => $level,
            'next_level'           => $level + 1,
            'next_level_threshold' => $nextLevelThreshold,
            'needed_xp'            => $neededXp,
            'progress_pct'         => $progressPct,
        ];
    }

    /**
     * Helper to get just the candidate level integer.
     */
    public function calculateLevel(int $xp): int
    {
        return $this->getLevelData($xp)['level'];
    }
}
