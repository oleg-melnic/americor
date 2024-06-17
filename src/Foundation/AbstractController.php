<?php

declare(strict_types=1);

namespace App\Foundation;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Foundation\Application\Query\GetEntityByIdQueryInterface as QueryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

abstract class AbstractController extends AbstractFOSRestController
{
    public function __construct(
        protected MessageBusInterface $messageBus,
        protected ParameterBagInterface $parameterBag
    ) {
    }

    protected function handleQuery(QueryInterface|AbstractRequest $query): mixed
    {
        $envelope = $this->messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }

    protected function handleCommand(QueryInterface|AbstractRequest $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
