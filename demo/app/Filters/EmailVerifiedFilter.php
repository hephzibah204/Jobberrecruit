<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class EmailVerifiedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (ENVIRONMENT === 'development') {
            return;
        }

        $auth = service('auth');
        $user = $auth->user();

        if (!$user) {
            // Not logged in - let Auth middleware handle redirects
            return;
        }

        if (empty($user->email_verified_at)) {
            // Redirect to verify page
            return redirect()->to('/auth/verify-email')->with('error', 'Please verify your email to continue.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
