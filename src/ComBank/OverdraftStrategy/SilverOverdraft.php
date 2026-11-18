<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class SilverOverdraft implements OverdraftInterface
{
    // Este es el margen de descubierto permitido: 100 euros
    // Con SilverOverdraft puedes quedarte hasta -100€ en tu cuenta
    private float $overdraftFunds = 100.0;

    // ¿Se puede conceder descubierto?
    // Ejemplo: saldo = -80 → -80 >= -100 → true  (sí, dentro del margen)
    // Ejemplo: saldo = -120 → -120 >= -100 → false (no, se pasa del límite)
    public function isGrantOverdraftFunds(float $currentBalance): bool
    {
        return $currentBalance >= -$this->overdraftFunds;
    }

    // Devuelve cuánto es el margen de descubierto: 100.0
    public function getOverdraftFundsAmount(): float
    {
        return $this->overdraftFunds;
    }

    public function getInterestRate(): int
    {
        // Silver ofrece un interés del 5%.
        return 5;
    }
}
