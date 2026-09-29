<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Testimonial;

class TestimonialModel extends Model
{
    protected $table            = 'testimonials';
    protected $primaryKey       = 'id';
    protected $returnType       = Testimonial::class;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'name',
        'role',
        'company',
        'message',
        'rating',
        'avatar',
        'is_featured',
        'status',
    ];

    // Default ordering
    protected $orderBy = [
        'is_featured' => 'DESC',
        'created_at'  => 'DESC',
    ];
}
