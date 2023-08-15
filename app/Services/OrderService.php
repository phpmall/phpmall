<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class OrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderRepository
    {
        return OrderRepository::getInstance();
    }
}
