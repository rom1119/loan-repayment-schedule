<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interview\CalculatorFactory;
use App\Interview\Model\CreditCalculationRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class LoanController extends AbstractFOSRestController
{

    private CalculatorFactory $calculatorFactory;

    public function __construct(CalculatorFactory $calculatorFactory) {
        $this->calculatorFactory = $calculatorFactory;
    }

    #[Route('/api/calculate', methods:'POST')]
    public function getUsersAction( 
        #[MapRequestPayload(
            validationFailedStatusCode: Response::HTTP_BAD_REQUEST
        )]CreditCalculationRequest $loanProposal,
        Request $request
        )
    {
        // dd($loanProposal);
        $data = [];
        $items = $this->calculatorFactory->create()->calculateRepaymentSchedule($loanProposal);

        dd($request->getSession()->remove('schedules'));
        $oldData = $request->getSession()->get('schedules', []);
        $request->getSession()->set('schedules', array_merge($items, $oldData));
        $view = $this->view($items, 200);
        dd($request->getSession()->get('schedules', []));
        return $this->handleView($view);
    }


    #[Route('/api/calculations', name: 'list_calculations', methods: ['GET'])]
    public function getAllSchedulesAction( 
        Request $request
        )
    {
        // dd($loanProposal);
        $data = $request->getSession()->get('schedules', []);

        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
    
}
