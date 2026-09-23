<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\CourseEnrollmentModel;
use App\Models\CourseCertificateModel;
use App\Models\CourseModuleModel;
use App\Models\UserModel;
use App\Models\JobSeekerModel;
use App\Models\CertificateTemplateModel;
use App\Models\CvReviewModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;
use Spatie\Browsershot\Browsershot;

class ElearningController extends BaseController
{
    use ResponseTrait;

    protected $courseModel;
    protected $enrollmentModel;
    protected $courseModuleModel;

    public function __construct()
    {
        $this->courseModel      = new CourseModel();
        $this->enrollmentModel  = new CourseEnrollmentModel();
        $this->courseModuleModel = new \App\Models\CourseModuleModel();
    }

    /**
     * Public view certificate endpoint e.g. /certificate?id=CERT-37682AF5442B
     */
    public function publicViewCertificate()
    {
        $code = $this->request->getGet('id') ?: $this->request->getGet('code');
        if (!$code) {
            return redirect()->to('/certificates/verify')->with('error', 'Please provide a valid certificate ID');
        }

        $certModel = model(CourseCertificateModel::class);
        $certificate = $certModel->where('certificate_code', $code)->first();

        if (!$certificate) {
            return redirect()->to('/certificates/verify?id=' . urlencode($code))->with('error', 'Certificate not found');
        }

        $course = $this->courseModel->find($certificate['course_id']);
        $userModel = model(\App\Models\UserModel::class) ?: model(\CodeIgniter\Shield\Models\UserModel::class);
        $targetUser = $userModel ? $userModel->find($certificate['user_id']) : null;

        if (!$targetUser) {
            $targetUser = (object)['full_name' => 'Verified Candidate', 'username' => 'Candidate', 'id' => $certificate['user_id']];
        }
        if (empty($targetUser->full_name)) {
            $seekerModel = model(\App\Models\JobSeekerModel::class);
            $seeker = $seekerModel->where('user_id', $targetUser->id ?? $certificate['user_id'])->first();
            if ($seeker && !empty($seeker->full_name)) {
                $targetUser->full_name = $seeker->full_name;
            } elseif (!empty($targetUser->username)) {
                $targetUser->full_name = $targetUser->username;
            }
        }

        $templateModel = model(\App\Models\CertificateTemplateModel::class);
        $template = $templateModel->getTemplateForCourse($certificate['course_id'] ?? null);

        return view('certificates/course_certificate', [
            'certificate' => $certificate,
            'course'      => $course,
            'user'        => $targetUser,
            'template'    => $template,
        ]);
    }

    /**
     * Shortcut endpoint e.g. /verify/CERT-37682AF5442B
     */
    public function verifyCertificateByCode($code)
    {
        return redirect()->to('/certificates/verify?id=' . urlencode($code));
    }

    public function verifyCertificateForm()
    {
        $code = $this->request->getGet('id');
        $certificateData = null;

        if ($code) {
            $certModel = model(CourseCertificateModel::class);
            $cert = $certModel->where('certificate_code', $code)->first();

            if ($cert) {
                $userModel = model(\App\Models\UserModel::class);
                $user   = $userModel->find($cert['user_id']);
                $course = model(CourseModel::class)->find($cert['course_id']);

                if ($user && $course) {
                    $seekerModel = model(\App\Models\JobSeekerModel::class);
                    $seeker = $seekerModel->where('user_id', $user->id)->first();
                    $recipientName = ($seeker && !empty($seeker->full_name)) ? $seeker->full_name : ($user->username ?? $user->email ?? 'Learner');

                    $certificateData = [
                        'recipient'    => $recipientName,
                        'course_title' => $course->title ?? 'Unknown Course',
                        'type'         => 'Professional Training',
                        'issued_at'    => date('d F Y', strtotime($cert['issued_at'])),
                        'duration'     => $course->duration ?? 'N/A',
                        'code'         => $cert['certificate_code'],
                    ];
                }
            }
        }

        return view('certificates/verify', [
            'title'           => 'Verify Certificate | JobberRecruit',
            'code'            => $code,
            'certificateData' => $certificateData,
        ]);
    }

