<?php namespace ComBank\Bank;


use ComBank\Bank\BankAccount;
// use ComBank\Support\Traits\CurrencyValidationTrait;


class BankNationalAccount extends BankAccount
{
    // use CurrencyValidationTrait;
    private string $currency;

    public function __construct(float $balance, string $currency){
       
        parent::__construct($balance);

        $this->currency = $currency;

    }


    public function getCurrency(): string {
        
        return $this->currency;

    }

    public function setCurrency(string $currency): void {

        $this->currency = $currency;

    }

    public function getFormattedBalance(): string {

    return $this->currency;

    }



}