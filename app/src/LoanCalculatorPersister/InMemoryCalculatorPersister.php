<?php

namespace App\LoanCalculatorPersister;

use App\Domain\CalculationPersister;
use App\Domain\Exception\CalculatorException;
use App\Domain\Model\BaseLoanCalculation;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Model\PaymentRate;
use App\Entity\LoanCalculation;

class InMemoryCalculatorPersister implements CalculationPersister
{

    /**
     * @param array<BaseLoanCalculation> $schedules
     */
    private array $data = [];

    /**
     * @param array<PaymentRate> $schedules
     * @throws CalculatorException
     */
    public function persist(CreditCalculationRequest $model, array $schedules, float $interestRate): BaseLoanCalculation
    {

        $calculation = new InMemoryLoanCalculation();
        $id = rand(1000, 9000000);
        $calculation->setId($id);
        $calculation->setAmount($model->amount());
        $calculation->setInstallments($model->amountOfInstallemnts());
        $calculation->setInterestRate($interestRate);
        $totalInterest = 0;
        array_map(function (PaymentRate $el) use (&$totalInterest) {
            $totalInterest += $el->getInterest();
        }, $schedules);

        $calculation->setTotalInterest($totalInterest);
        $calculation->setSchedule($schedules);
        $calculation->setCreatedAt(new \DateTime());

        $this->data[$id] = $calculation;

        return $calculation;
        
    }

    public function excludeCalculation(BaseLoanCalculation $baseLoanCalculation): void
    {
        if (!($baseLoanCalculation instanceof InMemoryLoanCalculation)) {
            throw new \InvalidArgumentException();
        }
        
        $baseLoanCalculation->setExcluded(true);

    }
    public function getById(int $id): BaseLoanCalculation
    {
        return $this->data[$id];
    }

    public function getAllSchedules(string $filter = 'all', int $limit = 4): array
    {
        $sorted = $this->data;
        $this->sortDescTotalInterest($sorted);

        if ($filter == 'all') {
            return array_slice($sorted, 0, $limit);
        }

        $res = [];
        foreach ($sorted as $item) {
            if (count($res) == $limit) {
                break;
            }
            if (!$item->isExcluded()) {
                $res[] = $item;
            }
        }

        
        return $res;
    }

    private function comparator(BaseLoanCalculation $a, BaseLoanCalculation $b): bool
    {
        return  $a->getTotalInterest() < $b->getTotalInterest();
    }
    private function sortDescTotalInterest(array &$breakpoints)
    {
        usort($breakpoints, array($this, "comparator"));
    }
}
