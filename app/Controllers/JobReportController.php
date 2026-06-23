<?php

namespace App\Controllers;

use App\Models\JobReportModel;
use App\Models\JobModel;
use CodeIgniter\API\ResponseTrait;

class JobReportController extends BaseController
{
    use ResponseTrait;

    protected $reportModel;
    protected $jobModel;

    public function __construct()
    {
        $this->reportModel = new JobReportModel();
        $this->jobModel = new JobModel();
    }

    /**
     * Submit a job report
     */
    public function submit()
    {
        $jobId = $this->request->getPost('job_id');
        $reason = $this->request->getPost('reason');
        $details = $this->request->getPost('details');

        if (!$jobId || !$reason) {
            return $this->fail('Job ID and Reason are required');
        }

        $job = $this->jobModel->find($jobId);
        if (!$job) {
            return $this->failNotFound('Job not found');
        }

        $this->reportModel->insert([
            'job_id' => $jobId,
            'user_id' => auth()->id() ?? null,
            'reason' => $reason,
            'details' => $details,
            'status' => 'pending'
        ]);

        return $this->respondCreated(['message' => 'Thank you for your report. Our team will investigate this job post.']);
    }

    /**
     * Admin: List all reports
     */
    public function adminIndex()
    {
        return view('admin/reports/index', [
            'title' => 'Job Fraud/Scam Reports',
            'reports' => $this->reportModel->getReportsWithDetails()
        ]);
    }

    /**
     * Admin: Take action on a report
     */
    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!$id || !$status) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        // Get report details to find the associated job
        $report = $this->reportModel->find($id);
        if (!$report) {
            return redirect()->back()->with('error', 'Report not found');
        }

        // Start transaction for atomicity
        $db = \Config\Database::connect();
        $db->transStart();

        // Update report status
        $this->reportModel->update($id, ['status' => $status]);

        // If the action is "acted" (confirmed fraud), deactivate the job
        if ($status === 'acted') {
            $this->jobModel->update($report->job_id, [
                'status' => 'deactivated',
                'admin_notes' => 'Deactivated due to confirmed fraud report #' . $id
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to update status and deactivate job');
        }

        $msg = ($status === 'acted') ? 'Report marked as acted and job has been deactivated.' : 'Report status updated to ' . $status;
        return redirect()->back()->with('success', $msg);
    }
}
