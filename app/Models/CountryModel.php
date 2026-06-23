<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $table      = 'countries';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Country::class;
    protected $allowedFields = ['name', 'iso_code', 'dial_code', 'currency', 'currency_code', 'is_active'];

    public function getCountriesWithStats()
    {
        return $this->db->table('countries c')
            ->select('c.*, COUNT(s.id) as state_count')
            ->join('states s', 's.country_id = c.id', 'left')
            ->groupBy('c.id')
            ->orderBy('c.name', 'ASC')
            ->get()
            ->getResult();
    }
}
