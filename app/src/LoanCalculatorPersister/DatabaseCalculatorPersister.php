<?php

namespace App\LoanCalculatorPersister;

use App\Domain\CalculationPersister;
use App\Domain\Exception\CalculatorException;
use App\Domain\Model\BaseLoanCalculation;
use App\Domain\Model\CreditCalculationRequest;
use App\Domain\Model\PaymentRate;
use App\Entity\LoanCalculation;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseCalculatorPersister implements CalculationPersister
{

    public function __construct(private EntityManagerInterface $em) {
        
    }

    /**
     * @param array<PaymentRate> $schedules
     * @throws CalculatorException
     */
    public function persist(CreditCalculationRequest $model, array $schedules, float $interestRate): BaseLoanCalculation
    {

        $calculation = new LoanCalculation();
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

        $this->em->persist($calculation);
        $this->em->flush();

        return $calculation;
        
    }

    public function excludeCalculation(BaseLoanCalculation $baseLoanCalculation): void
    {
        if (!($baseLoanCalculation instanceof LoanCalculation)) {
            throw new \InvalidArgumentException();
        }
        
        $baseLoanCalculation->setExcluded(true);
        $this->em->flush();

    }
    public function getAllSchedules(string $filter = 'all', int $limit = 4): array
    {
        return $this->em->getRepository(LoanCalculation::class)->findByFilter($filter, $limit);
    }
}
