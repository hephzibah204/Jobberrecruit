<?php

namespace App\Controllers;

use App\Models\EmailTemplateModel;
use App\Services\EmailTemplateService;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminEmailTemplateController extends BaseController
{
    protected $templateModel;
    protected $templateService;

    public function __construct()
    {
        $this->templateService = new EmailTemplateService();
        $this->templateService->ensureTableExists();
        $this->templateModel = model(EmailTemplateModel::class);
    }

    /**
     * List all email templates with category filtering & statistics
     */
    public function index()
    {
        // Ensure table exists and all system templates are seeded
        $this->templateService->ensureTableExists();
        $this->templateService->seedDefaults();

        $selectedCategory = $this->request->getGet('category') ?? 'all';
        $searchQuery = trim((string)$this->request->getGet('q'));

        try {
            $builder = $this->templateModel;

            if (!empty($selectedCategory) && $selectedCategory !== 'all') {
                $builder = $builder->where('category', $selectedCategory);
            }

            if (!empty($searchQuery)) {
                $builder = $builder->groupStart()
                    ->like('name', $searchQuery)
                    ->orLike('subject', $searchQuery)
                    ->orLike('template_key', $searchQuery)
                    ->groupEnd();
            }

            $templates = $builder->orderBy('category', 'ASC')
                                 ->orderBy('name', 'ASC')
                                 ->findAll();

            // Get category stats
            $allTemplates = $this->templateModel->findAll();
        } catch (\Throwable $e) {
            log_message('error', 'AdminEmailTemplateController index query error: ' . $e->getMessage());
            // Retry after explicit ensureTableExists
            $this->templateService->ensureTableExists();
            $this->templateService->seedDefaults();
            $templates = $this->templateModel->findAll();
            $allTemplates = $templates;
        }

        $categories = [];
        $totalActive = 0;

        foreach ($allTemplates as $tpl) {
            $cat = $tpl->category;
            $categories[$cat] = ($categories[$cat] ?? 0) + 1;
            if ($tpl->is_active) {
                $totalActive++;
            }
        }

        return view('admin/email_templates/index', [
            'title'            => 'Email Templates Management',
            'templates'        => $templates,
            'selectedCategory' => $selectedCategory,
            'searchQuery'      => $searchQuery,
            'categories'       => $categories,
            'totalCount'       => count($allTemplates),
            'activeCount'      => $totalActive,
        ]);
    }

    /**
     * Edit Email Template
     */
    public function edit(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            throw PageNotFoundException::forPageNotFound("Email template with ID #{$id} not found.");
        }

        $availableVariables = !empty($template->available_variables)
            ? json_decode($template->available_variables, true)
            : [];

        // Global variables available on all templates
        $globalVariables = [
            '{first_name}'   => 'Recipient\'s first name (e.g. John)',
            '{last_name}'    => 'Recipient\'s last name (e.g. Doe)',
            '{full_name}'    => 'Recipient\'s full name (e.g. John Doe)',
            '{greeting}'     => 'Automatic greeting based on preference (e.g. Hello John,)',
            '{site_name}'    => 'Platform name (JobberRecruit)',
            '{site_url}'     => 'Platform website home URL',
            '{login_url}'    => 'Candidate / Employer login URL',
            '{current_year}' => 'Current year (e.g. ' . date('Y') . ')',
            '{support_email}'=> 'Support email address',
        ];

        return view('admin/email_templates/edit', [
            'title'              => 'Edit Template: ' . $template->name,
            'template'           => $template,
            'availableVariables' => array_merge($globalVariables, (array)$availableVariables),
        ]);
    }

    /**
     * Update Email Template
     */
    public function update(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            throw PageNotFoundException::forPageNotFound();
        }

        $subject      = trim((string)$this->request->getPost('subject'));
        $greetingType = $this->request->getPost('greeting_type') ?? 'first_name';
        $bodyHtml     = $this->request->getPost('body_html');
        $bodyText     = $this->request->getPost('body_text');
        $isActive     = $this->request->getPost('is_active') ? 1 : 0;

        if (empty($subject) || empty($bodyHtml)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Subject and HTML Email Body cannot be empty.');
        }

        $this->templateModel->update($id, [
            'subject'       => $subject,
            'greeting_type' => in_array($greetingType, ['first_name', 'full_name', 'custom']) ? $greetingType : 'first_name',
            'body_html'     => $bodyHtml,
            'body_text'     => !empty($bodyText) ? $bodyText : strip_tags($bodyHtml),
            'is_active'     => $isActive,
        ]);

        return redirect()->to(base_url('admin/email-templates'))
            ->with('success', "Email template '{$template->name}' updated successfully!");
    }

    /**
     * Preview Rendered Email (HTML response for iframe / modal)
     */
    public function preview(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return $this->response->setStatusCode(404)->setBody('Template not found');
        }

        $sampleData = $this->templateService->getSampleData($template->template_key);
        $rendered = $this->templateService->render($template->template_key, $sampleData);

        return $this->response->setBody($rendered['html']);
    }

    /**
     * Send live test email to admin's test address
     */
    public function sendTest(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return $this->response->setJSON(['success' => false, 'message' => 'Template not found.']);
        }

        $toEmail = trim((string)$this->request->getPost('test_email'));
        if (empty($toEmail) || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please provide a valid email address.']);
        }

        $sampleData = $this->templateService->getSampleData($template->template_key);
        $sampleData['first_name'] = 'Admin (Test)';
        $sampleData['full_name']  = 'Administrator Test';
        $sampleData['name']       = 'Administrator Test';

        $success = $this->templateService->send($template->template_key, $toEmail, $sampleData);

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Test email for '{$template->name}' was successfully sent to {$toEmail}!",
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => "Failed to send test email. Please verify your mail server settings.",
        ]);
    }

    /**
     * Reset template to factory default
     */
    public function reset(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            throw PageNotFoundException::forPageNotFound();
        }

        $success = $this->templateService->resetToDefault($template->template_key);

        if ($success) {
            return redirect()->back()->with('success', "Template '{$template->name}' has been restored to factory defaults.");
        }

        return redirect()->back()->with('error', "Could not reset template.");
    }

    /**
     * Toggle Active status via AJAX
     */
    public function toggleStatus(?int $id = null)
    {
        // Extract ID from argument, POST, or JSON body
        if (empty($id)) {
            $id = (int) ($this->request->getPost('id') ?? $this->request->getVar('id') ?? 0);
            if (empty($id)) {
                $rawJson = $this->request->getJSON();
                if (!empty($rawJson->id)) {
                    $id = (int) $rawJson->id;
                }
            }
        }

        $template = $this->templateModel->find($id);
        if (!$template) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'Email template not found',
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
            ]);
        }

        $postedStatus = $this->request->getPost('is_active') ?? $this->request->getVar('is_active');
        if ($postedStatus === null) {
            $rawJson = $this->request->getJSON();
            if (isset($rawJson->is_active)) {
                $postedStatus = $rawJson->is_active;
            }
        }

        $newStatus = ($postedStatus !== null) ? ((int)$postedStatus ? 1 : 0) : ($template->is_active ? 0 : 1);
        $this->templateModel->update($id, ['is_active' => $newStatus]);

        return $this->response->setJSON([
            'success'    => true,
            'is_active'  => $newStatus,
            'message'    => "Template '{$template->name}' is now " . ($newStatus ? 'Active' : 'Inactive') . '.',
            'csrf_token' => csrf_token(),
            'csrf_hash'  => csrf_hash(),
        ]);
    }
}