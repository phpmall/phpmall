<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopPackRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopPackBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopPackRepository
    {
        return ShopPackRepository::getInstance();
    }
}
