<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Security\Exceptions\SecurityException;

class UserAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        $session = service('session');
        $response = service('response');

        // $auth = auth();

        // Check if user is logged in
        if (!$auth->loggedIn()) {
            // Store intended URL for redirect after login
            $remember = service('rememberer');
            $remember?->login();
            $currentURL = current_url();
            $session->set('redirect_url', $currentURL);

            if ($request->isAJAX()) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Your session has expired. Please login again.',
                    'redirect_url' => base_url('login')
                ]);
            }

            return redirect()->to('login')->with('error', 'Please login to continue.');
        }

        // Get the current user
        $user = $auth->user();

        // Check if user is active (if you have an active status field)
        if (isset($user->status) && $user->status !== 'active') {
            $auth->logout();

            if ($request->isAJAX()) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'Your account is not active. Please contact support.',
                    'redirect_url' => base_url('login')
                ])->setStatusCode(403);
            }

            return redirect()->to('login')->with('error', 'Your account is not active. Please contact support.');
        }

        if (ENVIRONMENT !== 'development' && empty($user->email_verified_at)) {
            // Redirect to verify page
            return redirect()->to('/auth/verify-email')->with('error', 'Please verify your email to continue.');
        }

        // Check user type if specified in arguments
        if (!empty($arguments)) {
            $userTypes = $arguments;
            $userType = $user->user_type ?? null;

            if ($userType && !in_array($userType, $userTypes)) {
                if ($request->isAJAX()) {
                    return $response->setJSON([
                        'status' => 'error',
                        'message' => 'You do not have permission to access this resource.',
                        'redirect_url' => base_url('dashboard')
                    ])->setStatusCode(403);
                }

                return redirect()->to('dashboard')->with('error', 'You do not have permission to access that page.');
            }
        }

        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // You can modify the response here if needed
        return $response;
    }
}
