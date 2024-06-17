<?php

namespace App\Foundation;

class PaginatedResult
{
    private array $items;
    private int $limitPerPage;
    private int $currentPage;
    private int $totalCount;

    /**
     * PaginatedResult constructor.
     * @param array $items
     * @param int $limitPerPage
     * @param int $currentPage
     * @param int $totalCount
     */
    public function __construct(array $items, int $limitPerPage, int $currentPage, int $totalCount)
    {
        $this->items = $items;
        $this->limitPerPage = $limitPerPage;
        $this->currentPage = $currentPage;
        $this->totalCount = $totalCount;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getLimitPerPage(): int
    {
        return $this->limitPerPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getTotalPagesCount(): int
    {
        return ceil($this->totalCount / $this->limitPerPage);
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}