<?php

declare(strict_types=1);

namespace App\Interview\Exception;


class BadLoanAmount extends \Exception implements CalculatorException
{

    public function __construct(float $requiredMin, float $requiredMax, float $devidedBy) {
        parent::__construct('Bad amount passed, the amount should be between ' . $requiredMin . ' and ' . $requiredMax . ' and need devided by ' . $devidedBy);
    }
}
