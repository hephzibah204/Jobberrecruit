<?php

namespace App\Controllers;

use App\Models\EmployerModel;
use App\Models\OfferLetterModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class OfferController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'text']);
    }

    /**
     * Public offer page. The 64-character token in the URL is the only
     * credential — candidates (including guest applicants) have no account
     * to sign in with. Draft offers are never exposed here.
     */
    public function view($token)
    {
        $offer = model(OfferLetterModel::class)->where('view_token', $token)->first();

        if (!$offer || $offer->status === 'draft') {
            throw PageNotFoundException::forPageNotFound('This offer link is invalid or has expired.');
        }

        $employer = model(EmployerModel::class)->find($offer->employer_id);

        return view('offers/view', [
            'offer'    => $offer,
            'employer' => $employer,
        ]);
    }

    /**
     * Record the candidate's response. One response only: once the status
     * leaves 'sent', the recorded answer stands and the form disappears.
     */
    public function respond($token)
    {
        $offer = model(OfferLetterModel::class)->where('view_token', $token)->first();

        if (!$offer || $offer->status === 'draft') {
            throw PageNotFoundException::forPageNotFound('This offer link is invalid or has expired.');
        }

        if ($offer->status !== 'sent') {
            return redirect()->to('offer/view/' . $token)->with('error', 'You have already responded to this offer.');
        }

        $decision = $this->request->getPost('decision');
        if (!in_array($decision, ['accept', 'decline'], true)) {
            return redirect()->to('offer/view/' . $token)->with('error', 'Please choose Accept or Decline.');
        }

        model(OfferLetterModel::class)->update($offer->id, [
            'status'       => $decision === 'accept' ? 'accepted' : 'declined',
            'responded_at' => date('Y-m-d H:i:s'),
        ]);

        $message = $decision === 'accept'
            ? 'Thank you — you have accepted this offer. The employer can see your response on their dashboard and will contact you with the next steps.'
            : 'You have declined this offer. Thank you for letting the employer know.';

        return redirect()->to('offer/view/' . $token)->with('success', $message);
    }
}
