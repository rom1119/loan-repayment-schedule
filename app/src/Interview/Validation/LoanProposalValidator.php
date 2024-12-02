<?php

declare(strict_types=1);

namespace App\Interview\Validation;

use App\Interview\Exception\BadLoanAmount;
use App\Interview\Exception\BadLoanInstallments;
use App\Interview\Model\CreditCalculationRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposalValidator
{
    public static function validateStatic(CreditCalculationRequest $value, ExecutionContextInterface $context, mixed $payload): void
    {
        $validator = new self();
        // dd('validation');
        try {
            $validator->validate($value);
        } catch (BadLoanAmount $e) {
            $context->buildViolation('Amount is incorrect should be between 1000 and 12000 and devided by 500')
                ->atPath('amount')
                ->addViolation()
            ;
        } catch (BadLoanInstallments $e) {
        
            $context->buildViolation('Amount is incorrect should be between 1000 and 12000 and devided by 500')
                ->atPath('amountOfInstallemnts')
                ->addViolation()
            ;
        }
    }

    public function validate(CreditCalculationRequest $value) {
        if (!in_array($value->amount(), range(1000, 12000, 500))) {
            throw new BadLoanAmount(1000, 12000, 500);
            
        }

        if (!in_array($value->amountOfInstallemnts(), range(3, 18, 3))) {
            throw new BadLoanInstallments(3, 18, 3);

        }

    }
}
