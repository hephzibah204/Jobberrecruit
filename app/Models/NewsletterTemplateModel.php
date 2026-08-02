<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterTemplateModel extends Model
{
    protected $table            = 'newsletter_templates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'html_content', 'thumbnail_url'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
