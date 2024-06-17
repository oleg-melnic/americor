<?php

namespace App\Client\Application\UseCases\Command;

use App\Foundation\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateClientCommand extends AbstractRequest
{
    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Type('string')]
    protected string $lastName;

    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Type('string')]
    protected string $firstName;

    #[Assert\NotBlank]
    protected int $age;

    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Type('string')]
    protected string $state;

    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Type('string')]
    protected string $city;

    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Type('string')]
    protected string $zip;

    #[Assert\NotBlank]
    protected string $ssn;

    protected int $fico;

    #[Assert\NotBlank]
    #[Assert\Email]
    protected string $email;

    #[Assert\NotBlank]
    protected string $phone;

    #[Assert\NotBlank]
    protected int $income;

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }

    public function setSsn(string $ssn): void
    {
        $this->ssn = $ssn;
    }

    public function getFico(): int
    {
        return $this->fico;
    }

    public function setFico(int $fico): void
    {
        $this->fico = $fico;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getIncome(): int
    {
        return $this->income;
    }

    public function setIncome(int $income): void
    {
        $this->income = $income;
    }
}
