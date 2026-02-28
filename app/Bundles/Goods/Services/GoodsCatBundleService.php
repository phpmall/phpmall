<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsCatRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsCatBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsCatRepository
    {
        return GoodsCatRepository::getInstance();
    }
}