    /**
     * Public Marketplace
     */
    public function index()
    {
        $q = trim((string) ($this->request->getVar('q') ?? ''));
        $level = trim((string) ($this->request->getVar('level') ?? ''));
        $type = trim((string) ($this->request->getVar('type') ?? ''));
        $price = trim((string) ($this->request->getVar('price') ?? ''));

        // Total Counts (non-filtered stats for hero section)
        $totalCourses = $this->courseModel->where('is_active', 1)->findAll();
        $freeCount = count(array_filter(
            $totalCourses,
            static fn ($course) => (float) ($course->price ?? 0) <= 0
        ));

        // Filtered Query Builder
        $dbBuilder = $this->courseModel->where('is_active', 1);

        if ($q !== '') {
            $dbBuilder->groupStart()
                      ->like('title', $q)
                      ->orLike('description', $q)
                      ->orLike('instructor', $q)
                      ->groupEnd();
        }
        if ($level !== '') {
            $dbBuilder->where('level', $level);
        }
        if ($type !== '') {
            $dbBuilder->where('item_type', $type);
        }
        if ($price !== '') {
            if ($price === 'free') {
                $dbBuilder->where('price <=', 0);
            } elseif ($price === 'paid') {
                $dbBuilder->where('price >', 0);
            }
        }

        $courses = $dbBuilder
            ->orderBy('is_featured', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $featuredCourses = array_values(array_filter(
            $totalCourses,
            static fn ($course) => (int) ($course->is_featured ?? 0) === 1
        ));

        return view('home/elearning', [
            'title'             => 'Professional E-Learning & Career Training | JobberRecruit',
            'meta_description'  => 'Upgrade your skills with JobberRecruit E-Learning and Training Marketplace. Explore free & premium certification courses in Tech, Business Management, sales, and more.',
            'keywords'          => 'elearning Nigeria, professional courses Lagos, online training, job skills, career development, IT certification, interview preparation, JobberRecruit',
            'og_title'          => 'Professional E-Learning & Career Training | JobberRecruit',
            'og_description'    => 'Upgrade your skills with JobberRecruit E-Learning and Training Marketplace. Explore free & premium certification courses.',
            'courses'           => $courses,
            'featuredCourses'   => array_slice($featuredCourses, 0, 3),
            'freeCount'         => $freeCount,
            'paidCount'         => count($totalCourses) - $freeCount,
            'totalActive'       => count($totalCourses),
            'q'                 => $q,
            'level'             => $level,
            'type'              => $type,
            'price'             => $price,
        ]);
    }

    public function show($id)
    {
        $course = $this->courseModel
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();

        if (! $course) {
            throw PageNotFoundException::forPageNotFound('Course not found.');
        }

        $enrollment = $this->getEnrollmentForCurrentUser((int) $course->id);
        $canAccessContent = $this->canAccessCourse($course, $enrollment);

        $relatedCourses = $this->courseModel
            ->where('is_active', 1)
            ->where('id !=', $course->id)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll(3);

        $cleanDescription = esc(mb_substr(strip_tags((string) $course->description), 0, 155));

        $modules = $this->courseModuleModel->where('course_id', $course->id)->orderBy('order_index', 'ASC')->findAll();

        $walletBalance = 0.0;
        if (auth()->loggedIn()) {
            $walletObj = (new \App\Services\WalletService())->getOrCreateWallet(auth()->id());
            $walletBalance = (float) ($walletObj->balance ?? 0);
        }

        return view('home/elearning_detail', [
            'title'             => esc($course->title) . ' - E-Learning Course | JobberRecruit',
            'meta_description'  => $cleanDescription . '...',
            'keywords'          => esc($course->title) . ', online course, ' . esc($course->instructor ?: 'JobberRecruit') . ', free training, online certification',
            'og_title'          => esc($course->title) . ' | JobberRecruit',
            'og_description'    => $cleanDescription . '...',
            'og_image'          => $course->thumbnail ? base_url($course->thumbnail) : base_url('images/default-og-image.jpg'),
            'course'            => $course,
            'modules'           => $modules,
            'enrollment'        => $enrollment,
            'canAccessContent'  => $canAccessContent,
            'youtubeEmbedUrl'   => $this->getYoutubeEmbedUrl($course->youtube_url ?? null),
            'relatedCourses'    => $relatedCourses,
            'walletBalance'     => $walletBalance,
        ]);
    }

    /**
     * Admin: Manage Courses
     */
    public function adminIndex()
    {
        $courses = $this->courseModel
            ->orderBy('is_featured', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($courses as &$c) {
            $courseId = is_object($c) ? $c->id : $c['id'];
            $modules = $this->courseModuleModel
                ->where('course_id', $courseId)
                ->orderBy('order_index', 'ASC')
                ->findAll();
            if (is_object($c)) {
                $c->modules = $modules;
            } else {
                $c['modules'] = $modules;
            }
        }

        return view('admin/elearning/index', [
            'title'   => 'Manage Courses',
            'courses' => $courses,
        ]);
    }

    /**
     * Admin: Save Course
     */
    public function saveCourse()
    {
        $id = $this->request->getPost('id') ?: null;
        $existing = $id ? $this->courseModel->find($id) : null;
        $title = trim((string) $this->request->getPost('title'));
        $contentSource = (string) $this->request->getPost('content_source');
        $allowedSources = ['none', 'youtube', 'upload'];

        if ($title === '') {
            return redirect()->back()->with('error', 'Course title is required.');
        }

        if (! in_array($contentSource, $allowedSources, true)) {
            $contentSource = 'none';
        }

        $durationVal  = $this->request->getPost('duration_value');
        $durationUnit = $this->request->getPost('duration_unit');
        $durationRaw  = $this->request->getPost('duration');
        $duration     = $this->formatCourseDuration($durationRaw, $durationVal, $durationUnit);

        $data = [
            'title'          => $title,
            'slug'           => $this->generateUniqueSlug($title, $id ? (int) $id : null),
            'item_type'      => $this->request->getPost('item_type') ?: 'course',
            'description'    => $this->request->getPost('description'),
            'instructor'     => $this->request->getPost('instructor'),
            'price'          => (float) ($this->request->getPost('price') ?: 0),
            'duration'       => $duration,
            'level'          => trim((string) $this->request->getPost('level')) ?: 'beginner',
            'content_source' => $contentSource,
            'youtube_url'    => $contentSource === 'youtube'
                ? (trim((string) $this->request->getPost('youtube_url')) ?: null)
                : null,
            'is_featured'    => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active'      => $this->request->getPost('status') === 'active' ? 1 : 0,
        ];

        // Handle thumbnail
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $thumbnailDir = FCPATH . 'uploads/courses/thumbnails';
            if (! is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0775, true);
            }

            $file->move($thumbnailDir, $newName);
            $data['thumbnail'] = 'uploads/courses/thumbnails/' . $newName;
        }

        $contentFile = $this->request->getFile('content_file');
        if ($contentSource === 'upload' && $contentFile && $contentFile->isValid() && ! $contentFile->hasMoved()) {
            $contentDir = WRITEPATH . 'uploads/courses';
            if (! is_dir($contentDir)) {
                mkdir($contentDir, 0775, true);
            }

            $newContentName = $contentFile->getRandomName();
            $contentFile->move($contentDir, $newContentName);
            $data['content_file'] = 'courses/' . $newContentName;
        } elseif ($contentSource === 'upload' && $existing?->content_file) {
            $data['content_file'] = $existing->content_file;
        } elseif ($contentSource !== 'upload') {
            $data['content_file'] = null;
        }

        if ($id) {
            $this->courseModel->update($id, $data);
            $targetCourseId = (int)$id;
            $msg = 'Course updated successfully';
        } else {
            $this->courseModel->insert($data);
            $targetCourseId = (int)$this->courseModel->getInsertID();
            $msg = 'Course created successfully';
        }

        // Process deleted modules if any were removed
        $deleteModuleIds = $this->request->getPost('delete_module_ids');
        if (is_array($deleteModuleIds) && !empty($deleteModuleIds) && $targetCourseId > 0) {
            foreach ($deleteModuleIds as $delId) {
                $delId = (int)$delId;
                if ($delId > 0) {
                    $this->courseModuleModel->where('id', $delId)->where('course_id', $targetCourseId)->delete();
                }
            }
        }

        // Save inline modules if provided in form
        $moduleTitles = $this->request->getPost('module_titles');
        if (is_array($moduleTitles) && !empty($moduleTitles) && $targetCourseId > 0) {
            $moduleDescriptions = $this->request->getPost('module_descriptions');
            $moduleSources      = $this->request->getPost('module_sources');
            $moduleYoutubeUrls  = $this->request->getPost('module_youtube_urls');
            $moduleIds          = $this->request->getPost('module_ids');

            foreach ($moduleTitles as $idx => $modTitle) {
                $modTitle = trim((string)$modTitle);
                if (empty($modTitle)) continue;

                $modDesc = $moduleDescriptions[$idx] ?? '';
                $modSrc  = $moduleSources[$idx] ?? 'none';
                $modYt   = $modSrc === 'youtube' ? (trim((string)($moduleYoutubeUrls[$idx] ?? '')) ?: null) : null;
                $modId   = isset($moduleIds[$idx]) && (int)$moduleIds[$idx] > 0 ? (int)$moduleIds[$idx] : null;

                $modData = [
                    'course_id'      => $targetCourseId,
                    'title'          => $modTitle,
                    'description'    => $modDesc,
                    'content_source' => $modSrc,
                    'youtube_url'    => $modYt,
                    'order_index'    => $idx + 1
                ];

                if ($modId) {
                    $this->courseModuleModel->update($modId, $modData);
                } else {
                    $this->courseModuleModel->insert($modData);
                }
            }
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Helper to parse and format course duration strictly in Days or Hours.
     */
    public function formatCourseDuration(?string $durationInput, ?string $valInput = null, ?string $unitInput = null): ?string
    {
        if ($valInput !== null && $valInput !== '' && is_numeric($valInput)) {
            $num = floatval($valInput);
            if ($num <= 0) {
                return null;
            }
            $numStr = ($num == (int)$num) ? (string)(int)$num : (string)$num;
            $unit = strtolower(trim((string)$unitInput));
            if (in_array($unit, ['hour', 'hours', 'hr', 'hrs', 'h'], true)) {
                return $numStr . ($num == 1 ? ' Hour' : ' Hours');
            }
            return $numStr . ($num == 1 ? ' Day' : ' Days');
        }

        $str = trim((string)$durationInput);
        if ($str === '') {
            return null;
        }

        if (preg_match('/(\d+(?:\.\d+)?)\s*(h|hr|hrs|hour|hours)/i', $str, $matches)) {
            $num = floatval($matches[1]);
            $numStr = ($num == (int)$num) ? (string)(int)$num : (string)$num;
            return $numStr . ($num == 1 ? ' Hour' : ' Hours');
        }

        if (preg_match('/(\d+(?:\.\d+)?)\s*(d|day|days)/i', $str, $matches)) {
            $num = floatval($matches[1]);
            $numStr = ($num == (int)$num) ? (string)(int)$num : (string)$num;
            return $numStr . ($num == 1 ? ' Day' : ' Days');
        }

        if (preg_match('/(\d+(?:\.\d+)?)\s*(w|wk|wks|week|weeks)/i', $str, $matches)) {
            $num = floatval($matches[1]) * 7;
            $numStr = ($num == (int)$num) ? (string)(int)$num : (string)$num;
            return $numStr . ($num == 1 ? ' Day' : ' Days');
        }

        if (is_numeric($str)) {
            $num = floatval($str);
            $numStr = ($num == (int)$num) ? (string)(int)$num : (string)$num;
            return $numStr . ($num == 1 ? ' Day' : ' Days');
        }

        return $str;
    }

    /**
     * Serve uploaded course content after access checks.
     */
    public function content($id)
    {
        $course = $this->courseModel
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();

        if (! $course) {
            return redirect()->to('training')->with('error', 'Course not found.');
        }

        if (! $this->canAccessCourse($course, $this->getEnrollmentForCurrentUser((int) $course->id))) {
            return redirect()->to('training/course/' . $course->id)
                ->with('error', 'Please enroll in this course to access the content.');
        }

        $moduleId = $this->request->getGet('module_id');
        $fileToServe = null;

        if ($moduleId) {
            $module = $this->courseModuleModel->find($moduleId);
            if ($module && $module->course_id == $course->id && $module->content_source === 'upload' && !empty($module->content_file)) {
                $fileToServe = $module->content_file;
            }
        } elseif (($course->content_source ?? 'none') === 'upload' && !empty($course->content_file)) {
            $fileToServe = $course->content_file;
        }

        if (!$fileToServe) {
            return redirect()->to('training/course/' . $course->id)
                ->with('error', 'The requested file is not available.');
        }

        $path = WRITEPATH . 'uploads/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileToServe);
        if (! is_file($path)) {
            return redirect()->to('training/course/' . $course->id)
                ->with('error', 'The uploaded file is missing on the server.');
        }

        return $this->response->download($path, null)->setFileName(basename($path));
    }

    /**
     * Candidate: Enroll in a course (with Payment support)
     */
    public function enroll($id = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login to enroll in courses');
        }

        if ($id === null) {
            $id = $this->request->getVar('id') ?? $this->request->getVar('course_id');
        }

        if (empty($id)) {
            return redirect()->to('training')->with('error', 'Course ID is required to enroll.');
        }

        $userId = auth()->id();
        $course = $this->courseModel->find($id);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }
        
        // Check if already enrolled
        $existing = $this->enrollmentModel->where(['course_id' => $id, 'user_id' => $userId])->first();
        if ($existing) {
            return redirect()->to('training/course/' . $id)->with('info', 'You are already enrolled in this course');
        }

        // Handle Paid Courses
        if ($course->price > 0) {
            $paymentMethod = $this->request->getVar('payment_method');
            if ($paymentMethod === 'wallet') {
                $walletService = new \App\Services\WalletService();
                $wallet = $walletService->getOrCreateWallet($userId);

                if ((float) $wallet->balance < (float) $course->price) {
                    return redirect()->back()->with('error', 'Insufficient wallet balance (₦' . number_format($wallet->balance, 2) . '). Please top up your wallet or pay via Paystack.');
                }

                $reference = 'course_w_' . uniqid();
                $walletService->debit(
                    userId: $userId,
                    amount: (float) $course->price,
                    source: 'wallet_checkout',
                    reference: $reference,
                    sourceId: (int) $id,
                    description: 'Paid with wallet for course: ' . $course->title
                );

                // Insert into payment ledger
                $paymentModel = model(\App\Models\PaymentModel::class);
                $paymentModel->insert([
                    'user_id'        => $userId,
                    'reference'      => $reference,
                    'amount'         => $course->price,
                    'status'         => 'paid',
                    'payment_method' => 'wallet',
                    'paid_at'        => date('Y-m-d H:i:s'),
                ]);

                // Enroll user
                $this->enrollmentModel->insert([
                    'course_id'         => $id,
                    'user_id'           => $userId,
                    'status'            => 'enrolled',
                    'payment_reference' => $reference,
                    'amount'            => $course->price
                ]);

                try {
                    $emailNotifService = new \App\Services\EmailNotificationService();
                    $emailNotifService->sendCourseEnrollmentNotification(auth()->user(), $course);
                } catch (\Throwable $e) {
                    log_message('error', 'Paid course enrollment notification error: ' . $e->getMessage());
                }

                return redirect()->to('training/payment-acknowledgement/' . $id . '?reference=' . urlencode($reference) . '&method=wallet')->with('success', 'Enrolled successfully using your wallet balance!');
            }

            $paystack = new \App\Services\PaystackService();
            $email = auth()->user()->email;
            $callbackUrl = base_url("training/verify/{$id}");
            
            $response = $paystack->initialize($email, $course->price, $callbackUrl, [
                'course_id' => $id,
                'user_id' => $userId
            ]);

            if ($response['status']) {
                return redirect()->to($response['data']['authorization_url']);
            } else {
                return redirect()->back()->with('error', 'Payment initialization failed: ' . $response['message']);
            }
        }

        // Free Course Enrollment
        $this->enrollmentModel->insert([
            'course_id' => $id,
            'user_id' => $userId,
            'status' => 'enrolled',
            'amount' => 0
        ]);

        try {
            $emailNotifService = new \App\Services\EmailNotificationService();
            $emailNotifService->sendCourseEnrollmentNotification(auth()->user(), $course);
        } catch (\Throwable $e) {
            log_message('error', 'Course enrollment notification error: ' . $e->getMessage());
        }

        return redirect()->to('candidate/my-courses/' . $id)->with('success', 'Enrolled successfully! Welcome to your interactive classroom.');
    }

