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
class FeeTransaction extends BaseTransaction implements BankTransactionInterface {  
// public function __construct(float $amount) {

//     parent::__construct($amount);

// }
    public function applyTransaction(BankAccountInterface $bankAccount): float {
        $newBalance = $bankAccount->getBalance() - $this->amount;

        return $newBalance;
    }

        public function getTransactionInfo(): string
    {
        return 'FEE_TRANSACTION';
    }



}
