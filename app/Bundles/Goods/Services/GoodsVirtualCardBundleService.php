<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsVirtualCardRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsVirtualCardBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsVirtualCardRepository
    {
        return GoodsVirtualCardRepository::getInstance();
    }
}
