<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderGoodsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderGoodsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderGoodsRepository
    {
        return OrderGoodsRepository::getInstance();
    }
}
