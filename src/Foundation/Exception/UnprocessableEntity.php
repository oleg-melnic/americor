<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class UnprocessableEntity extends BaseException
{
    public function getHttpCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::APPLICATION_ERROR;
    }
}