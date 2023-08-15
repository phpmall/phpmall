<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductTypeRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ProductTypeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductTypeRepository
    {
        return ProductTypeRepository::getInstance();
    }
}
