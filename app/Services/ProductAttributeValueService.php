<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductAttributeValueRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ProductAttributeValueService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductAttributeValueRepository
    {
        return ProductAttributeValueRepository::getInstance();
    }
}
