<?php

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class BusinessException extends BaseException
{
    private ErrorCode $errorCode;

    public function __construct(string $message, ErrorCode $errorCode = ErrorCode::BUSINESS_ERROR)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message);
    }

    public function getHttpCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
}