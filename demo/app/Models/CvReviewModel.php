<?php

namespace App\Models;

use CodeIgniter\Model;

class CvReviewModel extends Model
{
    protected $table = 'cv_reviews';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id', 'plan', 'amount', 'payment_reference', 'payment_status',
        'file_path', 'full_name', 'email', 'phone', 'industry', 'target_role',
        'feedback_request', 'admin_notes', 'status',
        'ai_review', 'review_mode', 'reviewed_at', 'admin_id',
        'feedback_delivered', 'delivered_at',
    ];

    public function pending()
    {
        return $this->where('status', 'pending');
    }

    public function inReview()
    {
        return $this->where('status', 'in_review');
    }

    public function completed()
    {
        return $this->where('status', 'completed');
    }

    public function forUser(int $userId)
    {
        return $this->where('user_id', $userId);
    }

    public function recent(int $limit = 20)
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }
}
