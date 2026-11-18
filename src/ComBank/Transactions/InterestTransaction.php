<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class InterestTransaction extends BaseTransaction implements BankTransactionInterface
{
    // Permite validar importes, aunque el interés no recibe un importe fijo.
    use AmountValidationTrait;

    public function __construct(float $amount = 0.0)
    {
        // El importe real se calcula después usando el saldo y la estrategia.
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $account): float
    {
        // El tipo de descubierto decide qué porcentaje de interés corresponde.
        $rate = $account->getOverdraft()->getInterestRate();
        // Se calcula el interés como porcentaje del saldo actual.
        $interest = $account->getBalance() * $rate / 100;
        $this->amount = $interest;

        // Se devuelve el saldo final sumando los intereses calculados.
        return $account->getBalance() + $interest;
    }

    public function getTransactionInfo(): string
    {
        return 'INTEREST_TRANSACTION';
    }
}
