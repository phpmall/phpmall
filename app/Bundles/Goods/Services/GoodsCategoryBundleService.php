<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsCategoryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsCategoryBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsCategoryRepository
    {
        return GoodsCategoryRepository::getInstance();
    }
}
