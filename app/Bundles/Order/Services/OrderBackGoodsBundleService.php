<?php

declare(strict_types=1);

namespace App\Bundles\Order\Services;

use App\Bundles\Order\Repositories\OrderBackGoodsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderBackGoodsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderBackGoodsRepository
    {
        return OrderBackGoodsRepository::getInstance();
    }
}
