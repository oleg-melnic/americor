<?php

namespace App\Credit\Application\UseCases\Command;

use App\Foundation\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

class IssueCreditCommand extends AbstractRequest
{
    #[Assert\NotBlank]
    protected string $clientSsn;

    #[Assert\NotBlank]
    protected int $creditId;

    public function getCreditId(): int
    {
        return $this->creditId;
    }

    public function setCreditId(int $creditId): void
    {
        $this->creditId = $creditId;
    }

    public function getClientSsn(): string
    {
        return $this->clientSsn;
    }

    public function setClientSsn(string $clientSsn): void
    {
        $this->clientSsn = $clientSsn;
    }
}
