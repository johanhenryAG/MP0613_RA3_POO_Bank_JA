<?php

use PHPUnit\Framework\TestCase;
use ComBank\Bank\BankAccount;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Transactions\TransferTransaction;

class TransferTransactionTest extends TestCase
{
    public function testSuccessfulTransfer(): void
    {
        $source = new BankAccount(500.0);
        $destination = new BankAccount(200.0);
        $source->transaction(new TransferTransaction(100.0, $destination));

        $this->assertEqualsWithDelta(400.0, $source->getBalance(), 0.001);
        $this->assertEqualsWithDelta(300.0, $destination->getBalance(), 0.001);
    }

    public function testRejectedTransferWithInsufficientFunds(): void
    {
        $source = new BankAccount(50.0);
        $destination = new BankAccount(200.0);

        $this->expectException(FailedTransactionException::class);
        $source->transaction(new TransferTransaction(100.0, $destination));
    }
}
