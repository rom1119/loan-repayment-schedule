<?php

declare(strict_types=1);

namespace App\Interview\Calculator;

use App\Interview\FeeCalculator;
use App\Interview\CalculatorLogger;
use App\Interview\Exception\CalculatorException;
use App\Interview\Exception\GeneralCalculatorError;
use App\Interview\Model\CreditCalculationRequest;
use App\Interview\Model\PaymentRate;

class DefaultCalculator implements FeeCalculator
{

    private CalculatorLogger $logger;

    public function __construct() {
        $this->logger = new CalculatorLogger();
    }
    /**
     * @throws CalculatorException
     * @return array<PaymentRate> The calculated total fee.
     */
    public function calculateRepaymentSchedule(CreditCalculationRequest $application): array
    {
        try {
    
            $amount = $application->amount();
            $installments = $application->amountOfInstallemnts();
            $interestRate = 0.05; // Fixed interest rate
                        
            $monthlyRate = $interestRate / 12;
            $monthlyPayment = $amount * ($monthlyRate * pow(1 + $monthlyRate, $installments)) / (pow(1 + $monthlyRate, $installments) - 1);
            $schedule = [];

            $remainingPrincipal = $amount;

            for ($i = 1; $i <= $installments; $i++) {
                $interest = $remainingPrincipal * $monthlyRate;
                $principal = $monthlyPayment - $interest;
                $remainingPrincipal -= $principal;

                $rate = new PaymentRate(round($interest, 2), round($principal, 2), round($monthlyPayment, 2), $i);
                $schedule[] = $rate;
            }
    
            return $schedule;

        } catch (CalculatorException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->logger->logError($e->getMessage(),
        [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stackTrace' => $e->getTraceAsString(),
                'application' => $application,
            ]);
            throw new GeneralCalculatorError('Somethink went wrong with calculator');
        }
    }


    
    private function comparator( $a,  $b): bool 
    {
        return  $a->amount() > $b->amount();
    }
    private function sortAscByAmount(array &$breakpoints)
    {
        usort($breakpoints, array($this, "comparator"));
    }
    
}
