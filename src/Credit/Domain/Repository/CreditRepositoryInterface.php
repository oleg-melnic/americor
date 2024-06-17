<?php

declare(strict_types=1);

namespace App\Credit\Domain\Repository;

use App\Credit\Domain\Entity\Credit;

interface CreditRepositoryInterface
{
    public function getById(int $id): null|Credit;
}
