<?php

declare(strict_types=1);

namespace App\Foundation\ParamConverter;

use App\Foundation\Application\Query\GetEntityByIdQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

final class GetEntityByIdRequestParamConverter extends RequestParamConverter
{
    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() &&
            (new \ReflectionClass($configuration->getClass()))->implementsInterface(GetEntityByIdQueryInterface::class);
    }

    protected function parseRequestBody(Request $request): array
    {
        $body = parent::parseRequestBody($request);

        $requestClosure = function() use ($request, $body) {
            $params = array_merge($body, [
                'id' => $request->get('id')
            ]);

            $this->content = json_encode($params, JSON_THROW_ON_ERROR);
            return $this;
        };

        $request->headers->set('Content-Type', 'application/json');
        $request = $requestClosure->call($request);

        return $request->toArray();
    }
}
