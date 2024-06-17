<?php

declare(strict_types=1);

namespace App\Client\Domain\Repository;

use App\Client\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function getClientBySsn(string $ssn): null|Client;

    public function create(Client $client): void;
}
