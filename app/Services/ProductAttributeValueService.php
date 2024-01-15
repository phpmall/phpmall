<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductAttributeValueRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ProductAttributeValueService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductAttributeValueRepository
    {
        return ProductAttributeValueRepository::getInstance();
    }
}
