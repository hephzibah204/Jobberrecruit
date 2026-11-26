<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = auth();

        // Not logged in
        if (! $auth->loggedIn()) {

            if ($request->getMethod() === 'get') {
                session()->set('admin_redirect', current_url());
            }

            return redirect()->to('/admin/login');
        }

        $user = $auth->user();

        // Logged-in non-admin users should be denied without exposing a
        // framework exception page. Return them to their own dashboard.
        if (! $user || $user->user_type !== 'admin') {
            $destination = ($user && $user->user_type === 'employer')
                ? '/employer/dashboard'
                : '/candidate/dashboard';

            return redirect()->to($destination)
                ->with('error', 'You do not have permission to access the administration area.');
        }

        // Two-factor gate: an admin who has enrolled in TOTP 2FA (an
        // enabled admin_2fa row) must pass the challenge at /admin/2fa
        // before reaching any admin page. Admins who never enrol have
        // no enabled row and are completely unaffected. The challenge
        // routes live outside this filter; logout stays reachable so a
        // pending admin can always sign out. The lookup fails OPEN on
        // infrastructure errors (e.g. the admin_2fa table not existing
        // yet during a deploy): 2FA must never lock every admin out.
        $segment2 = $request->getUri()->getSegment(2);
        if ($segment2 !== 'logout' && $segment2 !== '2fa' && ! session()->get('admin_2fa_passed')) {
            try {
                $enrolled = model(\App\Models\Admin2faModel::class)
                    ->where('user_id', (int) $user->id)
                    ->where('enabled', 1)
                    ->first();

                if ($enrolled) {
                    if ($request->getMethod() === 'GET') {
                        session()->set('admin_redirect', current_url());
                    }

                    return redirect()->to('/admin/2fa');
                }
            } catch (\Throwable $e) {
                log_message('error', 'Admin 2FA gate check failed: ' . $e->getMessage());
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
