<?php

namespace App\Modules\Batching\Exceptions;

use Exception;
use Throwable;

class BatchingException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        $message = "[BATCHING ERROR] - {$message}";

        parent::__construct($message, $code, $previous);
    }
}
