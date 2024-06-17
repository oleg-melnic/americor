<?php

declare(strict_types=1);

namespace App\Foundation\Application\Query;

use App\Foundation\AbstractRequest;
use App\Foundation\Exception\BadRequestException;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;
use DateInterval;
use DateTime;
use Throwable;

abstract class AbstractGetCollectionQuery extends AbstractRequest implements PaginationInterface
{
    public const PARAMETERS_KEY = '';

    #[Assert\Range(min: 1)]
    #[Assert\Type('numeric')]
    protected int $page = 1;

    #[Assert\Range(min: 1)]
    #[Assert\Type('numeric')]
    protected int $limit = 20;

    #[Assert\Length(
        min: 1,
        minMessage: 'Search condition must be at least {{ limit }} characters long',
    )]
    #[Assert\Type('string')]
    protected ?string $search = null;
    protected array $fieldsToSearch = [];

    /**
     * @Type("array<string, string>")
     */
    protected array $sort = [];
    protected array $allowedFieldsToSorting = [];
    protected array $allowedSortingDirections = [];
    /**
     * @Type("array<string, array>")
     */
    protected array $filter = [];
    protected array $allowedFieldsToFiltering = [];
    protected array $filterOperatorsMap = [];

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getFieldsToSearch(): array
    {
        return $this->fieldsToSearch;
    }

    /**
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     * @return AbstractGetCollectionQuery
     */
    public function setSort(array $sort): AbstractGetCollectionQuery
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedFieldsToSorting(): array
    {
        return $this->allowedFieldsToSorting;
    }

    public function getAllowedSortingDirections(): array
    {
        return $this->allowedSortingDirections;
    }

    public function setAllowedSortingDirections(array $allowedSortingDirections): AbstractGetCollectionQuery
    {
        $this->allowedSortingDirections = $allowedSortingDirections;
        return $this;
    }

    public function setAllowedFieldsToSorting(array $allowedFieldsToSorting): AbstractGetCollectionQuery
    {
        $this->allowedFieldsToSorting = $allowedFieldsToSorting;
        return $this;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }

    public function setFilter(array $filter): AbstractGetCollectionQuery
    {
        $this->filter = $filter;
        return $this;
    }

    public function getAllowedFieldsToFiltering(): array
    {
        return $this->allowedFieldsToFiltering;
    }

    public function setAllowedFieldsToFiltering(array $allowedFieldsToFiltering): AbstractGetCollectionQuery
    {
        $this->allowedFieldsToFiltering = $allowedFieldsToFiltering;
        return $this;
    }

    public function getFilterOperatorsMap(): array
    {
        return $this->filterOperatorsMap;
    }

    public function setFilterOperatorsMap(array $filterOperatorsMap): AbstractGetCollectionQuery
    {
        $this->filterOperatorsMap = $filterOperatorsMap;
        return $this;
    }

    public function loadConfiguration(ParameterBagInterface $parameterBag, TokenStorageInterface $tokenStorage): void
    {
        $this->loadFieldsToSearch($parameterBag);
        $this->loadAllowedFieldsToSorting($parameterBag);
        $this->loadAllowedSortingDirection($parameterBag);
        $this->loadAllowedFieldsToFiltering($parameterBag);
        $this->loadFilterOperatorsMap($parameterBag);
        $this->prepareFilters();
        $this->removeIncorrectSortingConditions();
    }

    protected function loadFieldsToSearch(ParameterBagInterface $parameterBag): void
    {
        $this->fieldsToSearch = (($parameterBag->get('fields_to_search'))[static::PARAMETERS_KEY]) ?? [];
    }

    protected function loadAllowedFieldsToSorting(ParameterBagInterface $parameterBag): void
    {
        $this->allowedFieldsToSorting = (($parameterBag->get('fields_to_sorting'))[static::PARAMETERS_KEY]) ?? [];
    }

    protected function loadAllowedFieldsToFiltering(ParameterBagInterface $parameterBag): void
    {
        $this->allowedFieldsToFiltering = ($parameterBag->get('filtering_parameters')[static::PARAMETERS_KEY]) ?? [];
    }

    protected function loadFilterOperatorsMap(ParameterBagInterface $parameterBag): void
    {
        $this->filterOperatorsMap = $parameterBag->get('filtering_parameters')['filter_operators_map'] ?? [];
    }

    protected function loadAllowedSortingDirection(ParameterBagInterface $parameterBag): void
    {
        $this->allowedSortingDirections = $parameterBag->get('allowed_directions') ?? [];
    }

    protected function prepareFilters(): void
    {
        $result = [];
        $filters = $this->removeIncorrectFilterConditions();
        foreach ($filters as $fieldName => $condition) {
            $fieldType = $this->allowedFieldsToFiltering[$fieldName];
            foreach ($condition as $key => $value) {
                $operator = $this->filterOperatorsMap[$key];
                $result[$fieldName][] = $this->makeFilterCondition($operator, $value, $fieldType);
            }
        }

        $this->filter = $result;
    }

    protected function makeFilterCondition(
        string $operator,
        $conditionValue,
        string $fieldType
    ): array {
        if (is_array($conditionValue)) {
            foreach ($conditionValue as $key => $item) {
                $conditionValue[$key] = $this->convertFilterValueToProperType((string) $item, $fieldType);
            }
        } else {
            $conditionValue = $this
                ->convertFilterValueToProperType((string) $conditionValue, $fieldType);
        }

        $isDateType = $fieldType === 'date';

        if ($isDateType && $conditionValue->format('His') === '000000' && $operator === '=') {
            $result[] = ['>=' => $conditionValue];
            $secondDate = (clone($conditionValue))->add(new DateInterval('P1D'));
            $result[] = ['<' => $secondDate];
        } else {
            $result = [$operator => $conditionValue];
        }

        return $result;
    }

    protected function removeIncorrectFilterConditions(): array
    {
        $fields = array_keys($this->allowedFieldsToFiltering);
        $allowedFilterOperators = array_keys($this->filterOperatorsMap);
        return array_filter(
            $this->filter,
            function ($value, $key) use ($fields, $allowedFilterOperators) {
                if (!in_array($key, $fields)) {
                    return false;
                }
                if (!is_array($value)) {
                    return true;
                }
                return !array_diff(array_keys($value), $allowedFilterOperators);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @throws BadRequestException
     */
    protected function convertFilterValueToProperType(string $value, string $fieldType)
    {
        if ($fieldType === 'int') {
            $value = (int)$value;
        }
        if ($fieldType === 'date') {
            try {
                $value = new DateTime($value);
            } catch (Throwable $e) {
                throw new BadRequestException('Wrong dateTime format');
            }
        }
        if ($fieldType === 'float') {
            $value = (float) $value;
        }

        return $value;
    }

    protected function removeIncorrectSortingConditions(): void
    {
       $this->sort = array_filter(
            $this->sort,
            function ($value, $key) {
                return (in_array($key, $this->allowedFieldsToSorting)
                    && in_array($value, $this->allowedSortingDirections)
                );
            },
            ARRAY_FILTER_USE_BOTH
        );
    }
}
