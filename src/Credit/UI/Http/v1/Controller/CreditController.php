<?php

declare(strict_types=1);

namespace App\Client\UI\Http\Rest\v1\Controller;

use App\Credit\Application\UseCases\Command\IssueCreditCommand;
use App\Credit\Application\UseCases\Query\CheckCreditEligibilityQuery;
use App\Foundation\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use App\Foundation\DTO\General\Status;
use App\Foundation\DTO\General\SuccessStatus;

#[Route(path: '/credit')]
#[Rest\View]
/**
 * @OA\Tag(name="Credit")
 */
class CreditController extends AbstractController
{
    /**
     * Issue Credit.
     *
     * @OA\RequestBody(
     *      required=true,
     *      @Model(type=IssueCreditCommand::class)
     *  )
     * @OA\Response(
     *     response=200,
     *     description="Get Issue Credit",
     *      @Model(type=Status::class)
     * )
     */
    #[Route(path: '', methods: ['POST'])]
    public function issueCreditAction(IssueCreditCommand $command): Status
    {
        $this->handleCommand($command);

        return new SuccessStatus();
    }

    /**
     * Check Credit Eligibility by Client SSN.
     *
     * @OA\Response(
     *     response=200,
     *     description="Check Credit Eligibility by Client SSN"
     * )
     */
    #[Route(path: '/{clientSsn}', methods: ['GET'])]
    public function getByUuidAction(CheckCreditEligibilityQuery $query): bool
    {
        return $this->handleQuery($query);
    }
}
