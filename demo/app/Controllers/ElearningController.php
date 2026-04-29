<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\CourseEnrollmentModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;

class ElearningController extends BaseController
{
    use ResponseTrait;

    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new CourseEnrollmentModel();
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
            'title'           => 'E-Learning & Training Marketplace',
            'courses'         => $courses,
            'featuredCourses' => array_slice($featuredCourses, 0, 3),
            'freeCount'       => $freeCount,
            'paidCount'       => count($courses) - $freeCount,
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

        return view('home/elearning_detail', [
            'title'            => $course->title,
            'course'           => $course,
            'enrollment'       => $enrollment,
            'canAccessContent' => $canAccessContent,
            'youtubeEmbedUrl'  => $this->getYoutubeEmbedUrl($course->youtube_url ?? null),
            'relatedCourses'   => $relatedCourses,
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
            'description' => $this->request->getPost('description'),
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

        if (! $course || ($course->content_source ?? 'none') !== 'upload' || empty($course->content_file)) {
            return redirect()->to('training')->with('error', 'Course content not found.');
        }

        if (! $this->canAccessCourse($course, $this->getEnrollmentForCurrentUser((int) $course->id))) {
            return redirect()->to('training/course/' . $course->id)
                ->with('error', 'Please enroll in this course to access the content.');
        }

        $path = WRITEPATH . 'uploads/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $course->content_file);
        if (! is_file($path)) {
            return redirect()->to('training/course/' . $course->id)
                ->with('error', 'The uploaded course file is missing.');
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
}
