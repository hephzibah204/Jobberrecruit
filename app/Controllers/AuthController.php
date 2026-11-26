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
            $jobModel = model(\App\Models\JobModel::class);
            $employerModel = model(\App\Models\EmployerModel::class);
            $jobSeekerModel = model(\App\Models\JobSeekerModel::class);

            $liveJobsCount = $jobModel->where('status', 'open')->where('admin_status', 'approved')->countAllResults();
            $employerCount = $employerModel->countAllResults();
            $candidateCount = $jobSeekerModel->countAllResults();

            return view('auth/login', [
                'title'          => 'Login',
                'auth'           => $this->auth,
                'liveJobsCount'  => $liveJobsCount,
                'employerCount'  => $employerCount,
                'candidateCount' => $candidateCount,
            ]);
        }

        // 1️⃣ HARD RESET (prevent session reuse)
        if ($this->auth->loggedIn()) {
            $oldUser = $this->auth->user();
            $this->auth->logout();
            $this->auth->forgetUser($oldUser->id);
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

        // CAPTCHA is verified server-side in every environment.
        if (!$this->verifyRecaptcha($this->request->getPost('g-recaptcha-response'), 'login')) {
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

        if (!$user->active) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Your account has been suspended. Please contact support.',
            ]);
        }

        // Skip verification gates locally so development can proceed without mail setup.
        if (empty($user->email_verified_at)) {
            log_message('info', 'Verified: ' . json_encode($user->email_verified_at));
            $this->session->set('pending_email_verification', [
                'user_id' => (int) $user->id,
                'expires_at' => time() + 3600,
            ]);
            $this->createAndSendVerification($user->id, $user->email, $user->username ?? null);

            return $this->response->setJSON([
                'status'       => 'error',
                'message'      => 'Please verify your email address before signing in.',
                'redirect_url' => base_url('auth/verify-email')
            ]);
        }

        // 6️⃣ Regenerate session (CRITICAL - must occur before writing authentication data to session)
        session()->regenerate(true);

        // 7️⃣ LOGIN ONCE (THIS IS THE ONLY LOGIN CALL)
        $this->auth->login($user, true);

        // 8️⃣ Redirect to role dashboard
        session()->remove('redirect_url');

        $redirectTo = match ($user->user_type) {
            'employer' => base_url('employer/dashboard'),
            'admin'    => base_url('admin/dashboard'),
            default    => base_url('candidate/dashboard'),
        };

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
    private function verifyRecaptcha(?string $token, string $action = 'login'): bool
    {
        return (new \App\Services\CaptchaVerifier())->verify($token, $action);
    }

    /* -------------------------------------------------------------------------
     | 3) STANDARD REGISTRATION
     --------------------------------------------------------------------------*/
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = strtolower(trim((string) $this->request->getPost('email')));
            if (trim((string) $this->request->getPost('website_url')) !== '') {
                return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Registration could not be completed.']);
            }
            if (!(new \App\Services\AuthAbuseGuard())->allow('register', $this->request->getIPAddress(), $email)) {
                return $this->response->setStatusCode(429)->setJSON(['status' => 'error', 'message' => 'Too many registration attempts. Please try again later.']);
            }
            if (!(new \App\Services\CaptchaVerifier())->verify($this->request->getPost('g-recaptcha-response'), 'register')) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'Security verification failed. Please refresh and try again.']);
            }
            if ((new \App\Services\EmailSuppressionService())->isSuppressed($email)) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'This email address cannot receive account emails. Please contact support.']);
            }
            // Let's check if email exists in auth_identities first to provide a clearer message (instead of generic validation error)
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

            $users = $this->getUserProvider();

            // Generate unique username
            $email = $this->request->getPost('email');
            $username = explode('@', $email)[0];
            $baseUsername = $username;
            $counter = 1;
            while ($this->users->where('username', $username)->first()) {
                $username = $baseUsername . $counter++;
            }

            $db = \Config\Database::connect();
            $db->transStart();

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
                $db->transRollback();
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

            // Trigger automation event
            \App\Libraries\AutomationEngine::triggerEvent('user_registered', $userId);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Registration failed due to a database error. Please try again.'
                ]);
            }

            $user = $users->findById($userId);
            session()->regenerate(true);
            $this->session->set('pending_email_verification', [
                'user_id' => (int) $userId,
                'expires_at' => time() + 3600,
            ]);
            $emailSent = $this->createAndSendVerification($userId, $email, $this->request->getPost('full_name'));

            return $this->response->setJSON([
                'status'       => $emailSent ? 'success' : 'warning',
                'message'      => $emailSent
                    ? 'Account created. Please check your email to verify your account.'
                    : 'Account created but verification email could not be sent. You can request a new one from the login page.',
                'user_id'      => $user->id,
                'redirect_url' => base_url('auth/verify-email')
            ]);
        }

        $jobModel = model(\App\Models\JobModel::class);
        $employerModel = model(\App\Models\EmployerModel::class);
        $jobSeekerModel = model(\App\Models\JobSeekerModel::class);

        $liveJobsCount = $jobModel->where('status', 'open')->where('admin_status', 'approved')->countAllResults();
        $employerCount = $employerModel->countAllResults();
        $candidateCount = $jobSeekerModel->countAllResults();

        return view('auth/register', [
            'title'          => 'Create Account',
            'auth'           => $this->auth,
            'liveJobsCount'  => $liveJobsCount,
            'employerCount'  => $employerCount,
            'candidateCount' => $candidateCount,
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
                'starts_at'  => date('Y-m-d H:i:s'),
                'ends_at'    => date('Y-m-d H:i:s', strtotime('+3650 days')),
                'is_active'  => 1
            ]);
        }
    }

    private function paystackPost($endpoint, $data)
    {
        $key = paystack_secret_key();

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

        $state = bin2hex(random_bytes(16));
        session()->set('google_oauth_state', $state);

        $params = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'offline',
            'prompt'        => 'select_account',
            'state'         => $state,
        ]);

        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function googleCallback()
    {
        $state = $this->request->getGet('state');
        $saved = session()->get('google_oauth_state');
        session()->remove('google_oauth_state');

        if ($state !== $saved) {
            log_message('error', "Google OAuth STATE mismatch: received={$state}, expected={$saved}");
            return redirect()->to('/login')->with('error', 'Invalid OAuth state');
        }

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

        $db = \Config\Database::connect();
        $db->transStart();

        $userId = $this->userProvider->insert($user);

        if (!$userId) {
            $db->transRollback();
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

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['status' => 'error', 'message' => 'Registration failed due to database transaction failure.'];
        }

        // Social signups use the same email verification and resend flow in every environment.
        $this->session->set('pending_email_verification', [
            'user_id' => (int) $userId,
            'expires_at' => time() + 3600,
        ]);
        session()->remove('social_pending');

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
                'starts_at'  => date('Y-m-d H:i:s'),
                'ends_at'    => date('Y-m-d H:i:s', strtotime('+3650 days')),
                'is_active'  => 1
            ]);
        }
    }

    private function createPaystackCustomer(string $email, int $userId)
    {
        $secret = paystack_secret_key();

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
        $db = db_connect();
        $db->transBegin();
        $model = model(EmailVerificationModel::class);
        $record = $model->where('token', $token)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$record) {
            $db->transRollback();
            return redirect()->to('/login')->with('error', 'Invalid or expired verification link.');
        }

        $userId = (int) $record->user_id;
        $user = $this->users->findById($userId);
        if (!$user) {
            $db->transRollback();
            return redirect()->to('/login')->with('error', 'This account is no longer available.');
        }

        // Consume the one-time token conditionally so concurrent requests cannot
        // verify or reward the same account more than once.
        $db->table('email_verifications')->where('id', $record->id)
            ->where('token', $token)->where('expires_at >=', date('Y-m-d H:i:s'))->delete();
        if ($db->affectedRows() !== 1) {
            $db->transRollback();
            return redirect()->to('/login')->with('error', 'Invalid or expired verification link.');
        }

        // Mark email as verified
        $updated = $this->users->update($userId, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status'            => 'active',
            'status_message'    => 'Welcome to JobberRecruit'
        ]);
        if (!$updated || !$db->transStatus()) {
            $db->transRollback();
            return redirect()->to('/login')->with('error', 'Email verification could not be completed. Please request a new link.');
        }
        $db->transCommit();

        // Auto-login user (Option B)
        $user = $this->users->findById($userId);
        $this->session->remove('pending_email_verification');
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
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed.']);
        }

        if (!(new \App\Services\CaptchaVerifier())->verify($this->request->getPost('g-recaptcha-response'), 'resend_verification')) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'Security verification failed. Please refresh and try again.']);
        }

        $user = $this->auth->user();
        $pending = $this->session->get('pending_email_verification');
        if (!$user && is_array($pending) && (int) ($pending['expires_at'] ?? 0) >= time()) {
            $user = $this->users->findById((int) ($pending['user_id'] ?? 0));
        }

        if (!$user || !empty($user->email_verified_at)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'This verification request has expired. Sign in or create an account to continue.'
            ]);
        }

        if (!(new \App\Services\AuthAbuseGuard())->allow('resend_verification', $this->request->getIPAddress(), (string) $user->email)) {
            return $this->response->setStatusCode(429)->setJSON(['status' => 'error', 'message' => 'Too many resend attempts. Please try again later.']);
        }

        $model = model(EmailVerificationModel::class);
        $existing = $model->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if ($existing && (strtotime($existing->created_at) > time() - 60)) {
            return $this->response->setStatusCode(429)->setJSON([
                'status' => 'error',
                'message' => 'You must wait before requesting another verification email.'
            ]);
        }

        if ((new \App\Services\EmailSuppressionService())->isSuppressed((string) $user->email)) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'This email address cannot receive account emails. Please contact support.']);
        }
        $sent = $this->createAndSendVerification($user->id, $user->email, $user->username ?? null);
        if (!$sent) {
            return $this->response->setStatusCode(502)->setJSON(['status' => 'error', 'message' => 'The email could not be sent. Please try again later.']);
        }

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
            $email = strtolower(trim((string) $data['email']));
            $genericResetMessage = 'If this email is registered, instructions will be sent.';
            if (!(new \App\Services\AuthAbuseGuard())->allow('password_reset', $this->request->getIPAddress(), $email)) {
                return $this->response->setJSON(['status' => 'success', 'message' => $genericResetMessage]);
            }
            $identity = $this->db->table('auth_identities')->where('secret', $email)->get()->getRow();

            if (!$identity) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => $genericResetMessage
                ]);
            }

            $user = $this->db->table('users')->where('id', $identity->user_id)->get()->getRow();

            if (!$user) {
                // Always return success to avoid leaking presence
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => $genericResetMessage
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
                'message'     => $genericResetMessage,
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

            // Claim the one-time token in a transaction before changing the password.
            // The conditional delete prevents two concurrent requests from using it twice.
            $db = db_connect();
            $db->transBegin();
            $resetModel = model(PasswordResetModel::class);
            $reset = $resetModel->where('token', $token)
                ->where('expires_at >=', date('Y-m-d H:i:s'))
                ->first();

            if (!$reset) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Invalid or expired reset token.',
                ]);
            }

            // Get Shield User
            $users = model(UserModel::class);
            $user  = $users->findById($reset->user_id);

            if (! $user) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'User not found.',
                ]);
            }

            $db->table('password_resets')->where('id', $reset->id)
                ->where('token', $token)->where('expires_at >=', date('Y-m-d H:i:s'))->delete();
            if ($db->affectedRows() !== 1) {
                $db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid or expired reset token.',
                ]);
            }

            // 🔐 Hash password using Shield
            $passwords = service('passwords');
            $user->password_hash = $passwords->hash($password);

            // Save user
            if (!$users->save($user) || !$db->transStatus()) {
                $db->transRollback();
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Password could not be updated. Please try again.',
                ]);
            }
            $db->transCommit();

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
    protected function createAndSendVerification(int $userId, string $email, ?string $fullname = null): bool
    {
        $existing = model(EmailVerificationModel::class)->where('user_id', $userId)->orderBy('created_at', 'DESC')->first();
        if ($existing && strtotime($existing->created_at) > time() - 60) {
            return false;
        }
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

        try {
            $mailer = new \App\Services\Mailer();
            $sent = $mailer->sendVerifyEmail($email, 'Please verify your JobberRecruit email', [
                'fullname' => $fullname,
                'verifyUrl' => $verifyUrl,
                'siteName' => env('site_name') ?: 'JobberRecruit',
            ]);

            if (!$sent) {
                log_message('error', "Failed sending verification email to {$email}");
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            log_message('error', "Mailer failed: " . $e->getMessage());
            return false;
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
