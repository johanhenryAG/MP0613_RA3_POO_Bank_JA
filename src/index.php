<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Transactions\InterestTransaction;
use ComBank\Transactions\TransferTransaction;
use ComBank\OverdraftStrategy\GoldOverdraft;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;

require_once 'bootstrap.php';

$bankAccount1 = new BankAccount(400.0, 'john.smith@example.com');
$bankAccount2 = new BankAccount(200.0, 'jane.doe@example.com');
// Esta cuenta permite quedarse con un descubierto máximo de 100 euros.
$bankAccount2->applyOverdraft(new SilverOverdraft());

pl('--------- [Bank account #1] --------');
pl('Initial balance: ' . $bankAccount1->getBalance());
// Los depósitos suman el importe al saldo actual.
$bankAccount1->transaction(new DepositTransaction(150.0));
pl('Balance after deposit: ' . $bankAccount1->getBalance());
// Los retiros restan el importe si la estrategia permite la operación.
$bankAccount1->transaction(new WithdrawTransaction(25.0));
pl('Balance after withdrawal: ' . $bankAccount1->getBalance());

// Gold permite 500 euros de descubierto y calcula intereses al 10%.
$bankAccount1->applyOverdraft(new GoldOverdraft());
$bankAccount1->transaction(new InterestTransaction());
pl('Balance after interest: ' . $bankAccount1->getBalance());

pl('--------- [Transfer] --------');
// Retira el importe de la cuenta origen y lo deposita en la cuenta destino.
$bankAccount1->transaction(new TransferTransaction(100.0, $bankAccount2));
pl('Source balance: ' . $bankAccount1->getBalance());
pl('Destination balance: ' . $bankAccount2->getBalance());
