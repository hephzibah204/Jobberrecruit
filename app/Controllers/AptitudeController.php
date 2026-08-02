<?php

namespace App\Controllers;

use App\Models\TestModel;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use App\Models\TestAttemptModel;
use App\Models\AttemptAnswerModel;
use App\Models\CandidateTestResultModel;
use CodeIgniter\RESTful\ResourceController;

class AptitudeController extends BaseController
{
    /** Skill-practice test slugs (everything else is a role assessment). */
    private array $skillSlugs = ['numerical', 'verbal', 'logical', 'abstract', 'ict', 'general-aptitude'];

    public function index()
    {
        $userId = (int) auth()->id();
        $db = db_connect();

        // ── Catalog (with job category name) ──
        $tests = $db->table('tests t')
            ->select('t.id, t.slug, t.title, t.description, t.difficulty, t.num_questions, t.duration_mins, t.pass_threshold, jc.name AS category_name, jc.slug AS category_slug')
            ->join('job_categories jc', 'jc.id = t.category_id', 'left')
            ->where('t.is_active', 1)
            ->orderBy('t.id', 'ASC')
            ->get()->getResultArray();

        // ── This user's best score per test ──
        $bestByTest = [];
        foreach ($db->table('test_attempts')
                    ->select('test_id, MAX(score_pct) AS best, MAX(passed) AS passed, COUNT(*) AS attempts')
                    ->where('candidate_id', $userId)->where('status', 'submitted')
                    ->groupBy('test_id')->get()->getResultArray() as $b) {
            $bestByTest[(int) $b['test_id']] = $b;
        }

        $skillTests = [];
        $roleTests  = [];
        foreach ($tests as $t) {
            $t['best'] = $bestByTest[(int) $t['id']] ?? null;
            if (in_array($t['slug'], $this->skillSlugs, true)) {
                $skillTests[$t['slug']] = $t;      // keyed for ordered lookup
            } else {
                $roleTests[] = $t;
            }
        }
        // keep skill cards in the canonical order
        $orderedSkills = [];
        foreach ($this->skillSlugs as $s) {
            if (isset($skillTests[$s])) $orderedSkills[] = $skillTests[$s];
        }
        $skillTests = $orderedSkills;

        // ── Summary / analytics from real submitted attempts ──
        $attempts = $db->table('test_attempts ta')
            ->select('ta.score_pct, ta.submitted_at, ta.mode, t.title, t.slug')
            ->join('tests t', 't.id = ta.test_id', 'left')
            ->where('ta.candidate_id', $userId)->where('ta.status', 'submitted')
            ->orderBy('ta.submitted_at', 'ASC')
            ->get()->getResultArray();

        $testsTaken = count($attempts);
        $avgScore   = $testsTaken ? (int) round(array_sum(array_column($attempts, 'score_pct')) / $testsTaken) : 0;

        $summary = [
            'testsTaken' => $testsTaken,
            'avgScore'   => $avgScore,
            'streak'     => $this->computeStreak(array_column($attempts, 'submitted_at')),
            'byCategory' => $this->scoresByCategory($attempts),
            'trend'      => $this->weeklyTrend($attempts),
        ];

        // ── Recent history ──
        $history = $db->table('test_attempts ta')
            ->select('ta.id, ta.mode, ta.score_pct, ta.num_correct, ta.num_total, ta.submitted_at, t.title, t.slug')
            ->join('tests t', 't.id = ta.test_id', 'left')
            ->where('ta.candidate_id', $userId)->where('ta.status', 'submitted')
            ->orderBy('ta.submitted_at', 'DESC')->limit(12)
            ->get()->getResultArray();

        $leaderboard = $this->weeklyLeaderboard($db, $userId);

        return view('candidate/aptitude/hub', [
            'skillTests'  => $skillTests,
            'roleTests'   => $roleTests,
            'summary'     => $summary,
            'history'     => $history,
            'leaderboard' => $leaderboard,
        ]);
    }

    /** Start a free practice attempt for a test slug (optional ?level=). */
    public function practice($slug)
    {
        $level = $this->request->getGet('level');
        return $this->launchBySlug($slug, 'practice', is_string($level) ? strtolower($level) : null);
    }

    /** Start an official (verified) attempt for a test slug. */
    public function official($slug)
    {
        return $this->launchBySlug($slug, 'official', null);
    }

