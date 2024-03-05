<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\OrderRepository;

class OrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderRepository
    {
        return OrderRepository::getInstance();
    }
}
