<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{
    // Permite reutilizar las validaciones de importes y correos electrónicos.
    use AmountValidationTrait;

    private float $balance;
    private string $status;
    private OverdraftInterface $overdraft;
    private string $email;

    public function __construct(float $balance, string $email = '')
    {
        // Una cuenta no puede crearse con saldo cero o negativo.
        $this->validateAmount($balance);
        // El correo solo se valida si el constructor recibe uno.
        if ($email !== '') {
            $this->validateEmail($email);
        }

        $this->balance = $balance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
        $this->email = $email;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function isOpen(): bool
    {
        return $this->status === BankAccountInterface::STATUS_OPEN;
    }

    public function closeAccount(): void
    {
        // Cambiar el estado impide que se hagan nuevas transacciones.
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function reopenAccount(): void
    {
        // No tiene sentido reabrir una cuenta que ya está abierta.
        if ($this->isOpen()) {
            throw new BankAccountException('The account is already open');
        }

        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function openAccount(): void
    {
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        // Sustituye la estrategia de descubierto y cálculo de interés.
        $this->overdraft = $overdraft;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        // Se vuelve a validar porque el correo puede modificarse después.
        $this->validateEmail($email);
        $this->email = $email;
    }

    public function transaction(BankTransactionInterface $transaction): void
    {
        // Las cuentas cerradas no permiten depósitos, retiradas ni otras operaciones.
        if (!$this->isOpen()) {
            throw new BankAccountException('Cannot perform a transaction on a closed account');
        }

        try {
            // Cada transacción calcula el nuevo saldo sin modificar directamente la cuenta.
            $newBalance = $transaction->applyTransaction($this);
        } catch (InvalidOverdraftFundsException $exception) {
            // Convertimos el error técnico de fondos insuficientes en un error de transacción.
            throw new FailedTransactionException(
                'Transaction failed: insufficient overdraft funds',
                0,
                $exception
            );
        }
        
        $this->setBalance($newBalance);
    }
}
