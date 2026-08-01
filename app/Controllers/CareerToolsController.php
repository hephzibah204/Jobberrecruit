<?php

namespace App\Controllers;

use App\Models\JobApplicationModel;
use App\Models\JobModel;
use App\Models\JobSeekerModel;
use App\Models\MockInterviewSessionModel;
use App\Services\AiService;
use CodeIgniter\API\ResponseTrait;

class CareerToolsController extends BaseController
{
    use ResponseTrait;

    protected $aiService;
    protected $candidateModel;
    protected $mockInterviewSessionModel;
    protected $jobApplicationModel;
    protected $jobModel;

    public function __construct()
    {
        $this->aiService = new AiService();
        $this->candidateModel = new JobSeekerModel();
        $this->mockInterviewSessionModel = new MockInterviewSessionModel();
        $this->jobApplicationModel = new JobApplicationModel();
        $this->jobModel = new JobModel();
    }

    public function index()
    {
        return view('candidate/career-tools/index', [
            'title' => 'AI Career Tools'
        ]);
    }

    /**
     * Mock Interview Interface
     */
    public function mockInterview()
    {
        $applicationId = (int) ($this->request->getGet('application_id') ?? 0);
        $contextPreset = $this->buildApplicationInterviewContext($applicationId);
        $userId = (int) auth()->id();

        $recentSessions = $this->mockInterviewSessionModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // Calculate improvement percentage dynamically across recent sessions
        $improvementPct = 0;
        if (count($recentSessions) >= 2) {
            $latestScore = (float) ($recentSessions[0]['overall_score'] ?? 0);
            $oldestScore = (float) (end($recentSessions)['overall_score'] ?? 0);
            if ($oldestScore > 0) {
                $improvementPct = (int) round((($latestScore - $oldestScore) / $oldestScore) * 100);
            }
        }

        return view('candidate/career-tools/mock-interview', [
            'title' => 'AI Mock Interview',
            'contextPreset' => $contextPreset,
            'recentSessions' => array_map(static function (array $session): array {
                $session['evaluation'] = json_decode((string) ($session['evaluation_json'] ?? ''), true) ?: [];
                return $session;
            }, $recentSessions),
            'streak' => $this->calculateStreak($userId),
            'xp' => $this->calculateXp($userId),
            'todayDone' => $this->checkTodayGoal($userId),
            'improvementPct' => $improvementPct,
        ]);
    }

    /**
     * Start distraction-free AI Mock Interview session
     */
    public function startInterviewSession()
    {
        $applicationId = (int) ($this->request->getGet('application_id') ?? 0);
        
        $jobTitle = $this->request->getGet('job_title') ?? '';
        $difficulty = $this->request->getGet('difficulty') ?? 'medium';
        $questionPack = $this->request->getGet('question_pack') ?? 'general';
        $interviewMode = $this->request->getGet('interview_mode') ?? 'chat';
        $webcamEnabled = $this->request->getGet('webcam_enabled') === '1' || $this->request->getGet('webcam_enabled') === 'true';

        $contextPreset = [
            'job_title' => $jobTitle,
            'difficulty' => $difficulty,
            'question_pack' => $questionPack,
            'interview_mode' => $interviewMode,
            'webcam_enabled' => $webcamEnabled,
            'application_id' => $applicationId,
            
            // New options passed from the mockup
            'interview_type' => $this->request->getGet('itype') ?? '',
            'duration' => $this->request->getGet('dur') ?? '',
            'personality' => $this->request->getGet('persona') ?? '',
            'experience' => $this->request->getGet('exp') ?? '',
            'focus' => $this->request->getGet('focus') ?? '',
            'salary' => $this->request->getGet('salary') ?? '',
            'arrangement' => $this->request->getGet('arrangement') ?? '',
            'language' => $this->request->getGet('language') ?? '',
            'company_type' => $this->request->getGet('company') ?? '',
        ];

        if ($applicationId > 0) {
            $contextPreset = array_merge($contextPreset, $this->buildApplicationInterviewContext($applicationId));
        }

        return view('candidate/career-tools/mock-interview-session', [
            'title' => 'AI Mock Interview - Live Practice Session',
            'contextPreset' => $contextPreset,
            'bodyClass' => '',
        ]);
    }

