<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderRepository $repository,
    ) {}

    public function getRepository(): OrderRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