    /** Daily challenge — a mixed general-aptitude practice run. */
    public function daily()
    {
        return $this->launchBySlug('general-aptitude', 'practice', null);
    }

    /** Resolve a slug, create an attempt, and redirect into the test engine. */
    private function launchBySlug(string $slug, string $mode, ?string $level)
    {
        $userId = (int) auth()->id();
        if (!$userId) {
            return redirect()->to('/login');
        }

        $testModel = new TestModel();
        $test = $testModel->where('slug', $slug)->where('is_active', 1)->first();
        if (!$test) {
            return redirect()->to('/aptitude')->with('error', 'That test is not available yet.');
        }

        $attemptModel = new TestAttemptModel();

        // Official rate limit: once every 30 days per test
        if ($mode === 'official') {
            $last = $attemptModel->where('candidate_id', $userId)
                ->where('test_id', $test['id'])->where('mode', 'official')
                ->orderBy('started_at', 'DESC')->first();
            if ($last) {
                $days = (time() - strtotime($last['started_at'])) / 86400;
                if ($days < 30) {
                    return redirect()->to('/aptitude')->with('error', 'You can only take an official assessment for this test once every 30 days.');
                }
            }
        }

        $questionIds = $this->selectQuestionIds($test, $level);
        if (count($questionIds) < 1) {
            return redirect()->to('/aptitude')->with('error', 'This test has no questions yet. Please try another.');
        }

        $attemptId = $attemptModel->insert([
            'candidate_id' => $userId,
            'test_id'      => $test['id'],
            'mode'         => $mode,
            'status'       => 'in_progress',
            'started_at'   => date('Y-m-d H:i:s'),
            'expires_at'   => date('Y-m-d H:i:s', strtotime('+' . (int) $test['duration_mins'] . ' minutes')),
            'question_ids' => json_encode($questionIds),
        ]);

        return redirect()->to("/aptitude/test/{$attemptId}");
    }

    /** Pick question IDs for an attempt, filtering by difficulty level when useful. */
    private function selectQuestionIds(array $test, ?string $level): array
    {
        $db = db_connect();
        $rows = $db->table('questions')->select('id, difficulty')
            ->where('test_id', $test['id'])->where('is_active', 1)
            ->get()->getResultArray();

        $ids = array_column($rows, 'id');

        if ($level && in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
            $subset = array_column(array_filter($rows, static fn ($r) => $r['difficulty'] === $level), 'id');
            if (count($subset) >= 3) {
                $ids = $subset; // enough at this level to make a focused set
            }
        }

        shuffle($ids);
        $take = min((int) $test['num_questions'], count($ids));
        return array_slice($ids, 0, $take);
    }

    /** Longest run of consecutive days (ending today or yesterday) with a submitted attempt. */
    private function computeStreak(array $timestamps): int
    {
        if (!$timestamps) return 0;
        $days = [];
        foreach ($timestamps as $ts) {
            if ($ts) $days[date('Y-m-d', strtotime($ts))] = true;
        }
        if (!$days) return 0;

        $today = strtotime('today');
        // Streak only counts if the user practised today or yesterday
        if (!isset($days[date('Y-m-d', $today)]) && !isset($days[date('Y-m-d', $today - 86400)])) {
            return 0;
        }
        $streak = 0;
        for ($d = $today; ; $d -= 86400) {
            if (isset($days[date('Y-m-d', $d)])) {
                $streak++;
            } elseif ($d !== $today) { // allow starting from yesterday
                break;
            }
        }
        return $streak;
    }

    /** Average score per test title, highest first (drives the analytics bars). */
    private function scoresByCategory(array $attempts): array
    {
        $agg = [];
        foreach ($attempts as $a) {
            $label = $a['title'] ?? 'Unknown';
            $agg[$label][] = (float) $a['score_pct'];
        }
        $out = [];
        foreach ($agg as $label => $scores) {
            $out[] = ['label' => $label, 'score' => (int) round(array_sum($scores) / count($scores))];
        }
        usort($out, static fn ($x, $y) => $y['score'] <=> $x['score']);
        return $out;
    }

    /** Weekly average score for the last 8 weeks (oldest → newest). */
    private function weeklyTrend(array $attempts): array
    {
        $weeks = [];
        for ($i = 7; $i >= 0; $i--) {
            $weeks[date('oW', strtotime("-{$i} weeks"))] = [];
        }
        foreach ($attempts as $a) {
            if (!$a['submitted_at']) continue;
            $k = date('oW', strtotime($a['submitted_at']));
            if (isset($weeks[$k])) $weeks[$k][] = (float) $a['score_pct'];
        }
        $out = [];
        foreach ($weeks as $scores) {
            $out[] = $scores ? (int) round(array_sum($scores) / count($scores)) : null;
        }
        return $out;
    }

