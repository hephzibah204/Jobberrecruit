<?php

namespace App\Libraries;

use App\Models\AutomationModel;
use App\Models\AutomationStepModel;
use App\Models\AutomationSubscriberModel;
use App\Models\NewsletterSubscriberModel;

class AutomationEngine
{
    /**
     * Trigger an event to enroll users into automations.
     *
     * @param string $eventName The event (e.g. 'user_registered')
     * @param int $userId The ID of the user that triggered it
     */
    public function triggerEvent(string $eventName, int $userId)
    {
        $automationModel = new AutomationModel();
        
        // Find all active automations listening for this trigger
        $automations = $automationModel->where('status', 'active')
                                       ->where('trigger_event', $eventName)
                                       ->findAll();
                                       
        if (empty($automations)) {
            return; // No active automations for this event
        }

        // We need to map the $userId to a newsletter_subscribers record
        // because automations send emails to newsletter subscribers.
        $subscriberModel = new NewsletterSubscriberModel();
        $subscriber = $subscriberModel->where('user_id', $userId)->first();
        
        // If the user isn't a subscriber, we could auto-subscribe them (for system transactional emails)
        // or just ignore. For now, let's assume they must be subscribed, or we auto-create a shadow subscriber.
        if (!$subscriber) {
            // Check if we can find their email from the auth system
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            if ($user) {
                $subId = $subscriberModel->insert([
                    'user_id' => $userId,
                    'email' => $user->email,
                    'is_active' => 1
                ]);
                $subscriber = $subscriberModel->find($subId);
            } else {
                return; // User not found, abort
            }
        }

        $stepModel = new AutomationStepModel();
        $autoSubModel = new AutomationSubscriberModel();

        foreach ($automations as $automation) {
            // Check if already enrolled to prevent duplicate journeys
            $existing = $autoSubModel->where('automation_id', $automation->id)
                                     ->where('subscriber_id', $subscriber->id)
                                     ->first();
            if ($existing) continue;

            // Get the first step of this automation
            $firstStep = $stepModel->where('automation_id', $automation->id)
                                   ->orderBy('step_order', 'ASC')
                                   ->first();
                                   
            if (!$firstStep) continue;

            // Calculate next step time based on delay
            $nextStepAt = date('Y-m-d H:i:s');
            if ($firstStep->delay_minutes > 0) {
                $nextStepAt = date('Y-m-d H:i:s', strtotime("+{$firstStep->delay_minutes} minutes"));
            }

            // Enroll the subscriber
            $autoSubModel->insert([
                'automation_id' => $automation->id,
                'subscriber_id' => $subscriber->id,
                'current_step_id' => $firstStep->id,
                'status' => 'in_progress',
                'enrolled_at' => date('Y-m-d H:i:s'),
                'next_step_at' => $nextStepAt
            ]);
        }
    }
}
