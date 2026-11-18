<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class TransferTransaction extends BaseTransaction implements BankTransactionInterface
{
    use AmountValidationTrait;

    // Cuenta bancaria que recibirá el dinero.
    private BankAccountInterface $destinationAccount;

    public function __construct(float $amount, BankAccountInterface $destinationAccount)
    {
        // La transferencia debe tener un importe positivo.
        $this->validateAmount($amount);
        parent::__construct($amount);
        // Guardamos la cuenta destino para utilizarla al aplicar la transferencia.
        $this->destinationAccount = $destinationAccount;
    }

    public function applyTransaction(BankAccountInterface $sourceAccount): float
    {
        // Primero calculamos si la cuenta origen puede retirar el importe.
        $withdraw = new WithdrawTransaction($this->amount);
        $sourceNewBalance = $withdraw->applyTransaction($sourceAccount);

        // Después calculamos el nuevo saldo de la cuenta destino y lo guardamos.
        $destinationNewBalance = (new DepositTransaction($this->amount))
            ->applyTransaction($this->destinationAccount);
        $this->destinationAccount->setBalance($destinationNewBalance);

        return $sourceNewBalance;
    }

    public function getTransactionInfo(): string
    {
        return 'TRANSFER_TRANSACTION';
    }

    public function setDestinationAccount(BankAccountInterface $destinationAccount): void
    {
        $this->destinationAccount = $destinationAccount;
    }

    public function getDestinationAccount(): BankAccountInterface
    {
        return $this->destinationAccount;
    }
}
