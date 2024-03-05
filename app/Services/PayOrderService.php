<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\PayOrderRepository;

class PayOrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): PayOrderRepository
    {
        return PayOrderRepository::getInstance();
    }
}
