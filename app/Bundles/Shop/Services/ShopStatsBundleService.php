<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopStatsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopStatsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopStatsRepository
    {
        return ShopStatsRepository::getInstance();
    }
}
