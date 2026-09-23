<?php

namespace App\Controllers;

use App\Models\NewsletterTemplateModel;

class NewsletterTemplateController extends BaseController
{
    public function store()
    {
        $templateModel = new NewsletterTemplateModel();
        
        $name = $this->request->getPost('name');
        $html_content = $this->request->getPost('html_content');
        
        if (empty($name) || empty($html_content)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Template name and content are required']);
        }

        $templateModel->insert([
            'name' => $name,
            'html_content' => $html_content,
            'thumbnail_url' => null // Can be added later
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Template saved successfully']);
    }

    public function fetchAll()
    {
        $templateModel = new NewsletterTemplateModel();
        return $this->response->setJSON([
            'status' => 'success',
            'templates' => $templateModel->orderBy('created_at', 'DESC')->findAll()
        ]);
    }
}
