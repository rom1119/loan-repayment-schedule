<?php

declare(strict_types=1);

namespace App\Controller;

use App\LoanCalculatorPersister\DatabaseCalculatorPersister;
use App\Entity\LoanCalculation;
use App\Domain\CalculatorFacade;
use App\Domain\Exception\CalculatorException;
use App\Domain\Model\CreditCalculationRequest;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LoanController extends AbstractFOSRestController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CalculatorFacade $calculatorFacade,
        private DatabaseCalculatorPersister $persister,
    ) {
    }

    #[Route('/api/calculate', methods:'POST')]
    #[IsGranted('PUBLIC_ACCESS')]
    public function postScheduleCalculationAction(
        #[MapRequestPayload(
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )]CreditCalculationRequest $loanProposal
        
        
    ) {
        try {
            $result = $this->calculatorFacade->makeCalculation($loanProposal, $this->persister);

        } catch (CalculatorException $e) {
            $view = $this->view([
                'error' => $e->getMessage()
            ], 400);
            return $this->handleView($view);
        }

        $view = $this->view($result, 200);
        return $this->handleView($view);
    }

    #[Route('/api/calculation/exclude/{id}', name: 'exclude_calculation', methods: ['DELETE'])]
    public function excludeCalculation(LoanCalculation $calculation): JsonResponse
    {
        $this->calculatorFacade->excludeCalculation($this->persister, $calculation);

        return new JsonResponse(['message' => 'Calculation excluded']);
    }


    #[Route('/api/calculations', name: 'list_calculations', methods: ['GET'])]
    public function getAllSchedulesAction(
        Request $request,
    ) {
        $filter = $request->query->get('filter', 'all');
        $data = $this->calculatorFacade->getAllSchedules($this->persister, $filter);

        $view = $this->view($data, 200);

        return $this->handleView($view);
    }

}
