<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsLinkGoodsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsLinkGoodsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsLinkGoodsRepository
    {
        return GoodsLinkGoodsRepository::getInstance();
    }
}
