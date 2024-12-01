<?php
// src/Entity/LoanCalculation.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\MakerBundle\Str;

#[ORM\Entity(repositoryClass: "App\\Repository\\LoanCalculationRepository")]
class LoanCalculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "float")]
    private $amount;

    #[ORM\Column(type: "integer")]
    private $installments;

    #[ORM\Column(type: "float")]
    private $interestRate;

    #[ORM\Column(type: "text")]
    private string $schedule = '[]';

    #[ORM\Column(type: "datetime")]
    private $createdAt;

    #[ORM\Column(type: "boolean")]
    private $excluded = false;

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
