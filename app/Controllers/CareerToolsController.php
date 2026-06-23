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

        $recentSessions = $this->mockInterviewSessionModel
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        return view('candidate/career-tools/mock-interview', [
            'title' => 'AI Mock Interview',
            'contextPreset' => $contextPreset,
            'recentSessions' => array_map(static function (array $session): array {
                $session['evaluation'] = json_decode((string) ($session['evaluation_json'] ?? ''), true) ?: [];
                return $session;
            }, $recentSessions),
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
        return view('candidate/career-tools/salary-negotiation', [
            'title' => 'Salary Negotiation Simulator'
        ]);
    }

    /**
     * Career Advice Interface
     */
    public function careerAdvice()
    {
        $candidate = $this->candidateModel->where('user_id', auth()->id())->first();
        $name = $candidate?->full_name ?? 'Candidate';
        $skills = $candidate?->skills ?? 'Not specified';
        $bio = $candidate?->bio ?? 'Not specified';

        $profile = "Name: {$name}, Skills: {$skills}, Bio: {$bio}";

        $advice = $this->aiService->getCareerAdvice($profile);
        
        // Clean up markdown formatting
        $advice = $this->cleanMarkdown($advice);

        return view('candidate/career-tools/career-advice', [
            'title' => 'AI Career Advice',
            'advice' => $advice
        ]);
    }
    
    /**
     * Clean markdown formatting from AI response
     */
    protected function cleanMarkdown($text)
    {
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
}
