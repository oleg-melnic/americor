<?php

namespace App\Credit\Application\UseCases\Handler;

use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Credit\Application\UseCases\Query\CheckCreditEligibilityQuery;
use App\Credit\Infrasrtucture\Service\CreditService;
use App\Foundation\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CheckCreditEligibilityHandler implements MessageHandlerInterface
{
    public function __construct(
        protected ClientRepositoryInterface $clientRepository,
        protected CreditService $creditService
    ) {
    }

    public function __invoke(CheckCreditEligibilityQuery $query): bool
    {
        $client = $this->clientRepository->getClientBySsn($query->getClientSsn());
        if (!$client) {
            throw new EntityNotFoundException("Client with SSN {$query->getClientSsn()} not found");
        }

        return $this->creditService->checkEligibility($client);
    }
}