    /** Weekly leaderboard: points = sum of scores from submitted attempts this week. */
    private function weeklyLeaderboard($db, int $userId): array
    {
        $weekStart = date('Y-m-d 00:00:00', strtotime('monday this week'));

        $rows = $db->table('test_attempts ta')
            ->select('ta.candidate_id, COUNT(*) AS tests, ROUND(SUM(ta.score_pct)) AS points, js.full_name')
            ->join('job_seekers js', 'js.user_id = ta.candidate_id', 'left')
            ->where('ta.status', 'submitted')
            ->where('ta.submitted_at >=', $weekStart)
            ->groupBy('ta.candidate_id')
            ->orderBy('points', 'DESC')->limit(8)
            ->get()->getResultArray();

        $out = [];
        $rank = 0;
        foreach ($rows as $r) {
            $rank++;
            $out[] = [
                'rank'   => $rank,
                'name'   => ((int) $r['candidate_id'] === $userId) ? 'You' : $this->privacyName($r['full_name']),
                'tests'  => (int) $r['tests'],
                'points' => (int) $r['points'],
                'isMe'   => ((int) $r['candidate_id'] === $userId),
            ];
        }
        return $out;
    }

    /** "Chidera O." — first name + last initial only. */
    private function privacyName(?string $fullName): string
    {
        $fullName = trim((string) $fullName);
        if ($fullName === '') return 'Candidate';
        $parts = preg_split('/\s+/', $fullName);
        $first = $parts[0];
        $last  = count($parts) > 1 ? ' ' . strtoupper(substr(end($parts), 0, 1)) . '.' : '';
        return $first . $last;
    }

    public function testEngine($attemptId)
    {
        $attemptModel = new TestAttemptModel();
        $attempt = $attemptModel->find($attemptId);
        
        if (!$attempt || $attempt['candidate_id'] != auth()->id()) {
            return redirect()->to('/aptitude')->with('error', 'Test attempt not found or unauthorized.');
        }
        
        if ($attempt['status'] !== 'in_progress') {
            return redirect()->to("/aptitude/result/{$attemptId}");
        }

        $testModel = new TestModel();
        $test = $testModel->find($attempt['test_id']);

        return view('candidate/aptitude/test_engine', [
            'title'   => ($test['title'] ?? 'Aptitude Test') . ' — Test in progress',
            'attempt' => $attempt,
            'test'    => $test,
        ]);
    }

