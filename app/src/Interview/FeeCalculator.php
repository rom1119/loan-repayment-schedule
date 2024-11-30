<?php

declare(strict_types=1);

namespace App\Interview;

use App\Interview\Exception\CalculatorException;
use App\Interview\Model\CreditCalculationRequest;

interface FeeCalculator
{
    /**
     * @throws CalculatorException
     * @return float The calculated total fee.
     */
    public function calculate(CreditCalculationRequest $application): float;
}
