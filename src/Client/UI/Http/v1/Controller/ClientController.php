<?php

declare(strict_types=1);

namespace App\Client\UI\Http\Rest\v1\Controller;

use App\Client\Application\DTO\ClientDTO;
use App\Client\Application\UseCases\Query\GetClientQuery;
use App\Foundation\AbstractController;
use App\Foundation\DTO\General\SuccessStatus;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use App\Client\Application\UseCases\Command\CreateClientCommand;
use App\Foundation\DTO\General\Status;

#[Route(path: '/credit')]
#[Rest\View]
/**
 * @OA\Tag(name="Credit")
 */
class ClientController extends AbstractController
{
    /**
     * Create new client.
     *
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=CreateClientCommand::class)
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Create new client",
     *     @Model(type=Status::class)
     * )
     */
    #[Route(path: '', methods: ['POST'])]
    public function add(CreateClientCommand $command): Status
    {
        $this->handleCommand($command);

        return new SuccessStatus();
    }

    /**
     * Get client by SSN.
     *
     * @OA\Response(
     *     response=200,
     *     description="Get client by SSN",
     *     @Model(type=ClientDTO::class)
     * )
     */
    #[Route(path: '/{ssn}', methods: ['GET'])]
    public function getByIdAction(GetClientQuery $query): ClientDTO
    {
        return $this->handleQuery($query);
    }
}
