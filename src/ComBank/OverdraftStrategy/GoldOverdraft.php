<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

class GoldOverdraft implements OverdraftInterface
{
    // Permite que la cuenta tenga un descubierto máximo de 500 euros.
    private float $overdraftFunds = 500.0;

    public function isGrantOverdraftFunds(float $currentBalance): bool
    {
        // La operación se permite mientras el saldo no sea inferior a -500.
        return $currentBalance >= -$this->overdraftFunds;
    }

    public function getOverdraftFundsAmount(): float
    {
        return $this->overdraftFunds;
    }

    public function getInterestRate(): int
    {
        // Gold ofrece el interés más alto: 10%.
        return 10;
    }
}