    public function sendMessage()
    {
        $type = $this->request->getPost('type');
        $message = $this->request->getPost('message');
        $history = json_decode($this->request->getPost('history') ?? '[]', true);
        $extra = $this->request->getPost('extra');
        $applicationId = (int) ($this->request->getPost('applicationId') ?? 0);

        $options = [
            'job_title'      => (string) $extra,
            'difficulty'     => (string) ($this->request->getPost('difficulty') ?? 'medium'),
            'question_pack'  => (string) ($this->request->getPost('questionPack') ?? 'general'),
            'interview_mode' => (string) ($this->request->getPost('interviewMode') ?? 'chat'),
            'webcam_enabled' => (bool) $this->request->getPost('webcamEnabled'),
            'application_id' => $applicationId,
            
            // New options passed from mockup
            'interview_type' => (string) ($this->request->getPost('itype') ?? ''),
            'duration'       => (string) ($this->request->getPost('duration') ?? ''),
            'personality'    => (string) ($this->request->getPost('personality') ?? ''),
            'experience'     => (string) ($this->request->getPost('experience') ?? ''),
            'focus'          => (string) ($this->request->getPost('focus') ?? ''),
            'salary'         => (string) ($this->request->getPost('salary') ?? ''),
            'arrangement'    => (string) ($this->request->getPost('arrangement') ?? ''),
            'language'       => (string) ($this->request->getPost('language') ?? ''),
            'company_type'   => (string) ($this->request->getPost('company') ?? ''),
        ];

        if ($applicationId > 0) {
            $options = array_merge($options, $this->buildApplicationInterviewContext($applicationId));
        }

        if ($type === 'interview') {
            $candidate = $this->candidateModel->where('user_id', auth()->id())->first();
            $name = $candidate?->full_name ?? 'Candidate';
            $response = $this->aiService->getMockInterviewTurn($message, is_array($history) ? $history : [], $options, $name);
        } elseif ($type === 'negotiation') {
            $response = $this->aiService->getSalaryNegotiationResponse($message, $history, $extra);
        } else {
            return $this->fail('Invalid tool type');
        }

        return $this->respond(is_array($response) ? $response : [
            'message' => $response,
        ]);
    }

