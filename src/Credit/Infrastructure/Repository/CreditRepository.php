<?php

namespace App\Credit\Infrasrtucture\Repository;

use App\Credit\Domain\Entity\Credit;
use App\Credit\Domain\Repository\CreditRepositoryInterface;
use App\Foundation\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class CreditRepository extends AbstractRepository implements CreditRepositoryInterface
{
    const ENTITY_ALIAS = 'client';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Credit::class);
    }

    public function getById(int $id): ?Credit
    {
        return $this->find($id);
    }
}
