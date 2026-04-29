<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authentication\AuthenticationException;

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

        // Logged in but not admin
        if (! $user || ! $user->user_type === 'admin') {
            throw AuthenticationException::forInvalidUser();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
