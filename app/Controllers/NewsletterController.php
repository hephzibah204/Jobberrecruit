<?php

namespace App\Controllers;

use App\Models\NewsletterModel;
use App\Models\NewsletterSubscriberModel;
use App\Models\NewsletterTemplateModel;
use App\Models\WebinarModel;
use App\Models\WebinarRegistrationModel;
use CodeIgniter\API\ResponseTrait;

class NewsletterController extends BaseController
{
    use ResponseTrait;

    protected $newsletterModel;
    protected $subscriberModel;
    protected $webinarModel;
    protected $registrationModel;
    protected $industryModel;

    public function __construct()
    {
        $this->newsletterModel = new NewsletterModel();
        $this->subscriberModel = new NewsletterSubscriberModel();
        $this->webinarModel = new WebinarModel();
        $this->registrationModel = new WebinarRegistrationModel();
        $this->industryModel = new \App\Models\IndustryModel();
    }

    /**
     * Public webinars listing
     */
    public function webinars()
    {
        return view('webinars_public', [
            'title'            => 'Free Career Webinars & Live Masterclasses | JobberRecruit',
            'meta_description' => 'Join free live career webinars and masterclasses hosted by top Nigerian HR leaders. Learn interview skills, resume writing & industry insights.',
            'webinars'         => $this->webinarModel->where('status !=', 'cancelled')
                                            ->orderBy('scheduled_at', 'ASC')
                                            ->findAll()
        ]);
    }

    public function registered($webinarId = null)
    {
        $user = auth()->loggedIn() ? auth()->user() : null;
        $userEmail = $user ? $user->email : 'your email';
        $userFirstName = 'there';
        $userFullName = 'Candidate';

        if ($user) {
            $seeker = model(\App\Models\JobSeekerModel::class)->where('user_id', $user->id)->first();
            if ($seeker && !empty($seeker->full_name)) {
                $userFullName = $seeker->full_name;
                $userFirstName = explode(' ', trim($seeker->full_name))[0];
            } elseif (!empty($user->username)) {
                $userFullName = $user->username;
                $userFirstName = $user->username;
            }
        }

        $targetWebinarId = $webinarId ?: $this->request->getGet('id');
        $webinar = null;

        if ($targetWebinarId) {
            $webinar = $this->webinarModel->find($targetWebinarId);
        }

        if (!$webinar && auth()->loggedIn()) {
            $reg = $this->registrationModel->where('user_id', auth()->id())->orderBy('id', 'DESC')->first();
            if ($reg) {
                $webinar = $this->webinarModel->find($reg->webinar_id ?? $reg['webinar_id']);
            }
        }

        if (!$webinar) {
            $webinar = $this->webinarModel->where('status !=', 'cancelled')->orderBy('scheduled_at', 'ASC')->first();
        }

        $registration = null;
        if ($webinar && auth()->loggedIn()) {
            $registration = $this->registrationModel->where([
                'webinar_id' => $webinar->id,
                'user_id'    => auth()->id(),
            ])->first();
        }

        $ticketNumber = 'JR-WBN-' . ($webinar ? str_pad($webinar->id, 4, '0', STR_PAD_LEFT) : '0001') . '-' . (auth()->loggedIn() ? str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) : rand(1000, 9999));
        $admissionType = ($webinar && ($webinar->access_type ?? 'free') === 'paid') ? ('Paid VIP Access (₦' . number_format((float)($webinar->price ?? 0)) . ')') : 'Standard Free Admission';
        $joinUrl = !empty($webinar->meeting_link) ? $webinar->meeting_link : base_url('training/webinars');

        $recommendedWebinars = $this->webinarModel
            ->where('status !=', 'cancelled')
            ->where('id !=', $webinar?->id ?? 0)
            ->orderBy('scheduled_at', 'ASC')
            ->findAll(3);