    public function result($attemptId)
    {
        $attemptModel = new TestAttemptModel();
        $attempt = $attemptModel->find($attemptId);

        if (!$attempt || $attempt['candidate_id'] != auth()->id()) {
            return redirect()->to('/aptitude')->with('error', 'Test result not found.');
        }

        if ($attempt['status'] !== 'submitted') {
            return redirect()->to('/aptitude/test/' . $attemptId);
        }

        $testModel = new TestModel();
        $test = $testModel->find($attempt['test_id']);

        $scorePct = (float) ($attempt['score_pct'] ?? 0);
        $passed   = (bool) ($attempt['passed'] ?? false);

        // Build per-question breakdown (question body, options, correct answer, what the candidate picked)
        $questionModel   = new QuestionModel();
        $optionModel     = new QuestionOptionModel();
        $answerModel     = new AttemptAnswerModel();

        $answersByQ = [];
        foreach ($answerModel->where('attempt_id', $attemptId)->findAll() as $a) {
            $answersByQ[(int) $a['question_id']] = $a;
        }

        $breakdown = [];
        foreach (json_decode($attempt['question_ids'], true) as $i => $qId) {
            $q = $questionModel->find($qId);
            if (!$q) continue;

            $options = $optionModel->where('question_id', $qId)->orderBy('id', 'ASC')->findAll();
            $selectedIds = [];
            if (isset($answersByQ[$qId]['selected_option_ids'])) {
                $selectedIds = json_decode($answersByQ[$qId]['selected_option_ids'], true) ?: [];
            }

            $breakdown[] = [
                'number'      => $i + 1,
                'body'        => $q['body'],
                'explanation' => $q['explanation'] ?? '',
                'is_correct'  => (bool) ($answersByQ[$qId]['is_correct'] ?? false),
                'options'     => array_map(static function ($opt) use ($selectedIds) {
                    return [
                        'body'      => $opt['body'],
                        'correct'   => (bool) $opt['is_correct'],
                        'chosen'    => in_array((int) $opt['id'], array_map('intval', $selectedIds), true),
                    ];
                }, $options),
            ];
        }

        // Percentile among other candidates who took this test (official mode only)
        $percentile = null;
        if ($attempt['mode'] === 'official') {
            $db = db_connect();
            $totalPeers = (int) $db->table('test_attempts')
                ->where('test_id', $attempt['test_id'])
                ->where('status', 'submitted')
                ->countAllResults();
            $lowerOrEqual = (int) $db->table('test_attempts')
                ->where('test_id', $attempt['test_id'])
                ->where('status', 'submitted')
                ->where('score_pct <=', $scorePct)
                ->countAllResults();
            // Percentile is only meaningful with a reasonable peer sample; otherwise a single
            // low-scoring attempt can mathematically compute as "top 1%".
            $percentile = $totalPeers >= 5 ? (int) round(($lowerOrEqual / $totalPeers) * 100) : null;
        }

        $viewData = [
            'title'      => ($test['title'] ?? 'Aptitude Test') . ' Results',
            'attempt'    => $attempt,
            'test'       => $test,
            'scorePct'   => $scorePct,
            'passed'     => $passed,
            'breakdown'  => $breakdown,
            'percentile' => $percentile,
        ];

        if ($attempt['mode'] === 'practice') {
            return view('candidate/aptitude/result_practice', $viewData);
        } else {
            return view('candidate/aptitude/result_official', $viewData);
        }
    }

    // --- API Endpoints ---

    public function startAttempt()
    {
        $userId = auth()->id();
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);

        $testId = $this->request->getPost('test_id');
        $mode = $this->request->getPost('mode') ?? 'practice';
        $employerRequired = $this->request->getPost('employer_required') ?? 0;
        $jobId = $this->request->getPost('job_id') ?? null;

        $testModel = new TestModel();
        $test = $testModel->find($testId);

        if (!$test) return $this->response->setJSON(['status' => 'error', 'message' => 'Test not found'])->setStatusCode(404);

        $attemptModel = new TestAttemptModel();

        // Rate limit for official mode (unless employer required)
        if ($mode === 'official' && !$employerRequired) {
            $lastOfficial = $attemptModel->where('candidate_id', $userId)
                                         ->where('test_id', $testId)
                                         ->where('mode', 'official')
                                         ->orderBy('started_at', 'DESC')
                                         ->first();
            if ($lastOfficial) {
                $daysSince = (time() - strtotime($lastOfficial['started_at'])) / (60 * 60 * 24);
                if ($daysSince < 30) {
                    return $this->response->setJSON([
                        'status' => 'error', 
                        'message' => 'Rate limit exceeded. You can only take an official test once every 30 days.'
                    ])->setStatusCode(429);
                }
            }
        }

        // Generate questions
        $questionModel = new QuestionModel();
        $allQuestions = $questionModel->where('test_id', $testId)->where('is_active', 1)->findAll();
        
