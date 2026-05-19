<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use App\Models\EmailVerificationModel;
use App\Models\PasswordResetModel;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class AuthController extends BaseController
{
    protected $auth;
    protected $config;
    protected $users;            // App\Models\UserModel
    protected $userProvider;     // Shield user provider (model)
    protected $userModel;        // Shield UserModel alias
    protected $session;
    protected $jobSeekerModel;
    protected $employerModel;
    protected $db;

    public function __construct()
    {
        helper(['auth', 'text', 'form', 'url', 'env']);

        $this->auth           = service('auth');
        $this->config         = config('Auth');
        $this->users          = model(UserModel::class);
        $this->userProvider   = model(setting('Auth.userProvider'));
        $this->userModel      = model(ShieldUserModel::class);
        $this->session        = service('session');
        $this->jobSeekerModel = model(JobSeekerModel::class);
        $this->employerModel  = model(EmployerModel::class);
        $this->db             = db_connect();
    }

    /* -------------------------------------------------------------------------
     | 1) LOGIN
     --------------------------------------------------------------------------*/
    public function login()
    {
        if ($this->request->getMethod() !== 'POST') {
            return view('auth/login', [
                'title' => 'Login',
                'auth'  => $this->auth,
            ]);
        }

        // 1️⃣ HARD RESET (prevent session reuse)
        if ($this->auth->loggedIn()) {
            $oldUser = $this->auth->user();
            $this->auth->logout();
            $this->auth->forgetUser($oldUser->id);
            session()->destroy();
        }

        // 2️⃣ Validate input
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid input.',
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        // 3️⃣ reCAPTCHA
        if (!$this->verifyRecaptcha($this->request->getPost('g-recaptcha-response'))) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'reCAPTCHA verification failed.',
            ]);
        }

        // 4️⃣ Manual credential validation via Shield identities (NO SESSION)
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $identity = $this->db->table('auth_identities')
            ->select('user_id, secret2')
            ->where('type', 'email_password')
            ->where('secret', $email)
            ->get()
            ->getRow();

        $userModel = model(UserModel::class);
        $user = $identity ? $userModel->find((int) $identity->user_id) : null;

        if (
            !$identity ||
            !$user ||
            !service('passwords')->verify($password, (string) $identity->secret2)
        ) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid email or password.',
            ]);
        }

        // Skip verification gates locally so development can proceed without mail setup.
        if (ENVIRONMENT !== 'development' && !($user->email_verified_at)) {
            log_message('info', 'Verified: ' . json_encode($user->email_verified_at));

            $verificationModel = model(EmailVerificationModel::class);
            $verificationModel->where('user_id', $user->id)->delete();

            $this->createAndSendVerification(
                $user->id,
                $user->email,
                $user->username ?? null
            );

            return $this->response->setJSON([
                'status'       => 'error',
                'message'      => 'Please verify your email address before signing in.',
                'redirect_url' => base_url('auth/verify-email')
            ]);
        }

        // 6️⃣ LOGIN ONCE (THIS IS THE ONLY LOGIN CALL)
        $this->auth->login($user, true);

        // 7️⃣ Regenerate session (CRITICAL)
        session()->regenerate(true);

        // 8️⃣ Redirect
        $redirectTo = ($user->user_type === 'employer')
            ? base_url('employer/dashboard')
            : base_url('candidate/dashboard');

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Login successful!',
            'user_id'      => $user->id,
            'redirect_url' => $redirectTo
        ]);
    }


    /* -------------------------------------------------------------------------
     | 2) reCAPTCHA helper
     --------------------------------------------------------------------------*/
    private function verifyRecaptcha(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $secretKey = env('recaptcha_secret_key');

        try {
            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$token}"
            );
            $result = json_decode($response, true);
        } catch (\Throwable $e) {
            log_message('error', 'reCAPTCHA request failed: ' . $e->getMessage());
            return false;
        }

        return (isset($result['success']) && $result['success'] === true && ($result['score'] ?? 0) >= 0.5);
    }

    /* -------------------------------------------------------------------------
     | 3) STANDARD REGISTRATION
     --------------------------------------------------------------------------*/
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            // Let's check if email exists in auth_identities first to provide a clearer message (instead of generic validation error)
            $email = $this->request->getPost('email');
            $existingIdentity = $this->db->table('auth_identities')->where('secret', $email)->get()->getRow();
            if ($existingIdentity) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'An account with this email already exists. Please log in or use a different email.',
                ]);
            }
            $rules = [
                'email'      => 'required|valid_email',
                'password'   => 'required|min_length[8]',
                'full_name'  => 'required',
                'phone'      => 'required',
                'user_type'  => 'required|in_list[employer,job_seeker]',
            ];

            // Require company_name for employer registration
            if ($this->request->getPost('user_type') === 'employer') {
                $rules['company_name'] = 'required|min_length[2]';
            }

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please fix the validation errors',
                    'errors'  => $this->validator->getErrors()
                ]);
            }

            // reCAPTCHA
            if (!$this->verifyRecaptcha($this->request->getPost('g-recaptcha-response'))) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed reCAPTCHA verification. Please try again.'
                ]);
            }

            $users = $this->getUserProvider();

            // Generate unique username
            $email = $this->request->getPost('email');
            $username = explode('@', $email)[0];
            $baseUsername = $username;
            $counter = 1;
            while ($this->users->where('username', $username)->first()) {
                $username = $baseUsername . $counter++;
            }

            // Create user record (Shield provider)
            $user = $users->createNewUser([
                'username'       => $username,
                'user_type'      => $this->request->getPost('user_type'),
                'status'         => 'active',
                'active'         => 1,
                'status_message' => 'Welcome to JobberRecruit',
                'last_active'    => date('Y-m-d H:i:s'),
            ]);

            $userId = $users->insert($user);

            if (!$userId) {
                throw new \Exception('Failed to create user: ' . json_encode($users->errors()));
            }

            // Explicitly update user_type to ensure it's saved correctly
            // (User entity has default 'job_seeker', so we need to update if employer)
            $userTypeFromPost = $this->request->getPost('user_type');
            $this->users->update($userId, [
                'user_type' => $userTypeFromPost,
            ]);

            $user->id = $userId;

            // Attach email identity
            $user->createEmailIdentity([
                'name'     => $this->request->getPost('full_name'),
                'email'    => $email,
                'password' => $this->request->getPost('password'),
            ]);

            // Handle Referral Attribution
            $refCode = $this->request->getPost('referral_code');
            if (!empty($refCode)) {
                $referralService = new \App\Services\ReferralService();
                $referralService->attributeReferral($userId, $refCode);
            }

            // Profiles + extras
            $userType = $this->request->getPost('user_type');

            if ($userType === 'job_seeker') {
                $this->jobSeekerModel->save([
                    'user_id'   => $userId,
                    'full_name' => $this->request->getPost('full_name'),
                    'phone'     => $this->request->getPost('phone'),
                ]);
            } else { // employer
                $this->employerModel->save([
                    'user_id'       => $userId,
                    'contact_name'  => $this->request->getPost('full_name'),
                    'contact_phone' => $this->request->getPost('phone'),
                    'company_name'  => $this->request->getPost('company_name'),
                ]);

                // Assign free plan & create paystack customer
                // $this->assignFreePlanToUser($userId);
                $this->createPaystackCustomerIfNeededById($userId, $email);
            }

             // Auto-login after registration (optional, can be removed if you want to force email verification first)
             $credentials = [
                'email'    => $email,
                'password' => $this->request->getPost('password'),
            ];

            $rememberMe = true; // Optional: set to true to remember the user

            $loginAttempt = $this->auth->attempt($credentials, $rememberMe);

            $user = $this->auth->user();

            if (ENVIRONMENT === 'development') {
                $redirect = $userType === 'employer' ? base_url('employer') : base_url('candidate');

                return $this->response->setJSON([
                    'status'       => 'success',
                    'message'      => 'Account created and signed in for development.',
                    'user_id'      => $user->id,
                    'redirect_url' => $redirect,
                ]);
            }

            // Send verification email and do NOT auto-login outside development.
            $this->createAndSendVerification($user->id, $email, $this->request->getPost('full_name'));

            return $this->response->setJSON([
                'status'       => 'success',
                'message'      => 'Account created. Please check your email to verify your account.',
                'user_id'      => $user->id,
                'redirect_url' => base_url('auth/verify-email')
            ]);
        }

        return view('auth/register', [
            'title' => 'Create Account',
            'auth'  => $this->auth,
        ]);
    }

    /* -------------------------------------------------------------------------
     | 4) PAYSTACK & SUBSCRIPTIONS
     --------------------------------------------------------------------------*/
    private function assignFreePlanToUser(int $userId)
    {
        $planModel = model(\App\Models\SubscriptionPlanModel::class);
        $subModel  = model(\App\Models\UserSubscriptionModel::class);

        $freePlan = $planModel->where('slug', 'free')->first();

        if ($freePlan) {
            $subModel->insert([
                'user_id'    => $userId,
                'plan_id'    => $freePlan->id,
                'start_date' => date('Y-m-d H:i:s'),
                'end_date'   => date('Y-m-d H:i:s', strtotime('+3650 days')),
                'is_active'  => 1
            ]);
        }
    }

    private function paystackPost($endpoint, $data)
    {
        $key = env('paystack_secret_key');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.paystack.co" . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer $key",
                "Content-Type: application/json"
            ],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data)
        ]);

        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res);
    }

    private function createPaystackCustomerIfNeededById(int $userId, string $email)
    {
        $user = $this->users->find($userId);
        if (!empty($user->paystack_customer_code)) {
            return $user->paystack_customer_code;
        }

        $response = $this->paystackPost("/customer", ["email" => $email]);

        if (!empty($response->status) && !empty($response->data->customer_code)) {
            $customerCode = $response->data->customer_code;
            $this->users->update($userId, ["paystack_customer_code" => $customerCode]);
            return $customerCode;
        }

        log_message('error', 'Paystack create customer failed: ' . json_encode($response));
        return null;
    }

    /* -------------------------------------------------------------------------
     | 5) SOCIAL LOGIN: Google
     --------------------------------------------------------------------------*/
    public function googleLogin()
    {
        $clientId    = env('google_client_id');
        $redirectUri = env('google_redirect_uri');

        if (!$clientId || !$redirectUri) {
            return redirect()->to('/login')->with('error', 'Google is not configured.');
        }

        $params = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'offline',
            'prompt'        => 'select_account',
            'state'         => bin2hex(random_bytes(16)),
        ]);

        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function googleCallback()
    {
        $code = $this->request->getGet('code');
        $social = service('socialAuth');

        $result = $social->authenticate('google', $code ?? '');

        log_message('info', 'Google callback result: ' . json_encode($result));

        if ($result['status'] === 'error') {
            return redirect()->to('/login')->with('error', $result['message']);
        }

        // If pending, socialAuth created social_pending session
        if ($result['status'] === 'pending') {
            return redirect()->to($result['redirect_url']);
        }

        // Otherwise redirect to the provided URL
        return redirect()->to($result['redirect_url'] ?? '/');
    }

    /* -------------------------------------------------------------------------
     | 6) SOCIAL LOGIN: LinkedIn
     --------------------------------------------------------------------------*/
    public function linkedinLogin()
    {
        $client_id = env('linkedin_client_id');
        $redirect  = env('linkedin_redirect_uri');
        $scope     = 'openid profile email';

        $state = bin2hex(random_bytes(16));
        session()->set('linkedin_oauth_state', $state);

        $params = http_build_query([
            "response_type" => "code",
            "client_id"     => $client_id,
            "redirect_uri"  => $redirect,
            "scope"         => $scope,
            "state"         => $state,
        ]);

        return redirect()->to("https://www.linkedin.com/oauth/v2/authorization?" . $params);
    }

    public function linkedinCallback()
    {
        $state = $this->request->getGet('state');
        $saved = session()->get('linkedin_oauth_state');

        if ($state !== $saved) {
            log_message('error', "LinkedIn OAuth STATE mismatch: received={$state}, expected={$saved}");
            return redirect()->to('/login')->with('error', 'Invalid OAuth state');
        }

        $code = $this->request->getGet('code');
        $social = service('socialAuth');

        $result = $social->authenticate('linkedin', $code ?? '');

        if ($result['status'] === 'error') {
            return redirect()->to('/login')->with('error', $result['message']);
        }

        if ($result['status'] === 'pending') {
            return redirect()->to($result['redirect_url']);
        }

        return redirect()->to($result['redirect_url'] ?? '/');
    }

    /* -------------------------------------------------------------------------
     | 7) SOCIAL ONBOARDING (resume / complete)
     --------------------------------------------------------------------------*/
    public function socialComplete()
    {
        $tmp = session()->get('social_pending');

        if (!$tmp) {
            return redirect()->to('/login')->with('error', 'Session expired. Please try again.');
        }

        $ctx = [
            'title' => 'Complete your account',
            'auth'  => $this->auth,
            'social' => $tmp
        ];

        return view('auth/social_complete', $ctx);
    }

    /**
     * socialFinalize: handles POST from social_complete
     * This delegates to finalizeSocialUser() which creates the user, sends verification, etc.
     */
    public function socialFinalize()
    {
        $tmp = session()->get('social_pending');
        if (!$tmp) {
            return redirect()->to('/login')->with('error', 'Session expired. Please try again.');
        }

        $post = $this->request->getPost();

        // Validate user_type & terms
        $rules = [
            'user_type'    => 'required|in_list[employer,job_seeker]',
            'agree_terms'  => 'required',
        ];

        if (isset($post['user_type']) && $post['user_type'] === 'employer') {
            $rules['company_name'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please complete the form correctly.')->withInput();
        }

        // Call internal finalization routine
        $result = $this->finalizeSocialUser($post);

        if ($result['status'] === 'error') {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }

        // success redirect (finalizeSocialUser returns redirect to verify-email)
        return redirect()->to($result['redirect_url']);
    }

    /**
     * finalizeSocialUser - converts `social_pending` session into a real user
     * NOTE: keeps social_pending in session until creation completes; clears afterward.
     */
    public function finalizeSocialUser(array $data): array
    {
        $tmp = session()->get('social_pending');

        if (!$tmp) {
            return ['status' => 'error', 'message' => 'Session expired. Please try again.'];
        }

        $userType = $data['user_type'] ?? null;
        $company  = $data['company_name'] ?? null;

        if (!$userType) {
            return ['status' => 'error', 'message' => 'Please choose an account type.'];
        }

        if ($userType === 'employer' && empty($company)) {
            return ['status' => 'error', 'message' => 'Company name is required for employer accounts'];
        }

        // Create unique username
        $username = explode('@', $tmp['email'])[0];
        $baseUsername = $username;
        $counter = 1;
        while ($this->users->where('username', $username)->first()) {
            $username = $baseUsername . $counter++;
        }

        // Create Shield user (note: email will be set)
        $user = $this->userProvider->createNewUser([
            'username'    => $username,
            'email'       => $tmp['email'],
            'user_type'   => $userType,
            'active'      => 1,
            'status'      => 'pending_verification',
            'status_message' => 'Awaiting Registration Verification',
            'last_active' => date('Y-m-d H:i:s'),
        ]);

        $userId = $this->userProvider->insert($user);

        if (!$userId) {
            log_message('error', 'Social finalize: failed to insert user: ' . json_encode($this->userProvider->errors()));
            return ['status' => 'error', 'message' => 'Could not create user account.'];
        }

        // Update the user
        $this->users->update($userId, [
            'user_type'     => $userType,
        ]);

        // Insert profile record
        if ($userType === 'job_seeker') {
            $this->jobSeekerModel->insert([
                'user_id'   => $userId,
                'full_name' => $tmp['full_name'] ?? null,
                'phone'     => null,
            ]);
        } else {
            $this->employerModel->insert([
                'user_id'       => $userId,
                'company_name'  => $company,
                'contact_name'  => $tmp['full_name'] ?? null,
                'contact_email' => $tmp['email'],
            ]);

            // Assign free plan + create paystack customer
            $this->assignFreeEmployerPlan($userId);
            $this->createPaystackCustomer($tmp['email'], $userId);
        }

        if (ENVIRONMENT === 'development') {
            $user = $this->users->findById($userId);
            if ($user) {
                $this->auth->login($user);
            }

            session()->remove('social_pending');

            return [
                'status'       => 'success',
                'redirect_url' => $userType === 'employer' ? base_url('employer') : base_url('candidate'),
            ];
        }

        // Send verification email
        $this->createAndSendVerification($userId, $tmp['email'], $tmp['full_name'] ?? null);

        // Remove pending session
        session()->remove('social_pending');

        return [
            'status'       => 'success',
            'redirect_url' => base_url('auth/verify-email'),
        ];
    }

    /* -------------------------------------------------------------------------
     | Helper wrappers used by finalizeSocialUser (kept separate)
     --------------------------------------------------------------------------*/
    private function assignFreeEmployerPlan(int $userId)
    {
        $planModel = model(\App\Models\SubscriptionPlanModel::class);
        $subModel  = model(\App\Models\UserSubscriptionModel::class);

        $freePlan = $planModel->where('slug', 'free')->first();

        if ($freePlan) {
            $subModel->insert([
                'user_id'    => $userId,
                'plan_id'    => $freePlan->id,
                'start_date' => date('Y-m-d H:i:s'),
                'end_date'   => date('Y-m-d H:i:s', strtotime('+3650 days')),
                'is_active'  => 1
            ]);
        }
    }

    private function createPaystackCustomer(string $email, int $userId)
    {
        $secret = env('paystack_secret_key');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.paystack.co/customer",
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode(["email" => $email]),
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer $secret",
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $res = json_decode(curl_exec($curl));
        curl_close($curl);

        if (!empty($res->status) && !empty($res->data->customer_code)) {
            model(UserModel::class)->update($userId, [
                'paystack_customer_code' => $res->data->customer_code
            ]);
        } else {
            log_message('error', 'Paystack customer creation failed (social): ' . json_encode($res));
        }
    }

    /* -------------------------------------------------------------------------
     | 8) EMAIL VERIFICATION (Hybrid pages + token)
     --------------------------------------------------------------------------*/
    /**
     * verifyEmailPage: hybrid behaviour
     * - If user is logged in: show personalized verify page with email
     * - If not logged in: show generic verify page (ask user to check inbox)
     */
    public function verifyEmailPage()
    {
        $user = $this->auth->user();

        if ($user) {
            if (!empty($user->email_verified_at)) {
                return redirect()->to('/')->with('success', 'Email already verified.');
            }

            return view('auth/verify_email', [
                'email' => $user->email,
                'title' => 'Verify your email',
                'user'      => $this->auth->user(),
                'auth'      => $this->auth ?? null,
            ]);
        }

        // Generic view (not logged in)
        return view('auth/verify_email_generic', [
            'title' => 'Verify your email',
            'auth'  => $this->auth ?? null,
        ]);
    }

    /**
     * verifyEmailToken: validate token, mark email_verified_at, auto-login and redirect
     * Option B: Automatically log the user in after verification.
     */
    public function verifyEmailToken($token)
    {
        $model = model(EmailVerificationModel::class);
        $record = $model->where('token', $token)->first();

        if (!$record || strtotime($record->expires_at) < time()) {
            return redirect()->to('/login')->with('error', 'Invalid or expired verification link.');
        }

        // Mark email as verified
        $userId = $record->user_id;
        $this->users->update($userId, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status'            => 'active',
            'status_message'    => 'Welcome to JobberRecruit'
        ]);

        // Remove the token
        $model->delete($record->id);

        // Auto-login user (Option B)
        $user = $this->users->find($userId);
        if ($user) {
            // Use Shield auth to log user in
            try {
                // Ensure no existing auth state
                if ($this->auth->loggedIn()) {
                    $this->auth->forgetUser($userId);
                }

                // Extra safety: regenerate session
                session()->regenerate(true);
                $this->auth->login($user);
            } catch (\Throwable $e) {
                log_message('error', 'Auto-login after verification failed: ' . $e->getMessage());
                // proceed to redirect to login page
                return redirect()->to('/login')->with('success', 'Email verified successfully. Please sign in.');
            }
        }

        // Reward referrer if candidate
        if ($user->user_type === 'job_seeker') {
            $referralService = new \App\Services\ReferralService();
            $referralService->rewardReferrer($userId, 'candidate');
        }

        // Redirect user to proper dashboard based on user_type
        $redirect_to = ($user->user_type === 'employer')
            ? base_url('employer/dashboard')
            : base_url('candidate/dashboard');

        return redirect()->to($redirect_to)->with('success', 'Email verified and logged in successfully.');
    }

    /**
     * resendVerification: logged-in user may request a new verification email
     */
    public function resendVerification()
    {
        $user = $this->auth->user();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to resend verification email.'
            ]);
        }

        if (!empty($user->email_verified_at)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Your email is already verified.'
            ]);
        }

        // Throttle: check last token creation time and prevent frequent resend (simple approach)
        $model = model(EmailVerificationModel::class);
        $existing = $model->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if ($existing && (strtotime($existing->created_at) > time() - 60)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must wait before requesting another verification email.'
            ]);
        }

        $model->where('user_id', $user->id)->delete();
        $this->createAndSendVerification($user->id, $user->email, $user->username ?? null);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'A new verification email has been sent to your inbox.'
        ]);
    }

    /* -------------------------------------------------------------------------
     | 9) PASSWORD RESET
     --------------------------------------------------------------------------*/
    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = ['email' => 'required|valid_email'];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'errors'  => $this->validator->getErrors(),
                    'message' => 'Invalid email address'
                ]);
            }

            $data = $this->request->getVar();
            $email = $data['email'];
            $identity = $this->db->table('auth_identities')->where('secret', $email)->get()->getRow();

            if (!$identity) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'If this email is registered, instructions will be sent.'
                ]);
            }

            $user = $this->db->table('users')->where('id', $identity->user_id)->get()->getRow();

            if (!$user) {
                // Always return success to avoid leaking presence
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'If this email is registered, instructions will be sent.'
                ]);
            }

            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $resetModel = model(\App\Models\PasswordResetModel::class);
            $resetModel->save([
                'user_id'    => $user->id,
                'token'      => $token,
                'expires_at' => $expiresAt
            ]);

            $resetLink = base_url("auth/reset-password/{$token}");

            try {
                $mailer = new \App\Services\Mailer();
                $sent = $mailer->sendResetPassword($email, 'Password Reset Requested', [
                    'fullname' => $user->username,
                    'resetLink' => $resetLink,
                    'siteName' => env('site_name') ?: 'JobberRecruit',
                ]);

                if (!$sent) {
                    log_message('error', "Failed sending password reset link to {$email}");
                }
            } catch (\Throwable $e) {
                log_message('error', "Mailer failed: " . $e->getMessage());
            }

            return $this->response->setJSON([
                'status'      => 'success',
                'message'     => 'Password reset link has been sent to your email.',
            ]);
        }

        return view('auth/forgot-password', ['title' => 'Forgot Password', 'auth'  => $this->auth,]);
    }

    public function resetPassword($token)
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                // 'token'    => 'required',
                'password' => 'required|min_length[8]|max_length[255]',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'errors'  => $this->validator->getErrors(),
                    'message' => 'Validation Error'
                ]);
            }

            $password = $this->request->getPost('password');

            // Validate reset token
            $resetModel = model(PasswordResetModel::class);
            $reset = $resetModel->where('token', $token)->first();

            if (! $reset || strtotime($reset->expires_at) < time()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Invalid or expired reset token.',
                ]);
            }

            // Get Shield User
            $users = model(UserModel::class);
            $user  = $users->findById($reset->user_id);

            if (! $user) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'User not found.',
                ]);
            }

            // 🔐 Hash password using Shield
            $passwords = service('passwords');
            $user->password_hash = $passwords->hash($password);

            // Save user
            $users->save($user);

            // Delete reset token
            $resetModel->delete($reset->id);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Password reset successfully'
            ]);
        }

        // Check if token is valid
        $resetModel = model(PasswordResetModel::class);
        $reset = $resetModel->where('token', $token)->first();
        $valid_token = $reset && strtotime($reset->expires_at) > time();

        return view('auth/reset-password', ['title' => 'Reset Password', 'auth'  => $this->auth, 'valid_token' => $valid_token, 'token' => $token]);
    }

    /* -------------------------------------------------------------------------
     | 10) UTILITIES
     --------------------------------------------------------------------------*/
    protected function getUserProvider(): ShieldUserModel
    {
        $provider = model(setting('Auth.userProvider'));
        if (!$provider instanceof ShieldUserModel) {
            throw new \Exception('Auth.userProvider is not a valid UserProvider.');
        }
        return $provider;
    }

    /**
     * createAndSendVerification: creates a token and sends the verification email using Mailer
     */
    protected function createAndSendVerification(int $userId, string $email, ?string $fullname = null): void
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 day'));

        $model = model(EmailVerificationModel::class);
        $model->where('user_id', $userId)->delete();
        $model->insert([
            'user_id'    => $userId,
            'token'      => $token,
            'expires_at' => $expires,
        ]);

        $verifyUrl = base_url("auth/verify-email/{$token}");

        // Use your Mailer service (app/Services/Mailer.php)
        try {
            $mailer = new \App\Services\Mailer();
            $sent = $mailer->sendVerifyEmail($email, 'Please verify your JobberRecruit email', [
                'fullname' => $fullname,
                'verifyUrl' => $verifyUrl,
                'siteName' => env('site_name') ?: 'JobberRecruit',
            ]);

            if (!$sent) {
                log_message('error', "Failed sending verification email to {$email}");
            }
        } catch (\Throwable $e) {
            log_message('error', "Mailer failed: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
     | 11) LOGOUT
     --------------------------------------------------------------------------*/
    public function logout()
    {
        $user = $this->auth->user();

        if ($user) {
            // 1. Destroy current session
            $this->auth->logout();

            // 2. Invalidate remember-me tokens (all devices)
            $this->auth->forgetUser($user->id);
            session()->destroy();
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Logged out successfully'
            ]);
        }

        return redirect()->to('/login')
            ->with('success', 'You have successfully logged out.');
    }
}
