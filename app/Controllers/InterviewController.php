<?php

namespace App\Controllers;

use App\Models\EmployerModel;
use App\Models\InterviewModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class InterviewController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'text']);
    }

    /**
     * Public interview page. The 64-character token in the URL is the only
     * credential — candidates (including guest applicants) have no account
     * to sign in with. Every interview row carries a token from creation,
     * so a found token is always viewable.
     */
    public function view($token)
    {
        $interview = model(InterviewModel::class)->where('view_token', $token)->first();

        if (!$interview) {
            throw PageNotFoundException::forPageNotFound('This interview link is invalid or has expired.');
        }

        $employer = model(EmployerModel::class)->find($interview->employer_id);

        return view('interviews/view', [
            'interview' => $interview,
            'employer'  => $employer,
        ]);
    }

    /**
     * Record the candidate's response. One response only: once the status
     * leaves 'scheduled', the recorded answer stands and the form
     * disappears. A cancelled interview accepts no response at all.
     */
    public function respond($token)
    {
        $interview = model(InterviewModel::class)->where('view_token', $token)->first();

        if (!$interview) {
            throw PageNotFoundException::forPageNotFound('This interview link is invalid or has expired.');
        }

        if ($interview->status === 'cancelled') {
            return redirect()->to('interview/view/' . $token)->with('error', 'This interview has been cancelled by the employer.');
        }

        if ($interview->status !== 'scheduled') {
            return redirect()->to('interview/view/' . $token)->with('error', 'You have already responded to this interview invitation.');
        }

        $decision = $this->request->getPost('decision');
        if (!in_array($decision, ['confirm', 'decline'], true)) {
            return redirect()->to('interview/view/' . $token)->with('error', 'Please choose Confirm Attendance or Decline.');
        }

        model(InterviewModel::class)->update($interview->id, [
            'status'       => $decision === 'confirm' ? 'confirmed' : 'declined',
            'responded_at' => date('Y-m-d H:i:s'),
        ]);

        $message = $decision === 'confirm'
            ? 'Thank you — your attendance is confirmed. The employer can see your response on their dashboard. We look forward to speaking with you.'
            : 'You have declined this interview invitation. Thank you for letting the employer know.';

        return redirect()->to('interview/view/' . $token)->with('success', $message);
    }
}
