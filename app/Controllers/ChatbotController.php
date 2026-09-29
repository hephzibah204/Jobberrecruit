<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\AiService;
use App\Models\JobSeekerModel;
use App\Models\EmployerModel;
use App\Models\JobApplicationModel;
use App\Models\SavedJobModel;
use App\Models\JobModel;
use App\Models\MessageModel;
use App\Models\WalletModel;

class ChatbotController extends BaseController
{
    protected $aiService;
    protected $candidateModel;
    protected $employerModel;
    protected $applicationModel;
    protected $savedJobModel;
    protected $jobModel;
    protected $messageModel;
    protected $walletModel;

    public function __construct()
    {
        $this->aiService = new AiService();
        $this->candidateModel = new JobSeekerModel();
        $this->employerModel = new EmployerModel();
        $this->applicationModel = new JobApplicationModel();
        $this->savedJobModel = new SavedJobModel();
        $this->jobModel = new JobModel();
        $this->messageModel = new MessageModel();
        $this->walletModel = new WalletModel();
    }

    /**
     * Handle incoming chat messages
     */
    public function sendMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        if (!auth()->loggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Please log in to use the assistant.']);
        }

        // Candidate Subscription Check for AI Chatbot
        $user = auth()->user();
        if ($user && ($user->user_type === 'candidate' || $user->role === 'candidate')) {
            if (!is_site_free_mode() && is_ai_tools_paid_mode()) {
                $subModel = model(\App\Models\UserSubscriptionModel::class);
                $hasActiveSub = $subModel->where('user_id', $user->id)
                    ->where('is_active', 1)
                    ->where('ends_at >=', date('Y-m-d H:i:s'))
                    ->first();

                if (!$hasActiveSub) {
                    return $this->response->setStatusCode(403)->setJSON([
                        'success'  => false,
                        'message'  => 'Access to the AI Assistant requires an active Premium Candidate Subscription. Please upgrade your plan to access AI tools.',
                        'redirect' => base_url('candidate/subscription/pricing')
                    ]);
                }
            }
        }

        $message = trim((string) $this->request->getPost('message'));
        if ($message === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Empty message']);
        }

        $session = session();
        $history = $session->get('chat_history') ?? [];

        $context = $this->buildContext();

        $response = $this->aiService->getChatResponse($message, $history, $context);

        $history[] = ['sender' => 'user', 'message' => $message];
        $history[] = ['sender' => 'bot', 'message' => $response];

