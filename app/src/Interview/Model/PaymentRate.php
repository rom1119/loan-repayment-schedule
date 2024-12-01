<?php

declare(strict_types=1);

namespace App\Interview\Model;


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

    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    public function serialize()
    {
        serialize(
            [
                'interest' => $this->interest,
                'principal' => $this->principal,
                'payment' => $this->payment,
                'installment' => $this->installment,
            ]
        );
    }

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $data The string representation of the object.
     * @return void
     */
    public function unserialize( $dataStr)
    {
        $data = unserialize($dataStr);

        $this->interest = $data->interest;
        $this->principal = $data->principal;
        $this->payment = $data->payment;
        $this->installment = $data->installment;
    }


}
