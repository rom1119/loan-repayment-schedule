<?php

declare(strict_types=1);

namespace App\Interview\Calculator;

use App\Interview\FeeCalculator;
use App\Interview\CalculatorLogger;
use App\Interview\Exception\CalculatorException;
use App\Interview\Exception\GeneralCalculatorError;
use App\Interview\Model\CreditCalculationRequest;

class DefaultCalculator implements FeeCalculator
{

    private CalculatorLogger $logger;

    public function __construct() {
        $this->logger = new CalculatorLogger();
    }
    /**
     * @throws CalculatorException
     * @return float The calculated total fee.
     */
    public function calculate(CreditCalculationRequest $application): float
    {
        try {
    
            $this->validateLoanProposal($application, $breakpointsSet);
    
            $this->sortAscByAmount($breakpointsSet);
    
  
            $calcFee = $this->calculateFeeValue(
                $application->amount(),
                $minBreakpoint->amount(),
                $maxBreakpoint->amount(),
                $minBreakpoint->fee(),
                $maxBreakpoint->fee(),
            );
                        
            return $this->roundUpToNearestFive($application->amount(), $calcFee);
            
        } catch (CalculatorException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->logger->logError($e->getMessage(),
        [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stackTrace' => $e->getTraceAsString(),
                'application' => $application,
            ]);
            throw new GeneralCalculatorError('Somethink went wrong with calculator');
        }
    }


    
    private function comparator( $a,  $b): bool 
    {
        return  $a->amount() > $b->amount();
    }
    private function sortAscByAmount(array &$breakpoints)
    {
        usort($breakpoints, array($this, "comparator"));
    }
    
}
