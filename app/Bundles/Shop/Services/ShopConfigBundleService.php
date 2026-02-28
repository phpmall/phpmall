<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopConfigRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopConfigBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopConfigRepository
    {
        return ShopConfigRepository::getInstance();
    }
}
