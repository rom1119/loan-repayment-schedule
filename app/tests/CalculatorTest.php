<?php

declare(strict_types=1);

namespace App\Tests;


use PHPUnit\Framework\TestCase;
use App\Domain\Exception\BadLoanAmount;
use App\Domain\Calculator\DefaultCalculator;
use App\Domain\CalculatorFacade;
use App\Domain\Exception\BadLoanInstallments;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Validation\LoanProposalValidator;
use App\LoanCalculatorPersister\InMemoryCalculatorPersister;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CalculatorTest extends KernelTestCase
{
    private CalculatorFacade $calculatorFacade;
    // public function __construct(
    //     private CalculatorFacade $calculatorFacade
    //     // private DatabaseCalculatorPersister $persister,
    // ) {
    // }

    protected function setUp(): void
    {
        self::bootKernel();
        $this->calculatorFacade = self::getContainer()->get(CalculatorFacade::class);

    }

    protected function tearDown(): void
    {
    }

    public function testMainCases(): void
    {

        $application = new CreditCalculationRequest(2500, 3);

        $res = $this->calculatorFacade->makeCalculation($application);

        $this->assertSame(3, count($res['schedule']));
        $this->assertSame(10.42,$res['schedule'][0]->getInterest());
        $this->assertSame(829.87,$res['schedule'][0]->getPrincipal());
        $this->assertSame(840.29,$res['schedule'][0]->getPayment());

    }
    
    public function testMainCasesWithExcluding(): void
    {
        $db = new InMemoryCalculatorPersister();
        $req1 = new CreditCalculationRequest(2500, 3);
        $req2 = new CreditCalculationRequest(4000, 9);
        $req3 = new CreditCalculationRequest(5000, 12);
        $req4 = new CreditCalculationRequest(8000, 18);

        $this->calculatorFacade->makeCalculation($req1, $db);
        $this->calculatorFacade->makeCalculation($req2, $db);
        $this->calculatorFacade->makeCalculation($req3, $db);
        $this->calculatorFacade->makeCalculation($req4, $db);
        $availableCalculations = $this->calculatorFacade->getAllSchedules($db);

        $this->assertSame(4, actual: count($availableCalculations));

        $byId = $db->getById($availableCalculations[1]['id']);

        $this->calculatorFacade->excludeCalculation($db, $byId);

        $result = $this->calculatorFacade->getAllSchedules($db, 'not_excluded');

        $this->assertSame(3, actual: count($result));


    }

    public function testExceptionUnderRangeInstallments(): void
    {
        $application = new CreditCalculationRequest(1000, 2);

        $this->expectException(BadLoanInstallments::class);
        $this->calculatorFacade->makeCalculation($application);
    }
    public function testExceptionNotCorrectDevidedByInstallments(): void
    {
        
        $application = new CreditCalculationRequest(1000, 5);

        $this->expectException(BadLoanInstallments::class);
        $this->calculatorFacade->makeCalculation($application);
    }
    public function testExceptionOverRangeInstallments(): void
    {
        $application = new CreditCalculationRequest(1000, 19);

        $this->expectException(BadLoanInstallments::class);
        $this->calculatorFacade->makeCalculation($application);
    }

    public function testExceptionUnderRangeAmount(): void
    {
        $application = new CreditCalculationRequest(900, 18);

        $this->expectException(BadLoanAmount::class);
        $this->calculatorFacade->makeCalculation($application);
    }
    public function testExceptioNotCorrectDevidedByAmount(): void
    {
        $application = new CreditCalculationRequest(1501, 18);

        $this->expectException(BadLoanAmount::class);
        $this->calculatorFacade->makeCalculation($application);
    }
        
    public function testExceptionOverRangeAmount(): void
    {
        $application = new CreditCalculationRequest(12001, 18);

        $this->expectException(BadLoanAmount::class);
        $this->calculatorFacade->makeCalculation($application);
    }


}
