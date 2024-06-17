<?php

namespace App\Foundation\Application\Query;

interface PaginationInterface
{
    public function getPage(): int;
    public function getLimit(): int;
}