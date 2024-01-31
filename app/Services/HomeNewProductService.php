<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeNewProductRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class HomeNewProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeNewProductRepository
    {
        return HomeNewProductRepository::getInstance();
    }
}
