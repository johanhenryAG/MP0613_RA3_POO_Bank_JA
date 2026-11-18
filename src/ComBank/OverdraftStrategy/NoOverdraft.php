<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

// implements = esta clase "firma el contrato" de OverdraftInterface
// Está obligada a implementar isGrantOverdraftFunds() y getOverdraftFundsAmount()
class NoOverdraft implements OverdraftInterface
{
    // Sin descubierto, solo se permiten retiradas que dejan saldo cero o positivo.
    public function isGrantOverdraftFunds(float $currentBalance): bool
    {
        return $currentBalance >= 0.0;
    }

    // ¿Cuánto descubierto tiene? 0 euros.
    public function getOverdraftFundsAmount(): float
    {
        return 0.0;
    }

    public function getInterestRate(): int
    {
        // Sin descubierto se aplica el interés básico del 1%.
        return 1;
    }
}
