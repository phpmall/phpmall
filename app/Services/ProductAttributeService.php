<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ProductAttributeRepository;

class ProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductAttributeRepository
    {
        return ProductAttributeRepository::getInstance();
    }
}
