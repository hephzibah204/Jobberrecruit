<?php

namespace App\Controllers;

class ContactController extends BaseController
{
    public function sales()
    {
        return redirect()->to(base_url('contact-us'))->with('message', 'For sales inquiries, please use our contact form or call our sales team.');
    }
}
