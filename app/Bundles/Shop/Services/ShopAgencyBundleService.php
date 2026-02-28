<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Services;

use App\Bundles\Shop\Repositories\ShopAgencyRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShopAgencyBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopAgencyRepository
    {
        return ShopAgencyRepository::getInstance();
    }
}
