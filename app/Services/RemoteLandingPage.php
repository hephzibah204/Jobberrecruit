<?php

namespace App\Services;

final class RemoteLandingPage
{
    public static function prepare($location)
    {
        if (($location->slug ?? '') === 'nigeria-nationwide') {
            $location = clone $location;
            $location->name = 'Nationwide';
            $location->capital = null;
            $location->region = 'Nigeria (All 36 States + FCT)';
            $location->seo_h1 = 'Nationwide Jobs in Nigeria — Verified Vacancies in All States';
            $location->meta_description = 'Browse nationwide jobs in Nigeria open to applicants in any state. Find roles in sales, banking, logistics, and more. Filter by salary and experience.';
            $location->description = 'Find verified job opportunities across Nigeria, with no fixed location. These vacancies are open to candidates in any of the 36 states and the FCT, or involve work across several states. Browse nationwide roles in sales, banking, logistics, FMCG, telecoms, field operations, and customer service with top employers. Many employers hire for multiple cities at once, so you can apply even if the location is not listed. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest nationwide jobs in Nigeria today.';
            return $location;
        }

        if (($location->slug ?? '') !== 'remote') {
            return $location;
        }

        $location = clone $location;
        $location->name = 'Remote';
        $location->capital = null;
        $location->region = 'Work from home';
        $location->seo_h1 = 'Remote Jobs in Nigeria — Work From Home Vacancies';
        $location->meta_description = 'Find verified remote jobs in Nigeria. Browse work-from-home roles in tech, customer support, writing, and sales. Filter by salary and experience.';
        $location->description = 'Find verified remote job opportunities for Nigerian professionals. Browse work-from-home vacancies in software development, customer support, digital marketing, writing, design, sales, and virtual assistance with local and international employers. Remote roles let you work from Lagos, Abuja, Port Harcourt, or anywhere else in Nigeria without relocating or commuting. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest remote jobs in Nigeria today.';

        return $location;
    }
}
