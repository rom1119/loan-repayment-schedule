<?php
// src/Entity/LoanCalculation.php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseLoanCalculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected $id;

    #[ORM\Column(type: "float")]
    protected $amount;

    #[ORM\Column(type: "integer")]
    protected $installments;

    #[ORM\Column(type: "float")]
    protected $interestRate;
    
    #[ORM\Column(type: "float")]
    protected $totalInterest;

    #[ORM\Column(type: "text")]
    protected string $schedule = '[]';

    #[ORM\Column(type: "datetime")]
    protected $createdAt;

    #[ORM\Column(type: "boolean")]
    protected $excluded = false;

    public function __construct() {
        $this->schedule = serialize([]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getInstallments(): ?int
    {
        return $this->installments;
    }

    public function setInstallments(int $installments): self
    {
        $this->installments = $installments;

        return $this;
    }

    public function getInterestRate(): ?float
    {
        return $this->interestRate;
    }

    public function setInterestRate(float $interestRate): self
    {
        $this->interestRate = $interestRate;

        return $this;
    }
    
    public function getTotalInterest(): ?float
    {
        return $this->totalInterest;
    }

    public function setTotalInterest(float $totalInterest): self
    {
        $this->totalInterest = $totalInterest;

        return $this;
    }

    public function getSchedule(): array
    {
        return unserialize($this->schedule);
    }

    public function setSchedule(array $schedule): self
    {
        $this->schedule = serialize($schedule);
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isExcluded(): ?bool
    {
        return $this->excluded;
    }

    public function setExcluded(bool $excluded): self
    {
        $this->excluded = $excluded;

        return $this;
    }
}
