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

        try {
            $count = (new \App\Services\SegmentAudienceService())->count($this->request->getPost('criteria_json'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', 'The segment rules could not be evaluated: ' . $e->getMessage());
        }

        $segmentModel->insert([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type'),
            'criteria_json' => $this->request->getPost('criteria_json'),
            'user_count' => $count,
            'last_synced_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('admin/newsletters/segments')->with('success', 'Audience segment created successfully.');
    }

    public function testSegment()
    {
        $rules = $this->request->getPost('rules');

        if (!$rules) {
            return $this->response->setJSON(['status' => 'error']);
        }

        try {
            $preview = (new \App\Services\SegmentAudienceService())->preview($rules);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'count' => number_format($preview['count']),
            'sample' => $preview['sample']
        ]);
    }

    public function refresh($id)
    {
        $segmentModel = new AudienceSegmentModel();
        $segment = $segmentModel->find($id);

        if (!$segment) {
            return redirect()->to('admin/newsletters/segments')->with('error', 'Segment not found.');
        }

        try {
            $count = (new \App\Services\SegmentAudienceService())->count($segment->criteria_json);
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', 'The segment rules could not be evaluated: ' . $e->getMessage());
        }

        $segmentModel->update($id, [
            'user_count' => $count,
            'last_synced_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', "Segment '{$segment->name}' now matches " . number_format($count) . ' subscribers.');
    }
}
