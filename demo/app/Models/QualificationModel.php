<?php

namespace App\Models;

use CodeIgniter\Model;

class QualificationModel extends Model
{
    protected $table            = 'qualifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'order_index', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActive()
    {
        return $this->where('is_active', 1)
                    ->orderBy('order_index', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
