<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\CategoryProductAttributeRepository;

class CategoryProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): CategoryProductAttributeRepository
    {
        return CategoryProductAttributeRepository::getInstance();
    }
}
