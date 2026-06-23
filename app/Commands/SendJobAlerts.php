<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SendJobAlerts extends BaseCommand
{
    protected $group       = 'Alerts';
    protected $name        = 'alerts:send';
    protected $description = 'Send job alerts based on candidate preferences, frequency, pause and snooze settings.';

    public function run(array $params)
    {
        $alertModel     = model('JobAlertModel');
        $candidateModel = model('JobSeekerModel');
        $jobModel       = model('JobModel');
        $email          = service('email');

        $nowTime   = date('H:i');
        $nowDate   = date('Y-m-d');
        $nowDateTime = date('Y-m-d H:i:s');

        log_message('info', 'This should be called');

        /**
         * Fetch alerts that:
         * - are active
         * - are NOT paused
         * - are not snoozed OR snooze has expired
         * - delivery time has passed
         * - not already sent today
         */
        $alerts = $alertModel
            ->where('is_active', 1)
            ->where('is_paused', 0)
            ->groupStart()
            ->where('snooze_until IS NULL')
            ->orWhere('snooze_until <=', $nowDateTime)
            ->groupEnd()
            ->where('delivery_time <=', $nowTime)
            ->groupStart()
            ->where('last_sent_at IS NULL')
            ->orWhere('DATE(last_sent_at) <', $nowDate)
            ->groupEnd()
            ->findAll();

        foreach ($alerts as $alert) {

            /**
             * -------------------------------------------------
             * FREQUENCY CHECK
             * -------------------------------------------------
             */
            $send = false;

            switch ($alert->frequency) {
                case 'daily':
                    $send = true;
                    break;

                case 'weekly':
                    $send = (date('N') == 1); // Monday
                    break;

                case 'monthly':
                    $send = (date('j') == 1); // First day of month
                    break;
            }

            if (! $send) {
                continue;
            }

            /**
             * -------------------------------------------------
             * CHANNEL CHECK (EMAIL ONLY FOR NOW)
             * -------------------------------------------------
             */
            if ($alert->channel !== 'email') {
                continue;
            }

            /**
             * -------------------------------------------------
             * AUTO-RESUME SNOOZED ALERTS (SAFETY)
             * -------------------------------------------------
             */
            if ($alert->snooze_until && strtotime($alert->snooze_until) <= time()) {
                $alertModel->update($alert->id, [
                    'is_paused'   => 0,
                    'snooze_until' => null,
                ]);
            }

            /**
             * -------------------------------------------------
             * FETCH CANDIDATE
             * -------------------------------------------------
             */
            $candidate = $candidateModel->find($alert->job_seeker_id);
            if (! $candidate || empty($candidate->email)) {
                continue;
            }

            /**
             * -------------------------------------------------
             * FETCH ONLY NEW MATCHING JOBS
             * -------------------------------------------------
             */
            $jobs = $jobModel->getMatchingJobs(
                $alert->keyword,
                $alert->location_id,
                $candidate->id,
                $alert->last_sent_at
            );

            if (empty($jobs)) {
                continue;
            }

            /**
             * -------------------------------------------------
             * BUILD EMAIL
             * -------------------------------------------------
             */
            $html = view('emails/job_alert', [
                'candidate' => $candidate,
                'jobs'      => $jobs,
                'alert'     => $alert,
            ]);

            $email->clear();
            $email->setTo($candidate->email);
            $email->setSubject('New Jobs Matching Your Alert');
            $email->setMessage($html);
            $email->setMailType('html');

            /**
             * -------------------------------------------------
             * SEND & UPDATE LAST SENT TIME
             * -------------------------------------------------
             */
            if ($email->send()) {
                $alertModel->update($alert->id, [
                    'last_sent_at' => $nowDateTime,
                ]);
            }
        }

        CLI::write('Job alerts processed successfully.', 'green');
    }
}
