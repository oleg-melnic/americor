<?php

namespace App\Foundation\Doctrine;

use App\Foundation\Application\Query\PaginationInterface;
use App\Foundation\PaginatedResult;
use Doctrine\ORM\QueryBuilder;

class DoctrineQueryBuilder extends QueryBuilder
{
    public function getPaginatedResult(PaginationInterface $pagination): PaginatedResult
    {
        $totalCount = (clone $this)
            ->select('COUNT(DISTINCT ' . $this->getRootAliases()[0] . ')')
            ->resetDQLPart('orderBy')
            ->resetDQLPart('groupBy')
            ->getQuery()
            ->getSingleScalarResult();

        $this->setMaxResults($limit = $pagination->getLimit())
            ->setFirstResult($limit * $pagination->getPage() - $limit);

        $items = $this->getQuery()->getResult();

        return new PaginatedResult(
            $items,
            $pagination->getLimit(),
            $pagination->getPage(),
            $totalCount
        );
    }
}