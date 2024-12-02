<?php
// src/Entity/LoanCalculation.php

namespace App\LoanCalculatorPersister;

use App\Domain\Model\BaseLoanCalculation;

class InMemoryLoanCalculation extends BaseLoanCalculation
{

    public function setId(int $id) {
        $this->id = $id;
    }
   
}
