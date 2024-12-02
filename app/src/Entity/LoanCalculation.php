<?php
// src/Entity/LoanCalculation.php

namespace App\Entity;

use App\Domain\Model\BaseLoanCalculation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\\Repository\\LoanCalculationRepository")]
class LoanCalculation extends BaseLoanCalculation
{
   
}
