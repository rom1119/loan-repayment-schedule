<?php declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Interview\FeeCalculator;


final class CalculatorTest extends TestCase
{
    private ?FeeCalculator $calculator;

    protected function setUp(): void
    {

    }
    
    protected function tearDown(): void
    {
        $this->calculator = null;
    }

    public function testMainCases(): void
    {
        
    }

    public function testExceptionBadTerm(): void
    {

    }
    

}