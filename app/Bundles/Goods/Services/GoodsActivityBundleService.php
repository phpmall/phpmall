<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsActivityRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsActivityBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsActivityRepository
    {
        return GoodsActivityRepository::getInstance();
    }
}
