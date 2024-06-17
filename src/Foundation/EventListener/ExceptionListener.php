<?php

declare(strict_types=1);

namespace App\Foundation\EventListener;

use App\Foundation\DTO\General\ErrorStatus;
use App\Foundation\Exception\BaseException;
use App\Foundation\Exception\BadRequestException;
use App\Foundation\Exception\EntityAlreadyExistsException;
use App\Foundation\Exception\ValidationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Throwable;

class ExceptionListener
{
    public function __construct(
        private SerializerInterface $serializer
    ) { }

    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        $status = new ErrorStatus();

        if ($throwable instanceof HandlerFailedException) {
            $throwable = $throwable->getPrevious();
        }

        $exception = $this->getApplicationException($throwable);

        if ($exception instanceof BaseException) {
            $status->setErrorCode($exception->getErrorCode())
                ->setErrorMessage($exception->getMessage() ?: $exception->getErrorCode()->getMessage());

            if ($exception instanceof ValidationException) {
                $fields = [];

                /** @var ConstraintViolation $violation */
                foreach ($exception->getConstraintViolationList() as $violation) {
                    $fields[$violation->getPropertyPath()] = $violation->getMessage();
                }

                $status->setErrorDescription([
                    'fields' => $fields
                ]);
            }

            $httpCode = $exception->getHttpCode();
        } else {
            $status->setErrorMessage($exception->getMessage())
                ->setErrorDescription(['trace' => $exception->getTraceAsString()]);

            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($exception instanceof HttpException) {
                $httpCode = $exception->getStatusCode();
            }
        }

        $response = new Response($this->serializer->serialize($status, 'json'), $httpCode);

        $event->setResponse($response);
    }

    private function getApplicationException(Throwable $throwable): Throwable
    {
        if ($throwable instanceof BaseException) {
            return $throwable;
        }

        if ($throwable instanceof UniqueConstraintViolationException) {
            return new EntityAlreadyExistsException('Entity with this id already exists. Please use another id.');
        }

        if ($throwable instanceof RequestExceptionInterface) {
            $httpCode = $throwable->getCode() ?: Response::HTTP_BAD_REQUEST;

            return new BadRequestException($throwable->getMessage(), $httpCode);
        }

        return $throwable;
    }
}
