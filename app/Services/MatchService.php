<?php

namespace App\Services;

/**
 * Computes a transparent, deterministic candidate↔job match score (0–100).
 *
 * The score blends four signals:
 *   - Skills overlap        (60%)  candidate skills vs job skills/requirements
 *   - Industry alignment    (15%)  candidate industry vs job industry
 *   - Education alignment   (15%)  education level match
 *   - Experience signal     (10%)  years of experience present/relevant
 *
 * The result is clamped to 35–99 so sparse profiles never read as "0%" and no
 * match is ever presented as a guaranteed "100%".
 */
class MatchService
{
    public function score(object $candidate, object $job): int
    {
        $earned = 0.0;

        // ── Skills overlap (60) ──────────────────────────────────────────
        $candSkills = $this->tokenize($candidate->skills ?? '');
        $jobSkills  = $this->tokenize(trim(($job->skills ?? '') . ', ' . ($job->requirements ?? '')));

        if ($candSkills !== [] && $jobSkills !== []) {
            $matched = count(array_intersect($candSkills, $jobSkills));
            $ratio   = $matched / max(count($jobSkills), 1);
            // Soften so a handful of strong matches reads well, cap at 1.
            $earned += 60 * min(1.0, $ratio * 1.4);
        } else {
            $earned += 60 * 0.4; // neutral baseline when either side lacks skill data
        }

        // ── Industry alignment (15) ──────────────────────────────────────
        $candIndustry = $candidate->industry_id ?? null;
        $jobIndustry  = $job->industry_id ?? null;
        if (! empty($candIndustry) && ! empty($jobIndustry)) {
            $earned += ((int) $candIndustry === (int) $jobIndustry) ? 15 : 3;
        } else {
            $earned += 15 * 0.5;
        }

        // ── Education alignment (15) ─────────────────────────────────────
        $earned += 15 * $this->levelMatch((string) ($candidate->education_level ?? ''), (string) ($job->education_level ?? ''));

        // ── Experience signal (10) ───────────────────────────────────────
        $earned += 10 * $this->experienceSignal($candidate->experience_years ?? null);

        return max(35, min(99, (int) round($earned)));
    }

    /**
     * Convenience: score a list of jobs for one candidate and attach ->match_score.
     * Returns the same list (jobs mutated in place where they are objects).
     */
    public function scoreJobs(object $candidate, array $jobs): array
    {
        foreach ($jobs as $job) {
            if (is_object($job)) {
                $job->match_score = $this->score($candidate, $job);
            }
        }
        return $jobs;
    }

    private function tokenize(string $text): array
    {
        $text = strtolower($text);
        // Split on commas, slashes, and whitespace.
        $parts = preg_split('/[,\/\|]+|\s{2,}/', $text) ?: [];
        $tokens = [];
        foreach ($parts as $p) {
            $p = trim(preg_replace('/[^a-z0-9+#. ]/', '', $p));
            if ($p !== '' && strlen($p) >= 2 && ! in_array($p, ['and', 'the', 'for', 'with', 'you', 'are'], true)) {
                $tokens[] = $p;
            }
        }
        return array_values(array_unique($tokens));
    }

    private function levelMatch(string $a, string $b): float
    {
        $a = strtolower(trim($a));
        $b = strtolower(trim($b));
        if ($a === '' || $b === '') {
            return 0.6; // unknown on either side → neutral-positive
        }
        if ($a === $b) {
            return 1.0;
        }
        // Partial credit if one contains the other (e.g. "bachelor" vs "bachelor's degree").
        return (str_contains($a, $b) || str_contains($b, $a)) ? 0.8 : 0.4;
    }

    private function experienceSignal($years): float
    {
        $years = (int) $years;
        if ($years <= 0) {
            return 0.5;
        }
        return min(1.0, 0.6 + ($years * 0.1));
    }
}
