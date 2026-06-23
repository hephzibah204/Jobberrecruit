<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateTemplateModel extends Model
{
    protected $table      = 'certificate_templates';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'course_id',
        'template_name',
        'background_image',
        'primary_color',
        'secondary_color',
        'text_color',
        'show_qr_code',
        'show_signature',
        'show_logo',
        'is_full_image_mode',
        'template_mode',
        'custom_html',
        'layout_json',
        'additional_text',
    ];

    protected $useTimestamps = true;

    public function getTemplateForCourse($courseId = null)
    {
        if ($courseId) {
            $template = $this->where('course_id', $courseId)->first();
            if ($template) {
                return $template;
            }
        }
        
        // Fallback to global default
        return $this->where('course_id', null)->first();
    }
}
