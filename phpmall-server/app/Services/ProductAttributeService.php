<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductAttributeRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductAttributeRepository
    {
        return ProductAttributeRepository::getInstance();
    }
}
