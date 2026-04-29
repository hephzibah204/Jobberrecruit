<?php

namespace App\Models;

use CodeIgniter\Model;

class BundlePackageModel extends Model
{
    protected $table = 'bundle_packages';
    protected $allowedFields = ['code', 'name', 'price', 'credits', 'cost_per_credit', 'is_active'];

    protected $returnType = 'array';
}
