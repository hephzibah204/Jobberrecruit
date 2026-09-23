<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JobCreditWalletEntity extends Entity
{
    public function hasCredits(): bool
    {
        return $this->credits > 0;
    }
}
