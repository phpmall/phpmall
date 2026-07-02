<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductReviewRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductReviewService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductReviewRepository $repository,
    ) {}

    public function getRepository(): ProductReviewRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
