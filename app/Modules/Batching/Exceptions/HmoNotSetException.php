<?php

namespace App\Modules\Batching\Exceptions;

use Exception;
use Throwable;

class HmoNotSetException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
