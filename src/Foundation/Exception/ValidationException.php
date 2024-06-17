<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use App\Foundation\ErrorCode;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/*
 * Used in request validators
 */
class ValidationException extends BaseException
{
    private ConstraintViolationListInterface $constraintViolationList;

    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $this->constraintViolationList = $constraintViolationList;
        parent::__construct('');
    }

    public function getHttpCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::INVALID_PARAMETERS;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}