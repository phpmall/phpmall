<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: int
{
    const Ok = 1;

    const Disabled = 2;
}
