<?php

declare(strict_types=1);

namespace App\Foundation\DTO\General;

class SuccessStatus extends Status
{
    protected bool $success = true;
}