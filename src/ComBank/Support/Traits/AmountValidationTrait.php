<?php namespace ComBank\Support\Traits;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:35 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

trait AmountValidationTrait
{
    /**
     * @param float $amount
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function validateAmount(float $amount):void
    {
        // Si el importe es 0, lanzamos un error de "importe cero"
        if ($amount == 0) {
            throw new ZeroAmountException('El importe no puede ser cero.');
        }

        // Si el importe es negativo, lanzamos un error de "argumentos inválidos"
        if ($amount < 0) {
            throw new ZeroAmountException('El importe no puede ser negativo.');
        }
    }

    public function validateEmail(string $email): void
    {
        // FILTER_VALIDATE_EMAIL comprueba el formato estándar de una dirección.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgsException('Invalid email format');
        }
    }
}
