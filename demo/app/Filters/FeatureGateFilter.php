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
        if (env('feature_webinars', 'true') == 'false') {
            if ($uri === 'webinars' || strpos($uri, 'webinars/') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'Career Webinars feature is currently disabled.');
            }
        }

        // 2. E-learning check
        if (env('feature_elearning', 'true') == 'false') {
            if ($uri === 'training' || strpos($uri, 'training/') === 0 || strpos($uri, 'elearning/') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'E-learning Courses feature is currently disabled.');
            }
        }

        // 3. AI Resume Builder check
        if (env('feature_ai_resume', 'true') == 'false') {
            if (strpos($uri, 'candidate/resumes') === 0 || strpos($uri, 'resume/generate') === 0 || strpos($uri, 'resume/improve') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'AI Resume Builder is currently disabled.');
            }
        }

        // 4. AI Career Tools check
        if (env('feature_ai_career_tools', 'true') == 'false') {
            if (strpos($uri, 'candidate/career-tools') === 0) {
                return redirect()->to(base_url('candidate/dashboard'))->with('error', 'AI Career Tools are currently disabled.');
            }
        }

        // 5. Messaging check
        if (env('feature_messaging', 'true') == 'false') {
            if (strpos($uri, 'candidate/messages') === 0 || strpos($uri, 'employer/messages') === 0) {
                return redirect()->to(base_url())->with('error', 'Direct Messaging is currently disabled.');
            }
        }

        // 6. Referrals check
        if (env('feature_referrals', 'true') == 'false') {
            if (strpos($uri, 'candidate/referrals') === 0 || strpos($uri, 'employer/referrals') === 0) {
                return redirect()->to(base_url())->with('error', 'Referral program is currently disabled.');
            }
        }

        // 7. AI Paid Mode check
        $isFreeMode = env('site_free_mode', 'false') == 'true';
        $aiPaidMode = env('ai_tools_paid_mode', 'false') == 'true';
        if ($aiPaidMode && !$isFreeMode) {
            // Check if route is AI resume or AI career tools
            $isAiResumeRoute = strpos($uri, 'candidate/resumes') === 0 || strpos($uri, 'resume/generate') === 0 || strpos($uri, 'resume/improve') === 0;
            $isAiCareerToolsRoute = strpos($uri, 'candidate/career-tools') === 0;

            if ($isAiResumeRoute || $isAiCareerToolsRoute) {
                $user = auth()->user();
                if ($user && $user->user_type === 'candidate') {
                    $subModel = model(\App\Models\UserSubscriptionModel::class);
                    $hasActiveSub = $subModel->where('user_id', $user->id)
                        ->where('is_active', 1)
                        ->where('end_date >=', date('Y-m-d H:i:s'))
                        ->first();

                    if (!$hasActiveSub) {
                        return redirect()->to(base_url('candidate/subscription/pricing'))->with('warning', 'AI Career Tools and Resume Builder require a Premium Plan. Please upgrade to access them.');
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