    /**
     * Verify Paystack Payment
     */
    public function verify($courseId)
    {
        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('training')->with('error', 'Invalid payment reference');
        }

        $paystack = new \App\Services\PaystackService();
        $response = $paystack->verify($reference);

        if ($response['status'] && $response['data']['status'] === 'success') {
            $userId = auth()->id();
            
            // Finalize Enrollment
            $this->enrollmentModel->insert([
                'course_id' => $courseId,
                'user_id' => $userId,
                'status' => 'enrolled',
                'payment_reference' => $reference,
                'amount' => $response['data']['amount'] / 100
            ]);

            $course = $this->courseModel->find($courseId);
            try {
                $emailNotifService = new \App\Services\EmailNotificationService();
                $emailNotifService->sendCourseEnrollmentNotification(auth()->user(), $course);
            } catch (\Throwable $e) {
                log_message('error', 'Paid course enrollment notification error: ' . $e->getMessage());
            }

            return redirect()->to('training/payment-acknowledgement/' . $courseId . '?reference=' . urlencode($reference) . '&method=paystack')->with('success', 'Payment successful! Seat confirmed.');
        }

        return redirect()->to('training')->with('error', 'Payment verification failed');
    }

    /**
     * Display Payment Acknowledgement & Receipt for a Course Purchase
     */
    public function paymentAcknowledgement($courseId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login to view payment receipt.');
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->to('training')->with('error', 'Course not found.');
        }

        $userId = auth()->id();
        $enrollment = $this->enrollmentModel->where([
            'course_id' => $courseId,
            'user_id'   => $userId
        ])->first();

        if (!$enrollment) {
            return redirect()->to('training/course/' . $courseId)->with('error', 'No active enrollment found for this course.');
        }

        $reference = $this->request->getGet('reference') ?? ($enrollment['payment_reference'] ?? ('JR-PAY-' . $courseId . '-' . $userId));
        $method = $this->request->getGet('method') ?? 'Paystack / Wallet';

        $candidate = model(\App\Models\JobSeekerModel::class)->where('user_id', $userId)->first();
        $user = auth()->user();

        return view('home/course_payment_acknowledgement', [
            'title'       => 'Payment Acknowledgement | ' . esc($course->title),
            'course'      => $course,
            'enrollment'  => $enrollment,
            'reference'   => $reference,
            'method'      => $method,
            'candidate'   => $candidate,
            'user'        => $user,
            'amountPaid'  => (float) ($enrollment['amount'] ?? $course->price),
            'classroomUrl'=> base_url('candidate/my-courses/' . $courseId),
        ]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = url_title($title, '-', true);
        $slug = $slug !== '' ? $slug : 'course';
        $candidate = $slug;
        $suffix = 2;

        while ($this->slugExists($candidate, $ignoreId)) {
            $candidate = $slug . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = $this->courseModel->where('slug', $slug);
        if ($ignoreId !== null) {
            $builder = $builder->where('id !=', $ignoreId);
        }

        return $builder->first() !== null;
    }

    private function getEnrollmentForCurrentUser(int $courseId): ?object
    {
        if (! auth()->loggedIn()) {
            return null;
        }

        return $this->enrollmentModel
            ->where('course_id', $courseId)
            ->where('user_id', auth()->id())
            ->first();
    }

    private function canAccessCourse(object $course, ?object $enrollment): bool
    {
        return (float) ($course->price ?? 0) <= 0 || $enrollment !== null;
    }

    private function getYoutubeEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $parsed = parse_url($url);
        if ($parsed === false) {
            return null;
        }

        $host = strtolower($parsed['host'] ?? '');
        if (str_contains($host, 'youtu.be')) {
            $videoId = trim($parsed['path'] ?? '', '/');
            return $videoId !== '' ? 'https://www.youtube.com/embed/' . $videoId : null;
        }

        if (str_contains($host, 'youtube.com')) {
            parse_str($parsed['query'] ?? '', $query);
            if (! empty($query['v'])) {
                return 'https://www.youtube.com/embed/' . $query['v'];
            }
        }

        return null;
    }

    /**
     * Candidate: View my enrolled courses
     */
    public function myCourses()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login');
        }

        $enrollments = $this->enrollmentModel
            ->select('course_enrollments.*, courses.title as course_title, courses.thumbnail, courses.instructor, courses.duration, courses.content_source, courses.price')
            ->join('courses', 'courses.id = course_enrollments.course_id', 'left')
            ->where('course_enrollments.user_id', auth()->id())
            ->orderBy('course_enrollments.created_at', 'DESC')
            ->findAll();

        return view('candidate/my_courses', [
            'title' => 'My Courses',
            'enrollments' => $enrollments,
        ]);
    }

    /**
     * Candidate Dashboard: Browse internal course catalog
     */
    public function browseCourses()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login');
        }

        $courseModel = new \App\Models\CourseModel();
        $courses = $courseModel->where('is_active', 1)->orderBy('id', 'DESC')->findAll();

        $enrolledIds = array_column(
            $this->enrollmentModel->where('user_id', auth()->id())->findAll(),
            'course_id'
        );

        return view('candidate/browse_courses', [
            'title'       => 'Browse Courses',
            'courses'     => $courses,
            'enrolledIds' => $enrolledIds,
        ]);
    }

    /**
     * Candidate: Interactive Classroom Workspace
     */
    public function classroom($courseId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login to access the classroom');
        }

        $userId = auth()->id();
        
        // Fetch course
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->to('training')->with('error', 'Course not found.');
        }

        // Fetch user enrollment
        $enrollment = $this->enrollmentModel
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        // If not enrolled but it is a paid course, block them
        if (!$enrollment && (float)($course->price ?? 0) > 0) {
            return redirect()->to('training/course/' . $courseId)->with('error', 'Please enroll in this course to access the classroom.');
        }

        // Auto enroll them if it's a free course and they clicked through
        if (!$enrollment && (float)($course->price ?? 0) <= 0) {
            $this->enrollmentModel->insert([
                'course_id' => $courseId,
                'user_id' => $userId,
                'status' => 'enrolled',
                'amount' => 0
            ]);
            $enrollment = $this->enrollmentModel
                ->where('course_id', $courseId)
                ->where('user_id', $userId)
                ->first();
        }

        // Fetch modules
        $modules = $this->courseModuleModel
            ->where('course_id', $courseId)
            ->orderBy('order_index', 'ASC')
            ->findAll();

        // Pre-resolve YouTube embed URLs per module — the view can't call this
        // private controller method itself ($this inside a view is the View object).
        $moduleEmbeds = [];
        foreach ($modules as $mod) {
            if (($mod->content_source ?? null) === 'youtube' && !empty($mod->youtube_url)) {
                $moduleEmbeds[$mod->id] = $this->getYoutubeEmbedUrl($mod->youtube_url);
            }
        }

        // Determine active module
        $activeModuleId = $this->request->getGet('module_id');
        $activeModule = null;

        if ($activeModuleId) {
            foreach ($modules as $mod) {
                if ((int)$mod->id === (int)$activeModuleId) {
                    $activeModule = $mod;
                    break;
                }
            }
        }

        // Default to the first module if no active module found/selected
        if (!$activeModule && !empty($modules)) {
            $activeModule = $modules[0];
        }

        // Fetch certificate if completed or existing
        $certModel = model(CourseCertificateModel::class);
        $certificate = $certModel->getCertificateForUser($userId, $courseId);
        if ($certificate && $enrollment && $enrollment->status !== 'completed') {
            $this->enrollmentModel->update($enrollment->id, [
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => $certificate['issued_at'] ?? date('Y-m-d H:i:s')
            ]);
            $enrollment->status = 'completed';
        }

        // Get candidate name
        $seekerModel = model(\App\Models\JobSeekerModel::class);
        $seeker = $seekerModel->where('user_id', $userId)->first();
        $candidateName = ($seeker && !empty($seeker->full_name)) ? $seeker->full_name : (auth()->user()->username ?? 'Student');

        // Dynamic Career Intelligence calculation
        $profileCompletion = 0;
        if ($seeker) {
            $profileCompletion = method_exists($seeker, 'getProfileCompletion') 
                ? $seeker->getProfileCompletion() 
                : (!empty($seeker->profile_completion) ? (int)$seeker->profile_completion : 70);
        }
        if ($profileCompletion <= 0) {
            $profileCompletion = 72;
        }

        // 1. Resume Score (evaluated against CV upload, review & profile)
        $hasResume = !empty($seeker?->resume);
        $cvReviewModel = model(\App\Models\CvReviewModel::class);
        $cvReview = $cvReviewModel ? $cvReviewModel->where('user_id', $userId)->where('status', 'completed')->first() : null;
        if ($cvReview) {
            $resumeScore = 88;
        } else {
            $resumeScore = (int) min(98, max(50, round(($profileCompletion * 0.7) + ($hasResume ? 26 : 8))));
        }

        // 2. ATS Match based on skills & role alignment
        $hasSkills = !empty($seeker?->skills);
        $atsMatch = (int) min(96, max(58, round(62 + ($profileCompletion * 0.24) + ($hasSkills ? 10 : 0))));

        // 3. Interview Prep based on mock sessions / experience
        $mockModel = model(\App\Models\MockInterviewSessionModel::class);
        $mockSessions = $mockModel ? $mockModel->where('user_id', $userId)->findAll(5) : [];
        if (!empty($mockSessions)) {
            $scores = array_filter(array_column($mockSessions, 'overall_score'));
            $interviewPrep = !empty($scores) ? (int)round((array_sum($scores) / count($scores)) * 10) : 75;
        } else {
            $experienceYears = (int)($seeker?->experience_years ?? 2);
            $interviewPrep = (int) min(94, max(55, round(52 + ($profileCompletion * 0.25) + min(15, $experienceYears * 3))));
        }

        // 4. Career Readiness composite score
        $careerReadiness = (int) min(99, max(55, round(
            ($resumeScore * 0.30) + 
            ($atsMatch * 0.30) + 
            ($interviewPrep * 0.25) + 
            ($profileCompletion * 0.15)
        )));

        // 5. Employability composite index
        $certsCount = $certModel->where('user_id', $userId)->countAllResults();
        $employability = (int) min(98, max(55, round(
            ($careerReadiness * 0.45) + 
            ($profileCompletion * 0.35) + 
            (min(5, $certsCount) * 4) + 12
        )));

        // 6. Skill Growth boost from enrolled and completed courses
        $progressBonus = ($enrollment && $enrollment->status === 'completed') ? 14 : (int)(($enrollment->progress ?? 0) * 0.10);
        $skillGrowthVal = (int) min(45, max(12, 15 + $progressBonus + (min(4, $certsCount) * 3)));
        $skillGrowth = '+' . $skillGrowthVal . '%';

        // 7. Dynamic Next Course recommendation
        $nextCourse = $this->courseModel
            ->where('id !=', $courseId)
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->first();

        // 8. Dynamic Matched Open Jobs
        $jobModel = model(\App\Models\JobModel::class);
        $matchedJobs = [];
        if ($jobModel) {
            $courseTitle = $course->title ?? '';
            $stopwords = ['and', 'for', 'the', 'with', 'from', 'into', 'over', 'your', 'course', 'training', 'masterclass', 'complete', 'guide', 'basic', 'advanced', 'program', 'programme', 'learn', 'introduction', 'professional'];
            $words = preg_split('/[\s,\-\:\&\|\/]+/', strtolower($courseTitle));
            $keywords = [];
            foreach ($words as $w) {
                $w = trim($w);
                if (strlen($w) >= 3 && !in_array($w, $stopwords, true)) {
                    $keywords[] = $w;
                }
            }

            $jobQuery = $jobModel
                ->select('jobs.*, states.name as location_name, employers.company_name as employer_name, employers.logo as employer_logo, employers.is_verified')
                ->join('states', 'states.id = jobs.state_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->where('jobs.status', 'open')
                ->where('jobs.admin_status', 'approved');

            if (!empty($course->category_id)) {
                $jobQuery->where('jobs.category_id', $course->category_id);
            } elseif (!empty($keywords)) {
                $jobQuery->groupStart();
                foreach ($keywords as $idx => $kw) {
                    if ($idx === 0) {
                        $jobQuery->like('jobs.title', $kw)
                                 ->orLike('jobs.description', $kw)
                                 ->orLike('jobs.skills', $kw);
                    } else {
                        $jobQuery->orLike('jobs.title', $kw)
                                 ->orLike('jobs.description', $kw)
                                 ->orLike('jobs.skills', $kw);
                    }
                }
                $jobQuery->groupEnd();
            }

            $matchedJobs = $jobQuery->orderBy('jobs.is_featured', 'DESC')
                                    ->orderBy('jobs.id', 'DESC')
                                    ->findAll(6);

            if (count($matchedJobs) < 3) {
                $existingIds = array_filter(array_map(function($j) { return is_object($j) ? $j->id : ($j['id'] ?? null); }, $matchedJobs));
                $fallbackQuery = $jobModel
                    ->select('jobs.*, states.name as location_name, employers.company_name as employer_name, employers.logo as employer_logo, employers.is_verified')
                    ->join('states', 'states.id = jobs.state_id', 'left')
                    ->join('employers', 'employers.id = jobs.employer_id', 'left')
                    ->where('jobs.status', 'open')
                    ->where('jobs.admin_status', 'approved');

                if (!empty($existingIds)) {
                    $fallbackQuery->whereNotIn('jobs.id', $existingIds);
                }
                $fallbackJobs = $fallbackQuery->orderBy('jobs.is_featured', 'DESC')
                                              ->orderBy('jobs.id', 'DESC')
                                              ->findAll(6 - count($matchedJobs));
                $matchedJobs = array_merge($matchedJobs, $fallbackJobs);
            }
        }

        // Telemetry stats
        $hoursStudied = number_format(max(1.2, round((count($modules) * 0.6) + (($enrollment->progress ?? 0) * 0.03), 1)), 1);
        $avgAccuracy = !empty($enrollment->test_score) ? (int)$enrollment->test_score : (($enrollment->status === 'completed') ? 100 : 85);

        $careerIntelligence = [
            'career_readiness' => $careerReadiness,
            'resume_score'     => $resumeScore,
            'ats_match'        => $atsMatch,
            'interview_prep'   => $interviewPrep,
            'employability'    => $employability,
            'skill_growth'     => $skillGrowth,
            'skill_growth_val' => min(100, $skillGrowthVal * 2.2), // scaled for data-fill 0-100%
            'next_course'      => $nextCourse,
            'matched_jobs'     => $matchedJobs,
        ];

        return view('candidate/classroom', [
            'title'              => esc($course->title) . ' - Learning Portal',
            'course'             => $course,
            'enrollment'         => (object) $enrollment,
            'modules'            => $modules,
            'activeModule'       => $activeModule,
            'certificate'        => $certificate,
            'candidateName'      => $candidateName,
            'youtubeEmbedUrl'    => $activeModule ? $this->getYoutubeEmbedUrl($activeModule->youtube_url ?? null) : null,
            'moduleEmbeds'       => $moduleEmbeds,
            'careerIntelligence' => $careerIntelligence,
            'hoursStudied'       => $hoursStudied,
            'avgAccuracy'        => $avgAccuracy,
            'matchedJobs'        => $matchedJobs,
        ]);
    }

    /**
     * Mark course as complete and generate certificate
     */
    public function completeCourse($courseId)
    {
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('Please login');
        }

        $userId = auth()->id();
        $course = $this->courseModel->find($courseId);

        if (!$course) {
            return $this->failNotFound('Course not found');
        }

        $enrollment = $this->enrollmentModel
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        if (!$enrollment) {
            return $this->fail('You are not enrolled in this course');
        }

        // Ensure test_attempts column exists
        $db = \Config\Database::connect();
        if (!$db->fieldExists('test_attempts', 'course_enrollments')) {
            $forge = \Config\Database::forge();
            $forge->addColumn('course_enrollments', [
                'test_attempts' => ['type' => 'INT', 'default' => 0]
            ]);
        }

        $attempts = (int) ($enrollment->test_attempts ?? 0);

        // Validate that candidate took the test and scored at least 70%
        $scoreRaw = $this->request->getPost('score')
            ?? $this->request->getVar('score')
            ?? $this->request->getGet('score');

        if ($scoreRaw === null) {
            $json = $this->request->getJSON(true);
            if (is_array($json) && isset($json['score'])) {
                $scoreRaw = $json['score'];
            }
        }
        if ($scoreRaw === null) {
            $rawInput = $this->request->getRawInput();
            if (is_array($rawInput) && isset($rawInput['score'])) {
                $scoreRaw = $rawInput['score'];
            }
        }

        $score = ($scoreRaw !== null && $scoreRaw !== '') ? (float) $scoreRaw : null;

        // Increment attempt count
        $attempts++;
        $this->enrollmentModel->update($enrollment->id, ['test_attempts' => $attempts]);

        if ($score === null || $score < 70) {
            $remaining = max(0, 3 - $attempts);
            return $this->respond([
                'success' => false,
                'score' => $score ?? 0,
                'attempts' => $attempts,
                'remaining_attempts' => $remaining,
                'message' => 'You must score at least 70% to receive a certificate. You scored ' . ($score ?? 0) . '%. (' . $remaining . ' retake(s) remaining).'
            ], 200);
        }

        $certModel = model(CourseCertificateModel::class);
        $existing = $certModel->getCertificateForUser($userId, $courseId);
        if ($existing) {
            $this->enrollmentModel->update($enrollment->id, [
                'status' => 'completed',
                'completed_at' => $existing['issued_at'] ?? date('Y-m-d H:i:s'),
                'progress' => 100,
            ]);
            return $this->respond([
                'success' => true,
                'score' => $score,
                'certificate_code' => $existing['certificate_code'],
                'certificate_id' => $existing['id'],
                'message' => 'Course completed! Certificate verified.',
            ], 200);
        }

        $updateData = [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'progress' => 100,
        ];
        if ($this->enrollmentModel->db->fieldExists('test_score', 'course_enrollments')) {
            $updateData['test_score'] = $score;
        }
        $this->enrollmentModel->update($enrollment->id, $updateData);

        $certCode = $certModel->generateCertificateCode();
        $certId = $certModel->insert([
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_id' => $enrollment->id,
            'certificate_code' => $certCode,
            'issued_at' => date('Y-m-d H:i:s'),
        ]);

        try {
            $emailNotifService = new \App\Services\EmailNotificationService();
            $certUrl = base_url("training/certificate/download/{$certId}");
            $emailNotifService->sendCourseCompletedNotification(auth()->user(), $course, $certUrl);
        } catch (\Throwable $e) {
            log_message('error', 'Course completed notification error: ' . $e->getMessage());
        }

        return $this->respond([
            'success' => true,
            'score' => $score,
            'passed' => true,
            'message' => 'Congratulations! Assessment passed and certificate issued.',
            'certificate_id' => $certId,
        ]);
    }

    /**
     * Update Course Lesson Progress via AJAX
     */
    public function updateCourseProgress($courseId)
    {
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('Please login to update course progress');
        }

        $userId = auth()->id();
        $progress = (int) $this->request->getVar('progress');
        $progress = max(0, min(100, $progress));

        $enrollment = $this->enrollmentModel->where([
            'course_id' => $courseId,
            'user_id'   => $userId
        ])->first();

        if ($enrollment) {
            $this->enrollmentModel->update($enrollment->id, [
                'progress' => $progress
            ]);
            return $this->respond(['success' => true, 'progress' => $progress]);
        }

        return $this->failNotFound('Enrollment not found');
    }

    /**
     * Download certificate as PDF
     */
    public function downloadCertificate($certificateId, $extraParam = null)
    {
        $certModel = model(CourseCertificateModel::class);
        $certificate = null;

        if ($certificateId === 'course' && $extraParam !== null) {
            $courseId = (int) $extraParam;
            $userId = auth()->id();
            if ($userId) {
                $certificate = $certModel->getCertificateForUser($userId, $courseId);
            }
        } elseif (is_numeric($certificateId)) {
            $certificate = $certModel->find($certificateId);
            if (!$certificate && auth()->loggedIn()) {
                $certificate = $certModel->getCertificateForUser(auth()->id(), (int)$certificateId);
            }
        } else {
            $certificate = $certModel->where('certificate_code', $certificateId)->first();
        }

        if (!$certificate) {
            return redirect()->to('training/certificates')->with('error', 'Certificate not found');
        }

        // Handle manual override certificate downloads
        if (!empty($certificate['manual_certificate'])) {
            $filePath = FCPATH . 'uploads/' . $certificate['manual_certificate'];
            if (file_exists($filePath)) {
                return $this->response->download($filePath, null)
                    ->setFileName('certificate-' . $certificate['certificate_code'] . '.pdf');
            }
        }

        $course = $this->courseModel->find($certificate['course_id']);
        $userModel = model(\App\Models\UserModel::class) ?: model(\CodeIgniter\Shield\Models\UserModel::class);
        $targetUser = $userModel ? $userModel->find($certificate['user_id']) : null;

        if (!$targetUser) {
            $targetUser = (object)['full_name' => 'Verified Candidate', 'username' => 'Candidate', 'id' => $certificate['user_id']];
        }
        if (empty($targetUser->full_name)) {
            $seekerModel = model(\App\Models\JobSeekerModel::class);
            $seeker = $seekerModel->where('user_id', $targetUser->id ?? $certificate['user_id'])->first();
            if ($seeker && !empty($seeker->full_name)) {
                $targetUser->full_name = $seeker->full_name;
            } elseif (!empty($targetUser->username)) {
                $targetUser->full_name = $targetUser->username;
            }
        }

        $templateModel = model(\App\Models\CertificateTemplateModel::class);
        $template = $templateModel->getTemplateForCourse($certificate['course_id'] ?? null);

        $html = view('certificates/course_certificate', [
            'certificate' => $certificate,
            'course'      => $course,
            'user'        => $targetUser,
            'template'    => $template,
            'isPdfExport' => true,
        ]);

        $tempPath = WRITEPATH . 'temp/';
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }
        $pdfPath = $tempPath . 'certificate-' . $certificate['certificate_code'] . '-' . time() . '.pdf';

        try {
            // Browsershot needs Node + Chrome on the host
            $browsershot = Browsershot::html($html)
                ->format('A4')
                ->landscape()
                ->margins(0, 0, 0, 0)
                ->windowSize(1056, 748)
                ->deviceScaleFactor(2)
                ->emulateMedia('screen')
                ->showBackground()
                ->noSandbox();

            // Automatically detect Node and npm on Linux/cPanel (or custom .env override)
            $envNode = env('node_binary_path') ?: env('NODE_BINARY_PATH');
            $envNpm  = env('npm_binary_path')  ?: env('NPM_BINARY_PATH');

            $linuxNodePaths = [
                '/usr/bin/node',
                '/usr/local/bin/node',
                '/opt/cpanel/ea-nodejs18/bin/node',
                '/opt/cpanel/ea-nodejs20/bin/node',
                '/opt/alt/alt-nodejs18/root/usr/bin/node',
                '/opt/alt/alt-nodejs20/root/usr/bin/node',
                '/opt/alt/alt-nodejs22/root/usr/bin/node',
                '/home/jobbcfsf/bin/node',
                '/home/jobbcfsf/.nvm/versions/node/current/bin/node',
            ];
            $linuxNpmPaths = [
                '/usr/bin/npm',
                '/usr/local/bin/npm',
                '/opt/cpanel/ea-nodejs18/bin/npm',
                '/opt/cpanel/ea-nodejs20/bin/npm',
                '/opt/alt/alt-nodejs18/root/usr/bin/npm',
                '/opt/alt/alt-nodejs20/root/usr/bin/npm',
                '/opt/alt/alt-nodejs22/root/usr/bin/npm',
                '/home/jobbcfsf/bin/npm',
                '/home/jobbcfsf/.nvm/versions/node/current/bin/npm',
            ];

            if ($envNode && file_exists($envNode)) {
                $browsershot->setNodeBinary($envNode);
            } elseif (DIRECTORY_SEPARATOR === '\\' && file_exists('C:\\Program Files\\nodejs\\node.exe')) {
                $browsershot->setNodeBinary('C:\\Program Files\\nodejs\\node.exe');
            } else {
                foreach ($linuxNodePaths as $p) {
                    if (file_exists($p)) { $browsershot->setNodeBinary($p); break; }
                }
            }

            if ($envNpm && file_exists($envNpm)) {
                $browsershot->setNpmBinary($envNpm);
            } elseif (DIRECTORY_SEPARATOR === '\\' && file_exists('C:\\Program Files\\nodejs\\npm.cmd')) {
                $browsershot->setNpmBinary('C:\\Program Files\\nodejs\\npm.cmd');
            } else {
                foreach ($linuxNpmPaths as $p) {
                    if (file_exists($p)) { $browsershot->setNpmBinary($p); break; }
                }
            }

            $browsershot->save($pdfPath);
        } catch (\Throwable $e) {
            log_message('warning', 'Browsershot PDF generation failed, falling back to Dompdf: ' . $e->getMessage());

            // Render dedicated Dompdf-compatible single-page A4 landscape certificate
            $pdfHtml = view('certificates/course_certificate_pdf', [
                'certificate' => $certificate,
                'course'      => $course,
                'user'        => $targetUser,
                'template'    => $template,
            ]);

            $dompdf = new \Dompdf\Dompdf([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
            ]);
            $dompdf->loadHtml($pdfHtml);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            file_put_contents($pdfPath, $dompdf->output());
        }

        return $this->response->download($pdfPath, null)
            ->setFileName('certificate-' . $certificate['certificate_code'] . '.pdf');
    }

    /**
     * View certificate as HTML page in browser (no PDF conversion)
     */
    public function viewCertificate($certificateId, $extraParam = null)
    {
        $certModel = model(CourseCertificateModel::class);
        $certificate = null;

        if ($certificateId === 'course' && $extraParam !== null) {
            $courseId = (int) $extraParam;
            $userId = auth()->id();
            if ($userId) {
                $certificate = $certModel->getCertificateForUser($userId, $courseId);
            }
        } elseif (is_numeric($certificateId)) {
            $certificate = $certModel->find($certificateId);
            if (!$certificate && auth()->loggedIn()) {
                $certificate = $certModel->getCertificateForUser(auth()->id(), (int)$certificateId);
            }
        } else {
            $certificate = $certModel->where('certificate_code', $certificateId)->first();
        }

        if (!$certificate) {
            return redirect()->to('training/certificates')->with('error', 'Certificate not found');
        }

        $course    = $this->courseModel->find($certificate['course_id']);
        $userModel = model(\App\Models\UserModel::class) ?: model(\CodeIgniter\Shield\Models\UserModel::class);
        $targetUser = $userModel ? $userModel->find($certificate['user_id']) : null;

        if (!$targetUser) {
            $targetUser = (object)['full_name' => 'Verified Candidate', 'username' => 'Candidate', 'id' => $certificate['user_id']];
        }
        if (empty($targetUser->full_name)) {
            $seekerModel = model(\App\Models\JobSeekerModel::class);
            $seeker = $seekerModel->where('user_id', $targetUser->id ?? $certificate['user_id'])->first();
            if ($seeker && !empty($seeker->full_name)) {
                $targetUser->full_name = $seeker->full_name;
            } elseif (!empty($targetUser->username)) {
                $targetUser->full_name = $targetUser->username;
            }
        }

        $templateModel = model(\App\Models\CertificateTemplateModel::class);
        $template = $templateModel->getTemplateForCourse($certificate['course_id'] ?? null);

        return view('certificates/course_certificate', [
            'certificate' => $certificate,
            'course'      => $course,
            'user'        => $targetUser,
            'template'    => $template,
            'isPdfExport' => false,
        ]);
    }

    /**
     * View my certificates
     */
    public function myCertificates()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login');
        }

        $certModel = model(CourseCertificateModel::class);
        $certificates = $certModel->getUserCertificates(auth()->id());

        return view('home/my_certificates', [
            'title' => 'My Certificates',
            'certificates' => $certificates,
        ]);
    }

    /**
     * Admin: Manage Course Modules
     */
    public function adminModules($courseId)
    {
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $modules = $this->courseModuleModel->where('course_id', $courseId)->orderBy('order_index', 'ASC')->findAll();

        return view('admin/elearning/modules', [
            'title'   => 'Manage Modules: ' . esc($course->title),
            'course'  => $course,
            'modules' => $modules,
        ]);
    }

    /**
     * Admin: Save Course Module
     */
    public function adminSaveModule()
    {
        $id = $this->request->getPost('id') ?: null;
        $courseId = $this->request->getPost('course_id');
        $existing = $id ? $this->courseModuleModel->find($id) : null;
        
        $contentSource = (string) $this->request->getPost('content_source');
        $allowedSources = ['none', 'youtube', 'upload', 'text'];
        if (! in_array($contentSource, $allowedSources, true)) {
            $contentSource = 'none';
        }

        $data = [
            'course_id'      => $courseId,
            'title'          => trim((string) $this->request->getPost('title')),
            'description'    => $this->request->getPost('description'),
            'content_source' => $contentSource,
            'youtube_url'    => $contentSource === 'youtube'
                ? (trim((string) $this->request->getPost('youtube_url')) ?: null)
                : null,
            'order_index'    => (int) $this->request->getPost('order_index'),
        ];

        $contentFile = $this->request->getFile('content_file');
        if ($contentSource === 'upload' && $contentFile && $contentFile->isValid() && ! $contentFile->hasMoved()) {
            $contentDir = WRITEPATH . 'uploads/courses/modules';
            if (! is_dir($contentDir)) {
                mkdir($contentDir, 0775, true);
            }

            $newContentName = $contentFile->getRandomName();
            $contentFile->move($contentDir, $newContentName);
            $data['content_file'] = 'courses/modules/' . $newContentName;
        } elseif ($contentSource === 'upload' && $existing?->content_file) {
            $data['content_file'] = $existing->content_file;
        } elseif ($contentSource !== 'upload') {
            $data['content_file'] = null;
        }

        if ($id) {
            $this->courseModuleModel->update($id, $data);
            $msg = 'Module updated successfully';
        } else {
            $this->courseModuleModel->insert($data);
            $msg = 'Module created successfully';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Admin: Delete Course
     */
    public function adminDeleteCourse($id)
    {
        $course = $this->courseModel->find($id);
        if (! $course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Delete thumbnail if it exists
        if (! empty($course->thumbnail)) {
            $thumbPath = FCPATH . $course->thumbnail;
            if (file_exists($thumbPath)) {
                @unlink($thumbPath);
            }
        }

        $this->courseModel->delete($id);
        return redirect()->back()->with('success', 'Course deleted successfully.');
    }

    /**
     * Admin: Toggle Course Active Status
     */
    public function adminToggleStatus($id)
    {
        $course = $this->courseModel->find($id);
        if (! $course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $newStatus = empty($course->is_active) ? 1 : 0;
        $this->courseModel->update($id, ['is_active' => $newStatus]);
        $label = $newStatus ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Course {$label} successfully.");
    }

    /**
     * Admin: Delete Course Module
     */
    public function adminDeleteModule($id)
    {
        $module = $this->courseModuleModel->find($id);
        if ($module) {
            $this->courseModuleModel->delete($id);
            return redirect()->back()->with('success', 'Module deleted.');
        }
        return redirect()->back()->with('error', 'Module not found.');
    }

    /**
     * CV Review Marketing Landing Page
     */
    public function cvReview()
    {
        $paidPlan = $this->request->getGet('plan');
        $reviewId = $this->request->getGet('review_id');

        return view('cv_review', [
            'title'           => 'Professional CV Review Service | JobberRecruit',
            'isLoggedIn'      => auth()->loggedIn(),
            'preselectedPlan' => in_array($paidPlan, ['professional', 'premium'], true) ? $paidPlan : 'basic',
            'reviewId'        => $reviewId ? (int) $reviewId : null,
            'planPrices'      => [
                'basic'        => 0,
                'professional' => (int) env('cv_review_pro_price', 15000),
                'premium'      => (int) env('cv_review_prem_price', 30000),
            ],
        ]);
    }

    /**
     * CV Review Submission Page (authenticated)
     */
    public function cvReviewSubmit()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login?redirect=cv-review/submit')
                ->with('error', 'Please login to submit your CV for review.');
        }

        $paidPlan = $this->request->getGet('plan');
        $reviewId = $this->request->getGet('review_id');

        return view('home/cv_review_submit', [
            'title'           => 'Submit Your CV for Review | JobberRecruit',
            'isLoggedIn'      => true,
            'preselectedPlan' => in_array($paidPlan, ['professional', 'premium'], true) ? $paidPlan : 'basic',
            'reviewId'        => $reviewId ? (int) $reviewId : null,
            'planPrices'      => [
                'basic'        => 0,
                'professional' => (int) env('cv_review_pro_price', 15000),
                'premium'      => (int) env('cv_review_prem_price', 30000),
            ],
        ]);
    }

    /**
     * Initiate Paystack payment for a paid CV review plan
     */
    public function initiateCvPayment()
    {
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('Please login to continue');
        }

        $plan = $this->request->getPost('plan');
        $amounts = [
            'professional' => (int) env('cv_review_pro_price', 15000),
            'premium'      => (int) env('cv_review_prem_price', 30000),
        ];

        if (!isset($amounts[$plan])) {
            return $this->fail('Invalid plan selected');
        }

        $amount = $amounts[$plan];
        $paymentMethod = $this->request->getPost('payment_method');

        if ($paymentMethod === 'wallet') {
            $userId = auth()->id();
            $walletService = new \App\Services\WalletService();
            $wallet = $walletService->getOrCreateWallet($userId);

            if ((float) $wallet->balance < (float) $amount) {
                return $this->fail('Insufficient wallet balance (₦' . number_format($wallet->balance, 2) . '). Please top up your wallet or pay via Paystack.');
            }

            $reference = 'cv_w_' . uniqid();
            $walletService->debit(
                userId: $userId,
                amount: (float) $amount,
                source: 'wallet_checkout',
                reference: $reference,
                description: 'Paid with wallet for CV Review plan: ' . ucfirst($plan)
            );

            // Record payment
            $paymentModel = model(\App\Models\PaymentModel::class);
            $paymentModel->insert([
                'user_id'        => $userId,
                'reference'      => $reference,
                'amount'         => $amount,
                'status'         => 'paid',
                'payment_method' => 'wallet',
                'paid_at'        => date('Y-m-d H:i:s'),
            ]);

            return $this->respond([
                'success'         => true,
                'pay_with_wallet' => true,
                'message'         => 'CV Review plan purchased successfully using your wallet balance!',
                'redirect_url'    => base_url('candidate/career-tools'),
            ]);
        }

        $paystack = new \App\Services\PaystackService();
        $email = auth()->user()->email;
        $callbackUrl = base_url('cv-review/verify');

        $response = $paystack->initialize($email, $amount, $callbackUrl, [
            'type'    => 'cv_review',
            'plan'    => $plan,
            'user_id' => auth()->id(),
        ]);

        if (!($response['status'] ?? false)) {
            return $this->fail('Payment initialization failed: ' . ($response['message'] ?? 'Unknown error'));
        }

        return $this->respond([
            'success'          => true,
            'authorization_url' => $response['data']['authorization_url'],
        ]);
    }

    /**
     * Verify Paystack payment for CV review and create pending record
     */
    public function verifyCvPayment()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login');
        }

        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('cv-review')->with('error', 'Invalid payment reference');
        }

        $paystack = new \App\Services\PaystackService();
        $result = $paystack->verify($reference);

        if (!($result['status'] ?? false) || ($result['data']['status'] ?? '') !== 'success') {
            return redirect()->to('cv-review')->with('error', 'Payment verification failed');
        }

        $metadata = $result['data']['metadata'] ?? [];
        $plan = $metadata['plan'] ?? 'professional';
        $amount = ($result['data']['amount'] ?? 0) / 100;

        $reviewMode = env('cv_review_mode', 'semi') === 'auto' ? 'auto' : 'semi';

        $reviewModel = model(CvReviewModel::class);
        $reviewModel->insert([
            'user_id'           => auth()->id(),
            'plan'              => $plan,
            'amount'            => $amount,
            'payment_reference' => $reference,
            'payment_status'    => 'paid',
            'status'            => 'pending',
            'review_mode'       => $reviewMode,
        ]);

        $reviewId = $reviewModel->getInsertID();

        return redirect()->to('cv-review/submit?review_id=' . $reviewId)
            ->with('success', 'Payment successful! Please upload your CV below.');
    }

    /**
     * Handle CV Review Upload
     */
    public function uploadCvReview()
    {
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('Please login to continue');
        }

        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid request');
        }

        $plan   = $this->request->getPost('plan') ?? 'basic';
        $reviewId = $this->request->getPost('review_id');

        // Paid plans require a successful payment record
        if (in_array($plan, ['professional', 'premium'], true)) {
            if (!$reviewId) {
                return $this->fail('Payment is required. Please select a plan and complete payment first.');
            }

            $review = model(CvReviewModel::class)->find($reviewId);
            if (!$review || $review['user_id'] != auth()->id() || $review['payment_status'] !== 'paid') {
                return $this->fail('Valid payment record not found. Please complete payment first.');
            }
        }

        $cvFile = $this->request->getFile('cv_file');

        if (!$cvFile || !$cvFile->isValid()) {
            return $this->fail('Please upload a valid CV file');
        }

        $allowedTypes = ['pdf', 'doc', 'docx'];
        $ext = strtolower($cvFile->getExtension());

        if (!in_array($ext, $allowedTypes)) {
            return $this->fail('Only PDF, DOC, and DOCX files are allowed');
        }

        if ($cvFile->getSize() > 5 * 1024 * 1024) {
            return $this->fail('File size must be less than 5MB');
        }

        $uploadPath = FCPATH . 'uploads/cv_reviews';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $newName = uniqid('cv_') . '.' . $ext;
        $cvFile->move($uploadPath, $newName);

        $reviewModel = model(CvReviewModel::class);

        $now = date('Y-m-d H:i:s');

        if ($reviewId) {
            $reviewModel->update($reviewId, [
                'file_path'        => 'uploads/cv_reviews/' . $newName,
                'full_name'        => $this->request->getPost('full_name'),
                'email'            => auth()->user()->email,
                'phone'            => $this->request->getPost('phone'),
                'industry'         => $this->request->getPost('industry'),
                'target_role'      => $this->request->getPost('target_role'),
                'feedback_request' => $this->request->getPost('feedback_request'),
                'status'           => 'pending',
            ]);
            $currentReviewId = $reviewId;
        } else {
            $reviewMode = env('cv_review_mode', 'semi') === 'auto' ? 'auto' : 'semi';
            $reviewModel->insert([
                'user_id'           => auth()->id(),
                'plan'              => 'basic',
                'amount'            => 0,
                'payment_status'    => 'free',
                'file_path'         => 'uploads/cv_reviews/' . $newName,
                'full_name'         => $this->request->getPost('full_name'),
                'email'             => auth()->user()->email,
                'phone'             => $this->request->getPost('phone'),
                'industry'          => $this->request->getPost('industry'),
                'target_role'       => $this->request->getPost('target_role'),
                'feedback_request'  => $this->request->getPost('feedback_request'),
                'status'            => 'pending',
                'review_mode'       => $reviewMode,
            ]);
            $currentReviewId = $reviewModel->getInsertID();
        }

        $email = auth()->user()->email;
        $name = $this->request->getPost('full_name') ?: (auth()->user()->username ?? 'Candidate');
        $emailService = service('mailer');
        $emailService->sendTemplate(
            $email,
            'CV Review Request Received',
            'emails/cv_review_received',
            [
                'user_name' => $name,
            ]
        );

        $autoReview = false;
        if ($currentReviewId) {
            $submitted = $reviewModel->find($currentReviewId);
            if ($submitted) {
                $subReviewMode = is_array($submitted) ? ($submitted['review_mode'] ?? null) : ($submitted->review_mode ?? null);
                $subPlan = is_array($submitted) ? ($submitted['plan'] ?? 'basic') : ($submitted->plan ?? 'basic');
                if (($subReviewMode ?? env('cv_review_mode', 'semi')) === 'auto') {
                    $filePath = FCPATH . 'uploads/cv_reviews/' . $newName;
                    $cvContent = '';
                    if (is_file($filePath)) {
                        $cvContent = file_get_contents($filePath) ?: '[Binary file]';
                    }
                    $aiService = new \App\Services\AiService();
                    $aiReview = $aiService->generateCvReview([
                        'full_name'        => $this->request->getPost('full_name') ?? 'Candidate',
                        'target_role'      => $this->request->getPost('target_role') ?? '',
                        'industry'         => $this->request->getPost('industry') ?? '',
                        'feedback_request' => $this->request->getPost('feedback_request') ?? '',
                        'cv_content'       => $cvContent,
                        'plan'             => $subPlan,
                    ]);
                    $reviewModel->update($currentReviewId, [
                        'ai_review'   => $aiReview,
                        'status'      => 'completed',
                        'reviewed_at' => $now,
                    ]);
                    $autoReview = true;
                }
            }
        }

        if ($autoReview) {
            return $this->respond([
                'success' => true,
                'message' => 'CV reviewed successfully! Your AI-powered review is ready. Check your dashboard to view it.'
            ]);
        }

        return $this->respond([
            'success' => true,
            'message' => 'CV uploaded successfully! Our team will review it within 48 hours.'
        ]);
    }

    /**
     * Admin: Manage Issued Certificates
     */
    public function adminCertificates()
    {
        $certModel = model(CourseCertificateModel::class);
        $certificates = $certModel->select('course_certificates.*, courses.title as course_title, users.username, auth_identities.secret as user_email, COALESCE((SELECT full_name FROM job_seekers WHERE user_id = users.id), (SELECT company_name FROM employers WHERE user_id = users.id), users.username) as full_name')
            ->join('courses', 'courses.id = course_certificates.course_id', 'left')
            ->join('users', 'users.id = course_certificates.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->orderBy('course_certificates.issued_at', 'DESC')
            ->findAll();

        return view('admin/elearning/certificates', [
            'title' => 'Issued Certificates',
            'certificates' => $certificates,
        ]);
    }

    /**
     * Admin: Upload Manual Certificate Override
     */
    public function uploadManualCertificate($id)
    {
        $certModel = model(CourseCertificateModel::class);
        $certificate = $certModel->find($id);
        if (!$certificate) {
            return redirect()->back()->with('error', 'Certificate not found.');
        }

        $file = $this->request->getFile('manual_certificate');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($file->getMimeType() !== 'application/pdf') {
                return redirect()->back()->with('error', 'Please upload a valid PDF file.');
            }

            $uploadDir = FCPATH . 'uploads/certificates';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!empty($certificate['manual_certificate']) && file_exists(FCPATH . 'uploads/' . $certificate['manual_certificate'])) {
                @unlink(FCPATH . 'uploads/' . $certificate['manual_certificate']);
            }

            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);

            $certModel->update($id, [
                'manual_certificate' => 'certificates/' . $newName
            ]);

            return redirect()->back()->with('success', 'Manual certificate uploaded and saved successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload manual certificate.');
    }

    /**
     * Admin: Certificate Settings (signature, stamp)
     */
    public function adminCertificateSettings()
    {
        return view('admin/elearning/certificate_settings', [
            'title' => 'Certificate Settings',
        ]);
    }

    /**
     * Admin: Save Certificate Settings
     */
    public function saveCertificateSettings()
    {
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $uploadDir    = FCPATH . 'uploads/certificate_assets';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach (['certificate_signature', 'certificate_stamp'] as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->with('error', 'Only PNG/JPG images are allowed.');
                }
                $newName = $file->getRandomName();
                $file->move($uploadDir, $newName);
                setting('Elearning.' . $field, 'uploads/certificate_assets/' . $newName);
            }
        }

        return redirect()->back()->with('success', 'Certificate settings saved successfully.');
    }

    /**
     * Admin: Certificate Template Editor
     */
    public function adminCertificateEditor()
    {
        $courses = model(\App\Models\CourseModel::class)->orderBy('title', 'ASC')->findAll();
        $courseId = $this->request->getGet('course_id');

        $templateModel = model(\App\Models\CertificateTemplateModel::class);
        $template = $templateModel->getTemplateForCourse($courseId ?: null);
        if (is_object($template)) {
            $template = (array) $template;
        }

        // Decode layout_json if stored as a JSON string
        if ($template && !empty($template['layout_json']) && is_string($template['layout_json'])) {
            $template['layout_json'] = json_decode($template['layout_json'], true) ?? [];
        } elseif (!$template) {
            $template = [
                'id'             => null,
                'course_id'      => null,
                'template_mode'  => 'builder',
                'primary_color'  => '#0D609E',
                'secondary_color'=> '#F3921D',
                'text_color'     => '#15233a',
                'show_logo'      => true,
                'show_qr_code'   => true,
                'show_signature' => true,
                'background_image' => '',
                'custom_html'    => '',
                'layout_json'    => [],
                'additional_text'=> '',
            ];
        } elseif (empty($template['layout_json'])) {
            $template['layout_json'] = [];
        }

        return view('admin/elearning/certificate_editor', [
            'title'    => 'Certificate Template Editor',
            'courses'  => $courses,
            'template' => $template,
            'selectedCourseId' => $courseId,
        ]);
    }

    /**
     * Admin: Save Certificate Template (upsert to certificate_templates table)
     */
    public function saveCertificateTemplate()
    {
        $templateModel = model(\App\Models\CertificateTemplateModel::class);

        $courseId      = $this->request->getPost('course_id') ?: null;
        $templateMode  = $this->request->getPost('template_mode') ?: 'builder';
        $primaryColor  = $this->request->getPost('primary_color') ?: '#0D609E';
        $secondaryColor= $this->request->getPost('secondary_color') ?: '#F3921D';
        $textColor     = $this->request->getPost('text_color') ?: '#15233a';
        $layoutJson    = $this->request->getPost('layout_json') ?: '{}';
        $customHtml    = $this->request->getPost('custom_html') ?: '';
        $additionalText= $this->request->getPost('additional_text') ?: '';
        $showLogo      = $this->request->getPost('show_logo') !== null ? (bool)$this->request->getPost('show_logo') : true;
        $showQr        = $this->request->getPost('show_qr_code') !== null ? (bool)$this->request->getPost('show_qr_code') : true;
        $showSig       = $this->request->getPost('show_signature') !== null ? (bool)$this->request->getPost('show_signature') : true;

        // Handle background image upload
        $backgroundImage = '';
        $bgFile = $this->request->getFile('background_image');
        if ($bgFile && $bgFile->isValid() && !$bgFile->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($bgFile->getMimeType(), $allowedTypes)) {
                $uploadDir = FCPATH . 'uploads/certificate_assets/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $newName = $bgFile->getRandomName();
                $bgFile->move($uploadDir, $newName);
                $backgroundImage = 'uploads/certificate_assets/' . $newName;
            }
        }

        $data = [
            'course_id'       => $courseId,
            'template_mode'   => $templateMode,
            'primary_color'   => $primaryColor,
            'secondary_color' => $secondaryColor,
            'text_color'      => $textColor,
            'layout_json'     => $layoutJson,
            'custom_html'     => $customHtml,
            'additional_text' => $additionalText,
            'show_logo'       => $showLogo ? 1 : 0,
            'show_qr_code'    => $showQr ? 1 : 0,
            'show_signature'  => $showSig ? 1 : 0,
        ];

        if (!empty($backgroundImage)) {
            $data['background_image'] = $backgroundImage;
        }

        // Find existing record for this course (or global default if courseId is null)
        $existing = $templateModel->getTemplateForCourse($courseId);

        if ($existing) {
            $templateModel->update($existing['id'], $data);
        } else {
            $templateModel->insert($data);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Certificate template saved successfully.']);
        }

        return redirect()->back()->with('success', 'Certificate template saved successfully.');
    }

    /**
     * Admin: AI Generate Course Test Questions
     */
    public function adminGenerateTest()
    {
        $courseId = $this->request->getPost('course_id');
        $numQuestions = (int) ($this->request->getPost('num_questions') ?? 5);

        if (!$courseId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course ID is required']);
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course not found']);
        }

        $aiService = new \App\Services\AiService();
        $questions = $aiService->generateCourseTestQuestions($course->title, strip_tags($course->description ?? ''), $numQuestions);

        if (empty($questions)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to generate AI course test. Please try again.']);
        }

        $db = \Config\Database::connect();
        if (!$db->fieldExists('test_questions', 'courses')) {
            $forge = \Config\Database::forge();
            $forge->addColumn('courses', [
                'test_questions' => ['type' => 'TEXT', 'null' => true]
            ]);
        }

        $this->courseModel->update($courseId, [
            'test_questions' => json_encode($questions)
        ]);

        return $this->response->setJSON([
            'success' => true,
            'questions' => $questions,
            'message' => 'AI course test generated successfully!'
        ]);
    }

    /**
     * Admin: Save Custom/Edited Course Test Questions
     */
    public function adminSaveTest()
    {
        $courseId = $this->request->getPost('course_id');
        $questionsRaw = $this->request->getPost('questions');

        if (!$courseId || empty($questionsRaw)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course ID and questions are required']);
        }

        $decoded = is_string($questionsRaw) ? json_decode($questionsRaw, true) : $questionsRaw;
        if (!is_array($decoded)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid test questions format']);
        }

        $db = \Config\Database::connect();
        if (!$db->fieldExists('test_questions', 'courses')) {
            $forge = \Config\Database::forge();
            $forge->addColumn('courses', [
                'test_questions' => ['type' => 'TEXT', 'null' => true]
            ]);
        }

        $this->courseModel->update($courseId, [
            'test_questions' => json_encode($decoded)
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Course test saved successfully!'
        ]);
    }

    /**
     * Admin: Fetch Aptitude Tests List for Course Test Importer
     */
    public function adminGetAptitudeTests()
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('tests')) {
            return $this->response->setJSON(['success' => true, 'tests' => []]);
        }

        $tests = $db->table('tests')
            ->select('id, title, description, difficulty, num_questions')
            ->where('is_active', 1)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'tests' => $tests
        ]);
    }

    /**
     * Admin: Import Questions from Aptitude Test into Course Test
     */
    public function adminImportAptitudeQuestions()
    {
        $courseId = $this->request->getPost('course_id');
        $testId = $this->request->getPost('test_id');
        $numQuestions = (int) ($this->request->getPost('num_questions') ?? 5);

        if (!$courseId || !$testId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course ID and Aptitude Test ID are required']);
        }

        $db = \Config\Database::connect();
        if (!$db->tableExists('questions') || !$db->tableExists('question_options')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Aptitude test question bank is not initialized']);
        }

        $rawQuestions = $db->table('questions')
            ->select('id, body, explanation')
            ->where('test_id', $testId)
            ->where('is_active', 1)
            ->limit($numQuestions)
            ->get()
            ->getResultArray();

        if (empty($rawQuestions)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active questions found in selected Aptitude Test']);
        }

        $formattedQuestions = [];
        foreach ($rawQuestions as $q) {
            $options = $db->table('question_options')
                ->select('body as text, is_correct')
                ->where('question_id', $q['id'])
                ->get()
                ->getResultArray();

            $formattedQuestions[] = [
                'question' => $q['body'],
                'explanation' => $q['explanation'] ?? '',
                'options' => array_map(function($opt) {
                    return [
                        'text' => $opt['text'],
                        'is_correct' => (int) $opt['is_correct']
                    ];
                }, $options)
            ];
        }

        if (!$db->fieldExists('test_questions', 'courses')) {
            $forge = \Config\Database::forge();
            $forge->addColumn('courses', [
                'test_questions' => ['type' => 'TEXT', 'null' => true]
            ]);
        }

        $this->courseModel->update($courseId, [
            'test_questions' => json_encode($formattedQuestions)
        ]);

        return $this->response->setJSON([
            'success' => true,
            'questions' => $formattedQuestions,
            'message' => 'Imported ' . count($formattedQuestions) . ' questions from Aptitude Test successfully!'
        ]);
    }
}