        if (count($allQuestions) < $test['num_questions']) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not enough questions in bank.'])->setStatusCode(500);
        }

        shuffle($allQuestions);
        $selectedQuestions = array_slice($allQuestions, 0, $test['num_questions']);
        $questionIds = array_column($selectedQuestions, 'id');

        $startedAt = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', strtotime("+" . $test['duration_mins'] . " minutes"));

        $attemptData = [
            'candidate_id' => $userId,
            'test_id' => $testId,
            'mode' => $mode,
            'status' => 'in_progress',
            'started_at' => $startedAt,
            'expires_at' => $expiresAt,
            'question_ids' => json_encode($questionIds),
            'employer_required' => $employerRequired,
            'job_id' => $jobId
        ];

        $attemptId = $attemptModel->insert($attemptData);

        return $this->response->setJSON([
            'status' => 'success',
            'attempt_id' => $attemptId,
            'redirect' => "/aptitude/test/{$attemptId}"
        ]);
    }

    public function fetchTest($attemptId)
    {
        $userId = auth()->id();
        $attemptModel = new TestAttemptModel();
        $attempt = $attemptModel->find($attemptId);

        if (!$attempt || $attempt['candidate_id'] != $userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $questionIds = json_decode($attempt['question_ids'], true);
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        $questions = [];
        foreach ($questionIds as $qId) {
            $q = $questionModel->find($qId);
            if ($q) {
                $options = $optionModel->where('question_id', $qId)->findAll();
                shuffle($options); // Shuffle options
                
                // Strip out is_correct
                foreach ($options as &$opt) {
                    unset($opt['is_correct']);
                }

                $q['options'] = $options;
                // Never send explanation before submission
                unset($q['explanation']);
                
                $questions[] = $q;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'attempt' => $attempt,
            'questions' => $questions,
            'server_time' => time()
        ]);
    }

    public function saveAnswer($attemptId)
    {
        $userId = auth()->id();
        $attemptModel = new TestAttemptModel();
        $attempt = $attemptModel->find($attemptId);

        if (!$attempt || $attempt['candidate_id'] != $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(403);
        }

        $questionId = $this->request->getPost('question_id');
        $optionIds = json_encode($this->request->getPost('option_ids')); // Array of option IDs

        $answerModel = new AttemptAnswerModel();
        $existing = $answerModel->where('attempt_id', $attemptId)->where('question_id', $questionId)->first();

        if ($existing) {
            $answerModel->update($existing['id'], ['selected_option_ids' => $optionIds]);
        } else {
            $answerModel->insert([
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'selected_option_ids' => $optionIds
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function submitTest($attemptId)
    {
        $userId = auth()->id();
        $attemptModel = new TestAttemptModel();
        $attempt = $attemptModel->find($attemptId);

        if (!$attempt || $attempt['candidate_id'] != $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(403);
        }

        $tabSwitches = (int) $this->request->getPost('tab_switches');
        $isFlagged = ($tabSwitches >= 4) ? 1 : 0;

        $testModel = new TestModel();
        $test = $testModel->find($attempt['test_id']);

        $answerModel = new AttemptAnswerModel();
        $answers = $answerModel->where('attempt_id', $attemptId)->findAll();
        
        $optionModel = new QuestionOptionModel();
        
        $numCorrect = 0;
        $numTotal = count(json_decode($attempt['question_ids'], true));

        // Re-check timing
        $isLate = (time() > strtotime($attempt['expires_at']) + 10); // 10 sec grace period

        foreach ($answers as $ans) {
            $selectedOptIds = json_decode($ans['selected_option_ids'], true);
            $isCorrect = 0;
            
            if (!$isLate && !empty($selectedOptIds)) {
                // simple MCQ logic (1 correct option)
                $optId = $selectedOptIds[0]; 
                $option = $optionModel->find($optId);
                if ($option && $option['is_correct'] == 1) {
                    $isCorrect = 1;
                    $numCorrect++;
                }
            }

            $answerModel->update($ans['id'], ['is_correct' => $isCorrect]);
        }

        $scorePct = ($numTotal > 0) ? ($numCorrect / $numTotal) * 100 : 0;
        $passed = ($scorePct >= $test['pass_threshold']) ? 1 : 0;

        $attemptModel->update($attemptId, [
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
            'score_pct' => $scorePct,
            'passed' => $passed,
            'num_correct' => $numCorrect,
            'num_total' => $numTotal,
            'flagged' => $isFlagged
        ]);

        // Upsert CandidateTestResult if official
        if ($attempt['mode'] === 'official') {
            $resultModel = new CandidateTestResultModel();
            $existingResult = $resultModel->where('candidate_id', $userId)->where('test_id', $attempt['test_id'])->first();

            $showOnProfile = $passed ? 1 : 0;

            if ($existingResult) {
                if ($scorePct > $existingResult['best_score']) {
                    $resultModel->update($existingResult['id'], [
                        'best_score' => $scorePct,
                        'passed' => $passed,
                        'attempt_id' => $attemptId,
                        'achieved_at' => date('Y-m-d H:i:s')
                    ]);
                }
            } else {
                $resultModel->insert([
                    'candidate_id' => $userId,
                    'test_id' => $attempt['test_id'],
                    'best_score' => $scorePct,
                    'passed' => $passed,
                    'attempt_id' => $attemptId,
                    'show_on_profile' => $showOnProfile,
                    'achieved_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'redirect' => "/aptitude/result/{$attemptId}"
        ]);
    }
}
