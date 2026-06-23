<?php

namespace App\Services;

use App\Models\JobAlertModel;
use App\Models\JobModel;
use App\Models\JobSeekerModel;

class JobAlertService
{
    protected $alertModel;
    protected $jobModel;

    public function __construct()
    {
        $this->alertModel = model(JobAlertModel::class);
        $this->jobModel   = model(JobModel::class);
    }

    public function processAlerts(string $frequency)
    {
        $alerts = $this->alertModel
            ->where('frequency', $frequency)
            ->where('is_active', 1)
            ->findAll();

        foreach ($alerts as $alert) {
            $jobs = $this->findMatchingJobs($alert);

            if (empty($jobs)) {
                continue;
            }

            $this->deliverAlert($alert, $jobs);

            $this->alertModel->update($alert->id, [
                'last_sent_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    protected function findMatchingJobs($alert): array
    {
        $builder = $this->jobModel
            ->where('status', 'open');

        if ($alert->keyword) {
            $builder->groupStart()
                ->like('title', $alert->keyword)
                ->orLike('description', $alert->keyword)
                ->groupEnd();
        }

        if ($alert->location_id) {
            $builder->where('state_id', $alert->location_id);
        }

        if ($alert->last_sent_at) {
            $builder->where('created_at >', $alert->last_sent_at);
        }

        return $builder->findAll();
    }

    protected function deliverAlert($alert, array $jobs)
    {
        if ($alert->channel === 'email') {
            $this->sendEmailAlert($alert, $jobs);
        }

        // Future:
        // sms
        // in_app
    }

    protected function sendEmailAlert($alert, array $jobs)
    {
        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->find($alert->job_seeker_id);

        if (!$candidate) {
            return;
        }

        $emailService = service('mailer');

        $emailService->sendTemplate(
            $candidate->email,
            'New Job Alerts Matching Your Preference',
            'emails/job_alert',
            [
                'candidate' => $candidate,
                'jobs'      => $jobs,
            ]
        );
    }
}
