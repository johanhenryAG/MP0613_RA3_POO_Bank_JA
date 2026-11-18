<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:24 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Support\Traits\AmountValidationTrait;

abstract class BaseTransaction
{
    // Todas las transacciones pueden usar las validaciones de importes.
    use AmountValidationTrait;

    // Protected permite acceder al importe desde Deposit, Withdraw y otras subclases.
    protected float $amount;

    public function __construct(float $amount)
    {
        // Rechaza importes negativos y también el importe cero.
        $this->validateAmount($amount);
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

}
