<?php

declare(strict_types=1);

namespace Juling\Foundation\Exceptions;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\BusinessCodeEnum;

class NotFoundException extends CustomException
{
    public function __construct(EnumMethodInterface|string $e = null, $code = 0, $previous = null)
    {
        if (is_null($e)) {
            $enum = BusinessCodeEnum::DATA_NOT_FOUND;
            $e = $enum->getDescription();
            $code = $enum->getValue();
        }

        parent::__construct($e, $code, $previous);
    }
}