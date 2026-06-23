<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModuleModel extends Model
{
    protected $table            = 'course_modules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id',
        'title',
        'description',
        'content_source',
        'youtube_url',
        'content_file',
        'order_index',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
