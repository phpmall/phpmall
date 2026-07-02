<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductCategoryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductCategoryService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductCategoryRepository $repository,
    ) {}

    public function getRepository(): ProductCategoryRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
