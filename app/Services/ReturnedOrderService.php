<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ReturnedOrderRepository;

class ReturnedOrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): ReturnedOrderRepository
    {
        return ReturnedOrderRepository::getInstance();
    }
}
