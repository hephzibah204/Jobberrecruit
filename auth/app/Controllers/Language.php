<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Language extends Controller
{
    public function switch($locale = null)
    {
        $supported = ['en', 'yo', 'ig', 'ha'];

        if (!in_array($locale, $supported)) {
            $locale = 'en';
        }

        // Save to session
        session()->set('lang', $locale);

        // Redirect back
        return redirect()->back();
    }
}
