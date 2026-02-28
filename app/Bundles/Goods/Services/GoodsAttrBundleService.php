<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsAttrRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsAttrBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsAttrRepository
    {
        return GoodsAttrRepository::getInstance();
    }
}
