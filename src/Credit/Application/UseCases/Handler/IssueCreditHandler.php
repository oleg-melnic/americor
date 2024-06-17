<?php

namespace App\Credit\Application\UseCases\Handler;

use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Credit\Application\UseCases\Command\IssueCreditCommand;
use App\Credit\Domain\Repository\CreditRepositoryInterface;
use App\Credit\Infrasrtucture\Service\CreditService;
use App\Foundation\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class IssueCreditHandler implements MessageHandlerInterface
{
    public function __construct(
        protected ClientRepositoryInterface $clientRepository,
        protected CreditRepositoryInterface $creditRepository,
        protected CreditService $creditService
    ) {
    }

    public function __invoke(IssueCreditCommand $command): void
    {
        $client = $this->clientRepository->getClientBySsn($command->getClientSsn());
        if (!$client) {
            throw new EntityNotFoundException("Client with SSN {$command->getClientSsn()} not found");
        }

        $credit = $this->creditRepository->getById($command->getCreditId());
        if (!$credit) {
            throw new EntityNotFoundException("Credit with ID {$command->getCreditId()} not found");
        }

        $this->creditService->issueCredit($client, $credit);
    }
}
