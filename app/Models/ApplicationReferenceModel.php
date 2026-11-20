<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationReferenceModel extends Model
{
    protected $table = 'application_references';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'application_id',
        'name',
        'title',
        'email'
    ];
}
