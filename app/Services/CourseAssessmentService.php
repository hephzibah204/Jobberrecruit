<?php

namespace App\Services;

class CourseAssessmentService
{
    private \CodeIgniter\Database\BaseConnection $db;
    public function __construct(?\CodeIgniter\Database\BaseConnection $db = null) { $this->db = $db ?? db_connect(); }

    public static function questions($course): array
    {
        $bank = is_array($course->test_questions ?? null) ? $course->test_questions : json_decode($course->test_questions ?? '[]', true);
        return is_array($bank) ? array_values($bank) : [];
    }

    public static function grade(array $bank, array $answers): float
    {
        self::validateBank($bank);
        if (!$bank || count($answers) !== count($bank)) throw new \InvalidArgumentException('Please answer every assessment question.');
        $correct = 0;
        foreach ($bank as $i => $question) {
            $options = $question['options'] ?? [];
            if (!array_key_exists($i, $answers) || filter_var($answers[$i], FILTER_VALIDATE_INT) === false
                || !isset($options[(int) $answers[$i]])) throw new \InvalidArgumentException('Invalid assessment answer.');
            if (count(array_filter($options, static fn ($o) => !empty($o['is_correct']))) !== 1) {
                throw new \InvalidArgumentException('This assessment needs to be configured by the course administrator.');
            }
            if (!empty($options[(int) $answers[$i]]['is_correct'])) $correct++;
        }
        return round(100 * $correct / count($bank), 2);
    }

    public static function validateBank(array $bank): void
    {
        if (!$bank || !array_is_list($bank)) throw new \InvalidArgumentException('Add at least one assessment question.');
        foreach ($bank as $question) {
            if (!is_array($question) || !is_string($question['question'] ?? null) || trim($question['question']) === '') {
                throw new \InvalidArgumentException('Each question needs question text.');
            }
            $options = $question['options'] ?? [];
            if (!is_array($options) || !array_is_list($options) || count($options) < 2) {
                throw new \InvalidArgumentException('Each question needs at least two answer options.');
            }
            $correct = 0;
            foreach ($options as $option) {
                if (!is_array($option) || !is_string($option['text'] ?? null) || trim($option['text']) === ''
                    || !in_array($option['is_correct'] ?? null, [0, 1, false, true, '0', '1'], true)) {
                    throw new \InvalidArgumentException('Each option needs text and a valid answer marker.');
                }
                if (!empty($option['is_correct'])) $correct++;
            }
            if ($correct !== 1) throw new \InvalidArgumentException('Each question must have exactly one correct answer.');
        }
    }

    public function saveQuestions(int $courseId, array $questions): void
    {
        self::validateBank($questions);
        $courses = new \App\Models\CourseModel($this->db);
        if (!$courses->find($courseId)) throw new \InvalidArgumentException('Course not found.');
        if (!$courses->update($courseId, ['test_questions' => json_encode($questions, JSON_THROW_ON_ERROR)])) {
            throw new \RuntimeException('Assessment questions could not be saved.');
        }
    }

    public static function version(array $bank): string
    {
        // Hash public content only. Hashing a small answer key would let a
        // candidate recover it by trying each possible combination offline.
        $public = array_map(static fn ($question) => ['question' => $question['question'] ?? '',
            'options' => array_map(static fn ($option) => $option['text'] ?? '', $question['options'] ?? [])], $bank);
        return hash('sha256', json_encode($public));
    }

    public function complete(int $userId, int $courseId, array $answers, string $version, string $requestKey): array
    {
        $newCertificateId = null;
        $result = PurchaseTransaction::run($userId, function () use ($userId, $courseId, $answers, $version, $requestKey, &$newCertificateId) {
            $course = (new \App\Models\CourseModel($this->db))->find($courseId);
            $enrollments = new \App\Models\CourseEnrollmentModel($this->db);
            $enrollment = $enrollments->where('user_id', $userId)->where('course_id', $courseId)->first();
            if (!$course || !$enrollment || !in_array($enrollment->status, ['enrolled', 'completed'], true)) {
                throw new \InvalidArgumentException('Active enrollment is required.');
            }
            if (!preg_match('/^[a-zA-Z0-9_-]{16,64}$/D', $requestKey)) throw new \InvalidArgumentException('Invalid assessment request.');
            $certificates = new \App\Models\CourseCertificateModel($this->db);
            $prior = $this->db->table('course_assessment_submissions')->where('user_id', $userId)->where('course_id', $courseId)->where('request_key', $requestKey)->get()->getRow();
            if ($prior) return $this->result((float) $prior->score, (int) $enrollment->test_attempts, $certificates->getCertificateForUser($userId, $courseId));
            $attempts = (int) $enrollment->test_attempts;
            if ($attempts >= 3) throw new \InvalidArgumentException('The maximum of three assessment attempts has been reached.');
            $bank = self::questions($course);
            if (!hash_equals(self::version($bank), $version)) throw new \InvalidArgumentException('The assessment changed. Reload the course before submitting.');
            $score = self::grade($bank, $answers);
            $attempts++;
            $this->db->table('course_assessment_submissions')->insert(['user_id' => $userId, 'course_id' => $courseId,
                'enrollment_id' => $enrollment->id, 'request_key' => $requestKey, 'questions' => json_encode($bank),
                'answers' => json_encode($answers), 'score' => $score, 'created_at' => date('Y-m-d H:i:s')]);
            $this->db->table('course_enrollments')->where('id', $enrollment->id)->update(['test_attempts' => $attempts, 'test_score' => $score]);
            $certificate = null;
            if ($score >= 70) {
                $certificate = $certificates->getCertificateForUser($userId, $courseId);
                if (!$certificate) {
                    $id = $certificates->insert(['user_id' => $userId, 'course_id' => $courseId, 'enrollment_id' => $enrollment->id,
                        'certificate_code' => $certificates->generateCertificateCode(), 'issued_at' => date('Y-m-d H:i:s')]);
                    if (!$id) throw new \RuntimeException('Certificate could not be issued.');
                    $certificate = $certificates->find($id);
                    $newCertificateId = (int) $id;
                }
                $enrollments->update($enrollment->id, ['status' => 'completed', 'completed_at' => $certificate['issued_at'], 'progress' => 100]);
            }
            return $this->result($score, $attempts, $certificate);
        }, $this->db);
        if ($newCertificateId && ENVIRONMENT !== 'testing') {
            try {
                (new EmailNotificationService())->sendCourseCompletedNotification(
                    model(\App\Models\UserModel::class)->find($userId),
                    (new \App\Models\CourseModel($this->db))->find($courseId), base_url('training/certificate/download/' . $newCertificateId));
            } catch (\Throwable $e) {
                log_message('error', 'Course completion notification: ' . $e->getMessage());
            }
        }
        return $result;
    }

    private function result(float $score, int $attempts, ?array $certificate): array
    {
        return ['success' => $score >= 70 && $certificate !== null, 'score' => $score, 'attempts' => $attempts,
            'remaining_attempts' => max(0, 3 - $attempts), 'certificate_id' => $certificate['id'] ?? null,
            'certificate_code' => $certificate['certificate_code'] ?? null];
    }
}
