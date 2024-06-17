<?php

namespace App\Client\Application\UseCases\Query;

use Symfony\Component\Validator\Constraints as Assert;
use App\Foundation\AbstractRequest;

class GetClientQuery extends AbstractRequest
{
    #[Assert\NotBlank]
    protected string $ssn;

    public function __construct(?string $ssn = null)
    {
        $this->ssn = $ssn;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }
}
