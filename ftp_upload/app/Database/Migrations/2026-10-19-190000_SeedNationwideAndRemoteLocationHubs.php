<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedNationwideAndRemoteLocationHubs extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Ensure country exists to prevent foreign key violation in fresh test databases
        $country = $db->table('countries')->where('id', 1)->get()->getRow();
        if (!$country) {
            $anyCountry = $db->table('countries')->get()->getRow();
            if ($anyCountry) {
                $countryId = (int) $anyCountry->id;
            } else {
                $db->table('countries')->insert([
                    'id'         => 1,
                    'name'       => 'Nigeria',
                    'iso_code'   => 'NG',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $countryId = 1;
            }
        } else {
            $countryId = 1;
        }

        // 38. Nationwide (Nigeria)
        $nationwide = $db->table('states')->where('slug', 'nigeria-nationwide')->get()->getRow();
        $nationwideData = [
            'country_id'       => $countryId,
            'name'             => 'Nationwide',
            'slug'             => 'nigeria-nationwide',
            'capital'          => 'All States',
            'region'           => 'Nigeria (All 36 States + FCT)',
            'is_active'        => 1,
            'seo_h1'           => 'Nationwide Jobs in Nigeria — Verified Vacancies in All States',
            'meta_description' => 'Browse nationwide jobs in Nigeria open to applicants in any state. Find roles in sales, banking, logistics, and more. Filter by salary and experience.',
            'description'      => "Find verified job opportunities across Nigeria, with no fixed location. These vacancies are open to candidates in any of the 36 states and the FCT, or involve work across several states. Browse nationwide roles in sales, banking, logistics, FMCG, telecoms, field operations, and customer service with top employers. Many employers hire for multiple cities at once, so you can apply even if the location is not listed. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest nationwide jobs in Nigeria today.",
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        if ($nationwide) {
            $db->table('states')->where('id', $nationwide->id)->update($nationwideData);
        } else {
            $nationwideData['created_at'] = date('Y-m-d H:i:s');
            $db->table('states')->insert($nationwideData);
        }

        // 39. Remote Jobs
        $remote = $db->table('states')->where('slug', 'remote')->get()->getRow();
        $remoteData = [
            'country_id'       => $countryId,
            'name'             => 'Remote',
            'slug'             => 'remote',
            'capital'          => null,
            'region'           => 'Work From Home',
            'is_active'        => 1,
            'seo_h1'           => 'Remote Jobs in Nigeria — Work From Home Vacancies',
            'meta_description' => 'Find verified remote jobs in Nigeria. Browse work-from-home roles in tech, customer support, writing, and sales. Filter by salary and experience.',
            'description'      => "Find verified remote job opportunities for Nigerian professionals. Browse work-from-home vacancies in software development, customer support, digital marketing, writing, design, sales, and virtual assistance with local and international employers. Remote roles let you work from Lagos, Abuja, Port Harcourt, or anywhere else in Nigeria without relocating or commuting. Whether you are looking for entry-level positions, mid-career roles, or senior management opportunities, browse and apply to the latest remote jobs in Nigeria today.",
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        if ($remote) {
            $db->table('states')->where('id', $remote->id)->update($remoteData);
        } else {
            $remoteData['created_at'] = date('Y-m-d H:i:s');
            $db->table('states')->insert($remoteData);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->table('states')->whereIn('slug', ['nigeria-nationwide', 'remote'])->delete();
    }
}