    public function evaluateInterview()
    {
        $history = json_decode($this->request->getPost('history') ?? '[]', true);
        $jobTitle = (string) ($this->request->getPost('jobTitle') ?? '');
        $applicationId = (int) ($this->request->getPost('applicationId') ?? 0);
        $difficulty = (string) ($this->request->getPost('difficulty') ?? 'medium');
        $questionPack = (string) ($this->request->getPost('questionPack') ?? 'general');
        $interviewMode = (string) ($this->request->getPost('interviewMode') ?? 'chat');
        $webcamEnabled = (bool) $this->request->getPost('webcamEnabled');
        $durationSeconds = (int) ($this->request->getPost('durationSeconds') ?? 0);

        if (! is_array($history) || $history === []) {
            return $this->failValidationErrors('Interview history is required.');
        }

        $candidate = $this->candidateModel->where('user_id', auth()->id())->first();
        $name = $candidate?->full_name ?? 'Candidate';
        $options = [
            'job_title'      => $jobTitle,
            'difficulty'     => $difficulty,
            'question_pack'  => $questionPack,
            'interview_mode' => $interviewMode,
            'webcam_enabled' => $webcamEnabled,
            'application_id' => $applicationId,
            
            // New options passed from mockup
            'interview_type' => (string) ($this->request->getPost('itype') ?? ''),
            'duration'       => (string) ($this->request->getPost('duration') ?? ''),
            'personality'    => (string) ($this->request->getPost('personality') ?? ''),
            'experience'     => (string) ($this->request->getPost('experience') ?? ''),
            'focus'          => (string) ($this->request->getPost('focus') ?? ''),
            'salary'         => (string) ($this->request->getPost('salary') ?? ''),
            'arrangement'    => (string) ($this->request->getPost('arrangement') ?? ''),
            'language'       => (string) ($this->request->getPost('language') ?? ''),
            'company_type'   => (string) ($this->request->getPost('company') ?? ''),
        ];

        if ($applicationId > 0) {
            $options = array_merge($options, $this->buildApplicationInterviewContext($applicationId));
        }

        $evaluation = $this->aiService->getMockInterviewEvaluation($history, $options, $name);

        $sessionData = [
            'application_id'   => $applicationId > 0 ? $applicationId : null,
            'user_id'          => (int) auth()->id(),
            'job_title'        => (string) ($options['job_title'] ?? $jobTitle),
            'difficulty'       => $difficulty,
            'question_pack'    => $questionPack,
            'interview_mode'   => $interviewMode,
            'webcam_enabled'   => $webcamEnabled ? 1 : 0,
            'duration_seconds' => max(0, $durationSeconds),
            'overall_score'    => (int) ($evaluation['overall_score'] ?? 0),
            'star_average'     => (int) ($evaluation['star_average'] ?? 0),
            'transcript_json'  => json_encode($history, JSON_UNESCAPED_UNICODE),
            'evaluation_json'  => json_encode($evaluation, JSON_UNESCAPED_UNICODE),
        ];

        $this->mockInterviewSessionModel->insert($sessionData);
        $sessionId = (int) $this->mockInterviewSessionModel->getInsertID();

        $evaluation['saved_session'] = [
            'id' => $sessionId,
            'job_title' => (string) ($options['job_title'] ?? $jobTitle),
            'difficulty' => $difficulty,
            'question_pack' => $questionPack,
            'interview_mode' => $interviewMode,
            'webcam_enabled' => $webcamEnabled,
            'duration_seconds' => max(0, $durationSeconds),
            'overall_score' => (int) ($evaluation['overall_score'] ?? 0),
            'star_average' => (int) ($evaluation['star_average'] ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->respond($evaluation);
    }

    /**
     * Build trusted interview context from the candidate's application.
     *
     * @return array<string, mixed>
     */
    protected function buildApplicationInterviewContext(int $applicationId): array
    {
        if ($applicationId <= 0) {
            return [];
        }

        $candidate = $this->candidateModel->where('user_id', auth()->id())->first();
        if (! $candidate) {
            return [];
        }

        $application = $this->jobApplicationModel
            ->where('id', $applicationId)
            ->where('job_seeker_id', $candidate->id)
            ->first();

        if (! $application) {
            return [];
        }

        $job = $this->jobModel
            ->select('jobs.id, jobs.title, jobs.description, jobs.skills, jobs.requirements, employers.company_name')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.id', $application->job_id)
            ->first();

        if (! $job) {
            return [];
        }

        return [
            'application_id'   => $applicationId,
            'job_id'           => (int) $job->id,
            'job_title'        => (string) $job->title,
            'company_name'     => (string) ($job->company_name ?? ''),
            'job_description'  => (string) ($job->description ?? ''),
            'job_requirements' => (string) ($job->requirements ?? ''),
            'job_skills'       => (string) ($job->skills ?? ''),
            'cv_path'          => (string) ($application->cv_path ?? ($candidate->resume ?? '')),
            'cover_letter'     => (string) ($application->cover_letter ?? ''),
            'candidate_profile' => $this->formatCandidateProfileSummary($candidate, $application),
            'summary_note'     => 'Interview feedback will use your submitted CV, cover letter, and saved candidate profile as the yardstick.',
        ];
    }

    /**
     * Create a compact candidate summary for the interview prompt.
     */
    protected function formatCandidateProfileSummary(object $candidate, object $application): string
    {
        $parts = array_filter([
            'Candidate target role: ' . (string) ($candidate->job_title ?? ''),
            'Skills: ' . (string) ($candidate->skills ?? ''),
            'Experience: ' . (string) ($candidate->experience_years ?? ''),
            'Education: ' . (string) ($candidate->education_level ?? ''),
            'Bio: ' . trim((string) ($candidate->bio ?? '')),
            'Cover letter: ' . trim((string) ($application->cover_letter ?? '')),
        ], static function ($value): bool {
            $value = trim((string) $value);
            return $value !== '' && substr($value, -1) !== ':';
        });

        return implode("\n", $parts);
    }

    /**
     * Salary Negotiation Interface
     */
    public function salaryNegotiation()
    {
        $userId = (int) auth()->id();
        $candidateObj = $this->candidateModel->where('user_id', $userId)->first();
        
        $candidate = [
            'firstName' => '',
            'targetPosition' => ''
        ];
        
        if ($candidateObj) {
            $candidate['firstName'] = explode(' ', trim($candidateObj->full_name))[0];
            $candidate['targetPosition'] = $candidateObj->job_title ?? '';
        }

        return view('candidate/career-tools/salary-negotiation', [
            'title' => 'Salary Negotiation Simulator',
            'candidate' => $candidate
        ]);
    }

    /**
     * Career Advice Interface (AI Career Coach)
     */
    public function careerAdvice()
    {
        $userId = (int) auth()->id();
        $candidate = $this->candidateModel->where('user_id', $userId)->first();
        $name = $candidate?->full_name ?? 'Candidate';
        $firstName = $candidate ? explode(' ', trim($candidate->full_name))[0] : 'Professional';
        $skills = $candidate?->skills ?? 'Not specified';
        $bio = $candidate?->bio ?? 'Not specified';
        $jobTitle = $candidate?->job_title ?? 'Product & Technology Professional';
        $experienceYears = (int) ($candidate?->experience_years ?? 3);

        // Fetch recent mock interview data
        $recentSessions = $this->mockInterviewSessionModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(12);

        $sessionsCount = count($recentSessions);
        $avgScore = 0;
        if ($sessionsCount > 0) {
            $scores = array_column($recentSessions, 'overall_score');
            $avgScore = round((array_sum($scores) / $sessionsCount) * 10, 0); // Convert scale 1-10 to 0-100
        }

        $streak = $this->calculateStreak($userId);
        $xp = $this->calculateXp($userId);
        $level = floor($xp / 500) + 1;

        // Profile completion heuristic
        $profileFields = [$candidate?->full_name, $candidate?->email, $candidate?->phone, $candidate?->job_title, $candidate?->skills, $candidate?->bio, $candidate?->resume];
        $filledFields = count(array_filter($profileFields, static fn($v) => !empty(trim((string)$v))));
        $profileCompletion = (int) round(($filledFields / count($profileFields)) * 100);

        // Career health score weighted calculation
        $careerHealth = (int) min(100, max(40, round(($avgScore * 0.4) + ($profileCompletion * 0.3) + (min(10, $sessionsCount) * 3) + 25)));

        $profileSummary = "Name: {$name}, Current Title: {$jobTitle}, Experience: {$experienceYears} years, Skills: {$skills}, Bio: {$bio}";

        $advice = $this->aiService->getCareerAdvice($profileSummary);
        $advice = $this->cleanMarkdown($advice);

        return view('candidate/career-tools/career-advice', [
            'title' => 'AI Career Coach',
            'advice' => $advice,
            'firstName' => $firstName,
            'candidate' => $candidate,
            'jobTitle' => $jobTitle,
            'experienceYears' => $experienceYears,
            'sessionsCount' => $sessionsCount,
            'avgScore' => $avgScore > 0 ? (int)$avgScore : 78,
            'streak' => $streak > 0 ? $streak : 4,
            'xp' => $xp,
            'level' => $level,
            'profileCompletion' => $profileCompletion,
            'careerHealth' => $careerHealth,
        ]);
    }
    
    /**
     * Clean markdown formatting from AI response
     */
    protected function cleanMarkdown($text)
    {
        // Escape raw HTML first so only the tags we intentionally introduce below
        // are rendered. AI output is seeded from user-supplied bio/skills, so this
        // prevents any HTML/script injection from reaching the view.
        $text = esc((string) $text);
        // Convert **bold** to <strong>
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
        // Convert *italic* to <em>
        $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);
        // Convert ### headers to <h3>
        $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
        // Convert ## headers to <h2>
        $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
        // Convert # headers to <h1>
        $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);
        // Remove standalone asterisks used as bullet points
        $text = preg_replace('/^\s*\*\s+/m', '', $text);
        // Remove multiple asterisks at start of lines
        $text = preg_replace('/^\s*\*+\s*/m', '', $text);
        
        return $text;
    }

