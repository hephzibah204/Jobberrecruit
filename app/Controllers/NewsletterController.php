<?php

namespace App\Controllers;

use App\Models\NewsletterModel;
use App\Models\NewsletterSubscriberModel;
use App\Models\NewsletterTemplateModel;
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
    protected $industryModel;

    public function __construct()
    {
        $this->newsletterModel = new NewsletterModel();
        $this->subscriberModel = new NewsletterSubscriberModel();
        $this->webinarModel = new WebinarModel();
        $this->registrationModel = new WebinarRegistrationModel();
        $this->industryModel = new \App\Models\IndustryModel();
    }

    /**
     * Public webinars listing
     */
    public function webinars()
    {
        return view('webinars_public', [
            'title' => 'Upcoming Career Webinars',
            'webinars' => $this->webinarModel->where('status !=', 'cancelled')
                                            ->orderBy('scheduled_at', 'ASC')
                                            ->findAll()
        ]);
    }

    public function registered()
    {
        return view('webinar_registered', [
            'title' => 'Webinar Registration Confirmed'
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
        $newsletters = $this->newsletterModel->orderBy('created_at', 'DESC')->findAll();
        $webinars = $this->webinarModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/newsletters/index', [
            'title' => 'Newsletters & Webinars',
            'newsletters' => $newsletters,
            'webinars' => $webinars,
            'subscribers' => $this->subscriberModel->where('is_active', 1)->countAllResults()
        ]);
    }

    public function create()
    {
        return view('admin/newsletters/editor', [
            'title' => 'Create Newsletter',
            'newsletter' => null,
            'industries' => $this->industryModel->orderBy('name', 'ASC')->findAll()
        ]);
    }

    public function edit($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (!$newsletter) {
            return redirect()->to('admin/newsletters')->with('error', 'Newsletter not found');
        }

        return view('admin/newsletters/editor', [
            'title' => 'Edit Newsletter',
            'newsletter' => $newsletter,
            'industries' => $this->industryModel->orderBy('name', 'ASC')->findAll()
        ]);
    }

    public function saveNewsletter()
    {
        $rules = [
            'title' => 'required',
            'subject' => 'permit_empty',
            'target_group' => 'permit_empty',
            'content' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $id = $this->request->getPost('id');
        $data = [
            'title' => $this->request->getPost('title'),
            // subject/target_group are NOT NULL columns; default when omitted
            'subject' => $this->request->getPost('subject') ?? '',
            'target_group' => $this->request->getPost('target_group') ?? 'general',
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

    public function deleteNewsletter($id)
    {
        $newsletter = $this->newsletterModel->find($id);
        if (! $newsletter) {
            return redirect()->to('admin/newsletters')->with('error', 'Newsletter not found.');
        }

        $this->newsletterModel->delete($id);
        return redirect()->to('admin/newsletters')->with('success', 'Newsletter deleted successfully.');
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

    public function deleteWebinar($id)
    {
        $this->webinarModel->delete($id);
        return redirect()->back()->with('success', 'Webinar deleted successfully.');
    }

    /**
     * View all newsletter subscribers
     */
    public function adminSubscribers()
    {
        return view('admin/newsletters/subscribers', [
            'title' => 'Newsletter Subscribers',
            'subscribers' => $this->subscriberModel->orderBy('created_at', 'DESC')->findAll()
        ]);
    }

    /**
     * Delete a subscriber
     */
    public function deleteSubscriber($id)
    {
        $this->subscriberModel->delete($id);
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }

    /**
     * Export subscriber emails as CSV
     */
    public function exportSubscribers()
    {
        $subscribers = $this->subscriberModel->where('is_active', 1)->findAll();
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Email', 'User ID', 'Subscribed At', 'Status']);
        
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber->email,
                $subscriber->user_id ?? 'Guest',
                $subscriber->created_at ?? '',
                $subscriber->is_active ? 'Active' : 'Inactive'
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Get list of all newsletter templates, auto-seeding defaults if empty.
     */
    public function listTemplates()
    {
        $templateModel = new NewsletterTemplateModel();
        
        if ($templateModel->countAllResults() === 0) {
            $defaults = [
                [
                    'name' => 'General Announcement Template',
                    'html_content' => view('emails/newsletter_general', [
                        'title'   => 'Important Platform Updates & Announcements',
                        'content' => '<h4>Welcome to the JobberRecruit Announcement!</h4><p>We are thrilled to bring you the latest developments, platform updates, and feature upgrades designed to make recruiting and career-building simpler for everyone.</p><p>Use this space to tell your subscribers about your news, product additions, or announcements.</p>',
                        'email'   => 'subscriber@example.com'
                    ])
                ],
                [
                    'name' => 'Candidate Careers Template',
                    'html_content' => view('emails/newsletter_candidate', [
                        'title'   => 'Top Career Tips & Job Search Success Strategies',
                        'content' => '<h4>Maximize Your Opportunities Today!</h4><p>Explore custom career hacks, mock interview guidance, and the best ways to match with employers looking for your exact skillset.</p><p>Stay ahead of the curve with our expert resources curated specially for candidates like you.</p>',
                        'email'   => 'candidate@example.com'
                    ])
                ],
                [
                    'name' => 'Employer Talent Update Template',
                    'html_content' => view('emails/newsletter_employer', [
                        'title'   => 'Attract, Screen & Retain Top Talent Efficiently',
                        'content' => '<h4>Build the Ultimate Team with JobberRecruit</h4><p>Learn how to utilize our smart screening workflows, aptitude testing tools, and direct talent sourcing filters to find matching candidates in record time.</p>',
                        'email'   => 'employer@example.com'
                    ])
                ]
            ];
            
            foreach ($defaults as $tmpl) {
                $templateModel->insert($tmpl);
            }
        }
        
        $templates = $templateModel->orderBy('created_at', 'DESC')->findAll();
        
        return $this->response->setJSON([
            'status'    => 'success',
            'templates' => $templates
        ]);
    }

    /**
     * Save a newsletter template into the library.
     */
    public function storeTemplate()
    {
        $name = $this->request->getPost('name');
        $html = $this->request->getPost('html_content');
        
        if (empty($name) || empty($html)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Template name and content are required'
            ]);
        }
        
        $templateModel = new NewsletterTemplateModel();
        $templateModel->insert([
            'name'         => $name,
            'html_content' => $html
        ]);
        
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Template successfully saved to library'
        ]);
    }
}
