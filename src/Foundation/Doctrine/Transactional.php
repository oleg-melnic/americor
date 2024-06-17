<?php

declare(strict_types=1);

namespace App\Foundation\Doctrine;
use Closure;

interface Transactional
{
    public function transaction(Closure $body): mixed;
}