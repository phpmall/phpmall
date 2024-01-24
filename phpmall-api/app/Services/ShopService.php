<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ShopService extends CommonService implements ServiceInterface
{
    public function getRepository(): ShopRepository
    {
        return ShopRepository::getInstance();
    }
}
