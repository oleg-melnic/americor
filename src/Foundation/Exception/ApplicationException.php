<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class ApplicationException extends BaseException
{
    public function getHttpCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::APPLICATION_ERROR;
    }
}