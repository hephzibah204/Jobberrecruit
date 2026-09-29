<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            [1, 'Abia', 'abia-state', 'Umuahia', 'South East'],
            [2, 'Adamawa', 'adamawa-state', 'Yola', 'North East'],
            [3, 'Akwa Ibom', 'akwa-ibom-state', 'Uyo', 'South South'],
            [4, 'Anambra', 'anambra-state', 'Awka', 'South East'],
            [5, 'Bauchi', 'bauchi-state', 'Bauchi', 'North East'],
            [6, 'Bayelsa', 'bayelsa-state', 'Yenagoa', 'South South'],
            [7, 'Benue', 'benue-state', 'Makurdi', 'North Central'],
            [8, 'Borno', 'borno-state', 'Maiduguri', 'North East'],
            [9, 'Cross River', 'cross-river-state', 'Calabar', 'South South'],
            [10, 'Delta', 'delta-state', 'Asaba', 'South South'],
            [11, 'Ebonyi', 'ebonyi-state', 'Abakaliki', 'South East'],
            [12, 'Edo', 'edo-state', 'Benin City', 'South South'],
            [13, 'Ekiti', 'ekiti-state', 'Ado-Ekiti', 'South West'],
            [14, 'Enugu', 'enugu-state', 'Enugu', 'South East'],
            [15, 'FCT', 'fct-abuja', 'Abuja', 'North Central'],
            [16, 'Gombe', 'gombe-state', 'Gombe', 'North East'],
            [17, 'Imo', 'imo-state', 'Owerri', 'South East'],
            [18, 'Jigawa', 'jigawa-state', 'Dutse', 'North West'],
            [19, 'Kaduna', 'kaduna-state', 'Kaduna', 'North West'],
            [20, 'Kano', 'kano-state', 'Kano', 'North West'],
            [21, 'Katsina', 'katsina-state', 'Katsina', 'North West'],
            [22, 'Kebbi', 'kebbi-state', 'Birnin Kebbi', 'North West'],
            [23, 'Kogi', 'kogi-state', 'Lokoja', 'North Central'],
            [24, 'Kwara', 'kwara-state', 'Ilorin', 'North Central'],
            [25, 'Lagos', 'lagos-state', 'Ikeja', 'South West'],
            [26, 'Nasarawa', 'nasarawa-state', 'Lafia', 'North Central'],
            [27, 'Niger', 'niger-state', 'Minna', 'North Central'],
            [28, 'Ogun', 'ogun-state', 'Abeokuta', 'South West'],
            [29, 'Ondo', 'ondo-state', 'Akure', 'South West'],
            [30, 'Osun', 'osun-state', 'Osogbo', 'South West'],
            [31, 'Oyo', 'oyo-state', 'Ibadan', 'South West'],
            [32, 'Plateau', 'plateau-state', 'Jos', 'North Central'],
            [33, 'Rivers', 'rivers-state', 'Port Harcourt', 'South South'],
            [34, 'Sokoto', 'sokoto-state', 'Sokoto', 'North West'],
            [35, 'Taraba', 'taraba-state', 'Jalingo', 'North East'],
            [36, 'Yobe', 'yobe-state', 'Damaturu', 'North East'],
            [37, 'Zamfara', 'zamfara-state', 'Gusau', 'North West'],
        ];

        $countryId = 1;

        foreach ($states as $state) {
            $this->db->table('states')->insert([
                'country_id' => $countryId,
                'name'       => $state[1],
                'slug'       => $state[2],
                'capital'    => $state[3],
                'region'     => $state[4],
                'is_active'  => 1,
            ]);
        }
    }
}
