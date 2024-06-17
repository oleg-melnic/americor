<?php

namespace App\Client\Application\UseCases\Handler;

use App\Client\Application\DTO\Assembler\ClientAssembler;
use App\Client\Application\DTO\ClientDTO;
use App\Client\Application\UseCases\Query\GetClientQuery;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Foundation\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetClientHandler implements MessageHandlerInterface
{
    public function __construct(
        protected ClientRepositoryInterface $repository,
        protected ClientAssembler $itemAssembler,
    ) {
    }

    public function __invoke(GetClientQuery $query): ClientDTO
    {
        $entity = $this->repository->getClientBySsn($query->getSsn());

        if (!$entity) {
            throw new EntityNotFoundException("Client with SSN {$query->getSsn()} not found");
        }

        return $this->itemAssembler->create($entity);
    }
}
