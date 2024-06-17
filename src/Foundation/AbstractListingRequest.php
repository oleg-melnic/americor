<?php

declare(strict_types=1);

namespace App\Foundation;

use App\Foundation\Application\Query\PaginationInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractListingRequest extends AbstractRequest implements PaginationInterface
{
    #[Assert\Range(min: 1)]
    protected int $page = 1;

    #[Assert\Range(min: 1)]
    protected int $limit = 36;

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}