        // Keep only last 10 turns (20 messages) for context efficiency
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }

        $session->set('chat_history', $history);

        return $this->response->setJSON([
            'success' => true,
            'response' => $response,
        ]);
    }

    /**
     * Build a live, DB-grounded context string so the assistant can answer
     * with the user's actual data (applications, saved jobs, wallet, unread
     * messages, job postings) instead of generic platform copy.
     */
    protected function buildContext(): string
    {
        $user = auth()->user();
        $userId = (int) $user->id;
        $userType = $user->user_type ?? 'guest';

        $context = "You are the JobberRecruit AI Assistant, embedded platform-wide. The current user is a {$userType} (email: {$user->email}). ";
        $context .= "Navigation map — link the user to these when relevant: /jobs (browse jobs), /candidate/dashboard, /candidate/profile, /candidate/applications, /candidate/saved-jobs, /candidate/wallet, /candidate/career-tools (AI Resume Builder, Interview Practice Simulator, Salary Negotiation, Career Advice), /candidate/notifications, /employer/dashboard, /employer/jobs, /employer/candidates, /employer/messages, /employer/settings, /referrals. ";
        $context .= "Referral program is active: users earn rewards for referring friends who sign up. ";

        try {
            if ($userType === 'job_seeker' || $userType === 'jobseeker' || $userType === 'candidate') {
                $context .= $this->buildCandidateContext($userId);
            } elseif ($userType === 'employer') {
                $context .= $this->buildEmployerContext($userId);
            }
        } catch (\Throwable $e) {
            // Never let a data lookup failure break the chat — degrade to
            // generic platform context instead of a hard error.
            log_message('error', 'Chatbot context build failed: ' . $e->getMessage());
        }

        $context .= "Only state facts about the user's account that are given to you above — never invent applications, numbers, or statuses that were not provided. If asked something you don't have data for, say so and point them to the right page. ";

        return $context;
    }

    protected function buildCandidateContext(int $userId): string
    {
        $candidate = $this->candidateModel->where('user_id', $userId)->first();
        if (! $candidate) {
            return "The candidate has not completed their profile yet — encourage them to visit /candidate/profile to get started. ";
        }

        $totalApplications = $this->applicationModel->where('job_seeker_id', $candidate->id)->countAllResults();
        $pendingApplications = $this->applicationModel
            ->where('job_seeker_id', $candidate->id)
            ->whereIn('status', ['pending', 'reviewed', 'shortlisted'])
            ->countAllResults();
        $savedJobs = $this->savedJobModel->where('user_id', $userId)->countAllResults();
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        $walletBalance = $wallet->balance ?? 0;
        $unreadMessages = $this->messageModel->getUnreadCount($userId, 'job_seeker');

        $recentApplications = $this->applicationModel
            ->select('job_applications.status, jobs.title')
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->where('job_applications.job_seeker_id', $candidate->id)
            ->orderBy('job_applications.created_at', 'DESC')
            ->findAll(3);

        $profileFields = [
            'full name' => $candidate->full_name,
            'phone' => $candidate->phone,
            'job title' => $candidate->job_title,
            'skills' => $candidate->skills,
            'resume' => $candidate->resume,
        ];
        $missing = array_keys(array_filter($profileFields, static fn ($v) => empty($v)));

        $summary = "CANDIDATE ACCOUNT DATA: Name: {$candidate->full_name}. ";
        $summary .= "Target role: " . ($candidate->job_title ?: 'not set') . ". ";
        $summary .= "Total applications submitted: {$totalApplications}. ";
        $summary .= "Applications awaiting employer response: {$pendingApplications}. ";
        $summary .= "Saved jobs: {$savedJobs}. ";
        $summary .= "Wallet balance: ₦" . number_format((float) $walletBalance, 2) . ". ";
        $summary .= "Unread messages: {$unreadMessages}. ";

        if (! empty($recentApplications)) {
            $lines = array_map(
                static fn ($a) => ($a->title ?? 'Untitled role') . ' (' . ($a->status ?? 'pending') . ')',
                $recentApplications
            );
            $summary .= "Most recent applications: " . implode('; ', $lines) . '. ';
        }

        if (! empty($missing)) {
            $summary .= "Profile is incomplete — missing: " . implode(', ', $missing) . ". This blocks their dashboard until fixed; direct them to /candidate/profile/edit. ";
        }

        return $summary;
    }

    protected function buildEmployerContext(int $userId): string
    {
        $employer = $this->employerModel->where('user_id', $userId)->first();
        if (! $employer) {
            return "The employer has not completed their company profile yet — encourage them to visit /employer/settings to get started. ";
        }

        $totalJobs = $this->jobModel->where('employer_id', $employer->id)->countAllResults();
        $activeJobs = $this->jobModel->where('employer_id', $employer->id)->where('status', 'active')->countAllResults();

        $totalApplicants = $this->applicationModel
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->where('jobs.employer_id', $employer->id)
            ->countAllResults();

        $pendingReview = $this->applicationModel
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->where('jobs.employer_id', $employer->id)
            ->where('job_applications.status', 'pending')
            ->countAllResults();

        $unreadMessages = $this->messageModel->getUnreadCount($userId, 'employer');

        $summary = "EMPLOYER ACCOUNT DATA: Company: {$employer->company_name}. ";
        $summary .= "Verification status: " . ($employer->verification_status ?: 'unverified') . ". ";
        $summary .= "Total job postings: {$totalJobs} ({$activeJobs} currently active). ";
        $summary .= "Total applicants received: {$totalApplicants}. ";
        $summary .= "Applications pending review: {$pendingReview}. ";
        $summary .= "Unread messages: {$unreadMessages}. ";

        if ((int) $employer->is_verified !== 1) {
            $summary .= "Account is not yet verified — this limits visibility to candidates; suggest completing verification in /employer/settings. ";
        }

        return $summary;
    }

    /**
     * Clear chat history
     */
    public function clearHistory()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        session()->remove('chat_history');
        return $this->response->setJSON(['success' => true]);
    }
}
