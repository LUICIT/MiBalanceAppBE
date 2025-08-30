<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct(string $message = 'Credenciales inválidas.')
    {
        parent::__construct($message, 401);
    }
}
