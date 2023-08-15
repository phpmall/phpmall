<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductStockRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ProductStockService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductStockRepository
    {
        return ProductStockRepository::getInstance();
    }
}
