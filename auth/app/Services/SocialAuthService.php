<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\JobSeekerModel;
use App\Models\EmployerModel;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class SocialAuthService
{
    protected $db;
    protected $auth;
    protected $users;
    protected $userProvider;
    protected $jobSeekerModel;
    protected $employerModel;

    public function __construct()
    {
        $this->auth           = service('auth');
        $this->users          = model(UserModel::class);
        $this->userProvider   = model(setting('Auth.userProvider'));
        $this->jobSeekerModel = model(JobSeekerModel::class);
        $this->employerModel  = model(EmployerModel::class);
        $this->db               = \Config\Database::connect();
    }

    /**
     * Entry point for Google or LinkedIn callback
     */
    public function authenticate(string $provider, string $code): array
    {
        if (!$code) {
            return ['status' => 'error', 'message' => 'Missing authorization code'];
        }

        try {
            return $provider === 'google'
                ? $this->processGoogle($code)
                : $this->processLinkedIn($code);
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Social login failed, please try again.'];
        }
    }

    /* ---------------------------------------------------------------------
     * GOOGLE
     * --------------------------------------------------------------------- */

    protected function processGoogle(string $code): array
    {
        // 1) Exchange authorization code → Access token
        $tokenResponse = $this->httpPost('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => env('google_client_id'),
            'client_secret' => env('google_client_secret'),
            'redirect_uri'  => env('google_redirect_uri'),
            'grant_type'    => 'authorization_code',
        ]);

        $accessToken = $tokenResponse->access_token ?? null;

        if (!$accessToken) {
            return ['status' => 'error', 'message' => 'Google authentication failed'];
        }

        // 2) Get profile
        $googleUser = $this->httpGet('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $accessToken);

        if (empty($googleUser->email)) {
            return ['status' => 'error', 'message' => 'Unable to fetch Google email'];
        }

        return $this->prepareUserForCompletion(
            provider: 'google',
            email: $googleUser->email,
            fullName: $googleUser->name ?? '',
            photo: $googleUser->picture ?? null
        );
    }

    /* ---------------------------------------------------------------------
     * LINKEDIN
     * --------------------------------------------------------------------- */

    protected function processLinkedIn(string $code): array
    {
        $tokenResponse = $this->httpPost('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => env('linkedin_redirect_uri'),
            'client_id'     => env('linkedin_client_id'),
            'client_secret' => env('linkedin_client_secret'),
        ]);

        $accessToken = $tokenResponse->access_token ?? null;

        if (!$accessToken) {
            return ['status' => 'error', 'message' => 'LinkedIn authentication failed'];
        }

        $data = $this->httpGetAuth('https://api.linkedin.com/v2/userinfo', $accessToken);

        return $this->prepareUserForCompletion(
            provider: 'linkedin',
            email: $data->email ?? '',
            fullName: $data->name ?? '',
            photo: $data->picture ?? null
        );
    }

    /* ---------------------------------------------------------------------
     * USER CREATION LOGIC
     * --------------------------------------------------------------------- */

    protected function prepareUserForCompletion(string $provider, string $email, string $fullName, ?string $photo): array
    {
        $userProvider = model(setting('Auth.userProvider'));

        // Check if identity exists
        $identity = $this->db->table('auth_identities')
            ->select('user_id')
            ->where('secret', $email)
            ->get()
            ->getRow();

        if ($identity) {
            // MUST load as Shield User entity
            $existingUser = $userProvider->find($identity->user_id);

            if ($existingUser instanceof \CodeIgniter\Shield\Entities\User) {

                // Login user through Shield
                service('auth')->login($existingUser);

                $redirect = $existingUser->user_type === 'employer'
                    ? base_url('employer/dashboard')
                    : base_url('candidate/dashboard');

                return [
                    'status'       => 'success',
                    'redirect_url' => $redirect,
                ];
            }
        }

        // No existing account → send to onboarding
        session()->set('social_pending', [
            'provider'  => $provider,
            'email'     => $email,
            'full_name' => $fullName,
            'photo'     => $photo,
        ]);

        return [
            'status'       => 'pending',
            'redirect_url' => base_url('auth/social/complete'),
        ];
    }

    /**
     * Final step: create the Shield user + profile
     */
    public function finalizeSocialUser(array $data): array
    {
        $tmp = session()->get('social_pending');
        if (!$tmp) {
            return ['status' => 'error', 'message' => 'Session expired. Please try again.'];
        }

        $userType = $data['user_type'];
        $company  = $data['company_name'] ?? null;

        if ($userType === 'employer' && empty($company)) {
            return ['status' => 'error', 'message' => 'Company name is required for employer accounts'];
        }

        /** Create Shield user record */
        $username = explode('@', $tmp['email'])[0];

        $user = $this->userProvider->createNewUser([
            'username' => $username,
            'email'    => $tmp['email'],
            'user_type' => $userType,
            'active'   => 1,
            'status'   => 'active',
        ]);

        $userId = $this->userProvider->insert($user);

        log_message('info', 'The Data from the Service: ' . json_encode($data));

        if (!$userId) {
            return ['status' => 'error', 'message' => 'Failed to create account'];
        }

        /** Store profile */
        if ($userType === 'job_seeker') {
            $this->jobSeekerModel->save([
                'user_id'   => $userId,
                'full_name' => $tmp['full_name'],
            ]);
        } else {
            $this->employerModel->save([
                'user_id'       => $userId,
                'company_name'  => $company,
                'contact_name'  => $tmp['full_name'],
                'contact_email' => $tmp['email'],
            ]);
        }

        // Ensure no existing auth state
        if ($this->auth->loggedIn()) {
            $this->auth->logout();
        }

        // Extra safety: regenerate session
        session()->regenerate(true);

        $this->auth->login($user);

        session()->remove('social_pending');

        return [
            'status' => 'success',
            'redirect_url' => $userType === 'employer'
                ? base_url('employer/dashboard')
                : base_url('candidate/dashboard'),
        ];
    }

    /* ---------------------------------------------------------------------
     * HTTP HELPERS
     * --------------------------------------------------------------------- */

    protected function httpPost(string $url, array $params)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res ?? '{}');
    }

    protected function httpGet(string $url)
    {
        return json_decode(file_get_contents($url) ?: '{}');
    }

    protected function httpGetAuth(string $url, string $token)
    {
        $opts = [
            "http" => [
                "header" => "Authorization: Bearer {$token}"
            ]
        ];
        return json_decode(file_get_contents($url, false, stream_context_create($opts)) ?: '{}');
    }
}
