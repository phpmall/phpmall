<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\OrderItemRepository;

class OrderItemService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderItemRepository
    {
        return OrderItemRepository::getInstance();
    }
}
