<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShippingRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ShippingService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShippingRepository
    {
        return ShippingRepository::getInstance();
    }
}
