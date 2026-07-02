<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductSkuRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductSkuService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductSkuRepository $repository,
    ) {}

    public function getRepository(): ProductSkuRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
