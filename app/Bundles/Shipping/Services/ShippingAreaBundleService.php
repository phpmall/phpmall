<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Services;

use App\Bundles\Shipping\Repositories\ShippingAreaRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShippingAreaBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShippingAreaRepository
    {
        return ShippingAreaRepository::getInstance();
    }
}
