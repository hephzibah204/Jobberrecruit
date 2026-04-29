<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseEnrollmentModel extends Model
{
    protected $table            = 'course_enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['course_id', 'user_id', 'status', 'payment_reference', 'amount'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at for enrollments
}
