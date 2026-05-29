<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\CourseEnrollmentModel;
use App\Models\CourseCertificateModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\CvReviewModel;

class ElearningController extends BaseController
{
    use ResponseTrait;

    protected $courseModel;
    protected $enrollmentModel;
    protected $courseModuleModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new CourseEnrollmentModel();
        $this->courseModuleModel = new \App\Models\CourseModuleModel();
    }

    /**
     * Public Marketplace
     */
    public function index()
    {
        $courses = $this->courseModel
            ->where('is_active', 1)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $featuredCourses = array_values(array_filter(
            $courses,
            static fn ($course) => (int) ($course->is_featured ?? 0) === 1
        ));

        $freeCount = count(array_filter(
            $courses,
            static fn ($course) => (float) ($course->price ?? 0) <= 0
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
            'paidCount'         => count($courses) - $freeCount,
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
        ]);
    }

    /**
     * Admin: Manage Courses
     */
    public function adminIndex()
    {
        return view('admin/elearning/index', [
            'title'   => 'Manage Courses',
            'courses' => $this->courseModel
                ->orderBy('is_featured', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->findAll(),
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

        $data = [
            'title'          => $title,
            'slug'           => $this->generateUniqueSlug($title, $id ? (int) $id : null),
            'item_type'      => $this->request->getPost('item_type') ?: 'course',
            'description'    => $this->request->getPost('description'),
            'instructor'     => $this->request->getPost('instructor'),
            'price'          => (float) ($this->request->getPost('price') ?: 0),
            'duration'       => trim((string) $this->request->getPost('duration')) ?: null,
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
            $msg = 'Course updated successfully';
        } else {
            $this->courseModel->insert($data);
            $msg = 'Course created successfully';
        }

        return redirect()->back()->with('success', $msg);
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

            return redirect()->to('candidate/my-courses/' . $courseId)->with('success', 'Payment successful! Welcome to your interactive classroom.');
        }

        return redirect()->to('training')->with('error', 'Payment verification failed');
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

        // Fetch certificate if completed
        $certificate = null;
        if ($enrollment && $enrollment->status === 'completed') {
            $certModel = model(CourseCertificateModel::class);
            $certificate = $certModel->getCertificateForUser($userId, $courseId);
        }

        return view('candidate/classroom', [
            'title' => esc($course->title) . ' - Learning Portal',
            'course' => $course,
            'enrollment' => (object) $enrollment,
            'modules' => $modules,
            'activeModule' => $activeModule,
            'certificate' => $certificate,
            'youtubeEmbedUrl' => $activeModule ? $this->getYoutubeEmbedUrl($activeModule->youtube_url ?? null) : null
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

        $certModel = model(CourseCertificateModel::class);
        $existing = $certModel->getCertificateForUser($userId, $courseId);
        if ($existing) {
            return $this->respond([
                'success' => true,
                'certificate_code' => $existing['certificate_code'],
                'message' => 'Certificate already issued',
            ]);
        }

        $this->enrollmentModel->update($enrollment->id, [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'progress' => 100,
        ]);

        $certCode = $certModel->generateCertificateCode();
        $certId = $certModel->insert([
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_id' => $enrollment->id,
            'certificate_code' => $certCode,
            'issued_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->respond([
            'success' => true,
            'certificate_code' => $certCode,
            'certificate_id' => $certId,
            'message' => 'Course completed! Certificate generated.',
        ]);
    }

    /**
     * Download certificate as PDF
     */
    public function downloadCertificate($certificateId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login');
        }

        $userId = auth()->id();
        $certModel = model(CourseCertificateModel::class);
        $certificate = $certModel
            ->where('id', $certificateId)
            ->where('user_id', $userId)
            ->first();

        if (!$certificate) {
            return redirect()->back()->with('error', 'Certificate not found');
        }

        $course = $this->courseModel->find($certificate['course_id']);
        $user = auth()->user();

        $html = view('certificates/course_certificate', [
            'certificate' => $certificate,
            'course' => $course,
            'user' => $user,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream('certificate-' . $certificate['certificate_code'] . '.pdf', ['Attachment' => true]);
        exit();
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

        return view('home/cv_review', [
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

        $autoReview = false;
        if ($currentReviewId) {
            $submitted = $reviewModel->find($currentReviewId);
            if ($submitted && ($submitted->review_mode ?? env('cv_review_mode', 'semi')) === 'auto') {
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
                    'plan'             => $submitted->plan ?? 'basic',
                ]);
                $reviewModel->update($currentReviewId, [
                    'ai_review'   => $aiReview,
                    'status'      => 'completed',
                    'reviewed_at' => $now,
                ]);
                $autoReview = true;
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
}
