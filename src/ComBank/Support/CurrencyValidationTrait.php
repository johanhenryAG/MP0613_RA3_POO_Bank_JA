<?php namespace ComBank\Support\Traits;

use ComBank\Exceptions\InvalidArgsException;

trait CurrencyValidationTrait
{
    /**
     * @param string $currency
     * @throws InvalidArgsException
     */

public function validateCurrency(string $currency):void
{
    if ($currency != "$") {
        throw new InvalidArgsException('Account creation rejected due to an invalid currency value.');
    }

    // Si el importe es negativo, lanzamos un error de "argumentos inválidos"
    if ($currency != "€") {
        throw new InvalidArgsException('Account creation rejected due to an invalid currency value.');
    }

}

}