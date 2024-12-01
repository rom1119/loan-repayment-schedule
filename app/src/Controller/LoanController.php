<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\LoanCalculation;
use App\Interview\Calculator\DefaultCalculator;
use App\Interview\CalculatorFactory;
use App\Interview\Model\CreditCalculationRequest;
use App\Interview\Model\PaymentRate;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class LoanController extends AbstractFOSRestController
{

    public function __construct(
        private CalculatorFactory $calculatorFactory,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/api/calculate', methods:'POST')]
    public function postScheduleCalculationAction( 
        #[MapRequestPayload(
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )]CreditCalculationRequest $loanProposal,
        Request $request
        )
    {
        $data = [];
        $schedule = $this->calculatorFactory->create()->calculateRepaymentSchedule($loanProposal);
        $interestRate = DefaultCalculator::$INTEREST_RATE; // Fixed interest rate

        $calculation = new LoanCalculation();
        $calculation->setAmount($loanProposal->amount());
        $calculation->setInstallments($loanProposal->amountOfInstallemnts());
        $calculation->setInterestRate($interestRate);
        $totalInterest = 0;
        array_map(function (PaymentRate $el) use (&$totalInterest) {
            $totalInterest += $el->getInterest();
        }, $schedule);

        $calculation->setTotalInterest($totalInterest);
        $calculation->setSchedule($schedule);
        $calculation->setCreatedAt(new \DateTime());

        $this->em->persist($calculation);
        $this->em->flush();

        
        $view = $this->view([
            'calculation' => [
                'timestamp' => $calculation->getCreatedAt()->format('Y-m-d H:i:s'),
                'amount' => $loanProposal->amount(),
                'installments' => $loanProposal->amountOfInstallemnts(),
                'interest_rate' => $interestRate,
            ],
            'schedule' => $schedule
        ], 200);
        return $this->handleView($view);
    }

    #[Route('/api/calculation/exclude/{id}', name: 'exclude_calculation', methods: ['DELETE'])]
    public function excludeCalculation(LoanCalculation $calculation): JsonResponse
    {
        $calculation->setExcluded(true);
        $this->em->flush();

        return new JsonResponse(['message' => 'Calculation excluded']);
    }


    #[Route('/api/calculations', name: 'list_calculations', methods: ['GET'])]
    public function getAllSchedulesAction( 
        Request $request
        )
    {
        $filter = $request->query->get('filter', 'all');
        $limit = 4;
        $list = $this->em->getRepository(LoanCalculation::class)->findByFilter($filter, $limit);

        $data = [];

        foreach ($list as $item) {
            $data[] = [
                'id' => $item->getId(),
                'amount' => $item->getAmount(),
                'installments' => $item->getInstallments(),
                'interest_rate' => $item->getInterestRate(),
                'schedule' => $item->getSchedule(),
            ];
        }
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
    
}
