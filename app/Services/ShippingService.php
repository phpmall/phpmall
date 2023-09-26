<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShippingRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ShippingService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShippingRepository
    {
        return ShippingRepository::getInstance();
    }
}
