<?php namespace ComBank\Transactions\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:29 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;

interface BankTransactionInterface
{
    // Aplica la transacción sobre la cuenta bancaria y devuelve el nuevo saldo
    // Es el método más importante: aquí se hace el cálculo real
    // Calcula cómo quedaría el saldo de la cuenta (sin modificarla).
    public function applyTransaction(BankAccountInterface $bankAccount): float;

    // Devuelve una descripción de la transacción ('Deposit' o 'Withdrawal')
    public function getTransactionInfo(): string;

    // Devuelve el importe de la transacción (ej: 150.0)
    public function getAmount(): float;
}
