<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class CategoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): CategoryRepository
    {
        return CategoryRepository::getInstance();
    }
}
