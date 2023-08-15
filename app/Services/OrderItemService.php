<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderItemRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class OrderItemService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderItemRepository
    {
        return OrderItemRepository::getInstance();
    }
}
