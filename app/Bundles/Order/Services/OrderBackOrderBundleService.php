<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderBackOrderRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderBackOrderBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderBackOrderRepository
    {
        return OrderBackOrderRepository::getInstance();
    }
}
