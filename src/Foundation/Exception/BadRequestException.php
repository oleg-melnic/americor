<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends BaseException
{
    private ErrorCode $errorCode;
    private int $httpCode;

    public function __construct(
        string $message,
        int $httpCode = Response::HTTP_BAD_REQUEST,
        ErrorCode $errorCode = ErrorCode::BAD_REQUEST
    )
    {
        $this->httpCode = $httpCode;
        $this->errorCode = $errorCode;
        parent::__construct($message);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
}