<?php

declare(strict_types=1);

namespace App\Foundation\ParamConverter;

use App\Foundation\AbstractRequest;
use App\Foundation\Exception\ValidationException;
use FOS\RestBundle\Request\RequestBodyParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\PropertyMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestParamConverter implements ParamConverterInterface
{
    protected string $content;

    public function __construct(
        private RequestBodyParamConverter $requestBodyParamConverter,
        private ValidatorInterface $validator
    ) { }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $this->parseRequestBody($request);
        $this->requestBodyParamConverter->apply($request, $configuration);
        $this->throwExceptionOnValidationErrors($request->attributes->get('validationErrors'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() &&
            (new \ReflectionClass($configuration->getClass()))->isSubclassOf(AbstractRequest::class);
    }

    private function validateRawPayload(Request $request, ParamConverter $configuration): void
    {
        $body = $this->parseRequestBody($request);

        $validationErrors = new ConstraintViolationList();

        /** @var ClassMetadata $classValidatorMetadata */
        $classValidatorMetadata = $this->validator->getMetadataFor($configuration->getClass());
        foreach ($classValidatorMetadata->getConstrainedProperties() as $property) {

            /** @var PropertyMetadataInterface[] $propertyMetadata */
            $propertyMetadata = $classValidatorMetadata->getPropertyMetadata($property);
            if (!$propertyMetadata) {
                continue;
            }
            $propertyErrors = $this->validator->startContext()->atPath($property)->validate(
                $body[$property] ?? null,
                \current($propertyMetadata)->getConstraints()
            )->getViolations();

            $validationErrors->addAll($propertyErrors);
        }

        $this->throwExceptionOnValidationErrors($validationErrors);
    }

    private function throwExceptionOnValidationErrors(ConstraintViolationListInterface $validationErrors): void
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }
    }

    protected function parseRequestBody(Request $request): array
    {
        if ($request->getMethod() === Request::METHOD_GET) {
            $request->headers->set('Content-Type', 'application/json');

            if ($queryString = $request->getQueryString()) {
                parse_str($queryString, $params);

            } else {
                $params = [];
            }

            $requestClosure = function() use ($params) {
                $this->content = json_encode($params, JSON_THROW_ON_ERROR);
                return $this;
            };

            $request = $requestClosure->call($request);
        }

        return $request->getContent() ? $request->toArray() : [];
    }
}