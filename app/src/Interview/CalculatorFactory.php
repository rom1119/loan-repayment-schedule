<?php

declare(strict_types=1);

namespace App\Interview;

use App\Interview\Calculator\DefaultCalculator;
use App\Interview\FeeCalculator;

class CalculatorFactory
{
    /**
     * @return FeeCalculator
     */
    public static function create(): FeeCalculator {
        return new DefaultCalculator(
            
        );
    }
}
