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

        $contextPreset = array_merge($contextPreset, $this->buildCandidateContext());
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

        $options = array_merge($options, $this->buildCandidateContext());
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

    /**
     * Text-to-speech for the mock interview voice mode, via Gemini's native
     * TTS model instead of the browser's SpeechSynthesis API.
     */
    public function speak()
    {
        $text = trim((string) $this->request->getPost('text'));
        if ($text === '') {
            return $this->fail('Text is required');
        }

        // Keep prompts short — this is a spoken interviewer line, not an essay.
        $text = mb_substr(strip_tags($text), 0, 800);

        $voiceName = (string) ($this->request->getPost('voice') ?? 'Kore');
        $audioB64 = $this->aiService->textToSpeech($text, $voiceName);

        if (! $audioB64) {
            return $this->respond(['audio' => null], 503);
        }

        return $this->respond(['audio' => $audioB64, 'mime' => 'audio/wav']);
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

        $options = array_merge($options, $this->buildCandidateContext());
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
     * Always-on candidate qualifications/experience context, independent of
     * whether the interview is tied to a specific job application. Without
     * this, sessions started outside an application flow had no candidate
     * background and the AI fell back to generic questions.
     *
     * @return array<string, mixed>
     */
    protected function buildCandidateContext(): array
    {
        $candidate = $this->candidateModel->where('user_id', auth()->id())->first();
        if (! $candidate) {
            return [];
        }

        $parts = array_filter([
            'Target role: ' . (string) ($candidate->job_title ?? ''),
            'Skills: ' . (string) ($candidate->skills ?? ''),
            'Years of experience: ' . (string) ($candidate->experience_years ?? ''),
            'Education level: ' . (string) ($candidate->education_level ?? ''),
            'Bio: ' . trim((string) ($candidate->bio ?? '')),
        ], static function ($value): bool {
            $value = trim((string) $value);
            return $value !== '' && substr($value, -1) !== ':';
        });

        $historyDetails = $this->getHistoryDetails($candidate);
        if ($historyDetails !== '') {
            $parts[] = $historyDetails;
        }

        if ($parts === []) {
            return [];
        }

        return [
            'candidate_job_title' => (string) ($candidate->job_title ?? ''),
            'candidate_profile'   => implode("\n", $parts),
        ];
    }

    /**
     * Fetch and format candidate's detailed experience and education history.
     */
    protected function getHistoryDetails(object $candidate): string
    {
        $expModel = new \App\Models\JobSeekerExperienceModel();
        $eduModel = new \App\Models\JobSeekerEducationModel();
        
        $experiences = $expModel->forSeeker((int) $candidate->id);
        $education = $eduModel->forSeeker((int) $candidate->id);
        
        $lines = [];
        if (!empty($experiences)) {
            $lines[] = "\nDetailed Work Experience & History:";
            foreach ($experiences as $exp) {
                $end = $exp->is_current ? 'Present' : $exp->end_date;
                $lines[] = "- Role: {$exp->job_title} at {$exp->company} ({$exp->start_date} to {$end})\n  Responsibilities & Achievements: " . trim((string) ($exp->description ?? ''));
            }
        }
        
        if (!empty($education)) {
            $lines[] = "\nDetailed Education & Qualifications:";
            foreach ($education as $edu) {
                $gradeStr = !empty($edu->grade) ? " (Grade: {$edu->grade})" : '';
                $lines[] = "- {$edu->degree} in {$edu->field_of_study} from {$edu->school} ({$edu->start_year} to {$edu->end_year}){$gradeStr}";
            }
        }
        
        return implode("\n", $lines);
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

        $historyDetails = $this->getHistoryDetails($candidate);
        if ($historyDetails !== '') {
            $parts[] = $historyDetails;
        }

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

        // Reuse streak/XP helpers from mock interview
        $negoModel = new \App\Models\SalaryNegotiationSessionModel();
        $recentSessions = $negoModel->getRecentSessions($userId, 10);
        $streak = $negoModel->calculateStreak($userId);
        $xp = $this->calculateXp($userId);

        // Weekly negotiation goal (target: 2 per week)
        $weeklyGoalTarget = 2;
        $weeklyDone = $negoModel->weeklySessionsCount($userId);
        $weeklyPct = $weeklyGoalTarget > 0 ? min(100, (int) round(($weeklyDone / $weeklyGoalTarget) * 100)) : 0;

        // Best score and averages
        $bestScore = $negoModel->bestScore($userId);
        $avgScores = $negoModel->averageScores($userId);
        $persuasionTrend = $negoModel->persuasionTrend($userId);

        // Format recent sessions for the history rail
        $history = array_map(static function (object $s) use ($userId): array {
            $eval = json_decode((string) ($s->evaluation_json ?? ''), true) ?: [];
            return [
                'id'               => (int) $s->id,
                'job_title'        => (string) ($s->job_title ?? 'Negotiation'),
                'difficulty'       => (string) ($s->difficulty ?? 'medium'),
                'overall_score'    => (int) ($s->overall_score ?? 0),
                'persuasion_score' => (int) ($s->persuasion_score ?? 0),
                'confidence_score' => (int) ($s->confidence_score ?? 0),
                'outcome'          => (string) ($s->outcome ?? ''),
                'created_at'       => date('d M', strtotime($s->created_at)),
            ];
        }, $recentSessions);

        return view('candidate/career-tools/salary-negotiation', [
            'title'            => 'Salary Negotiation Simulator',
            'candidate'        => $candidate,
            'recentSessions'   => $history,
            'streak'           => $streak,
            'xp'               => $xp,
            'weeklyDone'       => $weeklyDone,
            'weeklyGoalTarget' => $weeklyGoalTarget,
            'weeklyPct'        => $weeklyPct,
            'bestScore'        => $bestScore,
            'avgScores'        => $avgScores,
            'persuasionTrend'  => $persuasionTrend,
        ]);
    }

    /**
     * AJAX: persist a finished salary-negotiation session — without this, history/streak/XP
     * on the salary-negotiation page can never show real data (the whole flow runs client-side).
     */
    public function saveNegotiationSession()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $userId = (int) auth()->id();

        $data = [
            'user_id'              => $userId,
            'job_title'            => (string) $this->request->getPost('job_title'),
            'base_salary_offered'  => (float) $this->request->getPost('base_salary_offered'),
            'target_salary'        => (float) $this->request->getPost('target_salary'),
            'final_salary'         => (float) $this->request->getPost('final_salary'),
            'recruiter_style'      => (string) $this->request->getPost('recruiter_style'),
            'difficulty'           => (string) $this->request->getPost('difficulty'),
            'rounds_completed'     => (int) $this->request->getPost('rounds_completed'),
            'confidence_score'     => (int) $this->request->getPost('confidence_score'),
            'persuasion_score'     => (int) $this->request->getPost('persuasion_score'),
            'overall_score'        => (int) $this->request->getPost('overall_score'),
            'outcome'              => (string) $this->request->getPost('outcome'),
            'transcript_json'      => (string) $this->request->getPost('transcript_json'),
            'evaluation_json'      => (string) $this->request->getPost('evaluation_json'),
        ];

        if (empty($data['job_title'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing session data']);
        }

        $negoModel = new \App\Models\SalaryNegotiationSessionModel();
        $id = $negoModel->insert($data);

        return $this->response->setJSON(['success' => (bool) $id, 'id' => $id]);
    }

    /**
     * Career Advice Interface (AI Career Coach)
     */
    /**
     * Career Advice Interface (AI Career Coach)
     */
    public function careerAdvice()
    {
        $userId = (int) auth()->id();
        $candidate = $this->candidateModel->where('user_id', $userId)->first();
        $candidateId = (int) ($candidate?->id ?? 0);

        $name = $candidate?->full_name ?? 'Candidate';
        $firstName = $candidate && !empty($candidate->full_name) ? explode(' ', trim($candidate->full_name))[0] : 'Candidate';
        $skills = $candidate?->skills ?? 'Not specified';
        $bio = $candidate?->bio ?? 'Not specified';
        $jobTitle = $candidate?->job_title ?? 'Job Seeker / Professional';
        $experienceYears = (int) ($candidate?->experience_years ?? 1);

        // 1. Fetch Work Experience & Education counts
        $expModel = new \App\Models\JobSeekerExperienceModel();
        $eduModel = new \App\Models\JobSeekerEducationModel();
        $expCount = $candidateId > 0 ? $expModel->where('job_seeker_id', $candidateId)->countAllResults() : 0;
        $eduCount = $candidateId > 0 ? $eduModel->where('job_seeker_id', $candidateId)->countAllResults() : 0;

        // 2. Fetch Mock Interview Sessions
        $recentSessions = $this->mockInterviewSessionModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(12);

        $sessionsCount = count($recentSessions);
        $avgInterviewScore = 0;
        if ($sessionsCount > 0) {
            $scoresList = array_column($recentSessions, 'overall_score');
            $avgInterviewScore = (int) round((array_sum($scoresList) / $sessionsCount) * 10); // scale 0-100
        }

        // 3. Fetch Aptitude Test Attempts
        $testAttemptModel = new \App\Models\TestAttemptModel();
        $testAttempts = $testAttemptModel->where('candidate_id', $userId)->where('status', 'completed')->findAll();
        $testAttemptsCount = count($testAttempts);
        $avgTestScore = 0;
        if ($testAttemptsCount > 0) {
            $testScoresList = array_column($testAttempts, 'score_pct');
            $avgTestScore = (int) round(array_sum($testScoresList) / $testAttemptsCount);
        }

        // 4. Fetch Job Applications
        $appsCount = $candidateId > 0 ? $this->jobApplicationModel->where('job_seeker_id', $candidateId)->countAllResults() : 0;
        $shortlistedCount = $candidateId > 0 ? $this->jobApplicationModel->where('job_seeker_id', $candidateId)->whereIn('status', ['shortlisted', 'interviewed', 'offered'])->countAllResults() : 0;

        // 5. Compute Profile Completion
        $profileCompletion = $candidate ? $candidate->getProfileCompletion() : 0;

        // 6. Calculate 6 Metric Scores
        // 6a. Resume Strength
        $hasResume = !empty($candidate?->resume);
        $hasBio = !empty(trim((string)$candidate?->bio));
        $hasSkills = !empty(trim((string)$candidate?->skills));
        $resumeScore = ($hasResume ? 30 : 0) + ($expCount > 0 ? 25 : ($hasBio ? 10 : 0)) + ($eduCount > 0 ? 25 : 10) + ($hasSkills ? 10 : 0) + ($hasBio ? 10 : 0);
        $resumeScore = min(100, max(20, $resumeScore));
        $resumeSource = $hasResume ? 'from your uploaded CV' : 'from profile summary';

        // 6b. Interview Readiness
        if ($sessionsCount > 0) {
            $interviewScore = $avgInterviewScore;
            $interviewSource = "from {$sessionsCount} scored mock sessions";
        } else {
            $interviewScore = min(75, max(30, ($experienceYears * 10) + 30));
            $interviewSource = "estimated from experience profile";
        }

        // 6c. Technical Skills
        if ($testAttemptsCount > 0) {
            $techScore = $avgTestScore;
            $techSource = "from {$testAttemptsCount} aptitude test attempts";
        } else {
            $skillArr = !empty($skills) && $skills !== 'Not specified' ? array_filter(explode(',', $skills)) : [];
            $skillCount = count($skillArr);
            $techScore = min(85, max(35, ($skillCount * 12) + ($experienceYears * 5)));
            $techSource = $skillCount > 0 ? "from {$skillCount} listed skills" : "add skills to improve";
        }

        // 6d. Market Position
        if ($appsCount > 0) {
            $marketScore = min(100, max(30, 45 + ($appsCount * 5) + ($shortlistedCount * 15)));
            $marketSource = "from {$appsCount} role applications";
        } else {
            $marketScore = max(30, min(65, (int) round($profileCompletion * 0.6)));
            $marketSource = "apply to jobs to boost positioning";
        }

        // 6e. Online Presence
        $hasPic = !empty($candidate?->profile_picture);
        $hasLinkedin = !empty($candidate?->linkedin_url);
        $hasPortfolio = !empty($candidate?->portfolio);
        $hasPhoneLoc = !empty($candidate?->phone) && (!empty($candidate?->location) || !empty($candidate?->state_id));
        $presenceScore = ($hasPic ? 25 : 0) + ($hasLinkedin ? 30 : 0) + ($hasPortfolio ? 25 : 0) + ($hasPhoneLoc ? 20 : 0);
        $presenceScore = min(100, max(25, $presenceScore));
        $presenceSource = ($hasLinkedin || $hasPortfolio) ? "from portfolio & social links" : "add portfolio & LinkedIn links";

        // 6f. Soft Skills
        if ($sessionsCount > 0) {
            $softScores = array_map(static function ($s) {
                $eval = json_decode((string)($s['evaluation_json'] ?? ''), true) ?: [];
                return (int)($eval['soft_skills_score'] ?? (($s['overall_score'] ?? 7) * 10));
            }, $recentSessions);
            $softScore = (int) round(array_sum($softScores) / count($softScores));
            $softSource = "from mock session feedback";
        } else {
            $softScore = min(88, max(45, 55 + ($experienceYears * 5)));
            $softSource = "estimated from work background";
        }

        // 7. Overall Career Health
        $careerHealth = (int) min(100, max(30, round(($resumeScore * 0.25) + ($interviewScore * 0.25) + ($techScore * 0.20) + ($marketScore * 0.15) + ($presenceScore * 0.15))));

        // Market Readiness string
        if ($careerHealth >= 80) {
            $marketReadiness = 'High Market Fit';
        } elseif ($careerHealth >= 65) {
            $marketReadiness = 'Interview Ready';
        } elseif ($careerHealth >= 45) {
            $marketReadiness = 'In Motion';
        } else {
            $marketReadiness = 'Building Foundation';
        }

        // 8. Streak & XP
        $streak = $this->calculateStreak($userId);
        $xp = $this->calculateXp($userId);
        $level = (int) floor($xp / 500) + 1;

        // 9. Today's Career Plan & Progress
        $todayStart = date('Y-m-d 00:00:00');
        $todayMockDone = $this->mockInterviewSessionModel
            ->where('user_id', $userId)
            ->where('created_at >=', $todayStart)
            ->countAllResults() > 0;

        $todayPlan = [
            ['text' => 'Complete an AI Interview Practice session', 'mins' => 15, 'done' => $todayMockDone],
            ['text' => 'Ensure Resume PDF is uploaded & updated', 'mins' => 10, 'done' => $hasResume],
            ['text' => 'Add work experience & education details', 'mins' => 15, 'done' => ($expCount > 0 && $eduCount > 0)],
            ['text' => 'Take a JobberRecruit Aptitude Assessment', 'mins' => 20, 'done' => ($testAttemptsCount > 0)],
            ['text' => 'Apply to open matching job vacancies', 'mins' => 10, 'done' => ($appsCount > 0)],
        ];

        $todayDone = count(array_filter($todayPlan, static fn($t) => !empty($t['done'])));
        $todayTotal = count($todayPlan);
        $todayMinutesLeft = array_sum(array_column(array_filter($todayPlan, static fn($t) => empty($t['done'])), 'mins'));

        // 10. Six Scores Meter Array
        $scoresArray = [
            ['label' => 'Resume Strength',    'value' => $resumeScore,    'source' => $resumeSource,    'tone' => $resumeScore >= 75 ? 'good' : ($resumeScore < 50 ? 'warn' : '')],
            ['label' => 'Interview Readiness','value' => $interviewScore, 'source' => $interviewSource, 'tone' => $interviewScore >= 75 ? 'good' : ($interviewScore < 50 ? 'warn' : '')],
            ['label' => 'Technical Skills',   'value' => $techScore,      'source' => $techSource,      'tone' => $techScore >= 75 ? 'good' : ($techScore < 50 ? 'warn' : '')],
            ['label' => 'Market Position',    'value' => $marketScore,    'source' => $marketSource,    'tone' => $marketScore >= 75 ? 'good' : ($marketScore < 50 ? 'warn' : '')],
            ['label' => 'Online Presence',    'value' => $presenceScore,  'source' => $presenceSource,  'tone' => $presenceScore >= 75 ? 'good' : ($presenceScore < 50 ? 'warn' : '')],
            ['label' => 'Soft Skills',        'value' => $softScore,      'source' => $softSource,      'tone' => $softScore >= 75 ? 'good' : ($softScore < 50 ? 'warn' : '')],
        ];

        // AI Advice Prompt
        $profileSummary = "Name: {$name}, Current Title: {$jobTitle}, Experience: {$experienceYears} years, Skills: {$skills}, Profile Completion: {$profileCompletion}%, Resume: " . ($hasResume ? 'Uploaded' : 'Missing') . ", Work History Entries: {$expCount}, Education Entries: {$eduCount}, Applications: {$appsCount}";

        $advice = $this->aiService->getCareerAdvice($profileSummary);
        $advice = $this->cleanMarkdown($advice);

        return view('candidate/career-tools/career-advice', [
            'title'              => 'AI Career Coach',
            'advice'             => $advice,
            'firstName'          => $firstName,
            'candidate'          => $candidate,
            'jobTitle'           => $jobTitle,
            'experienceYears'    => $experienceYears,
            'sessionsCount'      => $sessionsCount,
            'avgScore'           => $interviewScore,
            'streak'             => $streak,
            'xp'                 => $xp,
            'level'              => $level,
            'profileCompletion'  => $profileCompletion,
            'careerHealth'       => $careerHealth,
            'marketReadiness'    => $marketReadiness,
            'scores'             => $scoresArray,
            'todayPlan'          => $todayPlan,
            'todayDone'          => $todayDone,
            'todayTotal'         => $todayTotal,
            'todayMinutesLeft'   => $todayMinutesLeft,
        ]);
    }

    /**
     * AJAX: confirms the AI service can produce advice before the page reloads and
     * regenerates it fresh — careerAdvice() always calls the AI on every load, so this
     * endpoint's only job is to surface a real error instead of a silent one on reload.
     */
    public function generateAdvice()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $userId = (int) auth()->id();
        $candidate = $this->candidateModel->where('user_id', $userId)->first();
        $profileSummary = "Name: " . ($candidate?->full_name ?? 'Candidate')
            . ", Current Title: " . ($candidate?->job_title ?? 'Professional')
            . ", Experience: " . ($candidate?->experience_years ?? 0) . " years"
            . ", Skills: " . ($candidate?->skills ?? 'Not specified')
            . ", Bio: " . ($candidate?->bio ?? 'Not specified');

        try {
            $this->aiService->getCareerAdvice($profileSummary);
            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $e) {
            log_message('error', 'Career advice generation failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Could not generate new advice right now. Please try again.']);
        }
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

        $candidateId = (int) ($this->candidateModel->where('user_id', $userId)->first()->id ?? 0);

        // 100 XP per job application (job_applications is keyed by job_seeker_id, not user_id)
        $appsCount = 0;
        if ($candidateId > 0) {
            $jobApplicationModel = new \App\Models\JobApplicationModel();
            $appsCount = $jobApplicationModel
                ->where('job_seeker_id', $candidateId)
                ->countAllResults();
        }

        // 150 XP per aptitude test attempt (test_attempts.candidate_id actually stores the auth user id, see AptitudeController)
        $testAttemptModel = new \App\Models\TestAttemptModel();
        $attemptsCount = $testAttemptModel
            ->where('candidate_id', $userId)
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
