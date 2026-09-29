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
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
