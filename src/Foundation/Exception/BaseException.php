<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Exception;

abstract class BaseException extends Exception
{
    abstract public function getHttpCode(): int;
    abstract public function getErrorCode(): ErrorCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}