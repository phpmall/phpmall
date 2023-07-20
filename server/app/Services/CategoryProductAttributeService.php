<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryProductAttributeRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class CategoryProductAttributeService extends CommonService implements ServiceInterface
{
    public function getRepository(): CategoryProductAttributeRepository
    {
        return CategoryProductAttributeRepository::getInstance();
    }
}
