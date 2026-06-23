<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Testimonial extends Entity
{
    protected $casts = [
        'id'          => 'integer',
        'rating'      => 'integer',
        'is_featured' => 'boolean',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
