<?php

declare(strict_types=1);

namespace App\Domain\Exception;


class BadLoanInstallments extends \Exception implements CalculatorException
{

    public function __construct(float $requiredMin, float $requiredMax, float $devidedBy) {
        parent::__construct('Bad loan installments passed, the installments should be between ' . $requiredMin . ' and ' . $requiredMax . ' and need devided by ' . $devidedBy);
    }
}
