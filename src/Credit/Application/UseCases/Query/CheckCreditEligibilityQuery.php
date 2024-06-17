<?php

namespace App\Credit\Application\UseCases\Query;

use App\Foundation\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CheckCreditEligibilityQuery extends AbstractRequest
{
    #[Assert\NotBlank]
    protected string $clientSsn;

    public function __construct(?string $clientSsn = null)
    {
        $this->clientSsn = $clientSsn;
    }

    public function getClientSsn(): string
    {
        return $this->clientSsn;
    }
}
