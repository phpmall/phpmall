<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductTypeRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ProductTypeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductTypeRepository
    {
        return ProductTypeRepository::getInstance();
    }
}
