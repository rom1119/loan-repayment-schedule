<?php

declare(strict_types=1);

namespace App\Interview\Exception;


class GeneralCalculatorError extends \Exception implements CalculatorException
{

    public function __construct(string $msg) {
        parent::__construct($msg);
    }
}
