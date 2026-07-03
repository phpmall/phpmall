<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class NotImplementedException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 501, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
