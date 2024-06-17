<?php

use App\Client\Domain\Entity\Client;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Foundation\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{
    const ENTITY_ALIAS = 'client';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Client::class);
    }

    public function getClientBySsn(string $ssn): ?Client
    {
        return $this->findOneBy(['ssn' => $ssn]);
    }

    public function create(Client $client): void
    {
        $this->_em->persist($client);
        $this->_em->flush();
    }
}
