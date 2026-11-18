<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

// "extends BaseTransaction": recibe amount, constructor y getAmount() gratis.
// "implements BankTransactionInterface": se compromete a cumplir el contrato.
class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{

    // Ahora pedimos la INTERFAZ, no la clase concreta: vale cualquier cuenta
    // que cumpla el contrato de BankAccountInterface.
    public function applyTransaction(BankAccountInterface $account): float
    {
        // $this->amount existe porque lo heredamos de BaseTransaction.
        return $account->getBalance() + $this->amount;
    }

        public function getTransactionInfo(): string
    {
        return 'DEPOSIT_TRANSACTION';
    }


}
