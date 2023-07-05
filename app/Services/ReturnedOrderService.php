<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReturnedOrderRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ReturnedOrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): ReturnedOrderRepository
    {
        return ReturnedOrderRepository::getInstance();
    }
}
