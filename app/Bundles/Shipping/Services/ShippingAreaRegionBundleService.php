<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Services;

use App\Bundles\Shipping\Repositories\ShippingAreaRegionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShippingAreaRegionBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShippingAreaRegionRepository
    {
        return ShippingAreaRegionRepository::getInstance();
    }
}