    protected function calculateStreak(int $userId): int
    {
        $sessions = $this->mockInterviewSessionModel
            ->select('created_at')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if (empty($sessions)) {
            return 0;
        }

        $dates = [];
        foreach ($sessions as $s) {
            $dates[] = date('Y-m-d', strtotime($s['created_at']));
        }
        $dates = array_unique($dates);

        $streak = 0;
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        // If the user has not practiced today or yesterday, streak is broken
        if (!in_array($today, $dates) && !in_array($yesterday, $dates)) {
            return 0;
        }

        $current = in_array($today, $dates) ? $today : $yesterday;

        while (in_array($current, $dates)) {
            $streak++;
            $current = date('Y-m-d', strtotime($current . ' -1 day'));
        }

        return $streak;
    }

    protected function calculateXp(int $userId): int
    {
        // 200 XP per interview session
        $sessionsCount = $this->mockInterviewSessionModel
            ->where('user_id', $userId)
            ->countAllResults();

        // 100 XP per job application
        $jobApplicationModel = new \App\Models\JobApplicationModel();
        $appsCount = $jobApplicationModel
            ->where('user_id', $userId)
            ->countAllResults();

        // 150 XP per aptitude test attempt
        $testAttemptModel = new \App\Models\TestAttemptModel();
        $attemptsCount = $testAttemptModel
            ->where('user_id', $userId)
            ->countAllResults();

        return ($sessionsCount * 200) + ($appsCount * 100) + ($attemptsCount * 150);
    }

    protected function checkTodayGoal(int $userId): int
    {
        // Count mock sessions completed today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');

        $todaySessions = $this->mockInterviewSessionModel
            ->where('user_id', $userId)
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->countAllResults();

        return $todaySessions > 0 ? 1 : 0;
    }
}
