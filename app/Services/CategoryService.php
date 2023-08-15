<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\CategoryRepository;

class CategoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): CategoryRepository
    {
        return CategoryRepository::getInstance();
    }
}
