<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\BankNationalAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\OverdraftStrategy\GoldOverdraft;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Transactions\InterestTransaction;
use ComBank\Transactions\TransferTransaction;
use ComBank\Transactions\FeeTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
// $usuario = new Usuario("María", "maria@ejemplo.com");
$bankAccount1 = new BankAccount(400);
$bankAccount1->setEmail('johan@ejemplo.com');
pl('Email: ' . $bankAccount1->getEmail());

pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    pl($bankAccount1->getBalance());
    // close account
    $bankAccount1->closeAccount();
    pl('Is my account closed? ' . ($bankAccount1->isOpen() ? 'no' : 'yes'));
    // reopen account
    $bankAccount1->reopenAccount();
    pl('Is my account open? ' . ($bankAccount1->isOpen() ? 'yes' : 'no'));

pl('Initial balance: ' . $bankAccount1->getBalance());

$bankAccount1->transaction(new FeeTransaction(15));

pl('Balance after fee: ' . $bankAccount1->getBalance());


    // deposit +150
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(25));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(600));

} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());




//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');

$bankAccount2 = new BankAccount(200);
try {
    $bankAccount2->applyOverdraft(new SilverOverdraft());


    // show balance account
    pl($bankAccount2->getBalance());

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100));
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(20));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

try {
    $bankAccount2->closeAccount();
    $bankAccount2->transaction(new DepositTransaction(10));

} catch (BankAccountException $e) {
    pl($e->getMessage());
}


//---[Interest Transaction]---/
pl('--------- [Interest transaction] --------');

// NoOverdraft: 1% interest
$accountInterest1 = new BankAccount(1000);
$accountInterest1->applyOverdraft(new NoOverdraft());
pl('Balance before interest (NoOverdraft 1%): ' . $accountInterest1->getBalance());
$accountInterest1->transaction(new InterestTransaction());
pl('Balance after interest (NoOverdraft 1%): ' . $accountInterest1->getBalance());

// SilverOverdraft: 5% interest
$accountInterest2 = new BankAccount(1000);
$accountInterest2->applyOverdraft(new SilverOverdraft());
pl('Balance before interest (SilverOverdraft 5%): ' . $accountInterest2->getBalance());
$accountInterest2->transaction(new InterestTransaction());
pl('Balance after interest (SilverOverdraft 5%): ' . $accountInterest2->getBalance());

// GoldOverdraft: 10% interest
$accountInterest3 = new BankAccount(1000);
$accountInterest3->applyOverdraft(new GoldOverdraft());
pl('Balance before interest (GoldOverdraft 10%): ' . $accountInterest3->getBalance());
$accountInterest3->transaction(new InterestTransaction());
pl('Balance after interest (GoldOverdraft 10%): ' . $accountInterest3->getBalance());


//---[Transfer Transaction]---/
pl('--------- [Transfer transaction] --------');

// Successful transfer
$source      = new BankAccount(500);
$destination = new BankAccount(200);
pl('Source balance before transfer: '      . $source->getBalance());
pl('Destination balance before transfer: ' . $destination->getBalance());
$source->transaction(new TransferTransaction(100, $destination));
pl('Source balance after transfer (-100): '      . $source->getBalance());
pl('Destination balance after transfer (+100): ' . $destination->getBalance());

// Failed transfer — insufficient funds
try {
    $source2      = new BankAccount(50);
    $destination2 = new BankAccount(200);
    pl('Trying to transfer 100 from account with balance 50 (no overdraft)...');
    $source2->transaction(new TransferTransaction(100, $destination2));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}

pl('--------- [BankNationalAccount] --------');

pl('--------- SAMPLE 1 --------');
$bankNationalAccount1 = new BankNationalAccount(500, "€");

pl('Currency: ' . $bankNationalAccount1->getCurrency());
pl('Balance: ' . $bankNationalAccount1->getBalance());
pl('Formatted balance: ' . $bankNationalAccount1->getFormattedBalance() . $bankNationalAccount1->getBalance());

pl('--------- [SAMPLE 2] --------');

$bankNationalAccount2 = new BankNationalAccount(500, "@");

pl('Currency: ' . $bankNationalAccount2->getCurrency());
pl('Balance: ' . $bankNationalAccount2->getBalance());
pl('Formatted balance: ' . $bankNationalAccount2->getFormattedBalance() . $bankNationalAccount2->getBalance());


