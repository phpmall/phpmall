<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductStockRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ProductStockService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductStockRepository
    {
        return ProductStockRepository::getInstance();
    }
}
