<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\CourseEnrollmentModel;
use App\Models\CourseCertificateModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            'og_image'          => $course->thumbnail ? base_url($course->thumbnail) : base_url('images/og-image-main.png'),
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
    public function enroll($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('login')->with('error', 'Please login to enroll in courses');
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

        return redirect()->to('training/course/' . $id)->with('success', 'Enrolled successfully!');
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

            return redirect()->to('training/course/' . $courseId)->with('success', 'Payment successful! You are now enrolled.');
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

        $this->enrollmentModel->update($enrollment['id'], [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'progress' => 100,
        ]);

        $certCode = $certModel->generateCertificateCode();
        $certId = $certModel->insert([
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_id' => $enrollment['id'],
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
     * CV Review Upload Page
     */
    public function cvReview()
    {
        return view('home/cv_review', [
            'title' => 'Professional CV Review Service | JobberRecruit'
        ]);
    }

    /**
     * Handle CV Review Upload
     */
    public function uploadCvReview()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid request');
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

        $uploadPath = 'uploads/cv_reviews';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $newName = uniqid('cv_') . '.' . $ext;
        $cvFile->move($uploadPath, $newName);

        // Save to database or session for admin review
        $reviewData = [
            'user_id' => auth()->id(),
            'file_path' => $uploadPath . '/' . $newName,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];

        // You can save this to a database table if needed
        // For now, just return success
        return $this->respond([
            'success' => true,
            'message' => 'CV uploaded successfully! Our team will review it within 48 hours.'
        ]);
    }
}
