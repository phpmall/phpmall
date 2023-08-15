<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryProductAttributeRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class CategoryProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): CategoryProductAttributeRepository
    {
        return CategoryProductAttributeRepository::getInstance();
    }
}
