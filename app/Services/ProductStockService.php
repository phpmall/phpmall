<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ProductStockRepository;

class ProductStockService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductStockRepository
    {
        return ProductStockRepository::getInstance();
    }
}
