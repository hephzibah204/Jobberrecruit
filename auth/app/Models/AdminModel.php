<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admins';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Admin::class;
    protected $allowedFields = ['user_id', 'full_name', 'role'];
}
