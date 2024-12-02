<?php

declare(strict_types=1);

namespace App\Interview;

use App\Interview\Calculator\DefaultCalculator;
use App\Interview\LoanCalculator;
use App\Interview\Validation\LoanProposalValidator;

class CalculatorFactory
{
    /**
     * @return LoanCalculator
     */
    public static function create(): LoanCalculator
    {
        return new DefaultCalculator(
            new LoanProposalValidator()
        );
    }
}
