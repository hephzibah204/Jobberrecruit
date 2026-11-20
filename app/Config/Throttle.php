<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Throttle extends BaseConfig
{
    public array $rules = [
        'contact' => [
            'capacity'  => 5,      // max attempts
            'seconds'   => 300,    // per 5 minutes
        ],
    ];
}
