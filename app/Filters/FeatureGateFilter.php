<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class FeatureGateFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = service('uri')->getPath();

        // 1. Webinars check
        if (!get_site_setting('feature_webinars', true)) {
            if ($uri === 'webinars' || strpos($uri, 'webinars/') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'Career Webinars feature is currently disabled.');
            }
        }

        // 2. E-learning check
        if (!get_site_setting('feature_elearning', true)) {
            if ($uri === 'training' || strpos($uri, 'training/') === 0 || strpos($uri, 'elearning/') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'E-learning Courses feature is currently disabled.');
            }
        }

        // 3. AI Resume Builder check
        if (!get_site_setting('feature_ai_resume', true)) {
            if (strpos($uri, 'candidate/resumes') === 0 || strpos($uri, 'resume/generate') === 0 || strpos($uri, 'resume/improve') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'AI Resume Builder is currently disabled.');
            }
        }

        // 4. AI Career Tools check
        if (!get_site_setting('feature_ai_career_tools', true)) {
            if (strpos($uri, 'candidate/career-tools') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'AI Career Tools are currently disabled.');
            }
        }

        // 5. Messaging check
        if (!get_site_setting('feature_messaging', true)) {
            if (strpos($uri, 'candidate/messages') === 0 || strpos($uri, 'employer/messages') === 0) {
                return redirect()->to(base_url())->with('error', 'Direct Messaging is currently disabled.');
            }
        }

        // 6. Referrals check
        if (!get_site_setting('feature_referrals', true)) {
            if (strpos($uri, 'candidate/referrals') === 0 || strpos($uri, 'employer/referrals') === 0) {
                return redirect()->to(base_url())->with('error', 'Referral program is currently disabled.');
            }
        }

        // 7. Candidate AI Paid Mode Enforcement (configured via Admin)
        $isFreeMode = is_site_free_mode();
        $isAiPaidMode = is_ai_tools_paid_mode();

        // Only enforce paywall on AI tools if Site Free Mode is OFF and AI Tools Paid Mode is ON
        if (!$isFreeMode && $isAiPaidMode) {
            $isAiChatbotRoute      = $uri === 'chatbot/send' || strpos($uri, 'chatbot/') === 0 || $uri === 'api/chat';
            $isAiResumeRoute       = strpos($uri, 'candidate/resumes') === 0 || strpos($uri, 'resume/generate') === 0 || strpos($uri, 'resume/improve') === 0 || strpos($uri, 'resume/rewrite') === 0;
            $isAiCareerToolsRoute  = strpos($uri, 'candidate/career-tools') === 0;
            $isAiInterviewApiRoute = strpos($uri, 'api/interview') === 0;

            if ($isAiChatbotRoute || $isAiResumeRoute || $isAiCareerToolsRoute || $isAiInterviewApiRoute) {
                $user = auth()->user();
                if ($user && ($user->user_type === 'candidate' || $user->role === 'candidate')) {
                    $subModel = model(\App\Models\UserSubscriptionModel::class);
                    $hasActiveSub = $subModel->where('user_id', $user->id)
                        ->where('is_active', 1)
                        ->where('ends_at >=', date('Y-m-d H:i:s'))
                        ->first();

                    if (!$hasActiveSub) {
                        if ($request->isAJAX() || strpos($request->getHeaderLine('Accept'), 'application/json') !== false || strpos($uri, 'api/') === 0 || $isAiChatbotRoute) {
                            $response = service('response');
                            return $response->setStatusCode(403)->setJSON([
                                'success'  => false,
                                'message'  => 'Access to AI tools (including the AI Assistant, Resume Builder & Career Tools) requires an active Premium Candidate Subscription. Please upgrade your plan to access them.',
                                'redirect' => base_url('candidate/subscription/pricing')
                            ]);
                        }

                        return redirect()->to(base_url('candidate/subscription/pricing'))
                            ->with('warning', 'AI tools (including Chatbot, Resume Builder & Career Tools) require an active Premium Candidate Subscription. Please upgrade your plan to access them.');
                    }
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
