<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderItemRepository;
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
