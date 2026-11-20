<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AiToolsSafetyFilter implements FilterInterface
{
    public static function protects(string $path): bool
    {
        return preg_match('~^(candidate/(resumes(?:/|$)|resume-builder$|career-tools(?:/|$)|coach/generate$)|api/interview(?:/|$)|resume/(generate|improve|rewrite)(?:/|$))~', trim($path, '/')) === 1;
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim($request->getUri()->getPath(), '/');
        if (!self::protects($path)) return;
        $user = auth()->user();
        if (!$user || !in_array($user->user_type, ['job_seeker', 'candidate'], true)) {
            // These routes include full HTML pages (resume builder and career tools)
            // as well as JSON APIs. Browser navigation should reach the styled login
            // page; only API/XHR requests should receive a JSON error payload.
            $acceptsHtml = str_contains(strtolower($request->getHeaderLine('Accept')), 'text/html');
            if (strtoupper($request->getMethod()) === 'GET' && $acceptsHtml && !$request->isAJAX()) {
                if (!$user) {
                    service('session')->set('redirect_url', current_url());
                    return redirect()->to(base_url('login'))->with('error', 'Please sign in with a candidate account to use this tool.');
                }

                return redirect()->to(base_url('dashboard'))->with('error', 'Please sign in with a candidate account to use this tool.');
            }

            return service('response')->setStatusCode($user ? 403 : 401)->setJSON(['success'=>false, 'message'=>'Please sign in with a candidate account to use this tool.']);
        }
        if (!$user->active || (!empty($user->status) && $user->status !== 'active')) {
            return service('response')->setStatusCode(403)->setJSON(['message'=>'Your account is not active. Please contact support.']);
        }
        if (strtoupper($request->getMethod()) !== 'POST') return;
        if ((int) $request->getHeaderLine('Content-Length') > 22 * 1024 * 1024) {
            return service('response')->setStatusCode(413)->setJSON(['message'=>'The uploaded content is too large.']);
        }
        // Explicit verification also covers the older broad api/* CSRF exemption.
        try { service('security')->verify($request); }
        catch (\CodeIgniter\Security\Exceptions\SecurityException $e) {
            return service('response')->setStatusCode(403)->setJSON(['message'=>'Your security token has expired. Refresh this page and try again.']);
        }
        if (!service('throttler')->check('ai_user_' . $user->id, 60, 60)
            || !service('throttler')->check('ai_user_hour_' . $user->id, 600, 3600)) {
            return service('response')->setStatusCode(429)->setJSON(['message'=>'You have reached the AI request limit. Please wait before trying again.']);
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
