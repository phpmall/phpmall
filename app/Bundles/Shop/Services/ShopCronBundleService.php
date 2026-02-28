<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopCronRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopCronBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopCronRepository
    {
        return ShopCronRepository::getInstance();
    }
}
