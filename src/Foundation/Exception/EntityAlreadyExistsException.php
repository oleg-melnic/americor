<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class EntityAlreadyExistsException extends BusinessException
{
    public function getHttpCode(): int
    {
        return Response::HTTP_CONFLICT;
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::ENTITY_ALREADY_EXISTS;
    }
}