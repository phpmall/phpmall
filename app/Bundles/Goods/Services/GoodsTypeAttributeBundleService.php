<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsTypeAttributeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsTypeAttributeBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsTypeAttributeRepository
    {
        return GoodsTypeAttributeRepository::getInstance();
    }
}
