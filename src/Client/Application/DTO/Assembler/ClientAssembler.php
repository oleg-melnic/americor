<?php

declare(strict_types=1);

namespace App\Client\Application\DTO\Assembler;

use App\Client\Application\DTO\ClientDTO;
use App\Client\Domain\Entity\Client;
use Exception;

class ClientAssembler
{
    /**
     * @throws Exception
     */
    public function create(Client $item): ClientDTO
    {
        return (new ClientDTO())
            ->setLastName($item->getLastName())
            ->setFirstName($item->getFirstName())
            ->setAge($item->getAge())
            ->setState($item->getState())
            ->setCity($item->getCity())
            ->setZip($item->getZip())
            ->setSsn($item->getSsn())
            ->setFico($item->getFico())
            ->setEmail($item->getEmail())
            ->setPhone($item->getPhone())
            ->setIncome($item->getIncome())
        ;
    }
}
