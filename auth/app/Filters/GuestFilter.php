<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('authentication');

        // Check if user is already logged in
        if ($auth->check()) {
            $response = service('response');

            if ($request->isAJAX()) {
                return $response->setJSON([
                    'status' => 'error',
                    'message' => 'You are already logged in.',
                    'redirect_url' => base_url('dashboard')
                ])->setStatusCode(403);
            }

            return redirect()->to('dashboard')->with('info', 'You are already logged in.');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
