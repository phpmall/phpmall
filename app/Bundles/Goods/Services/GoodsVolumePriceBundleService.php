<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsVolumePriceRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsVolumePriceBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsVolumePriceRepository
    {
        return GoodsVolumePriceRepository::getInstance();
    }
}