        return view('webinar_registered', [
            'title'               => 'Registration Confirmed - ' . ($webinar ? esc($webinar->title) : 'JobberRecruit'),
            'webinar'             => $webinar,
            'registration'        => $registration,
            'user_first_name'     => $userFirstName,
            'user_full_name'      => $userFullName,
            'user_email'          => $userEmail,
            'ticket_number'       => $ticketNumber,
            'admission_type'      => $admissionType,
            'join_url'            => $joinUrl,
            'recommendedWebinars' => $recommendedWebinars,
        ]);
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribe()
    {
        $email = $this->request->getPost('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->fail('Invalid email address');
        }

        $existing = $this->subscriberModel->where('email', $email)->first();
        if ($existing) {
            if ($existing->is_active) {
                return $this->failResourceExists('You are already subscribed');
            } else {
                $this->subscriberModel->update($existing->id, ['is_active' => 1]);
                return $this->respondCreated(['message' => 'Subscription reactivated']);
            }
        }

        $this->subscriberModel->insert([
            'email' => $email,
            'user_id' => auth()->id() ?? null,
            'is_active' => 1
        ]);

        return $this->respondCreated(['message' => 'Successfully subscribed to newsletter']);
    }

    /**
     * Register for a webinar (Free or Paid with Paystack / Wallet)
     */
    public function registerWebinar($webinarId)
    {
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('Please login to register for webinars');
        }

        $webinar = $this->webinarModel->find($webinarId);
        if (!$webinar) {
            return $this->failNotFound('Webinar not found');
        }

        $user = auth()->user();

        $existing = $this->registrationModel->where([
            'webinar_id' => $webinarId,
            'user_id'    => $user->id
        ])->first();

        if ($existing) {
            return $this->failResourceExists('You are already registered for this webinar');
        }

        $isPaid = ($webinar->access_type ?? 'free') === 'paid' && (float)($webinar->price ?? 0) > 0;

        // Handling Paid Webinar
        if ($isPaid) {
            $reference = $this->request->getPost('reference') ?? $this->request->getVar('reference');
            $paymentMethod = $this->request->getPost('payment_method') ?? $this->request->getVar('payment_method');

            // 1. Paystack Verification Callback from Modal / Inline
            if (!empty($reference)) {
                $paystack = new \App\Services\PaystackService();
                $verify = $paystack->verify($reference);

                if (!($verify['status'] ?? false) || ($verify['data']['status'] ?? '') !== 'success') {
                    return $this->fail('Payment verification failed. Please try again or contact support.');
                }

                // Record payment in PaymentModel
                try {
                    $paymentModel = model(\App\Models\PaymentModel::class);
                    $paymentModel->insert([
                        'user_id'        => $user->id,
                        'reference'      => $reference,
                        'amount'         => ($verify['data']['amount'] ?? ($webinar->price * 100)) / 100,
                        'status'         => 'paid',
                        'payment_method' => 'paystack',
                        'paid_at'        => date('Y-m-d H:i:s'),
                    ]);
                } catch (\Throwable $e) {
                    log_message('error', 'Payment recording error: ' . $e->getMessage());
                }

                $this->finalizeWebinarRegistration($user, $webinar);

                return $this->respondCreated([
                    'status'     => 201,
                    'message'    => 'Payment successful! You are now registered.',
                    'webinar_id' => $webinarId,
                    'redirect'   => base_url('training/webinars/registered?id=' . $webinarId),
                ]);
            }

            // 2. Wallet Payment Option
            if ($paymentMethod === 'wallet') {
                $walletService = new \App\Services\WalletService();
                $wallet = $walletService->getOrCreateWallet($user->id);
                $amount = (float)$webinar->price;

                if ((float)$wallet->balance < $amount) {
                    return $this->fail('Insufficient wallet balance (₦' . number_format($wallet->balance, 2) . '). Please top up your wallet or pay with Paystack.');
                }

                $wRef = 'wbn_w_' . uniqid();
                $walletService->debit(
                    userId: $user->id,
                    amount: $amount,
                    source: 'wallet_checkout',
                    reference: $wRef,
                    description: 'Paid with wallet for webinar: ' . $webinar->title
                );

                try {
                    $paymentModel = model(\App\Models\PaymentModel::class);
                    $paymentModel->insert([
                        'user_id'        => $user->id,
                        'reference'      => $wRef,
                        'amount'         => $amount,
                        'status'         => 'paid',
                        'payment_method' => 'wallet',
                        'paid_at'        => date('Y-m-d H:i:s'),
                    ]);
                } catch (\Throwable $e) {
                    log_message('error', 'Wallet payment recording error: ' . $e->getMessage());
                }

                $this->finalizeWebinarRegistration($user, $webinar);

                return $this->respondCreated([
                    'status'     => 201,
                    'message'    => 'Payment successful using wallet! You are registered.',
                    'webinar_id' => $webinarId,
                    'redirect'   => base_url('training/webinars/registered?id=' . $webinarId),
                ]);
            }

            // 3. Initiate Paystack Payment Modal / Checkout
            $paystack = new \App\Services\PaystackService();
            $callbackUrl = base_url("training/webinars/verify/{$webinarId}");
            $initResponse = $paystack->initialize($user->email, $webinar->price, $callbackUrl, [
                'type'       => 'webinar',
                'webinar_id' => $webinarId,
                'user_id'    => $user->id,
            ]);

            if (!($initResponse['status'] ?? false)) {
                return $this->fail('Could not initialize payment: ' . ($initResponse['message'] ?? 'Please try again later.'));
            }

            $publicKey = env('paystack_public_key', env('PAYSTACK_PUBLIC_KEY', ''));

            return $this->respond([
                'status'            => 200,
                'requires_payment'  => true,
                'authorization_url' => $initResponse['data']['authorization_url'],
                'reference'         => $initResponse['data']['reference'] ?? '',
                'access_code'       => $initResponse['data']['access_code'] ?? '',
                'public_key'        => $publicKey,
                'amount'            => (float)$webinar->price,
                'user_email'        => $user->email,
                'webinar_title'     => $webinar->title,
                'webinar_id'        => $webinarId,
            ]);
        }

