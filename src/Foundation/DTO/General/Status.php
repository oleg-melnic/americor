<?php

declare(strict_types=1);

namespace App\Foundation\DTO\General;

class Status
{
    protected bool $success;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     * @return Status
     */
    public function setSuccess(bool $success): Status
    {
        $this->success = $success;
        return $this;
    }
}