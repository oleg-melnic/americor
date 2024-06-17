<?php

declare(strict_types=1);

namespace App\Foundation\DTO\General;

use App\Foundation\ErrorCode;

class ErrorStatus extends Status
{
    protected bool $success = false;
    protected ?ErrorCode $errorCode = null;
    protected ?string $errorMessage = null;
    protected array $errorDescription = [];

    /**
     * @return null|ErrorCode
     */
    public function getErrorCode(): ?ErrorCode
    {
        return $this->errorCode;
    }

    /**
     * @param null|ErrorCode $errorCode
     * @return ErrorStatus
     */
    public function setErrorCode(?ErrorCode $errorCode): ErrorStatus
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param null|string $errorMessage
     * @return ErrorStatus
     */
    public function setErrorMessage(?string $errorMessage): ErrorStatus
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrorDescription(): array
    {
        return $this->errorDescription;
    }

    /**
     * @param array $errorDescription
     * @return ErrorStatus
     */
    public function setErrorDescription(array $errorDescription): ErrorStatus
    {
        $this->errorDescription = $errorDescription;
        return $this;
    }
}
