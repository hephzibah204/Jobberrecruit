<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseCertificateModel extends Model
{
    protected $table      = 'course_certificates';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrollment_id',
        'certificate_code',
        'issued_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getCertificateForUser(int $userId, int $courseId)
    {
        return $this->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function getUserCertificates(int $userId): array
    {
        return $this->select('course_certificates.*, courses.title as course_name, courses.thumbnail')
            ->join('courses', 'courses.id = course_certificates.course_id', 'left')
            ->where('course_certificates.user_id', $userId)
            ->orderBy('course_certificates.issued_at', 'DESC')
            ->findAll();
    }

    public function generateCertificateCode(): string
    {
        return 'CERT-' . strtoupper(substr(md5(uniqid((string) random_int(0, PHP_INT_MAX), true)), 0, 12));
    }
}
