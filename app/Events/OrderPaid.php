<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OrderPaid
{
    use Dispatchable;

    public function __construct(
        public readonly int $orderId,
        public readonly array $paymentData,
    ) {}
}
