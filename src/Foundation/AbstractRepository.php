<?php

declare(strict_types=1);

namespace App\Foundation;

use App\Foundation\Application\Query\AbstractGetCollectionQuery;
use App\Foundation\Application\Query\GetEntityByIdQueryInterface;
use App\Foundation\Doctrine\DoctrineQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Closure;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public const ENTITY_ALIAS = '';

    public function getTableAlias(): string
    {
        return static::ENTITY_ALIAS;
    }

    protected ?GetEntityByIdQueryInterface $queryRequest = null;

    /**
     * @return GetEntityByIdQueryInterface|null
     */
    public function getQueryRequest(): ?GetEntityByIdQueryInterface
    {
        return $this->queryRequest;
    }

    /**
     * @param GetEntityByIdQueryInterface|null $queryRequest
     */
    public function setQueryRequest(?GetEntityByIdQueryInterface $queryRequest): self
    {
        $this->queryRequest = $queryRequest;

        return $this;
    }

    /**
     * @param $alias
     * @param $indexBy
     * @return DoctrineQueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        return (new DoctrineQueryBuilder($this->_em))->select($alias)->from($this->_entityName, $alias, $indexBy);
    }

    public function transaction(Closure $body): mixed
    {
        return $this->getEntityManager()->wrapInTransaction($body);
    }

    protected function applySearchFilterAndSortingConditions(
        DoctrineQueryBuilder $qb,
        AbstractGetCollectionQuery $query,
        bool $shouldApplyACLByOrganization = true
    ): void
    {
        $this->applyFilters($qb, $query);
        $this->applySearchCondition($qb, $query);
        $this->applySortingConditions($qb, $query);
    }

    protected function applySearchCondition(DoctrineQueryBuilder $qb, AbstractGetCollectionQuery $query): void
    {
        if (!$query->getSearch()  || !$query->getFieldsToSearch()) {
            return;
        }

        $expr = $qb->expr();

        $conditions = [];

        foreach ($query->getFieldsToSearch() as $field) {
            $field = strpos($field, '.') ? $field : $this->getTableAlias() . '.' . $field;
            $conditions[] = $expr->like($field, ':query');
        }

        $qb
            ->andWhere($expr->orX(...$conditions))
            ->setParameter('query', "{$query->getSearch()}%");
    }

    protected function applySortingConditions(DoctrineQueryBuilder $qb, AbstractGetCollectionQuery $query): void
    {
        foreach ($query->getSort() as $field => $direction) {
            $field = strpos($field, '.') ? $field : $this->getTableAlias() . '.' . $field;
            $qb->addOrderBy($field, $direction);
        }
    }

    protected function applyFilters(DoctrineQueryBuilder $qb, AbstractGetCollectionQuery $query)
    {
        foreach ($query->getFilter() as $fieldName => $conditions) {
            $this->applyFilterConditions($qb, $fieldName, $conditions);
        }
    }

    protected function applyFilterConditions(DoctrineQueryBuilder $qb, string $fieldName, array $conditions)
    {
        $iterator = 0;
        foreach ($conditions as $condition) {
            $param = str_replace('.', '', $fieldName);
            $fieldName = strpos($fieldName, '.') ? $fieldName : $this->getTableAlias() . '.' . $fieldName;
            $operator = key($condition);

            if (is_string($operator) && strtolower($operator) === 'in') {
                $qb
                    ->andWhere("{$fieldName} IN ( :{$param}{$iterator} )")
                    ->setParameter("{$param}{$iterator}", $condition[$operator]);
                $iterator++;
                continue;
            }

            if (!is_string($operator)) {
                foreach ($condition as $item) {
                    $operator = key($item);
                    if (array_values($item)[0] instanceof \DateTime) {
                        $item[$operator] = array_values($item)[0]->format('Y-m-d H:i:s');
                    }
                    $qb
                        ->andWhere("{$fieldName} {$operator} :{$param}{$iterator}")
                        ->setParameter("{$param}{$iterator}", array_values($item)[0]);
                    $iterator++;
                }
            } else {
                if (array_values($condition)[0] instanceof \DateTime) {
                    $condition[$operator] = array_values($condition)[0]->format('Y-m-d H:i:s');
                }
                $qb
                    ->andWhere("{$fieldName} {$operator} :{$param}{$iterator}")
                    ->setParameter("{$param}{$iterator}", $condition[$operator]);
            }
            $iterator++;
        }
    }

    public function getPaginatedItems(AbstractGetCollectionQuery $query, $onlyActive = true): array
    {
        $offset = $this->getOffset($query->getPage(), $query->getLimit());
        $queryBuilder = $this
            ->createQueryBuilder($this->getTableAlias())
            ->setMaxResults($query->getLimit())
            ->setFirstResult($offset);

        $this->applySearchFilterAndSortingConditions($queryBuilder, $query);

        return $queryBuilder->getQuery()
            ->getResult();
    }

    public function getCountItems(AbstractGetCollectionQuery $query, $onlyActive = true): int
    {
        $queryBuilder = $this
            ->createQueryBuilder($this->getTableAlias())
            ->select('COUNT(' . $this->getTableAlias() . ')');

        $this->applySearchFilterAndSortingConditions($queryBuilder, $query);

        return (int) $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();
    }
}
