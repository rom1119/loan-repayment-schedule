<?php

declare(strict_types=1);

namespace App\Domain\Model;

class PaymentRate implements \Serializable
{
    private float $interest;
    private float $principal;
    private float $payment;
    private int $installment;

    public function __construct(float $interest, float $principal, float $payment, int $installment)
    {
        $this->interest = $interest;
        $this->principal = $principal;
        $this->payment = $payment;
        $this->installment = $installment;
    }

    public function getInterest()
    {
        return $this->interest;
    }

    

    public function serialize()
    {
        return serialize(
            [
                $this->interest,
                $this->principal,
                $this->payment,
                $this->installment,
            ]
        );
    }

    public function unserialize($dataStr)
    {
        list(
            $this->interest,
            $this->principal,
            $this->payment,
            $this->installment,
        ) = unserialize($dataStr);

    }



    /**
     * Get the value of payment
     */ 
    public function getPayment(): float
    {
        return $this->payment;
    }

    /**
     * Get the value of principal
     */ 
    public function getPrincipal(): float
    {
        return $this->principal;
    }
}
