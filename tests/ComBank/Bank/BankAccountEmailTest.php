<?php

use PHPUnit\Framework\TestCase;
use ComBank\Bank\BankAccount;
use ComBank\Exceptions\InvalidArgsException;

class BankAccountEmailTest extends TestCase
{
    public function testValidEmailIsStored(): void
    {
        $account = new BankAccount(100.0, 'john.smith@example.com');

        $this->assertSame('john.smith@example.com', $account->getEmail());
    }

    public function testInvalidEmailIsRejected(): void
    {
        $this->expectException(InvalidArgsException::class);
        new BankAccount(100.0, 'johnexample.com');
    }
}