        // Free Webinar Registration
        $this->finalizeWebinarRegistration($user, $webinar);

        return $this->respondCreated([
            'status'     => 201,
            'message'    => 'Successfully registered for the free webinar.',
            'webinar_id' => $webinarId,
            'redirect'   => base_url('training/webinars/registered?id=' . $webinarId),
        ]);
    }

    private function finalizeWebinarRegistration($user, $webinar)
    {
        $existing = $this->registrationModel->where([
            'webinar_id' => $webinar->id,
            'user_id'    => $user->id
        ])->first();

        if (!$existing) {
            $this->registrationModel->insert([
                'webinar_id' => $webinar->id,
                'user_id'    => $user->id
            ]);
            $this->webinarModel->where('id', $webinar->id)->increment('registrants_count', 1);
        }

        if (!empty($user->email)) {
            $existingSub = $this->subscriberModel->where('email', $user->email)->first();
            if (!$existingSub) {
                $this->subscriberModel->insert([
                    'email'     => $user->email,
                    'user_id'   => $user->id,
                    'is_active' => 1
                ]);
            }
        }

        try {
            $emailNotifService = new \App\Services\EmailNotificationService();
            $emailNotifService->sendWebinarRegistrationNotification($user, $webinar);
        } catch (\Throwable $e) {
            log_message('error', 'Webinar registration notification error: ' . $e->getMessage());
        }
    }

    /**
     * Verify Paystack standard redirect payment
     */
    public function verifyWebinarPayment($webinarId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login to view registration.');
        }

        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('training/webinars')->with('error', 'Invalid payment reference');
        }

        $paystack = new \App\Services\PaystackService();
        $result = $paystack->verify($reference);

        if (!($result['status'] ?? false) || ($result['data']['status'] ?? '') !== 'success') {
            return redirect()->to('training/webinars')->with('error', 'Webinar payment verification failed or was cancelled.');
        }

        $user = auth()->user();
        $webinar = $this->webinarModel->find($webinarId);
        if (!$webinar) {
            return redirect()->to('training/webinars')->with('error', 'Webinar not found.');
        }

        // Record payment
        try {
            $paymentModel = model(\App\Models\PaymentModel::class);
            $paymentModel->insert([
                'user_id'        => $user->id,
                'reference'      => $reference,
                'amount'         => ($result['data']['amount'] ?? ($webinar->price * 100)) / 100,
                'status'         => 'paid',
                'payment_method' => 'paystack',
                'paid_at'        => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Payment recording error: ' . $e->getMessage());
        }

        $this->finalizeWebinarRegistration($user, $webinar);

        return redirect()->to('training/webinars/registered?id=' . $webinarId)->with('success', 'Payment successful! Seat confirmed.');
    }

    // --- Admin Methods ---

    public function adminIndex()
    {
        $newsletters = $this->newsletterModel->orderBy('created_at', 'DESC')->findAll();
        $webinars = $this->webinarModel->orderBy('scheduled_at', 'DESC')->findAll();

        return view('admin/newsletters/index', [
            'title' => 'Newsletters & Webinars',
            'newsletters' => $newsletters,
            'webinars' => $webinars,
            'subscribers' => $this->subscriberModel->where('is_active', 1)->countAllResults()
        ]);
    }

    public function create()
    {
        return view('admin/newsletters/editor', [
            'title' => 'Create Newsletter',
            'newsletter' => null,
            'industries' => $this->industryModel->orderBy('name', 'ASC')->findAll()
        ]);
    }

    public function edit($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (!$newsletter) {
            return redirect()->to('admin/newsletters')->with('error', 'Newsletter not found');
        }

        return view('admin/newsletters/editor', [
            'title' => 'Edit Newsletter',
            'newsletter' => $newsletter,
            'industries' => $this->industryModel->orderBy('name', 'ASC')->findAll()
        ]);
    }

    public function saveNewsletter()
    {
        $rules = [
            'title' => 'required',
            'subject' => 'permit_empty',
            'target_group' => 'permit_empty',
            'content' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $id = $this->request->getPost('id');
        $data = [
            'title' => $this->request->getPost('title'),
            // subject/target_group are NOT NULL columns; default when omitted
            'subject' => $this->request->getPost('subject') ?? '',
            'target_group' => $this->request->getPost('target_group') ?? 'general',
            'content' => $this->request->getPost('content'),
            'status' => 'draft'
        ];

        if ($id) {
            $this->newsletterModel->update($id, $data);
        } else {
            $this->newsletterModel->insert($data);
        }

        return redirect()->back()->with('success', 'Newsletter saved as draft');
    }

    public function deleteNewsletter($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (! $newsletter) {
            return redirect()->to('admin/newsletters')->with('error', 'Newsletter not found.');
        }

        $this->newsletterModel->delete($id);
        return redirect()->to('admin/newsletters')->with('success', 'Newsletter deleted successfully.');
    }

    public function sendNewsletter($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found');
        }

        $targetGroup = $newsletter->target_group ?? 'all';
        $recipientEmails = [];
        $db = \Config\Database::connect();

        if ($targetGroup === 'webinar_registered') {
            $webinarEmails = $db->table('webinar_registrations')
                ->join('users', 'users.id = webinar_registrations.user_id')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.active', 1)
                ->distinct()
                ->get()
                ->getResultArray();
            $recipientEmails = array_column($webinarEmails, 'email');
        } elseif ($targetGroup === 'training_registered') {
            $courseEmails = $db->table('course_enrollments')
                ->join('users', 'users.id = course_enrollments.user_id')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.active', 1)
                ->distinct()
                ->get()
                ->getResultArray();
            $recipientEmails = array_column($courseEmails, 'email');
        } elseif ($targetGroup === 'candidates') {
            $candidateEmails = $db->table('users')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.user_type', 'candidate')
                ->where('users.active', 1)
                ->get()
                ->getResultArray();
            $recipientEmails = array_column($candidateEmails, 'email');
        } elseif ($targetGroup === 'new_candidates') {
            $candidateEmails = $db->table('users')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.user_type', 'candidate')
                ->where('users.active', 1)
                ->where('users.created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                ->get()
                ->getResultArray();
            $recipientEmails = array_column($candidateEmails, 'email');
        } elseif ($targetGroup === 'employers') {
            $employerEmails = $db->table('users')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.user_type', 'employer')
                ->where('users.active', 1)
                ->get()
                ->getResultArray();
            $recipientEmails = array_column($employerEmails, 'email');
        } elseif ($targetGroup === 'new_employers') {
            $employerEmails = $db->table('users')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.user_type', 'employer')
                ->where('users.active', 1)
                ->where('users.created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                ->get()
                ->getResultArray();
            $recipientEmails = array_column($employerEmails, 'email');
        } elseif ($targetGroup === 'guest_subscribers') {
            $subscribers = $this->subscriberModel->where('user_id IS NULL')->where('is_active', 1)->findAll();
            $recipientEmails = array_column($subscribers, 'email');
        } elseif ($targetGroup === 'registered_subscribers') {
            $subscribers = $this->subscriberModel->where('user_id IS NOT NULL')->where('is_active', 1)->findAll();
            $recipientEmails = array_column($subscribers, 'email');
        } elseif ($targetGroup === 'subscribers') {
            $subscribers = $this->subscriberModel->where('is_active', 1)->findAll();
            $recipientEmails = array_column($subscribers, 'email');
        } else {
            // 'all': Combine newsletter subscribers + active users
            $subscribers = $this->subscriberModel->where('is_active', 1)->findAll();
            $subEmails = array_column($subscribers, 'email');

            $userEmails = $db->table('users')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->select('auth_identities.secret as email')
                ->where('users.active', 1)
                ->get()
                ->getResultArray();
            $uEmails = array_column($userEmails, 'email');

            $recipientEmails = array_unique(array_filter(array_merge($subEmails, $uEmails)));
        }

        $queueModel = new \App\Models\JobQueueModel();
        $queuedCount = 0;

        foreach ($recipientEmails as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $queueModel->dispatch('newsletter_email', [
                'newsletter_id' => $id,
                'email'         => $email,
                'subject'       => $newsletter->subject ?: $newsletter->title,
                'content'       => $newsletter->content
            ]);
            $queuedCount++;
        }

        $adminUser = auth()->user();
        $adminName = $adminUser ? ($adminUser->username ?? $adminUser->first_name ?? 'Admin') : 'Admin';

        $this->newsletterModel->update($id, [
            'status'     => 'sent',
            'sent_at'    => date('Y-m-d H:i:s'),
            'created_by' => $adminName
        ]);

        $groupLabels = [
            'all'                    => 'All Users & Subscribers',
            'candidates'             => 'Registered Candidates',
            'employers'              => 'Registered Employers',
            'guest_subscribers'      => 'Guest Subscribers',
            'registered_subscribers' => 'Registered Subscribers',
            'new_candidates'         => 'New Candidates (Last 30 Days)',
            'new_employers'          => 'New Employers (Last 30 Days)',
            'subscribers'            => 'All Newsletter Subscribers',
            'webinar_registered'     => 'Webinar Registrants',
            'training_registered'    => 'Training / Course Registrants'
        ];

        $targetLabel = $groupLabels[$targetGroup] ?? ucfirst($targetGroup);

        return redirect()->back()->with('success', "Newsletter queued for {$queuedCount} recipients in segment '{$targetLabel}'. Emails will be delivered in background.");
    }

    public function saveWebinar()
    {
        $id = $this->request->getPost('id');
        $accessType = $this->request->getPost('access_type') === 'paid' ? 'paid' : 'free';
        $price = $accessType === 'paid' ? (float)$this->request->getPost('price') : 0.00;

        $data = [
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'speaker_name' => $this->request->getPost('speaker_name'),
            'scheduled_at' => $this->request->getPost('scheduled_at'),
            'meeting_link' => $this->request->getPost('meeting_link'),
            'access_type'  => $accessType,
            'price'        => $price,
            'status'       => $this->request->getPost('status') ?? 'upcoming'
        ];

        $flyer = $this->request->getFile('flyer_image');
        if ($flyer && $flyer->isValid() && !$flyer->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/webinars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newName = $flyer->getRandomName();
            $flyer->move($uploadDir, $newName);
            $data['flyer_image'] = 'uploads/webinars/' . $newName;
        }

        if ($id) {
            $this->webinarModel->update($id, $data);
        } else {
            $this->webinarModel->insert($data);
        }

        return redirect()->back()->with('success', 'Webinar saved successfully');
    }

    public function adminWebinarsIndex()
    {
        $webinars = $this->webinarModel
            ->select('webinars.*, COUNT(webinar_registrations.id) as registrations_count')
            ->join('webinar_registrations', 'webinar_registrations.webinar_id = webinars.id', 'left')
            ->groupBy('webinars.id')
            ->orderBy('webinars.scheduled_at', 'DESC')
            ->findAll();

        return view('admin/webinars/index', [
            'title'    => 'Webinar Classes Management',
            'webinars' => $webinars,
        ]);
    }

    /**
     * Get registered attendees for a specific webinar (JSON API)
     */
    public function getWebinarAttendees($id)
    {
        $webinar = $this->webinarModel->find($id);
        if (!$webinar) {
            return $this->failNotFound('Webinar not found');
        }

        $db = \Config\Database::connect();
        $attendees = $db->table('webinar_registrations')
            ->select('webinar_registrations.id as reg_id, webinar_registrations.created_at as registered_at, users.id as user_id, COALESCE(auth_identities.secret, employers.contact_email) as email, users.username, users.user_type, job_seekers.full_name as js_full_name, job_seekers.phone as js_phone, job_seekers.profile_picture as js_avatar, employers.company_name as emp_company, employers.phone as emp_phone, employers.logo as emp_logo')
            ->join('users', 'users.id = webinar_registrations.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
            ->join('employers', 'employers.user_id = users.id', 'left')
            ->where('webinar_registrations.webinar_id', $id)
            ->orderBy('webinar_registrations.created_at', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($attendees as &$att) {
            if (!empty($att['js_full_name'])) {
                $att['full_name'] = $att['js_full_name'];
                $att['phone'] = $att['js_phone'] ?? '';
                $att['avatar'] = !empty($att['js_avatar']) ? base_url($att['js_avatar']) : null;
            } elseif (!empty($att['emp_company'])) {
                $att['full_name'] = $att['emp_company'];
                $att['phone'] = $att['emp_phone'] ?? '';
                $att['avatar'] = !empty($att['emp_logo']) ? base_url($att['emp_logo']) : null;
            } else {
                $att['full_name'] = !empty($att['username']) ? $att['username'] : 'User #' . ($att['user_id'] ?? '');
                $att['phone'] = '';
                $att['avatar'] = null;
            }
        }

        return $this->respond([
            'success'   => true,
            'webinar'   => $webinar,
            'count'     => count($attendees),
            'attendees' => $attendees
        ]);
    }

    /**
     * Export registered attendees for a specific webinar as CSV
     */
    public function exportWebinarAttendees($id)
    {
        $webinar = $this->webinarModel->find($id);
        if (!$webinar) {
            return redirect()->back()->with('error', 'Webinar not found');
        }

        $db = \Config\Database::connect();
        $attendees = $db->table('webinar_registrations')
            ->select('webinar_registrations.id as reg_id, webinar_registrations.created_at as registered_at, users.id as user_id, COALESCE(auth_identities.secret, employers.contact_email) as email, users.username, job_seekers.full_name as js_full_name, job_seekers.phone as js_phone, employers.company_name as emp_company, employers.phone as emp_phone')
            ->join('users', 'users.id = webinar_registrations.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
            ->join('employers', 'employers.user_id = users.id', 'left')
            ->where('webinar_registrations.webinar_id', $id)
            ->orderBy('webinar_registrations.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string)$webinar->title);
        $filename = 'attendees_' . $safeTitle . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['User ID', 'Full Name', 'Email', 'Phone', 'Registration Date', 'Webinar Title']);

        foreach ($attendees as $att) {
            $fullName = !empty($att['js_full_name']) ? $att['js_full_name'] : (!empty($att['emp_company']) ? $att['emp_company'] : ($att['username'] ?? 'User #' . $att['user_id']));
            $phone = !empty($att['js_phone']) ? $att['js_phone'] : ($att['emp_phone'] ?? '');

            fputcsv($output, [
                $att['user_id'] ?? 'N/A',
                $fullName,
                $att['email'] ?? 'N/A',
                $phone ?: 'N/A',
                $att['registered_at'] ?? '',
                $webinar->title
            ]);
        }

        fclose($output);
        exit;
    }

    public function deleteWebinar($id)
    {
        $this->webinarModel->delete($id);
        return redirect()->back()->with('success', 'Webinar deleted successfully.');
    }

    /**
     * Preview Newsletter Content
     */
    public function previewContent($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (!$newsletter) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Newsletter not found'
            ]);
        }

        return $this->response->setJSON([
            'success'    => true,
            'newsletter' => [
                'id'           => $newsletter->id,
                'title'        => $newsletter->title,
                'subject'      => $newsletter->subject,
                'target_group' => $newsletter->target_group,
                'status'       => $newsletter->status,
                'sent_at'      => $newsletter->sent_at,
                'created_by'   => $newsletter->created_by ?? 'Admin',
                'content'      => $newsletter->content,
            ]
        ]);
    }

    /**
     * View all newsletter audience segments & subscribers
     */
    public function adminSubscribers()
    {
        $db = \Config\Database::connect();

        // 1. Registered Candidates
        $candidates = $db->table('users')
            ->select('users.id as user_id, auth_identities.secret as email, users.username, users.active, users.created_at, job_seekers.id as candidate_id, job_seekers.full_name, job_seekers.phone, job_seekers.profile_picture')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
            ->where('users.user_type', 'candidate')
            ->orderBy('users.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // 2. Registered Employers
        $employers = $db->table('users')
            ->select('users.id as user_id, COALESCE(auth_identities.secret, employers.contact_email) as email, users.username, users.active, users.created_at, employers.id as employer_id, employers.company_name, employers.phone, employers.verification_status, employers.logo')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->join('employers', 'employers.user_id = users.id', 'left')
            ->where('users.user_type', 'employer')
            ->orderBy('users.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // 3. Guest Subscribers (Newsletter subscribers who are not registered accounts)
        $guestSubscribers = $this->subscriberModel
            ->where('user_id IS NULL')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // 4. Registered Subscribers (Newsletter subscribers linked to registered accounts)
        $registeredSubscribers = $db->table('newsletter_subscribers')
            ->select('newsletter_subscribers.id, newsletter_subscribers.email, newsletter_subscribers.user_id, newsletter_subscribers.is_active, newsletter_subscribers.created_at, users.user_type, users.username, job_seekers.full_name, employers.company_name')
            ->join('users', 'users.id = newsletter_subscribers.user_id', 'left')
            ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
            ->join('employers', 'employers.user_id = users.id', 'left')
            ->where('newsletter_subscribers.user_id IS NOT NULL')
            ->orderBy('newsletter_subscribers.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // 5. New Candidates (joined in last 30 days)
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $newCandidates = array_filter($candidates, fn($c) => ($c['created_at'] ?? '') >= $thirtyDaysAgo);

        // 6. New Employers (joined in last 30 days)
        $newEmployers = array_filter($employers, fn($e) => ($e['created_at'] ?? '') >= $thirtyDaysAgo);

        // 7. Webinar Registrants
        $webinarRegistrants = $db->table('webinar_registrations')
            ->select('webinar_registrations.id, webinar_registrations.created_at as registered_at, webinars.id as webinar_id, webinars.title as webinar_title, users.id as user_id, COALESCE(auth_identities.secret, employers.contact_email) as email, users.user_type, job_seekers.id as candidate_id, job_seekers.full_name, employers.id as employer_id, employers.company_name')
            ->join('webinars', 'webinars.id = webinar_registrations.webinar_id', 'left')
            ->join('users', 'users.id = webinar_registrations.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
            ->join('employers', 'employers.user_id = users.id', 'left')
            ->orderBy('webinar_registrations.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // 8. Training / Course Registrants
        $trainingRegistrants = [];
        if ($db->tableExists('course_enrollments')) {
            $trainingRegistrants = $db->table('course_enrollments')
                ->select('course_enrollments.id, course_enrollments.created_at as enrolled_at, course_enrollments.status, course_enrollments.progress, courses.id as course_id, courses.title as course_title, users.id as user_id, auth_identities.secret as email, job_seekers.id as candidate_id, job_seekers.full_name')
                ->join('courses', 'courses.id = course_enrollments.course_id', 'left')
                ->join('users', 'users.id = course_enrollments.user_id', 'left')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->join('job_seekers', 'job_seekers.user_id = users.id', 'left')
                ->orderBy('course_enrollments.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        return view('admin/newsletters/subscribers', [
            'title'                 => 'Newsletter Audience & Subscribers Management',
            'candidates'            => $candidates,
            'employers'             => $employers,
            'guestSubscribers'      => $guestSubscribers,
            'registeredSubscribers' => $registeredSubscribers,
            'newCandidates'         => $newCandidates,
            'newEmployers'          => $newEmployers,
            'webinarRegistrants'    => $webinarRegistrants,
            'trainingRegistrants'   => $trainingRegistrants,
            'subscribers'           => $this->subscriberModel->orderBy('created_at', 'DESC')->findAll()
        ]);
    }

    /**
     * Delete a subscriber
     */
    public function deleteSubscriber($id)
    {
        $this->subscriberModel->delete($id);
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }

    /**
     * Export subscriber emails as CSV
     */
    public function exportSubscribers()
    {
        $subscribers = $this->subscriberModel->where('is_active', 1)->findAll();
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Email', 'User ID', 'Subscribed At', 'Status']);
        
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber->email,
                $subscriber->user_id ?? 'Guest',
                $subscriber->created_at ?? '',
                $subscriber->is_active ? 'Active' : 'Inactive'
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Get list of all newsletter templates, auto-seeding defaults if empty.
     */
    public function listTemplates()
    {
        $templateModel = new NewsletterTemplateModel();
        
        if ($templateModel->countAllResults() === 0) {
            $defaults = [
                [
                    'name' => 'General Announcement Template',
                    'html_content' => view('emails/newsletter_general', [
                        'title'   => 'Important Platform Updates & Announcements',
                        'content' => '<h4>Welcome to the JobberRecruit Announcement!</h4><p>We are thrilled to bring you the latest developments, platform updates, and feature upgrades designed to make recruiting and career-building simpler for everyone.</p><p>Use this space to tell your subscribers about your news, product additions, or announcements.</p>',
                        'email'   => 'subscriber@example.com'
                    ])
                ],
                [
                    'name' => 'Candidate Careers Template',
                    'html_content' => view('emails/newsletter_candidate', [
                        'title'   => 'Top Career Tips & Job Search Success Strategies',
                        'content' => '<h4>Maximize Your Opportunities Today!</h4><p>Explore custom career hacks, mock interview guidance, and the best ways to match with employers looking for your exact skillset.</p><p>Stay ahead of the curve with our expert resources curated specially for candidates like you.</p>',
                        'email'   => 'candidate@example.com'
                    ])
                ],
                [
                    'name' => 'Employer Talent Update Template',
                    'html_content' => view('emails/newsletter_employer', [
                        'title'   => 'Attract, Screen & Retain Top Talent Efficiently',
                        'content' => '<h4>Build the Ultimate Team with JobberRecruit</h4><p>Learn how to utilize our smart screening workflows, aptitude testing tools, and direct talent sourcing filters to find matching candidates in record time.</p>',
                        'email'   => 'employer@example.com'
                    ])
                ]
            ];
            
            foreach ($defaults as $tmpl) {
                $templateModel->insert($tmpl);
            }
        }
        
        $templates = $templateModel->orderBy('created_at', 'DESC')->findAll();
        
        return $this->response->setJSON([
            'status'    => 'success',
            'templates' => $templates
        ]);
    }

    /**
     * Save a newsletter template into the library.
     */
    public function storeTemplate()
    {
        $name = $this->request->getPost('name');
        $html = $this->request->getPost('html_content');
        
        if (empty($name) || empty($html)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Template name and content are required'
            ]);
        }
        
        $templateModel = new NewsletterTemplateModel();
        $templateModel->insert([
            'name'         => $name,
            'html_content' => $html
        ]);
        
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Template successfully saved to library'
        ]);
    }
}
