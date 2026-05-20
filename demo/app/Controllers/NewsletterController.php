<?php

namespace App\Controllers;

use App\Models\NewsletterModel;
use App\Models\NewsletterSubscriberModel;
use App\Models\WebinarModel;
use App\Models\WebinarRegistrationModel;
use CodeIgniter\API\ResponseTrait;

class NewsletterController extends BaseController
{
    use ResponseTrait;

    protected $newsletterModel;
    protected $subscriberModel;
    protected $webinarModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->newsletterModel = new NewsletterModel();
        $this->subscriberModel = new NewsletterSubscriberModel();
        $this->webinarModel = new WebinarModel();
        $this->registrationModel = new WebinarRegistrationModel();
    }

    /**
     * Public webinars listing
     */
    public function webinars()
    {
        return view('home/webinars', [
            'title' => 'Upcoming Career Webinars',
            'webinars' => $this->webinarModel->where('status !=', 'cancelled')
                                            ->orderBy('scheduled_at', 'ASC')
                                            ->findAll()
        ]);
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribe()
    {
        $email = $this->request->getPost('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->fail('Invalid email address');
        }

        $existing = $this->subscriberModel->where('email', $email)->first();
        if ($existing) {
            if ($existing->is_active) {
                return $this->failResourceExists('You are already subscribed');
            } else {
                $this->subscriberModel->update($existing->id, ['is_active' => 1]);
                return $this->respondCreated(['message' => 'Subscription reactivated']);
            }
        }

        $this->subscriberModel->insert([
            'email' => $email,
            'user_id' => auth()->id() ?? null,
            'is_active' => 1
        ]);

        return $this->respondCreated(['message' => 'Successfully subscribed to newsletter']);
    }

    /**
     * Register for a webinar
     */
    public function registerWebinar($webinarId)
    {
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('Please login to register for webinars');
        }

        $webinar = $this->webinarModel->find($webinarId);
        if (!$webinar) {
            return $this->failNotFound('Webinar not found');
        }

        $existing = $this->registrationModel->where([
            'webinar_id' => $webinarId,
            'user_id' => auth()->id()
        ])->first();

        if ($existing) {
            return $this->failResourceExists('You are already registered for this webinar');
        }

        $this->registrationModel->insert([
            'webinar_id' => $webinarId,
            'user_id' => auth()->id()
        ]);

        return $this->respondCreated(['message' => 'Successfully registered for the webinar']);
    }

    // --- Admin Methods ---

    public function adminIndex()
    {
        return view('admin/newsletters/index', [
            'title' => 'Newsletter & Webinar Management',
            'newsletters' => $this->newsletterModel->orderBy('created_at', 'DESC')->findAll(),
            'subscribers' => $this->subscriberModel->countAllResults(),
            'webinars' => $this->webinarModel->orderBy('scheduled_at', 'DESC')->findAll(),
        ]);
    }

    public function saveNewsletter()
    {
        $id = $this->request->getPost('id');
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'status' => 'draft'
        ];

        if ($id) {
            $this->newsletterModel->update($id, $data);
        } else {
            $this->newsletterModel->insert($data);
        }

        return redirect()->back()->with('success', 'Newsletter saved as draft');
    }

    public function sendNewsletter($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found');
        }

        $subscribers = $this->subscriberModel->where('is_active', 1)->findAll();
        
        $queueModel = new \App\Models\JobQueueModel();
        
        foreach ($subscribers as $subscriber) {
            $queueModel->dispatch('newsletter_email', [
                'newsletter_id' => $id,
                'email' => $subscriber->email,
                'subject' => $newsletter->title,
                'content' => $newsletter->content
            ]);
        }
        
        $this->newsletterModel->update($id, [
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Newsletter queued for ' . count($subscribers) . ' subscribers. They will be sent in the background.');
    }

    public function saveWebinar()
    {
        $id = $this->request->getPost('id');
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'speaker_name' => $this->request->getPost('speaker_name'),
            'scheduled_at' => $this->request->getPost('scheduled_at'),
            'meeting_link' => $this->request->getPost('meeting_link'),
            'status' => $this->request->getPost('status') ?? 'upcoming'
        ];

        if ($id) {
            $this->webinarModel->update($id, $data);
        } else {
            $this->webinarModel->insert($data);
        }

        return redirect()->back()->with('success', 'Webinar saved successfully');
    }

    public function adminWebinarsIndex()
    {
        return view('admin/webinars/index', [
            'title' => 'Webinar Management',
            'webinars' => $this->webinarModel->orderBy('scheduled_at', 'DESC')->findAll(),
        ]);
    }
}
