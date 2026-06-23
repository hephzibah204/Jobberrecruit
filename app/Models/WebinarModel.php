<?php

namespace App\Models;

use CodeIgniter\Model;

class WebinarModel extends Model
{
    protected $table            = 'webinars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'speaker_name', 'scheduled_at', 'meeting_link', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
