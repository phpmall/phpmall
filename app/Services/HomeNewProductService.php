<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\HomeNewProductRepository;

class HomeNewProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeNewProductRepository
    {
        return HomeNewProductRepository::getInstance();
    }
}
