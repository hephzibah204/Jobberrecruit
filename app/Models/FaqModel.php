<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Faq;

class FaqModel extends Model
{
    protected $table            = 'faqs';
    protected $primaryKey       = 'id';
    protected $returnType       = Faq::class;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'question',
        'answer',
        'category',
        'sort_order',
        'is_active',
    ];
}
