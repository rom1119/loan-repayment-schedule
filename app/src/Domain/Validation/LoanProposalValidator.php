<?php

declare(strict_types=1);

namespace App\Domain\Validation;

use App\Domain\Exception\BadLoanAmount;
use App\Domain\Exception\BadLoanInstallments;
use App\Domain\Model\CreditCalculationRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposalValidator
{
    public function validate(CreditCalculationRequest $value)
    {
        if (!in_array($value->amount(), range(1000, 12000, 500))) {
            throw new BadLoanAmount(1000, 12000, 500);

        }

        if (!in_array($value->amountOfInstallemnts(), range(3, 18, 3))) {
            throw new BadLoanInstallments(3, 18, 3);

        }

    }
}
