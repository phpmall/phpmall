<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderItemRepository $repository,
    ) {}

    public function getRepository(): OrderItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
