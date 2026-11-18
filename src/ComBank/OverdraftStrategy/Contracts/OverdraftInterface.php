<?php namespace ComBank\OverdraftStrategy\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:44 PM
 */

interface OverdraftInterface
{
    // ¿Se puede conceder descubierto con el saldo actual?
    // Recibe el saldo actual (puede ser negativo) y devuelve true/false
    public function isGrantOverdraftFunds(float $currentBalance): bool;

    // ¿Cuánto dinero de descubierto tiene disponible? (ej: 100.0, 0.0)
    public function getOverdraftFundsAmount(): float;

    // Devuelve el porcentaje de interés asociado a la estrategia.
    public function getInterestRate(): int;
}
