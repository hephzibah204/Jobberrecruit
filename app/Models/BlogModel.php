<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table      = 'blogs';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Blog::class;
    protected $allowedFields = ['admin_id', 'title', 'excerpt', 'slug', 'content', 'thumbnail', 'preview_token', 'meta_title', 'meta_description', 'meta_keywords', 'views', 'status', 'category', 'tags', 'is_featured'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
