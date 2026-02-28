<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopAutoManageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopAutoManageBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopAutoManageRepository
    {
        return ShopAutoManageRepository::getInstance();
    }
}
