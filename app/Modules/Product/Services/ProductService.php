<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductRepository $repository,
    ) {}

    public function getRepository(): ProductRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
