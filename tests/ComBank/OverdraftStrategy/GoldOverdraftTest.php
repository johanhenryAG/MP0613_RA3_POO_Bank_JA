<?php

use PHPUnit\Framework\TestCase;
use ComBank\OverdraftStrategy\GoldOverdraft;

class GoldOverdraftTest extends TestCase
{
    public function testGoldOverdraftFundsAndInterest(): void
    {
        $overdraft = new GoldOverdraft();

        $this->assertTrue($overdraft->isGrantOverdraftFunds(-500.0));
        $this->assertFalse($overdraft->isGrantOverdraftFunds(-501.0));
        $this->assertSame(500.0, $overdraft->getOverdraftFundsAmount());
        $this->assertSame(10, $overdraft->getInterestRate());
    }
}
