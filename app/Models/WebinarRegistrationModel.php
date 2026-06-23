<?php

namespace App\Models;

use CodeIgniter\Model;

class WebinarRegistrationModel extends Model
{
    protected $table            = 'webinar_registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['webinar_id', 'user_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
