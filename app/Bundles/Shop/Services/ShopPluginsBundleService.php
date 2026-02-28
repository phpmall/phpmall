<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopPluginsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopPluginsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopPluginsRepository
    {
        return ShopPluginsRepository::getInstance();
    }
}
