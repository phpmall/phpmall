<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryProductAttributeRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class CategoryProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): CategoryProductAttributeRepository
    {
        return CategoryProductAttributeRepository::getInstance();
    }
}
