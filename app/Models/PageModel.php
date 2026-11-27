<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Page;

class PageModel extends Model
{
    protected $table            = 'pages';
    protected $primaryKey       = 'id';
    protected $returnType       = Page::class;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'slug',
        'title',
        'content',
        'is_active',
    ];
}
