<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class EntityNotFoundException extends BusinessException
{
    public function getHttpCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::ENTITY_NOT_FOUND;
    }
}