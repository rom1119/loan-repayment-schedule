<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\CalculatorException;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Model\PaymentRate;

interface LoanCalculator
{
    /**
     * @throws CalculatorException
     * @return array<PaymentRate> The calculated total fee.
     */
    public function calculateRepaymentSchedule(CreditCalculationRequest $application): array;
}
