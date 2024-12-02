<?php

declare(strict_types=1);

namespace App\Interview;

use App\Interview\Exception\CalculatorException;
use App\Interview\Model\CreditCalculationRequest;
use App\Interview\Model\PaymentRate;

interface LoanCalculator
{
    /**
     * @throws CalculatorException
     * @return array<PaymentRate> The calculated total fee.
     */
    public function calculateRepaymentSchedule(CreditCalculationRequest $application): array;
}
