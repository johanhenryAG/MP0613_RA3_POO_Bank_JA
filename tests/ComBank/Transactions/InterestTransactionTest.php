<?php

use PHPUnit\Framework\TestCase;
use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\GoldOverdraft;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\InterestTransaction;

class InterestTransactionTest extends TestCase
{
    public function testInterestForNoOverdraft(): void
    {
        $account = new BankAccount(1000.0);
        $account->applyOverdraft(new NoOverdraft());
        $account->transaction(new InterestTransaction());

        $this->assertEqualsWithDelta(1010.0, $account->getBalance(), 0.001);
    }

    public function testInterestForSilverOverdraft(): void
    {
        $account = new BankAccount(1000.0);
        $account->applyOverdraft(new SilverOverdraft());
        $account->transaction(new InterestTransaction());

        $this->assertEqualsWithDelta(1050.0, $account->getBalance(), 0.001);
    }

    public function testInterestForGoldOverdraft(): void
    {
        $account = new BankAccount(1000.0);
        $account->applyOverdraft(new GoldOverdraft());
        $account->transaction(new InterestTransaction());

        $this->assertEqualsWithDelta(1100.0, $account->getBalance(), 0.001);
    }
}
