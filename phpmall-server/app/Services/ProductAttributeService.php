<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductAttributeRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductAttributeRepository
    {
        return ProductAttributeRepository::getInstance();
    }
}
