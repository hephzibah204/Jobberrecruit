<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Per-IP+route rate limit for auth endpoints (login/register/forgot-password),
 * which had no throttling at all — leaving them open to brute-force/credential
 * stuffing and registration spam. Uses CI4's built-in token-bucket Throttler.
 */
class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() !== 'POST') {
            return;
        }

        $throttler = service('throttler');
        $capacity  = (int) ($arguments[0] ?? 10);
        $seconds   = (int) ($arguments[1] ?? 60);
        // Cache keys can't contain {}()/\@: — build a safe key from the IP + route path.
        $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $request->getIPAddress() . '_' . current_url(true)->getPath());

        if (!$throttler->check($key, $capacity, $seconds)) {
            $response = service('response');
            if ($request->isAJAX()) {
                return $response->setJSON([
                    'status'  => 'error',
                    'message' => 'Too many attempts. Please wait a moment and try again.',
                ])->setStatusCode(429);
            }

            return $response->setStatusCode(429)->setBody('Too many attempts. Please wait a moment and try again.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
