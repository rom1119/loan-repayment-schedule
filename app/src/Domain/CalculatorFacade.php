<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Calculator\DefaultCalculator;
use App\Domain\Exception\CalculatorException;
use App\Domain\LoanCalculator;
use App\Domain\Model\BaseLoanCalculation;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Model\PaymentRate;

class CalculatorFacade
{

    public function __construct( private CalculatorFactory $calculatorFactory)
    {
    }

    /**
     * @throws CalculatorException
     */
    public function makeCalculation(CreditCalculationRequest $creditCalculationRequest, ?CalculationPersister $persister = null): array
    {
        $data = $this->calculatorFactory->create()->calculateRepaymentSchedule($creditCalculationRequest);
        $interestRate = DefaultCalculator::$INTEREST_RATE; // Fixed interest rate

        $date = new \DateTime();
        if ($persister) {
            $calculation = $persister->persist($creditCalculationRequest, $data, $interestRate);
            $date = $calculation->getCreatedAt();
        }


        $result = [
            'calculation' => [
                'timestamp' => $date->format('Y-m-d H:i:s'),
                'amount' => $creditCalculationRequest->amount(),
                'installments' => $creditCalculationRequest->amountOfInstallemnts(),
                'interest_rate' => $interestRate,
            ],
            'schedule' => $data
        ];

        return $result;
    }
    
    /**
     * @return LoanCalculator
     */
    public function excludeCalculation(CalculationPersister $persister, BaseLoanCalculation $baseLoanCalculation): void
    {
        $persister->excludeCalculation($baseLoanCalculation);
    }


    public function getAllSchedules(CalculationPersister $persister, string $filter = 'all', int $limit = 4): array
    {
        $list = $persister->getAllSchedules($filter, $limit);

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

        return $data;
    }
}
