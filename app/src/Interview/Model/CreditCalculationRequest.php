<?php

declare(strict_types=1);

namespace App\Interview\Model;

use App\Interview\Validation\LoanProposalValidator;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Callback([LoanProposalValidator::class, 'validate'])]
class CreditCalculationRequest
{
    private int $amount;
    private int $amountOfInstallemnts;
    
    public function __construct(int $amount, int $amountOfInstallemnts)
    {
        $this->amount = $amount;
        $this->amountOfInstallemnts = $amountOfInstallemnts;
    }

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     */
    public function amountOfInstallemnts(): int
    {
        return $this->amountOfInstallemnts;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): float
    {
        return $this->amount;
    }

    public function __tostring() {
        return 'LoanProposal(amount=' . $this->amount() . ',amountOfInstallemnts=' . $this->amountOfInstallemnts() . ')';
    }
}
