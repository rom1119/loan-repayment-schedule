<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Calculator\DefaultCalculator;
use App\Domain\LoanCalculator;
use App\Domain\Validation\LoanProposalValidator;

class CalculatorFactory
{

    private CalculatorLogger $logger;

    public function __construct( CalculatorLogger $logger)
    {
        $this->logger = $logger ;
    }
    /**
     * @return LoanCalculator
     */
    public function create(): LoanCalculator
    {
        return new DefaultCalculator(
            new LoanProposalValidator(), $this->logger
        );
    }
}
