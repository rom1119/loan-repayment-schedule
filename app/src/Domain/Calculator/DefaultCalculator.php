<?php

declare(strict_types=1);

namespace App\Domain\Calculator;

use App\Domain\LoanCalculator;
use App\Domain\CalculatorLogger;
use App\Domain\Exception\CalculatorException;
use App\Domain\Exception\GeneralCalculatorError;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Model\PaymentRate;
use App\Domain\Validation\LoanProposalValidator;

class DefaultCalculator implements LoanCalculator
{
    public static float $INTEREST_RATE = 0.05;
    private CalculatorLogger $logger;
    private LoanProposalValidator $validator;

    public function __construct(LoanProposalValidator $validator, CalculatorLogger $logger)
    {
        $this->logger = $logger ;
        $this->validator = $validator;
    }
    
    /**
     * @throws CalculatorException
     * @return array<PaymentRate> The calculated total fee.
     */
    public function calculateRepaymentSchedule(CreditCalculationRequest $application): array
    {
        try {
            $this->validator->validate($application);

            $amount = $application->amount();
            $installments = $application->amountOfInstallemnts();
            $interestRate = self::$INTEREST_RATE; // Fixed interest rate

            $monthlyRate = $interestRate / 12;
            $monthlyPayment = $amount * ($monthlyRate * pow(1 + $monthlyRate, $installments)) / (pow(1 + $monthlyRate, $installments) - 1);

            return $this->generateLoanSchedule($application, $monthlyRate, $monthlyPayment);

        } catch (CalculatorException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->logger->logError(
                $e->getMessage(),
                [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stackTrace' => $e->getTraceAsString(),
                'application' => $application,
            ]
            );
            throw new GeneralCalculatorError('Somethink went wrong with calculator');
        }
    }

    private function generateLoanSchedule(CreditCalculationRequest $application, float $monthlyRate, float $monthlyPayment): array {
        $schedule = [];

            $remainingPrincipal = $application->amount();

            for ($i = 1; $i <= $application->amountOfInstallemnts(); $i++) {
                $interest = $remainingPrincipal * $monthlyRate;
                $principal = $monthlyPayment - $interest;
                $remainingPrincipal -= $principal;

                $rate = new PaymentRate(round($interest, 2), round($principal, 2), round($monthlyPayment, 2), $i);

                $schedule[] = $rate;
            }

            return $schedule;
    }

}
