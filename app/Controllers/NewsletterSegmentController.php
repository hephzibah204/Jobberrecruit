<?php

namespace App\Controllers;

use App\Models\AudienceSegmentModel;

class NewsletterSegmentController extends BaseController
{
    public function index()
    {
        $segmentModel = new AudienceSegmentModel();
        
        $data = [
            'title' => 'Audience Segments',
            'segments' => $segmentModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/newsletters/segments/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Build Dynamic Segment'
        ];
        return view('admin/newsletters/segments/create', $data);
    }

    public function store()
    {
        $segmentModel = new AudienceSegmentModel();
        
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'criteria_json' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please complete all required fields.');
        }

        $segmentModel->insert([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type'),
            'criteria_json' => $this->request->getPost('criteria_json'),
            'user_count' => 0 // Would be updated by a cron job in reality
        ]);

        return redirect()->to('admin/newsletters/segments')->with('success', 'Audience segment created successfully.');
    }

    public function testSegment()
    {
        // In a real scenario, this would parse the JSON rules and execute a COUNT() query
        // For now, we return a mock number to demonstrate the UI
        $rules = $this->request->getPost('rules');
        
        if (!$rules) {
            return $this->response->setJSON(['status' => 'error']);
        }
        
        // Mock calculation
        $count = rand(100, 5000);
        
        return $this->response->setJSON([
            'status' => 'success',
            'count' => number_format($count)
        ]);
    }
}
