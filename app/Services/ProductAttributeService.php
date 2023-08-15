<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductAttributeRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductAttributeRepository
    {
        return ProductAttributeRepository::getInstance();
    }
}
