<?php

namespace App\Credit\Infrasrtucture\Service;

use App\Client\Domain\Entity\Client;

class CreditService
{
    public function checkEligibility(Client $client): bool
    {
        // Check client eligibility
        return true;
    }
}