<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopRegionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopRegionBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopRegionRepository
    {
        return ShopRegionRepository::getInstance();
    }
}
