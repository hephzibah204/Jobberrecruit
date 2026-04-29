<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Blog extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id' => 'integer',
        'admin_id' => 'integer'
    ];
}
