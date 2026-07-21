<?php

namespace App\Controllers;

use App\Models\AutomationModel;
use App\Models\AutomationStepModel;

class NewsletterAutomationController extends BaseController
{
    public function index()
    {
        $automationModel = new AutomationModel();
        
        $data = [
            'title' => 'Automated Campaigns',
            'automations' => $automationModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/newsletters/automations/index', $data);
    }

    public function builder($id = null)
    {
        $automationModel = new AutomationModel();
        $stepModel = new AutomationStepModel();
        
        $automation = $id ? $automationModel->find($id) : null;
        $steps = $id ? $stepModel->where('automation_id', $id)->orderBy('step_order', 'ASC')->findAll() : [];
        
        $templateModel = new \App\Models\NewsletterTemplateModel();

        $data = [
            'title' => $id ? 'Edit Automation' : 'New Automation',
            'automation' => $automation,
            'steps' => $steps,
            'templates' => $templateModel->findAll()
        ];

        return view('admin/newsletters/automations/builder', $data);
    }

    public function save()
    {
        $automationModel = new AutomationModel();
        
        $id = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'trigger_event' => $this->request->getPost('trigger_event'),
            'status' => $this->request->getPost('status') ?: 'draft'
        ];

        if ($id) {
            $automationModel->update($id, $data);
        } else {
            $id = $automationModel->insert($data);
        }

        return redirect()->to("admin/newsletters/automations/builder/{$id}")->with('success', 'Automation saved successfully.');
    }
}
