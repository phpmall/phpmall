<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ShopService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopRepository
    {
        return ShopRepository::getInstance();
    }
}
