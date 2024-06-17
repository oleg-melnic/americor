<?php

namespace App\Foundation\EventListener;

use App\Foundation\DTO\General\ErrorStatus;
use App\Foundation\Exception\BaseException;
use App\Foundation\Exception\ValidationException;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class SerializerPostDeserializeEventListener  implements EventSubscriberInterface
{
    public function __construct(
        protected ParameterBagInterface $parameterBag,
        protected TokenStorageInterface $tokenStorage
    ) { }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
                'priority' => 0,
            ],
        ];
    }

    public function onPostDeserialize(ObjectEvent $event)
    {
        if (!is_callable([$event->getObject(), 'loadConfiguration'])) {
            return;
        }

        $event->getObject()->loadConfiguration($this->parameterBag, $this->tokenStorage);
    }
}