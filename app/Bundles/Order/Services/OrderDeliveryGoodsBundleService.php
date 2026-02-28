<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderDeliveryGoodsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderDeliveryGoodsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderDeliveryGoodsRepository
    {
        return OrderDeliveryGoodsRepository::getInstance();
    }
}
