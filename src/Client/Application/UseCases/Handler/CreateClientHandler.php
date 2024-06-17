<?php

namespace App\Client\Application\UseCases\Handler;

use App\Client\Application\UseCases\Command\CreateClientCommand;
use App\Client\Domain\Entity\Client;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Foundation\Exception\EntityAlreadyExistsException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateClientHandler implements MessageHandlerInterface
{
    public function __construct(
        protected ClientRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CreateClientCommand $command): void
    {
        if ($this->repository->getClientBySsn($command->getSsn())) {
            throw new EntityAlreadyExistsException('Client already exists.');
        }

        $client = (new Client())
            ->setLastName($command->getLastName())
            ->setFirstName($command->getFirstName())
            ->setAge($command->getAge())
            ->setState($command->getState())
            ->setCity($command->getCity())
            ->setZip($command->getZip())
            ->setSsn($command->getSsn())
            ->setFico($command->getFico())
            ->setEmail($command->getEmail())
            ->setPhone($command->getPhone())
            ->setIncome($command->getIncome());

        $this->repository->create($client);
    }
}
