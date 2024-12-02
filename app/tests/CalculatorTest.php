<?php

declare(strict_types=1);

namespace App\Tests;

use App\Interview\Calculator\DefaultCalculator;
use App\Interview\Exception\BadLoanAmount;
use App\Interview\Exception\BadLoanInstallments;
use PHPUnit\Framework\TestCase;
use App\Interview\LoanCalculator;
use App\Interview\Model\CreditCalculationRequest;
use App\Interview\Validation\LoanProposalValidator;

final class CalculatorTest extends TestCase
{
    private ?LoanCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new DefaultCalculator(
            new LoanProposalValidator( )
        );
    }

    protected function tearDown(): void
    {
        $this->calculator = null;
    }

    public function testMainCases(): void
    {

    }

    public function testExceptionUnderRangeInstallments(): void
    {
        $application = new CreditCalculationRequest(1000, 2);

        $this->expectException(BadLoanInstallments::class);
        $this->calculator->calculateRepaymentSchedule($application);
    }
    public function testExceptionNotCorrectDevidedByInstallments(): void
    {
        
        $application = new CreditCalculationRequest(1000, 5);

        $this->expectException(BadLoanInstallments::class);
        $this->calculator->calculateRepaymentSchedule($application);
    }
    public function testExceptionOverRangeInstallments(): void
    {
        $application = new CreditCalculationRequest(1000, 19);

        $this->expectException(BadLoanInstallments::class);
        $this->calculator->calculateRepaymentSchedule($application);
    }

    public function testExceptionUnderRangeAmount(): void
    {
        $application = new CreditCalculationRequest(900, 18);

        $this->expectException(BadLoanAmount::class);
        $this->calculator->calculateRepaymentSchedule($application);
    }
    public function testExceptioNotCorrectDevidedByAmount(): void
    {
        $application = new CreditCalculationRequest(1501, 18);

        $this->expectException(BadLoanAmount::class);
        $this->calculator->calculateRepaymentSchedule($application);
    }
        
    public function testExceptionOverRangeAmount(): void
    {
        $application = new CreditCalculationRequest(12001, 18);

        $this->expectException(BadLoanAmount::class);
        $this->calculator->calculateRepaymentSchedule($application);
    }


}
