<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopErrorLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopErrorLogBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopErrorLogRepository
    {
        return ShopErrorLogRepository::getInstance();
    }
}
