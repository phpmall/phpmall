<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductRepository
    {
        return ProductRepository::getInstance();
    }
}
