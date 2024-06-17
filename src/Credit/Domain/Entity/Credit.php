<?php

declare(strict_types=1);

namespace App\Credit\Domain\Entity;

class Credit
{
    private int $id;

    private string $productName;

    private int $term;

    private float $rate;

    private float $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function setTerm(int $term): void
    {
        $this->term = $term;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
