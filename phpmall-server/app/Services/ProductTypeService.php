<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductTypeRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ProductTypeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductTypeRepository
    {
        return ProductTypeRepository::getInstance();
    }
}
