<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterSubscriberModel extends Model
{
    protected $table            = 'newsletter_subscribers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'user_id', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at for subscribers
}
