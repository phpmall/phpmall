<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReturnedOrderRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ReturnedOrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): ReturnedOrderRepository
    {
        return ReturnedOrderRepository::getInstance();
    }
}
