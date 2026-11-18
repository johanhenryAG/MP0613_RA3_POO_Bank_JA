<?php namespace ComBank\Bank\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:26 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

interface BankAccountInterface
{
    // Constantes: valores fijos que representan el estado de la cuenta
    // En lugar de escribir 'OPEN' o 'CLOSED' a mano, usamos estas constantes
    const STATUS_OPEN   = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';

    // Realiza una transacción (depósito o retirada) en la cuenta
    // Recibe un objeto que implementa BankTransactionInterface
    public function transaction(BankTransactionInterface $transaction): void;

    // Devuelve true si la cuenta está abierta, false si está cerrada
    public function isOpen(): bool;

    // Reabre una cuenta cerrada
    public function reopenAccount(): void;

    // Cierra una cuenta abierta
    public function closeAccount(): void;

    // Devuelve el saldo actual de la cuenta
    public function getBalance(): float;

    // Devuelve el tipo de descubierto aplicado a la cuenta
    public function getOverdraft(): OverdraftInterface;

    // Aplica un tipo de descubierto a la cuenta
    public function applyOverdraft(OverdraftInterface $overdraft): void;

    // Establece el saldo de la cuenta (lo usan las transacciones internamente)
    public function setBalance(float $balance): void;

    // Devuelve el correo almacenado en la cuenta.
    public function getEmail(): string;

    // Valida y guarda un nuevo correo electrónico.
    public function setEmail(string $email): void;
}
