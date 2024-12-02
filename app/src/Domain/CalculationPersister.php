<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\CalculatorException;
use App\Domain\Model\BaseLoanCalculation;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Model\PaymentRate;

interface CalculationPersister
{
    /**
     * @param array<PaymentRate> $schedules
     * @throws CalculatorException
     */
    public function persist(CreditCalculationRequest $model, array $schedules, float $interestRate): BaseLoanCalculation;
    public function getAllSchedules(string $filter = 'all', int $limit = 4): array;

    public function excludeCalculation(BaseLoanCalculation $baseLoanCalculation): void;


    
}

