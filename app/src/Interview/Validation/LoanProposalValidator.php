<?php

declare(strict_types=1);

namespace App\Interview\Validation;

use App\Interview\Model\CreditCalculationRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposalValidator
{
    public static function validate(CreditCalculationRequest $value, ExecutionContextInterface $context, mixed $payload): void
    {

        // dd('validation');
        if (!in_array($value->amount(), range(1000, 12000, 500))) {
            $context->buildViolation('Amount is incorrect should be between 1000 and 12000 and devided by 500')
                ->atPath('amount')
                ->addViolation()
            ;
        }
        
        if (!in_array($value->amountOfInstallemnts(), range(3, 18, 3))) {
            $context->buildViolation('Amount is incorrect should be between 1000 and 12000 and devided by 500')
                ->atPath('amountOfInstallemnts')
                ->addViolation()
            ;
        }
    }
}
