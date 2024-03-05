<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\ProductRepository;

class ProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductRepository
    {
        return ProductRepository::getInstance();
    }
}
