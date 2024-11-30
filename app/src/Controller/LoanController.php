<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interview\CalculatorFactory;
use App\Interview\Model\CreditCalculationRequest;
use App\Interview\Model\LoanProposal;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
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
        )]CreditCalculationRequest $loanProposal
        )
    {
        // dd($loanProposal);
        $data = [];
        $item = $this->calculatorFactory->create()->calculate($loanProposal);
        $view = $this->view($item, 200);

        return $this->handleView($view);
    }
    
}
